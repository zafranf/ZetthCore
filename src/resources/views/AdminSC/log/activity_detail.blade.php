@php($no=1)
@extends('admin.layout')

@section('content')
    <div class="panel-body no-padding-top">
        <h2>User: <span class="bg-success" style="padding:2px 10px;">{{ $act->user->user_fullname or 'Visitor' }}</span></h2>
        <br>
        <table class="table table-bordered">
            <tr>
                <td width="200">URL</td>
                <td>{{ $act->activity_route }}</td>
            </tr>
            <tr>
                <td>Action</td>
                <td>{{ $act->activity_action }}</td>
            </tr>
            <tr>
                <td>IP Address</td>
                <td>{{ $act->activity_ip }}</td>
            </tr>
            <tr>
                <td>GET Values</td>
                <td>{{ _print_json($act->activity_get) }}</td>
            </tr>
            <tr>
                <td>POST Values</td>
                <td>{{ _print_json($act->activity_post) }}</td>
            </tr>
            <tr>
                <td>FILE Values</td>
                <td>{{ _print_json($act->activity_file) }}</td>
            </tr>
        </table>
        <a id="btn-back" class="btn btn-default" href="{{ url($current_url) }}"><i class="fa fa-caret-left"></i> Back</a> 
    </div>
@endsection