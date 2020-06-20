<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ResearchFish Coding Challenge | Rick Sharp</title>
        <link href="/css/app.css" rel="stylesheet">

    </head>
    <body>
        <div class="container-fluid" id="app">
            <div class="row">
                <div class="col-10 offset-1">
                    <h1 class="mb-5 mt-3">Retrieve a Publication</h1>
                    <div class="card p-3">
                        <div>
                            <publication-search></publication-search>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="/js/app.js"></script>
    </body>
</html>
