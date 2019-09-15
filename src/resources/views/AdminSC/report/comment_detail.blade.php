@php($no=1)
@extends('admin.layout')

@section('styles')
{!! _load_sweetalert('css') !!}
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

@section('content')
    <div class="panel-body">
        From: {{ $comment->comment_name }} ({{ $comment->comment_email }}) <br>
        Site: {{ $comment->comment_site!=""?$comment->comment_site:'-' }} <br>
        Date: {{ _generate_date($comment->created_at, false, 'id') }} <br>
        In: <a style="text-decoration:none;">{{ $comment->post->post_title }}</a> <br>
        <br>
        {!! nl2br(e($comment->comment_text)) !!}
        <br>
        <br>
        <a id="btn-reply" class="zetth-share-button"  href="{{ url($current_url."/approve/".$comment->comment_id) }}"><i class="fa fa-check"></i> Approve</a>
        <a id="btn-reply" class="zetth-share-button"  href="{{ url($current_url."/create?cid=".$comment->comment_id."&pid=".$comment->post_id) }}"><i class="fa fa-reply"></i> Reply</a>
        <a id="btn-edit" class="zetth-share-button" href="{{ url($current_url.'/'.$comment->comment_id.'/edit') }}"><i class="fa fa-edit"></i> Edit</a> 
        <a id="btn-delete" class="zetth-share-button" onclick="_delete('{{ $comment->comment_id }}', '{{ $current_url }}');"><i class="fa fa-trash-o"></i> Delete</a>
        <a id="btn-back" class="zetth-share-button" href="{{ url($current_url) }}"><i class="fa fa-caret-left"></i> Back</a> 
    </div>
@endsection

@section('scripts')
{!! _load_sweetalert('js') !!}
<script>
$(function(){
    $('#btn-add-new').hide();
});
</script>
@endsection