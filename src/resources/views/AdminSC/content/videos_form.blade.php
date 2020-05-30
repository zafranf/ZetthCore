@extends('zetthcore::AdminSC.layouts.main')

@section('content')
	<div class="panel-body">
    <form class="form-horizontal" action="{{ _url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post">
      @if (isset($data))
        <div class="form-group">
          <label for="cover" class="col-sm-2 control-label">Sampul</label>
          <div class="col-sm-10">
            <div class="col-sm-3 no-padding">
              <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail">
                  <img src="https://img.youtube.com/vi/{{ $data->cover }}/mqdefault.jpg">
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
			<div class="form-group">
				<label for="cover" class="col-sm-2 control-label">Kode YouTube</label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-addon" id="slug_span">{{ url("https://youtube.com/watch?v=") }}</span>
						<input type="text" id="cover" class="form-control autofocus" name="cover" placeholder="Kode video youtube.." value="{{ $data->cover ?? old('cover') }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="title" class="col-sm-2 control-label">Judul</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="title" name="title" value="{{ $data->title ?? old('title') }}" maxlength="100" placeholder="Judul video..">
				</div>
			</div>
			<div class="form-group">
				<label for="content" class="col-sm-2 control-label">Deskripsi</label>
				<div class="col-sm-10">
					<textarea id="content" name="content" class="form-control" placeholder="Deskripsi tentang video..">{{ $data->content ?? old('content') }}</textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="status" value="active" {{ (isset($data) && $data->status == 'inactive') ? '' : 'checked' }}> Aktif
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
          {{ isset($data) ? method_field('PUT') : '' }}
          {{ csrf_field() }}
          {{ getButtonPost($current_url, true, $data->id ?? '') }}
				</div>
			</div>
		</form>
	</div>
@endsection

@include('zetthcore::AdminSC.components.tinymce')

@push('scripts')
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/tinymce/4.3.2/tinymce.min.js') !!}
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

      setTimeout(function(){
        $('#mceu_25 iframe').height(200);
      }, 500);
    });
  </script>
@endpush
