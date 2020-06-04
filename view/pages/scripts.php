<!-- jQuery 3 -->
<script src="view/bower_components/jquery/dist/jquery.min.js"></script>

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
<!-- iCheck 1.0.1 -->
<script src="view/plugins/iCheck/icheck.min.js"></script>
<!-- AdminLTE App -->
<script src="view/dist/js/adminlte.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="view/dist/js/demo.js"></script>
<!-- Select2 -->
<script src="view/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="view/plugins/input-mask/jquery.inputmask.js"></script>
<script src="view/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="view/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="view/dist/js/jquery.anexgrid.js"></script>
<!-- DATATABLES -->
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
<!-- Scripts para creacion de botones de exportacion DataTables -->
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js" type="text/javascript" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript" ></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js " type="text/javascript" ></script>
<?php
if ( $_SESSION['perfil'] == 'QDP' || $_SESSION['perfil'] == 'QDNP' ) {
	echo '<script src="view/dist/js/main.qd.js"></script>';
}elseif ( $_SESSION['perfil'] == 'SIRA' ) {
	echo '<script src="view/dist/js/main.sira.js"></script>';
}elseif ( $_SESSION['perfil'] == 'SAPA' ) {
	echo '<script src="view/dist/js/main.sapa.js"></script>';
}elseif ( $_SESSION['perfil'] == 'SC' ) {
	echo '<script src="view/dist/js/main.sc.js"></script>';
}elseif ( $_SESSION['perfil'] == 'TITULAR' ) {
	echo '<script src="view/dist/js/main.uai.js"></script>';
}elseif ( $_SESSION['perfil'] == 'DR' ) {
	echo '<script src="view/dist/js/main.dr.js"></script>';
}elseif ( $_SESSION['perfil'] == 'DI' ) {
	echo '<script src="view/dist/js/main.di.js"></script>';
}elseif ( $_SESSION['perfil'] == 'sys' ) {
	echo '<script src="view/dist/js/main.sys.js"></script>';
}

?>
</body>
</html>