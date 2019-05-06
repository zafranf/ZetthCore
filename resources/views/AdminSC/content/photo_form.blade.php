@extends('admin.layout')

@section('styles')
{!! _load_jasny('css') !!}
{!! _load_select2('css') !!}
{!! _load_sweetalert('css') !!}
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

@section('content')
    <div class="panel-body">
        <form class="form-horizontal" action="{{ url($current_url) }}{{ isset($albums)?'/'.$albums->album_id:'' }}" method="post" enctype="multipart/form-data">
            {{ isset($albums)?method_field('PUT'):'' }}
            {{ csrf_field() }}
            <div class="form-group" style="margin-top:20px;">
                <label for="photo_title" class="col-sm-2 control-label">Album Name</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control autofocus" id="album_name" name="album_name" value="{{ isset($albums->album_id)?$albums->album_name:'' }}" maxlength="100" placeholder="Album Name">
                </div>
            </div>
            <div class="form-group">
                <label for="album_title" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-4">
                    <textarea class="form-control" name="album_description" placeholder="Description">{{ isset($albums->album_id)?$albums->album_description:'' }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="album_status" {{ (isset($album->album_status) && $album->album_status==0)?'':'checked' }}> Active
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    {{ _get_button_post() }}
                </div>
            </div>
            <hr>
            
            <div class="row">
                <div class="col-sm-12 col-md-12" style="max-height: 500px;overflow: auto;" id="photo-box">
                    <div class="col-sm-6 col-md-2 col-xs-3 no-padding" style="margin-bottom:1px;cursor:pointer;" onclick="addPhotoModal()">
                        <div class="thumbnail text-warning" style="height:{{ $isDesktop ? '150px' : '64px' }};display:table-cell;vertical-align:middle;text-align:center;width:inherit">
                            <i class="fa fa-plus" style="font-size: {{ $isDesktop ? '80px' : '25px' }};"></i>
                            <br>
                            <span class="" style="font-size: {{ $isDesktop ? '32px' : '11px' }};">Add Photo</span>
                        </div>
                    </div>
                    @php $no_img=0 @endphp
                    @if (isset($albums->photos))
                        @foreach($albums->photos as $photo)
                        <div id="img{{ ++$no_img }}" class="col-sm-6 col-md-2 col-xs-3 no-padding" style="margin-bottom:1px;">
                            <div class="thumbnail" style="height:{{ $isDesktop ? '150px' : '64px' }};display:table-cell;vertical-align:middle;width:inherit;position:relative;">
                                <img src="{{ _get_image_temp('assets/images/upload/'.$photo->photo_name, ['auto',140]) }}" style="max-height:140px;"><div id="zetth-process{{ $no_img }}" class="zetth-process">
                                <img class="zetth-loading" src="{{ url('assets/images/loading.gif') }}"></div>
                                <button class="btn btn-default btn-xs btn-xs-top-right" title="Edit Description" type="button" onclick="_edit2('{{ $no_img }}', '{{ $photo->photo_name }}')" style="right:26px;"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-default btn-xs btn-xs-top-right" title="Remove Photo" type="button" onclick="_remove2('{{ $no_img }}', '{{ $photo->photo_name }}')"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
{!! _load_jasny('js') !!}
{!! _load_select2('js') !!}
{!! _load_sweetalert('js') !!}
<script>
var max_img = 30;
var no_img = {{ $no_img }};
$(function(){
    $(".select2").select2({
        placeholder: "[None]"
    });
});

function addPhotoModal() {
    if (no_img>=max_img){
        return alert('Max upload photo is '+max_img);
    }
    var html = 'Photo:<br>'+
                '<div class="fileinput fileinput-new" data-provides="fileinput">'+
                    '<div class="fileinput-new thumbnail">'+
                        '<img src="{{ url('assets/images/no-image2.png') }}">'+
                    '</div>'+
                    '<div class="fileinput-preview fileinput-exists thumbnail"></div>'+
                    '<div id="photo-file">'+
                        '<span class="btn btn-default btn-file">'+
                            '<span class="fileinput-new">Choose</span>'+
                            '<span class="fileinput-exists">Change</span>'+
                            '<input name="photo_name[]" id="photo_name'+no_img+'" type="file" accept="image/*">'+
                        '</span>'+
                        '<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>'+
                    '</div>'+
                '</div>';
    html += 'Description:<br><textarea name="photo_description[]" id="photo_description" class="form-control" placeholder="Photo Description"></textarea>';
    $('#zetth-modal').modal('show');
    $('.modal-title').text('Add Photo');
    $('.modal-body').html(html);
    $('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button><button type="button" class="btn btn-warning" onclick="addPhoto()">Add</button>');
}

function addPhoto() {
    var img = $('.fileinput-preview img').attr('src');
    var img_val = $('#photo_name').val();
    var img_file = $("#photo-file input")[1];
    var img_desc = $('#photo_description').val();
    if (img_val!="" && typeof img!="undefined"){
        no_img++;
        var photo = '<div id="img'+no_img+'" class="col-sm-6 col-md-2 col-xs-3 no-padding" style="margin-bottom:1px;">'+
                        '<div class="thumbnail" style="height:{{ $isDesktop ? '150px' : 'inherit' }};display:table-cell;vertical-align:middle;width:inherit">'+
                            '<img src="'+img+'" style="max-height:140px;"><div id="photo-box-file'+no_img+'" style="display:none;">'+img_file+'<textarea name="photo_description[]">'+img_desc+'</textarea></div>'+
                            '<button class="btn btn-default btn-xs btn-xs-top-right" title="Edit Description" type="button" onclick="_edit(\'#img'+no_img+'\')" style="right:26px;"><i class="fa fa-edit"></i></button>'+
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