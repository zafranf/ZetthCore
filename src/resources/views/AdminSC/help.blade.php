@extends('zetthcore::AdminSC.layouts.main')

@section('content2')
  <div class="container-fluid" id="help-section">
    <div class="col-sm-10 col-sm-offset-2">
      <div class="col-sm-3 no-padding">
        <nav id="myScrollspy">
          <ul class="nav">
            {!! generateHelpMenu($data) !!}
          </ul>
        </nav>
      </div>
      <div class="col-sm-9" id="help-content">
        <div class="col-sm-10">
          <p>Di sini anda akan dijelaskan bagaimana cara mengelola situs ini melalui panel admin. Penjelasan pada setiap halaman akan dijabarkan secara mendetail untuk setiap fitur dan bagian-bagian yang ada di halaman tersebut sehingga diharapkan Anda dapat mengelola situs ini secara mandiri.</p>

          {!! generateHelpContent($data) !!}
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/bootstrap/daterangepicker/2.1.24/daterangepicker.css') !!}
  <style>
    h2, .h2 {
      font-size: 32px;
    }
    h3, .h3 {
      font-size: 28px;
    }
    h4, .h4 {
      font-size: 24px;
    }
    h5, .h5 {
      font-size: 20px;
    }
    h6, .h6 {
      font-size: 16px;
    }
    #content-div {
      display:none;
    }
    #help-content {
        font-size: 16px;
    }
    #myScrollspy .nav>li.active>a, #myScrollspy .nav>li.active>a:focus, #myScrollspy .nav>li.active>a:hover {
        border-left: 3px solid coral;
    }
    #myScrollspy .nav>li>a {
        padding: 5px;
        border-left: 3px solid transparent;
    }
    #myScrollspy .nav .nav {
        display: none;
    }
    #myScrollspy .nav>li>a:focus, #myScrollspy .nav>li>a:hover {
        border-left: 3px solid coral;
    }
    .section {
        min-height: 100px;
    }
    .affix {
        top: 80px;
        min-width: 292.5px;
    }
  </style>
@endpush

@push('scripts')

<script>
  $(function(){
      $('body').scrollspy({ 
        target: "#myScrollspy",
        offset: 80
      });

      $('window').on("load", function() { 
        $('body').scrollspy("refresh") 
      });

      $('#myScrollspy ul li a').on('click', function() {
        var scrollPos = $('body>#help-section').find($(this).attr('href')).offset().top;
        $('body,html').animate({
          scrollTop: scrollPos - 70
        }, 0);

        return false;
      });

      var stickyNavTop = $('#myScrollspy').offset().top; 
      var stickyNav = function(){
        var scrollTop = $(window).scrollTop();         
        if (scrollTop > stickyNavTop) { 
          $('#myScrollspy').addClass('affix');
          $('#myScrollspy .nav .nav').hide();
          /*$('#myScrollspy li.active').closest('ul').show();*/
          $('#myScrollspy li.active').find('ul').show();
        } else {
          $('#myScrollspy').removeClass('affix');
        }
      };
      stickyNav();
      $(window).scroll(function() {
        stickyNav();
      });
    });
  </script>
@endpush