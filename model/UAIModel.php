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
			$wh = " 1=1 ";
			if( !empty($_POST['y']) ){
				$wh .= " AND YEAR(q.f_hechos) = ".$_POST['y'];
			}
			$this->sql = "SELECT q.estado, UPPER(e.nombre) as nombre , count(q.id) AS total from quejas AS q
			INNER JOIN estado_guarda as e ON e.id = q.estado 
			where $wh GROUP BY q.estado";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getDashboardActas()
	{
		try { 
			$wh = " 1=1 ";
			if( !empty($_POST['y']) ){
				$wh .= " AND YEAR(q.f_hechos) = ".$_POST['y'];
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
			$this->sql = "SELECT id, cve_exp AS value FROM quejas WHERE cve_exp LIKE ? LIMIT 0,10;";
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
			$wh = " 1=1 ";
			$edo = $_POST['e'];
			$year = $_POST['y'];
			if ( !empty($edo) ) {
				$wh .= " AND q.estado = ".$edo;
			}
			if ( !empty($year) ) {
				$wh .= " AND YEAR(q.f_hechos) = ".$year;
			}
			#echo $wh;
			$this->sql = "SELECT q.*,t.nombre AS n_tramite, e.nombre AS n_estado,p.nombre AS n_procedencia 
			FROM quejas AS q
			LEFT JOIN tipos_tramite AS t ON t.id = q.t_tramite
			LEFT JOIN estado_guarda AS e ON e.id = q.estado
			LEFT JOIN procedencias AS p ON p.id = q.procedencia
			WHERE $wh ";
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
			$act = $_POST['a'];
			$year = $_POST['y'];
			if ( !empty($act) ) {
				$wh .= " AND a.t_actuacion = '$act'";
			}
			if ( !empty($year) ) {
				$wh .= " AND YEAR(a.fecha) = '$year'";
			}
			#echo $wh;exit;
			$this->sql = "SELECT a.*, CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat) as elaboro FROM actas AS a INNER JOIN personal AS p ON p.id = a.persona_id
			WHERE $wh ";
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
			$wh = " 1=1 ";
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
			
			if ($estado == '1' || $estado == '2' ) {##Tramite y acumulado
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
				$contadores['d_dr'] = $f_res;
				$contadores['estado'] =" EN". $n_estado. " ";
			}
			#aRCHIVO, INCOMPETENCIA, PRESCRITO
			if ($estado == '3' ||$estado == '4'||$estado == '9' ) {
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
			if ($estado == '8' ) {#
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
				$contadores['estado'] ="EN LA DIRECCIÓN DE RESPONSABILIDADES. ";
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
				
				$contadores['estado'] = $n_estado. " EN LA DIRECCIÓN DE RESPONSABILIDADES.";
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
				$contadores['estado'] = $n_estado. " EN LA DIRECCIÓN DE RESPONSABILIDADES.";
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
	
}
?>