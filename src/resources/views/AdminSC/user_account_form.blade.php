@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
    <form class="form-horizontal" action="{{ url($current_url) }}" method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6">
          <h4>Informasi Utama</h4>
          <hr>
          <div class="form-group">
            <label for="image" class="col-md-4 control-label">Foto</label>
            <div class="col-md-8">
              <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail">
                  <img src="{{ getImageUser($data->image) }}">
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
          <div class="form-group">
            <label for="email" class="col-md-4 control-label">Email</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="email" name="email" value="{{ isset($data) ? $data->email : '' }}" maxlength="100" placeholder="Email">
            </div>
          </div>
          <div class="form-group">
            <label for="fullname" class="col-md-4 control-label">Nama Lengkap</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="fullname" name="fullname" value="{{ isset($data) ? $data->fullname : '' }}" maxlength="50" placeholder="Nama lengkap.." }}>
            </div>
          </div>
          <div class="form-group">
            <label for="about" class="col-md-4 control-label">Tentang</label>
            <div class="col-md-8">
              <textarea id="about" name="about" class="form-control" placeholder="Tentang..">{{ isset($data) ? $data->about : '' }}</textarea>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <h4>Media Sosial <span class="btn btn-default btn-xs pull-right" id="btn-add-socmed"><i class="fa fa-plus"></i></span></h4>
          <hr>
          <div class="form-group">
            <label for="label" class="col-md-4 control-label">Akun</label>
            <div class="col-md-8">
              @if (isset($data->socmed) && count($data->socmed) > 0)
                @foreach ($data->socmed as $key => $val)
                @php
                  $rand = rand(111111111, 999999999);
                @endphp
                <div id="div-socmed-{{ $rand }}">
                  <div class="col-md-3 no-padding">
                    <select name="socmed_id[]" class="form-control zetth-select">
                      <option value="">--Pilih--</option>
                      @if (isset($socmeds))
                        @foreach ($socmeds as $socmed)
                          @php
                            $sl = ($socmed->id == $val->socmed_id) ? 'selected' : '';
                          @endphp
                          <option value="{{ $socmed->id }}" {{ $sl }}>{{ $socmed->name }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                  <div class="col-md-9 no-padding">
                    @if ($key > 0)
                      <div class="input-group">
                        <input type="text" class="form-control" name="socmed_uname[]" placeholder="Nama/ID akun.." value="{{ $val->username }}">
                        <span class="input-group-btn">
                          <button type="button" class="btn" style="background:white;border:1px solid #ccc;" onclick="_remove('#div-socmed-{{ $rand }}')"><i class="fa fa-minus"></i></button>
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
                      @foreach ($socmeds as $socmed)
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
          <h4>Ganti Sandi</h4>
          <hr>
          <div class="form-group">
            <label for="password" class="col-md-4 control-label">Sandi Lama</label>
            <div class="col-md-8">
              <input type="password" class="form-control" id="password_old" name="password_old" placeholder="Kata sandi lama..">
            </div>
          </div>
          <div class="form-group">
            <label for="password" class="col-md-4 control-label">Sandi Baru</label>
            <div class="col-md-8">
              <input type="password" class="form-control" id="password" name="password" placeholder="Kata sandi baru..">
            </div>
          </div>
          <div class="form-group">
            <label for="password_confirmation" class="col-md-4 control-label">Ulangi Sandi Baru</label>
            <div class="col-md-8">
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"  placeholder="Konfirmasi kata sandi baru..">
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-offset-2 col-md-10">
          <div class="box-footer">
            {{ isset($data) ? method_field('PUT') : '' }}
            {{ csrf_field() }}
            {{ getButtonPost($current_url, true) }}
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@push('styles')
	{!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css') !!}
	{!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/select2/4.0.0/css/select2.min.css') !!}
@endpush

@push('scripts')
	{!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js') !!}
	{!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/select2/4.0.0/js/select2.min.js') !!}
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
                @foreach ($socmeds as $socmed)
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
@endpush