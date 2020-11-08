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
      <div class="col-md-1">Situs</div>
      <div class="col-md-11">{{ $data->site ?? '-' }}</div>
    </div>
    <div class="row">
      <div class="col-md-1">Waktu</div>
      <div class="col-md-11">{{ generateDate($data->created_at, 'dddd, Do MMMM YYYY HH:mm') }}</div>
    </div>
    <div class="row">
      <div class="col-md-1">Subjek</div>
      <div class="col-md-11"><a style="text-decoration:none;">{{ $data->post->title }}</a></div>
    </div>
    <hr>
    {!! nl2br(e(strip_tags($data->content))) !!}
    <hr>
    <a id="btn-back" class="share-button" href="{{ _url($current_url) }}">
      <i class="fa fa-caret-left"></i> Kembali
    </a>
    &nbsp;
    @if ($data->status == 'inactive')
      <a id="btn-reply" class="share-button"  href="{{ _url($current_url . "/approve/" . $data->id) }}">
        <i class="fa fa-check"></i> Setujui
      </a>
    @elseif ($data->status == 'active')
      <a id="btn-reply" class="share-button" href="{{ _url($current_url . "/unapprove/" . $data->id) }}">
        <i class="fa fa-times"></i> Batal Setuju
      </a>
    @endif
    &nbsp;
    <a id="btn-reply" class="share-button" href="{{ _url($current_url . "/create?cid=" . ($data->parent_id ?? $data->id) . "&pid=" . $data->commentable_id) }}">
      <i class="fa fa-reply"></i> Balas
    </a>
    &nbsp;
    <a id="btn-edit" class="share-button" href="{{ _url($current_url . '/' . $data->id . '/edit') }}">
      <i class="fa fa-edit"></i> Edit
    </a> 
    &nbsp;
    <a id="btn-delete" class="share-button" onclick="_delete('{{ $data->id }}', '{{ $current_url }}');">
      <i class="fa fa-trash-o"></i> Hapus
    </a>
  </div>
@endsection

@push('styles')
  <style>
    .panel>.panel-heading>.btn {
      display: none;
    }
    .share-button {
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
    .share-button:hover, .share-button:active, .share-button:focus {
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