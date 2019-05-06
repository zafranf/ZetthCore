@php
function sortMenu($data, $level = 0) {
  echo ($level == 0) ? '<ol class="default vertical">' : '<ol>';
  foreach ($data as $menu) {
    echo '<li data-id="' . $menu->id . '" data-name="' . $menu->name . '"><span class="fe ' . $menu->icon . '"></span> ' . $menu->name;
    if (count($menu->submenu) > 0) {
      sortMenu($menu->submenu, $level + 1);
    }
    echo '<ol></ol>';
    echo '</li>';
  }
  echo '</ol>';
}
@endphp

@extends('admin.layouts.main')

{{-- @section('menu-sort')
  @if (\Auth::user()->can('update-menus'))
    <a href="{{ url('/setting/menus/sort') }}" class="btn btn-info" data-toggle="tooltip" title="Urutkan"><i class="fa fa-sort"></i></a>
  @endif
@endsection --}}

@section('content')
  <div class="card-body">
    <div class="form-group row">
      <div class="col-sm-12">
        {!! sortMenu($data) !!}
      </div>
      {{-- <div class="col-sm-6">
        <pre id="serialize_output2"></pre>
      </div> --}}
    </div>
    <div class="form-group row">
      <div class="offset-sm-2 col-sm-10">
        <form action="{{ url('/setting/menus/sort') }}" method="post">
          @csrf
          {{ method_field('put') }}
          <input type="hidden" id="serialize_output" name="sort">
          <button type="submit" class="btn btn-success">Simpan</button>
          <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
        </form>
      </div>
    </div>
  </div>
@endsection

{{-- include css --}}
@section('css')
<style>
  ol {
    list-style-type: none; 
    padding-left: 20px;
  }
  ol.vertical {
  margin: 0 0 9px 0;
  min-height: 10px; }
  ol.vertical li {
    display: block;
    margin: 5px;
    padding: 5px;
    border: 1px solid #cccccc;
    color: #0088cc;
    background: #eeeeee; }
  ol.vertical li.placeholder {
    position: relative;
    margin: 0;
    padding: 0;
    border: none; }
  ol.vertical li.placeholder:before {
      position: absolute;
      content: "";
      width: 0;
      height: 0;
      margin-top: -5px;
      left: -5px;
      top: -4px;
      border: 5px solid transparent;
      border-left-color: red;
      border-right: none; }
  ol.default li {
    cursor: move; 
  }
  ol li.highlight {
    background: #333333;
    color: #999999; }
</style>
@endsection

{{-- include js --}}
@section('js')
{!! _load_js('/admin/js/vendors/jquery-sortable.min.js') !!}
<script>
    $(function() {
      var group = $('ol.default').sortable({
          group: 'default',
          onDrop: function ($item, container, _super) {
              var data = group.sortable("serialize").get();
              var jsonString = JSON.stringify(data, null, ' ');
              $('#serialize_output').val(jsonString);
              _super($item, container);
          }
      });
    });
</script>
@endsection