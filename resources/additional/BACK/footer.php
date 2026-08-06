    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <footer class="main-footer">
    <strong>Copyright &copy; <?= date('Y'); ?> <a href="<?= $helper->url(); ?>"><?= htmlspecialchars($helper->getDisplayName()); ?></a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 8.2.0 | <a href="<?= $helper->url(); ?>impressum" >Impressum<a> | <a href="<?= $helper->url(); ?>datenschutz" >Datenschutz<a> | <a href="<?= $helper->url(); ?>agb" >AGB'S<a>
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/moment/moment.min.js"></script>
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= $helper->cdnUrl(); ?>/BACKEND/dist/js/demo.js"></script>
</body>
</html>