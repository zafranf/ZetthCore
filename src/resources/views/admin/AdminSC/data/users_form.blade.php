@extends('admin.AdminSC.layouts.main')

@section('content')
	<div class="panel-body">
		<form class="form-horizontal" action="{{ url($current_url) }}{{ isset($data) ? '/'.$data->id : '' }}" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-6">
					<h4>Informasi Utama</h4>
					<hr>
					<div class="form-group">
						<label for="name" class="col-md-4 control-label">Nama Akses</label>
						<div class="col-md-8">
							<input type="text" class="form-control autofocus" id="name" name="name" value="{{ isset($data) ? $data->name : '' }}" maxlength="30" placeholder="Nama akses.." {{ isset($data) ? 'readonly' : '' }}>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-4 control-label">Surel</label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="email" name="email" value="{{ isset($data) ? $data->email : '' }}" maxlength="100" placeholder="Alamat surel..">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-md-4 control-label">Sandi</label>
						<div class="col-md-8">
							<input type="password" class="form-control" id="password" name="password" placeholder="Kata sandi..">
						</div>
					</div>
					<div class="form-group">
						<label for="password_confirmation" class="col-md-4 control-label">Ulangi Sandi</label>
						<div class="col-md-8">
							<input type="password" class="form-control" id="password_confirmation" name="password_confirmation"  placeholder="Konfirmasi kata sandi..">
						</div>
					</div>
					<div class="form-group">
						<label for="fullname" class="col-md-4 control-label">Nama Lengkap</label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="fullname" name="fullname" value="{{ isset($data) ? $data->fullname : '' }}" maxlength="50" placeholder="Nama lengkap.." }}>
						</div>
					</div>
					<div class="form-group">
						<label for="biography" class="col-md-4 control-label">Biografi</label>
						<div class="col-md-8">
							<textarea id="biography" name="biography" class="form-control" placeholder="Biografi.." rows="4">{{ isset($data) ? $data->biography : '' }}</textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="image" class="col-md-4 control-label">Foto</label>
						<div class="col-md-8">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail">
									<img src="{{ _get_image('/storage/assets/images/user/' . (isset($data) ? $data->image : '')) }}">
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail"></div>
								<div>
									<span class="btn btn-default btn-file">
										<span class="fileinput-new">Pilih</span>
										<span class="fileinput-exists">Ganti</span>
										<input name="image" id="image" type="file" accept="image/*">
									</span>
									<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Hapus</a>
								</div>
							</div>
						</div>
					</div>
					<div class="form-role">
						<label for="password" class="col-md-4 control-label">Peran</label>
						<div class="col-md-8">
							<select name="role" id="role" class="form-control custom-select2">
                @if (isset($roles))
                  @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ (isset($data) && $data->role_ids[0]->role_id == $role->id) ? 'selected' : '' }} >{{ $role->display_name }}</option>
                  @endforeach
                @endif
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-4 col-md-8">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="status" {{ (isset($data) && $data->status == 0 ) ?'' : 'checked' }}> Aktif
								</label>
							</div>
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
								@foreach($socmed_data as $key => $val)
                  @php
                    $rand = rand(111111111, 999999999);
                  @endphp
                  <div id="div-socmed-{{ $rand }}">
                    <div class="col-md-3 col-xs-6 no-padding">
                      <select name="socmed_id[]" class="form-control custom-select2">
                        <option value="">--Pilih--</option>
                        @if (isset($socmeds))
                          @foreach($socmeds as $socmed)
                            @php
                              $sl = ($socmed->id==$val->socmed->id) ? 'selected' : '';
                            @endphp
                            <option value="{{ $socmed->id }}" {{ $sl }}>{{ $socmed->name }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="col-md-9 col-xs-6 no-padding">
                      @if ($key > 0)
                        <div class="input-group">
                          <input type="text" class="form-control" name="socmed_uname[]" placeholder="Nama/ID akun.." value="{{ $val->username }}">
                          <span class="input-group-btn">
                            <button type="button" class="btn" style="background:white;border:1px solid #ccc;" onclick="_remove('#div-socmed-{{ $rand }}')"><i class="fa fa-minus"></i></button
                          </span>
                        </div>
                      @else
                        <input type="text" class="form-control" name="socmed_uname[]" placeholder="Nama/ID akun.." value="{{ $val->username }}">
                      @endif
                    </div>
                  </div>
								@endforeach
							@else
								<div class="col-md-3 col-xs-6 no-padding">
									<select name="socmed_id[]" class="form-control custom-select2">
                    <option value="">--Pilih--</option>
                    @if (isset($socmeds))
                      @foreach($socmeds as $socmed)
                        <option value="{{ $socmed->id }}">{{ $socmed->name }}</option>
                      @endforeach
                    @endif
									</select>
								</div>
								<div class="col-md-9 col-xs-6 no-padding">
									<input type="text" class="form-control" name="socmed_uname[]" placeholder="Nama/ID akun..">
								</div>
							@endif
							<div id="div-socmed"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-4">
          {{ isset($data) ? method_field('PUT') : '' }}
          {{ csrf_field() }}
					{{ _get_button_post($current_url, true, $data->id ?? '') }}
				</div>
			</div>
		</form>
	</div>
@endsection

@section('styles')
	{!! _load_css('themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css') !!}
	{!! _load_css('themes/admin/AdminSC/plugins/select2/4.0.0/css/select2.min.css') !!}
@endsection

@section('scripts')
{!! _load_js('themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js') !!}
{!! _load_js('themes/admin/AdminSC/plugins/select2/4.0.0/js/select2.min.js') !!}
  <script>
    $(function(){
      $(".custom-select2").select2({
        minimumResultsForSearch: Infinity
      });
    });

    $(document).ready(function(){
      $('#btn-add-socmed').on('click', function(){
        socmed_no = (Math.random() * 1000000000).toFixed(0);
        var html = '<div id="div-socmed-'+socmed_no+'"><div class="col-md-3 col-xs-6 no-padding">'+
                '<select name="socmed_id[]" class="form-control custom-select2">'+
                  '<option value="">--Pilih--</option>'+
                  @if (isset($socmeds))
                    @foreach($socmeds as $socmed)
                      '<option value="{{ $socmed->id }}">{{ $socmed->name }}</option>'+
                    @endforeach
                  @endif
                '</select>'+
              '</div>'+
              '<div class="col-md-9 col-xs-6 no-padding">'+
                '<div class="input-group">'+
                  '<input type="text" class="form-control" name="socmed_uname[]" placeholder="Nama/ID akun..">'+
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
