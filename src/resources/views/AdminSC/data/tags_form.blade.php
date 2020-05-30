@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
    <form class="form-horizontal" action="{{ _url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post">
        <div class="form-group">
          <label for="name" class="col-sm-2 control-label">Label</label>
          <div class="col-sm-4">
            <input type="text" class="form-control autofocus" id="name" name="name" value="{{ isset($data) ? $data->name : old('name') }}" maxlength="30" placeholder="Nama label..">
          </div>
        </div>
        <div class="form-group">
          <label for="description" class="col-sm-2 control-label">Deskripsi</label>
          <div class="col-sm-4">
            <textarea id="description" name="description" class="form-control" placeholder="Penjelasan singkat label.." rows="4">{{ isset($data) ? $data->description : old('description') }}</textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="status" value="active" {{ (isset($data) && $data->status == 'inactive') ? '' : 'checked' }}> Aktif
              </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            {{ isset($data) ? method_field('PUT') : '' }}
            {{ csrf_field() }}
            {{ getButtonPost($current_url, true, $data->id ?? '', isset($data) ? 'label \\\'' . $data->name . '\\\'' : null) }}
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
      $(".custom-select2").select2({
        minimumResultsForSearch: Infinity
      });
    });
  </script>
@endpush