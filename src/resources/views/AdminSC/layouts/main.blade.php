@include('zetthcore::AdminSC.layouts.header')
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
        <a class="navbar-brand" href="{{ url(app('admin_path') . '/dashboard') }}">
          <img src="{{ _get_image("assets/images/" . app('setting')->logo, url("themes/admin/AdminSC/images/" . (app('setting')->logo ?? 'logo.v2.png'))) }}">
        </a>
      </div>

      <div class="collapse navbar-collapse" id="app-navbar-collapse">
        {{-- Left Side Of Navbar --}}
        {{ generateMenu() }}
        {{-- Right Side Of Navbar --}}
        <ul class="nav navbar-nav navbar-right">
          <li data-toggle="tooltip" title="Kunjungi website" data-placement="bottom"><a href="{{ url('/') }}" target="_blank">{!! app('is_mobile') ? 'Kunjungi website&nbsp;<span class="pull-right"><i class="fa fa-external-link"></span>' : '<i class="fa fa-globe">' !!}</i></a></li>
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
@include('zetthcore::AdminSC.layouts.footer')