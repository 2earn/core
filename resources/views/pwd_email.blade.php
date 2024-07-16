<!DOCTYPE html>
<html dir="{{config('app.available_locales')[app()->getLocale()]['direction']}}"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="antialiased">
<div>
    <img src="{{Vite::asset('resources/images/2earn.png')}}" sizes="60x40" alt="">
</div>

<span style="margin:2px;font-size: 16px;color: blue;">{{$data}}</span>
<div>
    <span>{{ __('best regards') }} </span>
</div>
<div>
    <span><b><a href="" style="color: blue !important">2Earn.cash</a></b></span>
</div>
</body>
</html>
