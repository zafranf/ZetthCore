@extends('admin.layouts.main')

@section('content')
  <div class="card-body">
    <form action="{{ url('/setting/users') }}{{ isset($data->id) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
      @csrf
      @if (isset($data->id))
        {{ method_field('put') }}
      @endif
      <div class="row">
        <div class="col-sm-6">
          <h3 class="card-title">Data Utama</h3>
          <div class="form-group row">
            <label for="fullname" class="col-sm-3 col-form-label">Nama Lengkap</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nama Lengkap" value="{{ isset($data->id) ? $data->fullname : '' }}">
            </div>
          </div>
          <div class="form-group row">
            <label for="email" class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="email" name="email" placeholder="Alamat Email" value="{{ isset($data->id) ? $data->email : '' }}">
            </div>
          </div>
          <div class="form-group row">
            <label for="language" class="col-sm-3 col-form-label">Bahasa</label>
            <div class="col-sm-9">
              <select id="language" name="language" class="form-control custom-select">
                <option value="id" data-data='{"image": "{{ url('/admin/images/flags/id.svg') }}"}' {{ isset($data->id) && ($data->language == "id") ? 'selected' : '' }}>Indonesia</option>
                <option value="en" data-data='{"image": "{{ url('/admin/images/flags/gb.svg') }}"}' {{ isset($data->id) && ($data->language == "en") ? 'selected' : '' }}>English</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="image" class="col-sm-3 col-form-label">Foto</label>
            <div class="col-sm-9">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="image" accept="image/*">
                <label class="custom-file-label">Pilih gambar</label>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="role" class="col-sm-3 col-form-label">Peran</label>
            <div class="col-sm-9">
              <select id="role" name="role" class="form-control custom-select">
                @foreach ($roles as $role)
                  <option value="{{ $role->id }}" {{ isset($data->id) && $data->hasRole($role->name) ? 'selected' : '' }}>{{ $role->display_name }}</option>
                @endforeach
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
        <div class="col-sm-6">
          <h3 class="card-title">Data Login</h3>
          <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">Nama</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="name" name="name" placeholder="Nama ID" value="{{ isset($data->id) ? $data->name : '' }}">
            </div>
          </div>
          <div class="form-group row">
            <label for="password" class="col-sm-3 col-form-label">Sandi</label>
            <div class="col-sm-9">
              <input type="password" class="form-control" id="password" name="password" placeholder="Kata Sandi">
            </div>
          </div>
          <div class="form-group row">
            <label for="password_confirmation" class="col-sm-3 col-form-label">Ulangi Sandi</label>
            <div class="col-sm-9">
              <input type="password" class="form-control" id="password-confirmation" name="password_confirmation" placeholder="Ulangi Kata Sandi">
            </div>
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
    $('#language').selectize({
        render: {
            option: function (data, escape) {
                return '<div>' +
                    '<span class="image"><img src="' + data.image + '" alt=""></span>' +
                    '<span class="title">' + escape(data.text) + '</span>' +
                    '</div>';
            },
            item: function (data, escape) {
                return '<div>' +
                    '<span class="image"><img src="' + data.image + '" alt=""></span>' +
                    escape(data.text) +
                    '</div>';
            }
        }
    });
    $('#role').selectize();
  });
</script>
@endsection