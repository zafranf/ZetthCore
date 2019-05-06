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
      if ($term->type=="tag")
        $tags_[] = $term->name;
    }
  }

  $urlFilemanager = url('/themes/admin/AdminSC/plugins/filemanager/dialog.php?type=1&field_id=cover&lang=id&fldr=/');
@endphp
@extends('admin.AdminSC.layouts.main')

@section('content')
  <div class="panel-body no-padding-bottom">
    <div class="row" style="margin-top:-15px;">
      <form id="form-post" action="{{ url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
        <div class="col-sm-8 col-md-9 left-side no-padding">
          <input type="text" id="title" class="form-control {{ isset($data)   ? '' :   'autofocus' }} no-border-top-right no-border-left no-radius input-lg" name="title" placeholder="Judul.." maxlength="100" value="{{ isset($data) ? $data->title : '' }}">
          <div class="input-group">
            <span class="input-group-addon no-border-top-right no-border-left no-radius input-sm" id="url_span">{{ url('/post/') }}/</span>
            <input type="text" id="slug" class="form-control no-border-top-right no-radius input-sm" name="slug" placeholder="Tautan.. (klik 2x untuk edit)" readonly value="{{ isset($data) ? $data->slug : '' }}">
          </div>
          <textarea id="excerpt" name="excerpt" class="form-control no-border-top-right no-border-left no-radius input-xlarge" placeholder="Kutipan.." rows="3">{{ isset($data) ? $data->excerpt : '' }}</textarea>
          <textarea id="content" name="content" class="form-control no-border-top-right no-border-bottom no-radius input-xlarge" placeholder="Ketikkan tulisan anda di sini...">{{ isset($data) ? $data->content : '' }}</textarea>
        </div>
        <div class="col-sm-4 col-md-3 right-side">
          <div class="form-group" style="padding-top:10px;">
            <label for="cover">Sampul</label><br>
            <div class="zetth-upload">
              <div class="zetth-upload-new thumbnail">
                <img src="{!! _get_image(isset($data) ? $data->cover : '') !!}">
              </div>
              <div class="zetth-upload-exists thumbnail"></div>
              <div>
                <span class="btn btn-default">
                  <a href="{{ $urlFilemanager }}" type="button" class="zetth-upload-new" id="btn-upload">Pilih</a>
                  <a href="{{ $urlFilemanager }}" type="button" class="zetth-upload-exists" id="btn-upload">Ganti</a>
                </span>
                <span class="btn btn-default" style="display:none;">
                  <a type="button" class="zetth-upload-exists" id="btn-remove">Batal</a>
                </span>
                <input name="cover" id="cover" type="hidden" accept="image/*">
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
          {{-- <div class="form-group">
            <label for="featured_image">Featured Image</label>
            <a id="btn-add-featured-image" class="btn btn-default btn-xs pull-right" title="Add a Featured Image"><i class="fa fa-plus"></i></a>
            <div class="row">
              <div class="col-md-12" id="featured-images">
                @if (isset($data) ) 
                  @foreach ($data->images as $key => $image)
                    <div class="input-group" id="box-featured-image{{ $key+1 }}">
                        <input type="text" class="form-control featured_images" name="featured_image[]" id="featured_image{{ $key+1 }}" readonly value="{{ $image->image_file }}">
                        <a href="/assets/plugins/filemanager/dialog.php?type=1&field_id=featured_image{{ $key+1 }}&lang=id&fldr=/" class="input-group-addon" id="btn-add-featured-image{{ $key+1 }}" style="display:none;"><i class="fa fa-search"></i></a>
                      <a class="input-group-addon" onclick="_remove_featured({{ $key+1 }})" style="cursor:pointer;"><i class="fa fa-times"></i></a>
                    </div>
                  @endforeach
                @endif
              </div>
            </div>
          </div> --}}
          <div class="form-group">
            <label for="category">Kategori*</label>
            <a id="btn-add-category" class="btn btn-default btn-xs pull-right" data-toggle="modal" data-target="#zetth-modal" title="Add a New Category"><i class="fa fa-plus"></i></a>
            <ul id="category-list">
              @if (isset($data)) 
                @foreach ($categories_ as $key => $value)
                  <li>
                    {{ $value }}
                    <span class="pull-right"><i class="fa fa-minus-square-o" style="cursor:pointer;" onclick="_remove_category(this)" title="Remove {{ $value }}"></i></span>
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
          <div class="form-group">
            <label for="visitor">Pengunjung</label><br>
            <div class="col-sm-4 col-xs-4 no-padding">
              <label>
                <input name="comment" type="checkbox" {{ (isset($data) && ($data->comment)) ? 'checked' : ($apps->enable_comment) ? 'checked' : '' }}> Komentar
              </label>
            </div>
            <div class="col-sm-4 col-xs-4 no-padding">
              <label>
                <input name="like" type="checkbox" {{ (isset($data) && ($data->like)) ? 'checked' : ($apps->enable_like) ? 'checked' : '' }}> Suka
              </label>
            </div>
            <div class="col-sm-4 col-xs-4 no-padding">
              <label>
                <input name="share" type="checkbox" {{ (isset($data) && ($data->share)) ? 'checked' : ($apps->enable_share) ? 'checked' : '' }}> Sebar
              </label>
            </div>
          </div>
          <div class="form-group">
            <label for="publish">Terbitkan</label><br>
            <div class="col-sm-6 col-xs-6 no-padding">
              <label>
                <input name="status" type="radio" value="1" {{ (isset($data) && (!$data->status)) ? '' : 'checked' }}> Ya
              </label>
            </div>
            <div class="col-sm-6 col-xs-6 no-padding">
              <label>
                <input name="status" type="radio" value="0" {{ (isset($data) && (!$data->status)) ? 'checked' : '' }}> Tidak
              </label>
            </div>
          </div>
          <div class="form-group">
            <label for="time">Waktu</label><br>
            <div class="col-sm-6 col-xs-6 no-padding">
              <input type="text" class="form-control" id="date" name="date" value="{{ isset($data) ? date("Y-m-d", strtotime($data->published_at)):date("Y-m-d") }}" placeholder="{{ isset($data) ? date("Y-m-d", strtotime($data->published_at)):date("Y-m-d") }}">
            </div>
            <div class="col-sm-6 col-xs-6 no-padding">
              <input type="text" class="form-control" id="time" name="time" value="{{ isset($data) ? date("H:i", strtotime($data->published_at)) : date("H:i") }}" placeholder="{{ isset($data) ? date("H:i", strtotime($data->published_at)) : date("H:i") }}">
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

