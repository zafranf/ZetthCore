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
                        <td width="150">Name</td>
                        <td width="100">Phone</td>
                        <td width="200">Trip to</td>
                        <td width="100">Type</td>
                        <td width="100">Date Trip</td>
                        <td width="50">Participants</td>
                        <td width="80">Total</td>
                        <td width="80">Status</td>
                    @else
                        <td width="250">Subscriber</td>
                    @endif
                    <td width="50">Action</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $st = [
                        '0' => 'Canceled',
                        '1' => 'New',
                        '2' => 'Accepted',
                        '3' => 'Done'
                    ];
                @endphp
                @if (count($orders)>0)
                    @foreach($orders as $order)
                        <tr>
                            <td align="center">{{ $no++ }}</td>
                            @if ($isDesktop)
                                <td>{{ $order->order_name }}</td>
                                <td>{{ $order->order_phone }}</td>
                                <td>{{ $order->order_package }}</td>
                                <td>{{ $order->order_trip }}</td>
                                <td>{{ date("Y-m-d", strtotime($order->order_date)) }}</td>
                                <td>{{ $order->order_participant }}</td>
                                <td>{{ $order->order_total }}</td>
                                <td>{{ _get_status_text($order->order_status, $st) }}</td>
                            @else
                                <td>
                                    <small>{{ $order->order_trip }}</small><br>
                                    {{ $order->order_package }}<br>
                                    {{ $order->order_name }}<br>
                                    <small>{{ $order->order_phone }}</small><br>
                                    <small>{{ _get_status_text($order->order_status, $st) }}</small>
                                </td>
                            @endif
                            <td>
                                {{ _get_button_access($order->order_id, $current_url) }}
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