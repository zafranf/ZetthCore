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
                        <td width="100">Photo</td>
                        <td>Album</td>
                        <td width="80">Photos</td>
                        <td width="80">Status</td>
                    @else
                        <td>Album</td>
                    @endif
                    <td width="50">Action</td>
                </tr>
            </thead>
            <tbody>
                @if (count($albums)>0)
                    @foreach($albums as $album)
                        @php
                            $photo_name = ($album->photo)?$album->photo->photo_name:''
                        @endphp
                        <tr>
                            <td align="center">{{ $no++ }}</td>
                            @if ($isDesktop)
                                <td><img src="{{ _get_image_temp('assets/images/upload/'.$photo_name, [50,50]) }}" width="50"> </td>
                                <td>{{ $album->album_name }}</td>
                                <td>{{ count($album->photos) }}</td>
                                <td>{{ _get_status_text($album->album_status) }}</td>
                            @else
                                <td>
                                    {{ $album->album_name }}<br>
                                    <small>({{ count($album->photos) }} photos)<br>
                                    {{ _get_status_text($album->album_status) }}</small>
                                </td>
                            @endif
                            <td>{{ _get_button_access($album->album_id) }}</td>
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