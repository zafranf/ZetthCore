@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2">
        {{-- <h1><i class="fa fa-calendar" aria-hidden="true"></i> Laravel Log Viewer</h1> --}}
        <br>
        <p class="text-muted">
          oleh <i><a href="https://github.com/rap2hpoutre/laravel-log-viewer" target="_blank">Rap2h</a></i>
        </p>

        {{-- <div class="custom-control custom-switch" style="padding-bottom:20px;">
          <input type="checkbox" class="custom-control-input" id="darkSwitch">
          <label class="custom-control-label" for="darkSwitch" style="margin-top: 6px;">Dark Mode</label>
        </div> --}}
        <div id="affixs" style="top:80px;border:1px solid #ccc;border-radius:4px;">
          <h4 style="border-bottom:2px solid #ccc;padding:0 10px;line-height:29px;margin-bottom:0;">Berkas</h4>
          <div class="list-group div-scroll">
            @foreach($folders as $folder)
              <div class="list-group-item">
                <a href="?f={{ urlencode(_encrypt($folder)) }}">
                  <span class="fa fa-folder"></span> {{$folder}}
                </a>
                @if ($current_folder == $folder)
                  <div class="list-group folder">
                    @foreach($folder_files as $file)
                      <span onclick="window.location='?l={{ urlencode(_encrypt($file)) }}'"
                        class="list-group-item @if ($current_file == $file) llv-active @endif">
                        {{$file}}
                        <a class="pull-right" href="?dl={{ urlencode(_encrypt($current_file)) }}{{ ($current_folder) ? '&f=' . urlencode(_encrypt($current_folder)) : '' }}" data-toggle="tooltip" data-original-title="Unduh berkas" data-placement="left">
                          <span class="fa fa-download"></span>
                        </a>
                      </span>
                    @endforeach
                  </div>
                @endif
              </div>
            @endforeach
            @foreach($files as $file)
              <span onclick="window.location='?l={{ urlencode(_encrypt($file) )}}'"
                class="list-group-item @if ($current_file == $file) llv-active @endif">
                {{$file}}
                <a class="pull-right" href="?dl={{ urlencode(_encrypt($current_file)) }}{{ ($current_folder) ? '&f=' . urlencode(_encrypt($current_folder)) : '' }}" data-toggle="tooltip" data-original-title="Unduh berkas" data-placement="left">
                  <span class="fa fa-download"></span>
                </a>
              </span>
            @endforeach
          </div>
        </div>
      </div>
      <div class="col-md-10 table-container">
        <br>
        @if ($logs === null)
          <div>
            Log file >50M, please <a href="?dl={{ urlencode(_encrypt($current_file)) }}{{ ($current_folder) ? '&f=' . urlencode(_encrypt($current_folder)) : '' }}">
              <span class="fa fa-download"></span> download
            </a> it.
          </div>
        @else
          <table id="table-log" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
            <thead>
              <tr>
                @if ($standardFormat)
                  <th>Status</th>
                  <th>Konteks</th>
                  <th>Tanggal</th>
                @else
                  <th>Baris</th>
                @endif
                <th>Konten</th>
              </tr>
            </thead>
            <tbody>
              @foreach($logs as $key => $log)
                <tr data-display="stack{{{$key}}}">
                  @if ($standardFormat)
                    <td class="nowrap text-{{{$log['level_class']}}}">
                      <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                    </td>
                    <td class="text">{{$log['context']}}</td>
                  @endif
                  <td class="date">{{{$log['date']}}}</td>
                  <td class="text">
                    @if ($log['stack'])
                      <button type="button" class="pull-right expand btn btn-default btn-xs" data-display="stack{{{$key}}}" data-toggle="tooltip" data-original-title="Detail">
                        <span class="fa fa-eye"></span>
                      </button>
                    @endif
                    {{{$log['text']}}}
                    @if (isset($log['in_file']))
                      <br/>{{{$log['in_file']}}}
                    @endif
                    @if ($log['stack'])
                      <div class="stack" id="stack{{{$key}}}" style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                      </div>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>
    </div>
  </div>
@endsection

@push('styles')
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/DataTables/1.10.12/css/jquery.dataTables.min.css') !!}
  <style>
    #table-log {
        /* font-size: 0.85rem; */
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    #table-log tr {
      cursor: pointer;
    }
    #table-log tr:not(:first-child):hover {
      background: #f5f5f5;
    }
    .sidebar {
        /* font-size: 0.85rem; */
        line-height: 1;
    }
    .date {
      min-width: 75px;
    }
    .text {
      word-break: break-all;
    }
    span.llv-active {
      z-index: 2;
      background-color: #f5f5f5;
      border-color: #777;
    }
    .list-group-item {
      cursor: pointer;
      word-wrap: break-word;
      border: 0;
    }
    .list-group-item:not(:first-child) {
      border-top: 1px solid #ccc;
    }
    .list-group-item:hover {
      background:#f5f5f5;
    }
    .folder {
      padding-top: 15px;
    }
    .div-scroll {
      top: 80px;
      height: 50vh;
      overflow: hidden auto;
      margin-bottom: 0;
    }
    .nowrap {
      white-space: nowrap;
    }
  </style>
@endpush

@push('scripts')
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/DataTables/1.10.12/js/jquery.dataTables.min.js') !!}
  <script>
    $(document).ready(function () {
      let ds = $('.div-scroll').width();
      $("#affixs").affix({
          offset: {
            top: 80
          }
      });
      $('#affixs').on('affixed.bs.affix', function() {
        $('.affix').width(ds ? ds : 205);
      });
      if ($('.affix').length) {
        $('.affix').width(ds ? ds : 205);
      }
      $('.table-container tr').on('click', function () {
        $('#' + $(this).data('display')).toggle();
      });
      $('#table-log').DataTable({
        "order": [$('#table-log').data('orderingIndex'), 'desc'],
        "stateSave": true,
        "stateSaveCallback": function (settings, data) {
          window.localStorage.setItem("datatable", JSON.stringify(data));
        },
        "stateLoadCallback": function (settings) {
          var data = JSON.parse(window.localStorage.getItem("datatable"));
          if (data) data.start = 0;
          return data;
        },
        "pageLength": 20,
        "lengthMenu": [
          [10, 20, 50, 100, -1], 
          [10, 20, 50, 100, "All"]
        ],
      });
      $('#delete-log, #clean-log, #delete-all-log').click(function () {
        return confirm('Are you sure?');
      });
    });
  </script>
@endpush
