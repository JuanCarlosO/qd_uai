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
}
?>