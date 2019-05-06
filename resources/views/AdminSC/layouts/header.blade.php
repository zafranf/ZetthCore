<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $page_title }} | {{ $apps->name }}</title>

  <link rel="icon" type="image/x-icon" href="{{ _get_image("/assets/images/" . $apps->icon, "/assets/images/logo.jpg") }}" />

  {{-- Fonts --}}
  <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400" rel='stylesheet' type='text/css'>
  {!! _load_css('themes/admin/AdminSC/plugins/fontawesome/4.6.3/css/font-awesome.min.css') !!}

  {{-- Styles --}}
  {!! _load_css('themes/admin/AdminSC/plugins/bootstrap/3.3.6/css/bootstrap.min.css') !!}
  {!! _load_css('themes/admin/AdminSC/plugins/sweetalert2/css/sweetalert2.min.css') !!}
  @yield('styles')
  {!! _load_css('themes/admin/AdminSC/css/app.css') !!}

</head>

<body id="app-layout">