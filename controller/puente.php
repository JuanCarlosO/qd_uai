<?php 
include 'Security.php';
spl_autoload_register(function ($class) {
    include $class.'.php';
});
/*DECLARACIÓN DE LAS CLASES*/
$u = new UserController();
$i = new QDController();
$s = new SiraController();
$r = new DRController();
$uai = new UAIController();
/***/
if ( isset($_POST['option']) ) {
	$o = $_POST['option'];
	switch ( $o ) {
		case '1':
			$access = $u->validateAccess();
			
			if ( is_object($access) ) {
					
				session_start();
				$_SESSION['id']			= $access->personal_id;
				$_SESSION['name'] 		= $access->name;
				$_SESSION['n_completo'] = $access->n_completo;
				$_SESSION['perfil'] 	= $access->perfil;
				$_SESSION['area_id'] 	= $access->area_id;
				$_SESSION['n_area'] 	= $access->n_area;
				$_SESSION['nivel'] 		= $access->nivel;
				
				if ( isset($_SESSION) ) {
					header('Location: ../index.php?menu=general');
				}
			}else if( is_array($access) ){
				if ( isset( $access['status'] ) ) {
					if ( $access['status'] == 'error' ) {
						header('Location: ../login.php?edo=error&e_message='.$access['message']);
					}
				}
			}
			break;
		case '2':#Recuperar los tipos de referencia
			#echo json_encode( array('status'=>'error','message'=>'Mi mensaje de respuesta') );
			echo $i->getTR();
			break;
		case '3':
			echo $i->saveTR();
			break;
		case '4':
			echo $i->getProcedencias();
			break;
		case '4X':
			echo $i->getCapitulos();
			break;
			
		case '5':
			echo $i->getTT();
			break;
		case '6':
			echo $i->getCode();
			break;
		case '7A':
			echo $i->getLeyes();
			break;
		case '7B':
			echo $i->getArticulos();
			break;
		case '7C':
			echo $i->getSecciones();
			break;
		case '7D':
			echo $i->getFracciones();
			break;
		case '8':
			echo $i->getConductas();
			break;
		case '9':
			echo $i->getVias();
			break;
		case '10':
			echo $i->getMunicipios();
			break;
		case '11':
			echo $i->saveQueja();
			break;
		case '12':
			echo $i->getEstadosGuarda();
			break;
		case '13':
			echo $i->delete_turno();
			break;
		case '14':
			echo $i->delete_conducta();
			break;
		case '15':
			echo $i->saveDoc();
			break;
		case '16':
			echo $i->saveUnidad();
			break;
		#CASE 17 DISPONIBLE
		case '18':
			echo $i->saveQuejoso();
			break;
		case '19':
			echo $i->deleteFile();
			break;
		case '20':
			echo $i->editQD();
			break;
		case '21':
			echo $i->deleteVia();
			break;
		case '22':
			echo $i->getCargos();
			break;
		case '23':
			echo $i->getSubdirecciones();
			break;
		case '24':
			echo $i->getAgrupamientos();
			break;
		case '25':
			echo $i->getRegiones();
			break;
		case '26':
			echo $i->deletePresunto();
			break;
		case '27':
			echo $i->deleteUnidad();
			break;
		case '28':
			echo $i->deleteQuejoso();
			break;
		case '29':
			echo $i->generateReporte();
			break;
		case '30':
			echo $s->saveActa();
			break;
		case '31':
			echo $s->saveDocActa();
			break;
		case '32':
			echo $s->editActa();
			break;
		case '33':
			echo $s->savePresuntoR();
			break;
		case '34':
			echo $s->getPresuntos();
			break;
		case '35':
			echo $s->deletePR();			
			break;
		case '36':
			echo $s->saveQuejoso();
			break;
		case '37':
			echo $s->getQuejosos();
			break;
		case '38':
			echo $s->deleteQuejoso();
			break;
		case '39':
			echo $s->saveAuto();
			break;
		case '40':
			echo $s->getMarcas();
			break;
		case '41':
			echo $s->getAutos();
			break;
		case '42':
			echo $s->deleteAuto();
			break;
		case '43':
			echo $s->saveAnimal();
			break;
		case '44':
			echo $s->getAnimales();
			break;
		case '45':
			echo $s->deleteAnimal();
			break;
		case '46':
			echo $s->getArmas();
			break;
		case '47':
			echo $s->saveArma();
			break;
		case '48':
			echo $s->deleteArma();
			break;
		case '49':
			echo $s->getDocumentos();
			break;	
		case '50':
			echo $s->deleteDoc();
			break;	
		case '51':
			echo $s->generateReporte();
			break;	
		case '52':
			echo $s->generateReporte();
			break;	
		case '53':
			echo $s->saveDocOIN();
			break;
		case '54':
			echo $r->saveEdoProcesal();
			break;	
		case '55':
			echo $r->delete_responsable();
			break;	
		case '56':
			echo $r->updateEdoProcesal();
			break;	
		case '57':
			echo $s->getDashboard();
			break;	
		case '58':
			echo $r->saveOpinion();
			break;	
		case '59':
			echo $r->getConductasRespo();
			break;	
		case '60':
			echo $r->saveConductaRespo();
			break;	
		case '61':
			echo $r->saveAbogadoRes();
			break;	
		case '62':
			echo $r->saveResolucion();
			break;	
		case '63':
			echo $r->saveDemanda();
			break;	
		case '64':
			echo $r->saveResolucionDemanda();
			break;	
		case '65':
			echo $r->saveReserva();
			break;	
		case '66':
			echo $r->saveAImprocedencia();
			break;
		case '67':
			echo $r->devolverExp();
			break;
		case '68':
			echo $r->getReporte();
			break;	
		case '69':
			echo $r->editResolucion();
			break;
		case '70':
			echo $r->getResolucion();
			break;
		case '71':
			echo $uai->getDashboard();
			break;
		case '72':
			echo $uai->getExpedientesEstado();
			break;
		case '72B':
			echo $uai->getExpedientesEstadoNP();
			break;
			
		case '73':
			echo $uai->getDashboardActas();
			break;
		case '74':
			echo $uai->getActasTipo();
			break;
		case '75':
			echo $uai->getCoincidencias();
			break;
		case '76':#Cuando se va a turnar un expediente
			echo $i->saveTurno();
			break;
		case '77':#Cuando se va a turnar un expediente
			echo $i->getDependenciasF();
			break;
		case '78':#
			echo $r->getTblCtrl();
			break;
		case '79':#Obtener exédientes del tablero de control
			echo $r->getExpedientesTC();
			break;
		case '80':#
			echo $i->getTblCtrl();
			break;
		case '81':#Contar expe
			echo $i->getExpedientesTC();
			break;
		case '82':#Contar expe
			echo $i->getTblCtrlSubd();
			break;	
		case '83':#
			echo $i->getExpedientesForMigrate();
			break;	
		case '84':#
			echo $i->MigrateQuejas();
			break;
		case '85':#
			echo $i->getOINs();
			break;
		case '86':#Obtener actas por tipo de tramite 
			echo $s->getActasBy();
			break;
		case '87':#Obtener actas por tipo de tramite 
			echo $i->contadorOINs();
			break;
		case '88':#
			echo $i->getOINBy();
			break;
		case '89':#
			echo $r->saveAcuse();
			break;
		case '90':#eNVIAR A SAPA
			echo $r->sendSAPA();
			break;
		case '91':#
			echo $r->saveApersonamiento();
			break;
		case '92':#
			echo $r->saveAcuseSapa();
			break;
		case '93':#
			echo $r->asignarPersonal();
			break;
		case '94':#Guardar el estadp procesal
			echo $r->saveEProcesal();
			break;
		case '95':#Guardar el estadp procesal
			echo $r->saveCulpable();
			break;
		case '96':
			echo $r->getEstadistica();
			break;
		case '97':
			echo $r->saveApersonamiento();
			break;
		case '98':
			echo $r->getClave($_POST['id']);
			break;
		case '99':
			echo $r->getContadoresByEdo();
			break;
		case '100':
			session_start();
			if ( $_SESSION['perfil'] == 'SAPA' ) {
				echo $r->saveAsignar();
			}else{
				echo $i->saveAsignar();
			}
			
			break;
		case '101':
			echo $i->saveCargo();
			break;
		case '102':
			echo $i->getProcedenciasP();
			break;
		case '103':
			echo $i->saveProcedenciasP();
			break;
		case '104':
			echo $r->getSituacionSC();
			break;
		case '105':
			echo $uai->countSendCHyJ();
			break;
		case '106':
			echo $uai->getExpByDemanda();
			break;
		case '107':
			echo $uai->getExpByResCom();
			break;
		case '108':
			echo $uai->getExpByEdoDem();
			break;
		case '109':
			echo $i->getPenales();
			break;
		case '110':
			echo $i->saveOpinion();
			break;
		case '111':
			echo $r->save_acuse();
			break;
		case '112':
			echo $i->getColores();
			break;
		case '113':
			echo $r->getExpSapaByEdo();
			break;
		case '114':
			echo $s->saveCancelaOT();
			break;
		case '115':
			echo $i->getExpTipo();
			break;
		case '116':
			echo $i->getListadoTipo();
			break;
		case '117':
			echo $s->saveRespuesta();
			break;
		case '118':
			echo $s->saveResponder();
			break;
		case '119':
			echo $s->Responder_Comisionados();
			break;
		case '120':
			echo $s->Responder_Turnos();
			break;
		case '121':
			echo $s->Responder_Armas();
			break;
		case '122':
			echo $s->Responder_Gas();
			break;
		case '123':
			echo $s->Responder_Vehiculo();
			break;
		case '124':
			echo $s->saveRecomendacion();
			break;
		case '125':
			echo $s->saveDocObs();
			break;
		case '126':
			echo $s->saveRecordatorioObs();
			break;
		case '127':
			echo $s->saveSeguimiento();
			break;
		case '128':
			echo $s->getCoordinaciones();#obtener el catálogo de grupos de bienes 
			break;
		case '129':			
			echo $s->getSubdirecciones( $_POST['coord'] ); 
			break;
		case '130':			
			echo $s->getRegiones( $_POST['subd'] ); 
			break;
		case '131':			
			echo $s->getAgrupamientos( $_POST['region'] ); 
			break;
		case '132':			
			echo $s->saveReco_respuesta(); 
			break;
		case '133':
			echo $s->getCoordinacionesT();#obtener el catálogo de grupos de bienes 
			break;
		case '134':			
			echo $s->getAgrupamientosT( $_POST['coord_t'] ); 
			break;
		case '135':			
			echo $s->getAgrupamientosCPRS(); 
			break;
		case '136':			
			echo $s->graphic_censo($_POST['n_pregunta'], $_POST['question_a'], $_POST['question_a2'], $_POST['agrupamiento'], $_POST['agrupamiento_t'], $_POST['agrupamiento_cprs'], $_POST['niv5']);
			break;
		case '137':
			echo $s->getDetalle($_POST['pe']);
			break;
		case '138':
			echo $s->getOficio($_POST['o']);
			break;
		case '139':
			echo $s->graphic_actuaciones($_POST['f_inicio'],$_POST['f_fin']);
			break;
		case '140':
			echo $s->graphic_ordenes($_POST['f_inicio'],$_POST['f_fin']);
			break;
		case '141':
			echo $s->getAreaPersonal($_POST['p']);
			break;
		case '142':
			echo $s->getPreguntasCenso(); 
			break;
		case '143A':
			echo $s->getOperativos_Est(); 
			break;
		case '143B':
			echo $s->getTransito_Est(); 
			break;
		case '143C':
			echo $s->getCPRS_Est(); 
			break;
		case '143D':
			echo $s->getAdmin_Est(); 
			break;
		case '144':
			echo $s->tabla_actuaciones($_POST['fi'],$_POST['ff']);
			break;
		case '145':
			echo $s->editCenso();
			break;
		case '146':
			echo $s->saveEditRecomendacion();
			break;
		case '147':
			echo $s->saveActaAdministrativa();
			break;
		case '148':
			#echo json_encode( ['message'=>'success','count'=>3] );die();
			echo $s->EnviarActa();
			break;
		case '149':
			#echo json_encode( ['message'=>'success','count'=>3] );die();
			echo $s->CancelarActa();
			break;
		case '150':
			echo $s->generateReporteI();
			break;
		case '151':
			echo $s->getNivel1();#obtener el catálogo de grupos de bienes 
			break;
		case '152':			
			echo $s->getNivel2( $_POST['niv1'] ); 
			break;
		case '153':			
			echo $s->getNivel3( $_POST['niv2'] ); 
			break;
		case '154':			
			echo $s->getNivel4( $_POST['niv3'] ); 
			break;
		case '155':			
			echo $s->getNivel5( $_POST['niv4'] ); 
			break;
		case '156'://115
			echo $s->saveCenso();
			break;
		case '157'://116
			echo $s->saveRecordatorio();
			break;
		case '158':
			echo $s->saveEstatus();
			break;
		case '159':
			echo $s->saveComentario();
			break;
		case '160':
			echo $s->getDashboard_OT();
			break;
		case '161':
			echo $s->saveAcciones();
			break;
		case '162':
			echo $s->generateReporteOT();
			break;
		#Funcionalidadd desarrollada en el noviembre 2020
		case '1X':
			echo $r->getSanciones();
			break;
		case '2X':
			echo $r->saveSancion();
			break;
		case '3X':
			echo $r->saveVerificacion();
			break;	
		case '4X':
			echo $i->getCapitulos();
			break;	
		case '5X':
			echo $r->editSanVer();
			break;	
									
	}
}elseif ( isset($_GET['option']) ) {
	$o = $_GET['option'];	
	switch ( $o ) {
		case '1':#Recuperar las QD's
			echo $i->getQDs();
			break;
		case '2':#Recuperar las areas
			echo $s->getAreas();
			break;
		case '3':
			echo $i->getPersonal();
			break;
		case '4':
			header("Content-type: application/pdf");	
			echo $i->getDocumento($_GET['file']);
			break;
		case '4A':
			header("Content-type: application/pdf");	
			echo $i->getDocumentoByOficio($_GET['o']);
			break;
		case '4B':
			header("Content-type: application/pdf");	
			echo $r->getDocumentoDevoluciones($_GET['dev']);
			break;
		case '5':
			#Buscar las ordenes de trabajo
			echo $s->getOINs();
			break;
		case '6':
			#Buscar las ACTAS
			echo $s->getActas();
			break;
		case '7':
			header("Content-type: application/pdf");	
			echo $s->getDocumentoActa($_GET['file']);
			break;
		case '8':
			#Buscar las ordenes de trabajo
			echo $s->getListONIs();
			break;
		case '9':
			#Buscar las expedientes
			echo $r->getExpedientes();
			break;	
		case '10':
			#Buscar Numeros de oficio del Sistema de Oficina digital
			echo $r->getOFs();
			break;	
		case '11':
			#Buscar expedientes para el Lic Hinojosa
			echo $r->getExpedientesSC();
			break;	
		case '12':
			echo $uai->getClavesExp();
			break;	
		case '13':
			echo $r->getExpedientesAnalista();
			break;	
		case '14':
			echo $r->getCorrespondencia();
			break;	
		case '15':
			echo $r->getAcusesSAPA();
			break;	
		case '16':
			header("Content-type: application/pdf");
			echo $r->getAcuse($_GET['doc']);
			break;	
		case '17':
			header("Content-type: application/pdf");
			echo $r->viewDoc($_GET['doc'],$_GET['tbl']);
			break;	
		case '18':
			#Buscar los censos
			echo $s->getListCenso();
			break;
		case '19':#Recuperar el catálogo de preguntas
			echo $s->getPreguntas();
			break;
		case '20':#Recuperar las preguntas del censo
			echo $s->getCuestionario();
			break;
		case '21':#Recuperar las preguntas del censo
			echo $s->getRecomendaciones();
			break;
		case '22':
			#Buscar Numeros de oficio del Sistema de Oficina digital
			echo $s->getOFsRes();
			break;
		case '23':
			header("Content-type: application/pdf");	
			echo $s->getAcuse_Irr($_GET['file']);
			break;
		case '24':
			#Buscar los censos
			echo $s->getListActaAdmin();
			break;
		case '25':
			#Buscar los censos
			echo $i->getListActaAdmin_enviadas();
			break;
		case '26':
			header("Content-type: application/pdf");	
			echo $s->getAcuse_Acta($_GET['file']);
			break;	
		case '27':
			header("Content-type: application/pdf");	
			echo $s->getAcuse_OIN($_GET['file']);
			break;		
		case '28':
			echo $s->getPersonalApoyo();
			break;
			
					
		default:
			echo json_encode( array("status"=>'error','message'=>'La ruta seleccionada no existe. Verifique con el Depto. de Desarrollo de Sistemas.') );
			break;
	}
}

?>