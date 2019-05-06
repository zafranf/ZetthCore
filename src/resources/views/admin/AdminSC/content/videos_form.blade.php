@extends('admin.layout')

@section('content')
    <div class="panel-body">
        <form class="form-horizontal" action="{{ url($current_url) }}{{ isset($video->post_id)?'/'.$video->post_id:'' }}" method="post" enctype="multipart/form-data">
            {{ isset($video->post_id)?method_field('PUT'):'' }}
            {{ csrf_field() }}
            <div class="form-group">
                <label for="youtube_id" class="col-sm-2 control-label">Youtube ID</label>
                <div class="col-sm-4">
                    @if ($isDesktop)
                    <div class="input-group">
                        <span class="input-group-addon" id="youtube_id_span">http://youtube.com/watch?v=</span>
                        <input type="text" id="youtube_id" class="form-control" name="youtube_id" placeholder="Youtube ID" value="{{ isset($video->post_id)?$video->post_cover:'' }}" autofocus onfocus="this.value = this.value;">
                    </div>
                    @else
                        <input type="text" id="youtube_id" class="form-control" name="youtube_id" placeholder="Youtube ID" value="{{ isset($video->post_id)?$video->post_cover:'' }}" autofocus onfocus="this.value = this.value;">
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="video_title" class="col-sm-2 control-label">Video Title</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="video_title" name="video_title" value="{{ isset($video->post_id)?$video->post_title:'' }}" maxlength="100" placeholder="Video Title">
                </div>
            </div>
            <div class="form-group">
                <label for="video_description" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-4">
                    <textarea id="video_description" name="video_description" class="form-control" placeholder="Description">{{ isset($video->post_id)?$video->post_content:'' }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="post_status" {{ (isset($video->post_status) && $video->post_status==0)?'':'checked' }}> Active
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    {{ _get_button_post() }}
                </div>
            </div>
        </form>
    </div>
@endsection