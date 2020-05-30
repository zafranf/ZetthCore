@extends('zetthcore::AdminSC.layouts.main')

@section('content2')
  <div class="container-fluid" id="guide-section">
    <div class="col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 no-padding">
      <div class="col-xs-4 col-sm-3 no-padding">
        <nav id="myScrollspy">
          <ul id="affixs" class="nav">
            {!! generateHelpMenu($data) !!}
          </ul>
        </nav>
      </div>
      <div class="col-xs-8 col-sm-9" id="guide-content">
        <p>Di sini Anda akan dijelaskan bagaimana cara mengelola situs ini melalui panel admin. Penjelasan pada setiap halaman akan dijabarkan secara mendetail untuk setiap fitur dan bagian-bagian yang ada di halaman tersebut sehingga diharapkan Anda dapat mengelola situs ini secara mandiri.</p>

        {!! generateHelpContent($data) !!}
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    html ,body {
      /* width: 100%;
      height: 100%;
      margin: 0px;
      padding: 0px; */
      overflow-x: hidden; 
  }
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
    #guide-content {
        font-size: 16px;
        background: white;
    }
    #guide-content .section img {
      border: 1px solid #e5e5e5;
      border-radius: 5px;
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
    /* hide inactive nested list */
    /* https://codepen.io/mhadaily/pen/dpdvwp */
    #myScrollspy .nav ul.nav {
        display: none;           
    }
    /* show active nested list */
    #myScrollspy .nav>.active>ul.nav {
        display: block;           
    }
    .section {
        min-height: 100px;
    }
    #affixs {
        top: 80px;
    }

    #date-range-guide, #btn-refresh-guide {
      background: #fff;
      cursor: pointer;
      padding: 5px 10px;
      border: 1px solid coral;
      color: coral;
      font-size: 12px;
      margin-top:-40px;
    }

    #date-range-guide {
      margin-right: 45px;
      border-top-left-radius: 5px;
      border-bottom-left-radius: 5px;
    }

    #btn-refresh-guide {
      margin-right: 15px;
      border-top-right-radius: 5px;
      border-bottom-right-radius: 5px;
    }

    @media screen and (max-width: 767px) {
      #myScrollspy ul {
        max-width: 100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      #date-range-guide {
        float: unset!important;
        margin-top: 0;
      }
      #date-range-guide b.caret {
        float: right;
        margin-top: 8px;
      }
      #btn-refresh-guide {
        margin-top: -29px;
      }
    }
  </style>
@endpush

@push('scripts')
  <script>
    $(document).ready(function() {
      $('body').scrollspy({
        target: "#myScrollspy",
        offset: 80
      });
      
      $("#affixs").affix({
          offset: {
            top: 80
          }
      });

      $('#myScrollspy ul li a').on('click', function() {
        var scrollPos = $('body>#guide-section').find($(this).attr('href')).offset().top;
        $('body,html').animate({
          scrollTop: scrollPos - 70
        }, 0);

        return false;
      });
    });
  </script>
@endpush