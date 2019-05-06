@extends('admin.layouts.main')

@section('content')
  <div class="card-body">
    <form action="{{ url('/report/subscribers') }}{{ isset($data->id) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
      @csrf
      @if (isset($data->id))
        {{ method_field('put') }}
      @endif
      <div class="form-group row">
        <label for="email" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{ isset($data->id) ? $data->email : '' }}" readonly>
        </div>
      </div>
      <div class="form-group row">
        <label for="token" class="col-sm-2 col-form-label">Token</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="token" name="token" placeholder="Token" value="{{ isset($data->id) ? $data->token : '' }}" readonly>
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