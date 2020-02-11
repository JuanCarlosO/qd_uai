<?php
#recuperar el perfil al que pertenece
$perfil = $_SESSION['perfil'];

if ( isset($_GET['menu']) ) {
	$menu = $_GET['menu'];

	switch ($perfil) {
		case 'investigacion':
			switch ($menu) {
				case 'general':#formulario de alta de queja
					include 'view/pages/di/qd/content_header/header_general.php';
					include 'view/pages/di/qd/content_main/content_general.php';
					break;
				case 'list_queja':#Listado de quejas
					include 'view/pages/di/qd/content_header/header_list.php';
					include 'view/pages/di/qd/content_main/content_list.php';
					break;
				case 'reports':#opcion de reportes
					include 'view/pages/di/qd/content_header/header_reports.php';
					include 'view/pages/di/qd/content_main/content_reports.php';
					break;
				case 'aviso':#opcion de reportes
					include 'view/pages/di/qd/content_header/header_aviso.php';
					include 'view/pages/di/qd/content_main/content_aviso.php';
					break;
				case 'manual':#opcion de reportes
					include 'view/pages/di/qd/content_header/header_manual.php';
					include 'view/pages/di/qd/content_main/content_manual.php';
					break;
				
				default:
					header("Location: ../../login.php");
					break;
			}
			break;
		case 'sira':
			switch ($menu) {
				case 'general':
					include 'view/pages/di/sira/content_header/header_general.php';
					include 'view/pages/di/sira/content_main/content_general.php';
					break;
				case 'list_acta':#Listado de actas
					include 'view/pages/di/sira/content_header/header_list.php';
					include 'view/pages/di/sira/content_main/content_list.php';
					break;
				case 'ordenes':#opcion de ordenes de inspeccion
					include 'view/pages/di/sira/content_header/header_ordenes.php';
					include 'view/pages/di/sira/content_main/content_ordenes.php';
					break;
				case 'reports':#opcion de reportes
					include 'view/pages/di/sira/content_header/header_reports.php';
					include 'view/pages/di/sira/content_main/content_reports.php';
					break;
				case 'aviso':#opcion de aviso de privacidad
					include 'view/pages/di/sira/content_header/header_aviso.php';
					include 'view/pages/di/sira/content_main/content_aviso.php';
					break;
				case 'manual':#opcion de manual
					include 'view/pages/di/sira/content_header/header_manual.php';
					include 'view/pages/di/sira/content_main/content_manual.php';
					break;
				#RUTAS DE OPCIONES DEL LISTADO DE ACTAS
				case 'seguimiento':
					include 'view/pages/di/sira/content_header/header_seguimiento.php';
					include 'view/pages/di/sira/content_main/content_seguimiento.php';
					include 'view/pages/di/sira/modals/modal_add_pr.php';#Agrega presunto responsable
					include 'view/pages/di/sira/modals/modal_add_quejoso.php';#Agrega un quejoso
					break;
				case 'cedula':
					break;
				default:
					header("Location: ../../login.php");
					break;
			}
			break;
		case 'respo':
			switch ($menu) {
				case 'general':
					# code...
					break;
				
				default:
					header("Location: ../../login.php");
					break;
			}
			break;
		case 'sys':#perfil de sistemas
			switch ($menu) {
				case 'general':
					# code...
					break;
				
				default:
					header("Location: ../../login.php");
					break;
			}
			break;
		default:
			# code...
			break;
	}
}
?>