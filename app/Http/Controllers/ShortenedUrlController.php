<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as guzzle;
use Goutte\Client;
use App\Http\Controllers\Controller;
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
        $client = new guzzle([
             'base_uri' => env('APP_URL'),
             'defaults' => [
                 'exceptions' => false
             ]
        ]);

        $res = $client->request('GET', 'api/get-top-urls');
        $shortened_links = json_decode($res->getBody());

        return view('shortenedUrls', ["shortened_links"=>$shortened_links]);
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

        $client = new guzzle([
            'base_uri' => env('APP_URL'),
            'defaults' => [
                'exceptions' => false
            ]
        ]);
        $res = $client->request('GET', $url);
        $response = json_decode($res->getBody());

        $urlCodeExists = ShortenedUrl::where('shortened_url', $response->shortened_url)->first();

        if(!is_null($urlCodeExists)) {
            return redirect('/')->with('error', 'The url has already been shortened, try another url!');
        }
        else {
            $newShortenedUrl = new ShortenedUrl;
            $newShortenedUrl->url = $request->url;
            $newShortenedUrl->code = $response->code;
            $newShortenedUrl->shortened_url = $response->shortened_url;
            $newShortenedUrl->title = $this->getTitle($request->url);
            $newShortenedUrl->save();

            return redirect('/')->with('success', 'URL ' . $response->url . ' has been shorten to ' . $response->shortened_url . ' successfully!');
        }
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
