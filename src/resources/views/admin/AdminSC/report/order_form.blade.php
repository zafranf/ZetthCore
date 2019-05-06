@extends('admin.layout')

@section('styles')
{!! _load_select2('css') !!}
@endsection

@section('content')
    <div class="panel-body">
        <form class="form-horizontal" action="{{ url($current_url) }}{{ isset($order->order_id)?'/'.$order->order_id:'' }}" method="post">
            {{ isset($order->order_id)?method_field('PUT'):'' }}
            {{ csrf_field() }}
            <div class="form-group">
                <label for="order_email" class="col-sm-2 control-label">Trip</label>
                <div class="col-sm-4">
                    <p class="form-control-static">
                        {{ $order->order_package }}<br>
                        <small>{{ $order->order_trip }}</small>
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label for="order_email" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-4">
                    <p class="form-control-static">
                        {{ $order->order_name }}<br>
                        <small>{{ $order->order_phone }}</small>
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label for="order_email" class="col-sm-2 control-label">Total</label>
                <div class="col-sm-4">
                    <p class="form-control-static">
                        Rp{{ $order->order_price }} x {{ $order->order_participant }} participants<br>
                        <strong><big>Rp{{ $order->order_total }}</big></strong>
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label for="order_status" class="col-sm-2 control-label">Status</label>
                <div class="col-sm-4">
                    <select name="order_status" class="form-control select2">
                        <option value="3">Done</option>
                        <option value="2">Accept</option>
                        <option value="1">New</option>
                        <option value="0">Cancel</option>
                    </select>
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
{!! _load_select2('js') !!}
<script>
$(function(){
    $(".select2").select2({
        placeholder: "[None]",
        minimumResultsForSearch: Infinity
    });
});
</script>
@endsection