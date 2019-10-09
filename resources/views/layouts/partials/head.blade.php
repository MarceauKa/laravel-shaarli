<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@if(isset($page_title)){{ $page_title }} - @endif{{ app('shaarli')->getName() }}</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
@if(auth()->check())
    <meta name="api-token" content="{{ auth()->user()->api_token }}">
@endif
@stack('meta')
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="{{ mix('css/app.css') }}" rel="stylesheet">
@stack('css')
@if(app('shaarli')->getCustomBackgroundEncoded())
<style>
    body,
    body.dark {
        background-image: url('{{ app('shaarli')->getCustomBackgroundEncoded() }}') !important;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
    }
</style>
@endif
