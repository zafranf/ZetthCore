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

@push('styles')
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
@endpush

@push('scripts')
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

    $(document).ready(function() {
      let options = {
        "processing": true,
        "serverSide": true,
        "ajax": ADMIN_URL + "/content/posts/data",
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
            return '<img src="'+(data ? data : '{!! _url(adminPath() . "/themes/admin/AdminSC/images/no-image.png") !!}')+'" width="80px">';
          }
        }, {
          "targets": 2,
          "data": 'title',
          "render": function (data, type, row, meta) {
            let sharelink = SITE_URL + '/action/share/' + row.slug;
            let posturl = SITE_URL + '/{{ config('path.post', 'post') }}/' + row.slug;
            let fblink = 'https://www.facebook.com/sharer/sharer.php?u='+posturl+'&amp;src=sdkpreparse';
            let twlink = 'https://twitter.com/intent/tweet?text=' + data + ' ' + posturl;
            let render = data + '<br>';
            render += 'oleh <b>' + row.author.fullname + '</b><br>';
            /* render += 'pada <b>' + row.published_string + '</b><br>'; */
            render += '<a class="zetth-share-button" onclick="_open_window(\''+sharelink+'/facebook'+'\')"><i class="fa fa-facebook-square"></i> Facebook</a>&nbsp;'; 
            render += '<a class="zetth-share-button" onclick="_open_window(\''+sharelink+'/twitter'+'\')"><i class="fa fa-twitter"></i> Twitter</a>&nbsp;';
            render += '<a class="zetth-share-button" onclick="_open_window(\''+sharelink+'/whatsapp'+'\')"><i class="fa fa-whatsapp"></i> WhatsApp</a>&nbsp;';
            render += '<a class="zetth-share-button" onclick="_open_window(\''+sharelink+'/telegram'+'\')"><i class="fa fa-telegram">T</i> Telegram</a>&nbsp;';
            render += '<a id="btn-short-url-'+row.id+'" class="zetth-share-button btn-short-url" data-toggle="modal" data-url="'+posturl+'" data-target="#zetth-modal"><i class="fa fa-link"></i> Salin</a>';

            return render;
          }
        }, {
          "targets": 3,
          "data": 'status',
          "sortable": false,
          "render": function (data, type, row, meta) {
            return _get_status_text(data, {
              active: "Aktif",
              inactive: "Nonaktif",
              draft: "Draf"
            });
          }
        }, {
          "targets": 4,
          "data": 'id',
          "sortable": false,
          "render": function (data, type, row, meta) {
            var actions = '';
            var url = ADMIN_URL + "/content/posts/" + data;
            var del = "_delete('" + url + "', 'artikel \\'"+ row.title +"\\'')";
            {!! getAccessButtons() !!}
            $('[data-toggle="tooltip"]').tooltip();

            return actions;
          }
        }],
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
            let sharelink = SITE_URL + '/action/share/' + row.slug;
              let posturl = SITE_URL + '/post/' + row.slug;
              let fblink = 'https://www.facebook.com/sharer/sharer.php?u='+posturl+'&amp;src=sdkpreparse';
              let twlink = 'https://twitter.com/intent/tweet?text=' + data + ' ' + posturl;
              let render = data + '<br>';
              /* render += 'oleh <b>' + row.author.fullname + '</b><br>'; */
              render += '<a class="zetth-share-button" onclick="_open_window(\''+sharelink+'/facebook'+'\')"><i class="fa fa-facebook-square"></i></a>&nbsp;'; 
              render += '<a class="zetth-share-button" onclick="_open_window(\''+sharelink+'/twitter'+'\')"><i class="fa fa-twitter"></i></a>&nbsp;';
              render += '<a class="zetth-share-button" onclick="_open_window(\''+sharelink+'/whatsapp'+'\')"><i class="fa fa-whatsapp"></i></a>&nbsp;';
              render += '<a class="zetth-share-button" onclick="_open_window(\''+sharelink+'/telegram'+'\')">T</a>&nbsp;';
              render += '<a id="btn-short-url-'+row.id+'" class="zetth-share-button btn-short-url" data-toggle="modal" data-url="'+posturl+'" data-target="#zetth-modal"><i class="fa fa-link"></i></a><br>';
              render += _get_status_text(row.status, {
                active: "Aktif",
                inactive: "Nonaktif",
                draft: "Draf"
              });

              return render;
            }
          }, {
            "targets": 2,
            "data": 'id',
            "sortable": false,
            "render": function (data, type, row, meta) {
              let actions = '';
              let url = ADMIN_URL + "/content/posts/" + data;
              let del = "_delete('" + url + "', 'artikel \\'"+ row.title +"\\'')";
              {!! getAccessButtons() !!}
              $('[data-toggle="tooltip"]').tooltip();

              return actions;
            }
          }
        ];
      @endif

      let table = $('#table-data').DataTable(options);
      $('#table-data tbody').on('click', 'tr td .btn-short-url', function() {
        let url = $(this).data('url');
        let html = 'Tekan ikon untuk menyalin tautan: <div class="input-group"><input id="zetth-short-url" type="text" class="form-control" readonly value="'+url+'"><span class="input-group-addon" onclick="copy()" style="cursor:pointer;"><i class="fa fa-copy"></i></span></div>';
        $('.modal-title').text('Salin Tautan');
        $('.modal-body').html(html);
        $('.modal-footer').hide();
      });
    });
  </script>
@endpush