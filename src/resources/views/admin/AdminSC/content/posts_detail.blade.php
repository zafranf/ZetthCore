@php $no=1 @endphp
@extends('admin.layout')

@section('styles')
{!! _load_prism('css') !!}
{!! _load_sweetalert('css') !!}
<style>
    .twitter-share-button {
        position: relative;
        height: 20px;
        padding: 1px 8px 1px 6px;
        color: #fff;
        cursor: pointer;
        background-color: #1b95e0;
        border-radius: 3px;
        box-sizing: border-box;
        font-size: 12px;
    }
    .twitter-share-button:hover, .twitter-share-button:active, .twitter-share-button:focus {
        text-decoration: none;
        color: white;
    }
    .fb-share-button {
        position: relative;
        height: 20px;
        padding: 1px 8px 1px 6px;
        color: #fff;
        cursor: pointer;
        background-color: #4267b2;
        border-radius: 3px;
        box-sizing: border-box;
        font-size: 12px;
    }
    .fb-share-button:hover, .fb-share-button:active, .fb-share-button:focus {
        text-decoration: none;
        color: white;
    }
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
    @php $link = url('post/'.$post->post_slug); @endphp
    {{-- @if (Session::get('app')->app_id==1 || Session::get('app')->app_id==2)
        @php $link = 'http://zafran.id/'.$post->url_code; @endphp
    @endif --}}

    <div class="panel-body no-padding-top">
        <h2>{{ $post->post_title }}</h2>
        <i class="fa fa-calendar"></i> {{ _generate_date($post->post_time, false, 'id') }} &nbsp;
        @if (!$isDesktop)
            <br>
        @endif
        <i class="fa fa-bookmark"></i> 
        @php $cat=[] @endphp
        @foreach($post->terms as $term)
                @if($term->term_type == "category")
                    @php $cat[] = $term->term_name; @endphp
                @endif
        @endforeach
        {{ implode($cat, ", ") }}
        <br>
        @if ($isDesktop)
            <a class="zetth-share-button" onclick="_open_window('https://www.facebook.com/sharer/sharer.php?u={{ $link }}&amp;src=sdkpreparse')"><i class="fa fa-facebook-square"></i> Share</a>
            <a class="zetth-share-button" onclick="_open_window('https://twitter.com/intent/tweet?text={{ $post->post_title.' '.$link }}')"><i class="fa fa-twitter"></i> Tweet</a>
            <a class="zetth-share-button" onclick="_open_window('https://plus.google.com/share?url={{ $link }}')"><i class="fa fa-google-plus"></i> Share</a>
            <a id="btn-short-url" class="zetth-share-button btn-short-url" data-toggle="modal" data-target="#zetth-modal"><i class="fa fa-link"></i> {{ $link }}</a>
            <a id="btn-edit" class="zetth-share-button" href="{{ url($current_url.'/'.$post->post_id.'/edit') }}"><i class="fa fa-edit"></i> Edit</a>
            <a id="btn-delete" class="zetth-share-button" onclick="_delete('{{ $post->post_id }}', '{{ $current_url }}');"><i class="fa fa-trash-o"></i> Delete</a>
            <a id="btn-back" class="zetth-share-button" href="{{ url($current_url) }}"><i class="fa fa-caret-left"></i> Back</a> 
        @else
            <a class="zetth-share-button" onclick="_open_window('https://www.facebook.com/sharer/sharer.php?u={{ $link }}&amp;src=sdkpreparse')"><i class="fa fa-facebook-square"></i></a>
            <a class="zetth-share-button" onclick="_open_window('https://twitter.com/intent/tweet?text={{ $post->post_title.' '.$link }}')"><i class="fa fa-twitter"></i></a>
            <a class="zetth-share-button" onclick="_open_window('https://plus.google.com/share?url={{ $link }}')"><i class="fa fa-google-plus"></i></a>
            <a id="btn-short-url" class="zetth-share-button btn-short-url" data-toggle="modal" data-target="#zetth-modal"><i class="fa fa-link"></i> <span class="hide">{{ $link }}</span></a>
            <a id="btn-edit" class="zetth-share-button" href="{{ url($current_url.'/'.$post->post_id.'/edit') }}"><i class="fa fa-edit"></i></a>
            <a id="btn-delete" class="zetth-share-button" onclick="_delete('{{ $post->post_id }}', '{{ $current_url }}');"><i class="fa fa-trash-o"></i></a>
            <a id="btn-back" class="zetth-share-button" href="{{ url($current_url) }}"><i class="fa fa-caret-left"></i> Back</a> 
        @endif
        <br>
        <br>
        {!! $post->post_content !!}

        @php $tag=[] @endphp
        @foreach($post->terms as $term) 
            @if ($term->term_type=="tag") 
                @php $tag[] = '#'.$term->term_name; @endphp
            @endif 
        @endforeach 
        Tags: {!! implode($tag, " &nbsp;") !!}
    </div>
@endsection

@section('scripts')
{!! _load_prism('js') !!}
{!! _load_sweetalert('js') !!}
<script>
$(document).ready(function(){
    $('.btn-short-url').on('click', function(){
        url = $(this).text();
        html = '<input id="zetth-short-url" type="text" class="form-control" readonly value="'+url+'">';
        $('.modal-title').text('Share URL');
        $('.modal-body').html(html);
        $('.modal-footer').hide();
    });
    $('#zetth-modal').on('shown.bs.modal', function () {
        $('#zetth-short-url').select();
    })
});
</script>
@endsection