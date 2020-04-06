@push('styles')
  <style>
    .mce-fullscreen {
      z-index: 9999 !important;
    }

    #mceu_15 {
      position: absolute;
      right: 4px;
      top: 4px;
    }
  </style>
@endpush

@push('scripts')
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/tinymce/4.3.2/tinymce.min.js') !!}
  <script>
    var lsH,tmH = 0;
    $(document).ready(function(){
      tinymce.init({
        relative_urls: false,
        selector: '{{ $selector ?? '#content' }}',
        /*codesample_dialog_height: 300,*/
        height: (lsH-190),
        skin: 'custom',
        language: 'id',
        plugins: [
          "advlist autolink link image lists charmap print preview hr anchor pagebreak",
          "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
          "table {{ app('is_desktop') ? 'contextmenu ' : '' }}directionality emoticons paste textcolor code codesample",
          "placeholder youtube fullscreen"
        ],
        toolbar: "undo redo | bullist numlist blockquote | link unlink | youtube image table | styleselect fontselect | fontsizeselect codesample code fullscreen",
        image_advtab: true,
        image_caption: true,
        menubar: false,
        external_filemanager_path:"{{ asset(adminPath() . '/larafile/') }}/",
        filemanager_title:"Filemanager",
        filemanager_folder: '/',
        filemanager_language: 'id',
        external_plugins: { "filemanager" : "{{ asset(adminPath() . '/larafile/plugin.min.js') }}" },
        setup : function(ed) {
          ed.on('init', function() 
          {
            /* this.getDoc().body.style.fontSize = '12px'; */
            this.getDoc().body.style.fontFamily = 'arial, helvetica, sans-serif';
            /* this.getDoc().body.style.fontWeight = '300'; */
          });
        }
      });
    });
  </script>
@endpush