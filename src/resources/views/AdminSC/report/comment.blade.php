@extends('zetthcore::AdminSC.layouts.main')

@section('content')
	<div class="panel-body no-padding-right-left">
		<table id="table-data" class="row-border hover">
			<thead>
				<tr>
					<td>No.</td>
					@if (app('is_desktop'))
						<td>Nama</td>
						<td>Surel</td>
						<td>Komentar</td>
						<td>Status</td>
					@else
						<td>Komentar</td>
					@endif
					<td>Akses</td>
				</tr>
			</thead>
		</table>
	</div>
@endsection

@push('styles')
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/DataTables/1.10.12/css/jquery.dataTables.min.css') !!}
  <style>
    .panel>.panel-heading>.btn {
      display: none;
    }
  </style>
@endpush

@push('scripts')
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/DataTables/1.10.12/js/jquery.dataTables.min.js') !!}
  <script>
    $(document).ready(function() {
      let options = {
        "processing": true,
        "serverSide": true,
        "ajax": ADMIN_URL + "/report/comments/data",
        "pageLength": 20,
        "lengthMenu": [
          [10, 20, 50, 100, -1], 
          [10, 20, 50, 100, "All"]
        ],
        "columns": [
          { "width": "30px" },
          { "data": "name", "width": "200px" },
          { "data": "email", "width": "200px" },
          { "data": "content" },
          { "data": "status", "width": "50px" },
          { "width": "100px" },
        ],
        "columnDefs": [{
          "targets": 0,
          "sortable": false,
          "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        }, {
          "targets": 4,
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
            let reply_to = row.parent_id ? row.parent_id : data;
            let actions = '';
            let url = ADMIN_URL + "/report/comments/" + data;
            let del = "_delete('" + url + "', 'komentar dari \\'"+ row.name +"\\'')";
            actions += '<a href="' + ADMIN_URL + '/report/comments/create?cid=' + data + '&pid=' + row.post_id + '" class="btn btn-default btn-xs" data-toggle="tooltip" data-original-title="Balas"><i class="fa fa-reply"></i></a>';
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
              let render = row.name+' ('+row.email+')<br>';
              render += '<small>'+row.content+'</small><br>';
              render += _get_status_text(row.status);

              return render;
            }
          }, {
            "targets": 2,
            "data": 'id',
            "sortable": false,
            "render": function (data, type, row, meta) {
            let reply_to = row.parent_id ? row.parent_id : data;
              let actions = '';
              let url = ADMIN_URL + "/report/comments/" + data;
              let del = "_delete('" + url + "', 'komentar dari \\'"+ row.name +"\\'')";
              actions += '<a href="' + ADMIN_URL + '/report/comments/create?cid=' + data + '&pid=' + row.post_id + '" class="btn btn-default btn-xs" data-toggle="tooltip" data-original-title="Balas"><i class="fa fa-reply"></i></a>';
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
