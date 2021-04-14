<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex">
    <link href="/css/app.css" rel="stylesheet">
    <title>@yield('title')</title>
</head>
<body>
<div class="container xl mx-auto">
    @yield('content')

    <div class="mt-32 mb-12">
        <a class="underline" href="/">TOP</a>
    </div>
</div>
</body>
</html>
