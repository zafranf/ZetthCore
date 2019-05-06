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
						<td width="50">Image</td>
						<td>Banner Name</td>
						<td width="200">URL</td>
						<td width="80">Order</td>
						<td width="80">Status</td>
					@else
						<td>Banner</td>
					@endif
					<td width="50">Action</td>
				</tr>
			</thead>
			<tbody>
				@if (count($banners)>0)
					@foreach($banners as $banner)
						<tr>
							<td align="center">{{ $no++ }}</td>
							@if ($isDesktop)
								<td><img src="{{ _get_image_temp($banner->banner_image, [50,50]) }}" width="50"></td>
								<td>{{ $banner->banner_title }}<br>{!! ($banner->banner_only)?'<small><i class="fa fa-check"></i> Image Only</small>':'' !!}</td>
								<td>{{ url($banner->banner_url) }}</td>
								<td>{{ $banner->banner_order }}</td>
								<td>{{ _get_status_text($banner->banner_status) }}</td>
							@else
								<td>
									<img src="{{ _get_image_temp($banner->banner_image, [50,50,"crop"]) }}" width="50"><br>
									<small>{!! ($banner->banner_only)?'<i class="fa fa-check"></i> Image Only<br>':'' !!}
									{{ _get_status_text($banner->banner_status) }}</small>
								</td>
							@endif
							<td>{{ _get_button_access($banner->banner_id) }}</td>
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
