<!doctype html>
<html lang="en" dir="ltr">
  <head>
    {{-- <base href="{{ url('/') }}"> --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en" />
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <link rel="icon" href="{{ _get_image('/images/' . $apps->logo) }}" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="{{ _get_image('/images/' . $apps->logo) }}" />
    <title>{{ $page_title }} | {{ $apps->name }}</title>
    {!! _load_css('/admin/css/font-awesome.min.css') !!}
    {!! _load_css('/admin/css/sourcesanspro.css') !!}
    {{-- Dashboard Core --}}
    {!! _load_css('/admin/css/dashboard.css') !!}
    {!! _load_css('/admin/css/navbar.css') !!}
    @yield('css')
  </head>
  <body class="">
    <div class="page">