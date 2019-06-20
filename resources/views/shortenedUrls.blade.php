<!DOCTYPE html>
<html>
    <head>
        <title>Shortened URL Challenge</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <div class="row m-5">
                <div class="col-sm-12">
                    <h1 class="text-center">Shortened URL Challenge</h1>
                </div>
            </div>
            <div class="row m-5">
                <div class="col-sm-12">
                    <form  method="POST" action="{{ route('shorten.store') }}" class="mx-auto">
                        @csrf
                        <div class="row">
                            <div class="col-9">
                                <input type="url" name="url" class="form-control" id="inputUrl" placeholder="Insert the url to be shorten" required>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary mb-2 text-uppercase">Shorten</button>
                            </div>
                        </div>
                    </form>
                    @if (Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('success') }}
                    </div>
                    @elseif (Session::has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session::get('error') }}
                    </div>
                    @endif
                </div>
            </div>
            @if(!isset($shortened_links->error))
            <div class="row m-5">
                <div class="col-sm-12">
                    <h1 class="text-center mb-3">Top 100 most frequently accessed URLs</h1>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">URL</th>
                                <th scope="col" class="text-center">Shortened URL</th>
                                <th scope="col" class="text-center">Title</th>
                                <th scope="col" class="text-center">Times visited</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shortened_links as $link)
                            <tr>
                                <td class="text-center">{{ $link->url }}</td>
                                <td class="text-center"><a href="{{ route('shorten.url', $link->code) }}" target="_blank">{{ $link->shortened_url }}</a></td>
                                <td class="text-center">{{ $link->title }}</td>
                                <td class="text-center">{{ $link->times_visited }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"/>
    </body>
</html>