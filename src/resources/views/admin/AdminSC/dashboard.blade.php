@extends('admin.AdminSC.layouts.main')

@section('content2')
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="btn btn-default btn-quick">
          <a href="{{ url($adminPath . '/content/posts/create') }}">
            <div class="row">
              <div class="col-sm-12" title="Create a new Post">
                <i class="fa fa-edit"></i>
                <div class="text">
                  Buat Artikel
                </div>
              </div>
            </div>
          </a>
        </div>
        <div class="btn btn-default btn-quick">
          <a href="{{ url($adminPath . '/content/pages/create') }}">
            <div class="row">
              <div class="col-sm-12" title="Create a new Page">
                <i class="fa fa-file-o"></i>
                <div class="text">
                  Buat Halaman
                </div>
              </div>
            </div>
          </a>
        </div>
        <div class="btn btn-default btn-quick">
          <a href="{{ url($adminPath . '/report/comments') }}">
            <div class="row">
              <div class="col-sm-12" title="Check for new comments">
                <i class="fa fa-comment-o"></i>
                <div class="text">
                  Komentar
                </div>
                <span class="btn btn-warning btn-xs btn-xs-top-right no-border-top-right" style="top:-6px;right:3px;"><b>{{ $comment->unread ?? 0 }}</b></span>
              </div>
            </div>
          </a>
        </div>
        <div class="btn btn-default btn-quick">
          <a href="{{ url($adminPath . '/report/inbox') }}">
            <div class="row">
              <div class="col-sm-12" title="Get inbox">
                <i class="fa fa-envelope-o"></i>
                <div class="text">
                  Pesan Masuk
                </div>
                <span class="btn btn-warning btn-xs btn-xs-top-right no-border-top-right" style="top:-6px;right:3px;"><b>{{ $message->unread ?? 0 }}</b></span>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-sm-12" id="box-pageview-chart">
        <div class="loading">Loading<img src="{{ url('themes/admin/AdminSC/images/loading-flat.gif') }}"></div>
        <div id="pageview-chart" style="height:370px;"></div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-sm-12 col-md-6" id="box-popular-post">
          <div class="panel panel-default">
            <div class="panel-heading">
              Artikel Populer
              <a href="{{ url($adminPath . '/content/article/posts') }}" class="btn btn-default btn-sm pull-right"><i class="fa fa-eye"></i> Semua</a>
            </div>
            <div class="panel-body no-padding">
              <div class="loading">Loading<img src="{{ url('themes/admin/AdminSC/images/loading-flat.gif') }}"></div>
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
            <a href="{{ url($adminPath . '/report/comments') }}" class="btn btn-default btn-sm pull-right"><i class="fa fa-eye"></i> Semua</a>
          </div>
          <div class="panel-body no-padding">
            <div class="loading">Loading<img src="{{ url('themes/admin/AdminSC/images/loading-flat.gif') }}"></div>
            <table id="table-data-comment" class="table table-hover no-margin-bottom">
              <thead>
                <tr>
                  <td><b>Komentar</b></td>
                  <td width="120"><b>Dari</b></td>
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

@section('styles')
  {!! _load_css('themes/admin/AdminSC/plugins/bootstrap/daterangepicker/2.1.24/daterangepicker.css') !!}
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
@endsection

