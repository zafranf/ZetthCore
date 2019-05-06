@extends('admin.AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
      <div class="row">
      <div class="col-md-2">Dari</div>
      <div class="col-md-10">{{ $data->name }} ({{ $data->email }})</div>
    </div>
    <div class="row">
      <div class="col-md-2">No. Telepon</div>
      <div class="col-md-10">{{ $data->phone!=""?$data->phone:'-' }}</div>
    </div>
    <div class="row">
      <div class="col-md-2">Tanggal</div>
      <div class="col-md-10">{{ generateDate($data->created_at) }}</div>
    </div>
    <hr>
    {{ $data->message }}
    <hr>
    <!-- <a id="btn-delete" class="zetth-share-button" onclick="_delete('{{ $data->id }}', '{{ $current_url }}');"><i class="fa fa-envelope"></i> Mark as Unread</a> --> 
    <a id="btn-back" class="zetth-share-button" href="{{ url($current_url) }}"><i class="fa fa-caret-left"></i> Kembali</a> 
    <a id="btn-delete" class="zetth-share-button" onclick="_delete('{{ $current_url . '/' . $data->id }}');"><i class="fa fa-trash-o"></i> Hapus</a>
  </div>
@endsection

@section('styles')
  <style>
    .zetth-share-button {
        position: relative;
        height: 18px;
        margin-top: -2px;
        padding: 1px 8px 1px 6px;
        /*color: #fff;*/
        cursor: pointer;
        /*background-color: #1b95e0;*/
        border: 1px solid coral;
        border-radius: 3px;
        box-sizing: border-box;
        font-size: 12px;
        line-height: 1.2;
    }
    .zetth-share-button:hover, .zetth-share-button:active, .zetth-share-button:focus {
          text-decoration: none;
      }
  </style>
@endsection