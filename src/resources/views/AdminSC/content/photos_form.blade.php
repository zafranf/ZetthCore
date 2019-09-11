@extends('zetthcore::AdminSC.layouts.main')

@section('content')
    <div class="panel-body">
        <form class="form-horizontal" action="{{ url($current_url) }}{{ isset($data) ? '/' . $data->id : '' }}" method="post" enctype="multipart/form-data">
            <div class="form-group" style="margin-top:20px;">
                <label for="photo_title" class="col-sm-2 control-label">Nama Album</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control autofocus" id="name" name="name" value="{{ isset($data->id) ? $data->name : '' }}" maxlength="100" placeholder="Nama album..">
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-4">
                    <textarea class="form-control" name="description" placeholder="Penjelasan singkat tentang album..">{{ isset($data->id) ? $data->description : '' }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="status" {{ (isset($album->status) && $album->status == 0) ? '' : 'checked' }}> Aktif
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-4">
                {{ isset($data->id) ? method_field('PUT') : '' }}
                {{ csrf_field() }}
                {{ _get_button_post($current_url, true, $data->id ?? '') }}
              </div>
            </div>
            <hr>
            
            <div class="row">
                <div class="col-sm-12 col-md-12" style="max-height: 720px;overflow: auto;" id="photo-box">
                    <div class="col-sm-6 col-md-3 no-padding" style="margin-bottom:1px;cursor:pointer;" onclick="addPhotoModal()">
                        <div class="thumbnail text-warning" style="height:{{ $is_desktop ? '350px' : '64px' }};display:table-cell;vertical-align:middle;text-align:center;width:inherit">
                            <i class="fa fa-plus" style="font-size: {{ $is_desktop ? '80px' : '25px' }};"></i>
                            <br>
                            <span class="" style="font-size: {{ $is_desktop ? '32px' : '11px' }};">Tambah Foto</span>
                            <input type="hidden" id="input_tmp">
                        </div>
                    </div>
                    @php $no_img=0 @endphp
                    @if (isset($data->photos))
                      @foreach($data->photos as $photo)
                        <div id="img{{ ++$no_img }}" class="col-sm-6 col-md-2 no-padding" style="margin-bottom:1px;">
                            <div class="thumbnail" style="height:{{ $is_desktop ? '350px' : '64px' }};display:table-cell;width:inherit;position:relative;">
                                <img src="{{ _get_image('assets/images/upload/'.$photo->name) }}" style="max-height:170px;"><div id="zetth-process{{ $no_img }}" class="zetth-process">
                                <img class="zetth-loading" src="{{ url('assets/images/loading.gif') }}"></div>
                                <button class="btn btn-default btn-xs btn-xs-top-right" title="Edit Description" type="button" onclick="_edit2('{{ $no_img }}', '{{ $photo->name }}')" style="right:26px;"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-default btn-xs btn-xs-top-right" title="Remove Photo" type="button" onclick="_remove2('{{ $no_img }}', '{{ $photo->name }}')"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                      @endforeach
                    @endif
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
    var max_img = 30;
    var no_img = {{ $no_img }};
    var wFB = window.innerWidth - 30;
    var hFB = window.innerHeight - 60;
    $(function(){
        /* $(".select2").select2({
            placeholder: "[None]"
        }); */
    });

    function addPhotoModal() {
        if (no_img>=max_img){
            return alert('Max upload photo is '+max_img);
        }
        $('#photo-box').fancybox({
          href : '{!! url('/larafile-standalone/dialog.php?type=1&field_id=input_tmp&lang=id&fldr=/images') !!}',
          type : 'iframe',
          autoScale : false,
          autoSize : true,
          beforeLoad : function() {
            this.width  = wFB;
            this.height = hFB;
          }/*,
          afterClose : function(){
            alert('from iframe btn');
          }*/
        });
        /* var html = 'Photo:<br>'+
                    '<div class="fileinput fileinput-new" data-provides="fileinput">'+
                        '<div class="fileinput-new thumbnail">'+
                            '<img src="{{ url('themes/admin/AdminSC/images/no-image.png') }}">'+
                        '</div>'+
                        '<div class="fileinput-preview fileinput-exists thumbnail"></div>'+
                        '<div id="photo-file">'+
                            '<span class="btn btn-default btn-file">'+
                                '<span class="fileinput-new">Pilih</span>'+
                                '<span class="fileinput-exists">Ganti</span>'+
                                '<input name="photo_name[]" id="photo_name'+no_img+'" type="file" accept="image/*">'+
                            '</span>'+
                            '<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Batal</a>'+
                        '</div>'+
                    '</div>';
        html += 'Description:<br><textarea name="photo_description[]" id="photo_description" class="form-control" placeholder="Photo Description"></textarea>';
        $('#zetth-modal').modal('show');
        $('.modal-title').text('Add Photo');
        $('.modal-body').html(html);
        $('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button><button type="button" class="btn btn-warning" onclick="addPhoto()">Add</button>'); */
    }

    function responsive_filemanager_callback(field_id){
        var vals = $('#input_tmp').val();
        console.log('ini vals', vals);
        console.log('tipe', typeof vals);
        try {
            vals = JSON.parse(vals);
        } catch (e) {
            vals = JSON.parse('["'+vals+'"]')
        }
        console.log('ini vals', vals);
        console.log('tipe', typeof vals);
        console.log('ini vals length', vals.length);
        $.each(vals, function(i,val) {
            var path = val.replace(SITE_URL, "");
            console.log('ini index',i)
            console.log('ini value',val)
            console.log('ini path',path)
        })
    }

    function addPhoto() {
        var img = $('.fileinput-preview img').attr('src');
        var img_val = $('#photo_name').val();
        var img_file = $("#photo-file input")[1];
        var img_desc = $('#photo_description').val();
        $('#photo_name'+no_img).hide();
        if (img_val!="" && typeof img!="undefined"){
            no_img++;
            var photo = '<div id="img'+no_img+'" class="col-sm-6 col-md-3 no-padding" style="margin-bottom:1px;">'+
                            '<div class="thumbnail" style="height:{{ $is_desktop ? '350px' : 'inherit' }};display:table-cell;width:inherit">'+
                                '<img src="'+img+'" style="max-height:270px;"><div id="photo-box-file'+no_img+'"><div style="display:none;">'+img_file+'</div><textarea name="photo_description[]" class="form-control" style="position:absolute;bottom:0;left:0;height:80px;" placeholder="Keterangan foto..">'+img_desc+'</textarea></div>'+
                                // '<button class="btn btn-default btn-xs btn-xs-top-right" title="Edit Description" type="button" onclick="_edit2(\'#img'+no_img+'\')" style="right:26px;"><i class="fa fa-edit"></i></button>'+
                                '<button class="btn btn-default btn-xs btn-xs-top-right" title="Remove Photo" type="button" onclick="_remove(\'#img'+no_img+'\')"><i class="fa fa-minus"></i></button>'+
                            '</div>'+
                        '</div>';
            $('#photo-box').append(photo);
            $('#photo-box-file'+no_img).append(img_file);
        }
        $('#zetth-modal').modal('hide');
        $('.modal-body').html('');
    }

    function _remove2(id, name) {
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
                        $("#img"+id).remove();
                        swal({
                            title: 'Success!',
                            text: "Photo has been deleted",
                            type: "success",
                            confirmButtonColor: "coral"
                        });
                    } else {
                        swal({
                            title: 'Oops!',
                            text: "Failed to remove photo",
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