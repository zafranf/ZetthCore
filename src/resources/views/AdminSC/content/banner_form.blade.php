@extends('admin.layout')

@section('styles')
{!! _load_select2('css') !!}
{!! _load_fancybox('css') !!}
<style media="screen">
	.zetth-upload-exists {
		display: none;
	}
</style>
@endsection

@section('content')
	<div class="panel-body">
		<form class="form-horizontal" action="{{ url($current_url) }}{{ isset($banner->banner_id)?'/'.$banner->banner_id:'' }}" method="post" enctype="multipart/form-data">
			{{ isset($banner->banner_id)?method_field('PUT'):'' }}
			{{ csrf_field() }}
			<div class="form-group">
				<label for="banner_image" class="col-sm-2 control-label">Banner Image <abbr data-toggle="tooltip" data-placement="top" title="Size 1600x600"><i class="fa fa-question-circle"></i></abbr></label>
				<div class="col-sm-4">
					<div class="zetth-upload">
						<div class="zetth-upload-new thumbnail">
							<img src="{!! _get_image_temp(isset($banner->banner_id)?$banner->banner_image:'', [560]) !!}">
						</div>
						<div class="zetth-upload-exists thumbnail"></div>
						<div>
							<a href="{{ url('assets/plugins/filemanager/dialog.php?type=1&field_id=banner_image&relative_url=1&fldr=').Session::get('template') }}/" class="btn btn-default zetth-upload-new" id="btn-upload" type="button">Select</a>
							<a href="{{ url('assets/plugins/filemanager/dialog.php?type=1&field_id=banner_image&relative_url=1&fldr=').Session::get('template') }}/" class="btn btn-default zetth-upload-exists" id="btn-upload" type="button">Change</a>
							<a id="btn-remove" class="btn btn-default zetth-upload-exists" type="button">Remove</a>
							<input name="banner_image" id="banner_image" type="hidden">
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="banner_title" class="col-sm-2 control-label">Title</label>
				<div class="col-sm-4">
					<input type="text" class="form-control autofocus" id="banner_title" name="banner_title" value="{{ isset($banner->banner_id)?$banner->banner_title:'' }}" maxlength="100" placeholder="Title">
				</div>
			</div>
			<div class="form-group">
				<label for="banner_description" class="col-sm-2 control-label">Description</label>
				<div class="col-sm-4">
					<textarea id="banner_description" name="banner_description" class="form-control" placeholder="Type the description here..">{{ isset($banner->banner_id)?$banner->banner_description:'' }}</textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="banner_url" class="col-sm-2 control-label">URL</label>
				<div class="col-sm-4">
					<select id="banner_url" name="banner_url" class="form-control select2">
						<option value="#">[None]</option>
						<option value="/">Home</option>
						<option value="external" {{ (isset($banner->banner_id) && $banner->banner_url_ext)?'selected':'' }}>External Link</option>
						<?php $type = ''; ?>
						@foreach($post_opt as $n => $post)
							@if ($type!=$post->post_type)
								{!! ($n>0)?'</optgroup>':'' !!}
								@php($type=($post->post_type=="video")?"Video":$post->post_type)
								<optgroup label="{{ ucfirst($type) }}">
							@endif
							@if ($post->post_type=="video")
								<option value="{{ $post->post_slug }}" {{ $post->post_slug=="#"?'disabled':'' }}  {{ (isset($banner->banner_id) && $post->post_slug==$banner->banner_url)?'selected':'' }}>{{ $post->post_title }}</option>
							@endif
							@if ($post->post_type=="page")
								<option value="{{ $post->post_slug }}" {{ $post->post_slug=="#"?'disabled':'' }}  {{ (isset($banner->banner_id) && $post->post_slug==$banner->banner_url)?'selected':'' }}>{{ $post->post_title }}</option>
							@endif
							@if ($post->post_type=="article")
								<option value="{{ 'article/'.$post->post_slug }}" {{ $post->post_slug=="#"?'disabled':'' }}  {{ (isset($banner->banner_id) && 'article/'.$post->post_slug==$banner->banner_url)?'selected':'' }}>{{ $post->post_title }}</option>
							@endif
							<?php $type = $post->post_type; ?>
						@endforeach
					</select>
					<input type="text" class="form-control" id="banner_url_ext" name="banner_url_ext" value="{{ isset($banner->banner_id)?$banner->banner_url:'' }}" placeholder="http://external.link" {!! (isset($banner->banner_id) && ($banner->banner_url_ext))?'style="margin-top:5px;"':'style="margin-top:5px;display:none;" disabled' !!}>
				</div>
			</div>
			<div class="form-group">
				<label for="banner_target" class="col-sm-2 control-label">Target</label>
				<div class="col-sm-4">
					<select name="banner_target" class="form-control zetth-select">
					@foreach(_option_target() as $key => $value)
						<option {{ (isset($banner->banner_id) && $value==$banner->banner_target)?'selected':'' }} value="{{ $key }}">{{ $value }}</option>
					@endforeach
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="banner_status" {{ (isset($banner->banner_status) && $banner->banner_status==0)?'':'checked' }}> Active
						</label>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>
							<input type="checkbox" name="banner_only" {{ (isset($banner->banner_only) && $banner->banner_only==1)?'checked':'' }}> Image Only
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

@section('scripts')
{!! _load_select2('js') !!}
{!! _load_fancybox('js') !!}
<script>
$(function(){
	$(".select2").select2({
		placeholder: "[None]"
	});
	$(".zetth-select").select2({
		minimumResultsForSearch: Infinity
	});
});

function responsive_filemanager_callback(field_id){
	var url = $('#'+field_id).val().replace(SITE_URL, "");
	var img = '<img src="'+url+'">';
	$('.zetth-upload-new').hide();
	$('.zetth-upload-exists').show();
	$('.zetth-upload-exists.thumbnail').html(img);
	$('#banner_image_remove').attr("checked", false);
}

$(document).ready(function(){
	$("body").tooltip({ 
		selector: '[data-toggle=tooltip]' 
	});

	var wFB = window.innerWidth - 30,
		hFB = window.innerHeight - 60;

	$('.select2').on('change',function(){
		if ($('#banner_url').val()=="external"){
			$('#banner_url_ext').attr("disabled", false).show();
		}else{
			$('#banner_url_ext').attr("disabled", true).hide();
		}
	});
	$('#btn-upload').fancybox({
		type      : 'iframe',
		autoScale : false,
		autoSize : false,
		beforeLoad : function() {
			this.width  = wFB;
			this.height = hFB;
		}
	});
	$('#btn-remove').on('click', function(){
		$('#post_cover').val('');
		$('.zetth-upload-new').show();
		$('.zetth-upload-exists').hide();
	});
});
</script>
@endsection
