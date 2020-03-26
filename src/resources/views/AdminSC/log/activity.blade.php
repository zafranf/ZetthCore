@extends('zetthcore::AdminSC.layouts.main')

@section('content')
	<div class="panel-body no-padding-right-left">
		<table id="table-data" class="row-border hover">
			<thead>
				<tr>
          <td>No.</td>
          <td>Waktu</td>
          <td>IP</td>
          <td>Aktifitas</td>
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
      var table = $('#table-data').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ADMIN_URL + "/log/activities/data",
        "pageLength": 20,
        "lengthMenu": [
          [10, 20, 50, 100, -1], 
          [10, 20, 50, 100, "All"]
        ],
        "columns": [
          { "width": "30px" },
          { "data": "created_at", "width": "120px" },
          { "data": "ip", "width": "100px" },
          { "data": "description" },
        ],
        "columnDefs": [{
          "targets": 0,
          "data": null,
          "sortable": false,
          "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        }],
      });
    });
  </script>
@endpush
