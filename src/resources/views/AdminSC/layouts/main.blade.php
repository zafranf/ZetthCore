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
          <img src="{{ getImageLogo() }}">
        </a>
      </div>

      <div class="collapse navbar-collapse" id="app-navbar-collapse">
        {{-- Left Side Of Navbar --}}
        {!! generateMenu('admin', [
          'main' => [
            'wrapper' => [
              'tag' => 'ul',
              'class' => 'nav navbar-nav ',
            ],
            'list' => [
              'tag' => 'li',
              'active' => true,
            ],
            'link' => [
              'tag' => 'a',
            ]
          ],
          'sub' => [
            'parent' => [
              'list' => [
                'class' => 'dropdown'
              ],
              'link' => [
                'class' => 'dropdown-toggle',
                'attributes' => [
                  'data-toggle' => 'dropdown',
                  'role' => 'button'
                ],
                'additional' => [
                  'position' => 'after',
                  'html' => '<span class="pull-right"><span class="caret"></span></span>'
                ]
              ]
            ],
            'wrapper' => [
              'tag' => 'ul',
              'class' => 'dropdown-menu sub-menu',
              'attributes' => [
                'role' => 'menu'
              ]
            ],
            'link' => [
              'active' => true
            ]
          ],
          'sub_level' => [
            'parent' => [
              'list' => [
                'class' => 'dropdown-submenu'
              ],
              'link' => [
                'class' => 'submenu',
                'additional' => [
                  'position' => 'after',
                  'html' => '<span class="pull-right"><span class="fa fa-caret-right"></span></span>'
                ]
              ]
            ],
          ]
        ]) !!}
        {{-- Right Side Of Navbar --}}
        <ul class="nav navbar-nav navbar-right">
          <li data-toggle="tooltip" title="Kunjungi situs" data-placement="bottom">
            <a href="{{ getSiteURL('/') }}" target="_blank">
              {!! app('is_mobile') ? 'Kunjungi situs&nbsp;<span class="pull-right"><i class="fa fa-external-link"></i></span>' : '<i class="fa fa-globe"></i>' !!}
            </a>
          </li>
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
                <li>
                  <a href="{{ route('admin.user.account') }}">
                    <i class="fa fa-btn fa-user"></i> Akun
                  </a>
                </li>
                <li>
                  <a href="{{ url(adminPath() . '/webmail') }}" target="_blank">
                    <i class="fa fa-btn fa-envelope"></i> Email
                  </a>
                </li>
                <li>
                  <a onclick="$('#form-logout').submit();" style="cursor:pointer;">
                    <i class="fa fa-btn fa-sign-out"></i> Keluar
                  </a>
                  <form id="form-logout" method="POST" action="{{ route('admin.logout.post') }}" style="display:none;">
                    @csrf
                  </form>
                </li>
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
    @if ($errors->any() > 0)
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