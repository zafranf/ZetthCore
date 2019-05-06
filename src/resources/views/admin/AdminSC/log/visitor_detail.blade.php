@php($no=1)
@extends('admin.layout')

@section('content')
    <div class="panel-body no-padding-top">
        <br>
        <table class="table table-bordered">
            <tr>
                <td width="200">IP Address</td>
                <td>{{ $vis->visitor_ip }}</td>
            </tr>
            <tr>
                <td>Browser</td>
                <td>{{ Agent::browser($vis->visitor_agent) }}</td>
            </tr>
            <tr>
                <td>Referral</td>
                <td>{{ $vis->visitor_referral  or '-' }}</td>
            </tr>
            <tr>
                <td>Page Visited</td>
                <td>{{ $vis->visitor_page }}</td>
            </tr>
            <tr>
                <td>Device</td>
                <td>{{ $vis->visitor_device }}</td>
            </tr>
            <tr>
                <td>Device Name</td>
                <td>{{ $vis->visitor_device_name }}</td>
            </tr>
        </table>
        <a id="btn-back" class="btn btn-default" href="{{ url($current_url) }}"><i class="fa fa-caret-left"></i> Back</a> 
    </div>
@endsection