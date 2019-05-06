@extends('admin.AdminSC.layouts.main')

@section('content')
  <div class="panel-body no-padding-right-left">
    <table id="table-data" class="row-border hover">
      <thead>
        <tr>
          <td width="25">No.</td>
          {{-- @if ($isDesktop) --}}
            <td>Judul</td>
            {{-- <td width="300">URL</td> --}}
            <td width="80">Status</td>
          {{-- @else
            <td width="250">Page</td>
          @endif --}}
          <td width="50">Akses</td>
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
        "ajax": SITE_URL + "{{ $adminPath }}/content/pages/data",
        "pageLength": 20,
        "lengthMenu": [
          [10, 20, 50, 100, -1], 
          [10, 20, 50, 100, "All"]
        ],
        "columns": [
          { "width": "30px" },
          // { "data": "title", "width": "200px" },
          { "data": "title" },
          { "data": "status", "width": "50px" },
          { "width": "100px" },
        ],
        "columnDefs": [{
          "targets": 0,
          "data": null,
          "sortable": false,
          "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        }, {
          "targets": 2,
          "data": 'status',
          "sortable": false,
          "render": function (data, type, row, meta) {
            return _get_status_text(data);
          }
        }, {
          "targets": 3,
          "data": 'id',
          "sortable": false,
          "render": function (data, type, row, meta) {
            var actions = '';
            var url = SITE_URL + "{{ $adminPath }}/content/pages/" + data;
            var del = "_delete('" + url + "')";
            {!! _get_access_buttons() !!}
            $('[data-toggle="tooltip"]').tooltip();

            return actions;
          }
        }],
      });
    });
  </script>
@endsection