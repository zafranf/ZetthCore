@extends('zetthcore::AdminSC.layouts.main')

@section('content2')
  <div class="container-fluid">
    <div class="row">
      <div id="btn-quick-box" class="col-sm-12">
        @if (Auth::user()->isAbleTo('admin.content.posts.create'))
        <div class="btn btn-default btn-quick">
          <a href="{{ _url(adminPath() . '/content/posts/create') }}">
            <div class="row">
              <div class="col-sm-12" title="Buat artikel baru">
                <i class="fa fa-edit"></i>
                <div class="text">
                  Buat Artikel
                </div>
              </div>
            </div>
          </a>
        </div>
        @endif
        @if (Auth::user()->isAbleTo('admin.content.pages.create'))
        <div class="btn btn-default btn-quick">
          <a href="{{ _url(adminPath() . '/content/pages/create') }}">
            <div class="row">
              <div class="col-sm-12" title="Buat halaman baru">
                <i class="fa fa-file-o"></i>
                <div class="text">
                  Buat Halaman
                </div>
              </div>
            </div>
          </a>
        </div>
        @endif
        @if (Auth::user()->isAbleTo('admin.report.comments.index'))
        <div class="btn btn-default btn-quick">
          <a href="{{ _url(adminPath() . '/report/comments') }}">
            <div class="row">
              <div class="col-sm-12" title="Lihat komentar terbaru">
                <i class="fa fa-comment-o"></i>
                <div class="text">
                  Komentar
                </div>
                <span class="btn btn-warning btn-xs btn-xs-top-right no-border-top-right" style="top:-6px;right:3px;"><b>{{ $comment->unread ?? 0 }}</b></span>
              </div>
            </div>
          </a>
        </div>
        @endif
        @if (Auth::user()->isAbleTo('admin.report.inbox.index'))
        <div class="btn btn-default btn-quick">
          <a href="{{ _url(adminPath() . '/report/inbox') }}">
            <div class="row">
              <div class="col-sm-12" title="Lihat pesan masuk">
                <i class="fa fa-envelope-o"></i>
                <div class="text">
                  Pesan Masuk
                </div>
                <span class="btn btn-warning btn-xs btn-xs-top-right no-border-top-right" style="top:-6px;right:3px;"><b>{{ $message->unread ?? 0 }}</b></span>
              </div>
            </div>
          </a>
        </div>
        @endif
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-sm-12" id="box-pageview-chart">
        <div class="loading">Memuat<img src="{{ _url(adminPath() . '/themes/admin/AdminSC/images/loading-flat.gif') }}"></div>
        <div id="pageview-chart" style="height:370px;"></div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-sm-12 col-md-6" id="box-popular-post">
          <div class="panel panel-default">
            <div class="panel-heading">
              Artikel Populer
              @if (Auth::user()->isAbleTo('admin.content.pages.index'))
              <a href="{{ _url(adminPath() . '/content/posts') }}" class="btn btn-default btn-sm pull-right"><i class="fa fa-eye"></i> Semua</a>
              @endif
            </div>
            <div class="panel-body no-padding">
              <div class="loading">Memuat<img src="{{ _url(adminPath() . '/themes/admin/AdminSC/images/loading-flat.gif') }}"></div>
              <table id="table-data-popular" class="table table-hover no-margin-bottom">
                <thead>
                  <tr>
                    <td><b>Artikel</b></td>
                    <td width="80"><b>Dilihat</b></td>
                  </tr>
                </thead>
                <tbody id="popular-post">
                </tbody>
              </table>
            </div>
          </div>
      </div>
      <div class="col-sm-12 col-md-6" id="box-recent-comment">
        <div class="panel panel-default">
          <div class="panel-heading">
            Komentar Terbaru
            @if (Auth::user()->isAbleTo('admin.report.comments.index'))
            <a href="{{ _url(adminPath() . '/report/comments') }}" class="btn btn-default btn-sm pull-right"><i class="fa fa-eye"></i> Semua</a>
            @endif
          </div>
          <div class="panel-body no-padding">
            <div class="loading">Memuat<img src="{{ _url(adminPath() . '/themes/admin/AdminSC/images/loading-flat.gif') }}"></div>
            <table id="table-data-comment" class="table table-hover no-margin-bottom">
              <thead>
                <tr>
                  <td><b>Komentar</b></td>
                  <td width="120"><b>Pengirim</b></td>
                </tr>
              </thead>
              <tbody id="recent-comment">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/bootstrap/daterangepicker/2.1.24/daterangepicker.css') !!}
  <style>
    #content-div {
      display:none;
    }
    .ranges li {
      color: coral;
    }
    .ranges li:hover, .ranges li.active {
      background: coral;
      border-color: coral;
    }
    .daterangepicker td.in-range {
      background-color: #FBF4E8;
    }
    .daterangepicker td.active, .daterangepicker td.active:hover {
      background-color: coral;
    }
    .daterangepicker.dropdown-menu {
      top: 122px!important;
    }
    #btn-quick-box {
      white-space: nowrap;
      overflow-x: scroll;
    }
    .btn-quick {
      font-size: 24px;
      min-width: 145px;
      margin-top: 3px;
      margin-left: 3px;
    }
    .btn-quick i {
      font-size: 54px;
    }
    .panel-heading {
      font-size: 20px!important;
      padding: 5px 15px;
    }
    tbody tr td {
      padding: 0 8px!important;
    }
    .page-header {
      margin-top: 20px;
      margin-bottom: 3px;
      padding-bottom: 0;
    }
    .loading {
      padding-left: 10px;
    }
    @media (max-width: 768px) {
      .btn-quick {
        font-size: inherit;
        min-width: 32%;
      }
      .btn-quick i {
        font-size: inherit;
      }
    }
  </style>
