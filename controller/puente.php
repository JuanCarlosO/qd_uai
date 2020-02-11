<?php 
include 'Security.php';
spl_autoload_register(function ($class) {
    include $class.'.php';
});
/*DECLARACIÓN DE LAS CLASES*/
$u = new UserController();
/***/
if ( isset($_POST['option']) ) {
	$o = $_POST['option'];
	switch ( $o ) {
		case '1':
			session_start();
			$_SESSION['name'] = "JUAN CARLOS OVANDO";
			#$_SESSION['perfil'] = "investigacion";
			$_SESSION['perfil'] = "sira";
			$_SESSION['id'] = "1";
			if ( isset($_SESSION) ) {
				header('Location: ../index.php?menu=general');
			}
			break;
		
		default:
			# code...
			break;
	}
}elseif ( isset($_GET['option']) ) {
	$o = $_GET['option'];	
	switch ( $o ) {
		case '1':#Recuperar las ordenes de trabajo
			echo '{"data":{"id":"1","fecha":"hoy","participantes":"yo","estado":"chido","clave":"Mi_clave"},"total":"1"}';exit;
			break;
		case '2':#Recuperar las actas
			echo '{"data":{"id":"1","t_acta":"hoy","no_acta":"yo","procedencia":"Unidad de Asuntos","fecha":"chido","municipio":"Mi_clave","descripcion":"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus doloremque rem voluptates tenetur voluptate, veniam illo vel recusandae nam praesentium sint asperiores soluta quia nisi laboriosam ex explicabo itaque eligendi."},"total":"1"}';exit;
			break;
		
		default:
			# code...
			break;
	}
}

?>