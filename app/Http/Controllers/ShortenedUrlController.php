<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use App\ShortenedUrl;

class ShortenedUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = Request::create('/api/get-top-urls', 'GET');
        $response = app()->handle($request);
        $shortened_links = json_decode($response->getContent());

        return view('shortenedUrls', compact('shortened_links'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['url' => 'required|url']);

        $url = '/api/get-shorten-url/' . $request->url;

        $request = Request::create($url, 'GET');
        $response = app()->handle($request);
        $response = json_decode($response->getContent());

        $urlCodeExists = ShortenedUrl::where('shortened_url', $response->shortened_url)->first();

        if(!is_null($urlCodeExists)) {
            echo "hola";
        }
        else {
            $newShortenedUrl = new ShortenedUrl;
            $newShortenedUrl->url = $request->url;
            $newShortenedUrl->code = $response->code;
            $newShortenedUrl->shortened_url = $response->shortened_url;
            $newShortenedUrl->title = $this->getTitle($request->url);
            $newShortenedUrl->save();
        }

        return redirect('/')->with('success', 'URL ' . $response->url . ' has been shorten to' . $response->shortened_url . 'created successfully!');
    }


    /**
     * Returns the top 100 most frequently accessed URLs.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTopUrls()
    {
        $shortened_links = ShortenedUrl::orderBy('times_visited', 'desc')
                                        ->take(100)
                                        ->get(['url', 'code', 'shortened_url', 'title', 'times_visited']);

        if($shortened_links->count() == 0) {
            return response()->json([
                'error' => 'No shortened urls have been created yet!'
            ]);
        }

        return response()->json($shortened_links);
    }

    /**
     * Receive url and returns shorten version.
     *
     * @return \Illuminate\Http\Response
     */
    public function getShortenUrl($url)
    {
        $urlExists = ShortenedUrl::where('url', $url)->first();

        if($urlExists) {
            $code = $urlExists->code;
            $shortened_url = $urlExists->shortened_url;
        }
        else {
            $code = str_random(6);
            $shortened_url = url('/') . '/' . $code;
        }

        return response()->json([
            'url' => $url,
            'code' => $code,
            'shortened_url' => $shortened_url,
        ]);
    }

    /**
     * Receive url with code and redirect to the original url.
     *
     * @param $code
     * @return \Illuminate\Http\Response
     */
    public function shortUrlLink($code)
    {
        $url = '/api/get-link/' . $code;

        $request = Request::create($url, 'GET');
        $response = app()->handle($request);
        $response = json_decode($response->getContent());

        $urlVisited = ShortenedUrl::where('code', $code)->first();
        $urlVisited->increment('times_visited', 1);
        $urlVisited->save();

        return redirect($response->link);
    }

    /**
     * Receive url and crawls to the return page title.
     *
     * @param $code
     * @return $title
     */
    public function getLink($code)
    {
        $originalUrl = ShortenedUrl::where('code', $code)->first();

        if($originalUrl) {
            return response()->json([
                'link' => $originalUrl->url
            ]);
        }
        else {
            return response()->json([
                'error' => 'There is no shortened url with this code!'
            ]);
        }
    }

    /**
     * Receive url and crawls to the return page title.
     *
     * @param $url
     * @return $title
     */
    public function getTitle($url)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $title = $crawler->filter('title')->first()->text();
        return $title;
    }
}
