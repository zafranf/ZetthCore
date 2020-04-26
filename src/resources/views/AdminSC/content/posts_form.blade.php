@php
  $categories_ = [];
  $descriptions_ = [];
  $parents_ = [];
  $tags_ = [];
  if (isset($data) ) {
    foreach($data->terms as $k => $term) {
      if ($term->type == "category"){
        $categories_[] = $term->name;
        $descriptions_[] = $term->description;
        $parents_[] = $term->parent;
      }
      if ($term->type=="tag") {
        $tags_[] = $term->name;
      }
    }
  }

  $urlFilemanager = url(adminPath() . '/larafile/dialog.php?type=1&multiple=0&field_id=cover&lang=id');
@endphp

@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="panel-body no-padding-bottom">
    <div class="row" style="margin-top:-15px;">
      <form id="form-post" action="{{ url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
        <div class="col-sm-8 col-md-9 left-side no-padding">
          <input type="text" id="title" class="form-control {{ isset($data) ? '' : 'autofocus' }} no-border-top-right no-border-left no-radius input-lg" name="title" placeholder="Judul.." maxlength="100" value="{{ $data->title ?? old('title') }}">
          <div class="input-group">
            <span class="input-group-addon no-border-top-right no-border-left no-radius input-sm" id="url_span">{{ getSiteURL('/' . env('POST_PATH', 'post') . '/') }}</span>
            <input type="text" id="slug" class="form-control no-border-top-right no-radius input-sm" name="slug" placeholder="Tautan otomatis.. (dapat disesuaikan, klik 2x untuk menyesuaikan)" readonly value="{{ $data->slug ?? old('slug') }}">
          </div>
          <textarea id="excerpt" name="excerpt" class="form-control no-border-top-right no-border-left no-radius input-xlarge" placeholder="Kutipan/deskripsi singkat tentang artikel (opsional).." rows="3">{{ $data->excerpt ?? old('excerpt') }}</textarea>
          <textarea id="content" name="content" class="form-control no-border-top-right no-border-bottom no-radius input-xlarge" placeholder="Ketikkan tulisan anda di sini...">{{ $data->content ?? old('content') }}</textarea>
        </div>
        <div class="col-sm-4 col-md-3 right-side">
          <div class="form-group" style="padding-top:10px;">
            <label for="cover">
              Sampul
              <small class="help-block">Maksimal dimensi sampul adalah
                {{ config('site.post.image.dimension.width') ?? 1280 }}px x 
                {{ config('site.post.image.dimension.height') ?? 720 }}px dengan 
                rasio {{ config('site.post.image.ratio') ?? '16:9' }} dan 
                ukuran maksimal {{ (config('site.post.image.weight') > 512 ? 512 : config('site.post.image.weight')) ?? 256 }}KB</small>
            </label>
            <div class="fileinput fileinput-new" data-provides="fileinput">
              <div class="fileinput-new thumbnail">
                <img src="{{ getImage('/assets/images/posts/' . ($data->cover ?? null)) }}">
              </div>
              <div class="fileinput-preview fileinput-exists thumbnail"></div>
              <div>
                <span class="btn btn-default btn-file">
                  <span class="fileinput-new">Pilih</span>
                  <span class="fileinput-exists">Ganti</span>
                  <input type="file" id="cover" name="cover" accept="image/*">
                </span>
                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Batal</a>
                @if (isset($data->cover))
                  <small class="help-inline-table">
                    <label class="pull-right">
                      <input type="checkbox" name="cover_remove" id="cover_remove"> Hapus
                    </label>
                  </small>
                @endif
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="info-subscriber">Pelanggan Artikel</label><br>
            <div class="col-sm-12 col-xs-12 no-padding">
              <label>
                <input name="info_subscriber" type="checkbox" value="1" {{ bool(app('site')->enable_subscribe) ? 'checked' : '' }}> Infokan ke Pelanggan
              </label>
            </div>
          </div>
          <div class="form-group">
            <label for="visitor">Pengunjung</label><br>
            <div class="col-sm-4 col-xs-4 no-padding">
              <label>
                @php
                  if (isset($data)) {
                    $is_comment = bool($data->comment) ? 'checked' : '';
                  } else {
                    $is_comment = bool(app('site')->enable_comment) ? 'checked' : '';
                  }
                @endphp
                <input name="comment" type="checkbox" value="yes" {{ $is_comment }}> Komentar
              </label>
            </div>
            <div class="col-sm-4 col-xs-4 no-padding">
              <label>
                @php
                  if (isset($data)) {
                    $is_like = bool($data->like) ? 'checked' : '';
                  } else {
                    $is_like = bool(app('site')->enable_like) ? 'checked' : '';
                  }
                @endphp
                <input name="like" type="checkbox" value="yes" {{ $is_like }}> Suka
              </label>
            </div>
            <div class="col-sm-4 col-xs-4 no-padding">
              <label>
                @php
                  if (isset($data)) {
                    $is_share = bool($data->share) ? 'checked' : '';
                  } else {
                    $is_share = bool(app('site')->enable_share) ? 'checked' : '';
                  }
                @endphp
                <input name="share" type="checkbox" value="yes" {{ $is_share }}> Sebar
              </label>
            </div>
          </div>
          <div class="form-group">
            <label for="publish">Terbitkan</label><br>
            {{-- <div class="col-sm-6 col-xs-6 no-padding">
              <label>
                <input name="status" type="radio" value="active" {{ (isset($data) && $data->status == 'inactive') ? '' : 'checked' }}> Ya
              </label>
            </div>
            <div class="col-sm-6 col-xs-6 no-padding">
              <label>
                <input name="status" type="radio" value="inactive" {{ (isset($data) && $data->status == 'inactive') ? 'checked' : '' }}>
                Tidak
              </label>
            </div> --}}

            <select class="form-control custom-select2" id="status" name="status">
              <option value="active" {{ (isset($data) && $data->status == 'actice') ? 'selected' : '' }}>Sekarang</option>
              <option value="set" {{ (isset($data) && $data->status == 'set') ? 'selected' : '' }}>Atur waktu</option>
              <option value="draft" {{ (isset($data) && $data->status == 'draft') ? 'selected' : '' }}>Draf</option>
              <option value="inactive" {{ (isset($data) && $data->status == 'inactive') ? 'selected' : '' }}>Sembunyikan</option>
            </select>
          </div>
          <div class="form-group hide" id="d-publish-time">
            <label for="time">Waktu Terbit</label><br>
            <div class="col-sm-6 col-xs-6 no-padding">
              <input type="text" class="form-control" id="date" name="date" value="{{ isset($data) ? carbon($data->published_at)->format("Y-m-d") : old('date') }}" placeholder="{{ carbon()->format("Y-m-d") }}">
            </div>
            <div class="col-sm-6 col-xs-6 no-padding">
              <input type="text" class="form-control" id="time" name="time" value="{{ isset($data) ? carbon($data->published_at)->format("H:i") : old('time') }}" placeholder="{{ carbon()->format("H:i") }}">
            </div>
            <br><br>
          </div>
          <div class="form-group">
            <label for="category">Kategori*</label>
            <a id="btn-add-category" class="btn btn-default btn-xs pull-right" data-toggle="modal" data-target="#zetth-modal" title="Tambah kategori baru"><i class="fa fa-plus"></i></a>
            <ul id="category-list">
              @if (isset($data))
                @foreach ($categories_ as $key => $value)
                <li>
                  {{ $value }}
                  <span class="pull-right"><i class="fa fa-minus-square-o" style="cursor:pointer;" onclick="_remove_category(this)" title="Hapus {{ $value }}"></i></span>
                  <input type="hidden" name="categories[]" value="{{ $value }}">
                  <input type="hidden" name="descriptions[]" value="{{ $descriptions_[$key] }}">
                  <input type="hidden" name="parents[]" value="{{ $parents_[$key] }}">
                </li>
                @endforeach
              @endif
            </ul>
            <input type="text" class="form-control" id="category" name="category" placeholder="Set kategori..">
          </div>
          <div class="form-group">
            <label for="tags">Label*</label>
            <div class="col-sm-12 no-padding">
              <input type="text" class="form-control" id="tags" name="tags" placeholder="Berikan label.." value="{{ isset($data) ? implode(",", $tags_) : '' }}">
            </div>
          </div>
          <div class="form-group btn-post">
            <br><br>
            <div class="btn-group btn-group-justified" role="group">
              <a onclick="$('#form-post').submit();" class="btn btn-warning"><i class="fa fa-edit"></i> Simpan</a>
              <a href="{{ url($current_url) }}" class="btn btn-default"><i class="fa fa-times"></i> Batal</a>
            </div>
          </div>
        </div>
        {{ isset($data) ? method_field('PUT') : '' }}
        {{ csrf_field() }}
      </form>
    </div>
  </div>
