<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="{{ getImageLogo(app('site')->icon) }}">

    <style>
      html, body {
        height: 100%;
      }

      body {
        margin: 0;
        padding: 0;
        width: 100%;
        color: #B0BEC5;
        display: table;
        font-weight: 100;
        font-family: 'Lato';
        /*background: url('images/Jackie-Chan-WTF.jpg') bottom left no-repeat;*/
      }

      .container {
        text-align: center;
        display: table-cell;
        vertical-align: middle;
      }

      .content {
        text-align: center;
        display: inline-block;
      }

      .title {
        font-size: 72px;
        margin-bottom: 20px;
      }

      fieldset {
        border-radius: 10px;
        border: 1px solid #ccc;
        text-align: left;
      }

      legend {
        margin-left: 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
        background: #BAB9B9;
        color: #fff;
        font-weight: bold;
        font-size: 1.5em;
        letter-spacing: 10px;
        padding-left: 10px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="content">
        @yield('content')
      </div>
    </div>
  </body>
</html>