@section('scripts')
  {!! _load_js('themes/admin/AdminSC/plugins/moment/2.13.0/js/moment.min.js') !!}
  {!! _load_js('themes/admin/AdminSC/plugins/bootstrap/daterangepicker/2.1.24/daterangepicker.js') !!}
  {!! _load_js('themes/admin/AdminSC/plugins/highcharts/4.2.6/highcharts.js') !!}
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
    var start = moment();
    var end = moment();
    var min = moment('{{ $apps->created_at }}');
    var max = moment();
    var yr = moment();
    var mn = moment();
    var dt = moment();
    var intHour = 1;
    var intDay = 24;
    var label = (typeof label != "undefined") ? label : 'Today';

    /* generate date range box */
    var html = '<div id="z-date-range" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; margin-top: -40px; margin-right: 45px;">'+
              '<i class="fa fa-calendar"></i>&nbsp;'+
              '<span></span> '+
              '<b class="caret"></b>'+
          '</div>'+
              '<div id="btn-refresh" title="Refresh" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; margin-top: -40px; margin-right: 15px;">'+
                  '<i class="fa fa-refresh"></i>'+
              '</div>&nbsp;';
    $('#page-header').append(html);

      /* updating combobox */
    function combobox(start, end, label) {
      yr = start.format('YYYY');
      mn = start.format('MM')-1;
      dt = start.format('DD');
      @if ($isDesktop)
        var range = start.format('MMMM DD, YYYY') + ' - ' + end.format('MMMM DD, YYYY');
      @else
      var range = label;
      @endif
      $('#z-date-range span').text(range);

      if (start.format('YYYY-MM-DD') == end.format('YYYY-MM-DD')) {
        rangetype = 'hourly';
      } else if (start.format('YYYY') != end.format('YYYY')){
        rangetype = 'yearly';
      } else if (start.format('YYYY-MM') != end.format('YYYY-MM')){
        rangetype = 'monthly';
      } else {
        rangetype = 'daily';
      }

      // $('#pageview-chart').html("Loading<img src=\"{{ url('themes/admin/AdminSC/images/loading-flat.gif') }}\">");
      // $('#box-popular-post').addClass('hide');
      // $('#box-recent-comment').addClass('hide');
      $('.loading').removeClass('hide');
      if (CONNECT){
        /* get data pageview */
        $.ajax({
          url: "{{ url($adminPath . '/ajax/pageview') }}",
          data: {
            range: rangetype,
            start: start.format('YYYY-MM-DD'),
            end: end.format('YYYY-MM-DD')
          },
          cache: false
        }).done(function(data) {
          if (data.status) {
            $('#box-pageview-chart .loading').addClass('hide');
            pageview_chart(data.rows);
          }
        });

        /* get data popular post */
        $.ajax({
          url: "{{ url($adminPath . '/ajax/popularpost') }}",
          data: {
            start: start.format('YYYY-MM-DD'),
            end: end.format('YYYY-MM-DD')
          },
          cache: false
        }).done(function(data) {
          if (data.status) {
            $('#box-popular-post .loading').addClass('hide');
            popular_post(data.rows);
          }
        });

        /* get data recent comment */
        $.ajax({
          url: "{{ url($adminPath . '/ajax/recentcomment') }}",
          data: {
            start: start.format('YYYY-MM-DD'),
            end: end.format('YYYY-MM-DD')
          },
          cache: false
        }).done(function(data) {
          if (data.status) {
            $('#box-recent-comment .loading').addClass('hide');
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
          @if ($isDesktop)
          var time = 'on '+v.time;
          @else
          var time = ''; 
          @endif
          html += '<tr>';
          html += '<td>'+v.text+'<br>';
          html += '<small>in '+v.post+' '+time+'</small></td>';
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
          text: 'Pageviews',
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
          {!! !$isDesktop ? 'layout: \'vertical\',' : '' !!}
          align: 'right',
          verticalAlign: 'top',
          floating: true,
          borderWidth: 0
        },
        series: data,
        tooltip: {
          headerFormat: 'Time: {point.key}<br>',
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
        /*'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],*/
        'Minggu ini': [moment().startOf('week'), moment().endOf('week')],
        'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
        'Semua': [min, max],
        /* 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')] */
      },
      locale: {
        firstDay: 1,
        applyLabel: 'Atur',
        cancelLabel: 'Batal',
        fromLabel: 'Dari',
        toLabel: 'Sampai',
        customRangeLabel: 'Atur Tanggal',
        daysOfWeek: ['Mi', 'Se', 'Sl', 'Ra', 'Ka', 'Ju', 'Sa'],
        monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        format: 'YYYY-MM-DD'
      },
      applyClass: 'btn-warning'
    }, combobox);

    combobox(start, end, label);
  });
  </script>
@endsection