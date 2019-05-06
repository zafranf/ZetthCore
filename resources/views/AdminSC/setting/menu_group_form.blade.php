@php
  function sortMenu($data, $level = 0, $adminPath = '') {
    echo ($level == 0) ? 'Geser untuk atur posisi' : '';
    echo ($level == 0) ? '<ol class="default vertical">' : '<ol>';
    echo count($data) == 0 ? '<span style="color:grey">Belum ada menu yang terdaftar..</span>' : '';
    foreach ($data as $menu) {
      echo '<li data-id="' . $menu->id . '" data-name="' . $menu->name . '">';
      echo '<span>';
      if (isset($menu->icon)) {
        echo '<i class="' . $menu->icon . '"></i> ';
      }
      echo $menu->name;
      echo '<a onclick="_delete(\'' . url($adminPath . '/setting/menus/'.$menu->id. '?group='.$menu->group_id).'\')" class="btn btn-default btn-xs pull-right" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></a>';
      echo '<a href="' . url($adminPath . '/setting/menus/' . $menu->id . '/edit?group=' . $menu->group_id) . '" class="btn btn-default btn-xs pull-right" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';
      echo '</span>';
      if (count($menu->submenu) > 0) {
        sortMenu($menu->submenu, $level + 1, $adminPath);
      }
      echo '<ol></ol>';
      echo '</li>';
    }
    echo '</ol>';
  }
@endphp

@extends('admin.AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
    <form class="form-horizontal" action="{{ url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6">
          <h4>Info Utama</h4>
          <hr>
          <div class="form-group">
            <label for="name" class="col-sm-4 control-label">Nama Grup Menu</label>
            <div class="col-sm-8">
              <input type="text" class="form-control autofocus" id="name" name="name" value="{{ isset($data) ? $data->name : '' }}" maxlength="100" placeholder="Nama grup menu..">
            </div>
          </div>
          <div class="form-group">
            <label for="description" class="col-sm-4 control-label">Deskripsi</label>
            <div class="col-sm-8">
              <textarea id="description" name="description" class="form-control" placeholder="Penjelasan singkat grup menu.." rows="4">{{ isset($data) ? $data->description : '' }}</textarea>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="status" {{ (isset($data) && $data->status == 0) ? '' : 'checked' }}> Aktif
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          @if (isset($data))
            <h4>Daftar Menu <a href="{{ url($adminPath.'/setting/menus/create?group='.$data->id) }}" class="btn btn-default btn-xs pull-right" data-toggle="tooltip" title="Tambah"><i class="fa fa-plus"></i></a></h4>
            <hr>
            {!! sortMenu($data->allMenu, 0, $adminPath) !!}
          @endif
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          <input type="hidden" id="serialize_output" name="sort">
          {{ isset($data) ? method_field('PUT') : '' }}
          {{ csrf_field() }}
          {{ _get_button_post($current_url, true, $data->id ?? '') }}
        </div>
      </div>
    </form>
  </div>
@endsection

@section('styles')
  <style>
    ol.default {
      list-style-type: none; 
      /* padding-left: 20px; */
      padding: 0;
    }
    ol.default ol {
      list-style-type: none;
      padding-left: 20px;
    }
    ol.default li {
      cursor: move; 
    }
    ol.default li span:hover {
      background: #f9f9f9;
    }
    ol.vertical {
      /* margin: 0 0 9px 0; */
      min-height: 10px; 
      display: block;
      /* margin: 5px; */
      /* padding: 5px; */
      /* border: 1px solid #ccc; */
      color: coral;
      /* background: #eee; */
    }
    ol.vertical li span {
      display: block;
      margin: 5px;
      margin-left: 0;
      padding: 5px;
      border: 1px solid #ccc;
      color: coral;
      /* background: #eee;  */
    }
    ol.vertical li.placeholder {
      position: relative;
      margin: 0;
      padding: 0;
      border: none; 
    }
    ol.vertical li.placeholder:before {
        position: absolute;
        content: "";
        width: 0;
        height: 0;
        margin-top: -5px;
        left: -5px;
        top: -4px;
        border: 5px solid transparent;
        border-left-color: coral;
        border-right: none; 
      }
  </style>
@endsection

@section('scripts')
  {!! _load_js('themes/admin/AdminSC/plugins/jquery/sortable/0.9.13/jquery-sortable.min.js') !!}
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