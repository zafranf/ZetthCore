@php($no=1)
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
                        <td width="100">Photo</td>
                        <td>Video Title</td>
                        <td width="50">Views</td>
                        <td width="80">Status</td>
                    @else
                        <td width="250">Video</td>
                    @endif
                    <td width="50">Action</td>
                </tr>
            </thead>
            <tbody>
                @if (count($videos)>0)
                    @foreach($videos as $video)
                        <tr>
                            <td align="center">{{ $no++ }}</td>
                            @if ($isDesktop)
                                <td><img src="http://i.ytimg.com/vi/{{ $video->post_cover }}/default.jpg" width="50"></td>
                                <td>{{ $video->post_title }}</td>
                                <td>{{ $video->post_visited }}</td>
                                <td>{{ _get_status_text($video->post_status) }}</td>
                            @else
                                <td>
                                    {{ $video->post_title }}<br>
                                    <small>{{ _get_status_text($video->post_status) }}</small>
                                </td>
                            @endif
                            <td>{{ _get_button_access($video->post_id) }}</td>
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
@endsection