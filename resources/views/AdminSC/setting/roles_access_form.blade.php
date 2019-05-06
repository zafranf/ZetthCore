@extends('admin.AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
    <form class="form-horizontal" action="{{ url($current_url) }}{{ isset($group) ? '/' . $group->id : '' }}" method="post">
      <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Group Name</label>
        <div class="col-sm-4">
          <input type="text" class="form-control autofocus" id="name" name="name" value="{{ isset($group) ? $group->name : '' }}" maxlength="50" placeholder="Group Name">
        </div>
      </div>
      <div class="form-group">
        <label for="description" class="col-sm-2 control-label">Description</label>
        <div class="col-sm-4">
          <textarea id="description" name="description" class="form-control" placeholder="Type the description here..">{{ isset($group) ? $group->description : '' }}</textarea>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="status" {{ (isset($group) && $group->status==0) ? '' : 'checked' }}> Active
            </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          {{ isset($group) ? method_field('PUT') : '' }}
          {{ csrf_field() }}
          {{ _get_button_post($current_url) }}
        </div>
      </div>
    </form>
  </div>
@endsection