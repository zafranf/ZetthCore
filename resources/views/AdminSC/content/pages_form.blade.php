@extends('admin.AdminSC.layouts.main')

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
				<label for="slug" class="col-sm-2 control-label">URL</label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-addon" id="slug_span">{{ url("/") }}/</span>
						<input type="text" id="slug" class="form-control" name="slug" placeholder="Sesuaikan URL.." value="{{ isset($data) ? $data->slug : '' }}">
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
  {!! _load_js('themes/admin/AdminSC/plugins/tinymce/4.3.2/tinymce.min.js') !!}
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
        external_filemanager_path:"{{ asset('themes/admin/AdminSC/plugins/filemanager/') }}/",
        filemanager_title:"Filemanager",
        filemanager_folder: '/',
        filemanager_language: 'id',
        external_plugins: { "filemanager" : "{{ asset('themes/admin/AdminSC/plugins/filemanager/plugin.min.js') }}" }
      });
    });
  </script>
@endsection
