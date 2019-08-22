@extends('admin.layout')

@section('styles')
<style>
    #mceu_14 {
        position: absolute;
        right: 10px;
    }
</style>
@endsection

@section('content')
    <div class="panel-body">
        <form class="form-horizontal" action="{{ url($current_url) }}{{ isset($comment->comment_id)?'/'.$comment->comment_id:'' }}" method="post">
            {{ isset($comment->comment_id)?method_field('PUT'):'' }}
            {{ csrf_field() }}
            @if (isset($comment->comment_id))
            <input type="hidden" name="comment_name_old" value="{{ isset($comment->comment_id)?$comment->comment_name:'' }}">
            <input type="hidden" name="comment_text_old" value="{{ isset($comment->comment_id)?$comment->comment_text:'' }}">
            <div class="form-group">
                <label for="comment_name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="comment_name" name="comment_name" value="{{ isset($comment->comment_id)?$comment->comment_name:'' }}">
                </div>
            </div>
            @else
            <input type="hidden" name="cid" value="{{ request('cid')!==null?request('cid'):0 }}">
            <input type="hidden" name="pid" value="{{ request('pid')!==null?request('pid'):0 }}">
            <div class="form-group">
                <label for="reply_to" class="col-sm-2 control-label">Reply To</label>
                <div class="col-sm-10">
                    {{-- <input type="text" class="form-control" id="reply_to" name="reply_to" value="Comment #{{ $_GET['cid'] }} on Post #{{ $_GET['pid'] }}" readonly> --}}
                    <textarea rows="8" id="reply_to" name="reply_to" class="form-control" readonly>
                    {{ isset($reply->comment_id)?$reply->comment_text:'' }}
                    </textarea>
                </div>
            </div>
            @endif
            <div class="form-group">
                <label for="comment_text" class="col-sm-2 control-label">Comment</label>
                <div class="col-sm-10">
                    <textarea rows="8" id="comment_text" name="comment_text" class="form-control" placeholder="Type your content here..">
                    @if (isset($comment->comment_id))
                        {{ $comment->comment_text }}
                    @endif
                    </textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="comment_status" {{ (isset($comment->comment_status) && $comment->comment_status==0)?'':'checked' }}> Approve
                        </label>
                        <!-- @if (isset($comment->comment_id))
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="checkbox" name="approval" {{ (isset($comment->approved_by) && $comment->approved_by==0)?'':'checked' }}> Approve
                        </label>
                        @endif -->
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  {{ _get_button_post() }}
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
{!! _load_tinymce() !!}
<script>
$(document).ready(function(){
    tinymce.init({ 
        relative_urls: false,
        selector: '#comment_text',
        skin: 'custom',
        height: 300,
        plugins: [
             "advlist autolink link image lists charmap print preview hr anchor pagebreak",
             "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
             "table contextmenu directionality emoticons paste textcolor responsivefilemanager code",
             "placeholder youtube fullscreen"
       ],
       toolbar1: "undo redo | bullist numlist blockquote | link unlink | youtube image table | styleselect fontselect fontsizeselect code | fullscreen",
       image_advtab: true ,
       menubar : false,
       external_filemanager_path:"{{ url('assets/plugins/filemanager/') }}/",
       filemanager_title:"Filemanager" ,
       external_plugins: { "filemanager" : "{{ url('assets/plugins/filemanager/plugin.min.js') }}" },
       /*extended_valid_elements : "script[language|type]"*/
    });
});
</script>
@endsection