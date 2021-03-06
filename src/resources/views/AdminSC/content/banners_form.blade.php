@extends('zetthcore::AdminSC.layouts.main')

@php
  /* mapping order to array */
  $orders = collect($banners)->map(function($arr) {
    return $arr->id;
  })->toArray();

  /* remove current id */
  if (isset($data) && ($key = array_search($data->id, $orders)) !== false) {;
    unset($orders[$key]);
  }
@endphp

@section('content')
  <div class="panel-body">
    <form class="form-horizontal" action="{{ _url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}"
      method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="image" class="col-sm-2 control-label">
          Gambar Spanduk
          <small class="help-block">Maksimal dimensi spanduk adalah
            {{ config('site.banner.image.dimension.width') ?? 1280 }}px x 
            {{ config('site.banner.image.dimension.height') ?? 720 }}px dengan 
            rasio {{ config('site.banner.image.ratio') ?? '16:9' }} dan 
            ukuran maksimal {{ (config('site.banner.image.weight') > 512 ? 512 : config('site.banner.image.weight')) ?? 256 }}KB</small>
        </label>
        <div class="col-sm-4">
          <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new thumbnail">
              <img src="{{ getImage('/assets/images/banners/' . ($data->image ?? null)) }}">
            </div>
            <div class="fileinput-preview fileinput-exists thumbnail"></div>
            <div>
              <span class="btn btn-default btn-file">
                <span class="fileinput-new">Pilih</span>
                <span class="fileinput-exists">Ganti</span>
                <input type="file" id="image" name="image" accept="image/*">
              </span>
              <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Batal</a>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Judul</label>
        <div class="col-sm-4">
          <input type="text" class="form-control autofocus" id="title" name="title"
            value="{{ isset($data) ? $data->title : old('title') }}" maxlength="100" placeholder="Judul spanduk..">
        </div>
      </div>
      <div class="form-group">
        <label for="description" class="col-sm-2 control-label">Sub Judul</label>
        <div class="col-sm-4">
          <textarea id="description" name="description" class="form-control"
            placeholder="Sub judul spanduk..">{{ isset($data) ? $data->description : old('description') }}</textarea>
        </div>
      </div>
      <div class="form-group">
        <label for="url" class="col-sm-2 control-label">Tautan</label>
        <div class="col-sm-4">
          <select id="url" name="url" class="form-control select2">
            <option value="#">[Tidak ada]</option>
            <option value="external" {{ (isset($data) && bool($data->url_external)) ? 'selected' : '' }}>[Tautan Luar]</option>
            <option value="/" {{ (isset($data) && $data->url == "/" ) ? 'selected' : '' }}>Beranda</option>
            <option value="{{ config('path.posts', 'posts') }}" {{ (isset($data) && $data->url == config('path.posts', 'posts') ) ? 'selected' : '' }}>Artikel</option>
            <option value="{{ config('path.albums', 'albums') }}" {{ (isset($data) && $data->url == config('path.albums', 'albums') ) ? 'selected' : '' }}>Galeri Foto</option>
            <option value="{{ config('path.videos', 'videos') }}" {{ (isset($data) && $data->url == config('path.videos', 'videos') ) ? 'selected' : '' }}>Galeri Video</option>
            <option value="{{ config('path.contact', 'contact') }}" {{ (isset($data) && $data->url == config('path.contact', 'contact') ) ? 'selected' : '' }}>Kontak</option>
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
                <option value="{{ config('path.post', 'post') . '/' . $post->slug }}" {{ $post->slug == "#" ? 'disabled' : '' }} {{ (isset($data) && config('path.post', 'post') . '/' . $post->slug == $data->url) ? 'selected' : '' }}>{{ $post->title }}</option>
              @endif
            @endforeach
          </select>
          <input type="text" class="form-control" id="url_external" name="url_external" value="{{ isset($data) ? $data->url : '' }}" placeholder="http://external.link" {!! isset($data) && bool($data->url_external) ? 'style="margin-top:5px;" ' : 'style="margin-top:5px;display:none;" disabled ' !!}>
        </div>
      </div>
      <div class="form-group">
        <label for="target" class="col-sm-2 control-label">Target</label>
        <div class="col-sm-4">
          <select class="form-control custom-select2" name="target" id="target">
            <option value="_self" {{ isset($data) && $data->target == "_self" ? 'selected' : '' }}>Jendela Aktif
            </option>
            <option value="_blank" {{ isset($data) && $data->target == "_blank" ? 'selected' : '' }}>Jendela Baru
            </option>
          </select>
        </div>
      </div>
      <div class="form-group {{ config('site.banner.single') ? 'hide' : '' }}">
        <label for="target" class="col-sm-2 control-label">Urutan</label>
        <div class="col-sm-4">
          <input type="hidden" name="orders" value="{{ implode(',', $orders) }}">
          <select id="order" name="order" class="form-control custom-select2">
            <option value="first">Pertama</option>
            @foreach ($banners as $banner)
              @if (!isset($data) || (isset($data) && $banner->id != $data->id))
              <option value="{{ $banner->id }}"
                {{ (isset($data) && ($banner->order == ($data->order - 1))) ? 'selected' : '' }}>Setelah
                {{ $banner->title ?? $banner->order }}</option>
              @endif
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="status" value="active" {{ (isset($data) && $data->status == 'inactive') ? '' : 'checked' }}> Aktif
            </label>
            {!! spaces() !!}
            <label>
              <input type="checkbox" name="only_image" value="yes" {{ (isset($data) && bool($data->only_image)) ? 'checked' : '' }}>
              Hanya Gambar
            </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          {{ isset($data) ? method_field('PUT') : '' }}
          {{ csrf_field() }}
          {{ getButtonPost($current_url, true, $data->id ?? '', isset($data) ? 'spanduk \\\'' . $data->title . '\\\'' : null) }}
        </div>
      </div>
    </form>
  </div>
@endsection

@push('styles')
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css') !!}
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/fancybox/2.1.5/css/jquery.fancybox.css') !!}
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/select2/4.0.0/css/select2.min.css') !!}
@endpush

@push('scripts')
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/fancybox/2.1.5/js/jquery.fancybox.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/select2/4.0.0/js/select2.min.js') !!}
  <script>
    $(function(){
      $(".select2").select2({
        placeholder: "[None]"
      });
      
      $(".custom-select2").select2({
        minimumResultsForSearch: Infinity
      });
    });

    $(document).ready(function(){
      $("body").tooltip({ 
        selector: '[data-toggle=tooltip]' 
      });

      $('.select2').on('change',function(){
        if ($('#url').val() == "external"){
          $('#url_external').attr("disabled", false).show();
        }else{
          $('#url_external').attr("disabled", true).hide();
        }
      });
    });
  </script>
@endpush