@extends('zetthcore::AdminSC.layouts.main')

@section('content')
	<div class="panel-body">
		<form class="form-horizontal" action="{{ url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post">
			<div class="form-group">
				<label for="cover" class="col-sm-2 control-label">Kode Youtube</label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-addon" id="slug_span">{{ url("https://youtube.com/watch?v=") }}</span>
						<input type="text" id="cover" class="form-control autofocus" name="cover" placeholder="Kode video youtube.." value="{{ isset($data) ? $data->cover : '' }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="title" class="col-sm-2 control-label">Judul</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="title" name="title" value="{{ isset($data) ? $data->title : '' }}" maxlength="100" placeholder="Judul video..">
				</div>
			</div>
			<div class="form-group">
				<label for="content" class="col-sm-2 control-label">Deskripsi</label>
				<div class="col-sm-10">
					<textarea id="content" name="content" class="form-control" placeholder="Deskripsi tentang video..">{{ isset($data) ? $data->content : '' }}</textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="status" value="1" {{ (isset($data) && $data->status == 0) ? '' : 'checked' }}> Aktif
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
                    {{ isset($data) ? method_field('PUT') : '' }}
                    {{ csrf_field() }}
                    {{ _get_button_post($current_url, true, $data->id ?? '') }}
				</div>
			</div>
		</form>
	</div>
@endsection

@section('styles')
  <style>
    .mce-fullscreen {
        z-index: 9999!important;
    }
    #mceu_9 {
      position: absolute;
      right: 4px;
      top: 4px;
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
        height: 200,
        plugins: [
          "advlist autolink link image lists charmap print preview hr anchor pagebreak",
          "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
          "table contextmenu directionality emoticons paste textcolor responsivefilemanager code",
          "placeholder youtube fullscreen"
        ],
        toolbar1: "undo redo | bold italic underline blockquote | link unlink | image | fullscreen",
        image_advtab: true,
        image_caption: true,
        menubar : false,
        external_filemanager_path:"{{ asset('larafile/') }}/",
        filemanager_title:"Filemanager",
        filemanager_folder: '/images',
        filemanager_language: 'id',
        external_plugins: { "filemanager" : "{{ asset('themes/admin/AdminSC/plugins/filemanager/plugin.min.js') }}" },
        setup : function(ed) {
            ed.on('init', function() 
            {
                // this.getDoc().body.style.fontSize = '12px';
                this.getDoc().body.style.fontFamily = 'arial, helvetica, sans-serif';
                // this.getDoc().body.style.fontWeight = '300';
            });
        }
      });
    });
  </script>
@endsection
