@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
    <form class="form-horizontal" action="{{ _url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post">
      <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Judul</label>
        <div class="col-sm-10">
          <input type="text" class="form-control autofocus" id="title" name="title"
            value="{{ isset($data) ? $data->title : old('title') }}" maxlength="100" placeholder="Judul halaman..">
        </div>
      </div>
      <div class="form-group">
        <label for="slug" class="col-sm-2 control-label">Tautan</label>
        <div class="col-sm-10">
          <div class="input-group">
            <span class="input-group-addon" id="slug_span">{{ getSiteURL("/") . '/' }}</span>
            <input type="text" id="slug" class="form-control" name="slug" placeholder="Tautan otomatis (dapat disesuaikan).." value="{{ isset($data) ? $data->slug : old('slug') }}" {{ isset($data) ? 'readonly' : '' }}>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="content" class="col-sm-2 control-label">Konten</label>
        <div class="col-sm-10">
          <textarea id="content" name="content" class="form-control" placeholder="Tulis konten di sini..">{{ isset($data) ? $data->content : old('content') }}</textarea>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="status" {{ (isset($data) && $data->status == 0) ? '' : 'checked' }}> Aktif
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
  <script>
    $(document).ready(function(){
      @if (!isset($data))
        $('#title').on('keyup blur', function(){
          var slug = _get_slug($(this).val());
          $('#slug').val(slug);
        });

        $('#slug').on('blur', function(){
          var slug = _get_slug($(this).val());
          $('#slug').val(slug);
        });
      @endif

      setTimeout(function(){
        $('#mceu_25 iframe').height(200);
      }, 500);
    });
  </script>
@endpush