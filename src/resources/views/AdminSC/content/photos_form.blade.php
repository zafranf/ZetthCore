@extends('zetthcore::AdminSC.layouts.main')

@section('content')
  <div class="panel-body">
    <form class="form-horizontal" action="{{ _url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
      <div class="row">
        <div id="div-album" class="col-md-2 col-sm-12">
          <h4>Album</h4>
          <hr>
          <div class="form-group">
            {{-- <label for="name" class="col-sm-4 control-label">Nama Album</label> --}}
            <div class="col-sm-12">
              <input type="text" class="form-control autofocus" id="name" name="name" value="{{ isset($data->id) ? $data->name : old('name') }}" maxlength="100" placeholder="Nama album..">
            </div>
          </div>
          <div class="form-group">
            {{-- <label for="description" class="col-sm-2 control-label">Deskripsi</label> --}}
            <div class="col-sm-12">
              <textarea class="form-control" id="description" name="description" placeholder="Penjelasan singkat tentang album..">{{ isset($data->id) ? $data->description : old('description') }}</textarea>
            </div>
          </div>
          {{-- <div class="form-group">
            <div class="col-sm-12">
              <div class="thumbnail text-warning" style="height:150px;display:block;width:100%;margin-bottom:1px;text-align:center;cursor:pointer;" onclick="addPhotoModal()">
                <i class="fa fa-plus" style="font-size:80px;"></i>
                <br>
                <span class="" style="font-size:28px;">Tambah Foto</span>
                <input type="hidden" id="input_tmp">
              </div>
            </div>
          </div> --}}
          <div class="form-group">
            <div class="col-sm-12">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="status" value="active" {{ (isset($album->status) && $album->status == 'inactive') ? '' : 'checked' }}> Aktif
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12">
              <input type="hidden" id="input_tmp">
              {{ isset($data->id) ? method_field('PUT') : '' }}
              {{ csrf_field() }}
              {{ getButtonPost($current_url, true, $data->id ?? '', isset($data) ? 'album \\\'' . $data->name . '\\\'' : null) }}
            </div>
          </div>
        </div>
        <div id="div-photo" class="col-md-10 col-sm-12">
          <h4>Foto <span class="btn btn-default btn-xs pull-right" id="btn-add-photo" onclick="addPhotoModal()" style="cursor:pointer;"><i class="fa fa-plus"></i></span></h4>
          <hr>
          <div class="row">
            <div class="col-sm-12" style="max-height:400px;overflow:auto;" id="photo-box">
              <div class="col-sm-6 col-md-2 hidden-xs" style="padding:10px;padding-top:0;padding-bottom:20px;cursor:pointer;" onclick="addPhotoModal()">
                <div class="thumbnail text-warning" style="height:150px;display:{{ app('is_desktop') ? 'table-cell' : 'block' }};width:inherit;margin-bottom:1px;text-align:center;">
                  <i class="fa fa-plus" style="font-size:80px;"></i>
                  <br>
                  <span class="" style="font-size:28px;">Tambah Foto</span>
                  {{-- <input type="hidden" id="input_tmp"> --}}
                </div>
              </div>
              @php $no_img = 0 @endphp
              @if (isset($data->photos))
                @foreach ($data->photos as $photo)
                  <div id="img{{ ++$no_img }}" class="col-sm-6 col-md-2" style="padding:10px;padding-top:0;padding-bottom:20px;">
                    <div class="thumbnail" style="height:150px;display:{{ app('is_desktop') ? 'table-cell;' : 'block;' }}padding:0;width:inherit;margin-bottom:1px;background:#f8f8f8;">
                      <img src="{{ str_replace('/files/', '/thumbs/', $photo->file) }}" style="height:100px;">
                      <input type="hidden" name="photos[files][]" value="{{ $photo->file }}">
                      <textarea name="photos[descriptions][]" class="form-control" style="bottom:0;left:0;height:50px;" placeholder="Keterangan foto..">{{ $photo->description }}</textarea>
                      <button class="btn btn-default btn-xs btn-xs-top-right" title="Hapus foto" type="button" onclick="_remove_photo('#img{{ $no_img }}', {{ $photo->id }})" style="top:5px;right:15px;"><i class="fa fa-minus"></i></button>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@push('styles')
  {{-- {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css') !!} --}}
  {!! _admin_css(adminPath() . '/themes/admin/AdminSC/plugins/fancybox/2.1.5/css/jquery.fancybox.css') !!}
  <style>
    .zetth-process {
      display: none;
      background: #fff;
      width: 100%;
      height: 100%;
      position: absolute;
      z-index: 1;
      top: 0;
      left: 0;
      opacity: 0.7;
      text-align: center;
    }
    .zetth-loading {
      position: relative;
      top: 45%;
    }
    @media (max-width: 767px) {
      #div-photo {
        position: relative;
        top: 20px;
      }
    }
  </style>
@endpush

@push('scripts')
  {{-- {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js') !!} --}}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/fancybox/2.1.5/js/jquery.fancybox.js') !!}
  <script>
    var max_img = 20;
    var no_img = {{ $no_img }};
    var wFB = window.innerWidth - 30;
    var hFB = window.innerHeight - 60;

    function addPhotoModal() {
      if (no_img >= max_img){
        swal('Maksimal ' + max_img + ' foto');
      } else {
        $.fancybox({
          href : '{!! _url(adminPath() . "/larafile/dialog.php?type=1&multiple=1&field_id=input_tmp&lang=id") !!}',
          type : 'iframe',
          autoScale : false,
          autoSize : true,
          beforeLoad : function() {
            this.width  = wFB;
            this.height = hFB;
          }
        });
      }
    }

    function responsive_filemanager_callback(field_id){
      var vals = $('#input_tmp').val();
      try {
        vals = JSON.parse(vals);
      } catch (e) {
        vals = JSON.parse('["'+vals+'"]');
      }
      $.each(vals, function(i,val) {
        var path = val.replace(SITE_URL, "");
        addPhoto(path);
      });
    }

    function addPhoto(val) {
      no_img++;
      if (no_img >= max_img) {
        if (no_img == max_img) {
          setTimeout(function() {
            no_img = max_img;
            swal('Maksimal ' + max_img + ' foto');
          }, 500);
        }

        return;
      }
      if (val) {
        var photo = '<div id="img' + no_img + '" class="col-sm-6 col-md-2" style="padding:10px;padding-top:0;padding-bottom:20px;">'+
                      '<div class="thumbnail" style="height:150px;display:{{ app('is_desktop') ? 'table-cell' : 'block' }};padding:0;width:inherit;margin-bottom:1px;background:#f8f8f8;">'+
                          '<img src="' + val.replace('/files/', '/thumbs/') + '" style="height:100px;">'+
                          '<input type="hidden" name="photos[files][]" value="' + val + '">'+
                          '<textarea name="photos[descriptions][]" class="form-control" style="position:absoluste;bottom:0;left:0;height:50px;" placeholder="Keterangan foto.."></textarea>'+
                          '<button class="btn btn-default btn-xs btn-xs-top-right" title="Remove Photo" type="button" onclick="_remove_preview(\'#img' + no_img + '\')" style="top:5px;right:15px;"><i class="fa fa-minus"></i></button>'+
                      '</div>'+
                    '</div>';
        $('#photo-box').append(photo);
      }
    }

    function _remove_preview(id) {
      no_img--;
      $(id).remove();
    }

    function _remove_photo(id, id_photo) {
      no_img--;
      $(id).remove();
      var photodel = '<input type="hidden" name="photos[deletes][]" value="' + id_photo + '">';
      $('#photo-box').append(photodel);
    }
  </script>
@endpush