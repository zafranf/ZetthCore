@include('admin.layouts.header')
  <div class="page-main">
    <div class="header py-4">
      <div class="container">
        <div class="d-flex">
          <a class="header-brand" href="{{ url('/dashboard') }}">
            <img src="" class="header-brand-img" alt="{{ $apps->name }}">
          </a>
          <div class="d-flex order-lg-2 ml-auto">
            <div class="dropdown">
              <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                <span class="avatar" style="background-image: url()"></span>
                <span class="ml-2 d-none d-lg-block">
                  <span class="text-default">{{ Auth::user()->name }}</span>
                  <small class="text-muted d-block mt-1">{{ Auth::user()->fullname }}</small>
                </span>
              </a>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <a class="dropdown-item" href="#">
                  <i class="dropdown-icon fe fe-user"></i> Ubah Profil
                </a>
                <a class="dropdown-item" href="{{ url('/logout') }}">
                  <i class="dropdown-icon fe fe-log-out"></i> Keluar
                </a>
              </div>
            </div>
          </div>
          <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
            <span class="header-toggler-icon"></span>
          </a>
        </div>
      </div>
    </div>
    <div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg order-lg-first">
            <!-- Static navbar -->
            <nav class="nav border-0 nav-tabs navbar-expand-md">
              <div class="navbar-collapse" id="navbarNavDropdown">
                {{ generateMenu() }}
              </div>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="my-3 my-md-5">
      <div class="container">
        @if (session('success'))
          <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert"></button>
            {{ session('success') }}
          </div>
        @endif
        @if (count($errors)>0)
          <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert"></button>
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <div class="page-header">
          <h1 class="page-title">
            {{ $page_title }}
          </h1>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ $page_subtitle }}</h3>
                
                <div class="text-right">
                  @yield('menu-sort')
                  {!! _get_access_buttons($current_url, 'add') !!}
                </div>
              </div>
              @yield('content')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@include('admin.layouts.footer')