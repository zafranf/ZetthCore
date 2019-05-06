@php
/* mapping order to array */
$orders = collect($banners)->map(function($arr) use ($data) {
    return $arr->id;
})->toArray();

/* remove current id */
if (($key = array_search($data->id, $orders)) !== false) {;
  unset($orders[$key]);
}
@endphp

@extends('admin.layouts.main')

@section('content')
  <div class="card-body">
    <form action="{{ url('/content/banners') }}{{ isset($data->id) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
      @csrf
      @if (isset($data->id))
        {{ method_field('put') }}
      @endif
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group row">
            <label for="image" class="col-sm-3 col-form-label">Gambar Spanduk</label>
            <div class="col-sm-9">
              <div class="custom-file">
                  <input type="file" class="custom-file-input" name="image" accept="image/*">
                  <label class="custom-file-label">Pilih gambar</label>
              </div>
            </div>
          </div>
          <div class="form-group row">
              <label for="only_image" class="col-sm-3 col-form-label">&nbsp;</label>
              {{-- <div class="col-sm-9"> --}}
              <label class="custom-switch" style="margin-left: 10px;">
                  <input type="checkbox" id="only_image" name="only_image" class="custom-switch-input" {{ isset($data->id) && $data->only_image ? 'checked' : '' }}>
                  <span class="custom-switch-indicator"></span>
                  <span class="custom-switch-description">Hanya Gambar</span>
              </label>
              {{-- </div> --}}
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group row">
              <label for="title" class="col-sm-3 col-form-label">Judul</label>
              <div class="col-sm-9">
              <input type="text" class="form-control" id="title" name="title" placeholder="Judul Spanduk" value="{{ isset($data->id) ? $data->title : '' }}">
              </div>
          </div>
          <div class="form-group row">
              <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
              <div class="col-sm-9">
              <textarea class="form-control" name="description" rows="3" placeholder="Penjelasan singkat kategori">{{ isset($data->id) ? $data->description : '' }}</textarea>
              </div>
          </div>
          <div class="form-group row">
            <label for="order" class="col-sm-3 col-form-label">Urutan</label>
            <div class="col-sm-9">
              <input type="hidden" name="orders" value="{{ implode(',', $orders) }}">
              <select id="order" name="order" class="form-control custom-select">
                <option value="first">Pertama</option>
                @foreach ($banners as $banner)
                  @if ($banner->id != $data->id)
                    <option value="{{ $banner->id }}" {{ ($banner->order == ($data->order - 1)) ? 'selected' : '' }}>Setelah {{ $banner->title != '' ? $banner->title : $banner->order }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="target" class="col-sm-3 col-form-label">Target</label>
            <div class="col-sm-9">
              <select id="target" name="target" class="form-control custom-select">
                <option value="_self">Tab Sendiri</option>
                <option value="_blank">Tab Baru</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
              <label for="status" class="col-sm-3 col-form-label">Status</label>
              {{-- <div class="col-sm-9"> --}}
              <label class="custom-switch" style="margin-left: 10px;">
                  <input type="checkbox" id="status" name="status" class="custom-switch-input" {{ (isset($data->id) && !$data->status) ? '' : 'checked' }}>
                  <span class="custom-switch-indicator"></span>
                  <span class="custom-switch-description">Aktif</span>
              </label>
              {{-- </div> --}}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group row">
            <div class="offset-sm-3 col-sm-9">
              <button type="submit" class="btn btn-success">Simpan</button>
              <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

{{-- include css --}}
@section('css')
  <style>
    .selectgroup-button {
      padding: 0.1rem 1rem;
    }
  </style>
@endsection

{{-- include js --}}
@section('js')
{!! _load_js('/admin/js/vendors/selectize.min.js') !!}
<script>
  $('document').ready(function(){
    $('#order').selectize();
  });
</script>
@endsection