@section('styles')
  {{-- {!! _load_css('themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css') !!} --}}
	{!! _load_css('themes/admin/AdminSC/plugins/bootstrap/tagsinput/0.8.0/css/bootstrap-tagsinput.css') !!}
	{!! _load_css('themes/admin/AdminSC/plugins/bootstrap/datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css') !!}
  {!! _load_css('themes/admin/AdminSC/plugins/fancybox/2.1.5/css/jquery.fancybox.css') !!}
	{!! _load_css('themes/admin/AdminSC/plugins/select2/4.0.0/css/select2.min.css') !!}
  <style>
    #mceu_15 {
      position: absolute;
      right: 10px;
    }
    /* @if ($isDesktop)
      textarea#mceu_34 {
        height: 458px!important;
      }
      #mceu_32, #mceu_32-body, #mceu_31-body {
        max-height: 548px!important;
      }
      #mceu_31 {
        height: 638px!important;
      }
    @else
      textarea#mceu_33 {
        height: 376px!important;
      }
      #mceu_31, #mceu_31-body, #mceu_30-body {
        height: 466px!important;
      }
      #mceu_30 {
        height: 556px!important;
      }
    @endif */
    .mce-tinymce {
      border: 0!important;
    }
    .mce-fullscreen {
      z-index: 9999!important;
    }
    .mce-toolbar-grp {
      padding-left: 5px!important;
    }
    #post_title {
      font-size: 24px;
    }
    #post_url, #post_url_span {
      height: 20px;
      padding-left: 5px;
    }
    #post_url_span {
      padding:3px 5px 2px 5px;
    }
    #post_content {
      font-size: 14px;
    }
    .left-side {
      border-right: 1px solid #ccc;
      min-height: 640px;
    }
    
    .zetth-upload a {
      text-decoration: none;
    }
    .zetth-upload .thumbnail {
      margin-bottom: 5px;
    }
    .zetth-upload-exists {
      display: none;
    }
    @media (max-width: 767px) {
      .left-side {
        border-right: 0;
        min-height: 200px;
      }
      #mceu_26 {
        border-bottom: 1px solid #ccc!important;
      }
    }
    .select2-container {
      width: 100%!important;
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
    
    .bootstrap-tagsinput .tag [data-role="remove"]:after {
      font-family: 'FontAwesome'; 
      content: "\f147";
      padding: 0;
      position: relative;
      top: 1px;
    }
  </style>
@endsection

@section('scripts')
  {{-- {!! _load_js('themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js') !!} --}}
  {!! _load_js('themes/admin/AdminSC/plugins/bootstrap/tagsinput/0.8.0/js/bootstrap-tagsinput.js') !!}
  {!! _load_js('themes/admin/AdminSC/plugins/moment/2.13.0/js/moment.min.js') !!}
  {!! _load_js('themes/admin/AdminSC/plugins/bootstrap/datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js') !!}
  {!! _load_js('themes/admin/AdminSC/plugins/fancybox/2.1.5/js/jquery.fancybox.js') !!}
  {!! _load_js('themes/admin/AdminSC/plugins/typeahead/0.11.1/js/typeahead.bundle.min.js') !!}
  {!! _load_js('themes/admin/AdminSC/plugins/tinymce/4.3.2/tinymce.min.js') !!}
	{!! _load_js('themes/admin/AdminSC/plugins/select2/4.0.0/js/select2.min.js') !!}
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
      _resize_tinymce();
    });

    function responsive_filemanager_callback(field_id){
      var url = $('#'+field_id).val().replace(SITE_URL, "");
      var img = '<img src="'+url+'">';
      if (field_id.indexOf("featured") < 0) {
        $('.zetth-upload-new').hide();
        $('.zetth-upload-exists').show();
        $('.zetth-upload-exists.thumbnail').html(img);
        $('#btn-remove').parent().show();
        $('#cover_remove').attr("checked", false);
      }/*  else {
        url = url.replace('/storage/assets/images/upload/', "");
      } */
      $('#'+field_id).val(url.replace('/storage', ''));
    }

    $(document).ready(function(){
      var wFB = window.innerWidth - 30;
      var hFB = window.innerHeight - 60;
      // var fImage = {{ isset($data) ? count($data->images) : 1 }};
      
      $('input').on('keypress', function(e){
        key = e.keyCode;
        if (key==13) {
          e.preventDefault();
        }
      });

      $('#btn-upload').fancybox({
        type      : 'iframe',
        autoScale : false,
        autoSize : true,
        beforeLoad : function() {
          this.width  = wFB;
          this.height = hFB;
        }/*,
        afterClose : function(){
          alert('from iframe btn');
        }*/
      });
      $('#btn-remove').on('click', function(){
        $('#cover').val('');
        $('.zetth-upload-new').show();
        $('.zetth-upload-exists').hide();
        $('#btn-remove').parent().hide();
      });
      tinymce.init({
        relative_urls: false,
        selector: '#content',
        /*codesample_dialog_height: 300,*/
        height: (lsH-190),
        skin: 'custom',
        language: 'id',
        plugins: [
          "advlist autolink link image lists charmap print preview hr anchor pagebreak",
          "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
          "table contextmenu directionality emoticons paste textcolor code codesample",
          "placeholder youtube fullscreen"
        ],
        toolbar: "undo redo | bullist numlist blockquote | link unlink | youtube image table | styleselect fontselect | fontsizeselect codesample code fullscreen",
        image_advtab: true,
        image_caption: true,
        menubar: false,
        external_filemanager_path:"{{ asset('/themes/admin/AdminSC/plugins/filemanager/') }}/",
        filemanager_title:"Filemanager",
        filemanager_folder: '/',
        filemanager_language: 'id',
        external_plugins: { "filemanager" : "{{ asset('/themes/admin/AdminSC/plugins/filemanager/plugin.min.js') }}" }
      });

      /* $('#btn-add-featured-image').on('click', function(){
        if ($('.featured_images').length>=5) {
          alert('Max 5 featured images');
          return false;
        }
        
        var html = '<div class="input-group" id="box-featured-image'+fImage+'">'+
                  '<input type="text" class="form-control featured_images" name="featured_image[]" id="featured_image'+fImage+'" readonly>'+
                  '<a href="/themes/admin/AdminSC/plugins/filemanager/dialog.php?type=1&field_id=featured_image'+fImage+'&lang=id&relative_url=1&fldr=/" class="input-group-addon" id="btn-add-featured-image'+fImage+'" style="display:none;"><i class="fa fa-search"></i></a>'+
                '<a class="input-group-addon" onclick="_remove_featured('+fImage+')" style="cursor:pointer;"><i class="fa fa-times"></i></a>'+
              '</div>';
        $('#featured-images').append(html);

        $('#btn-add-featured-image'+fImage).fancybox({
          type      : 'iframe',
          autoScale : false,
          autoSize : false,
          beforeLoad : function() {
            this.width  = wFB;
            this.height = hFB;
            console.log('aaaa', this.width)
            console.log('aaaa', wFB)
          },
          afterClose : function(){
            fimg = fImage-1;
            if ($('#featured_image'+fimg).val()=="") {
              _remove_featured(fimg);
            }
          }
        });

        $('#btn-add-featured-image'+fImage).click();
        fImage++;
      }); */

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
          if (ttl_val=="") {
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
          if (!ro){
            sl_val = slug.val();
            slug.val(_get_slug(sl_val));
            slug.attr("readonly", true);
          }
        });
      @endif

      $('#title').on('keydown', function(e){
        if (e.keyCode==9){
          setTimeout(function(){
            $('#excerpt').focus();
          },0);
        }
      });
      /*$('#excerpt').on('keydown', function(e){
        if (e.keyCode==9 && e.keyCode==16){
          setTimeout(function(){
            $('#title').focus();
          },0);
        }
      });*/

      /* categories typeahead */
      var categories = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
          url: "{{ url($adminPath . '/ajax/term/categories') }}",
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
        /*templates: {
          empty: '<div class="empty-message" style="padding-left:10px;">No matches.</div>'
        }*/
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
          url: "{{ url($adminPath . '/ajax/term/tags') }}",
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
          // return 'label label-warning'
        },
        typeaheadjs: {
          name: 'tags',
          displayKey: 'name',
          valueKey: 'name',
          source: tags.ttAdapter(),
          /*templates: {
            empty: '<div class="empty-message" style="padding-left:10px;">No matches.</div>'
          }*/
        }
      });
      /*resize tinymce height when add tags*/
      /*$('#tags').on('itemAdded', function(){

      });*/
      /*resize tinymce height when remove tags*/
      /*$('#tags').on('itemRemoved', function(){

      });*/
      /*resize tinymce height when change cover*/
      /*$('.fileinput').on('change.bs.fileinput', function(){

      });*/
      /*resize tinymce height when remove cover*/
      /*$('a.fileinput-exists').on('click', function(){
        setTimeout(function(){

        }, 0);
      });*/
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

    function _remove_featured(el){
      $('#box-featured-image'+el).remove();
    }
  </script>
@endsection
