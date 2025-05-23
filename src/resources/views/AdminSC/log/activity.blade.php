@extends('zetthcore::AdminSC.layouts.main')

@section('content')
	<div class="panel-body no-padding-right-left">
		<table id="table-data" class="row-border hover">
			<thead>
				<tr>
          <td>No.</td>
          @if (app('is_desktop'))
            <td>Waktu</td>
            <td>IP</td>
            <td>Aktifitas</td>
          @else
            <td>Aktifitas</td>
          @endif
          <td>Akses</td>
				</tr>
			</thead>
		</table>
	</div>
@endsection

@push('styles')
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/DataTables/1.10.12/css/jquery.dataTables.min.css') !!}
@endpush

@push('scripts')
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/DataTables/1.10.12/js/jquery.dataTables.min.js') !!}
  <script>
    function debounce(fn, delay) {
      let timeout;
      return function () {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          fn.apply(this, arguments);
        }, delay);
      };
    }

    $(document).ready(function() {
      let currentXHR = null;
      let options = {
        "processing": true,
        "serverSide": true,
        "ajax": function (data, callback, settings) {
          if (currentXHR) currentXHR.abort();

          data.club_id = '{{ request()->get('club_id') }}';
          data.user_id = '{{ request()->get('user_id') }}';
          data.from = '{{ request()->get('from') }}';
          data.to = '{{ request()->get('to') }}';
          currentXHR = $.ajax({
            url: ADMIN_URL + '/log/activities/data',
            data: data,
            timeout: 5000,
            success: callback
          });
        },
        "pageLength": 20,
        "lengthMenu": [
          [10, 20, 50, 100, -1], 
          [10, 20, 50, 100, "All"]
        ],
        "columns": [
          { "width": "30px" },
          { "width": "120px" },
          { "data": "ip", "width": "100px" },
          { "data": "description" },
        ],
        "columnDefs": [{
          "targets": 0,
          "sortable": false,
          "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          },
        }, {
          "targets": 1,
          "data": 'created_at',
          "sortable": false,
          "render": function (data, type, row, meta) {
            return row.created_at_tz;
          }
        }, {
          "targets": 3,
          "data": 'description',
          "sortable": false,
          "render": function (data, type, row, meta) {
            return data.replace('[~name]', '<b>'+ (row.user != null ? row.user.fullname : '-' )+'</b>').replace('[~page]', '<br>['+row.path+']');
          }
        }, {
          "targets": 4,
          "data": 'id',
          "sortable": false,
          "render": function (data, type, row, meta) {
            let actions = '';
            let url = ADMIN_URL + "/log/activities/" + data;
            /* let del = "_delete('" + url + "', 'catatan dari \\'"+ row.email +"\\'')"; */
            {!! getAccessButtons() !!}
            $('[data-toggle="tooltip"]').tooltip();

            return actions;
          }
        }],
      };

      @if (!app('is_desktop'))
        options.columns = [
          { "width": "30px" },
          { },
          { "width": "40px" },
        ];
        options.columnDefs = [
          {
            "targets": 0,
            "sortable": false,
            "render": function (data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
            }
          }, {
            "targets": 1,
            "sortable": false,
            "render": function (data, type, row, meta) {
              let render = row.ip+'<br>';
              render += row.description.replace('[~name]', '<b>'+ (row.user != null ? row.user.fullname : '-' )+'</b>')+'<br>';
              render += '<small>'+row.created_at_tz+'</small><br>';
              // render += _get_status_text(row.status);

              return render;
            }
          }, {
            "targets": 2,
            "data": 'id',
            "sortable": false,
            "render": function (data, type, row, meta) {
              let actions = '';
              let url = ADMIN_URL + "/log/activities/" + data;
              /* let del = "_delete('" + url + "', 'pesan dari \\'"+ row.email +"\\'')"; */
              {!! getAccessButtons() !!}
              $('[data-toggle="tooltip"]').tooltip();

              return actions;
            }
          }
        ];
      @endif

      var table = $('#table-data').DataTable(options);
      // $('#table-data_filter input')
      //   .off() // remove default
      //   .on('input', debounce(function () {
      //     table.search(this.value).draw();
      //   }, 300));
    });
  </script>
@endpush
