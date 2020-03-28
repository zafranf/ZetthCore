@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
    Dari: {{ $data->name }} ({{ $data->email }}) <br>
    No. Telp: {{ $data->phone ?? '-' }} <br>
    Situs: {{ $data->site ?? '-' }} <br>
    Tanggal: {{ generateDate($data->created_at,'id', 'dddd, Do MMMM YYYY HH:mm') }} <br>
    Artikel: <a style="text-decoration:none;">{{ $data->post->title }}</a> <br>
    <br>
    {!! nl2br(e(strip_tags($data->comment))) !!}
    <br>
    <br>
    <a id="btn-reply" class="zetth-share-button" href="{{ url($current_url."/approve/".$data->id) }}"><i class="fa fa-check"></i> Approve</a>
    <a id="btn-reply" class="zetth-share-button" href="{{ url($current_url."/create?cid=".$data->id."&pid=".$data->post_id) }}"><i class="fa fa-reply"></i> Reply</a>
    <a id="btn-edit" class="zetth-share-button" href="{{ url($current_url.'/'.$data->id.'/edit') }}"><i class="fa fa-edit"></i> Edit</a> 
    <a id="btn-delete" class="zetth-share-button" onclick="_delete('{{ $data->id }}', '{{ $current_url }}');"><i class="fa fa-trash-o"></i> Delete</a>
    <a id="btn-back" class="zetth-share-button" href="{{ url($current_url) }}"><i class="fa fa-caret-left"></i> Back</a> 
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

@push('scripts')
  <script>
    $(function(){
      $('#btn-add-new').hide();
    });
  </script>
@endpush