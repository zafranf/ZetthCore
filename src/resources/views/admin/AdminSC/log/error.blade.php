@extends('admin.AdminSC.layouts.main')

@section('content')
	<div class="panel-body no-padding-right-left">
		<table id="table-data" class="row-border hover">
			<thead>
				<tr>
          <td>No.</td>
          <td>Tanggal</td>
          <td>Pesan</td>
          <td>File</td>
          <td>Baris</td>
          <td>Jumlah</td>
				</tr>
			</thead>
		</table>
	</div>
@endsection

@section('styles')
  {!! _load_css('themes/admin/AdminSC/plugins/DataTables/1.10.12/css/jquery.dataTables.min.css') !!}
@endsection

@section('scripts')
  {!! _load_js('themes/admin/AdminSC/plugins/DataTables/1.10.12/js/jquery.dataTables.min.js') !!}
  <script>
    $(document).ready(function() {
      var table = $('#table-data').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": SITE_URL + "{{ $adminPath }}/log/errors/data",
        "pageLength": 20,
        "lengthMenu": [
          [10, 20, 50, 100, -1], 
          [10, 20, 50, 100, "All"]
        ],
        // "order": [[ 0, "desc" ]],
        "columns": [
          { "width": "30px" },
          { "data": "updated_at", "width": "50px" },
          { "data": "message", "width": "300px" },
          { "data": "file", "width": "200px" },
          { "data": "line", "width": "50px" },
          { "data": "count", "width": "50px" },
        ],
        "columnDefs": [{
          "targets": 0,
          "data": null,
          "sortable": false,
          "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        }]
      });
    });
  </script>
@endsection
