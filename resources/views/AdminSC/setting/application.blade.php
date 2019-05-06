@extends('admin.AdminSC.layouts.main')

@section('content')
	<div class="panel-body">
		<form class="form-horizontal" action="{{ url($current_url) }}/{{ $apps->id ?? '' }}" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-6">
					<h4>Informasi Utama</h4>
					<hr>
					<div class="form-group">
						<label for="name" class="col-md-4 control-label">
              Logo
              <small class="help-block">Maksimal dimensi logo adalah 512x512 piksel dengan ukuran maksimal 384 KB</small>
            </label>
						<div class="col-md-8">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail">
									<img src="{{ _get_image("/assets/images/" . $apps->logo, "/assets/images/logo.jpg") }}">
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail"></div>
								<div>
									<span class="btn btn-default btn-file">
										<span class="fileinput-new">Pilih</span>
										<span class="fileinput-exists">Ganti</span>
										<input type="file" id="logo" name="logo" accept="image/*">
									</span>
									<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Batal</a>
                  @if (isset($apps->logo))
                    <small class="help-inline-table">
                      <label class="pull-right">
                        <input type="checkbox" name="logo_remove" id="logo_remove"> Hapus
                      </label>
                    </small>
                  @endif
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="name" class="col-md-4 control-label">
              Ikon
							<small class="help-block">Maksimal dimensi ikon adalah 128x128 piksel dengan ukuran maksimal 96 KB</small>
            </label>
						<div class="col-md-8">
							<div class="fileinput fileinput-new input-group" data-provides="fileinput">
								<div class="form-control" data-trigger="fileinput">
                  <div class="fileinput-new thumbnail" style="width:20px;padding:0;margin-bottom:8px;position:absolute;left:5px;">
                    <img src="{{ _get_image("/assets/images/" . $apps->icon, "/assets/images/logo.jpg") }}" width="20">
                  </div>
                  <div class="fileinput-preview fileinput-exists thumbnail" style="width:20px;padding:0;margin-bottom:8px;position:absolute;left:5px;"></div>
									<span class="fileinput-filename" style="margin-bottom:5px;position:relative;left:20px;"></span>
								</div>
								<span class="input-group-addon btn btn-file">
									<span class="fileinput-new">Pilih</span>
									<span class="fileinput-exists">Ganti</span>
                  <input type="file" id="icon" name="icon" accept="image/*">
								</span>
								<a href="#" class="input-group-addon btn fileinput-exists" data-dismiss="fileinput">Batal</a>
							</div>
							<small class="help-block">
								<label>
									<input type="checkbox" name="use_logo" id="use_logo"> Gunakan logo
								</label>
                @if (isset($apps->icon))
                  <label class="pull-right">
                    <input type="checkbox" name="icon_remove" id="icon_remove"> Hapus
                  </label>
                @endif
							</small>
						</div>
					</div>
					<div class="form-group">
						<label for="name" class="col-md-4 control-label">Nama Situs</label>
						<div class="col-md-8">
							<input type="text" class="form-control autofocus" id="name" name="name" value="{{ $apps->name ?? '' }}" placeholder="Nama situs.." maxlength="50">
						</div>
					</div>
					<div class="form-group">
						<label for="slogan" class="col-md-4 control-label">Slogan</label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="tagline" name="tagline" value="{{ $apps->tagline ?? '' }}" placeholder="Slogan situs.." maxlength="100">
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-4 control-label">Surel</label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="email" name="email" value="{{ $apps->email ?? '' }}" placeholder="Alamat surel.." maxlength="100">
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-md-4 control-label">Telepon</label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="phone" name="phone" value="{{ $apps->phone ?? '' }}" placeholder="Nomor telepon.." maxlength="16">
						</div>
					</div>
					{{-- <div class="form-group" {!! (Auth::user()->id != 1)?'style="display:none;"':'' !!}>
						<label for="max_login_failed" class="col-md-4 control-label">Max Login Failed</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="max_login_failed" value="{{ $apps->max_login_failed ?? '' }}" maxlength="1">
							<span class="text-danger">{{ ($errors->has('max_login_failed'))?$errors->first('max_login_failed'):'' }}</span>
						</div>
					</div> --}}
					{{-- <div class="form-group" {!! (Auth::user()->id != 1)?'style="display:none;"':'' !!}>
						<label for="lockout_time" class="col-md-4 control-label">Lockout Time <small>(in minutes)</small></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="lockout_time" value="{{ $apps->lockout_time ?? '' }}" maxlength="2">
							<span class="text-danger">{{ ($errors->has('lockout_time'))?$errors->first('lockout_time'):'' }}</span>
						</div>
					</div> --}}
					<div class="form-group" {!! (Auth::user()->id != 1)?'style="display:none;"':'' !!}>
						<label for="perpage" class="col-md-4 control-label">Data Perhalaman</label>
						<div class="col-md-8">
							<input type="number" class="form-control" id="perpage" name="perpage" value="{{ $apps->perpage ?? 0 }}" placeholder="Jumlah data perhalaman.." min="3" max="100">
						</div>
					</div>
					<div class="form-group">
						<label for="enable" class="col-md-4 control-label">Aktifkan</label>
						<div class="col-md-8">
							<div class="checkbox">
								<div class="col-xs-6 col-sm-3">
									<label>
										<input type="checkbox" id="enable-subscribe" name="enable_subscribe" {{ (!$apps->enable_subscribe) ? '' : 'checked' }}> Langganan
									</label>
								</div>
								<div class="col-xs-6 col-sm-3">
									<label>
										<input type="checkbox" id="enable-comment" name="enable_comment" {{ (!$apps->enable_comment) ? '' : 'checked' }}> Komentar
									</label>
								</div>
								<div class="col-xs-6 col-sm-3">
									<label>
										<input type="checkbox" id="enable-like" name="enable_like" {{ (!$apps->enable_like) ? '' : 'checked' }}> Suka
									</label>
								</div>
								<div class="col-xs-6 col-sm-3">
									<label>
										<input type="checkbox" id="enable-share" name="enable_share" {{ (!$apps->enable_share) ? '' : 'checked' }}> Sebar
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="status" class="col-md-4 control-label">Status</label>
						<div class="col-md-8">
							<select class="form-control custom-select2" id="status" name="status">
								<option value="1" {{ ($apps->status == 1) ? 'selected' : '' }}>Aktif</option>
								<option value="0" {{ ($apps->status == 0) ? 'selected' : '' }}>Segera Hadir</option>
								<option value="2" {{ ($apps->status == 2) ? 'selected' : '' }}>Perbaikan</option>
							</select>
						</div>
					</div>
					<div class="form-group" {!! ($apps->status == 1) ? 'style="display:none;"' : '' !!} id="d-active-at">
						<label for="active_at" class="col-md-4 control-label">Dibuka pada</label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="active-at" name="active_at" value="{{ isset($apps) ? date("Y-m-d", strtotime($apps->active_at)) : '' }}" {!! ($apps->status == 1) ? 'readonly' : '' !!} placeholder="Dibuka pada..">
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<h4>Media Sosial <span class="btn btn-default btn-xs pull-right" id="btn-add-socmed"><i class="fa fa-plus"></i> Tambah</span></h4>
					<hr>
					<div class="form-group">
						<label for="label" class="col-md-4 control-label">Akun</label>
						<div class="col-md-8">
							@if (isset($socmed_data) && count($socmed_data) > 0)
								@foreach ($socmed_data as $key => $val)
									@php
										$rand = rand(111111111, 999999999);
									@endphp
									<div id="div-socmed-{{ $rand }}">
										<div class="col-md-3 col-xs-6 no-padding">
                      <select class="form-control custom-select2" id="socmed-id-{{ $rand }}" name="socmed_id[]">
                        <option value="">--Pilih--</option>
                        @if (isset($socmeds))
                          @foreach ($socmeds as $socmed)
                            @php 
                              $sl = ($socmed->id==$val->socmed->id) ? 'selected' : ''
                            @endphp
                            <option value="{{ $socmed->id }}" {{ $sl }}>{{ $socmed->name }}</option>
                          @endforeach
                        @endif
                      </select>
										</div>
										<div class="col-md-9 col-xs-6 no-padding">
                      @if ($key > 0)
                        <div class="input-group">
                          <input type="text" class="form-control" id="socmed-uname-{{ $rand }}" name="socmed_uname[]" placeholder="Nama/ID akun.." maxlength="50" value="{{ $val->username }}">
                          <span class="input-group-btn">
                            <button type="button" class="btn" style="background:white;border:1px solid #ccc;" onclick="_remove('#div-socmed-{{ $rand }}')"><i class="fa fa-minus"></i></button>
                          </span>
                        </div>
                      @else
                        <input type="text" class="form-control" id="socmed-uname-{{ $rand }}" name="socmed_uname[]" placeholder="Nama/ID akun.." value="{{ $val->username }}">
                      @endif
										</div>
									</div>
								@endforeach
							@else
								<div class="col-md-3 col-xs-6 no-padding">
                  <select class="form-control custom-select2" id="socmed-id" name="socmed_id[]">
										<option value="">--Pilih--</option>
										@if (isset($socmeds))
                      @foreach ($socmeds as $socmed)
                        <option value="{{ $socmed->id }}">{{ $socmed->name }}</option>
                      @endforeach
										@endif
									</select>
								</div>
								<div class="col-md-9 col-xs-6 no-padding">
                  <input type="text" class="form-control" id="socmed-uname" name="socmed_uname[]" placeholder="Nama/ID akun.." maxlength="50">
								</div>
							@endif
							<div id="div-socmed"></div>
						</div>
					</div>
					<h4>Pengaturan SEO</h4>
					<hr>
					<div class="form-group">
						<label for="keywords" class="col-md-4 control-label">Kata Kunci</label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="keywords" name="keywords" value="{{ $apps->keywords ?? '' }}" placeholder="Tekan enter untuk konfirmasi.." maxlength="50">
						</div>
					</div>
					<div class="form-group">
						<label for="description" class="col-md-4 control-label">Deskripsi</label>
						<div class="col-md-8">
							<textarea class="form-control" id="description" name="description" rows="4" placeholder="Penjelasan singkat mengenai situs..">{{ $apps->description ?? '' }}</textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="google_analytics" class="col-md-4 control-label">
              Google Analytics
							<small class="help-block">Kode untuk analitik website dari Google</small>
            </label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="google-analytics" name="google_analytics" value="{{ $apps->google_analytics ?? '' }}" placeholder="Kode lacak dari Google Analytics.." maxlength="20">
						</div>
					</div>
					<h4>Lokasi</h4>
					<hr>
					<div class="form-group">
						<label for="address" class="col-md-4 control-label">Alamat</label>
						<div class="col-md-8">
							<textarea class="form-control" id="address" name="address" rows="4" placeholder="Alamat lengkap.." maxlength="280">{{ $apps->address ?? '' }}</textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="coordinate" class="col-md-4 control-label">
              Koordinat
              <small class="help-block">Garis lintang dan garis bujur.<br>(contoh: -6.229728, 106.6894312)</small>
            </label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="coordinate" name="coordinate" value="{{ $apps->coordinate ?? '' }}" placeholder="Titik koordinat.." maxlength="30">
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-10">
					{{ isset($apps) ? method_field('PUT') : '' }}
					{{ csrf_field() }}
				  {{ _get_button_post(url($adminPath . '/dashboard')) }}
				</div>
			</div>
		</form>
	</div>
