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
                        <td>Email</td>
                        <td width="80">Status</td>
                    @else
                        <td width="250">Subscriber</td>
                    @endif
                    <td width="50">Action</td>
                </tr>
            </thead>
            <tbody>
                @if (count($subscribers)>0)
                    @foreach($subscribers as $subscriber)
                        <tr>
                            <td align="center">{{ $no++ }}</td>
                            @if ($isDesktop)
                                <td>{{ $subscriber->subscriber_email }}</td>
                                <td>{{ _get_status_text($subscriber->subscriber_status) }}</td>
                            @else
                                <td>
                                    {{ $subscriber->subscriber_email }}<br>
                                    <small>{{ _get_status_text($subscriber->subscriber_status) }}</small>
                                </td>
                            @endif
                            <td>
                                {{ _get_button_access($subscriber->subscriber_id, $current_url) }}
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
@endsection