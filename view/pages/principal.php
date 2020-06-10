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
					if ( $_SESSION['nivel'] == 'ANALISTA' || $_SESSION['nivel'] == 'JEFE' ) {
						include 'view/pages/di/qd/content_header/header_list.php';
						include 'view/pages/di/qd/content_main/content_list.php';
					}else{
						include 'view/pages/di/qd/content_header/header_general.php';
						include 'view/pages/di/qd/content_main/content_general.php';
					}
					#Inclución de los modales
					include 'view/pages/di/qd/modals/modal_add_referencia.php';
					include 'view/pages/di/qd/modals/modal_asignar.php';
					break;
				case 'list_queja':#Listado de quejas
					include 'view/pages/di/qd/content_header/header_list.php';
					include 'view/pages/di/qd/content_main/content_list.php';
					#Inclución de modales
					include 'view/pages/di/qd/modals/modal_upload_file.php';
					include 'view/pages/di/qd/modals/modal_asignar.php';
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
				case 'turnar':
					include 'view/pages/di/qd/content_header/header_turnar.php';
					include 'view/pages/di/qd/content_main/content_turnar.php';
					break;
				case 'devoluciones':
					include 'view/pages/di/qd/content_header/header_devoluciones.php';
					include 'view/pages/di/qd/content_main/content_devoluciones.php';
					break;
				case 'tablero':
					include 'view/pages/di/directivo/content_header/header_general.php';
					include 'view/pages/di/qd/content_main/content_tablero.php';
					break;
				case 'migracion':
					include 'view/pages/di/qd/content_header/header_migracion.php';
					include 'view/pages/di/qd/content_main/content_migracion.php';
					break;
				case 'turnado_multi':
					include 'view/pages/di/qd/content_header/header_turnar.php';
					include 'view/pages/di/qd/content_main/content_turnado_multi.php';
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
		case 'SAPA':
			switch ($menu) {
				case 'general':
					if ( $_SESSION['nivel'] == 'SECRETARIA' || $_SESSION['nivel'] == 'SECRETARIA' ) {
						include 'view/pages/dr/hinojosa/content_header/header_acuse.php';
						include 'view/pages/dr/hinojosa/content_main/content_acuse.php';
					}else{
						include 'view/pages/dr/hinojosa/content_header/header_general.php';
						include 'view/pages/dr/hinojosa/content_main/content_general.php';
					}
					
					#Importacion de modales
					include 'view/pages/dr/hinojosa/modals/modal_add_turno.php';
					include 'view/pages/dr/hinojosa/modals/modal_add_seguimiento.php';
					#Modales nuevos 
					include 'view/pages/dr/hinojosa/modals/modal_add_responsable.php';
					include 'view/pages/dr/hinojosa/modals/modal_add_eprocesal.php';
					include 'view/pages/dr/hinojosa/modals/modal_add_culpable.php';
					include 'view/pages/dr/hinojosa/modals/modal_asignar.php';
					break;
				case 'e_procesal':
					include 'view/pages/dr/hinojosa/content_header/header_e_procesal.php';
					include 'view/pages/dr/hinojosa/content_main/content_e_procesal.php';
					break;
				case 'cedula':
					include 'view/pages/dr/hinojosa/content_header/header_cedula.php';
					include 'view/pages/dr/hinojosa/content_main/content_cedula.php';
					break;
				case 'e_e_procesal':
					include 'view/pages/dr/hinojosa/content_header/header_edit_e_procesal.php';
					include 'view/pages/dr/hinojosa/content_main/content_edit_e_procesal.php';
					break;
				case 'estadistica':
					include 'view/pages/dr/hinojosa/content_header/header_estadisticas.php';
					include 'view/pages/dr/hinojosa/content_main/content_estadisticas.php';
					break;
				case 'acuse':
					include 'view/pages/dr/hinojosa/content_header/header_acuse.php';
					include 'view/pages/dr/hinojosa/content_main/content_acuse.php';
					break;
				
				default:
					header("Location: ../../login.php");
					break;
			}
			break;
		case 'DR':

			switch ($menu) {
				case 'general':
					if ( $_SESSION['nivel'] == 'ANALISTA' ) {
						include 'view/pages/dr/directivo/content_header/header_analista.php';
						include 'view/pages/dr/directivo/content_main/content_analista.php';
					}else{
						include 'view/pages/dr/directivo/content_header/header_general.php';
						include 'view/pages/dr/directivo/content_main/content_general.php';
					}
					#Seccion de modales
					include 'view/pages/dr/directivo/modals/modal_add_acuse.php';
					include 'view/pages/dr/directivo/modals/modal_send_sapa.php';
					include 'view/pages/dr/directivo/modals/modal_turnar.php';
					break;
				case 'list_exp':
					include 'view/pages/dr/directivo/content_header/header_analista.php';
					include 'view/pages/dr/directivo/content_main/content_analista.php';
					#Seccion de modales
					include 'view/pages/dr/directivo/modals/modal_add_acuse.php';
					break;
				case 'cedula':
					include 'view/pages/dr/directivo/content_header/header_cedula.php';
					include 'view/pages/dr/directivo/content_main/content_cedula.php';
					#Seccion de modales
					include 'view/pages/dr/directivo/modals/modal_ejemplo.php';
					break;
			}
			break;
		case 'DI';
			switch ($menu) {
				case 'general':
					include 'view/pages/di/directivo/content_header/header_general.php';
					include 'view/pages/di/directivo/content_main/content_general.php';
					#Seccion de modales
					include 'view/pages/di/directivo/modals/modal_detalle_oin.php';
					include 'view/pages/di/directivo/modals/modal_detalle_actas.php';
					break;
				case 'cedula':
					include 'view/pages/di/directivo/content_header/header_cedula.php';
					include 'view/pages/di/directivo/content_main/content_cedula.php';
					#Seccion de modales
					include 'view/pages/di/directivo/modals/modal_ejemplo.php';
					break;
				case 'abogados':
					include 'view/pages/di/qd/content_header/header_abogados.php';
					include 'view/pages/di/qd/content_main/content_abogados.php';
					break;
				case 'migracion':
					include 'view/pages/di/qd/content_header/header_migracion.php';
					include 'view/pages/di/qd/content_main/content_migracion.php';
					break;
				case 'turnado_multi':
					include 'view/pages/di/qd/content_header/header_turnar.php';
					include 'view/pages/di/qd/content_main/content_turnado_multi.php';
					break;
				case 'expedientes':
					include 'view/pages/di/qd/content_header/header_exp_abogado.php';
					include 'view/pages/di/qd/content_main/content_exp_abogado.php';
					break;
			}
			break;
		case 'SC':#SUBDIRECCION DE LO CONTENCIOSO (REYES)
			switch ($menu) {
				case 'general':
					include 'view/pages/dr/reyes/content_header/header_general.php';
					include 'view/pages/dr/reyes/content_main/content_general.php';
					#Seccion de modales
					include 'view/pages/dr/reyes/modals/modal_add_responsable.php';
					break;
				case 'resolver':
					include 'view/pages/dr/reyes/content_header/header_resolucion.php';
					include 'view/pages/dr/reyes/content_main/content_resolucion.php';
					break;
				case 'demandar':
					include 'view/pages/dr/reyes/content_header/header_demanda.php';
					include 'view/pages/dr/reyes/content_main/content_demanda.php';
					break;
				case 'list_demandas':
					include 'view/pages/dr/reyes/content_header/header_ldemanda.php';
					include 'view/pages/dr/reyes/content_main/content_ldemanda.php';
					#Seccion de modales
					include 'view/pages/dr/reyes/modals/modal_resolver_demanda.php';
					include 'view/pages/dr/reyes/modals/modal_add_apersonamiento.php';
					break;
				case 'cedula':
					include 'view/pages/dr/reyes/content_header/header_cedula.php';
					include 'view/pages/dr/reyes/content_main/content_cedula.php';
					break;
				case 'improcedencia':
					include 'view/pages/dr/reyes/content_header/header_a_improcedencia.php';
					include 'view/pages/dr/reyes/content_main/content_a_improcedencia.php';
					break;
				case 'reserva':
					include 'view/pages/dr/reyes/content_header/header_reserva.php';
					include 'view/pages/dr/reyes/content_main/content_reserva.php';
					break;
				case 'list_reservas':
					include 'view/pages/dr/reyes/content_header/header_list_reserva.php';
					include 'view/pages/dr/reyes/content_main/content_list_reserva.php';
					#Seccion de modales
					include 'view/pages/dr/reyes/modals/modal_regresar_exp.php';
					include 'view/pages/dr/reyes/modals/modal_add_improcedencia.php';
					break;
				case 'reportes':
					include 'view/pages/dr/reyes/content_header/header_reportes.php';
					include 'view/pages/dr/reyes/content_main/content_reportes.php';
					break;
				case 'apersonamiento':
					include 'view/pages/dr/reyes/content_header/header_apersonamiento.php';
					include 'view/pages/dr/reyes/content_main/content_apersonamiento.php';
					break;	
			}
			break;
			case 'TITULAR':#TITULAR
			switch ($menu) {
				case 'general':
					include 'view/pages/uai/content_header/header_general.php';
					include 'view/pages/uai/content_main/content_general.php';
					break;
				case 'reportes':
					include 'view/pages/uai/content_header/header_reportes.php';
					include 'view/pages/uai/content_main/content_reportes.php';
					break;
				case 'cedula':
					include 'view/pages/uai/content_header/header_cedula.php';
					include 'view/pages/uai/content_main/content_cedula.php';
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