@php 
$page_title = 'Masuk Aplikasi';
@endphp
@include('admin.AdminSC.layouts.header')
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <!-- <div class="panel-heading">Login Form</div> -->
          <div class="panel-body">
            @php
              $w = $isMobile ? 150 : 250;
            @endphp
            <center>
              <img src="{{ _get_image("/assets/images/" . $apps->logo) }}" alt="{{ $apps->name }} Logo" style="margin-bottom: 20px; width: {{ $w }}px;">
            </center>
            <form class="form-horizontal" role="form" method="POST" action="{{ url($adminPath . '/login') }}">
              
              <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Pengguna</label>
                <div class="col-md-6">
                  <input type="name" class="form-control" name="name" value="{{ old('name') }}" placeholder="Nama atau surel.." autofocus>
                  @if ($errors->has('name'))
                    <span class="help-block">
                      <strong>{{ $errors->first('name') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Sandi</label>
                <div class="col-md-6">
                  <input type="password" class="form-control" name="password" placeholder="Kata sandi..">
                  @if ($errors->has('password'))
                    <span class="help-block">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              {{-- <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="remember"> Remember Me
                    </label>
                  </div>
                </div>
              </div> --}}

              <div class="form-group">
                <div class="col-md-7 col-md-offset-4">
                  <button type="submit" class="btn btn-default">
                    <i class="fa fa-btn fa-sign-in"></i> Masuk
                  </button>
                  <a class="btn btn-link" href="{{ url($adminPath . '/password/reset') }}">Lupa sandi?</a>
                  {!! csrf_field() !!}
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="copyright" style="padding-left: 10px;">
    Powered by <a href="https://porisweb.id" target="_blank">Poris Webdev</a>
  </div>
@include('admin.AdminSC.layouts.footer')