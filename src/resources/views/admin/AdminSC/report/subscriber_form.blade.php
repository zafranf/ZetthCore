@extends('admin.layout')

@section('content')
    <div class="panel-body">
        <form class="form-horizontal" action="{{ url($current_url) }}{{ isset($subscriber->subscriber_id)?'/'.$subscriber->subscriber_id:'' }}" method="post">
            {{ isset($subscriber->subscriber_id)?method_field('PUT'):'' }}
            {{ csrf_field() }}
            <div class="form-group">
                <label for="subscriber_email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="subscriber_email" name="subscriber_email" value="{{ isset($subscriber->subscriber_email)?$subscriber->subscriber_email:'' }}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="subscriber_status" {{ (isset($subscriber->subscriber_status) && $subscriber->subscriber_status==0)?'':'checked' }}> Active
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  {{ _get_button_post() }}
                </div>
            </div>
        </form>
    </div>
@endsection