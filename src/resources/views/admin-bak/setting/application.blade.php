@extends('admin.layouts.main')

@section('content')
  <div class="card-body">
    <form action="{{ url('/setting/application/'.$data->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        {{ method_field('put') }}
      <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Nama</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="name" name="name" placeholder="Nama Aplikasi" value="{{ $data->name }}">
        </div>
      </div>
      <div class="form-group row">
        <label for="description" class="col-sm-2 col-form-label">Deskripsi</label>
        <div class="col-sm-10">
          <textarea class="form-control" name="description" rows="3" placeholder="Penjelasan singkat tentang aplikasi">{{ $data->description }}</textarea>
        </div>
      </div>
      <div class="form-group row">
        <label for="logo" class="col-sm-2 col-form-label">Logo</label>
        <div class="col-sm-10">
          <div class="custom-file">
            <input type="file" class="custom-file-input" name="logo" accept="image/*">
            <label class="custom-file-label">Pilih gambar</label>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="status" class="col-sm-2 col-form-label">Status</label>
        {{-- <div class="col-sm-10"> --}}
          <label class="custom-switch" style="margin-left: 10px;">
          <input type="checkbox" id="status" name="status" class="custom-switch-input" {{ $data->status ? 'checked' : '' }}>
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description">Aktif</span>
          </label>
        {{-- </div> --}}
      </div>
      <div class="form-group row">
        <div class="offset-md-2 col-sm-10">
          <button type="submit" class="btn btn-success">Simpan</button>
          <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
        </div>
      </div>
    </form>
  </div>
@endsection