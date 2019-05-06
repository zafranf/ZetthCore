<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">

    <title>Forgot Password - {{ Session::get('app')->config->config_name }}</title>
    <link rel="shortcut icon" href="{{ url('assets/images/'.Session::get('app')->config->config_icon) }}">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css?family=Lato:100,200,300,400,500" rel='stylesheet' type='text/css'>
    {!! _load_fontawesome() !!}

    {{-- Styles --}}
    {!! _load_bootstrap('css') !!}
    <link href="{{ url('themes/admin/adminsc/css/adminsc.css')."?".strtotime(date('Y-m-d H:i:s')) }}" rel="stylesheet">
    <style>
        .panel-body img {
            margin: 20px 0;
        }
        .copyright {
            padding-left: 10px;
        }
    </style>
</head>
{{-- {{ dd([session(),$errors]) }} --}}
<body id="app-layout">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <!-- <div class="panel-heading">Login Form</div> -->
                    <div class="panel-body">
                        <center><img src="{{ url('assets/images/n_'.Session::get('app')->config->config_logo) }}"></center>
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                            {!! csrf_field() !!} 
                            @if(session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Email Address</label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="user_email" value="{{ old('user_email') }}"> 
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span> 
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fa fa-btn fa-envelope"></i>Reset Password
                                    </button>
                                    <a class="btn btn-default" href="{{ url('/admin/login') }}">Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        Powered by <a href="http://poris.web.id" target="_blank">Poris Webdev</a> 
    </div>
</body>
</html>
