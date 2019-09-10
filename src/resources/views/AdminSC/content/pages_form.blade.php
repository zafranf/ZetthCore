@extends('zetthcore::AdminSC.layouts.main')

@section('content')
	<div class="panel-body">
		<form class="form-horizontal" action="{{ url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post">
			<div class="form-group">
				<label for="title" class="col-sm-2 control-label">Judul</label>
				<div class="col-sm-10">
					<input type="text" class="form-control autofocus" id="title" name="title" value="{{ isset($data) ? $data->title : '' }}" maxlength="100" placeholder="Judul halaman..">
				</div>
			</div>
			<div class="form-group">
				<label for="slug" class="col-sm-2 control-label">Tautan</label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-addon" id="slug_span">{{ url("/") }}/</span>
						<input type="text" id="slug" class="form-control" name="slug" placeholder="Sesuaikan tautan.." value="{{ isset($data) ? $data->slug : '' }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="content" class="col-sm-2 control-label">Konten</label>
				<div class="col-sm-10">
					<textarea id="content" name="content" class="form-control" placeholder="Tulis konten di sini..">{{ isset($data) ? $data->content : '' }}</textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="status" {{ (isset($data) && $data->status==0) ? '' : 'checked' }}> Aktif
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
          {{ isset($data) ? method_field('PUT') : '' }}
          {{ csrf_field() }}
				  {{ _get_button_post($current_url) }}
				</div>
			</div>
		</form>
	</div>
@endsection

@section('styles')
  <style>
    #mceu_14 {
      position: absolute;
      right: 10px;
    }
  </style>
@endsection

@section('scripts')
  {!! _admin_js('themes/admin/AdminSC/plugins/tinymce/4.3.2/tinymce.min.js') !!}
  <script>
    $(document).ready(function(){
      $('#title').on('keyup blur', function(){
        var slug = _get_slug($(this).val());
        $('#slug').val(slug);
      });

      $('#slug').on('blur', function(){
        var slug = _get_slug($(this).val());
        $('#slug').val(slug);
      });

      tinymce.init({
        relative_urls: false,
        selector: '#content',
        skin: 'custom',
        language: 'id',
        height: 300,
        plugins: [
          "advlist autolink link image lists charmap print preview hr anchor pagebreak",
          "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
          "table contextmenu directionality emoticons paste textcolor responsivefilemanager code",
          "placeholder youtube fullscreen"
        ],
        toolbar1: "undo redo | bullist numlist blockquote | link unlink | youtube image table | styleselect fontselect | fontsizeselect code | fullscreen",
        image_advtab: true,
        image_caption: true,
        menubar : false,
        external_filemanager_path:"{{ asset('larafile/') }}/",
        filemanager_title:"Filemanager",
        filemanager_subfolder: '/images',
        filemanager_language: 'id',
        external_plugins: { "filemanager" : "{{ asset('themes/admin/AdminSC/plugins/filemanager/plugin.min.js') }}" },
        file_picker_callback: function(cb, value, meta) {
        var width = window.innerWidth - 30;
        var height = window.innerHeight - 60;
        if (width > 1800) width = 1800;
        if (height > 1200) height = 1200;
        if (width > 600) {
            var width_reduce = (width - 20) % 138;
            width = width - width_reduce + 10;
        }
        var urltype = 2;
        if (meta.filetype == 'image') {
            urltype = 1;
        }
        if (meta.filetype == 'media') {
            urltype = 3;
        }
        var title = "RESPONSIVE FileManager";
        if (typeof this.settings.filemanager_title !== "undefined" && this.settings.filemanager_title) {
            title = this.settings.filemanager_title;
        }
        var akey = "key";
        if (typeof this.settings.filemanager_access_key !== "undefined" && this.settings.filemanager_access_key) {
            akey = this.settings.filemanager_access_key;
        }
        var sort_by = "";
        if (typeof this.settings.filemanager_sort_by !== "undefined" && this.settings.filemanager_sort_by) {
            sort_by = "&sort_by=" + this.settings.filemanager_sort_by;
        }
        var descending = "false";
        if (typeof this.settings.filemanager_descending !== "undefined" && this.settings.filemanager_descending) {
            descending = this.settings.filemanager_descending;
        }
        var fldr = "";
        if (typeof this.settings.filemanager_subfolder !== "undefined" && this.settings.filemanager_subfolder) {
            fldr = "&fldr=" + this.settings.filemanager_subfolder;
        }
        var crossdomain = "";
        if (typeof this.settings.filemanager_crossdomain !== "undefined" && this.settings.filemanager_crossdomain) {
            crossdomain = "&crossdomain=1";
            // Add handler for a message from ResponsiveFilemanager
            if (window.addEventListener) {
                window.addEventListener('message', filemanager_onMessage, false);
            } else {
                window.attachEvent('onmessage', filemanager_onMessage);
            }
        }
        tinymce.activeEditor.windowManager.open({
            title: title,
            file: this.settings.external_filemanager_path + 'dialog.php?type=' + urltype + '&descending=' + descending + sort_by + fldr + crossdomain + '&lang=' + this.settings.language + '&akey=' + akey,
            width: width,
            height: height,
            resizable: true,
            maximizable: true,
            inline: 1
        }, {
            setUrl: function(url) {
                cb(url);
            }
        });
    },
      });
    });
  </script>
@endsection
