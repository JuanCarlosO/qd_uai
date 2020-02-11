<!-- jQuery 3 -->
<script src="view/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="view/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="view/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="view/bower_components/raphael/raphael.min.js"></script>
<script src="view/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="view/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="view/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="view/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="view/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="view/bower_components/moment/min/moment.min.js"></script>
<script src="view/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="view/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="view/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="view/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="view/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="view/dist/js/adminlte.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="view/dist/js/demo.js"></script>
<!-- Select2 -->
<script src="view/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="view/dist/js/jquery.anexgrid.js"></script>
<?php
if ( $_SESSION['perfil'] == 'Investigación' ) {
	echo '<script src="view/dist/js/main.qd.js"></script>';
}elseif ( $_SESSION['perfil'] == 'sira' ) {
	echo '<script src="view/dist/js/main.sira.js"></script>';
}elseif ( $_SESSION['perfil'] == 'dr' ) {
	echo '<script src="view/dist/js/main.dr.js"></script>';
}elseif ( $_SESSION['perfil'] == 'sys' ) {
	echo '<script src="view/dist/js/main.sys.js"></script>';
}

?>
</body>
</html>