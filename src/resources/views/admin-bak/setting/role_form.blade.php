@extends('admin.layouts.main')

@section('content')
<div class="card-body">
  <form action="{{ url($current_url) }}{{ isset($data->id) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
    @csrf
    @if (isset($data->id))
      {{ method_field('put') }}
    @endif
    <div class="form-group row">
      <label for="name" class="col-sm-2 col-form-label">Nama</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Peran" value="{{ isset($data->id) ? $data->display_name : '' }}">
      </div>
    </div>
    <div class="form-group row">
      <label for="description" class="col-sm-2 col-form-label">Deskripsi</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="description" rows="3" placeholder="Penjelasan singkat peran">{{ isset($data->id) ? $data->description : '' }}</textarea>
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
      <div class="offset-md-2 col-sm-10">
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
      </div>
    </div>
    {{-- 
  </form> --}}
</div>

</div>
</div>
</div>

<div class="row" id="role-row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Akses</h3>
      </div>
      <div class="card-body">
        {{-- <form action="{{ url('/setting/roles') }}{{ isset($data->id) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
          @csrf
          @if (isset($data->id))
            {{ method_field('put') }}
          @endif --}}
          <div class="form-group row">
            <table class="table table-bordered table-hover" id="access-list" width="100%">
                <thead>
                  <tr>
                      <th>Menu</th>
                      <th width="100px">Indeks</th>
                      <th width="100px">Tambah</th>
                      <th width="100px">Detail</th>
                      <th width="100px">Edit</th>
                      <th width="100px">Hapus</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach (generateArrayLevel($menus) as $menu)
                    <tr>
                      <td>{!! $menu->name !!}</td>
                      <td>
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="access[{{ $menu->route_name }}][index]" {{ !$menu->index ? 'disabled' : '' }} {{ isset($data) && $data->hasPermission('index-' . $menu->route_name) ? 'checked' : '' }}>
                          <span class="custom-control-label"></span>
                        </label>
                      </td>
                      <td>
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="access[{{ $menu->route_name }}][create]" {{ !$menu->create ? 'disabled' : '' }} {{ isset($data) && $data->hasPermission('create-' . $menu->route_name) ? 'checked' : '' }}>
                          <span class="custom-control-label"></span>
                        </label>
                      </td>
                      <td>
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="access[{{ $menu->route_name }}][read]" {{ !$menu->read ? 'disabled' : '' }} {{ isset($data) && $data->hasPermission('read-' . $menu->route_name) ? 'checked' : '' }}>
                          <span class="custom-control-label"></span>
                        </label>
                      </td>
                      <td>
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="access[{{ $menu->route_name }}][update]" {{ !$menu->update ? 'disabled' : '' }} {{ isset($data) && $data->hasPermission('update-' . $menu->route_name) ? 'checked' : '' }}>
                          <span class="custom-control-label"></span>
                        </label>
                      </td>
                      <td>
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="access[{{ $menu->route_name }}][delete]" {{ !$menu->delete ? 'disabled' : '' }} {{ isset($data) && $data->hasPermission('delete-' . $menu->route_name) ? 'checked' : '' }}>
                          <span class="custom-control-label"></span>
                        </label>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
          </div>
          <div class="form-group row">
            <div class="mx-auto">
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
    #role-row .card-body {
      padding: 0;
      padding-bottom: 20px;
    }
    #role-row .form-group.row {
      margin: 0;
    }
    table th {
      font-weight: bold!important;
      color: #5d5d5d!important;
    }
    table th:not(:first-child), table td:not(:first-child) {
      text-align: center;
    }
    .table-bordered {
      border: 0;
    }
    .table-bordered th, .table-bordered td {
      border: 0;
    }
    .table-bordered tr:last-child {
      border-bottom: 1px solid #dee2e6;
    }
    .table th, .text-wrap table th, .table td, .text-wrap table td {
      border-top: 1px solid #dee2e6;
    }
    label.custom-checkbox {
      margin-left: 50%;
      left: -8px;
    }
  </style>
@endsection