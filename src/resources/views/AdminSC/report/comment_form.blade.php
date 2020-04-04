@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
    <form class="form-horizontal" action="{{ url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post">
      @if (isset($data))
        <input type="hidden" name="name_old" value="{{ isset($data) ? $data->name : '' }}">
        <input type="hidden" name="comment_old" value="{{ isset($data) ? $data->comment : '' }}">
        <div class="form-group">
          <label for="name" class="col-sm-2 control-label">Nama</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" value="{{ $data->name ?? old('name') }}">
          </div>
        </div>
      @elseif (isset($reply))
        <input type="hidden" name="cid" value="{{ request('cid') ?? 0 }}">
        <input type="hidden" name="pid" value="{{ request('pid') ?? 0 }}">
        <div class="form-group">
          <label for="reply_to" class="col-sm-2 control-label">Balas ke</label>
          <div class="col-sm-10">
            <div id="reply-to-name" style="position:relative;margin-top:3px;font-weight:bold;line-height:30px;">{{ $reply->commentator->fullname }}</div>
            <div id="reply-to-text" style="padding:5px 8px;background:#eee;border:1px solid #ccc;border-radius:4px;max-height:200px;overflow-y:scroll;">{!! $reply->comment !!}</div>
          </div>
        </div>
      @endif
      <div class="form-group">
        <label for="text" class="col-sm-2 control-label">Komentar</label>
        <div class="col-sm-10">
          <textarea rows="8" id="comment" name="comment" class="form-control" placeholder="Type your content here..">{{ $data->comment ?? old('comment') }}</textarea>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="status" {{ (isset($data->status) && $data->status == 0) ? '' : 'checked' }}> Setujui
            </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          {{ isset($data) ? method_field('PUT') : '' }}
          {{ csrf_field() }}
          {{ getButtonPost($current_url, true, $data->id ?? '') }}
        </div>
      </div>
    </form>
  </div>
@endsection

@include('zetthcore::AdminSC.components.tinymce', [
  'selector' => '#comment'
])

@push('styles')
  <style>
    .panel-heading a.btn {
      display: none;
    }
  </style>
@endpush