@endsection

@include('zetthcore::AdminSC.components.tinymce')

@push('styles')
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css') !!}
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/bootstrap/tagsinput/0.8.0/css/bootstrap-tagsinput.css') !!}
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/bootstrap/datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css') !!}
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/fancybox/2.1.5/css/jquery.fancybox.css') !!}
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/select2/4.0.0/css/select2.min.css') !!}
  <style>
    .mce-tinymce {
      border: 0 !important;
    }

    .mce-toolbar-grp {
      padding-left: 5px !important;
    }

    #post_title {
      font-size: 24px;
    }

    #post_url,
    #post_url_span {
      height: 20px;
      padding-left: 5px;
    }

    #post_url_span {
      padding: 3px 5px 2px 5px;
    }

    #post_content {
      font-size: 14px;
    }

    .left-side {
      border-right: 1px solid #ccc;
      min-height: 640px;
    }

    @media (max-width: 767px) {
      .left-side {
        border-right: 0;
        min-height: 200px;
      }

      #mceu_26 {
        border-bottom: 1px solid #ccc !important;
      }
    }

    .select2-container {
      width: 100% !important;
    }

    #category-list {
      padding-left: 0;
      margin-bottom: 5px;
    }

    #category-list li {
      display: inline-block;
      margin: 5px 0;
      padding: 5px;
      border: 1px solid #ccc;
      color: coral;
      border-radius: 4px;
    }

    #category-list li:hover {
      background: #f9f9f9
    }

    #category-list li span {
      margin-left: 8px;
      position: relative;
      top: 1px;
    }

    .bootstrap-tagsinput {
      border: 0;
      box-shadow: none;
      padding: 0;
    }

    .bootstrap-tagsinput .tag {
      /* display: block; */
      margin: 5px 0;
      padding: 5px;
      border: 1px solid #ccc;
      color: coral;
      border-radius: 4px;
      line-height: 35px;
    }

    .bootstrap-tagsinput .twitter-typeahead {
      display: block;
      margin: 5px 0;
      margin-top: 3px;
      padding: 5px;
      border: 1px solid #ccc;
      color: coral;
      border-radius: 4px;
    }

    .bootstrap-tagsinput .twitter-typeahead .empty-message {
      color: #555;
    }

    .bootstrap-tagsinput .tag [data-role="remove"]:after {
      font-family: 'FontAwesome';
      content: "\f147";
      padding: 0;
      position: relative;
      top: 1px;
    }
  </style>
