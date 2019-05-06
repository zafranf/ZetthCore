@php $no=1 @endphp
@extends('admin.layout')

@section('styles')
{!! _load_sweetalert('css') !!}
{!! _load_datatables('css') !!}
@endsection

@section('content')
    <div class="panel-body no-padding-right-left">
        <table id="table-data" class="row-border hover">
            <thead>
                <tr>
                    <td width="25">No.</td>
                    @if ($isDesktop)
                        <td width="200">Name</td>
                        <td>Comment</td>
                        <td width="80">Approved By</td>
                        <td width="80">Status</td>
                    @else
                        <td width="300">Comment</td>
                    @endif
                    <td width="100">Action</td>
                </tr>
            </thead>
            <tbody>
                @if (count($comments)>0)
                    @foreach($comments as $comment)
                        <tr{!! ($comment->comment_read)?'':' style="font-weight:400"' !!}>
                            <td align="center">{{ $no++ }}</td>
                            @if ($isDesktop)
                                <td>
                                    {{ $comment->comment_name }}<br>
                                    <small>{{ $comment->comment_email }}</small>
                                </td>
                                <td>
                                    {{ str_limit(strip_tags($comment->comment_text), 100) }}<br>
                                    <small>in <a style="text-decoration:none;">{{ $comment->post->post_title }}</a></small>
                                </td>
                                <td>{{ ($comment->comment_status)?$comment->approval->user_fullname:'-' }}</td>
                                <td>{{ _get_status_text($comment->comment_status, ['Pending', 'Approved']) }}</td>
                            @else
                                <td>
                                    {{ $comment->comment_name }} <!-- <small>({{ $comment->comment_email }})</small> --><br>
                                    <small>{{ str_limit(strip_tags($comment->comment_text), 60) }}<br>
                                    in <a style="text-decoration:none;">{{ str_limit($comment->post->post_title, 50) }}</a><br>
                                    {{ _get_status_text($comment->comment_status, ['Pending', 'Approved']) }}{{ ($comment->comment_status)?" by ".$comment->approval->user_fullname:'' }}</small>
                                </td>
                            @endif
                            <td>
                                <a href="{{ url($current_url."/create?cid=".$comment->comment_id."&pid=".$comment->post_id) }}" class="btn btn-default btn-xs" title="Reply"><i class="fa fa-reply"></i></a>
                                {{ _get_button_access($comment->comment_id, $current_url) }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
{!! _load_sweetalert('js') !!}
{!! _load_datatables('js') !!}
<script>
$(function(){
    $('#btn-add-new').hide();
});
</script>
@endsection