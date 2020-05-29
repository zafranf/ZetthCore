@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
    <form class="form-horizontal" action="{{ _url($current_url) }}/{{ app('site')->id ?? '' }}" method="post"
      enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6">
          <h4>Informasi Utama</h4>
          <hr>
          <div class="form-group">
            <label for="logo" class="col-md-4 control-label">
              Logo
              <small class="help-block">Maksimal dimensi logo adalah 512px x 512px dengan ukuran maksimal 384KB</small>
            </label>
            <div class="col-md-8">
              <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail">
                  <img src="{{ getImageLogo() }}">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                <div>
                  <span class="btn btn-default btn-file">
                    <span class="fileinput-new">Pilih</span>
                    <span class="fileinput-exists">Ganti</span>
                    <input type="file" id="logo" name="logo" accept="image/*">
                  </span>
                  <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Batal</a>
                  @if (app('site')->logo)
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
            <label for="icon" class="col-md-4 control-label">
              Ikon
              <small class="help-block">Maksimal dimensi ikon adalah 128px x 128px dengan ukuran maksimal 96 KB</small>
            </label>
            <div class="col-md-8">
              <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                <div class="form-control" data-trigger="fileinput">
                  <div class="fileinput-new thumbnail"
                    style="width:20px!important;padding:0;margin-bottom:8px;position:absolute;left:5px;">
                    <img src="{{ getImageLogo(app('site')->icon) }}" width="20">
                  </div>
                  <div class="fileinput-preview fileinput-exists thumbnail" style="width:20px!important;padding:0;margin-bottom:8px;position:absolute;left:5px;">
                  </div>
                  <span class="fileinput-filename" style="margin-bottom:5px;position:relative;left:20px;"></span>
                </div>
                <span class="input-group-addon btn btn-default btn-file" style="border-left:1px solid coral!important;">
                  <span class="fileinput-new">Pilih</span>
                  <span class="fileinput-exists">Ganti</span>
                  <input type="file" id="icon" name="icon" accept="image/*">
                </span>
                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Batal</a>
              </div>
              <small class="help-block">
                <label>
                  <input type="checkbox" name="use_logo" id="use_logo"> Gunakan logo
                </label>
                @if (app('site')->icon)
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
              <input type="text" class="form-control autofocus" id="name" name="name" value="{{ app('site')->name ?? old('name') }}" placeholder="Nama situs.." maxlength="50">
            </div>
          </div>
          <div class="form-group">
            <label for="slogan" class="col-md-4 control-label">Slogan</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="tagline" name="tagline" value="{{ app('site')->tagline ?? old('tagline') }}" placeholder="Slogan situs.." maxlength="100">
            </div>
          </div>
          <div class="form-group">
            <label for="email" class="col-md-4 control-label">Surel</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="email" name="email" value="{{ app('site')->email ?? old('email') }}" placeholder="Alamat surel.." maxlength="100">
            </div>
          </div>
          <div class="form-group">
            <label for="phone" class="col-md-4 control-label">No. Telepon</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="phone" name="phone" value="{{ app('site')->phone ?? old('phone') }}" placeholder="Nomor telepon.." maxlength="16">
            </div>
          </div>
          <div class="form-group" {!! (Auth::user()->id != 1)?'style="display:none;"':'' !!}>
            <label for="perpage" class="col-md-4 control-label">Data Perhalaman</label>
            <div class="col-md-8">
              <input type="number" class="form-control" id="perpage" name="perpage" value="{{ app('site')->perpage ?? old('perpage') }}" placeholder="Jumlah data perhalaman.." min="3" max="100">
            </div>
          </div>
          <div class="form-group">
            <label for="enable" class="col-md-4 control-label">
              Akses Pengunjung
              {{-- <small class="help-block">Fitur untuk pengunjung situs</small> --}}
            </label>
            <div class="col-md-8">
              <div class="checkbox">
                <div class="col-xs-6 col-sm-3">
                  <label>
                    <input type="checkbox" id="enable-subscribe" name="enable_subscribe" value="yes" {{ (!bool(app('site')->enable_subscribe)) ? '' : 'checked' }}> Langganan
                  </label>
                </div>
                <div class="col-xs-6 col-sm-3">
                  <label>
                    <input type="checkbox" id="enable-comment" name="enable_comment" value="yes" {{ (!bool(app('site')->enable_comment)) ? '' : 'checked' }}> Komentar
                  </label>
                </div>
                <div class="col-xs-6 col-sm-3">
                  <label>
                    <input type="checkbox" id="enable-like" name="enable_like" value="yes" {{ (!bool(app('site')->enable_like)) ? '' : 'checked' }}> Suka
                  </label>
                </div>
                <div class="col-xs-6 col-sm-3">
                  <label>
                    <input type="checkbox" id="enable-share" name="enable_share" value="yes" {{ (!bool(app('site')->enable_share)) ? '' : 'checked' }}> Sebar
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="status" class="col-md-4 control-label">Status</label>
            <div class="col-md-8">
              <select class="form-control custom-select2" id="status" name="status">
                <option value="active" {{ (app('site')->status == 'active') ? 'selected' : '' }}>Aktif</option>
                <option value="comingsoon" {{ (app('site')->status == 'comingsoon') ? 'selected' : '' }}>Segera Hadir</option>
                <option value="maintenance" {{ (app('site')->status == 'maintenance') ? 'selected' : '' }}>Perbaikan</option>
                @if (app('user')->hasRole('super')) 
                  <option value="suspend" {{ (app('site')->status == 'suspend') ? 'selected' : '' }}>Ditangguhkan</option>
                @endif
              </select>
            </div>
          </div>
          <div class="form-group" {!! (app('site')->status == 'active' || app('site')->status == 'suspend') ? 'style="display:none;"' : '' !!} id="d-active-at">
            <label for="active_at" class="col-md-4 control-label">Aktif pada</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="active-at" name="active_at" value="{{ isset(app('site')->active_at) ? carbon(app('site')->active_at)->format('Y-m-d') : '' }}" {!!
                (app('site')->status == 'active') ? 'readonly' : '' !!} placeholder="Dibuka pada..">
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <h4>
            Media Sosial 
            <span class="btn btn-default btn-xs pull-right" id="btn-add-socmed">
              <i class="fa fa-plus"></i>
            </span>
          </h4>
          <hr>
          <div class="form-group">
            <label for="label" class="col-md-4 control-label">
              Akun
              <small class="help-block">Akun sosial media situs</small>
            </label>
            <div class="col-md-8">
              @if (isset(app('site')->socmed) && count(app('site')->socmed) > 0)
                @foreach (app('site')->socmed as $key => $val)
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
                              $sl = ($socmed->id == $val->socmed_id) ? 'selected' : ''
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
            <label for="keywords" class="col-md-4 control-label">
              Kata Kunci
              <small class="help-block">Ketik kata kunci lalu tekan enter untuk menambahkan</small>
            </label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="keywords" name="keywords" value="{{ app('site')->keywords ?? old('keywords') }}" placeholder="Kata kunci situs.." maxlength="50">
            </div>
          </div>
          <div class="form-group">
            <label for="description" class="col-md-4 control-label">Deskripsi</label>
            <div class="col-md-8">
              <textarea class="form-control" id="description" name="description" rows="4" placeholder="Penjelasan singkat mengenai situs..">{{ app('site')->description ?? old('description') }}</textarea>
            </div>
          </div>
          <div class="form-group">
            <label for="google_analytics" class="col-md-4 control-label">
              Google Analytics
              <small class="help-block">Kode untuk analitik website dari Google</small>
            </label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="google-analytics" name="google_analytics" value="{{ app('site')->google_analytics ?? old('google_analytics') }}" placeholder="Kode Google Analytics.." maxlength="20">
            </div>
          </div>
          <h4>Lokasi</h4>
          <hr>
          <div class="form-group">
            <label for="address" class="col-md-4 control-label">Alamat</label>
            <div class="col-md-8">
              <textarea class="form-control" id="address" name="address" rows="4" placeholder="Alamat lengkap.." maxlength="280">{{ app('site')->address ?? old('address') }}</textarea>
            </div>
          </div>
          <div class="form-group">
            <label for="coordinate" class="col-md-4 control-label">
              Nama Lokasi/Titik Koordinat
              <small class="help-block">
                (contoh: <b>Monas</b> atau
                <b>-6.1762332,106.8206543</b>)
              </small>
            </label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="coordinate" name="coordinate" value="{{ app('site')->coordinate ?? old('coordinate') }}" placeholder="Titik koordinat.." maxlength="30">
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-offset-2 col-md-10">
          {{ isset(app('site')->id) ? method_field('PUT') : '' }}
          {{ csrf_field() }}
          {{ getButtonPost(_url(adminPath() . '/dashboard')) }}
        </div>
      </div>
    </form>
  </div>
@endsection

@push('styles')
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css') !!}
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/bootstrap/tagsinput/0.8.0/css/bootstrap-tagsinput.css') !!}
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/bootstrap/datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css') !!}
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/select2/4.0.0/css/select2.min.css') !!}

  <style>
    .group-socmed {
      width: 125px;
      padding: 3px 5px;
      text-align: left;
      font-size: 12px;
    }

    .btn.input-group-addon {
      background: transparent;
    }

    .fileinput.input-group>.form-control {
      border-right: 0;
    }
  </style>
@endpush

@push('scripts')
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/bootstrap/tagsinput/0.8.0/js/bootstrap-tagsinput.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/moment/2.13.0/js/moment.min.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/bootstrap/datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/select2/4.0.0/js/select2.min.js') !!}
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
          if ($('#status').val() != 'active' && $('#status').val() != 'suspend'){
            $('#d-active-at').show();
            $('#active-at').attr('readonly', false);
          } else {
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
                      @foreach ($socmeds as $socmed)
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
@endpush