@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="panel-body no-padding-top">
    <h2>User: <span class="bg-success" style="padding:2px 10px;">{{ $act->user->fullname ?? 'Visitor' }}</span></h2>
    <br>
    <table class="table table-bordered">
      <tr>
        <td width="200">Description</td>
        <td>{!! str_replace('[~name]', '<b>'.($act->user->fullname ?? 'Visitor').'</b>', $act->description) !!}</td>
      </tr>
      <tr>
        <td>Path</td>
        <td>{{ $act->path }}</td>
      </tr>
      <tr>
        <td>Method</td>
        <td>{{ $act->method }}</td>
      </tr>
      <tr>
        <td>IP Address</td>
        <td>{{ $act->ip }}</td>
      </tr>
      <tr>
        <td>GET Values</td>
        <td>{{ print_json($act->get) }}</td>
      </tr>
      <tr>
        <td>POST Values</td>
        <td>{{ print_json($act->post) }}</td>
      </tr>
      <tr>
        <td>FILE Values</td>
        <td>{{ print_json($act->files) }}</td>
      </tr>
    </table>
    <a id="btn-back" class="btn btn-default" href="{{ _url($current_url) }}"><i class="fa fa-caret-left"></i> Back</a> 
  </div>
@endsection

@push('styles')
  <style>
    @if (app('is_mobile'))
      td pre {
        padding:0;
      }
    @endif
  </style>
@endpush