@endpush

@push('scripts')
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/moment/2.13.0/js/moment.min.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/bootstrap/daterangepicker/2.1.24/daterangepicker.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/highcharts/4.2.6/highcharts.js') !!}
  <script>
  $(function(){
    moment.locale('id', {
      days: [
        'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
      ],
      week: {
        dow: 1,
      },
      months: [
      'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ]
    });
    var today = moment();
    var start = moment();
    var end = moment();
    var min = moment('{{ app('site')->created_at }}');
    var max = moment();
    var yr = moment();
    var mn = moment();
    var dt = moment();
    var intHour = 1;
    var intDay = 24;
    var label = (typeof label != "undefined") ? label : 'Hari ini';

    /* generate date range box */
    var html = '<div id="z-date-range" class="pull-right" style="background:#fff;cursor:pointer; padding:5px 10px;border:1px solid coral;margin-top:-40px;margin-right:45px;border-top-left-radius:5px;border-bottom-left-radius:5px;color:coral;">'+
              '<i class="fa fa-calendar"></i>&nbsp;'+
              '<span></span> '+
              '<b class="caret"></b>'+
          '</div>'+
              '<div id="btn-refresh" title="Segarkan" class="pull-right" style="background:#fff;cursor:pointer;padding:5px 10px;border:1px solid coral;margin-top:-40px;margin-right:15px;border-top-right-radius:5px;border-bottom-right-radius:5px;color:coral;">'+
                  '<i class="fa fa-refresh"></i>'+
              '</div>&nbsp;';
    $('#page-header').append(html);

      /* updating combobox */
    function combobox(start_date, end_date, label) {
      start = start_date;
      end = end_date;
      yr = start_date.format('YYYY');
      mn = start_date.format('MM')-1;
      dt = start_date.format('DD');
      @if (app('is_desktop'))
        var range = start_date.format('DD MMMM YYYY') + ' - ' + end_date.format('DD MMMM YYYY');
      @else
        var range = label;
      @endif
      $('#z-date-range span').text(range);

      if (start_date.format('YYYY-MM-DD') == end_date.format('YYYY-MM-DD')) {
        rangetype = 'hourly';
      } else if (start_date.format('YYYY') != end_date.format('YYYY')){
        rangetype = 'yearly';
      } else if (start_date.format('YYYY-MM') != end_date.format('YYYY-MM')){
        rangetype = 'monthly';
      } else {
        rangetype = 'daily';
      }

      /* $('#pageview-chart').html("Memuat<img src=\"{{ _url(adminPath() . '/themes/admin/AdminSC/images/loading-flat.gif') }}\">");
      $('#box-popular-post').addClass('hide');
      $('#box-recent-comment').addClass('hide'); */
      $('.loading').removeClass('hide');
      $('#pageview-chart').addClass('hide');
      $('#table-data-popular').addClass('hide');
      $('#table-data-comment').addClass('hide');
      if (CONNECT){
        /* get data pageview */
        $.ajax({
          url: "{{ _url(adminPath() . '/ajax/pageview') }}",
          data: {
            range: rangetype,
            start: start_date.format('YYYY-MM-DD'),
            end: end_date.format('YYYY-MM-DD')
          },
          cache: false
        }).done(function(data) {
          if (data.status) {
            $('#box-pageview-chart .loading').addClass('hide');
            $('#pageview-chart').removeClass('hide');
            pageview_chart(data.rows);
          }
        });

        /* get data popular post */
        $.ajax({
          url: "{{ _url(adminPath() . '/ajax/popularpost') }}",
          data: {
            start: start_date.format('YYYY-MM-DD'),
            end: end_date.format('YYYY-MM-DD')
          },
          cache: false
        }).done(function(data) {
          if (data.status) {
            $('#box-popular-post .loading').addClass('hide');
            $('#table-data-popular').removeClass('hide');
            popular_post(data.rows);
          }
        });

        /* get data recent comment */
        $.ajax({
          url: "{{ _url(adminPath() . '/ajax/recentcomment') }}",
          data: {
            start: start_date.format('YYYY-MM-DD'),
            end: end_date.format('YYYY-MM-DD')
          },
          cache: false
        }).done(function(data) {
          if (data.status) {
            $('#box-recent-comment .loading').addClass('hide');
            $('#table-data-comment').removeClass('hide');
            recent_comments(data.rows);
          }
        });
      }
    }

    /* get popular post */
    function popular_post(data) {
      var html = '';
      if (data.length > 0) {
        $.each(data, function(k,v){
          html += '<tr>';
          html += '<td>'+v.title+'<br> <small>in '+v.categories+'</small></td>';
          html += '<td>'+v.views+'</td>';
          html += '</tr>';
        });
      } else {
        html += '<tr>';
        html += '<td colspan="2" align="center">Belum ada artikel populer.';
        html += '</tr>';
      }

      $('#box-popular-post').removeClass('hide');
      $('#popular-post').html(html);
    }

    /* get recent comments */
    function recent_comments(data) {
      var html = '';
      if (data.length > 0) {
        $.each(data, function(k,v){
          var time = ''; 
          @if (app('is_desktop'))
            time = 'on '+v.time;
          @endif
          html += '<tr>';
          html += '<td>'+v.text+'<br><small>in '+v.post+' '+time+'</small></td>';
          html += '<td>'+v.name+'</td>';
          html += '</tr>';
        });
      } else {
        html += '<tr>';
        html += '<td colspan="2" align="center">Belum ada komentar terbaru.';
        html += '</tr>';
      }

      $('#box-recent-comment').removeClass('hide');
      $('#recent-comment').html(html);
    }

    /* generate pageview chart */
    function pageview_chart(data) {
      var opt = {
        chart : {
          type: 'spline'
        },
        title: {
          text: 'Kunjungan',
          align: 'left'
        },
        credits: {
          enabled: false
        },
        xAxis: {
          type: 'datetime',
          gridLineWidth: 1,
          gridLineColor: '#bbbbbb',
          gridLineDashStyle: 'dot'
        },
        yAxis: {
          title: {
            enabled: false
          },
          min: 0,
          gridLineWidth: 1,
          gridLineColor: '#bbbbbb',
          gridLineDashStyle: 'dot',
          allowDecimals: false,
          /*labels: {
              formatter: function() {
                  return this.value;
              }
          }*/
        },
        legend: {
          {!! !app('is_desktop') ? 'layout: \'vertical\',' : '' !!}
          align: 'right',
          verticalAlign: 'top',
          floating: true,
          borderWidth: 0
        },
        series: data,
        tooltip: {
          headerFormat: 'Waktu: {point.key}<br>',
          shared: true,
          crosshairs: true
        },
        plotOptions: {
          spline: {
            pointStart: Date.UTC(yr, mn, dt),
          }
        }
      };

      if (rangetype == "hourly") {
        var dayTime = 3600 * 1000;
        opt.xAxis.tickInterval = dayTime;
        opt.plotOptions.spline.pointInterval = dayTime;
      } else if (rangetype == "monthly") {
        opt.plotOptions.spline.pointIntervalUnit = 'month';
      } else if (rangetype == "yearly") {
        opt.tooltip.xDateFormat = '%Y';
        opt.plotOptions.spline.pointIntervalUnit = 'year';
      } else {
        var dayTime = 24 * 3600 * 1000;
        opt.xAxis.tickInterval = dayTime;
        opt.plotOptions.spline.pointInterval = dayTime;
      }
      
      $('#pageview-chart').highcharts(opt);
    }

    $('#btn-refresh').on("click", function(){
      combobox(start, end, label);
    });

    $('#z-date-range').daterangepicker({
      startDate: start,
      endDate: end,
      minDate: min,
      maxDate: max,
      ranges: {
        'Hari ini': [start, end],
        'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Minggu ini': [moment().startOf('week'), moment().endOf('week')],
        'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
        'Semua': [min, max],
      },
      locale: {
        firstDay: 1,
        applyLabel: 'Atur',
        cancelLabel: 'Batal',
        fromLabel: 'Dari',
        toLabel: 'Sampai',
        customRangeLabel: 'Atur tanggal',
        daysOfWeek: ['Mi', 'Se', 'Sl', 'Ra', 'Ka', 'Ju', 'Sa'],
        monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        format: 'YYYY-MM-DD'
      },
      applyClass: 'btn-warning'
    }, combobox);

    combobox(start, end, label);
  });
  </script>
@endpush