@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
    <div class="row">
      <div class="col-md-1">Dari</div>
      <div class="col-md-11">{{ $data->name }} ({{ $data->email }})</div>
    </div>
    <div class="row">
      <div class="col-md-1">No. Telepon</div>
      <div class="col-md-11">{{ $data->phone !='' ? $data->phone : '-' }}</div>
    </div>
    <div class="row">
      <div class="col-md-1">Waktu</div>
      <div class="col-md-11">{{ generateDate($data->created_at,'id', 'dddd, Do MMMM YYYY HH:mm') }}</div>
    </div>
    <div class="row">
      <div class="col-md-1">Subjek</div>
      <div class="col-md-11">{{ $data->subject ?? '-' }}</div>
    </div>
    <hr>
    {{ $data->message }}
    <hr>
    <a id="btn-back" class="zetth-share-button" href="{{ _url($current_url) }}"><i class="fa fa-caret-left"></i> Kembali</a> 
    <a id="btn-delete" class="zetth-share-button" onclick="_delete('{{ $current_url . '/' . $data->id }}', 'pesan dari \'{{ $data->email }}\'');"><i class="fa fa-trash-o"></i> Hapus</a>
  </div>
@endsection

@push('styles')
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
@endpush