@endpush

@push('scripts')
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/bootstrap/tagsinput/0.8.0/js/bootstrap-tagsinput.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/moment/2.13.0/js/moment.min.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/bootstrap/datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/fancybox/2.1.5/js/jquery.fancybox.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/typeahead/0.11.1/js/typeahead.bundle.min.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/select2/4.0.0/js/select2.min.js') !!}
  <script>
    var selected = ['{!! isset($data) ? implode("','", $categories_ ) : '' !!}'];
    var lsH,tmH = 0;
    $(function () {
      $('#date').datetimepicker({
        format: 'YYYY-MM-DD'
      });
      $('#time').datetimepicker({
        format: 'HH:mm'
      });
      $(".custom-select2").select2({
        minimumResultsForSearch: Infinity
      });
      _resize_tinymce();
    });

    $(document).ready(function(){
      var wFB = window.innerWidth - 30;
      var hFB = window.innerHeight - 60;
      /* var fImage = {{ isset($data->images) ? count($data->images) : 1 }}; */
      
      $('input').on('keypress', function(e){
        key = e.keyCode;
        if (key == 13) {
          e.preventDefault();
        }
      });

      $('#status').on("change", function(){
        if ($('#status').val() == 'set') {
          $('#d-publish-time').removeClass('hide');
        } else {
          $('#d-publish-time').addClass('hide');
        }
      });

      $('#btn-add-category').on('click', function() {
        var categories = "";
        @if (isset($categories) && count($categories) > 0)
          @foreach (generateArrayLevel($categories, 'subcategory') as $category)
            categories += '<option value="{{ $category->id }}" {{ isset($data) && ($data->parent_id == $category->id) ? 'selected' : '' }}>{!! $category->name !!}</option>';
          @endforeach
        @endif
        var inp = '<form class="form-horizontal" role="form">';
          inp+= '<div class="form-group"><label class="control-label col-sm-4" for="category_name">Kategori</label><div class="col-sm-6"><input type="text" class="form-control" id="category_name" placeholder="Nama kategori.." maxlength="30"></div></div>';
          inp+= '<div class="form-group"><label class="control-label col-sm-4" for="category_desc">Deskripsi</label><div class="col-sm-6"><textarea id="category_desc" name="category_desc" class="form-control" placeholder="Penjelasan singkat kategori.."></textarea></div></div>';
          inp+= '<div class="form-group"><label class="control-label col-sm-4" for="category_parent">Induk</label><div class="col-sm-6"><select id="category_parent" name="category_parent" class="form-control custom-select2"><option value="">[Tidak ada]</option>'+categories+'</select></div></div>';
          inp+= '</form>';
        var btn = '<button type="button" class="btn btn-default" data-dismiss="modal" id="btn-modal-cancel">Batal</button> <button type="button" class="btn btn-warning" data-dismiss="modal" id="btn-modal-add">Tambah</button>';

        $('.modal-title').text('Tambah Kategori');
        $('.modal-body').html(inp);
        $('.modal-footer').html(btn);

        $('#zetth-modal').on('shown.bs.modal', function () {
          $('#category_name').select();
        });

        $('#btn-modal-add').on('click', function(){
          var par = {
            name: $('#category_name').val(),
            desc: $('#category_desc').val(),
            parent: $('#category_parent').val()
          };
          _insert_new_category(par);
        });

        $('input').on('keypress', function(e){
          key = e.keyCode;
          if (key==13) {
            e.preventDefault();
          }
        });

        $(".custom-select2").select2({
          minimumResultsForSearch: Infinity
        });
      });

      @if (!isset($data)) 
        var title = $('#title');
        var slug = $('#slug');

        $('#title').on('keyup blur', function(){
          ttl_val = title.val();
          if (ttl_val == "") {
            slug.val('');
          } else {
            url = _get_slug(ttl_val);
            slug.val(url);
          }
        });

        $('#slug').on('dblclick', function(){
          slug.focus();
          slug.attr("readonly", false);
        });

        $('#slug').on('blur', function(){
          ro = slug.attr('readonly');
          if (!ro) {
            sl_val = slug.val();
            slug.val(_get_slug(sl_val));
            slug.attr("readonly", true);
          }
        });
      @endif

      $('#title').on('keydown', function(e){
        if (e.keyCode == 9){
          setTimeout(function() {
            $('#excerpt').focus();
          }, 0);
        }
      });

      /* categories typeahead */
      var categories = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
          url: "{{ url(adminPath() . '/ajax/term/categories') }}",
          cache: false,
          filter: function(list) {
            return $.map(list, function(category) {
              return { name: category };
            });
          }
        }
      });
      categories.initialize();

      $('#category').typeahead({
          minLength: 1
        }, {
        name: 'categories',
        displayKey: 'name',
        valueKey: 'name',
        source: categories.ttAdapter(),
        templates: {
          empty: '<div class="empty-message" style="padding:0 10px;">Kategori tidak ada, silakan buat baru..</div>'
        }
      }).on('typeahead:selected typeahead:autocompleted', function(e, val) {
        if ($.inArray(val.name, selected)<0){
          var par = {
            name: val.name,
            desc: '',
            parent: '',
          };
          _insert_new_category(par);
          selected.push(val.name);
        }
        $('#category').typeahead('val', '');
      });

      /* tagsinput */
      var tags = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
          url: "{{ url(adminPath() . '/ajax/term/tags') }}",
          cache: false,
          filter: function(list) {
          return $.map(list, function(tag) {
            return { name: tag }; });
          }
        }
      });
      tags.initialize();
      
      $('#tags').tagsinput({
        tagClass: function(item){
          /* return 'label label-warning' */
        },
        typeaheadjs: {
          name: 'tags',
          displayKey: 'name',
          valueKey: 'name',
          source: tags.ttAdapter(),
          templates: {
            empty: '<div class="empty-message" style="padding:0 10px;">Label tidak ada, tekan enter untuk buat baru..</div>'
          }
        }
      });
    });

    function _insert_new_category(par) {
      var cat = '<li>'+par.name+'<span class="pull-right"><i class="fa fa-minus-square-o" style="cursor:pointer;" onclick="_remove_category(this)" title="Remove '+par.name+'"></i></span>'
          +'<input type="hidden" name="categories[]" value="'+par.name+'">'
          +'<input type="hidden" name="descriptions[]" value="'+par.desc+'">'
          +'<input type="hidden" name="parents[]" value="'+par.parent+'">'
          +'</li>';
      $('#category-list').append(cat);
    }

    function _resize_tinymce(){
      setTimeout(function(){
        _resize_tinymce();
      }, 0);
      lsH = $('.right-side').height();
      tmH = lsH - 210;
      $('#mceu_25 iframe').height(tmH);
    }

    function _remove_category(el){
      txt = $(el).closest('li').text();
      slIdx = selected.indexOf(txt);
      if (slIdx > -1) {
        selected.splice(slIdx, 1);
      }
      $(el).closest('li').remove();
    }
  </script>
@endpush