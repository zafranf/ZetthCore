@php
  $dataMenuGroup = [];
  if (isset($data)) {
    $dataMenuGroup = $data->menu_groups->map(function ($arr) { 
      return $arr->id; 
    })->toArray();
  }
@endphp
@extends('admin.AdminSC.layouts.main')

@section('content')
  <form class="form-horizontal" action="{{ url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post">
    <div class="panel-body">
      <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Nama Peran</label>
        <div class="col-sm-4">
          <input type="text" class="form-control autofocus" id="name" name="name" value="{{ isset($data) ? $data->display_name : '' }}" maxlength="50" placeholder="Nama peran..">
        </div>
      </div>
      <div class="form-group">
        <label for="description" class="col-sm-2 control-label">Deskripsi</label>
        <div class="col-sm-4">
          <textarea id="description" name="description" class="form-control" placeholder="Penjelasan singkat peran.." rows="4">{{ isset($data) ? $data->description : '' }}</textarea>
        </div>
      </div>
      <div class="form-group">
        <label for="menugroups" class="col-sm-2 control-label">Grup Menu</label>
        <div class="col-sm-4">
          <select id="menugroups" name="menugroups[]" class="form-control select2" multiple>
            @foreach ($menugroups as $group)
              <option value="{{ $group->id }}" {{ in_array($group->id, $dataMenuGroup) ? 'selected' : '' }}>{{ $group->display_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="status" {{ (isset($data) && $data->status == 0) ? '' : 'checked' }}> Aktif
            </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          {{ isset($data) ? method_field('PUT') : '' }}
          {{ csrf_field() }}
          {{ _get_button_post($current_url, true, $data->id ?? '') }}
        </div>
      </div>
    </div>

    {{-- close div first panel --}}
    </div>

    @if (isset($data))
      <div id="access-panel" class="panel panel-default">
        <div class="panel-heading">
          Akses
          <input type="checkbox" name="is_access" class="hide" checked>
        </div>
        <div class="panel-body no-padding">
          @if (count($menus) > 0)
            <table class="table table-bordered table-hover" id="access-list" width="100%">
              <thead>
                <tr>
                  <th>Menu</th>
                  <th width="100px">Daftar</th>
                  <th width="100px">Tambah</th>
                  <th width="100px">Detail</th>
                  <th width="100px">Edit</th>
                  <th width="100px">Hapus</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $group = '';
                @endphp
                @foreach (generateArrayLevel($menus) as $menu)
                  @php
                    $routename = explode('.', $menu->route_name);
                  @endphp
                  @if ($group != $menu->group->name)
                      <tr>
                        <td colspan="6" style="font-size:15px;"><b>{{ $menu->group->display_name }}</b></td>
                      </tr>
                      @php
                       $group = $menu->group->name;  
                      @endphp
                  @endif
                  <tr>
                    <td style="padding-left:20px;">{!! $menu->name !!}</td>
                    <td>
                      <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="access[{{ $routename[0] }}][index]" {{ !$menu->index ? 'disabled' : '' }} {{ isset($data) && $data->hasPermission('index-' . $routename[0]) ? 'checked' : '' }}>
                        <span class="custom-control-label"></span>
                      </label>
                    </td>
                    <td>
                      <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="access[{{ $routename[0] }}][create]" {{ !$menu->create ? 'disabled' : '' }} {{ isset($data) && $data->hasPermission('create-' . $routename[0]) ? 'checked' : '' }}>
                        <span class="custom-control-label"></span>
                      </label>
                    </td>
                    <td>
                      <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="access[{{ $routename[0] }}][read]" {{ !$menu->read ? 'disabled' : '' }} {{ isset($data) && $data->hasPermission('read-' . $routename[0]) ? 'checked' : '' }}>
                        <span class="custom-control-label"></span>
                      </label>
                    </td>
                    <td>
                      <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="access[{{ $routename[0] }}][update]" {{ !$menu->update ? 'disabled' : '' }} {{ isset($data) && $data->hasPermission('update-' . $routename[0]) ? 'checked' : '' }}>
                        <span class="custom-control-label"></span>
                      </label>
                    </td>
                    <td>
                      <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="access[{{ $routename[0] }}][delete]" {{ !$menu->delete ? 'disabled' : '' }} {{ isset($data) && $data->hasPermission('delete-' . $routename[0]) ? 'checked' : '' }}>
                        <span class="custom-control-label"></span>
                      </label>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @else
            <table class="table table-bordered table-hover" id="access-list" width="100%">
              <tr>
                <td>
                  <span style="color:grey">Belum ada menu yang terdaftar..</span>
                </td>
              </tr>
            </table>
          @endif
        </div>
      </div>
    @endif
  </form>
@endsection

@section('styles')
  {!! _load_css('themes/admin/AdminSC/plugins/select2/4.0.0/css/select2.min.css') !!}
  <style>
    #access-list {
      margin: 0;
    }
    #access-list th {
      font-weight: bold!important;
      color: #5d5d5d!important;
    }
    #access-list th:not(:first-child), #access-list td:not(:first-child) {
      text-align: center;
    }
    .table-bordered {
      border: 0;
    }
    .table-bordered tr {
      border-left: 0;
      border-right: 0;
    }
    .table-bordered th, .table-bordered td {
      border: 0;
    }
    .table-bordered tr td:first-child, .table-bordered tr th:first-child {
      border-left:0;
    }
    .table-bordered tr td:last-child, .table-bordered tr th:last-child {
      border-right:0;
    }
    .table-bordered tr:last-child  td{
      border-bottom: 0;
    }
    .table th, .text-wrap table th, .table td, .text-wrap table td {
      border-top: 1px solid #dee2e6;
    }
    /* label.custom-checkbox {
      margin-left: 50%;
      left: -8px;
    } */
  </style>
@endsection

@section('scripts')
  {!! _load_js('themes/admin/AdminSC/plugins/select2/4.0.0/js/select2.min.js') !!}
  <script>
    var menugroup = {{ isset($data) ? '['. implode(",", $dataMenuGroup) .']' : '[]' }}  
    $('document').ready(function() {
      $('.select2').select2({
        placeholder: '--Pilih--'
      });

      $('#menugroups').on('change', function() {
        togglePanel($(this).val());
      });
    });

    function togglePanel(val) {
      let hide = true;
      let cMenugroup = menugroup.length;
      let cVal = val ? val.length : 0;
      console.log('menugroup', menugroup)
      console.log('val', val)
      console.log('length menugroup', cMenugroup)
      console.log('length val', cVal)
      if (cVal == cMenugroup) {
        hide = checkToggle(val, menugroup);
      }

      if (hide) {
        $('#access-panel').hide();
        $('input[name=is_access]').prop('checked', false)
      } else {
        $('#access-panel').show();
        $('input[name=is_access]').prop('checked', true)
      }
    }

    function checkToggle(arr1, arr2) {
      let toggle = false;
      let t = 0;
      let f = 0;
      $.each(arr1, function (k, v) {
        let idx = arr2.indexOf(parseInt(v));
        if (idx >= 0) {
          t += 1
        } else {
          f += 1
        }
        console.log('indexof v=' + parseInt(v) + ' k=' + k, arr2.indexOf(parseInt(v)));
        console.log('t='+ t +' f='+ f)
      });

      if (f > 0) {
        return true;
      }

      return false;
    }
  </script>
@endsection