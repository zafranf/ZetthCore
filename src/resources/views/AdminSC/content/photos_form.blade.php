@extends('zetthcore::AdminSC.layouts.main')

@section('content')
    <div class="panel-body">
        <form class="form-horizontal" action="{{ url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-2 col-sm-12">
                    <h4>Album</h4>
                    <hr>
                    <div class="form-group">
                        {{-- <label for="name" class="col-sm-4 control-label">Nama Album</label> --}}
                        <div class="col-sm-12">
                            <input type="text" class="form-control autofocus" id="name" name="name" value="{{ isset($data->id) ? $data->name : '' }}" maxlength="100" placeholder="Nama album..">
                        </div>
                    </div>
                    <div class="form-group">
                        {{-- <label for="description" class="col-sm-2 control-label">Deskripsi</label> --}}
                        <div class="col-sm-12">
                            <textarea class="form-control" id="description" name="description" placeholder="Penjelasan singkat tentang album..">{{ isset($data->id) ? $data->description : '' }}</textarea>
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
                                    <input type="checkbox" name="status" {{ (isset($album->status) && $album->status == 0) ? '' : 'checked' }}> Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="hidden" id="input_tmp">
                            {{ isset($data->id) ? method_field('PUT') : '' }}
                            {{ csrf_field() }}
                            {{ _get_button_post($current_url, true, $data->id ?? '') }}
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-sm-12">
                    <h4>Foto <span class="btn btn-default btn-xs pull-right" id="btn-add-photo" onclick="addPhotoModal()" style="cursor:pointer;"><i class="fa fa-plus"></i> Tambah</span></h4>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12" style="max-height:400px;overflow:auto;" id="photo-box">
                            {{-- <div class="col-sm-6 col-md-2 no-padding" style="margin-bottom:1px;cursor:pointer;" onclick="addPhotoModal()">
                                <div class="thumbnail text-warning" style="height:150px;display:{{ $is_desktop ? 'table-cell' : 'block' }};width:inherit;margin-bottom:1px;text-align:center;">
                                    <i class="fa fa-plus" style="font-size:80px;"></i>
                                    <br>
                                    <span class="" style="font-size:28px;">Foto</span>
                                    <input type="hidden" id="input_tmp">
                                </div>
                            </div> --}}
                            @php $no_img=0 @endphp
                            @if (isset($data->photos))
                                @foreach($data->photos as $photo)
                                <div id="img{{ ++$no_img }}" class="col-sm-6 col-md-2" style="margin-bottom:1px;">
                                    <div class="thumbnail" style="height:{{ $is_desktop ? '150px' : '64px' }};display:table-cell;width:inherit;position:relative;">
                                        <img src="{{ _get_image('assets/images/upload/'.$photo->name) }}" style="max-height:100px;"><div id="zetth-process{{ $no_img }}" class="zetth-process">
                                        <img class="zetth-loading" src="{{ url('assets/images/loading.gif') }}"></div>
                                        <button class="btn btn-default btn-xs btn-xs-top-right" title="Edit Description" type="button" onclick="_edit2('{{ $no_img }}', '{{ $photo->name }}')" style="right:26px;"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-default btn-xs btn-xs-top-right" title="Remove Photo" type="button" onclick="_remove_photo('{{ $no_img }}', '{{ $photo->name }}')"><i class="fa fa-minus"></i></button>
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

@section('styles')
  {{-- {!! _admin_css('themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css') !!} --}}
  {!! _admin_css('themes/admin/AdminSC/plugins/fancybox/2.1.5/css/jquery.fancybox.css') !!}
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
  </style>
@endsection

@section('scripts')
  {{-- {!! _admin_js('themes/admin/AdminSC/plugins/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js') !!} --}}
  {!! _admin_js('themes/admin/AdminSC/plugins/fancybox/2.1.5/js/jquery.fancybox.js') !!}
  <script>
    var max_img = 100;
    var no_img = {{ $no_img }};
    var wFB = window.innerWidth - 30;
    var hFB = window.innerHeight - 60;

    function addPhotoModal() {
        if (no_img >= max_img){
            swal('Maksimal ' + max_img + ' foto');
        } else {
            $.fancybox({
                href : '{!! url('/larafile-standalone/dialog.php?type=1&field_id=input_tmp&lang=id&fldr=/images') !!}',
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
            var photo = '<div id="img'+no_img+'" class="col-sm-6 col-md-2" style="padding:10px;">'+
                            '<div class="thumbnail" style="height:150px;display:{{ $is_desktop ? 'table-cell' : 'block;padding:0;' }};width:inherit;margin-bottom:1px;">'+
                                '<img src="'+val+'" style="height:100px;">'+
                                '<input type="hidden" name="photo_file[]" value="'+val+'">'+
                                '<textarea name="photo_description[]" class="form-control" style="position:absoluste;bottom:0;left:0;height:{{ $is_desktop ? '55px' : '50px' }};" placeholder="Keterangan foto.."></textarea>'+
                                '<button class="btn btn-default btn-xs btn-xs-top-right" title="Remove Photo" type="button" onclick="_remove_preview(\'#img'+no_img+'\')" style="top:15px;right:15px;"><i class="fa fa-minus"></i></button>'+
                            '</div>'+
                        '</div>';
            $('#photo-box').append(photo);
        }
    }

    function _remove_preview(id) {
        no_img--;
        $(id).remove();
    }

    function _remove_photo(id, name) {
        if (!CONNECT) {
            return false;
        }

        swal({
            title: "Are you sure?",
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: '#d33',
            confirmButtonText: "Yes, delete it!"
        }).then(function(isConfirm){
            if (isConfirm) {
                $('#zetth-process'+id).show();
                $.ajax({
                    url: "{{ url('ajax/delete/photo/') }}",
                    data: {
                        filename: name
                    },
                    type: 'post'
                }).done(function(data) {
                    if (data.status) {
                        no_img--;
                        $("#img"+id).remove();
                        swal({
                            title: 'Sukses!',
                            text: "Foto berhasil dihapus",
                            type: "success",
                            confirmButtonColor: "coral"
                        });
                    } else {
                        swal({
                            title: 'Oops!',
                            text: "Gagal menghapus foto",
                            type: "error",
                            confirmButtonColor: "coral"
                        });
                    }

                    $('#zetth-process'+id).hide();
                });
            }
        });
    }
  </script>
@endsection