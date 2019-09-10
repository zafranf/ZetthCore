@extends('zetthcore::AdminSC.layouts.main')

@section('content')
	<div class="panel-body no-padding-right-left">
		<table id="table-data" class="row-border hover">
			<thead>
				<tr>
          <td>No.</td>
          <td>IP</td>
          <td>Halaman</td>
          <td>Referral</td>
          <td>Jumlah</td>
				</tr>
			</thead>
		</table>
	</div>
@endsection

@section('styles')
  {!! _admin_css('themes/admin/AdminSC/plugins/DataTables/1.10.12/css/jquery.dataTables.min.css') !!}
@endsection

@section('scripts')
  {!! _admin_js('themes/admin/AdminSC/plugins/DataTables/1.10.12/js/jquery.dataTables.min.js') !!}
  <script>
    $(document).ready(function() {
      var table = $('#table-data').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": SITE_URL + "{{ $adminPath }}/log/visitors/data",
        "pageLength": 20,
        "lengthMenu": [
          [10, 20, 50, 100, -1], 
          [10, 20, 50, 100, "All"]
        ],
        "columns": [
          { "width": "30px" },
          // { "data": "image", "width": "80px" },
          { "data": "ip", "width": "100px" },
          { "data": "page" },
          { "data": "referral" },
          { "data": "count", "width": "50px" },
          // { "data": "email", "width": "200px" },
          // { "data": "method", "width": "50px" },
          // { "width": "100px" },
        ],
        "columnDefs": [{
          "targets": 0,
          "data": null,
          "sortable": false,
          "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        }, /* {
          "targets": 1,
          "data": 'image',
          "sortable": false,
          "render": function (data, type, row, meta) {
            return '<img src="' + data + '" width="80">';
          }
        }, */ /* {
          "targets": 3,
          "data": 'status',
          "sortable": false,
          "render": function (data, type, row, meta) {
            return _get_status_text(data);
          }
        }, {
          "targets": 4,
          "data": 'id',
          "sortable": false,
          "render": function (data, type, row, meta) {
            var actions = '';
            var url = SITE_URL + "{{ $adminPath }}/log/visitors/" + data;
            var del = "_delete('" + url + "')";
            {!! _get_access_buttons() !!}
            $('[data-toggle="tooltip"]').tooltip();

            return actions;
          }
        } */],
      });
    });
  </script>
@endsection