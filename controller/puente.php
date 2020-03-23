<?php 
include 'Security.php';
spl_autoload_register(function ($class) {
    include $class.'.php';
});
/*DECLARACIÓN DE LAS CLASES*/
$u = new UserController();
$i = new QDController();
$s = new SiraController();
/***/
if ( isset($_POST['option']) ) {
	$o = $_POST['option'];
	switch ( $o ) {
		case '1':
			$access = $u->validateAccess();
			
			if ( is_object($access) ) {
				
				session_start();
				$_SESSION['id']			= $access->person_id;
				$_SESSION['name'] 		= $access->name;
				$_SESSION['n_completo'] = $access->n_completo;
				$_SESSION['perfil'] 	= $access->perfil;
				$_SESSION['area_id'] 	= $access->area_id;
				$_SESSION['n_area'] 	= $access->n_area;
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
		case '5':
			echo $i->getTT();
			break;
		case '6':
			echo $i->getCode();
			break;
		case '7':
			echo $i->getLeyes();
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
		case '17':
			echo $i->savePresunto();
			break;
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
				
		default:
			# code...
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
		case '5':
			#Buscar las ordenes de trabajo
			echo $s->getOINs();
			break;
		case '6':
			#Buscar las ordenes de trabajo
			echo $s->getActas();
			break;
			
		default:
			echo json_encode( array("status"=>'error','message'=>'La ruta seleccionada no existe. Verifique con el Depto. de Desarrollo de Sistemas.') );
			break;
	}
}

?>