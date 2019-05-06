
      <footer class="footer">
        <div class="container">
          <div class="row align-items-center flex-row-reverse">
            <div class="col-auto ml-lg-auto">
              <div class="row align-items-center">
              </div>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
              Copyright &copy; 2018 <a href=".">Tabler</a>. Theme by <a href="https://codecalm.net" target="_blank">codecalm.net</a> All rights reserved.
            </div>
          </div>
        </div>
      </footer>
    </div>
    <script>
      var TOKEN = '{{ csrf_token() }}';
      var SITE_URL = '{{ url('/') }}';
    </script>
    {!! _load_js('/admin/js/vendors/jquery-3.2.1.min.js') !!}
    {!! _load_js('/admin/js/vendors/bootstrap.bundle.min.js') !!}
    {!! _load_js('/admin/js/vendors/sweetalert2.min.js') !!}
    {!! _load_js('/admin/js/app.js') !!}
    {!! _load_js('/admin/js/navbar.js') !!}
    @yield('js')
  </body>
</html>