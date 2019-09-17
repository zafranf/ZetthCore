  
  <div class="copyright">
    <span id="status-server" class="bg-success" data-toggle="tooltip" title="Status Koneksi">Terhubung</span> Dipersembahkan oleh <a href="https://porisweb.id" target="_blank">Porisweb</a>
  </div>
  <script>
    var SITE_URL = '{{ url('/') }}';
    var ADMIN_URL = '{{ url($adminPath) }}';
    var CURRENT_URL = '{{ url($current_url) }}';
    var TOKEN = '{{ csrf_token() }}';
    var CONNECT = true;
    var IS_MOBILE = {{ $is_mobile ? 'true' : 'false' }};
  </script>
  {!! _admin_js('themes/admin/AdminSC/plugins/jquery/2.2.4/js/jquery.min.js') !!}
  {!! _admin_js('themes/admin/AdminSC/plugins/bootstrap/3.3.6/js/bootstrap.min.js') !!}
  {!! _admin_js('themes/admin/AdminSC/plugins/sweetalert2/js/sweetalert2.min.js') !!}
  @yield('scripts')
  {!! _admin_js('themes/admin/AdminSC/js/app.js') !!}
</body>
</html>