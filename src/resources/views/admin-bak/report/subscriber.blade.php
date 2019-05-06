@extends('admin.layouts.main')

@section('content')
  <div class="card-body">
    <table id="list">
      <thead>
        <tr>
            <th>No.</th>
            <th>Email</th>
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
      "ajax": SITE_URL + "{{ $adminPath }}/report/subscribers/data",
      "columns": [
          { "data": "no", "width": "30px" },
          { "data": "email" },
          { "data": "status", "width": "50px" },
          { "width": "100px" },
      ],
      "lengthMenu": [ [20, 50, 100, -1], [20, 50, 100, "All"] ],
      "columnDefs": [{
        "targets": 3,
        "data": 'id',
        "render": function (data, type, row, meta) {
          var actions = '';
          var url = SITE_URL + "{{ $adminPath }}/report/subscribers/" + data;
          var del = "_delete('" + url + "')";
          {!! _get_access_buttons() !!}
          $('[data-toggle="tooltip"]').tooltip();
          return actions;
        }
      }, {
        "targets": 2,
        "data": 'status',
        "render": function (data, type, row, meta) {
          return _get_status_text(data);
        }
      }],
    });
  });
</script>
@endsection