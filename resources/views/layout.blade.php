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
        <div class="flex flex-wrap justify-between pt-4 opacity-60">
            <a class="underline" href="/">AIIT 2021 syllabus</a>
            <a href="https://github.com/chiroruxx/aiit-syllabus-2021" rel="noopener" target="_blank">
                <img src="/image/github.png" alt="">
            </a>
        </div>
    </div>
</div>
</body>
</html>
