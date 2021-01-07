<?php
/**
 * ACCIONES DE USUARIOS CON PERFIL TITULAR 
 */
include_once 'anexgrid.php';
include_once 'UserModel.php';
class UAIModel extends Connection
{
	private $sql;
	private $stmt;
	public $result;
	public function getDashboard()
	{
		try {
			$investigacion = array(); 
			$wh = " 1=1 AND DATE(q.created_at) >= '2019-07-01' ";
			if( !empty($_POST['y']) ){
				$wh .= " AND YEAR(q.created_at) = ".$_POST['y'];
			}
			$this->sql = "SELECT q.estado, UPPER(e.nombre) as nombre , count(q.id) AS total from quejas AS q
			INNER JOIN estado_guarda as e ON e.id = q.estado 
			where $wh AND q.t_asunto = 1  GROUP BY q.estado";
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$investigacion['qdp'] = $this->result;
			$this->sql = "SELECT q.estado, UPPER(e.nombre) as nombre , count(q.id) AS total from quejas AS q
			INNER JOIN estado_guarda as e ON e.id = q.estado 
			where $wh AND q.t_asunto = 2 GROUP BY q.estado";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$investigacion['qdnp'] = $this->result;
			return json_encode( $investigacion );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getDashboardActas()
	{
		try { 
			$wh = " 1=1 ";
			if( !empty($_POST['y']) ){
				$wh .= " AND YEAR(fecha) = ".$_POST['y'];
			}
			$this->sql = "SELECT t_actuacion as nombre, COUNT(t_actuacion) as total FROM actas 
			WHERE $wh GROUP BY t_actuacion 
			";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function getClavesExp()
	{
		try {			
			$term = "%".$_REQUEST['term']."%";
			$this->sql = "SELECT id, cve_exp AS value FROM quejas WHERE cve_exp LIKE ? AND created_at > '2019-07-01' LIMIT 0,10;";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$term, PDO::PARAM_STR);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getExpedientesEstado()
	{
		try {	
			$wh = " 1=1 AND q.created_at > '2019-07-01' ";
			$edo = $_POST['e'];
			$year = $_POST['y'];
			if ( !empty($edo) ) {
				$wh .= " AND q.estado = ".$edo;
			}
			if ( !empty($year) ) {
				$wh .= " AND YEAR(q.f_hechos) = ".$year;
			}
			#echo $wh;
			$this->sql = "SELECT q.*,t.nombre AS n_tramite, e.nombre AS n_estado, p.nombre AS n_procedencia 
			FROM quejas AS q
			LEFT JOIN tipos_tramite AS t ON t.id = q.t_tramite
			LEFT JOIN estado_guarda AS e ON e.id = q.estado
			LEFT JOIN procedencias AS p ON p.id = q.procedencia
			WHERE $wh and q.t_asunto = 1";
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$term, PDO::PARAM_STR);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getExpedientesEstadoNP()
	{
		try {	
			$wh = " 1=1 AND q.created_at > '2019-07-01' AND q.t_asunto = 2";
			$edo = $_POST['e'];
			$year = $_POST['y'];
			if ( !empty($edo) ) {
				$wh .= " AND q.estado = ".$edo;
			}
			if ( !empty($year) ) {
				$wh .= " AND YEAR(q.f_hechos) = ".$year;
			}
			#echo $wh;
			$this->sql = "SELECT q.*,t.nombre AS n_tramite, e.nombre AS n_estado, p.nombre AS n_procedencia 
			FROM quejas AS q
			LEFT JOIN tipos_tramite AS t ON t.id = q.t_tramite
			LEFT JOIN estado_guarda AS e ON e.id = q.estado
			LEFT JOIN procedencias AS p ON p.id = q.procedencia
			WHERE $wh  ";
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$term, PDO::PARAM_STR);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function getActasTipo()
	{
		try {	
			$wh = " 1=1 ";
			
			$act = ( !isset($_POST['a']) ) ? '' : $_POST['a'];
			$year = ( !isset($_POST['y']) ) ? '' : $_POST['y'];
			if ( !empty($act) ) {
				$wh .= " AND a.t_actuacion = '$act'";
			}
			if ( !empty($year) ) {
				$wh .= " AND YEAR(a.fecha) = '$year'";
			}
			#echo $wh;exit;
			$this->sql = "SELECT a.*, CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat) as elaboro FROM actas AS a INNER JOIN personal AS p ON p.id = a.persona_id
			WHERE $wh ";
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$term, PDO::PARAM_STR);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getCoincidencias()
	{
		try {	
			$wh = " 1=1 AND q.created_at > '2019-07-01'";
			$p = "%".$_POST['palabra']."%";
			if ( !empty($p) ) {
				$wh .= " AND q.descripcion LIKE '$p'";
			}
			#echo $wh;exit;
			$this->sql = "SELECT q.*,t.nombre AS n_tramite, e.nombre AS n_estado,p.nombre AS n_procedencia 
			FROM quejas AS q
			LEFT JOIN tipos_tramite AS t ON t.id = q.t_tramite
			LEFT JOIN estado_guarda AS e ON e.id = q.estado
			LEFT JOIN procedencias AS p ON p.id = q.procedencia
			WHERE $wh";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getContadores($queja_id)
	{
		try {
			$contadores = array();
			#verificar que la queja esta en respo
			$this->sql 	= "SELECT q.estado,q.f_hechos,DATE(q.created_at) AS apertura, e.nombre AS n_estado FROM quejas AS q
			INNER JOIN estado_guarda AS e ON e.id = q.estado
			WHERE q.id = ?";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$qd = $this->stmt->fetch(PDO::FETCH_OBJ);
			$f_hechos = $qd->f_hechos;
			$apertura = $qd->apertura;
			$estado = $qd->estado;
			$n_estado = $qd->n_estado;
			
			if ($estado == '1' || $estado == '8' ) {##Tramite y acumulado

				#genera la resta 
				$this->sql 	= "SELECT DATEDIFF( DATE(NOW()), ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$f_hechos,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;

				$contadores['d_hechos'] = $f_res;
				$contadores['d_di'] = $f_res;
				$this->sql 	= "SELECT DATEDIFF( DATE(NOW()), ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$apertura,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
				
				$contadores['d_apertura'] = $f_res;
				$contadores['d_dr'] = 'AÚN NO DEFINIDO';
				$contadores['estado'] =" EN ". $n_estado. " ";
			}
			#aRCHIVO, INCOMPETENCIA, PRESCRITO
			if ($estado == '2' || $estado == '3' || $estado == '9' ) {
				#Tiempo que lo trabajo investigacion ( fecha_turnado - Fecha de apertura)
				$this->sql 	= "SELECT max(id),f_turno FROM e_turnados WHERE queja_id = ?";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				$turno = $this->stmt->fetch(PDO::FETCH_OBJ)->f_turno;
				#genera la resta 
				$this->sql 	= "SELECT DATEDIFF( ?, ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$turno,PDO::PARAM_STR);
				$this->stmt->bindParam(2,$apertura,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
				$contadores['d_apertura'] = $f_res;
				$contadores['d_hechos'] = $f_res;
				$contadores['estado'] = $n_estado. " EN LA DIRECCIÓN DE INVESTIGACIÓN.";
				$contadores['d_di'] = 'YA ESTA PRESCRITO';
				$contadores['d_dr'] = 'YA ESTA PRESCRITO';
			}
			#Si es resposabilidades 
			if ($estado == '7' ) {#
				#dias transcurridos desde la apertura
				$this->sql 	= "SELECT DATEDIFF( DATE(NOW()), ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$apertura,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
				$contadores['d_apertura'] = $f_res;
				#dias transcurridos desde la fecha de hechos
				$this->sql 	= "SELECT DATEDIFF( DATE(NOW()), ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$f_hechos,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
				$contadores['d_hechos'] = $f_res;
				#duracion de dias en investigacion
				$this->sql 	= "SELECT max(id),f_turno FROM e_turnados WHERE queja_id = ?";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				$turno = $this->stmt->fetch(PDO::FETCH_OBJ)->f_turno;
				$this->sql 	= "SELECT DATEDIFF( ?, ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$turno,PDO::PARAM_STR);
				$this->stmt->bindParam(2,$apertura,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
				$contadores['d_di'] = $f_res;
				#duracion de dias en respo
				$this->sql 	= "SELECT DATEDIFF( DATE(NOW()), ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$turno,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
				$contadores['d_dr'] = $f_res;
				$contadores['estado'] ="EN LA DIRECCIÓN DE RESPONSABILIDADES EN ASUNTOS INTERNOS. ";
			}

			#Si es reserva 
			if ($estado == '10' ) {#
				#dias transcurridos desde la apertura
				$this->sql 	= "SELECT max(id),f_reserva FROM reservas WHERE queja_id = ?";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				$f_reserva = $this->stmt->fetch(PDO::FETCH_OBJ)->f_reserva;
				$this->sql 	= "SELECT DATEDIFF( ?, ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$f_reserva,PDO::PARAM_STR);
				$this->stmt->bindParam(2,$apertura,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
				$contadores['d_apertura'] = $f_res;
				#dias transcurridos desde la fecha de hechos
				$this->sql 	= "SELECT DATEDIFF( ?, ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$f_reserva,PDO::PARAM_STR);
				$this->stmt->bindParam(2,$f_hechos,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
				$contadores['d_hechos'] = $f_res;
				#duracion de dias en investigacion
				$this->sql 	= "SELECT max(id),f_turno FROM e_turnados WHERE queja_id = ?";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ) {
					$turno = $this->stmt->fetch(PDO::FETCH_OBJ)->f_turno;
					$this->sql 	= "SELECT DATEDIFF( ?, ?) AS f_res";
					$this->stmt = $this->pdo->prepare( $this->sql );
					$this->stmt->bindParam(1,$turno,PDO::PARAM_STR);
					$this->stmt->bindParam(2,$apertura,PDO::PARAM_STR);
					$this->stmt->execute();
					$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
					$contadores['d_di'] = $f_res;
					#duracion de dias en respo
					$this->sql 	= "SELECT DATEDIFF( ?, ?) AS f_res";
					$this->stmt = $this->pdo->prepare( $this->sql );
					$this->stmt->bindParam(1,$f_reserva,PDO::PARAM_STR);
					$this->stmt->bindParam(2,$turno,PDO::PARAM_STR);
					$this->stmt->execute();
					$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
					$contadores['d_dr'] = $f_res;
				}else{
					$contadores['d_di'] = 'NO SE A TURNADO EL EXPEDIENTE';
					$contadores['d_dr'] = 'NO SE A TURNADO EL EXPEDIENTE';
				}
				
				$contadores['estado'] = $n_estado. " EN LA DIRECCIÓN DE RESPONSABILIDADES EN ASUNTOS INTERNOS.";
			}
			#Si es improcedecia 
			if ($estado == '11' ) {#
				#dias transcurridos desde la apertura
				$this->sql 	= "SELECT max(id),f_reserva FROM reservas WHERE queja_id = ?";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				$f_reserva = $this->stmt->fetch(PDO::FETCH_OBJ)->f_reserva;
				$this->sql 	= "SELECT DATEDIFF( ?, ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$f_reserva,PDO::PARAM_STR);
				$this->stmt->bindParam(2,$apertura,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
				$contadores['d_apertura'] = $f_res;
				#dias transcurridos desde la fecha de hechos
				$this->sql 	= "SELECT DATEDIFF( ?, ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$f_reserva,PDO::PARAM_STR);
				$this->stmt->bindParam(2,$f_hechos,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
				$contadores['d_hechos'] = $f_res;
				#duracion de dias en investigacion
				$this->sql 	= "SELECT max(id),f_turno FROM e_turnados WHERE queja_id = ?";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				$turno = $this->stmt->fetch(PDO::FETCH_OBJ)->f_turno;
				$this->sql 	= "SELECT DATEDIFF( ?, ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$turno,PDO::PARAM_STR);
				$this->stmt->bindParam(2,$apertura,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
				$contadores['d_di'] = $f_res;
				#duracion de dias en respo
				$this->sql 	= "SELECT DATEDIFF( ?, ?) AS f_res";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$f_reserva,PDO::PARAM_STR);
				$this->stmt->bindParam(2,$turno,PDO::PARAM_STR);
				$this->stmt->execute();
				$f_res = $this->stmt->fetch(PDO::FETCH_OBJ)->f_res;
				$contadores['d_dr'] = $f_res;
				$contadores['estado'] = $n_estado. " EN LA DIRECCIÓN DE RESPONSABILIDADES EN ASUNTOS INTERNOS.";
			}
			
			return $contadores;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	#Obtener datos de la subdireccion de lo contencioso
	public function getDataSC($queja_id)
	{
		try {
			$sc = array();
			$aux = array();
			$this->sql = "SELECT * FROM demandas AS d
			INNER JOIN r_demanda AS r ON r.demanda_id = d.id
			WHERE queja_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return $this->result;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()));
		}
	}
	public function countSendCHyJ()#Cuenta expedientes enviados a la CHyJ
	{
		try {
			$this->sql = "SELECT COUNT(*) AS cuenta FROM quejas_respo WHERE estado = 1 AND autoridad = 'CHyJ'";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()));
		}
	}
	public function getExpByDemanda()#Cuenta expedientes enviados a la CHyJ
	{
		try {
			$td = $_POST['td'];
			
			$this->sql = "SELECT queja_id FROM demandas WHERE t_demanda = ? GROUP BY  queja_id;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1, $td , PDO::PARAM_INT);
			$this->stmt->execute();
			$quejas_id = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux = array();
			foreach ($quejas_id as $key => $q) {
				array_push($aux, $q->queja_id);
			}
			$aux = implode(',', $aux);
			$this->sql = "SELECT q.*,
			CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat ) AS name_abogado, 
			CONCAT(p2.nombre,' ',p2.ap_pat,' ',p2.ap_mat ) AS name_jefe
			FROM quejas AS q 
			INNER JOIN e_turnados AS e ON e.queja_id = q.id
			INNER JOIN personal AS p ON p.id = e.persona_id
			LEFT JOIN personal AS p2 ON p2.id = e.jefe_id
			WHERE q.id IN ($aux) GROUP BY  id;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$quejas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux = array(); $qd = array();
			foreach ($quejas as $key => $queja) {
				$aux['id'] = $queja->id;
				$aux['cve_exp'] = $queja->cve_exp;
				$aux['name_abogado'] = $queja->name_abogado;
				$aux['name_jefe'] = $queja->name_jefe;
				#Buscar fecha de resolucion chyj
				$this->sql = "SELECT * FROM demandas WHERE queja_id = ? AND t_demanda = ? 
				ORDER BY id DESC LIMIT 1";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja->id, PDO::PARAM_INT);
				$this->stmt->bindParam(2,$td, PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0  ) {
					$dem = $this->stmt->fetch(PDO::FETCH_OBJ);
					$aux['u_oficio'] = $dem->oficio;#ultimo oficio
				}else{
					$aux['u_oficio'] = 'NO SE ENCONTRÓ OFICIO DE DEMANDA';#ultimo oficio
				}
				#detalle del apersonamiento
				$this->sql = "SELECT * FROM r_demanda WHERE demanda_id = ?
				ORDER BY id DESC LIMIT 1";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja->id, PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0  ) {
					$apersona = $this->stmt->fetchAll(PDO::FETCH_OBJ);
					$aux['apersona'] = $apersona;#
				}else{
					$aux['apersona'] = NULL;#ultimo oficio
				}
				#fechas
				$aux['f_chyj'] = NULL;
				$aux['f_trijaem'] = NULL;
				#Revisar si existe acuerdo de conclusion 
				$this->sql = "SELECT f_acuerdo, asunto FROM acuerdos WHERE queja_id = ? ORDER BY id DESC LIMIT 1";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja->id, PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ) {
					$conclucion = $this->stmt->fetch(PDO::FETCH_OBJ);
					$aux['f_acuerdo'] = $conclucion->f_acuerdo;
					$aux['asunto'] = $conclucion->asunto;
				}else{
					$aux['f_acuerdo'] = NULL;
					$aux['asunto'] = NULL;
				}
				

				array_push($qd, $aux);
			}
			return json_encode($qd);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()));
		}
	}
	public function getExpByResCom()
	{
		try {
			$r = $_POST['r'];
			$this->sql = "SELECT queja_id FROM resoluciones WHERE sancion = ? GROUP BY  queja_id;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1, $r , PDO::PARAM_STR);
			$this->stmt->execute();
			$quejas_id = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux = array();
			foreach ($quejas_id as $key => $q) {
				array_push($aux, $q->queja_id);
			}
			$aux = implode(',', $aux);
			$this->sql = "SELECT q.*,
			CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat ) AS name_abogado, 
			CONCAT(p2.nombre,' ',p2.ap_pat,' ',p2.ap_mat ) AS name_jefe
			FROM quejas AS q 
			INNER JOIN e_turnados AS e ON e.queja_id = q.id
			INNER JOIN personal AS p ON p.id = e.persona_id
			LEFT JOIN personal AS p2 ON p2.id = e.jefe_id
			WHERE q.id IN ($aux) GROUP BY  id;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$quejas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux = array(); $qd = array();
			foreach ($quejas as $key => $queja) {
				$aux['id'] = $queja->id;
				$aux['cve_exp'] = $queja->cve_exp;
				$aux['name_abogado'] = $queja->name_abogado;
				$aux['name_jefe'] = $queja->name_jefe;
				#Buscar fecha de resolucion chyj
				$this->sql = "SELECT * FROM demandas WHERE queja_id = ? AND t_demanda = ? 
				ORDER BY id DESC LIMIT 1";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja->id, PDO::PARAM_INT);
				$this->stmt->bindParam(2,$td, PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0  ) {
					$dem = $this->stmt->fetch(PDO::FETCH_OBJ);
					$aux['u_oficio'] = $dem->oficio;#ultimo oficio
				}else{
					$aux['u_oficio'] = 'NO SE ENCONTRÓ OFICIO DE DEMANDA';#ultimo oficio
				}
				#detalle del apersonamiento
				$this->sql = "SELECT * FROM r_demanda WHERE demanda_id = ?
				ORDER BY id DESC LIMIT 1";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja->id, PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0  ) {
					$apersona = $this->stmt->fetchAll(PDO::FETCH_OBJ);
					$aux['apersona'] = $apersona;#
				}else{
					$aux['apersona'] = NULL;#ultimo oficio
				}
				#fechas
				$aux['f_chyj'] = NULL;
				$aux['f_trijaem'] = NULL;
				#Revisar si existe acuerdo de conclusion 
				$this->sql = "SELECT f_acuerdo, asunto FROM acuerdos WHERE queja_id = ? ORDER BY id DESC LIMIT 1";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja->id, PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ) {
					$conclucion = $this->stmt->fetch(PDO::FETCH_OBJ);
					$aux['f_acuerdo'] = $conclucion->f_acuerdo;
					$aux['asunto'] = $conclucion->asunto;
				}else{
					$aux['f_acuerdo'] = NULL;
					$aux['asunto'] = NULL;
				}
				

				array_push($qd, $aux);
			}
			return json_encode($qd);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()));
		}
	}
	public function getExpByEdoDem()
	{
		try {
			$edo = $_POST['edo'];
			$d = $_POST['d'];
			$this->sql = "SELECT d.queja_id FROM demandas AS d 
			INNER JOIN r_demanda AS rd ON rd.demanda_id = d.id
			WHERE rd.estado = ? AND d.t_demanda = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1, $edo , PDO::PARAM_STR);
			$this->stmt->bindParam(2, $d , PDO::PARAM_INT);
			$this->stmt->execute();
			$quejas_id = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux = array();
			foreach ($quejas_id as $key => $q) {
				array_push($aux, $q->queja_id);
			}
			$aux = implode(',', $aux);
			$this->sql = "SELECT q.*,
			CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat ) AS name_abogado, 
			CONCAT(p2.nombre,' ',p2.ap_pat,' ',p2.ap_mat ) AS name_jefe
			FROM quejas AS q 
			INNER JOIN e_turnados AS e ON e.queja_id = q.id
			INNER JOIN personal AS p ON p.id = e.persona_id
			LEFT JOIN personal AS p2 ON p2.id = e.jefe_id
			WHERE q.id IN ($aux) GROUP BY  id;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$quejas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux = array(); $qd = array();
			foreach ($quejas as $key => $queja) {
				$aux['id'] = $queja->id;
				$aux['cve_exp'] = $queja->cve_exp;
				$aux['name_abogado'] = $queja->name_abogado;
				$aux['name_jefe'] = $queja->name_jefe;
				#Buscar fecha de resolucion chyj
				$this->sql = "SELECT * FROM demandas WHERE queja_id = ? AND t_demanda = ? 
				ORDER BY id DESC LIMIT 1";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja->id, PDO::PARAM_INT);
				$this->stmt->bindParam(2,$td, PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0  ) {
					$dem = $this->stmt->fetch(PDO::FETCH_OBJ);
					$aux['u_oficio'] = $dem->oficio;#ultimo oficio
				}else{
					$aux['u_oficio'] = 'NO SE ENCONTRÓ OFICIO DE DEMANDA';#ultimo oficio
				}
				#detalle del apersonamiento
				$this->sql = "SELECT * FROM r_demanda WHERE demanda_id = ?
				ORDER BY id DESC LIMIT 1";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja->id, PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0  ) {
					$apersona = $this->stmt->fetchAll(PDO::FETCH_OBJ);
					$aux['apersona'] = $apersona;#
				}else{
					$aux['apersona'] = NULL;#ultimo oficio
				}
				#fechas
				$aux['f_chyj'] = NULL;
				$aux['f_trijaem'] = NULL;
				#Revisar si existe acuerdo de conclusion 
				$this->sql = "SELECT f_acuerdo, asunto FROM acuerdos WHERE queja_id = ? ORDER BY id DESC LIMIT 1";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja->id, PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ) {
					$conclucion = $this->stmt->fetch(PDO::FETCH_OBJ);
					$aux['f_acuerdo'] = $conclucion->f_acuerdo;
					$aux['asunto'] = $conclucion->asunto;
				}else{
					$aux['f_acuerdo'] = NULL;
					$aux['asunto'] = NULL;
				}
				

				array_push($qd, $aux);
			}
			return json_encode($qd);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()));
		}
	}
}
?>