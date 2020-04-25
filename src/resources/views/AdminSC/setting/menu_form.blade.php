@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
    <form class="form-horizontal" action="{{ url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Nama Menu</label>
        <div class="col-sm-4">
          <input type="text" class="form-control autofocus" id="name" name="name" value="{{ isset($data) ? $data->name : old('name') }}" maxlength="100" placeholder="Nama menu..">
        </div>
      </div>
      <div class="form-group">
        <label for="description" class="col-sm-2 control-label">Deskripsi</label>
        <div class="col-sm-4">
          <textarea id="description" name="description" class="form-control" placeholder="Penjelasan singkat menu.." rows="4">{{ isset($data) ? $data->description : old('description') }}</textarea>
        </div>
      </div>
      <div class="form-group {{ Auth::user()->hasRole('super') ? '' : 'hide' }}">
        <label for="route_name" class="col-sm-2 control-label">Route</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="route_name" name="route_name" value="{{ isset($data) ? $data->route_name : '' }}" maxlength="100" placeholder="Route name..">
        </div>
      </div>
      <div class="form-group">
        <label for="url" class="col-sm-2 control-label">Tautan</label>
        <div class="col-sm-4">
          <select id="url" name="url" class="form-control select2">
            <option value="#">[Tidak ada]</option>
            <option value="external" {{ (isset($data) && $data->url_external == 'yes') ? 'selected' : '' }}>[Tautan Luar]</option>
            <option value="/" {{ (isset($data) && $data->url == "/" ) ? 'selected' : '' }}>Beranda</option>
            <option value="{{ env('POSTS_PATH', 'posts') }}" {{ (isset($data) && $data->url == env('POSTS_PATH', 'posts') ) ? 'selected' : '' }}>Artikel</option>
            {{-- <option value="{{ env('PAGES_PATH', 'pages') }}" {{ (isset($data) && $data->url == env('PAGES_PATH', 'pages') ) ? 'selected' : '' }}>Halaman</option> --}}
            <option value="{{ env('ALBUMS_PATH', 'albums') }}" {{ (isset($data) && $data->url == env('ALBUMS_PATH', 'albums') ) ? 'selected' : '' }}>Galeri Foto</option>
            <option value="{{ env('VIDEOS_PATH', 'videos') }}" {{ (isset($data) && $data->url == env('VIDEOS_PATH', 'videos') ) ? 'selected' : '' }}>Galeri Video</option>
            <option value="{{ env('CONTACT_PATH', 'contact') }}" {{ (isset($data) && $data->url == env('CONTACT_PATH', 'contact') ) ? 'selected' : '' }}>Kontak</option>
            @php 
              $types = [
                'page' => 'Halaman',
                'article' => 'Artikel',
                'video' => 'Video',
              ];
              $type = ''; 
            @endphp
            @foreach ($post_opts as $n => $post)
              @if ($type != $post->type)
                {!! ($n > 0) ? '</optgroup>' : '' !!}
                @php 
                  $type = $post->type; 
                @endphp
                <optgroup label="{{ ucfirst($types[$post->type]) }}">
              @endif
              @if ($post->type == "page" || $post->type == "video")
                <option value="{{ $post->slug }}" {{ $post->slug == "#" ? 'disabled' : '' }} {{ (isset($data) && $post->slug == $data->url) ? 'selected' : '' }}>{{ $post->title }}</option>
              @elseif ($post->type == "article")
                <option value="{{ env('POST_PATH', 'post') . '/' . $post->slug }}" {{ $post->slug == "#" ? 'disabled' : '' }} {{ (isset($data) && env('POST_PATH', 'post') . '/' . $post->slug == $data->url) ? 'selected' : '' }}>{{ $post->title }}</option>
              @endif
            @endforeach
          </select>
          <input type="text" class="form-control" id="url_external" name="url_external" value="{{ isset($data) ? (($data->url=="#") ? '' : $data->url) : '' }}" placeholder="http://example.com" {!! (isset($data) && $data->url_external == 'yes' ) ? 'style="margin-top:5px;" ' : 'style="margin-top:5px;display:none;" disabled ' !!}>
        </div>
      </div>
      <div class="form-group">
        <label for="icon" class="col-sm-2 control-label">Ikon</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="icon" name="icon" placeholder="Ikon menu.." value="{{ isset($data) ? $data->icon : '' }}">
        </div>
      </div>
      <div class="form-group">
        <label for="target" class="col-sm-2 control-label">Target</label>
        <div class="col-sm-4">
          <select class="form-control custom-select2" name="target" id="target">
            <option value="_self" {{ isset($data) && ($data->target == "_self") ? 'selected' : '' }}>Jendela Aktif</option>
            <option value="_blank" {{ isset($data) && ($data->target == "_blank") ? 'selected' : '' }}>Jendela Baru</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="parent" class="col-sm-2 control-label">Induk</label>
        <div class="col-sm-4">
          <select class="form-control select2" name="parent" id="parent">
              <option value="0">[Tidak ada]</option>
              @foreach (generateArrayLevel($menus) as $menu)
                @if (isset($data) && $data->id == $menu->id)
                @else
                  <option value="{{ $menu->id }}" {{ isset($data) && ($data->parent_id == $menu->id) ? 'selected' : '' }}>{!! $menu->name !!}</option>
                @endif
              @endforeach
          </select>
        </div>
      </div>
      <div class="form-group {{ Auth::user()->hasRole('super') ? '' : 'hide' }}">
        <label for="is_crud" class="col-sm-2 control-label">Berikan Akses</label>
        <div class="col-sm-4">
          <div class="checkbox">
            <label>
              <input type="checkbox" id="is_crud" name="is_crud" value="yes" {{ (isset($data) && $data->is_crud == 'yes') ? 'checked' : '' }}> Ya
            </label>
          </div>
        </div>
      </div>
      <div class="form-group {{ Auth::user()->hasRole('super') ? '' : 'hide' }}" id="access-fields" {!! (isset($data) && $data->is_crud == 'yes') ? '' : 'style="display:none;"' !!}>
        <label for="status" class="col-sm-2 control-label">Akses</label>
        <div class="col-sm-4">
          <div class="checkbox">
            <div class="row">
              <div class="col-sm-2">
                <label>
                  <input type="checkbox" name="index" value="yes" {{ (isset($data) && $data->index == 'no') ? '' : 'checked' }}> Daftar
                </label>
              </div>
              <div class="col-sm-2">
                <label>
                  <input type="checkbox" name="create" value="yes" {{ isset($data) && $data->create == 'yes' ? 'checked' : '' }}> Tambah
                </label>
              </div>
              <div class="col-sm-2">
                <label>
                  <input type="checkbox" name="read" value="yes" {{ isset($data) && $data->read == 'yes' ? 'checked' : '' }}> Detail
                </label>
              </div>
              <div class="col-sm-2">
                <label>
                  <input type="checkbox" name="update" value="yes" {{ isset($data) && $data->update == 'yes' ? 'checked' : '' }}> Edit
                </label>
              </div>
              <div class="col-sm-2">
                <label>
                  <input type="checkbox" name="delete" value="yes" {{ isset($data) && $data->delete == 'yes' ? 'checked' : '' }}> Hapus
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="status" value="active" {{ (isset($data) && $data->status == 'inactive') ? '' : 'checked' }}> Aktif
            </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          <input type="hidden" name="group" value="{{ _get('group') }}">
          {{ isset($data) ? method_field('PUT') : '' }}
          {{ csrf_field() }}
          {{ getButtonPost($current_url, true, $data->id ?? '') }}
        </div>
      </div>
    </form>
  </div>
@endsection

@push('styles')
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/select2/4.0.0/css/select2.min.css') !!}
@endpush

@push('scripts')
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/select2/4.0.0/js/select2.min.js') !!}
  <script>
    $(function(){
      $(".select2").select2();
      $(".custom-select2").select2({
        minimumResultsForSearch: Infinity
      });
    });

    $(document).ready(function(){
      $('#url').on('change', function(){
        if ($('#url').val() == "external"){
          $('#url_external').attr("disabled", false).show();
        } else {
          $('#url_external').attr("disabled", true).hide();
        }
      });
      
      $('#is_crud').on('click', function() {
        let checked = $('#is_crud').prop('checked');
        if (checked) {
          $('#access-fields').show();
        } else {
          $('#access-fields').hide();
        }
      })
    });
  </script>
@endpush