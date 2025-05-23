@extends('zetthcore::AdminSC.layouts.main')

@section('content')
	<div class="panel-body no-padding-right-left">
		<table id="table-data" class="row-border hover">
			<thead>
				<tr>
					<td>No.</td>
					@if (app('is_desktop'))
						<td>Nama Akses</td>
						<td>Nama Lengkap</td>
						<td>Status</td>
						<td>Tanggal</td>
					@else
						<td>User</td>
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
    $(document).ready(function() {
      let options = {
        "processing": true,
        "serverSide": true,
        "ajax": ADMIN_URL + "/data/users/data",
        "pageLength": 20,
        "lengthMenu": [
          [10, 20, 50, 100, -1], 
          [10, 20, 50, 100, "All"]
        ],
        "columns": [
          { "width": "30px" },
          { "data": "name", "width": "250px" },
          { "data": "fullname" },
          { "data": "status", "width": "50px" },
          { "data": "tanggal", "width": "50px" },
          { "width": "70px" },
        ],
        "columnDefs": [{
          "targets": 0,
          "sortable": false,
          "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        }, {
          "targets": 3,
          "data": 'status',
          "sortable": false,
          "render": function (data, type, row, meta) {
            return _get_status_text(data);
          }
        }, {
          "targets": 5,
          "data": 'id',
          "sortable": false,
          "render": function (data, type, row, meta) {
            var actions = '';
            var url = ADMIN_URL + "/data/users/" + data;
            var del = "_delete('" + url + "', 'pengguna \\'"+ row.name +"\\'')";
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
              let render = row.name+'<br>';
              render += '<small>'+row.fullname+'</small><br>';
              render += _get_status_text(row.status);

              return render;
            }
          }, {
            "targets": 2,
            "data": 'id',
            "sortable": false,
            "render": function (data, type, row, meta) {
              let actions = '';
              let url = ADMIN_URL + "/data/users/" + data;
              let del = "_delete('" + url + "', 'pengguna \\'"+ row.name +"\\'')";
              {!! getAccessButtons() !!}
              $('[data-toggle="tooltip"]').tooltip();

              return actions;
            }
          }
        ];
      @endif

      let table = $('#table-data').DataTable(options);
    });
  </script>
@endpush