@endsection

@section('styles')
	{!! _load_css('themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css') !!}
	{!! _load_css('themes/admin/AdminSC/plugins/bootstrap/tagsinput/0.8.0/css/bootstrap-tagsinput.css') !!}
	{!! _load_css('themes/admin/AdminSC/plugins/bootstrap/datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css') !!}
	{!! _load_css('themes/admin/AdminSC/plugins/select2/4.0.0/css/select2.min.css') !!}

	<style>
		.group-socmed {
			width:125px;
			padding:3px 5px;
			text-align:left;
			font-size: 12px;
		}
	</style>
@endsection

@section('scripts')
	{!! _load_js('themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js') !!}
	{!! _load_js('themes/admin/AdminSC/plugins/bootstrap/tagsinput/0.8.0/js/bootstrap-tagsinput.js') !!}
	{!! _load_js('themes/admin/AdminSC/plugins/moment/2.13.0/js/moment.min.js') !!}
	{!! _load_js('themes/admin/AdminSC/plugins/bootstrap/datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js') !!}
	{!! _load_js('themes/admin/AdminSC/plugins/select2/4.0.0/js/select2.min.js') !!}
	<script>
		$(function() {
			$('#active-at').datetimepicker({
				format: 'YYYY-MM-DD'
      });
      
			$(".custom-select2").select2({
				minimumResultsForSearch: Infinity
			});
		});

		$(document).ready(function() {
			$('#status').on("change", function(){
				if ($('#status').val()!=1){
					$('#d-active-at').show();
					$('#active-at').attr('readonly', false);
				}else{
					$('#d-active-at').hide();
					$('#active-at').attr('readonly', true);
				}
      });
      
			$('#keywords').tagsinput({
				tagClass: function(item){
					return 'label label-warning'
				}
      });
      
			$('#btn-add-socmed').on('click', function(){
				socmed_no = (Math.random() * 1000000000).toFixed(0);
				var html = '<div id="div-socmed-'+socmed_no+'"><div class="col-md-3 col-xs-6 no-padding">'+
								'<select class="form-control custom-select2" id="socmed-id-'+socmed_no+'" name="socmed_id[]">'+
									'<option value="">--Choose--</option>'+
									@if (isset($socmeds))
										@foreach($socmeds as $socmed)
											'<option value="{{ $socmed->id }}">{{ $socmed->name }}</option>'+
										@endforeach
									@endif
								'</select>'+
							'</div>'+
							'<div class="col-md-9 col-xs-6 no-padding">'+
								'<div class="input-group">'+
									'<input type="text" class="form-control" id="socmed-uname-'+socmed_no+'" name="socmed_uname[]" placeholder="Nama/ID akun.." maxlength="50">'+
									'<span class="input-group-btn">'+
										'<button type="button" class="btn" style="background:white;border:1px solid #ccc;" onclick="_remove(\'#div-socmed-'+socmed_no+'\')"><i class="fa fa-minus"></i></button'+
									'</span>'+
								'</div>'+
							'</div></div>';
        $('#div-socmed').append(html);
        
				$(".custom-select2").select2({
					minimumResultsForSearch: Infinity
				});
			});
		});
	</script>
@endsection
