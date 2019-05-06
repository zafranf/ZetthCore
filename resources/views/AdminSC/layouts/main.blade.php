@include('admin.AdminSC.layouts.header')
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">

        {{-- Collapsed Hamburger --}}
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
          <span class="sr-only">Toggle Navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>

        {{-- Branding Image --}}
        <a class="navbar-brand" href="{{ url($adminPath . '/dashboard') }}">
          <img src="{{ _get_image("/assets/images/" . $apps->logo, '/assets/images/logo.jpg') }}">
        </a>
      </div>

      <div class="collapse navbar-collapse" id="app-navbar-collapse">
        {{-- Left Side Of Navbar --}}
        {{ generateMenu() }}
        {{-- Right Side Of Navbar --}}
        <ul class="nav navbar-nav navbar-right">
          <li data-toggle="tooltip" title="Kunjungi website" data-placement="bottom"><a href="{{ url('/') }}" target="_blank"><i class="fa fa-globe"></i></a></li>
          {{-- <li><a href="{{ url('admin/help') }}" title="Help"><i class="fa fa-question-circle-o"></i></a></li> --}}
          {{-- <li><a href="#" title="Notifications"><i class="fa fa-bell-o"></i></a></li> --}}
          {{-- Authentication Links --}}
          @if (Auth::guest())
            <button type="button" class="btn btn-default navbar-btn btn-login" onclick="location='/login'">Login</button>
          @else
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                {{ Auth::user()->fullname }} 
                <span class="pull-right">
                  <span class="caret"></span>
                </span>
              </a>

              <ul class="dropdown-menu" role="menu">
                <li><a href="{{ url('admin/account') }}"><i class="fa fa-btn fa-user"></i> Account</a></li>
                <li><a href="{{ url('admin/logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a></li>
              </ul>
            </li>
          @endif
        </ul>
      </div>
    </div>

    {{ generateBreadcrumb($breadcrumbs) }}
  </nav>

  <div class="page-header" id="page-header">
    <h1>{{ $page_title }}</h1>
  </div>

  <div id="content-div" class="container-fluid">
    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif
    @if (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            {{ $page_subtitle}} 
            {!! _get_access_buttons($current_url, 'add') !!}
          </div>
          
          @yield('content')
        </div>
      </div>
    </div>
  </div>

  @yield('content2')

  <div id="zetth-modal" class="modal" role="dialog">
    <div class="modal-dialog {{ !$isDesktop ? 'modal-sm' : '' }}">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="copyright">
    <span id="status-server" class="bg-success" title="Server Status">Connected</span> Powered by <a href="https://porisweb.id" target="_blank">Poris Webdev</a>
  </div>
  <script>
    var SITE_URL = '{{ url('/') }}';
    var ADMIN_URL = '{{ url($adminPath) }}';
    var CURRENT_URL = '{{ url($current_url) }}';
    var TOKEN = '{{ csrf_token() }}';
    var CONNECT = true;
    var IS_MOBILE = {{ $isMobile ? 'true' : 'false' }};
  </script>
  {!! _load_js('themes/admin/AdminSC/plugins/jquery/2.2.4/js/jquery.min.js') !!}
  {!! _load_js('themes/admin/AdminSC/plugins/bootstrap/3.3.6/js/bootstrap.min.js') !!}
  {!! _load_js('themes/admin/AdminSC/plugins/sweetalert2/js/sweetalert2.min.js') !!}
  @yield('scripts')
  {!! _load_js('themes/admin/AdminSC/js/app.js') !!}
  <script>_tc();</script>
@include('admin.AdminSC.layouts.footer')