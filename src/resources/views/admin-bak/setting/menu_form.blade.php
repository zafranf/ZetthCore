@extends('admin.layouts.main')

@section('menu-sort')
  @if (\Auth::user()->can('update-menus'))
    <a href="{{ url('/setting/menus/sort') }}" class="btn btn-info" data-toggle="tooltip" title="Urutkan"><i class="fa fa-sort"></i></a>
  @endif
@endsection

@section('content')
  <div class="card-body">
    <form action="{{ url('/setting/menus') }}{{ isset($data->id) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
      @csrf
      @if (isset($data->id))
        {{ method_field('put') }}
      @endif
      <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Nama</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="name" name="name" placeholder="Nama Menu" value="{{ isset($data->id) ? $data->name : '' }}">
        </div>
      </div>
      <div class="form-group row">
        <label for="description" class="col-sm-2 col-form-label">Deskripsi</label>
        <div class="col-sm-10">
          <textarea class="form-control" name="description" rows="3" placeholder="Penjelasan singkat menu">{{ isset($data->id) ? $data->description : '' }}</textarea>
        </div>
      </div>
      {{-- <div class="form-group row">
        <label for="url" class="col-sm-2 col-form-label">URL</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="url" name="url" placeholder="URL" value="{{ isset($data->id) ? $data->url : '' }}">
        </div>
      </div> --}}
      <div class="form-group row">
        <label for="route_name" class="col-sm-2 col-form-label">Route</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="route_name" name="route_name" placeholder="Nama Route" value="{{ isset($data->id) ? $data->route_name : '' }}">
        </div>
      </div>
      <div class="form-group row">
        <label for="target" class="col-sm-2 col-form-label">Target</label>
        <div class="col-sm-10">
          <select class="form-control custom-select" name="target" id="target">
            <option value="_self" {{ isset($data->id) && ($data->target == "_self") ? 'selected' : '' }}>Tab Sendiri</option>
            <option value="_blank" {{ isset($data->id) && ($data->target == "_blank") ? 'selected' : '' }}>Tab Baru</option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="icon" class="col-sm-2 col-form-label">Ikon</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="icon" name="icon" placeholder="Ikon Menu" value="{{ isset($data->id) ? $data->icon : '' }}">
        </div>
      </div>
      {{-- <div class="form-group row">
        <label for="order" class="col-sm-2 col-form-label">Urutan</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="order" name="order" placeholder="Urutan Menu" value="{{ isset($data->id) ? $data->order : '' }}">
        </div>
      </div> --}}
      <div class="form-group row">
        <label for="parent" class="col-sm-2 col-form-label">Grup</label>
        <div class="col-sm-10">
          <select class="form-control" name="parent" id="parent">
              <option value="0">--Pilih--</option>
              @foreach (generateArrayLevel($menus) as $menu)
                <option value="{{ $menu->id }}" {{ isset($data->id) && ($data->parent_id == $menu->id) ? 'selected' : '' }}>{!! $menu->name !!}</option>
              @endforeach
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="status" class="col-sm-2 col-form-label">Akses</label>
          <div class="selectgroup selectgroup-pills" style="margin-left: 10px;">
            <label class="selectgroup-item">
              <input type="checkbox" name="index" class="selectgroup-input" {{ isset($data->id) && $data->index ? 'checked' : '' }}>
              <span class="selectgroup-button">Indeks</span>
            </label>
            <label class="selectgroup-item">
              <input type="checkbox" name="create" class="selectgroup-input" {{ isset($data->id) && $data->create ? 'checked' : '' }}>
              <span class="selectgroup-button">Tambah</span>
            </label>
            <label class="selectgroup-item">
              <input type="checkbox" name="read" class="selectgroup-input" {{ isset($data->id) && $data->read ? 'checked' : '' }}>
              <span class="selectgroup-button">Detail</span>
            </label>
            <label class="selectgroup-item">
              <input type="checkbox" name="update" class="selectgroup-input" {{ isset($data->id) && $data->update ? 'checked' : '' }}>
              <span class="selectgroup-button">Edit</span>
            </label>
            <label class="selectgroup-item">
              <input type="checkbox" name="delete" class="selectgroup-input" {{ isset($data->id) && $data->delete ? 'checked' : '' }}>
              <span class="selectgroup-button">Hapus</span>
            </label>
          </div>
      </div>
      <div class="form-group row">
        <label for="status" class="col-sm-2 col-form-label">Status</label>
        {{-- <div class="col-sm-10"> --}}
          <label class="custom-switch" style="margin-left: 10px;">
            <input type="checkbox" id="status" name="status" class="custom-switch-input" {{ (isset($data->id) && !$data->status) ? '' : 'checked' }}>
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description">Aktif</span>
          </label>
        {{-- </div> --}}
      </div>
      <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
          <button type="submit" class="btn btn-success">Simpan</button>
          <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
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
      $('#target, #parent').selectize();
  });
</script>
@endsection