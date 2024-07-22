@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
    <form class="form-horizontal" action="{{ _url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
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
            <option value="external" {{ (isset($data) && ($data->url_external) ) ? 'selected' : '' }}>[Tautan Luar]</option>
            <option value="/" {{ (isset($data) && $data->url == "/" ) ? 'selected' : '' }}>Beranda</option>
            <option value="articles" {{ (isset($data) && $data->url == "articles" ) ? 'selected' : '' }}>Artikel</option>
            {{-- <option value="pages" {{ (isset($data) && $data->url == "pages" ) ? 'selected' : '' }}>Halaman</option> --}}
            <option value="albums" {{ (isset($data) && $data->url == "albums" ) ? 'selected' : '' }}>Galeri Foto</option>
            <option value="videos" {{ (isset($data) && $data->url == "videos" ) ? 'selected' : '' }}>Galeri Video</option>
            <option value="contact" {{ (isset($data) && $data->url == "contact" ) ? 'selected' : '' }}>Kontak</option>
            @php $type = ''; @endphp
						@foreach ($post_opts as $n => $post)
							@if ($type != $post->type)
								{!! ($n > 0) ? '</optgroup>' : '' !!}
								@php $type = $post->type @endphp
								<optgroup label="{{ ucfirst($type) }}">
							@endif
							@if ($post->type == "page" || $post->type == "video")
								<option value="{{ $post->slug }}" {{ $post->slug == "#" ? 'disabled' : '' }} {{ (isset($data->id) && $post->slug == $data->url) ? 'selected' : '' }}>{{ $post->title }}</option>
							@elseif ($post->type == "article")
								<option value="{{ 'article/' . $post->slug }}" {{ $post->slug == "#" ? 'disabled' : '' }} {{ (isset($data->id) && 'article/' . $post->slug == $data->url) ? 'selected' : '' }}>{{ $post->title }}</option>
							@endif
						@endforeach
          </select>
          <input type="text" class="form-control" id="url_external" name="url_external" value="{{ isset($data) ? (($data->url=="#") ? '' : $data->url) : '' }}" placeholder="http://example.com" {!! (isset($data) && ($data->url_external) ) ? 'style="margin-top:5px;" ' : 'style="margin-top:5px;display:none;" disabled ' !!}>
        </div>
      </div>
      {{-- <div class="form-group">
        <label for="url" class="col-sm-2 control-label">URL</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="url" name="url" placeholder="Alamat URL.." value="{{ isset($data) ? $data->url : '' }}">
        </div>
      </div> --}}
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
              <input type="checkbox" id="is_crud" name="is_crud" {{ (isset($data) && $data->is_crud == 1) ? 'checked' : '' }}> Ya
            </label>
          </div>
        </div>
      </div>
      <div class="form-group {{ Auth::user()->hasRole('super') ? '' : 'hide' }}" id="access-fields" {!! (isset($data) && $data->is_crud == 1) ? '' : 'style="display:none;"' !!}>
        <label for="status" class="col-sm-2 control-label">Akses</label>
        <div class="col-sm-4">
          <div class="checkbox">
            <div class="row">
              <div class="col-sm-2">
                <label>
                  <input type="checkbox" name="index" {{ (isset($data) && $data->index == 0) ? '' : 'checked' }}> Daftar
                </label>
              </div>
              <div class="col-sm-2">
                <label>
                  <input type="checkbox" name="create" {{ isset($data) && $data->create ? 'checked' : '' }}> Tambah
                </label>
              </div>
              <div class="col-sm-2">
                <label>
                  <input type="checkbox" name="read" {{ isset($data) && $data->read ? 'checked' : '' }}> Detail
                </label>
              </div>
              <div class="col-sm-2">
                <label>
                  <input type="checkbox" name="update" {{ isset($data) && $data->update ? 'checked' : '' }}> Edit
                </label>
              </div>
              <div class="col-sm-2">
                <label>
                  <input type="checkbox" name="delete" {{ isset($data) && $data->delete ? 'checked' : '' }}> Hapus
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
              <input type="checkbox" name="status" {{ (isset($data) && $data->status == 0) ? '' : 'checked' }}> Aktif
            </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          <input type="hidden" name="group" value="{{ _get('group') }}">
          {{ isset($data) ? method_field('PUT') : '' }}
          {{ csrf_field() }}
          {{ _get_button_post($current_url, true, $data->id ?? '') }}
        </div>
      </div>
    </form>
  </div>
@endsection

@section('styles')
  {!! _admin_css('themes/admin/AdminSC/plugins/select2/4.0.0/css/select2.min.css') !!}
@endsection

@section('scripts')
  {!! _admin_js('themes/admin/AdminSC/plugins/select2/4.0.0/js/select2.min.js') !!}
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
@endsection