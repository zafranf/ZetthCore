<!DOCTYPE html>
<html>
    <head>
        <title>Page Unavailable - {{ env('APP_NAME') }}</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link rel="shortcut icon" href="{{ _get_image("assets/images/" . app('site')->icon, _url("themes/admin/AdminSC/images/" . (app('site')->logo ?? 'logo.v2.png'))) }}">

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
                margin-bottom: 40px;
            }

            fieldset {
                border-radius: 10px;
                border: 1px solid #CCC;
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
                <fieldset>
                    <legend>503</legend>
                    <div class="title">Service Unavailable.</div>
                </fieldset>
            </div>
        </div>
    </body>
</html>
