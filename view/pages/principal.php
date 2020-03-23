	<?php
#recuperar el perfil al que pertenece
$perfil = $_SESSION['perfil'];

if ( isset($_GET['menu']) ) {
	$menu = $_GET['menu'];

	switch ($perfil) {
		case 'QDP';
		case 'QDNP':
			switch ($menu) {
				case 'general':#formulario de alta de queja
					include 'view/pages/di/qd/content_header/header_general.php';
					include 'view/pages/di/qd/content_main/content_general.php';
					#Inclución de los modales
					include 'view/pages/di/qd/modals/modal_add_referencia.php';

					break;
				case 'list_queja':#Listado de quejas
					include 'view/pages/di/qd/content_header/header_list.php';
					include 'view/pages/di/qd/content_main/content_list.php';
					#Inclución de modales
					include 'view/pages/di/qd/modals/modal_upload_file.php';
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
				case 'seguimiento':
					include 'view/pages/di/qd/content_header/header_seguimiento.php';
					include 'view/pages/di/qd/content_main/content_seguimiento.php';
					#Inclución de los modales
					include 'view/pages/di/qd/modals/modal_add_presunto.php';
					include 'view/pages/di/qd/modals/modal_add_unidad.php';
					include 'view/pages/di/qd/modals/modal_add_quejoso.php';
					break;
				case 'cedula':
					include 'view/pages/di/qd/content_header/header_cedula.php';
					include 'view/pages/di/qd/content_main/content_cedula.php';
					break;
				case 'm_queja':
					include 'view/pages/di/qd/content_header/header_edit.php';
					include 'view/pages/di/qd/content_main/content_edit.php';
					break;
				case 'abogados':
					include 'view/pages/di/qd/content_header/header_abogados.php';
					include 'view/pages/di/qd/content_main/content_abogados.php';
					break;
				case 'expedientes':
					include 'view/pages/di/qd/content_header/header_exp_abogado.php';
					include 'view/pages/di/qd/content_main/content_exp_abogado.php';
					break;
				default:
					header("Location: ../../login.php");
					break;
			}
			break;
		case 'SIRA':
			switch ($menu) {
				case 'general':
					include 'view/pages/di/sira/content_header/header_general.php';
					include 'view/pages/di/sira/content_main/content_general.php';
					break;
				case 'list_acta':#Listado de actas
					include 'view/pages/di/sira/content_header/header_list.php';
					include 'view/pages/di/sira/content_main/content_list.php';
					include 'view/pages/di/sira/modals/modal_upload_file.php';#Agrega un archivo al acta
					break;
				case 'ordenes':#opcion de ordenes de inspeccion
					include 'view/pages/di/sira/content_header/header_ordenes.php';
					include 'view/pages/di/sira/content_main/content_ordenes.php';
					include 'view/pages/di/sira/modals/modal_ot_upload.php';
					include 'view/pages/di/sira/modals/modal_add_observaciones.php';
					break;
				case 'detalle':#opcion del detalle de una orden de trabajo
					include 'view/pages/di/sira/content_header/header_detail_ot.php';
					include 'view/pages/di/sira/content_main/content_detail_ot.php';
					include 'view/pages/di/sira/modals/modal_acta.php';
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
					include 'view/pages/di/sira/modals/modal_add_vehiculo.php';#Agrega un vehiculo 
					include 'view/pages/di/sira/modals/modal_add_animal.php';#Agrega un animal
					include 'view/pages/di/sira/modals/modal_add_arma.php';#Agrega un arma
					include 'view/pages/di/sira/modals/modal_add_archivo.php';#Agrega un archivo
					include 'view/pages/di/sira/modals/modal_add_turno.php';#Agrega un turno
					break;
				case 'cedula':
					include 'view/pages/di/sira/content_header/header_cedula.php';
					include 'view/pages/di/sira/content_main/content_cedula.php';
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