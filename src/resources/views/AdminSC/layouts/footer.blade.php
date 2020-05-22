  <div id="zetth-modal" class="modal" role="dialog">
    <div class="modal-dialog {{ !app('is_desktop') ? 'modal-sm' : '' }}">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="copyright">
    <span id="status-server" class="bg-success" data-toggle="tooltip" title="Status Koneksi">Terhubung</span> Didukung oleh <a href="https://porisweb.id" target="_blank">Porisweb</a>
  </div>
  <script>
    var SITE_URL = '{{ _url('/') }}';
    var ADMIN_URL = '{{ _url(adminPath() ?? '/') }}';
    var CURRENT_URL = '{{ _url($current_url) }}';
    var TOKEN = '{{ csrf_token() }}';
    var CONNECT = true;
    var IS_MOBILE = {{ app('is_mobile') ? 'true' : 'false' }};
  </script>
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/jquery/2.2.4/js/jquery.min.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/bootstrap/3.3.6/js/bootstrap.min.js') !!}
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/plugins/sweetalert2/js/sweetalert2.min.js') !!}
  @stack('scripts')
  {!! _admin_js(adminPath() . '/themes/admin/AdminSC/js/app.min.js') !!}
</body>

</html>