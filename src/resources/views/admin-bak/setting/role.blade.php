@extends('admin.layouts.main')

@section('content')
  <div class="card-body">
    <table id="list">
      <thead>
        <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
@endsection

{{-- include css --}}
@section('css')
{!! _load_css('/admin/plugins/DataTables/datatables.min.css') !!}
@endsection

{{-- include js --}}
@section('js')
{!! _load_js('/admin/plugins/DataTables/datatables.min.js') !!}
<script>
  $(document).ready(function() {
    var table = $('#list').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": SITE_URL + "{{ $adminPath }}/setting/roles/data",
      "columns": [
          { "data": "no", "width": "30px" },
          { "data": "name", "width": "200px" },
          { "data": "description" },
          { "data": "status", "width": "50px" },
          { "width": "60px" },
      ],
      "lengthMenu": [ [20, 50, 100, -1], [20, 50, 100, "All"] ],
      "columnDefs": [{
        "targets": 4,
        "data": 'id',
        "render": function (data, type, row, meta) {
          var actions = "";
          var url = SITE_URL + "{{ $adminPath }}/setting/roles/" + data;
          var del = "_delete('" + url + "')";
          {!! _get_access_buttons() !!}
          $('[data-toggle="tooltip"]').tooltip();
          return actions;
        }
      }, {
        "targets": 3,
        "data": 'status',
        "render": function (data, type, row, meta) {
          return _get_status_text(data);
        }
      }],
    });
  });
</script>
@endsection