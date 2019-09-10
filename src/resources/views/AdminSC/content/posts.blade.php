@extends('zetthcore::AdminSC.layouts.main')

@section('content')
	<div class="panel-body no-padding-right-left">
		<table id="table-data" class="row-border hover">
			<thead>
				<tr>
					<td>No.</td>
					@if ($is_desktop)
						<td>Judul</td>
						<td>Status</td>
					@else
						<td>Artikel</td>
					@endif
					<td>Akses</td>
				</tr>
			</thead>
		</table>
	</div>
@endsection

@section('styles')
  {!! _admin_css('themes/admin/AdminSC/plugins/DataTables/1.10.12/css/jquery.dataTables.min.css') !!}
  <style>
    .twitter-share-button {
      position: relative;
      height: 20px;
      padding: 1px 8px 1px 6px;
      color: #fff;
      cursor: pointer;
      background-color: #1b95e0;
      border-radius: 3px;
      box-sizing: border-box;
      font-size: 12px;
    }
    .twitter-share-button:hover, .twitter-share-button:active, .twitter-share-button:focus {
      text-decoration: none;
      color: white;
    }
    .fb-share-button {
      position: relative;
      height: 20px;
      padding: 1px 8px 1px 6px;
      color: #fff;
      cursor: pointer;
      background-color: #4267b2;
      border-radius: 3px;
      box-sizing: border-box;
      font-size: 12px;
    }
    .fb-share-button:hover, .fb-share-button:active, .fb-share-button:focus {
      text-decoration: none;
      color: white;
    }
    .zetth-share-button {
      position: relative;
      height: 18px;
      margin-top: -2px;
      padding: 1px 8px 1px 6px;
      /*color: #fff;*/
      cursor: pointer;
      /*background-color: #1b95e0;*/
      border: 1px solid coral;
      border-radius: 3px;
      box-sizing: border-box;
      font-size: 12px;
      line-height: 1.2;
    }
    .zetth-share-button:hover, .zetth-share-button:active, .zetth-share-button:focus {
      text-decoration: none;
    }
    .zetth-stats {
      border: 1px solid coral;
      border-radius: 3px;
      width: 100%;
      display: block;
      padding: 0 5px;
      margin: 1px 0;
      overflow: hidden;
      text-align: center;
      font-size: 12px;
    }
    .zetth-stats .text {
      float: right;
      background: coral;
      color: white;
      padding: 0 3px;
      position: relative;
      right: -5px;
      overflow: hidden;
      width: 70%;
      text-align: right;
    }
    @media (max-width: 767px) {
      .zetth-stats {
        width: 40%;
        display: inline;
      }
    }
  </style>
@endsection

@section('scripts')
  {!! _admin_js('themes/admin/AdminSC/plugins/DataTables/1.10.12/js/jquery.dataTables.min.js') !!}
  <script>
    $(document).ready(function(){
      let options = {
        "processing": true,
        "serverSide": true,
        "ajax": SITE_URL + "{{ $adminPath }}/content/posts/data",
        "pageLength": 20,
        "lengthMenu": [
          [10, 20, 50, 100, -1], 
          [10, 20, 50, 100, "All"]
        ],
        "columns": [
          { "width": "30px" },
          { "data": "title" },
          { "data": "status", "width": "50px" },
          { "width": "40px" },
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
            var url = SITE_URL + "{{ $adminPath }}/content/posts/" + data;
            var del = "_delete('" + url + "')";
            {!! _get_access_buttons() !!}
            $('[data-toggle="tooltip"]').tooltip();

            return actions;
          }
        }],
      };

      @if (!$is_desktop)
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
              let render = row.title+'<br>';
              // render += '<small>'+row.description+'</small><br>';
              render += _get_status_text(row.status);

              return render;
            }
          }, {
            "targets": 2,
            "data": 'id',
            "sortable": false,
            "render": function (data, type, row, meta) {
              let actions = '';
              let url = SITE_URL + "{{ $adminPath }}/content/posts/" + data;
              let del = "_delete('" + url + "')";
              {!! _get_access_buttons() !!}
              $('[data-toggle="tooltip"]').tooltip();

              return actions;
            }
          }
        ];
      @endif

      let table = $('#table-data').DataTable(options);

      $('.btn-short-url').on('click', function(){
        url = $(this).text();
        html = 'Press <code>CTRL+C</code> to copy: <input id="zetth-short-url" type="text" class="form-control" readonly value="'+url+'" style="margin-top:10px;">';
        $('.modal-title').text('Share URL');
        $('.modal-body').html(html);
        $('.modal-footer').hide();
      });
      
      $('#zetth-modal').on('shown.bs.modal', function () {
        $('#zetth-short-url').select();
      })
    });
  </script>
@endsection