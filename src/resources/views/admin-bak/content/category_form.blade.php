@extends('admin.layouts.main')

@section('content')
  <div class="card-body">
    <form action="{{ url('/content/categories') }}{{ isset($data->id) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
      @csrf
      @if (isset($data->id))
        {{ method_field('put') }}
      @endif
      <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Nama</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="name" name="name" placeholder="Nama Kategori" value="{{ isset($data->id) ? $data->name : '' }}">
        </div>
      </div>
      <div class="form-group row">
        <label for="description" class="col-sm-2 col-form-label">Deskripsi</label>
        <div class="col-sm-10">
          <textarea class="form-control" name="description" rows="3" placeholder="Penjelasan singkat kategori">{{ isset($data->id) ? $data->description : '' }}</textarea>
        </div>
      </div>
      {{-- <div class="form-group row">
        <label for="parent" class="col-sm-2 col-form-label">Grup</label>
        <div class="col-sm-10">
          <select class="form-control" name="parent" id="parent">
              <option value="0">--Pilih--</option>
              @foreach (generateArrayLevel($categories) as $category)
                <option value="{{ $category->id }}" {{ isset($data->id) && ($data->parent_id == $category->id) ? 'selected' : '' }}>{!! $category->name !!}</option>
              @endforeach
          </select>
        </div>
      </div> --}}
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