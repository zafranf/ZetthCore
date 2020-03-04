@extends('zetthcore::AdminSC.layouts.main')

@section('content')
<div class="panel-body no-padding-right-left">
  <table id="table-data" class="row-border hover">
    <thead>
      <tr>
        <td>No.</td>
        @if (app('is_desktop'))
        <td>Sampul</td>
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
{!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/DataTables/1.10.12/css/jquery.dataTables.min.css') !!}
<style>
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

  .zetth-share-button:hover,
  .zetth-share-button:active,
  .zetth-share-button:focus {
    text-decoration: none;
    background: #e4e4e4;
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
{!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/moment/2.13.0/js/moment.min.js') !!}
{!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/DataTables/1.10.12/js/jquery.dataTables.min.js') !!}
<script>
  function copy() {
    $('#zetth-short-url').select();
    try {
      setTimeout(function() {
        document.execCommand('copy');
        setTimeout(function() {
          alert('Tautan berhasil disalin!');
        }, 10);
      }, 10);
    } catch(e) {}
  }

  $(document).ready(function(){
      let options = {
        "processing": true,
        "serverSide": true,
        "ajax": SITE_URL + "{{ adminPath() }}/content/posts/data",
        "pageLength": 20,
        "lengthMenu": [
          [10, 20, 50, 100, -1], 
          [10, 20, 50, 100, "All"]
        ],
        "columns": [
          { "width": "30px" },
          { "data": "cover", "width": "80px" },
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
          "targets": 1,
          "data": 'cover',
          "sortable": false,
          "render": function (data, type, row, meta) {
            return '<img src="'+data+'" width="80px">';
          }
        }, {
          "targets": 2,
          "data": 'title',
          "render": function (data, type, row, meta) {
            let postlink = SITE_URL + '/post/' + row.slug;
            let fblink = 'https://www.facebook.com/sharer/sharer.php?u='+postlink+'&amp;src=sdkpreparse';
            let twlink = 'https://twitter.com/intent/tweet?text=' + data + ' ' + postlink;
            let render = data + '<br>';
            render += 'oleh <b>' + row.author.fullname + '</b><br>';
            /* render += 'pada <b>' + row.published_string + '</b><br>'; */
            render += '<a class="zetth-share-button" onclick="_open_window(\''+fblink+'\')"><i class="fa fa-facebook-square"></i> Share</a>&nbsp;'; 
            render += '<a class="zetth-share-button" onclick="_open_window(\''+twlink+'\')"><i class="fa fa-twitter"></i> Tweet</a>&nbsp;';
            render += '<a id="btn-short-url-'+row.id+'" class="zetth-share-button btn-short-url" data-toggle="modal" data-target="#zetth-modal"><i class="fa fa-link"></i> '+postlink+'</a>';

            return render;
          }
        }, {
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
            var url = SITE_URL + "{{ adminPath() }}/content/posts/" + data;
            var del = "_delete('" + url + "')";
            {!! _get_access_buttons() !!}
            $('[data-toggle="tooltip"]').tooltip();

            return actions;
          }
        }],
        "initComplete": function(settins, json) {
          $('.btn-short-url').on('click', function() {
            let url = $(this).text();
            let html = 'Tekan ikon untuk menyalin tautan: <div class="input-group"><input id="zetth-short-url" type="text" class="form-control" readonly value="'+url+'"><span class="input-group-addon" onclick="copy()" style="cursor:pointer;"><i class="fa fa-copy"></i></span></div>';
            $('.modal-title').text('Bagikan Tautan');
            $('.modal-body').html(html);
            $('.modal-footer').hide();
          });
        },
        "language": {
            "decimal":        "",
            "emptyTable":     "Data tidak tersedia",
            "info":           "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty":      "Menampilkan 0 sampai 0 dari 0 data",
            "infoFiltered":   "(tersaring dari _MAX_)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Tampilkan _MENU_ data",
            "loadingRecords": "Memuat...",
            "processing":     "Memproses...",
            "search":         "Cari",
            "zeroRecords":    "Data tidak ditemukan",
            "paginate": {
                "first":      "Awal",
                "last":       "Akhir",
                "next":       "Lanjut",
                "previous":   "Kembali"
            },
            "aria": {
                "sortAscending":  ": urutkan a-z",
                "sortDescending": ": urutkan z-a"
            }
        }
      };

      @if (!app('is_desktop'))
        options.columns = [
          { "width": "30px" },
          { "data": "title"},
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
            "data": "title",
            "render": function (data, type, row, meta) {
              let postlink = SITE_URL + '/post/' + row.slug;
              let fblink = 'https://www.facebook.com/sharer/sharer.php?u='+postlink+'&amp;src=sdkpreparse';
              let twlink = 'https://twitter.com/intent/tweet?text=' + data + ' ' + postlink;
              let render = data + '<br>';
              /* render += 'oleh <b>' + row.author.fullname + '</b><br>'; */
              render += '<a class="zetth-share-button" onclick="_open_window(\''+fblink+'\')"><i class="fa fa-facebook-square"></i></a>&nbsp;'; 
              render += '<a class="zetth-share-button" onclick="_open_window(\''+twlink+'\')"><i class="fa fa-twitter"></i></a>&nbsp;';
              render += '<a id="btn-short-url-'+row.id+'" class="zetth-share-button btn-short-url" data-toggle="modal" data-target="#zetth-modal"><i class="fa fa-link"></i></a><br>';
              render += _get_status_text(row.status);

              return render;
            }
          }, {
            "targets": 2,
            "data": 'id',
            "sortable": false,
            "render": function (data, type, row, meta) {
              let actions = '';
              let url = SITE_URL + "{{ adminPath() }}/content/posts/" + data;
              let del = "_delete('" + url + "')";
              {!! _get_access_buttons() !!}
              $('[data-toggle="tooltip"]').tooltip();

              return actions;
            }
          }
        ];
      @endif

      let table = $('#table-data').DataTable(options);
    });
</script>
@endsection