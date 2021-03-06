@extends('zetthcore::AdminSC.layouts.main')

@section('content')
	<div class="panel-body no-padding-right-left">
		<table id="table-data" class="row-border hover">
			<thead>
				<tr>
					<td>No.</td>
					@if (app('is_desktop'))
						<td>Sumber</td>
						<td>Kata Pencarian</td>
						<td>Jumlah</td>
					@else
						<td>Kata Pencarian</td>
					@endif
					{{-- <td>Akses</td> --}}
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
        "ajax": ADMIN_URL + "/report/incoming-terms/data",
        "pageLength": 20,
        "lengthMenu": [
          [10, 20, 50, 100, -1], 
          [10, 20, 50, 100, "All"]
        ],
        "columns": [
          { "width": "30px" },
          { "data": "host", "width": "250px" },
          { "data": "keyword" },
          { "data": "count", "width": "50px" },
          /* { "width": "40px" }, */
        ],
        "columnDefs": [{
          "targets": 0,
          "sortable": false,
          "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        }/* , {
          "targets": 4,
          "data": 'id',
          "sortable": false,
          "render": function (data, type, row, meta) {
            let actions = '';
            let url = ADMIN_URL + "/report/incoming-terms/" + data;
            let del = "_delete('" + url + "')";
            {!! getAccessButtons() !!}
            $('[data-toggle="tooltip"]').tooltip();

            return actions;
          }
        } */],
      };

      @if (!app('is_desktop'))
        options.columns = [
          { "width": "30px" },
          { },
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
              let render = '<i>'+row.host+'</i><br>';
              render += row.keyword+'<br>';
              render += row.count+'<br>';

              return render;
            }
          }
        ];
      @endif

      let table = $('#table-data').DataTable(options);
    });
  </script>
@endpush
