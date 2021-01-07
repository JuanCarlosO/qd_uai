<?php
#recuperar el perfil al que pertenece
$perfil = $_SESSION['perfil'];

if ( isset($_GET['menu']) ) {
	$menu = $_GET['menu'];

	switch ($perfil) {
		
		case 'QDP';
		case 'ESPECIAL':
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
					include 'view/pages/di/qd/modals/modal_add_cargo.php';
					include 'view/pages/di/qd/modals/modal_add_opinion.php';
					break;
				case 'list_queja':#Listado de quejas
					include 'view/pages/di/qd/content_header/header_list.php';
					include 'view/pages/di/qd/content_main/content_list.php';
					#Inclución de modales
					include 'view/pages/di/qd/modals/modal_upload_file.php';
					include 'view/pages/di/qd/modals/modal_asignar.php';
					include 'view/pages/di/qd/modals/modal_add_opinion.php';
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
				case 'cedula':
					include 'view/pages/di/qd/content_header/header_cedula.php';
					include 'view/pages/di/qd/content_main/content_cedula.php';
					break;
				case 'm_queja':
					include 'view/pages/di/qd/content_header/header_edit.php';
					include 'view/pages/di/qd/content_main/content_edit.php';
					#INCLUCIÓN DE MODALES 
					include 'view/pages/di/qd/modals/modal_add_quejoso.php';
					include 'view/pages/di/qd/modals/modal_aviso.php';
					break;
				case 'abogados':
					include 'view/pages/di/qd/content_header/header_abogados.php';
					include 'view/pages/di/qd/content_main/content_abogados.php';
					break;
				case 'expedientes':
					include 'view/pages/di/qd/content_header/header_exp_abogado.php';
					include 'view/pages/di/qd/content_main/content_exp_abogado.php';
					break;
				case 'enviar':
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
				#Cedula general del sistema Viejo
				case 'cedula_old':
					include 'view/pages/uai/content_header/header_cedula_old.php';
					include 'view/pages/uai/content_main/content_cedula_old.php';
					break;
				default:
					header("Location: ../../login.php");
					break;
			}
			break;
		case 'SIRA':
			switch ($menu) {
				/*case 'general':
					include 'view/pages/di/sira/content_header/header_general.php';
					include 'view/pages/di/sira/content_main/content_general.php';
					break;*/
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
					include 'view/pages/di/sira/modals/modal_cancelar_ot.php';
					include 'view/pages/di/sira/modals/modal_obs_upload.php';
					break;
				case 'detalle':#opcion del detalle de una orden de trabajo
					include 'view/pages/di/sira/content_header/header_detail_ot.php';
					include 'view/pages/di/sira/content_main/content_detail_ot.php';
					include 'view/pages/di/sira/modals/modal_acta.php';
					break;
				case 'verdetalle':#opcion del detalle de una orden de trabajo
					include 'view/pages/di/sira/content_header/header_detail_censo.php';
					include 'view/pages/di/sira/content_main/content_detail_censo.php';
					break;
				case 'reports':#opcion de reportes
					include 'view/pages/di/sira/content_header/header_reports_ot.php';
					include 'view/pages/di/sira/content_main/content_reports.php';
					break;
				case 'reports_ot':#opcion de reportes
					include 'view/pages/di/sira/content_header/header_reports.php';
					include 'view/pages/di/sira/content_main/content_reports_ot.php';
					break;
				case 'tablero':
					include 'view/pages/di/directivo/content_header/header_general.php';
					include 'view/pages/di/qd/content_main/content_tablero.php';
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
				case 'seguimiento_acciones':
					include 'view/pages/di/sira/content_header/header_seguimiento.php';
					include 'view/pages/di/sira/content_main/content_seguimiento_acciones.php';
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
				case 'cedula_acciones':
					include 'view/pages/di/sira/content_header/header_cedula_acciones.php';
					include 'view/pages/di/sira/content_main/content_cedula_acciones.php';
					break;
				case 'acciones':
					include 'view/pages/di/sira/content_header/header_acciones.php';
					include 'view/pages/di/sira/content_main/content_acciones.php';
					break;
				#RUTAS DE OPCIONES DE CENSOS
				case 'censo':
					include 'view/pages/di/sira/content_header/header_censo.php';
					include 'view/pages/di/sira/content_main/content_censo.php';
					break;
				case 'general':
					include 'view/pages/di/sira/content_header/header_list_censo.php';
					include 'view/pages/di/sira/content_main/content_list_censo.php';
					break;
				case 'recordatorio':
					include 'view/pages/di/sira/content_header/header_recordatorio.php';
					include 'view/pages/di/sira/content_main/content_recordatorio.php';
					break;
				case 'respuestas':
					include 'view/pages/di/sira/content_header/header_respuestas.php';
					include 'view/pages/di/sira/content_main/content_respuestas.php';
					break;
				case 'list_preguntas':
					include 'view/pages/di/sira/content_header/header_list_preguntas.php';
					include 'view/pages/di/sira/content_main/content_list_preguntas.php';
					include 'view/pages/di/sira/modals/modal_add_respuesta.php';
					include 'view/pages/di/sira/modals/modal_add_res_comisionados.php';
					include 'view/pages/di/sira/modals/modal_add_res_turnos.php';
					include 'view/pages/di/sira/modals/modal_add_res_armas.php';
					include 'view/pages/di/sira/modals/modal_add_res_gas.php';
					include 'view/pages/di/sira/modals/modal_add_res_vehiculo.php';
					break;
				case 'recomendaciones':
					include 'view/pages/di/sira/content_header/header_recomendaciones.php';
					include 'view/pages/di/sira/content_main/content_recomendaciones.php';
					break;
				case 'recomendaciones_edit':
					include 'view/pages/di/sira/content_header/header_recomendaciones_edit.php';
					include 'view/pages/di/sira/content_main/content_recomendaciones_edit.php';
					break;
				case 'recordatorio_obs':
					include 'view/pages/di/sira/content_header/header_recordatorio_obs.php';
					include 'view/pages/di/sira/content_main/content_recordatorio_obs.php';
					break;
				case 'seguimiento_irr':
					include 'view/pages/di/sira/content_header/header_respuestas_obs.php';
					include 'view/pages/di/sira/content_main/content_respuestas_obs.php';
					break;
				case 'list_recomendaciones':
					include 'view/pages/di/sira/content_header/header_list_recomendaciones.php';
					include 'view/pages/di/sira/content_main/content_list_recomendaciones.php';
					include 'view/pages/di/sira/modals/modal_recomendacion.php';
					include 'view/pages/di/sira/modals/modal_estatus.php';
					break;
				case 'estadistica_censo':
					include 'view/pages/di/sira/content_header/header_estadistica_censo.php';
					include 'view/pages/di/sira/content_main/content_estadistica_censo.php';
					break;
				case 'estadistica_actuaciones':
					include 'view/pages/di/sira/content_header/header_estadistica_actuaciones.php';
					include 'view/pages/di/sira/content_main/content_estadistica_actuaciones.php';
					break;
				case 'estadistica_ordenes':
					include 'view/pages/di/sira/content_header/header_estadistica_ordenes.php';
					include 'view/pages/di/sira/content_main/content_estadistica_ordenes.php';
					break;
				case 'resumen':
					include 'view/pages/di/sira/content_header/header_list_resumen.php';
					include 'view/pages/di/sira/content_main/content_list_resumen.php';
					include 'view/pages/di/sira/modals/modal_resumen_respuesta.php';
					include 'view/pages/di/sira/modals/modal_resumen_comisionados.php';
					include 'view/pages/di/sira/modals/modal_add_respuesta.php';
					include 'view/pages/di/sira/modals/modal_add_res_comisionados.php';
					include 'view/pages/di/sira/modals/modal_add_res_turnos.php';
					include 'view/pages/di/sira/modals/modal_add_res_armas.php';
					include 'view/pages/di/sira/modals/modal_add_res_gas.php';
					include 'view/pages/di/sira/modals/modal_add_res_vehiculo.php';
					/*include 'view/pages/di/sira/modals/modal_add_res_turnos.php';
					include 'view/pages/di/sira/modals/modal_add_res_armas.php';
					include 'view/pages/di/sira/modals/modal_add_res_gas.php';
					include 'view/pages/di/sira/modals/modal_add_res_vehiculo.php';*/
					break;
				case 'acta_admin':
					include 'view/pages/di/sira/content_header/header_add_acta_admin.php';
					include 'view/pages/di/sira/content_main/content_add_acta_admin.php';
					break;
				case 'acta_admin_ot':
					include 'view/pages/di/sira/content_header/header_add_acta_admin.php';
					include 'view/pages/di/sira/content_main/content_add_acta_admin_ot.php';
					break;
				case 'list_acta_admin':
					include 'view/pages/di/sira/content_header/header_list_actas_admin.php';
					include 'view/pages/di/sira/content_main/content_list_actas_admin.php';
					include 'view/pages/di/sira/modals/modal_envio_acta_admin.php';
					break;
				case 'reports_i':
					include 'view/pages/di/sira/content_header/header_reporte_i.php';
					include 'view/pages/di/sira/content_main/content_reporte_i.php';
					break;
				
				default:
					header("Location: ../../login.php");
					break;
			}
			break;
		case 'SAPA':
			switch ($menu) {
				case 'general':
					if ( $_SESSION['nivel'] == 'SECRETARIA' ) {
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
					include 'view/pages/dr/hinojosa/modals/modal_add_sancion.php';
					include 'view/pages/dr/hinojosa/modals/modal_add_verificacion.php';
					break;
				case 'e_procesal':
					include 'view/pages/dr/hinojosa/content_header/header_e_procesal.php';
					include 'view/pages/dr/hinojosa/content_main/content_e_procesal.php';
					break;
				case 'cedula':
					include 'view/pages/dr/hinojosa/content_header/header_cedula.php';
					include 'view/pages/dr/hinojosa/content_main/content_cedula.php';
					break;
				case 'modificar':
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
					include 'view/pages/dr/directivo/modals/modal_situacion.php';
					break;
				
				case 'list_exp':
					include 'view/pages/dr/directivo/content_header/header_analista.php';
					include 'view/pages/dr/directivo/content_main/content_analista.php';
					#Seccion de modales
					include 'view/pages/dr/directivo/modals/modal_add_acuse.php';
					include 'view/pages/dr/directivo/modals/modal_send_sapa.php';
					break;
				case 'cedula':
					include 'view/pages/dr/directivo/content_header/header_cedula.php';
					include 'view/pages/dr/directivo/content_main/content_cedula.php';
					#Seccion de modales
					#include 'view/pages/dr/directivo/modals/modal_ejemplo.php';
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
				case 'list_queja':#Listado de quejas
					include 'view/pages/di/qd/content_header/header_list.php';
					include 'view/pages/di/qd/content_main/content_list.php';
					#Inclución de modales
					include 'view/pages/di/qd/modals/modal_upload_file.php';
					include 'view/pages/di/qd/modals/modal_asignar.php';
					include 'view/pages/di/qd/modals/modal_add_opinion.php';

					break;
				case 'turnar':
					include 'view/pages/di/qd/content_header/header_turnar.php';
					include 'view/pages/di/qd/content_main/content_turnar.php';
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
				case 'seguimiento':
					include 'view/pages/di/qd/content_header/header_seguimiento.php';
					include 'view/pages/di/qd/content_main/content_seguimiento.php';
					#Inclución de los modales
					include 'view/pages/di/qd/modals/modal_add_presunto.php';
					include 'view/pages/di/qd/modals/modal_add_unidad.php';
					include 'view/pages/di/qd/modals/modal_add_quejoso.php';
					include 'view/pages/di/qd/modals/modal_add_cargo.php';
					include 'view/pages/di/qd/modals/modal_add_adsp.php';
					break;
				case 'm_queja':
					include 'view/pages/di/qd/content_header/header_edit.php';
					include 'view/pages/di/qd/content_main/content_edit.php';
					break;
				#Cedula general del sistema Viejo
				case 'cedula_old':
					include 'view/pages/uai/content_header/header_cedula_old.php';
					include 'view/pages/uai/content_main/content_cedula_old.php';
					break;
			}
			break;
		case 'SC':#SUBDIRECCION DE LO CONTENCIOSO (REYES)
			switch ($menu) {
				case 'general':
					if ( $_SESSION['nivel'] == 'SUBDIRECTOR' || $_SESSION['nivel'] == 'JEFE'|| $_SESSION['nivel'] == 'ANALISTA' ) {
						include 'view/pages/dr/reyes/content_header/header_general.php';
						include 'view/pages/dr/reyes/content_main/content_general.php';
						#Seccion de modales
						include 'view/pages/dr/reyes/modals/modal_add_responsable.php';
					}else{
						include 'view/pages/dr/reyes/content_header/header_add_acuse.php';
						include 'view/pages/dr/reyes/content_main/content_add_acuse.php';
					}
					include 'view/pages/dr/hinojosa/modals/modal_add_sancion.php';
					include 'view/pages/dr/hinojosa/modals/modal_add_verificacion.php';
					
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
				case 'add_acuse':
					include 'view/pages/dr/reyes/content_header/header_add_acuse.php';
					include 'view/pages/dr/reyes/content_main/content_add_acuse.php';
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
					#modales
					include 'view/pages/dr/directivo/modals/modal_situacion.php';
					break;
				case 'apersonamiento':
					include 'view/pages/dr/reyes/content_header/header_apersonamiento.php';
					include 'view/pages/dr/reyes/content_main/content_apersonamiento.php';
					break;	
				case 'tablero':
					include 'view/pages/dr/reyes/content_header/header_tablero.php';
					include 'view/pages/dr/reyes/content_main/content_tablero.php';
					break;
				case 'modificar':
					include 'view/pages/dr/reyes/content_header/header_modificar.php';
					include 'view/pages/dr/reyes/content_main/content_modificar.php';
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
				#Direccion de responsabilides
				case 'dr':
					include 'view/pages/uai/content_header/header_res.php';
					include 'view/pages/uai/content_main/content_res.php';
					include 'view/pages/dr/directivo/modals/modal_situacion.php';
					break;
				#Direccion de Investigación
				case 'di':
					include 'view/pages/uai/content_header/header_inv.php';
					include 'view/pages/uai/content_main/content_inv.php';
					break;
				#Cedula general del sistema Viejo
				case 'cedula_old':
					include 'view/pages/uai/content_header/header_cedula_old.php';
					include 'view/pages/uai/content_main/content_cedula_old.php';
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