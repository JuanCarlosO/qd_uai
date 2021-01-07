<?php
include_once 'anexgrid.php';
include_once 'UserModel.php';
class QDModel extends Connection
{
	private $sql;
	private $stmt;
	public $result;
	public function getTblCtrl()
	{
		try {
			session_start();
			$resultado = array();
			#Contar los expedientes por estado guarda 
			
			if( $_SESSION['nivel'] == 'DIRECTOR' ){
				$this->sql = "SELECT q.estado, e.nombre AS n_estado,COUNT(q.id) as cuenta 
				FROM quejas AS q 
				INNER JOIN estado_guarda AS e ON e.id = q.estado
				WHERE q.created_at >= '2019-07-01'
				GROUP BY q.estado";
				$ta_wh = "";
			}elseif($_SESSION['nivel'] == 'SUBDIRECTOR'){
				if ( $_SESSION['perfil'] == 'QDP' ) {
					$t_asunto = 1;
				}elseif($_SESSION['perfil'] == 'ESPECIAL'){
					$t_asunto = 2;
				}
				$this->sql = "SELECT q.estado, e.nombre AS n_estado,COUNT(q.id) as cuenta 
				FROM quejas AS q 
				INNER JOIN estado_guarda AS e ON e.id = q.estado
				WHERE q.created_at > '2019-07-01' AND q.t_asunto = $t_asunto
				GROUP BY q.estado";
				$ta_wh = " AND q.t_asunto = $t_asunto";
			}
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$resultado['edos'] = $this->result;
			/*$this->sql = "SELECT p.id,CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat) AS full_name,COUNT(e.persona_id) AS cuenta ,
			a.nombre AS n_area
			FROM e_turnados AS e 
			INNER JOIN personal AS p ON p.id = e.persona_id
			INNER JOIN areas AS a ON a.id = p.area_id
			INNER JOIN quejas AS q ON q.id = e.queja_id AND  q.created_at > '2019-07-01'
			#WHERE e.estado != 2 
			GROUP BY p.id";*/

			$this->sql = "
			SELECT  p.id, CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat) AS full_name,  COUNT( DISTINCT(q.id) ) AS cuenta, a.nombre AS n_area
			FROM e_turnados AS e
			RIGHT JOIN quejas AS q ON q.id = e.queja_id 
			LEFT JOIN personal AS p ON p.id = e.persona_id
			LEFT JOIN areas AS a ON a.id = p.area_id
			WHERE q.created_at > '2019-07-01' $ta_wh
			GROUP by e.persona_id 
			ORDER BY cuenta ASC
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$resultado['abogados'] = $this->result;
			#Agregar las actas
			$this->sql = "
			SELECT a.t_actuacion AS ta, COUNT(a.id) AS cuenta
			FROM actas AS a 
			WHERE fecha > '2019-07-01'
			GROUP BY a.t_actuacion
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$resultado['actas'] = $this->result;
			#CONECTAR A LA OTRA BD 
			#$pass = '7W+Th_+uTh2X';
			$pass = '';
			$n_pdo = new PDO("mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8","root", $pass);
			$this->sql = "SELECT t_orden, COUNT( t_orden ) AS cuenta 
			FROM orden_inspeccion GROUP BY t_orden";
			$this->stmt = $n_pdo->prepare($this->sql);
			$this->stmt->execute();
			$ordenes = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$resultado['c_oins'] = $ordenes;

			return json_encode($resultado);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function operacionesFechas($tipo,$mayor,$menor)
	{
		try {
			if ($tipo == '-') {
				$this->sql = "SELECT DATEDIFF(?,?) AS resta";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$mayor,PDO::PARAM_STR);
				$this->stmt->bindParam(2,$menor,PDO::PARAM_STR);
				$this->stmt->execute();
				$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
			}
			return $this->result;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getTblCtrlSubd()
	{
		try {
			session_start();
			#Buscar a que subdireccion pertenece Policial o no policial 
			$perfil = $_SESSION['perfil'];
			$wh = ' 1=1 AND q.created_at > "2019-07-01" ';
			$wh2 = '';
			if ( $perfil == 'QDP' ) {
				$wh .= ' AND t_asunto = 1';
				$wh2 .= ' AND area_id = 7';
			}elseif ( $perfil == 'QDNP' ) {
				$wh .= ' AND t_asunto = 2';
				$wh2 .= ' AND area_id = 8';
			}
			$resultado = array();
			#Contar los expedientes por estado guarda 
			$this->sql = "SELECT q.estado, e.nombre AS n_estado,COUNT(q.id) as cuenta 
			FROM quejas AS q 
			INNER JOIN estado_guarda AS e ON e.id = q.estado
			WHERE $wh
			GROUP BY q.estado";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$resultado['edos'] = $this->result;
			$this->sql = "SELECT p.id,CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat) AS full_name,COUNT(e.persona_id) AS cuenta 
			FROM e_turnados AS e INNER JOIN personal AS p ON p.id = e.persona_id
			WHERE e.estado != 2 $wh2
			GROUP BY p.id";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$resultado['abogados'] = $this->result;
			return json_encode($resultado);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getExpedientesTC()
	{
		try {
			session_start();
			$t_asunto = "";
			if ( $_SESSION['nivel'] == 'SUBDIRECTOR' ) {
				if ($_SESSION['perfil'] == 'QDP') {
					$t_asunto  = " AND q.t_asunto = 1";
				}
				elseif ($_SESSION['perfil'] == 'ESPECIAL') {
					$t_asunto  = " AND q.t_asunto = 2";
				}
			}else{
				$t_asunto = "";
			}
			
			$t = $_POST['tipo'];
			$v = $_POST['valor'];
			if ( $t == 'abogado') {
				$this->sql = "SELECT DISTINCT(queja_id) FROM e_turnados WHERE persona_id = $v";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->execute();
				$ids = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux = array();
				foreach ($ids as $key => $v) {
					array_push($aux, $v->queja_id);
				}
				$ids = implode(',', $aux);
			}
			if ( $t == 'estado') {
				$this->sql = "SELECT id FROM quejas WHERE estado = $v";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->execute();
				$ids = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux = array();
				foreach ($ids as $key => $v) {
					array_push($aux, $v->id);
				}
				$ids = implode(',', $aux);
				#echo $ids;exit;
			}
			$this->sql = "SELECT q.*,p.nombre as n_procedencia, e.nombre as n_estado ,
			et.f_turno,et.t_tramite
			FROM quejas AS q
			LEFT JOIN procedencias AS p ON p.id =q.procedencia
			LEFT JOIN estado_guarda AS e ON e.id = q.estado 
			LEFT JOIN e_turnados AS et ON et.queja_id = q.id AND et.estado != 2 
			WHERE q.id IN ($ids) AND q.created_at > '2019-07-01' $t_asunto ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			#agregar contador de dias 
			$quejas = array();
			foreach ($this->result as $key => $queja) {
				$aux = array(); 
				$aux['id'] = $queja->id;
				$aux['cve_exp'] = $queja->cve_exp;
				$aux['n_estado'] = $queja->n_estado;
				$aux['n_procedencia'] = $queja->n_procedencia;
				$aux['t_asunto'] = $queja->t_asunto;
				$aux['t_tramite'] = $queja->t_tramite;
				$aux['f_turno'] = $queja->f_turno;
				#contador de dias 
				if ($queja->estado == 1) {#tramite
					$sql_diff = "SELECT DATEDIFF(DATE(NOW()),?) AS dias ";
					$this->stmt = $this->pdo->prepare($sql_diff);
					$this->stmt->bindParam(1,$queja->f_turno,PDO::PARAM_STR);
					$this->stmt->execute();
					$dias = $this->stmt->fetch(PDO::FETCH_OBJ)->dias;	
					$aux['dias_t'] = $dias;	
				}
				if ($queja->estado == 3) {#archivo
					$sql_diff = "SELECT DATEDIFF(DATE(NOW()),?) AS dias ";
					$this->stmt = $this->pdo->prepare($sql_diff);
					$this->stmt->bindParam(1,$queja->f_turno,PDO::PARAM_STR);
					$this->stmt->execute();
					$dias = $this->stmt->fetch(PDO::FETCH_OBJ)->dias;	
					$aux['dias_t'] = $dias;	
				}
				if ($queja->estado == 4) {#incompe
					$sql_diff = "SELECT DATEDIFF(DATE(NOW()),?) AS dias ";
					$this->stmt = $this->pdo->prepare($sql_diff);
					$this->stmt->bindParam(1,$queja->f_turno,PDO::PARAM_STR);
					$this->stmt->execute();
					$dias = $this->stmt->fetch(PDO::FETCH_OBJ)->dias;	
					$aux['dias_t'] = $dias;	
				}
				if ($queja->estado == 8) {#respo
					$sql_r = "SELECT f_acuse FROM quejas_respo WHERE queja_id=? ";
					$this->stmt = $this->pdo->prepare($sql_r);
					$this->stmt->bindParam(1,$queja->id,PDO::PARAM_INT);
					$this->stmt->execute();
					$f_acuse = $this->stmt->fetch(PDO::FETCH_OBJ);	
					if( $this->stmt->rowCount() > 0 ){
						$f_acuse = $f_acuse->f_acuse;
					}else{
						$f_acuse = date('Y-m-d');
					}

					$sql_diff = "SELECT DATEDIFF(?,?) AS dias ";
					$this->stmt = $this->pdo->prepare($sql_diff);
					$this->stmt->bindParam(1,$f_acuse,PDO::PARAM_STR);
					$this->stmt->bindParam(2,$queja->f_turno,PDO::PARAM_STR);
					$this->stmt->execute();
					$dias = $this->stmt->fetch(PDO::FETCH_OBJ)->dias;	
					$aux['dias_t'] = $dias;	
				}
				if ($queja->estado == 9) {#prescrito
					
					$sql_diff = "SELECT DATEDIFF(DATE(NOW()),?) AS dias ";
					$this->stmt = $this->pdo->prepare($sql_diff);
					$this->stmt->bindParam(1,$queja->f_turno,PDO::PARAM_STR);
					$this->stmt->execute();
					$dias = $this->stmt->fetch(PDO::FETCH_OBJ)->dias;	
					$aux['dias_t'] = $dias;	
				}
				if ($queja->estado == 10) {#reserva
					$sql_fr = "SELECT f_reserva FROM reservas WHERE queja_id=? ";
					$this->stmt = $this->pdo->prepare($sql_fr);
					$this->stmt->bindParam(1,$queja->id,PDO::PARAM_INT);
					$this->stmt->execute();
					$f_reserva = $this->stmt->fetch(PDO::FETCH_OBJ);	
					if( $this->stmt->rowCount() > 0 ){
						$f_reserva = $f_reserva->f_reserva;
					}
					$sql_diff = "SELECT DATEDIFF(?,?) AS dias ";
					$this->stmt = $this->pdo->prepare($sql_diff);
					$this->stmt->bindParam(1,$f_reserva,PDO::PARAM_STR);
					$this->stmt->bindParam(2,$queja->f_turno,PDO::PARAM_STR);
					$this->stmt->execute();
					$dias = $this->stmt->fetch(PDO::FETCH_OBJ)->dias;	
					$aux['dias_t'] = $dias;	
				}
				if ($queja->estado == 11) {#improcedencia
					#
					$sql_fai = "SELECT f_acuerdo FROM acuerdos_improcedencia WHERE queja_id=? ";
					$this->stmt = $this->pdo->prepare($sql_fai);
					$this->stmt->bindParam(1,$queja->id,PDO::PARAM_INT);
					$this->stmt->execute();
					if ($this->stmt->rowCount() > 0) {
						$f_acuerdo = $this->stmt->fetch(PDO::FETCH_OBJ)->f_acuerdo;	
					}else{
						$f_acuerdo = "";
					}
					

					$sql_diff = "SELECT DATEDIFF(?,?) AS dias ";
					$this->stmt = $this->pdo->prepare($sql_diff);
					$this->stmt->bindParam(1,$f_acuerdo,PDO::PARAM_STR);
					$this->stmt->bindParam(2,$queja->f_turno,PDO::PARAM_STR);
					$this->stmt->execute();
					$dias = $this->stmt->fetch(PDO::FETCH_OBJ)->dias;	
					$aux['dias_t'] = $dias;	
				}
				array_push($quejas, $aux);
			}
			return json_encode($quejas);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getTR()
	{
		try {
			$this->sql = "SELECT * FROM tipos_referencia ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getCargos()
	{
		try {
			$this->sql = "SELECT * FROM cargos ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getAgrupamientos()
	{
		try {
			$this->sql = "SELECT * FROM catalogo_agrupamientos ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getRegiones()
	{
		try {
			$this->sql = "SELECT * FROM catalogo_regiones ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getSubdirecciones()
	{
		try {
			$this->sql = "SELECT * FROM catalogo_subdirecciones ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getCoordinacionesTra()
	{
		try {
			$this->sql = "SELECT * FROM catalogo_transito_coordinacion ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getAgrupamientosTra()
	{
		try {
			$this->sql = "SELECT * FROM catalogo_transito_agrupamieno ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getDireccionAdmin()
	{
		try {
			$this->sql = "SELECT * FROM catalogo_nivel1_admin ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getUnidadAdmin()
	{
		try {
			$this->sql = "SELECT * FROM catalogo_nivel2_admin ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getDirAreasAdmin()
	{
		try {
			$this->sql = "SELECT * FROM catalogo_nivel3_admin ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getSubdirAreasAdmin()
	{
		try {
			$this->sql = "SELECT * FROM catalogo_nivel4_admin ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getDepartamentosAdmin()
	{
		try {
			$this->sql = "SELECT * FROM catalogo_nivel5_admin ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function getDependenciasF()
	{
		try {
			$this->sql = "SELECT id, nombre FROM organismos_f ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getExpList($arreglo)
	{
		try {
			$ids = implode(',', $arreglo);
			$this->sql = "SELECT id, cve_exp FROM quejas WHERE id IN ($ids);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return $this->result;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function getQDs()
	{
		try {
			session_start();
			$perfil = "";
			
			$quejas = array();
			$anexgrid = new AnexGrid();
			$wh = " 1=1 AND DATE(q.created_at) > '2019-07-01' ";
			if ( $_SESSION['perfil'] == "QDP") {
				$wh .= " AND q.t_asunto = 1";
			}elseif ($_SESSION['perfil'] == "ESPECIAL") {
				$wh .= " AND q.t_asunto = 2";
			}

			#Para los niveles
			if ( $_SESSION['nivel'] == "ANALISTA") {
				$wh .= " AND et.persona_id = ".$_SESSION['id'];
			}elseif ( $_SESSION['nivel'] == "SUBDIRECTOR" || $_SESSION['nivel'] == "DIRECTOR") {
				$wh .= " ";
			}elseif ($_SESSION['nivel'] == "JEFE" ) {
				# Buscar las personas que tiene a su cargo
				$area = $_SESSION['area_id'];
				$this->sql = "SELECT * FROM personal WHERE area_id = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$area,PDO::PARAM_INT);
				$this->stmt->execute();
				$empleados = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux = array();
				foreach ($empleados as $key => $e) {
					array_push($aux, $e->id);
				}
				$aux = implode(',', $aux);
				$wh .= " AND et.persona_id IN ($aux) ";
			}
			#Los filtros 
			foreach ($anexgrid->filtros as $filter) {
				if ( $filter['columna'] != '' ) {
					if ( $filter['columna'] == 'q.cve_ref' || $filter['columna'] == 'q.cve_exp' ) {
						$wh .= " AND ".$filter['columna']." LIKE '%".$filter['valor']."%'";
					}elseif ($filter['columna'] == 'pe.nombre') {
						$wh .= " AND CONCAT(pe.nombre,' ',pe.ap_pat,' ',pe.ap_mat) LIKE '%".$filter['valor']."%'";
					}elseif ($filter['columna'] == 'a.nombre') {
						$wh .= " AND a.nombre LIKE '%".$filter['valor']."%'";
					}else{
						$wh .= " AND ".$filter['columna'] ." = ".$filter['valor'];
					}
				}
			}
			$this->sql = "SELECT q.*,DATE(q.created_at) AS f_alta, UPPER(m.nombre) AS municipio,
			p.nombre AS procedencia, e.nombre AS n_estado, e.id AS edo_id,
			DATEDIFF(DATE(NOW()),DATE(q.created_at) ) AS fase,
			ev.estado AS visto
			FROM quejas AS q
			INNER JOIN ubicacion_referencia AS u ON u.queja_id = q.id
			INNER JOIN municipios AS m ON m.id = u.municipio
			INNER JOIN procedencias AS p ON p.id = q.procedencia
			LEFT JOIN estado_guarda AS e ON e.id = q.estado
			LEFT JOIN e_vistos AS ev ON ev.queja_id = q.id
			WHERE $wh /*AND et.estado = 1*/  ORDER BY q.$anexgrid->columna $anexgrid->columna_orden LIMIT $anexgrid->pagina , $anexgrid->limite";

		    #echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			#La cuenta de datos 
			$this->sql = "SELECT COUNT(*) AS total
			FROM quejas AS q
			INNER JOIN ubicacion_referencia AS u ON u.queja_id = q.id
			INNER JOIN municipios AS m ON m.id = u.municipio
			INNER JOIN procedencias AS p ON p.id = q.procedencia
			LEFT JOIN estado_guarda AS e ON e.id = q.estado
			LEFT JOIN e_vistos AS ev ON ev.queja_id = q.id
			
			WHERE $wh /*AND et.estado = 1*/  ";
		    #echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$total = $this->stmt->fetch(PDO::FETCH_OBJ)->total;
			#$total = $this->stmt->rowCount();
			#echo $total;exit;
			
			foreach ($this->result as $key => $qd) {
				$aux = array();
				$aux['id'] = $qd->id;
				$aux['cve_ref'] = $qd->cve_ref;
				$aux['cve_exp'] = $qd->cve_exp;
				$aux['h_hechos'] = $qd->h_hechos;
				$aux['f_hechos'] = $qd->f_hechos;
				$aux['municipio'] = $qd->municipio;
				$aux['procedencia'] = $qd->procedencia;
				$aux['n_estado'] = $qd->n_estado;
				$aux['edo_id'] = $qd->edo_id;
				$aux['fase'] = $qd->fase;
				$aux['multiple_id'] = $qd->multiple_id;
				//$aux['full_name'] = $qd->full_name;
				$aux['visto'] = $qd->visto;
				#$aux['t_tramite'] = $qd->t_tramite;
				#$aux['f_turno'] = $qd->f_turno;
				#$aux['turno_id'] = $qd->turno_id;
				#$aux['n_area'] = $qd->n_area;
				$aux['f_alta'] = $qd->f_alta;
				#Buscar los turnos del expediente
				$sql_tur = "
				SELECT e.id, e.f_turno, CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat) AS turno , 
				a.nombre AS n_area, CONCAT(p2.nombre,' ',p2.ap_pat,' ',p2.ap_mat) AS jefe 
				FROM e_turnados AS e 
				INNER JOIN personal AS p ON p.id = e.persona_id
				INNER JOIN personal AS p2 ON p2.id = e.jefe_id
				INNER JOIN areas AS a ON a.id = p.area_id
				WHERE e.queja_id = :queja_id AND e.estado = 1  ORDER BY e.id DESC LIMIT 1
				";
				$this->stmt = $this->pdo->prepare($sql_tur);
				$this->stmt->bindParam(':queja_id',$qd->id,PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ) {
					$turno = $this->stmt->fetch(PDO::FETCH_OBJ);
					$aux['full_name'] = $turno->turno;
					$aux['jefe'] = $turno->jefe;
					$aux['n_area'] = $turno->n_area;
					$aux['turno_id'] = $turno->id;
					$aux['f_turno'] = $turno->f_turno;
				}else{
					$aux['full_name'] = "NO ASIGNADO";
					$aux['jefe'] = 'NO ASIGNADO';
					$aux['n_area'] = "NO ASIGNADO";
					$aux['turno_id'] = "";
					$aux['f_turno'] = "";
				}
				
				#Buscar las presuntas conductas de cada expediente
				$sql_conductas = "SELECT cc.id,cc.nombre  FROM p_conductas AS pc
				INNER JOIN catalogo_conductas AS cc ON cc.id = pc.conducta_id
				WHERE pc.queja_id = ?";
				$this->stmt = $this->pdo->prepare($sql_conductas);
				$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$conductas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['conductas'] = $conductas;
				#Buscar la cantidad de dias que tiene el expediente
				$sql_resta = "SELECT DATEDIFF(DATE(NOW()),?) AS resta";
				$this->stmt = $this->pdo->prepare($sql_resta);
				$this->stmt->bindParam(1,$qd->f_hechos,PDO::PARAM_STR);
				$this->stmt->execute();
				$resta = $this->stmt->fetch(PDO::FETCH_OBJ)->resta;
				$aux['resta'] = $resta;
				#contador de dias desde turnado
				if ( $qd->t_tramite == 'REASIGNADO' ) {
					$sql_reasig = "SELECT DATEDIFF(DATE(NOW()),?) AS reasig";
					$this->stmt = $this->pdo->prepare($sql_reasig);
					$this->stmt->bindParam(1,$qd->f_turno,PDO::PARAM_STR);
					$this->stmt->execute();
					$reasig = $this->stmt->fetch(PDO::FETCH_OBJ)->reasig;
					$aux['f_reasignado'] = $reasig;	
				}else{
					$aux['f_reasignado'] = ' NO REASIGNADO ';
				}
				array_push($quejas, $aux);
			}
			return $anexgrid->responde($quejas,$total);

		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}

	public function getProcedencias()
	{
		try {
			$wh = "";
			if ( isset( $_POST['data']) ) {
				if (!empty($_POST['data'])) {
					$t_asunto = $_POST['data'];
					$wh .= " AND t_asunto = $t_asunto";
				}
			}
			
			$this->sql = "SELECT * FROM procedencias WHERE 1=1 $wh;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getProcedenciasP()
	{
		try {
			$this->sql = "SELECT * FROM adscripciones_p";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function getTT()
	{
		try {
			$this->sql = "SELECT * FROM tipos_tramite WHERE estado = 1";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getLeyes()
	{
		try {
			
			$this->sql = "SELECT * FROM leyes";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getArticulos()
	{
		try {
			
			$wh = "";
			if ( isset($_POST['data']) && !empty($_POST['data']) ) {
				$wh .= " cap = ".$_REQUEST['data'];
			}else{
				$wh .= " 1=1 ";
			}
			$this->sql = "
			SELECT id, UPPER(CONCAT(numero,'.- ',nombre)) AS nombre 
			FROM articulos WHERE $wh 
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getSecciones()
	{
		try {
			$wh = "";
			$capitulo = $_POST['data'];
			$wh .=  (isset($_POST['art'])) ? "AND articulo = ".$_POST['art'] : '';
			$this->sql = "
			SELECT seccion AS id, seccion AS nombre 
			FROM catalogo_conductas
			WHERE cap = $capitulo $wh 
			GROUP BY seccion
			";
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getFracciones()
	{
		try {
			$ley = $_POST['data'];
			$art = $_POST['art'];
			$sec = $_POST['sec'];
			$this->sql = "
			SELECT fraccion as id, upper(fraccion) as nombre FROM catalogo_conductas 
			WHERE cap = $ley AND articulo = $art  AND seccion LIKE '$sec' 
			GROUP BY fraccion
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			if ($this->stmt->rowCount() > 0) {
				return json_encode($this->result);
			}else{
				throw new Exception("SIN RESULTADO", 1);				
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	
	public function getConductas()
	{
		try {
			$wh = "";
			if ( isset($_POST['art']) && !empty($_POST['art']) ) {
				$wh .= " AND articulo =".$_POST['art'];
			}
			if ( isset($_POST['data']) && !empty($_POST['data']) ) {
				$wh .= " AND cap =".$_POST['data'];
			}
			if ( isset($_POST['sec']) && !empty($_POST['sec']) ) {
				$wh .= " AND seccion LIKE '".$_POST['sec']."'";
			}
			if ( isset($_POST['fra']) && !empty($_POST['fra']) ) {
				$wh .= " AND fraccion LIKE '".$_POST['fra']."'";
			}
			
			$this->sql = "SELECT id, UPPER(nombre) as nombre  
			FROM catalogo_conductas WHERE 1=1 $wh ";
			#echo($this->sql);exit;
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getVias()
	{
		try {
			$this->sql = "SELECT id, nombre FROM catalogo_vias ORDER BY nombre ASC";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getMunicipios()
	{
		try {
			$this->sql = "SELECT id, UPPER(nombre) AS nombre FROM municipios";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getPersonal()
	{
		try {
			session_start();
			$area_id 	= $_SESSION['area_id'];
			$nivel 		= trim($_SESSION['nivel']);
			$areas = "";
			/*if (strcmp('SUBDIRECTOR', $nivel) === 0) {
				if ($area_id >= '7'|| $area_id <= '9') {
					$areas = " AND area_id IN (7,8,9)";
				}elseif ($area_id >= '10' || $area_id <= '12') {
					$areas = " AND area_id IN (10,11,12)";
				}
			}*/
			
			$text = "%".$_REQUEST['term']."%";
			$this->sql = "SELECT id , CONCAT(nombre,' ',ap_pat,' ',ap_mat) AS value 
			FROM personal
			WHERE CONCAT(nombre,' ',ap_pat,' ',ap_mat) LIKE ? AND estado = 1 $areas";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$text);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getEstadosGuarda()
	{
		try {
			$this->sql = "SELECT * FROM estado_guarda WHERE estado = 1 ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getColores()
	{
		try {
			$this->sql = "SELECT * FROM colores ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function delete_turno()
	{
		try {
			$this->sql = "DELETE FROM e_turnados WHERE id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$_POST['turno'],PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'TURNO ELIMINADO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function deleteVia()
	{
		try {
			$this->sql = "DELETE FROM vias_recepcion WHERE id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$_POST['via'],PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'VÍA DE RECEPCIÓN DESASIGNADA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function deleteFile()
	{
		try {
			$this->sql = "DELETE FROM documentos_quejas WHERE id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$_POST['file'],PDO::PARAM_INT);
			$this->stmt->execute();
			if ($this->stmt->rowCount() > 0) {
				session_start();
				$logger = $_SESSION['id'];
				$desc = "SE A ELIMINADO UN DOCUMENTO DE UN EXPEDIENTE.";
				$sis = 1;
				$sql_pista = "INSERT INTO 
				pista_auditoria (id, descripcion,person_id,tipo, sistema) 
				VALUES 
				('',?,?,4,?);";
				$stmt = $this->pdo->prepare($sql_pista);
				$stmt->bindParam(1,$desc,PDO::PARAM_STR);
				$stmt->bindParam(2,$logger,PDO::PARAM_INT);
				$stmt->bindParam(3,$sis,PDO::PARAM_INT);
				$stmt->execute();
				return json_encode( array('status'=>'success','message'=>'ARCHIVO ELIMINADO DE MANERA EXITOSA.' ) );
			}else{
				throw new Exception("Ocurrio un error al tratar de eliminar.", 1);
			}
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function delete_conducta()
	{
		try {
			if (!isset($_POST['presunta'])) {
				throw new Exception("No se recibio el Identificador de una conducta.", 1);
			}
			$this->sql = "DELETE FROM p_conductas WHERE id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$_POST['presunta'],PDO::PARAM_INT);
			$this->stmt->execute();
			if ($this->stmt->rowCount() > 0) {
				session_start();
				$logger = $_SESSION['id'];
				$desc = "";
				$sis = 1;
				$sql_pista = "INSERT INTO 
				pista_auditoria (id, descripcion,person_id,tipo, sistema) 
				VALUES 
				('',?,?,4,?);";
				$stmt = $this->pdo->prepare($sql_pista);
				$stmt->bindParam(1,$desc,PDO::PARAM_STR);
				$stmt->bindParam(2,$logger,PDO::PARAM_INT);
				
				$stmt->bindParam(3,$sis,PDO::PARAM_INT);
				$stmt->execute();
				return json_encode( array('status'=>'success','message'=>'PROBABLE CONDUCTA ELIMINADA DE MANERA EXITOSA.' ) );
			}else{
				throw new Exception("Ocurrio un error al tratar de eliminar.", 1);
			}
			#Insertar en la pista de auditoria
			
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function deletePresunto()
	{
		try {
			$queja_id = $_POST['queja_id'];
			if (!isset($_POST['presunto'])) {
				throw new Exception("No se recibio el Identificador de una conducta.", 1);
			}
			$this->sql = "DELETE FROM presuntos WHERE id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$_POST['presunto'],PDO::PARAM_INT);
			$this->stmt->execute();
			#Pistas de auditoria
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			if ($this->stmt->rowCount() > 0) {
				session_start();
				$logger = $_SESSION['id'];
				$desc = "SE A ELIMINADO UN PRESUNTO RESPONSABLE DE LA QUEJA $queja_id";
				$sis = 1;#QUEJAS Y DENUNCIAS
				$sql_pista = "INSERT INTO 
				pista_auditoria (id, descripcion,person_id,tipo, sistema) 
				VALUES 
				('',?,?,4,?);";
				$stmt = $this->pdo->prepare($sql_pista);
				$stmt->bindParam(1,$desc,PDO::PARAM_STR);
				$stmt->bindParam(2,$logger,PDO::PARAM_INT);
				
				$stmt->bindParam(3,$sis,PDO::PARAM_INT);
				$stmt->execute();
				return json_encode( array('status'=>'success','message'=>'TURNO ELIMINADO DE MANERA EXITOSA.' ) );
			}else{
				throw new Exception("Ocurrio un error al tratar de eliminar.", 1);
			}
			#Insertar en la pista de auditoria
			
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function deleteUnidad()
	{
		try {
			$queja_id = $_POST['queja_id'];
			if (!isset($_POST['unidad'])) {
				throw new Exception("No se recibio el Identificador de una unidad.", 1);
			}
			$this->sql = "DELETE FROM u_implicadas WHERE id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$_POST['unidad'],PDO::PARAM_INT);
			$this->stmt->execute();
			#Pistas de auditoria
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			if ($this->stmt->rowCount() > 0) {
				session_start();
				$logger = $_SESSION['id'];
				$desc = "SE A ELIMINADO LA UNIDAD IMPLICADA DE LA QUEJA $queja_id";
				$sis = 1;#QUEJAS Y DENUNCIAS
				$sql_pista = "INSERT INTO 
				pista_auditoria (id, descripcion,person_id,tipo, sistema) 
				VALUES 
				('',?,?,4,?);";
				$stmt = $this->pdo->prepare($sql_pista);
				$stmt->bindParam(1,$desc,PDO::PARAM_STR);
				$stmt->bindParam(2,$logger,PDO::PARAM_INT);
				
				$stmt->bindParam(3,$sis,PDO::PARAM_INT);
				$stmt->execute();
				return json_encode( array('status'=>'success','message'=>'VEHÍCULO ELIMINADO DE MANERA EXITOSA.' ) );
			}else{
				throw new Exception("Ocurrio un error al tratar de eliminar.", 1);
			}
			#Insertar en la pista de auditoria
			
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function deleteQuejoso()
	{
		try {
			$queja_id = $_POST['queja_id'];
			if (!isset($_POST['quejoso'])) {
				throw new Exception("No se recibio el Identificador del quejoso.", 1);
			}
			$this->sql = "DELETE FROM quejosos WHERE id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$_POST['quejoso'],PDO::PARAM_INT);
			$this->stmt->execute();
			#Pistas de auditoria
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			if ($this->stmt->rowCount() > 0) {
				session_start();
				$logger = $_SESSION['id'];
				$desc = "SE A ELIMINADO UN QUEJOSO DE LA QUEJA $queja_id";
				$sis = 1;#QUEJAS Y DENUNCIAS
				$sql_pista = "INSERT INTO 
				pista_auditoria (id, descripcion,person_id,tipo, sistema) 
				VALUES 
				('',?,?,4,?);";
				$stmt = $this->pdo->prepare($sql_pista);
				$stmt->bindParam(1,$desc,PDO::PARAM_STR);
				$stmt->bindParam(2,$logger,PDO::PARAM_INT);
				
				$stmt->bindParam(3,$sis,PDO::PARAM_INT);
				$stmt->execute();
				return json_encode( array('status'=>'success','message'=>'QUEJOSO ELIMINADO DE MANERA EXITOSA.' ) );
			}else{
				throw new Exception("Ocurrio un error al tratar de eliminar.", 1);
			}
			#Insertar en la pista de auditoria
			
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function getCode()
	{
		try {

			$tt = $_POST['tt'];
			$this->sql = "SELECT nombre AS sn FROM tipos_tramite WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$tt,PDO::PARAM_INT);
			$this->stmt->execute();
			$sn = $this->stmt->fetch(PDO::FETCH_OBJ);
			$this->sql = "SELECT id, cve_exp FROM quejas WHERE t_tramite = :tt ORDER BY id DESC LIMIT 1";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(':tt',$tt, PDO::PARAM_INT);
			$this->stmt->execute();
			if ( $this->stmt->rowCount() > 0  ) {
				$total = $this->stmt->fetch(PDO::FETCH_OBJ);
				$total = explode('/', $total->cve_exp)[3];
				$total++;
				if ($total>=1 && $total <= 9) { $total = "000".$total;  }
				if ($total>=10 && $total <= 99) { $total = "00".$total;  }
				if ($total>=100 && $total <= 999) { $total = "0".$total;  }
				if ($total>=1000 && $total <= 9999) { $total = $total;  }
				$this->result = "UAI/EDOMEX/".$sn->sn."/".$total."/".date('Y');
			}else{
				$total = 1;
				$this->result = "UAI/EDOMEX/".$sn->sn."/".$total."/".date('Y');
			}
			return $this->result;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function saveTR()
	{
		try {
			if ( !isset($_POST['n_ref']) ) {
				throw new Exception("NO EXISTE EL NOMBRE DEL TIPO DE REFERENCIA.", 1);
			}
			if ( empty($_POST['n_ref']) ) {
				throw new Exception("NO PUEDE IR VACIO EL NOMBRE DEL TIPO DE REFERENCIA.", 1);
			}
			$nombre = mb_strtoupper($_POST['n_ref'],'utf-8') ;
			$this->sql = "INSERT INTO tipos_referencia (id,nombre) VALUES (NULL,?);";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$nombre,PDO::PARAM_STR);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'TIPO DE REFERENCIA AGREGADA CORRECTAMENTE.' ) );
		} catch (Exception $e) {
			if( $e->getCode() == '23000' ){
				return json_encode( array('status'=>'error','message'=>'EL TIPO DE REFERENCIA YA EXISTE.') );
			}else{
				return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
			}
		}
	}
	public function saveCargo()
	{
		try {
			if ( !isset($_POST['cargo']) ) {
				throw new Exception("NO EXISTE EL NOMBRE DEL TIPO DE REFERENCIA.", 1);
			}
			if ( empty($_POST['cargo']) ) {
				throw new Exception("NO EXISTE EL NOMBRE DEL TIPO DE REFERENCIA.", 1);
			}
			$nombre = mb_strtoupper($_POST['cargo'],'utf-8') ;
			$this->sql = "INSERT INTO cargos (id,nombre) VALUES ('',?);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$nombre,PDO::PARAM_STR);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'NOMBRE DEL CARGO AGREGADO CORRECTAMENTE.' ) );
		} catch (Exception $e) {
			if( $e->getCode() == '23000' ){
				return json_encode( array('status'=>'error','message'=>'EL TIPO DE CARGO YA EXISTE.') );
			}else{
				return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
			}
		}
	}
	public function saveProcedenciasP()
	{
		try {
			if ( !isset($_POST['nombre']) ) {
				throw new Exception("NO EXISTE EL NOMBRE DEL TIPO DE REFERENCIA.", 1);
			}
			if ( empty($_POST['nombre']) ) {
				throw new Exception("NO EXISTE EL NOMBRE DEL TIPO DE REFERENCIA.", 1);
			}
			$nombre = mb_strtoupper($_POST['nombre'],'utf-8') ;
			$this->sql = "INSERT INTO adscripciones_p (id,nombre) VALUES ('',?);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$nombre,PDO::PARAM_STR);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'NOMBRE DEL CARGO AGREGADO CORRECTAMENTE.' ) );
		} catch (Exception $e) {
			if( $e->getCode() == '23000' ){
				return json_encode( array('status'=>'error','message'=>'EL TIPO DE CARGO YA EXISTE.') );
			}else{
				return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
			}
		}
	}
	
	public function saveUnidad()
	{
		try {
			$queja_id 		=( !empty($_POST['queja_id']) ) ? $_POST['queja_id'] : NULL;
			$procedencia 	=( !empty($_POST['procedencia']) ) ? $_POST['procedencia'] : NULL;
			$t_vehiculo  	=( !empty($_POST['t_vehiculo']) ) ? $_POST['t_vehiculo'] : NULL;
			$n_eco 			=( !empty($_POST['n_eco']) ) ? $_POST['n_eco'] : NULL;
			$placas 		=( !empty($_POST['placas']) ) ? $_POST['placas'] : NULL;
			$color 			=( !empty($_POST['color']) ) ? $_POST['color'] : NULL;
			$comentarios 	= mb_strtoupper($_POST['comentarios'],'utf-8');
			$this->sql = "INSERT INTO u_implicadas 
			(id,queja_id,procedencia,t_vehiculo,color,n_eco,placas,comentario) 
			VALUES 
			('',?,?,?,?,?,?,?);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id);
			$this->stmt->bindParam(2,$procedencia);
			$this->stmt->bindParam(3,$t_vehiculo);
			$this->stmt->bindParam(4,$color);
			$this->stmt->bindParam(5,$n_eco);
			$this->stmt->bindParam(6,$placas);
			$this->stmt->bindParam(7,$comentarios,PDO::PARAM_STR);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'UNIDAD IMPLICADA AGREGADA CORRECTAMENTE.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	
	public function saveDoc()
	{
		try {

			#Validacion de los campos
			if ( $_FILES['file']['error'] > 0 ) {
				throw new Exception("DEBE DE SELECCIONAR UN DOCUMENTO.", 1);
			}
			if ( $_FILES['file']['size'] > 10485760 ) {
				throw new Exception("EL DOCUMENTO EXCEDE EL TAMAÑO DE ARCHIVO ADMITIDO.", 1);	
			}
			if ( $_FILES['file']['type'] != 'application/pdf' ) {
				throw new Exception("EL FORMATO DE ARCHIVO NO ES ADMITIDO (SOLO PDF). ", 1);
			}
			
			#Recuperar las variables necesarias
			$doc_name = $_FILES['file']['name'];
			$doc_type = $_FILES['file']['type'];
			$doc_size = $_FILES['file']['size'];
			$destino  = $_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/';

			$name 		 	= mb_strtoupper($_POST['name_file'],'utf-8');
			$comentario		= mb_strtoupper($_POST['comentario'],'utf-8');
			$queja_id		= $_POST['queja_id'];
			#echo $destino.$doc_name;exit;
			#Mover el Doc
			move_uploaded_file($_FILES['file']['tmp_name'],$destino.$doc_name);
			#abrir el archivo
			$file 		= fopen($destino.$doc_name,'r');
			$content 	= fread($file, $doc_size);
			$content 	= addslashes($content);
			fclose($file);
			
			#Eliminar  el archivo 
			unlink($_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/'.$doc_name);			
			#------------------------------------------------------------
			$this->sql = "INSERT INTO documentos_quejas 
			(id,queja_id,nombre,descripcion,archivo) 
			VALUES ('',?,?,?,?);";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$name,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$content,PDO::PARAM_LOB);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'DOCUMENTO ALMACENADO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			if( $e->getCode() == '23000' ){
				return json_encode( array('status'=>'error','message'=>'EL TIPO DE REFERENCIA YA EXISTE.') );
			}else{
				return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
			}
		}
	}

	public function saveQueja()
	{
		try {
			$insertados = array();
			$t_asunto = $_POST['t_asunto'];
			#print_r($_POST['pro'][1]);exit;
			#Valida el tipo de referencia 
			if( isset($_POST['t_ref']) ){
				$t_ref = $_POST['t_ref'];
				if( !empty($_POST['t_ref']) ){
					$cve_ref = mb_strtoupper($_POST['cve_ref'],'utf-8');
					$n_turno = mb_strtoupper($_POST['n_turno'],'utf-8');
				}else{
					$t_ref = NULL;
					$cve_ref = NULL;
					$n_turno = NULL;
				}
			}else{
				$t_ref = NULL;
				$cve_ref = NULL;
				$n_turno = NULL;
			}
			#validar el nuevo campo de acta administrativa 
			if ( isset($_POST['acta_admin']) ) {
				$acta_admin = $_POST['acta_admin'];
			}else{
				$acta_admin = NULL; 
			}

			if ( isset($_POST['t_tra']) && !empty($_POST['t_tra']) ) {
				$t_tramite = $_POST['t_tra'];
			}else{
				throw new Exception("DEBE DE ELEGIR UN TIPO DE TRÁMITE.", 1);
			}
			#echo $_POST['cve_exp'];exit;
			$cve_exp 	= $_POST['cve_exp'];
			$f_hechos 	= $_POST['f_hechos'];
			$h_hechos 	= $_POST['h_hechos'];
			$genero 	= $_POST['genero'];
			#$t_afectado	= $_POST['t_afecta'];
			$t_afectado	= 0;
			$categoria	= $_POST['categoria'];
			$d_ano = (isset( $_POST['d_ano'] )) ? $_POST['d_ano'] : 2;
			//$comentario = mb_strtoupper($_POST['comentario'],'utf-8');
			$descripcion= mb_strtoupper($_POST['descripcion']);
			$prioridad 	= $_POST['prioridad'];
			$fojas 		= $_POST['fojas'];
			$evidencia 	= $_POST['evidencia'];
			$estado 	= $_POST['estado'];
			$procedencia 	= $_POST['procedencia'];
			#Recuperar y validar los datos de los semovientes 
			$add_semo = ( !empty($_POST['add_semo']) ) ? $_POST['add_semo'] : NULL;
			$t_animal = ( !empty($_POST['t_animal']) ) ? $_POST['t_animal'] : NULL;
			$raza = ( !empty($_POST['raza']) ) ? mb_strtoupper($_POST['raza'],'utf-8') : NULL;
			$edad = ( !empty($_POST['edad']) ) ? mb_strtoupper($_POST['edad'],'utf-8') : NULL;
			$color = ( !empty($_POST['color']) ) ? mb_strtoupper($_POST['color'],'utf-8') : NULL;
			$n_animal = ( !empty($_POST['n_animal']) ) ? mb_strtoupper($_POST['n_animal'],'utf-8') : NULL;
			$inventario = ( !empty($_POST['inventario']) ) ? mb_strtoupper($_POST['inventario'],'utf-8') : NULL;
			
			if( $_POST['pregunta'] == '1' ){
				$this->sql = "
				INSERT INTO quejas (
					id,
					t_asunto,
					ref_id,
					cve_ref,
					n_turno,
					t_tramite,
					cve_exp,
					f_hechos,
					h_hechos,
					genero,
					t_afectado,
					categoria,
					d_ano,
					
					descripcion,
					prioridad,
					fojas,
					evidencia,
					estado,
					procedencia,
					acta_admin
					) VALUES ( 
						'',
						?,
						?,
						?,
						?,
						?,
						?,
						?,
						?,
						?,
						?,
						?,
						
						?,
						?,
						?,
						?,
						?,
						?,
						?,
						?
						
					);
				";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$t_asunto,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$t_ref,PDO::PARAM_INT);
				$this->stmt->bindParam(3,$cve_ref,PDO::PARAM_STR);
				$this->stmt->bindParam(4,$n_turno,PDO::PARAM_STR);
				$this->stmt->bindParam(5,$t_tramite,PDO::PARAM_STR);
				$this->stmt->bindParam(6,$cve_exp,PDO::PARAM_STR);
				$this->stmt->bindParam(7,$f_hechos,PDO::PARAM_STR);
				$this->stmt->bindParam(8,$h_hechos,PDO::PARAM_STR);
				$this->stmt->bindParam(9,$genero,PDO::PARAM_INT);
				$this->stmt->bindParam(10,$t_afectado,PDO::PARAM_STR);
				$this->stmt->bindParam(11,$categoria,PDO::PARAM_STR);
				$this->stmt->bindParam(12,$d_ano,PDO::PARAM_STR);
				//$this->stmt->bindParam(13,$comentario,PDO::PARAM_STR);
				$this->stmt->bindParam(13,$descripcion,PDO::PARAM_STR);
				$this->stmt->bindParam(14,$prioridad,PDO::PARAM_STR);
				$this->stmt->bindParam(15,$fojas,PDO::PARAM_STR);
				$this->stmt->bindParam(16,$evidencia,PDO::PARAM_STR);
				$this->stmt->bindParam(17,$estado,PDO::PARAM_STR);
				$this->stmt->bindParam(18,$procedencia,PDO::PARAM_INT);
				$this->stmt->bindParam(19,$acta_admin,PDO::PARAM_INT|PDO::PARAM_NULL);
				$this->stmt->execute();
				#RECUPERAR EL ID DE LA QUEJA INSERTADO 
				$queja_id = $this->pdo->lastInsertId();
				#Insertar los datos del SEMOVIENTE (ANIMALES)
				if ($add_semo == '1') {
					$this->sql = "
					INSERT INTO semovientes (
					    id,
					    queja_id,
					    tipo,
					    raza,
					    edad,
					    color,
					    nombre,
					    inventario
						) VALUES (
							'',
							:queja_id,
							:tipo,
							:raza,
							:edad,
							:color,
							:nombre,
							:inventario
						);";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT|PDO::PARAM_NULL);
					$this->stmt->bindParam(':tipo',$t_animal,PDO::PARAM_INT|PDO::PARAM_NULL);
					$this->stmt->bindParam(':raza',$raza,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':edad',$edad,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':color',$color,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':nombre',$n_animal,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':inventario',$inventario,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->execute();
				}
				##Insertar el estado del expediente 
				$this->sql = "INSERT INTO e_vistos (id,queja_id,estado) VALUES ('',?,1);";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();

				unset($this->sql);
				unset($this->stmt);
				#Insertar un Quien resulte responsable 
				$this->sql 	= "INSERT INTO presuntos(
					    id,
					    queja_id,
					    nombre,
					    genero,
					    rfc,
					    curp,
					    cuip,
					    t_puesto,
					    procedencia,
					    cargo_id,					    
					    a_determina,
					    comentarios
						) VALUES (
							'', 
							:queja_id, 
							'QUIEN RESULTE RESPONSABLE',
							NULL,
							NULL,
							NULL,
							NULL,
							NULL,
							NULL,
							NULL,							
							2,
							NULL
						);";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':queja_id',$queja_id);
				$this->stmt->execute();
				unset($this->sql);
				unset($this->stmt);
				#DATOS DEL QUEJOSO
				if ( $d_ano == '2' ) {
					$name_quejoso = ( isset($_POST['name_quejoso']) && !empty($_POST['name_quejoso']) ) ? $_POST['name_quejoso'] : NULL ;
					$phone = ( isset($_POST['phone']) && !empty($_POST['phone']) ) ? $_POST['phone'] : NULL ;
					$mail = ( isset($_POST['mail']) && !empty($_POST['mail']) ) ? $_POST['mail'] : NULL ;
					$genero = ( isset($_POST['genero']) && !empty($_POST['genero']) ) ? $_POST['genero'] : NULL ;
					$municipio = ( isset($_POST['municipio']) && !empty($_POST['municipio']) ) ? $_POST['municipio'] : NULL ;
					$cp = ( isset($_POST['cp']) && !empty($_POST['cp']) ) ? $_POST['cp'] : NULL ;
					$n_int = ( isset($_POST['n_int']) && !empty($_POST['n_int']) ) ? $_POST['n_int'] : NULL ;
					$n_ext = ( isset($_POST['n_ext']) && !empty($_POST['n_ext']) ) ? $_POST['n_ext'] : NULL ;
					$n_calle = ( isset($_POST['n_calle']) && !empty($_POST['n_calle']) ) ? $_POST['n_calle'] : NULL ;
					$this->sql = "INSERT INTO quejosos 
					(id, queja_id, nombre,telefono,email,municipio_id,cp,n_int,n_ext,comentarios,genero) 
					VALUES 
					('',:queja_id,:nombre,:telefono,:email,:municipio_id,:cp,:n_int,:n_ext,:comentarios,:genero);";
					$this->stmt = $this->getPDO()->prepare($this->sql);
					$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
					$this->stmt->bindParam(':nombre',$name_quejoso,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':telefono',$phone,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':email',$mail,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':municipio_id',$municipio,PDO::PARAM_INT|PDO::PARAM_NULL);
					$this->stmt->bindParam(':cp',$cp,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':n_int',$n_int,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':n_ext',$n_ext,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':comentarios',$n_calle,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':genero',$genero,PDO::PARAM_INT|PDO::PARAM_NULL);
					$this->stmt->execute();		
				}
				unset($this->sql);
				unset($this->stmt);
				#Datos de unidad implicada
				$u_procedencia = ( isset($_POST['u_procedencia']) && !empty($_POST['u_procedencia']) ) ? $_POST['u_procedencia'] : NULL ;
				$t_vehiculo = ( isset($_POST['t_vehiculo']) && !empty($_POST['t_vehiculo']) ) ? $_POST['t_vehiculo'] : NULL ;
				$color = ( isset($_POST['color']) && !empty($_POST['color']) ) ? $_POST['color'] : NULL ;
				$n_eco = ( isset($_POST['n_eco']) && !empty($_POST['n_eco']) ) ? $_POST['n_eco'] : NULL ;
				$placas = ( isset($_POST['placas']) && !empty($_POST['placas']) ) ? $_POST['placas'] : NULL ;
				$serie = ( isset($_POST['serie']) && !empty($_POST['serie']) ) ? $_POST['serie'] : NULL ;
				$inventario = ( isset($_POST['inventario']) && !empty($_POST['inventario']) ) ? $_POST['inventario'] : NULL ;
				$u_comentario = ( isset($_POST['u_comentario']) && !empty($_POST['u_comentario']) ) ? mb_strtoupper($_POST['u_comentario'],'UTF-8') : NULL ;
				$this->sql = "
				INSERT INTO u_implicadas (id, queja_id, procedencia, t_vehiculo, color, n_eco, placas,serie, inventario, comentario) 
				VALUES ('', :queja_id, :procedencia, :t_vehiculo, :color, :n_eco, :placas, :serie, :inventario, :comentario) ;";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->bindParam(':procedencia',$u_procedencia,PDO::PARAM_INT);
				$this->stmt->bindParam(':t_vehiculo',$t_vehiculo,PDO::PARAM_INT);
				$this->stmt->bindParam(':color',$color,PDO::PARAM_INT);
				$this->stmt->bindParam(':n_eco',$n_eco,PDO::PARAM_STR);
				$this->stmt->bindParam(':placas',$placas,PDO::PARAM_STR);
				$this->stmt->bindParam(':comentario',$u_comentario,PDO::PARAM_STR);
				$this->stmt->bindParam(':serie',$serie,PDO::PARAM_STR|PDO::PARAM_NULL);
				$this->stmt->bindParam(':inventario',$inventario,PDO::PARAM_STR|PDO::PARAM_NULL);
				$this->stmt->execute();	
				unset($this->sql);
				unset($this->stmt);
				#GUARDAR LOS DATOS DEL PRESUNTO 
				if ( !empty($_POST['nombre']) ) {
					$full_name = mb_strtoupper($_POST['nombre'],'utf-8')." ".mb_strtoupper($_POST['nombre'],'utf-8')." ".mb_strtoupper($_POST['nombre'],'utf-8');
					$genero= $_POST['ge'];
					$pro = $_POST['pro'];
					$cargo = $_POST['cargo'];
					$this->sql = "INSERT INTO presuntos (id,queja_id,nombre,genero,procedencia,cargo_id)
					VALUES ('',?,?,?,?,?);";
					$this->stmt = $this->getPDO()->prepare($this->sql);
					$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
					$this->stmt->bindParam(2,$full_name,PDO::PARAM_STR);
					$this->stmt->bindParam(3,$genero,PDO::PARAM_INT);
					$this->stmt->bindParam(4,$pro,PDO::PARAM_INT);
					$this->stmt->bindParam(5,$cargo,PDO::PARAM_INT);
					$this->stmt->execute();
				}
				#GUARDAR DATOS DE LA UBICACION
				$calle			= ( isset( $_POST['c_principal'] ) && !empty( $_POST['c_principal'] ) ) ? mb_strtoupper($_POST['c_principal']): 0;
				$e_calle		= ( isset( $_POST['e_calle'] ) && !empty( $_POST['e_calle'] ) ) ? mb_strtoupper($_POST['e_calle']): 0;
				$y_calle		= ( isset( $_POST['y_calle'] ) && !empty( $_POST['y_calle'] ) ) ? mb_strtoupper($_POST['y_calle']): 0;
				$colonia 	= ( isset( $_POST['edificacion'] ) && !empty( $_POST['edificacion'] ) ) ? $_POST['edificacion']: 0;
				$n_edificacion	= ( isset( $_POST['n_edificacion'] ) && !empty( $_POST['n_edificacion'] ) ) ? $_POST['n_edificacion']: 0;
				$municipio		= ( isset( $_POST['municipios'] ) && !empty( $_POST['municipios'] ) ) ? $_POST['municipios']: 0;
				$this->sql 		= "INSERT INTO ubicacion_referencia 
				(
					id,queja_id,calle,e_calle,y_calle,colonia,numero,municipio
				) 
				VALUES 
				(
					'',
					?,
					?,
					?,
					?,
					?,
					?,
					?
				);";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_STR);
				$this->stmt->bindParam(2,$calle,PDO::PARAM_STR);
				$this->stmt->bindParam(3,$e_calle,PDO::PARAM_STR);
				$this->stmt->bindParam(4,$y_calle,PDO::PARAM_STR);
				$this->stmt->bindParam(5,$colonia,PDO::PARAM_STR);
				$this->stmt->bindParam(6,$n_edificacion,PDO::PARAM_STR);
				$this->stmt->bindParam(7,$municipio,PDO::PARAM_STR);
				$this->stmt->execute();
				#Guardar las presuntas conductas
				unset($this->sql);
				unset($this->stmt);
				$conducta = $_POST['conducta'];
				$this->sql = " INSERT INTO p_conductas (id,queja_id,conducta_id, tipo) VALUES ('',?,?,1); ";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$conducta,PDO::PARAM_INT);
				$this->stmt->execute();
				#Agregar los datos de la averiguación previa.
				unset($this->sql);
				unset($this->stmt);

				/*if ($_POST['origen'][0] != '' ) {
					for($i = 0; $i < count($_POST['origen']); $i++ ){
						$origen 	= $_POST['origen'][$i];
						$tramite	= $_POST['tramite_prev'][$i];
						$clave_prev	= $_POST['clave_prev'][$i];
						$this->sql = " INSERT INTO referencia_queja (id,clave,origen,tipo, queja_id) VALUES ('',?,?,?,?); ";
						$this->stmt = $this->pdo->prepare($this->sql);
						$this->stmt->bindParam(1,$clave_prev,PDO::PARAM_STR);
						$this->stmt->bindParam(2,$origen,PDO::PARAM_INT);
						$this->stmt->bindParam(3,$tramite_preva,PDO::PARAM_INT);
						$this->stmt->bindParam(4,$queja_id,PDO::PARAM_INT);
						$this->stmt->execute();
					}
				}*/
				#Insertar el turnado
				/*unset($this->sql);
				unset($this->stmt);
				$this->sql = "INSERT INTO e_turnados(id,queja_id,persona_id,t_tramite,estado,f_turno) VALUES ('',?,?,1,1,DATE(NOW() ) ) ";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$_POST['sp_id'],PDO::PARAM_INT);
				$this->stmt->execute();*/
				#Insertar las vias de recepcion 
				unset($this->sql);
				unset($this->stmt);
				$this->sql = "INSERT INTO vias_recepcion (id,queja_id,via_id) VALUES ('',?,?) ";
				for ($i=0; $i < count($_POST['vias_r']); $i++) { 
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
					$this->stmt->bindParam(2,$_POST['vias_r'][$i],PDO::PARAM_INT);
					$this->stmt->execute();
				}
			}else{

				#Generar el numero aleatorio 
				$aleatorio = rand(1,1000000);
				for ($i=0; $i < $_POST['cantidad']; $i++) { 
					#-----------------------------------------------------------------------------------
					/*GENERAR LA CLAVE*/
					$this->sql = "SELECT nombre AS sn FROM tipos_tramite WHERE id = ?";
					$this->stmt = $this->getPDO()->prepare($this->sql);
					$this->stmt->bindParam(1,$t_tramite,PDO::PARAM_INT);
					$this->stmt->execute();
					$sn = $this->stmt->fetch(PDO::FETCH_OBJ);
					
					$this->sql = "SELECT id, cve_exp FROM quejas WHERE t_tramite = :tt ORDER BY id DESC LIMIT 1";
					$this->stmt = $this->getPDO()->prepare($this->sql);
					$this->stmt->bindParam(':tt',$t_tramite, PDO::PARAM_INT);
					$this->stmt->execute();
					if ( $this->stmt->rowCount() > 0  ) {
						$total = $this->stmt->fetch(PDO::FETCH_OBJ);
						$total = explode('/', $total->cve_exp)[3];
						$total++;
						$c_exp = "UAI/EDOMEX/".$sn->sn."/".$total."/".date('Y');
					}else{
						$total = 1;
						$c_exp = "UAI/EDOMEX/".$sn->sn."/".$total."/".date('Y');
					}
					#-----------------------------------------------------------------------------------
					$this->sql = "
						INSERT INTO quejas (
						id,
						t_asunto,
						ref_id,
						cve_ref,
						n_turno,
						t_tramite,
						cve_exp,
						f_hechos,
						h_hechos,
						genero,
						t_afectado,
						categoria,
						d_ano,
						
						descripcion,
						prioridad,
						fojas,
						evidencia,
						estado,
						procedencia,
						multiple_id
						) VALUES ( 
							'',
							?,
							?,
							?,
							?,
							?,
							?,
							?,
							?,
							?,
							?,
							?,
							?,
							
							?,
							?,
							?,
							?,
							?,
							?,
							?
							
						);
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$t_asunto,PDO::PARAM_INT|PDO::PARAM_NULL);
					$this->stmt->bindParam(2,$t_ref,PDO::PARAM_INT);
					$this->stmt->bindParam(3,$cve_ref,PDO::PARAM_STR);
					$this->stmt->bindParam(4,$n_turno,PDO::PARAM_STR);
					$this->stmt->bindParam(5,$t_tramite,PDO::PARAM_STR);
					$this->stmt->bindParam(6,$c_exp,PDO::PARAM_STR);
					$this->stmt->bindParam(7,$f_hechos,PDO::PARAM_STR);
					$this->stmt->bindParam(8,$h_hechos,PDO::PARAM_STR);
					$this->stmt->bindParam(9,$genero,PDO::PARAM_INT);
					$this->stmt->bindParam(10,$t_afectado,PDO::PARAM_STR);
					$this->stmt->bindParam(11,$categoria,PDO::PARAM_STR);
					$this->stmt->bindParam(12,$d_ano,PDO::PARAM_STR);
					//$this->stmt->bindParam(13,$comentario,PDO::PARAM_STR);
					$this->stmt->bindParam(13,$descripcion,PDO::PARAM_STR);
					$this->stmt->bindParam(14,$prioridad,PDO::PARAM_STR);
					$this->stmt->bindParam(15,$fojas,PDO::PARAM_STR);
					$this->stmt->bindParam(16,$evidencia,PDO::PARAM_STR);
					$this->stmt->bindParam(17,$estado,PDO::PARAM_STR);
					$this->stmt->bindParam(18,$procedencia,PDO::PARAM_INT);
					$this->stmt->bindParam(19,$aleatorio,PDO::PARAM_INT);
					
					$this->stmt->execute();
					#RECUPERAR EL ID DE LA QUEJA INSERTADO 
					$queja_id = $this->pdo->lastInsertId();
					array_push($insertados, $queja_id);
					#Insertar los datos del SEMOVIENTE (ANIMALES)
					unset($this->sql);
					unset($this->stmt);
					if ($add_semo == '1') {
						$this->sql = "
						INSERT INTO semovientes (
						    id,
						    queja_id,
						    tipo,
						    raza,
						    edad,
						    color,
						    nombre,
						    inventario
							) VALUES (
								'',
								:queja_id,
								:tipo,
								:raza,
								:edad,
								:color,
								:nombre,
								:inventario
							);";
						$this->stmt = $this->pdo->prepare($this->sql);
						$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT|PDO::PARAM_NULL);
						$this->stmt->bindParam(':tipo',$t_animal,PDO::PARAM_INT|PDO::PARAM_NULL);
						$this->stmt->bindParam(':raza',$raza,PDO::PARAM_STR|PDO::PARAM_NULL);
						$this->stmt->bindParam(':edad',$edad,PDO::PARAM_STR|PDO::PARAM_NULL);
						$this->stmt->bindParam(':color',$color,PDO::PARAM_STR|PDO::PARAM_NULL);
						$this->stmt->bindParam(':nombre',$n_animal,PDO::PARAM_STR|PDO::PARAM_NULL);
						$this->stmt->bindParam(':inventario',$inventario,PDO::PARAM_STR|PDO::PARAM_NULL);
						$this->stmt->execute();
					}
					#DATOS DEL QUEJOSO
					if ( $d_ano == 2 ) {
						$name_quejoso = ( isset($_POST['name_quejoso']) && !empty($_POST['name_quejoso']) ) ? $_POST['name_quejoso'] : NULL ;
						$phone = ( isset($_POST['phone']) && !empty($_POST['phone']) ) ? $_POST['phone'] : NULL ;
						$mail = ( isset($_POST['mail']) && !empty($_POST['mail']) ) ? $_POST['mail'] : NULL ;
						$genero = ( isset($_POST['genero']) && !empty($_POST['genero']) ) ? $_POST['genero'] : NULL ;
						$municipio = ( isset($_POST['municipio']) && !empty($_POST['municipio']) ) ? $_POST['municipio'] : NULL ;
						$cp = ( isset($_POST['cp']) && !empty($_POST['cp']) ) ? $_POST['cp'] : NULL ;
						$n_int = ( isset($_POST['n_int']) && !empty($_POST['n_int']) ) ? $_POST['n_int'] : NULL ;
						$n_ext = ( isset($_POST['n_ext']) && !empty($_POST['n_ext']) ) ? $_POST['n_ext'] : NULL ;
						$n_calle = ( isset($_POST['n_calle']) && !empty($_POST['n_calle']) ) ? $_POST['n_calle'] : NULL ;
						$this->sql = "INSERT INTO quejosos 
						(id,queja_id,nombre,telefono,email,municipio_id,cp,n_int,n_ext,comentarios,genero) 
						VALUES 
						('',:queja_id,:nombre,:telefono,:email,:municipio_id,:cp,:n_int,:n_ext,:comentarios,:genero);";
						$this->stmt = $this->getPDO()->prepare($this->sql);
						$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
						$this->stmt->bindParam(':nombre',$name_quejoso,PDO::PARAM_STR);
						$this->stmt->bindParam(':telefono',$phone,PDO::PARAM_STR);
						$this->stmt->bindParam(':email',$mail,PDO::PARAM_STR);
						$this->stmt->bindParam(':municipio_id',$municipio,PDO::PARAM_INT);
						$this->stmt->bindParam(':cp',$cp,PDO::PARAM_STR);
						$this->stmt->bindParam(':n_int',$n_int,PDO::PARAM_STR);
						$this->stmt->bindParam(':n_ext',$n_ext,PDO::PARAM_STR);
						$this->stmt->bindParam(':comentarios',$n_calle,PDO::PARAM_STR);
						$this->stmt->bindParam(':genero',$genero,PDO::PARAM_INT);
						$this->stmt->execute();				
					}

					unset($this->sql);
					unset($this->stmt);
					
					#Datos de unidad implicada
					$u_procedencia = ( isset($_POST['u_procedencia']) && !empty($_POST['u_procedencia']) ) ? $_POST['u_procedencia'] : NULL ;
					$t_vehiculo = ( isset($_POST['t_vehiculo']) && !empty($_POST['t_vehiculo']) ) ? $_POST['t_vehiculo'] : NULL ;
					$color = ( isset($_POST['color']) && !empty($_POST['color']) ) ? $_POST['color'] : NULL ;
					$n_eco = ( isset($_POST['n_eco']) && !empty($_POST['n_eco']) ) ? $_POST['n_eco'] : NULL ;
					$placas = ( isset($_POST['placas']) && !empty($_POST['placas']) ) ? $_POST['placas'] : NULL ;
					$ser = ( isset($_POST['n_ser']) && !empty($_POST['n_ser']) ) ? $_POST['n_ser'] : NULL ;
					$inv = ( isset($_POST['n_inv']) && !empty($_POST['n_inv']) ) ? $_POST['n_inv'] : NULL ;
					$u_comentario = ( isset($_POST['u_comentarios']) && !empty($_POST['u_comentarios']) ) ? mb_strtoupper($_POST['u_comentarios'],'UTF-8') : NULL ;
					if ( !empty( $u_procedencia ) ) {
						if ( is_null($t_vehiculo) || is_null($t_vehiculo) ) {
							throw new Exception("DEBE ELEGIR TIPO DE VEHÍCULO Y COLOR.", 1);							
						}
						$this->sql = "
						INSERT INTO u_implicadas (id, queja_id, procedencia, t_vehiculo, color, n_eco, placas, serie, inventario, comentario) 
						VALUES ('', :queja_id, :procedencia, :t_vehiculo, :color, :n_eco, :placas, :comentario) ;";
						$this->stmt = $this->getPDO()->prepare($this->sql);
						$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
						$this->stmt->bindParam(':procedencia',$u_procedencia,PDO::PARAM_INT|PDO::PARAM_NULL);
						$this->stmt->bindParam(':t_vehiculo',$t_vehiculo,PDO::PARAM_INT|PDO::PARAM_NULL);
						$this->stmt->bindParam(':color',$color,PDO::PARAM_INT|PDO::PARAM_NULL);
						$this->stmt->bindParam(':n_eco',$n_eco,PDO::PARAM_STR|PDO::PARAM_NULL);
						$this->stmt->bindParam(':placas',$placas,PDO::PARAM_STR|PDO::PARAM_NULL);
						$this->stmt->bindParam(':serie',$ser,PDO::PARAM_STR|PDO::PARAM_NULL);
						$this->stmt->bindParam(':inventario',$inv,PDO::PARAM_STR|PDO::PARAM_NULL);
						$this->stmt->bindParam(':comentario',$u_comentario,PDO::PARAM_STR|PDO::PARAM_NULL);
						$this->stmt->execute();
					}
						
					unset($this->sql);
					unset($this->stmt);
					#GUARDAR DATOS DE LA UBICACION
					$calle			= ( isset( $_POST['c_principal'] ) && !empty( $_POST['c_principal'] ) ) ? mb_strtoupper($_POST['c_principal']): 0;
					$e_calle		= ( isset( $_POST['e_calle'] ) && !empty( $_POST['e_calle'] ) ) ? mb_strtoupper($_POST['e_calle']): 0;
					$y_calle		= ( isset( $_POST['y_calle'] ) && !empty( $_POST['y_calle'] ) ) ? mb_strtoupper($_POST['y_calle']): 0;
					$colonia 	= ( isset( $_POST['edificacion'] ) && !empty( $_POST['edificacion'] ) ) ? $_POST['edificacion']: 0;
					$n_edificacion	= ( isset( $_POST['n_edificacion'] ) && !empty( $_POST['n_edificacion'] ) ) ? $_POST['n_edificacion']: 0;
					$municipio		= ( isset( $_POST['municipios'] ) && !empty( $_POST['municipios'] ) ) ? $_POST['municipios']: 0;
					$this->sql 		= "INSERT INTO ubicacion_referencia 
					(
						id,queja_id,calle,e_calle,y_calle,colonia,numero,municipio
					) 
					VALUES 
					(
						'',
						?,
						?,
						?,
						?,
						?,
						?,
						?
					);";
					$this->stmt = $this->getPDO()->prepare($this->sql);
					$this->stmt->bindParam(1,$queja_id,PDO::PARAM_STR);
					$this->stmt->bindParam(2,$calle,PDO::PARAM_STR);
					$this->stmt->bindParam(3,$e_calle,PDO::PARAM_STR);
					$this->stmt->bindParam(4,$y_calle,PDO::PARAM_STR);
					$this->stmt->bindParam(5,$colonia,PDO::PARAM_STR);
					$this->stmt->bindParam(6,$n_edificacion,PDO::PARAM_STR);
					$this->stmt->bindParam(7,$municipio,PDO::PARAM_STR);
					$this->stmt->execute();
					#Guardar las presuntas conductas
					$conducta = $_POST['conducta'];
					$this->sql = " INSERT INTO p_conductas (id,queja_id,conducta_id,tipo) VALUES ('',?,?,1); ";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
					$this->stmt->bindParam(2,$conducta,PDO::PARAM_INT);
					$this->stmt->execute();

					#Insertar las vias de recepcion 					
					$this->sql = "INSERT INTO vias_recepcion (id,via_id,queja_id) VALUES ('',?,?) ";
					for ($j=0; $j < count($_POST['vias_r']); $j++) { 
						$this->stmt = $this->pdo->prepare($this->sql);
						$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
						$this->stmt->bindParam(2,$_POST['vias_r'][$j],PDO::PARAM_INT);
						$this->stmt->execute();
					}
					#insertar a los presuntos
					
					$pro 		= ( !empty($_POST['pro'][$i]) ) ? $_POST['pro'][$i] : NULL;
					$nombre 	= ( !empty($_POST['nombre'][$i]) ) ? mb_strtoupper($_POST['nombre'][$i],'utf-8'): NULL;
					$ap_pat 	= ( !empty($_POST['ap_pat'][$i]) ) ? mb_strtoupper($_POST['ap_pat'][$i],'utf-8'): NULL;
					$ap_mat 	= ( !empty($_POST['ap_mat'][$i]) ) ? mb_strtoupper($_POST['ap_mat'][$i],'utf-8'): NULL;
					$full_name 	= mb_strtoupper($nombre,'utf-8')." ".mb_strtoupper($ap_pat,'utf-8')." ".mb_strtoupper($ap_mat,'utf-8'); 
					$genero 	= ( !empty($_POST['ge'][$i]) ) ? $_POST['ge'][$i] : NULL;
					$cargo  	= ( !empty($_POST['cargo'][$i]) )  ? $_POST['cargo'][$i] : NULL;
					$media 		= ( !empty($_POST['media'][$i]) ) ? mb_strtoupper($_POST['media'][$i],'utf-8'): NULL;
					#campos nuevos
					$rfc = ( !empty($_POST['rfc'][$i]) ) ? mb_strtoupper($_POST['rfc'][$i],'utf-8') : NULL;
					$curp = ( !empty($_POST['curp'][$i]) ) ? mb_strtoupper($_POST['curp'][$i],'utf-8') : NULL;
					$cuip = ( !empty($_POST['cuip'][$i]) ) ? mb_strtoupper($_POST['cuip'][$i],'utf-8') : NULL;
					$t_puesto = ( !empty($_POST['t_puesto'][$i]) ) ? mb_strtoupper($_POST['t_puesto'][$i],'utf-8') : NULL;
					
					$this->sql 	= "INSERT INTO presuntos (
						    id,
						    queja_id,
						    nombre,
						    genero,
						    rfc,
						    curp,
						    cuip,
						    t_puesto,
						    procedencia,
						    cargo_id,					    
						    a_determina,
						    comentarios
							) VALUES (
								'', 
								:queja_id, 
								:nombre,
								:genero,
								:rfc,
								:curp,
								:cuip,
								:t_puesto,
								:procedencia,
								:cargo_id,								
								2,
								:comentarios
							);";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
					$this->stmt->bindParam(':nombre',$full_name,PDO::PARAM_STR);
					$this->stmt->bindParam(':genero',$genero,PDO::PARAM_INT|PDO::PARAM_NULL);
					$this->stmt->bindParam(':rfc',$rfc,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':curp',$curp,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':cuip',$cuip,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':t_puesto',$t_puesto,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':procedencia',$pro,PDO::PARAM_INT);
					$this->stmt->bindParam(':cargo_id',$cargo,PDO::PARAM_INT);
					$this->stmt->bindParam(':comentarios',$media,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->execute();
					
				}
				#consular las claves de los expedientes insertados
				$insertados = implode(',', $insertados);
				$this->sql 	= "SELECT cve_exp FROM quejas WHERE id IN ($insertados);";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->execute();
				$claves = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux = array();
				foreach ($claves as $key => $clave) {
					array_push($aux, $clave->cve_exp);
				}
				$insertados = implode(',', $aux);
			}
		
			#Insertar la pista de auditoria
			session_start();
			$logger = $_SESSION['id'];
			$desc = "SE INSERTO UNA QUEJA ";
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,2,1);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=>'SE HA INSERTADO LA INFORMACIÓN DE MANERA CORRECTA.') );
		} catch (Exception $e) {
			#if( $e->getCode()  ){}
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function getQDOnly($queja)
	{
		try {

			$quejas = array();
			
			$this->sql = " SELECT q.*,UPPER(m.nombre) AS municipio,
			p.nombre AS procedencia,u.municipio AS mun_id,u.calle AS calle, u.e_calle AS e_calle, 
			u.y_calle AS y_calle, u.colonia AS colonia ,u.numero AS numero, tt.nombre AS n_tramite,
			e.nombre AS n_estado,DATE(q.created_at) AS f_apertura, q.procedencia AS procedencia_abre
			FROM quejas AS q
			LEFT JOIN ubicacion_referencia AS u ON u.queja_id = q.id
			LEFT JOIN municipios AS m ON m.id = u.municipio
			LEFT JOIN procedencias AS p ON p.id = q.procedencia
			LEFT JOIN tipos_tramite AS tt ON tt.id = q.t_tramite
			LEFT JOIN estado_guarda AS e ON e.id = q.estado
			WHERE q.id = ?
			";
			
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja,PDO::PARAM_INT);
			$this->stmt->execute();
			$qd = $this->stmt->fetch(PDO::FETCH_OBJ);
			
			$aux['id'] = $qd->id;
			$aux['cve_ref'] 	= $qd->cve_ref;
			$aux['n_turno'] 	= $qd->n_turno;
			$aux['cve_exp'] 	= $qd->cve_exp;
			$aux['h_hechos'] 	= $qd->h_hechos;
			$aux['f_hechos'] 	= $qd->f_hechos;
			$aux['municipio'] 	= $qd->municipio;
			$aux['procedencia'] = $qd->procedencia;
			$aux['procedencia_abre'] = $qd->procedencia_abre;
			$aux['t_asunto'] 	= $qd->t_asunto;
			$aux['t_tramite'] 	= $qd->t_tramite;
			$aux['t_afectado'] 	= $qd->t_afectado;
			$aux['categoria'] 	= $qd->categoria;
			$aux['d_ano'] 		= $qd->d_ano;
			#$aux['comentario'] 	= $qd->comentario;
			$aux['descripcion'] = $qd->descripcion;
			$aux['prioridad'] 	= $qd->prioridad;
			$aux['fojas'] 		= $qd->fojas;
			$aux['evidencia'] 	= $qd->evidencia;
			$aux['estado'] 		= $qd->estado;
			$aux['ref_id'] 		= $qd->ref_id;
			$aux['genero'] 		= $qd->genero;
			$aux['estado'] 		= $qd->estado;
			$aux['f_apertura'] 	= $qd->f_apertura;
			#Datos de la direccion de referencia
			$aux['calle'] 		= $qd->calle;
			$aux['e_calle'] 	= $qd->e_calle;
			$aux['y_calle'] 	= $qd->y_calle;
			$aux['colonia'] 	= $qd->colonia;
			$aux['numero'] 		= $qd->numero;
			$aux['mun_id'] 		= $qd->mun_id;
			$aux['n_tramite'] 	= $qd->n_tramite;
			$aux['n_estado'] 	= $qd->n_estado;

			#Buscar las presuntas conductas de cada expediente
			$sql_conductas = "SELECT pc.id AS id_presunta, cc.id,cc.nombre,l.nombre AS n_ley, pc.tipo 
			FROM p_conductas AS pc
			INNER JOIN catalogo_conductas AS cc ON cc.id = pc.conducta_id
            INNER JOIN leyes AS l ON l.id = cc.ley_id
			WHERE pc.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_conductas);
			$this->stmt->bindParam(1,$queja,PDO::PARAM_INT);
			$this->stmt->execute();

			if ( $this->stmt->rowCount() > 0 ) {
				$conductas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['conductas'] = $conductas;
			}else{
				$aux['conductas'] = array();
			}
			
			#Buscar los turnos del expediente
			$sql_turno = "SELECT p.*,e.id AS id_turno FROM e_turnados AS e
			INNER JOIN personal AS p ON p.id = e.persona_id
			WHERE e.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_turno);
			$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$turnos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['turnos'] = $turnos;

			#OPINIONES DE LOS ABOGADOS DE D.I.
			$this->sql = "SELECT *
			FROM opiniones_inv AS o
			WHERE o.queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja,PDO::PARAM_INT);
			$this->stmt->execute();
			
			if ($this->stmt->rowCount() > 0 ) {
				$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
				$aux['opiniones'] = $this->result;
			}else{
				$aux['opiniones'] = NULL;
			}
			
			#Buscar las vias de recepcion
			
			$sql_vias = "SELECT v.id , cv.nombre AS via  
			FROM vias_recepcion AS v 
			INNER JOIN catalogo_vias AS cv ON cv.id = v.via_id
			WHERE v.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_vias);
			$this->stmt->bindParam(1,$queja,PDO::PARAM_INT);
			$this->stmt->execute();
			
			if ( $this->stmt->rowCount()> 0) {
				$vias = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['vias'] = $vias;
			}else{
				$aux['vias'] = array();
			}
			
			#Agregar los datos de la direccion
			$sql_ubica = "SELECT u.*, m.nombre AS n_municipio  
			FROM ubicacion_referencia AS u 
			INNER JOIN municipios AS m ON m.id = u.municipio
			WHERE u.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_ubica);
			$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$ubicacion = $this->stmt->fetch(PDO::FETCH_OBJ);
			$aux['ubicacion'] = $ubicacion;
			#Agregar a los quejosos 
			$sql_quejosos = "SELECT q.* ,m.nombre AS n_municipio
			FROM quejosos AS q 
			LEFT JOIN municipios AS m ON m.id = q.municipio_id
			WHERE q.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_quejosos);
			$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$quejosos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['quejosos'] = $quejosos;
			
			#Agregar presuntos responsables
			$sql_presuntos = "SELECT p.* , pr.nombre as n_procedencia
			FROM presuntos AS p 
			LEFT JOIN procedencias AS pr ON pr.id = p.procedencia
			WHERE p.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_presuntos);
			$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			if ( $this->stmt->rowCount() > 0 ) {
				$presuntos = $this->stmt->fetch(PDO::FETCH_OBJ);
				$aux['presuntos'] = $presuntos;
			}else{
				$aux['presuntos'] = array();
			}
			#Agregar presuntos responsables
			$sql_unidades = "SELECT *
			FROM u_implicadas AS u 
			WHERE u.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_unidades);
			$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$unidades = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['unidades'] = $unidades;
			#Agregar archivos 
			$sql_files = "SELECT id, nombre, descripcion
			FROM documentos_quejas AS d 
			WHERE d.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_files);
			$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$archivos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['archivos'] = $archivos;
			#buscar expedientes acumulados 
			$this->sql = "
			SELECT
			    q1.cve_exp AS original,
			    q2.cve_exp AS acumulado,
			    q2.id AS acumulado_id
			FROM
			    quejas_acumuladas AS qa
			INNER JOIN quejas AS q1
			ON
			    q1.id = qa.q_original
			INNER JOIN quejas AS q2
			ON
			    q2.id = qa.q_acumulado
			WHERE qa.q_original = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$acumulados = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux['acumuladas'] = $acumulados;
			#agregar los semovientes
			$this->sql = "
			SELECT *
			FROM semovientes 
			WHERE queja_id = :queja_id;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':queja_id',$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$semovientes = $this->stmt->fetch( PDO::FETCH_OBJ );
			$aux['semovientes'] = $semovientes;
			array_push($quejas, $aux);
			return $quejas;
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}

	public function saveQuejoso()
	{
		try {
			$queja_id = $_POST['queja_id'];
			$nombre = ( !empty($_POST['nombre']) ) ? $_POST['nombre'] : NULL;
			$ap_pat = ( !empty($_POST['ap_pat']) ) ? $_POST['ap_pat'] : NULL;
			$ap_mat = ( !empty($_POST['ap_mat']) ) ? $_POST['ap_mat'] : NULL;
			$full_name = mb_strtoupper($nombre,'utf-8')." ".mb_strtoupper($ap_pat,'utf-8')." ".mb_strtoupper($ap_mat,'utf-8');
			$phone = ( !empty($_POST['phone']) ) ? $_POST['phone'] : NULL;
			$mail = ( !empty($_POST['mail']) ) ? $_POST['mail'] : NULL;
			$municipio = ( !empty($_POST['municipio']) ) ? $_POST['municipio'] : NULL;
			$cp = ( !empty($_POST['cp']) ) ? $_POST['cp'] : NULL;
			$n_int = ( !empty($_POST['n_int']) ) ? $_POST['n_int'] : NULL;
			$n_ext = ( !empty($_POST['n_ext']) ) ? $_POST['n_ext'] : NULL;
			$genero = ( !empty($_POST['genero']) ) ? $_POST['genero'] : NULL;
			$comentarios = (!empty($_POST['comentario'])) ? mb_strtoupper($_POST['comentario'],'utf-8')  : NULL;
			$this->sql = "INSERT INTO quejosos 
			(id,queja_id,nombre,telefono,email,municipio_id,cp,n_int,n_ext,comentarios,genero) 
			VALUES 
			('',?,?,?,?,?,?,?,?,?,?);";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(2,$full_name,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(3,$phone,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(4,$mail,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(5,$municipio,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(6,$cp,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(7,$n_int,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(8,$n_ext,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(9,$comentarios,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(10,$genero,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			session_start();
			$logger = $_SESSION['id'];
			$desc = "SE INSERTO UN PRESUNTO RESPONSABLE PARA LA QUEJA $queja_id ";
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,2,1);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=>'QUEJOSO AGREGADO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			if( $e->getCode() == '23000' ){
				return json_encode( array('status'=>'error','message'=>'EL TIPO DE REFERENCIA YA EXISTE.') );
			}else{
				return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
			}
		}
	}

	public function editQD()
	{
		try {
			$queja_id = $_POST['queja_id'];

			#Revisar las vias de recepcion
			if (isset($_POST['vias_r'])) {
				for ($i=0; $i < count( $_POST['vias_r'] ); $i++) { 
					$this->sql = "INSERT INTO vias_recepcion (id, via_id, queja_id) VALUES ('', :via, :queja_id) ;";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(':via',$_POST['vias_r'][$i],PDO::PARAM_INT);
					$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
					$this->stmt->execute();
				}
			}
			#evidencia
			if( isset($_POST['evidencia']) && !empty($_POST['evidencia']) ){
				$this->sql = "UPDATE quejas SET evidencia = :evidencia WHERE id = :queja_id";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':evidencia',$_POST['evidencia'],PDO::PARAM_INT);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
			}
			if( isset($_POST['fojas']) && !empty($_POST['fojas']) ){
				$this->sql = "UPDATE quejas SET fojas = :fojas WHERE id = :queja_id";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':fojas',$_POST['fojas'],PDO::PARAM_INT);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
			}
			if( isset($_POST['procedencia']) && !empty($_POST['procedencia']) ){
				$this->sql = "UPDATE quejas SET procedencia = :procedencia WHERE id = :queja_id";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':procedencia',$_POST['procedencia'],PDO::PARAM_INT);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
			}
			if( isset($_POST['f_hechos']) && !empty($_POST['f_hechos']) ){
				$this->sql = "UPDATE quejas SET f_hechos = :f_hechos WHERE id = :queja_id";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':f_hechos',$_POST['f_hechos'],PDO::PARAM_STR);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
			}
			if( isset($_POST['h_hechos']) && !empty($_POST['h_hechos']) ){
				$this->sql = "UPDATE quejas SET h_hechos = :h_hechos WHERE id = :queja_id";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':h_hechos',$_POST['h_hechos'],PDO::PARAM_STR);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
			}
			if( isset($_POST['categoria']) && !empty($_POST['categoria']) ){
				$this->sql = "UPDATE quejas SET categoria = :categoria WHERE id = :queja_id";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':categoria',$_POST['categoria'],PDO::PARAM_INT);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
			}
			if( isset($_POST['d_ano']) && $_POST['d_ano'] == '1' ){
				$this->sql = "UPDATE quejas SET d_ano = :d_ano WHERE id = :queja_id";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':d_ano',$_POST['d_ano'],PDO::PARAM_INT);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
			}
			#ACTUALIZAR LOS DATOS DE LA UBICACIÓN
			#GUARDAR DATOS DE LA UBICACION
			$municipio		= ( isset( $_POST['municipios'] ) && !empty( $_POST['municipios'] ) ) ? $_POST['municipios']: NULL;
			$calle			= ( isset( $_POST['c_principal'] ) && !empty( $_POST['c_principal'] ) ) ? mb_strtoupper($_POST['c_principal']): NULL;
			$e_calle		= ( isset( $_POST['e_calle'] ) && !empty( $_POST['e_calle'] ) ) ? mb_strtoupper($_POST['e_calle']): NULL;
			$y_calle		= ( isset( $_POST['y_calle'] ) && !empty( $_POST['y_calle'] ) ) ? mb_strtoupper($_POST['y_calle']): NULL;
			$colonia 	= ( isset( $_POST['edificacion'] ) && !empty( $_POST['edificacion'] ) ) ? $_POST['edificacion']: NULL;
			$n_edificacion	= ( isset( $_POST['n_edificacion'] ) && !empty( $_POST['n_edificacion'] ) ) ? $_POST['n_edificacion']: NULL;
			$this->sql 		= "
			UPDATE ubicacion_referencia SET
			calle = ?,
			e_calle = ?,
			y_calle = ?,
			colonia = ?,
			numero = ?,
			municipio = ?
			WHERE queja_id = ?
			;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$calle,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$e_calle,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$y_calle,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$colonia,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$n_edificacion,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$municipio,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$queja_id,PDO::PARAM_STR);
			$this->stmt->execute();
			#EDITAR LOS  DATOS DEL PRESUNTO RESPONSABLE
			$name_presunto = ( $_POST['name_presunto'] ) ? mb_strtoupper($_POST['name_presunto'],'UTF-8') : NULL ;
			$rfc = ( $_POST['rfc'] ) ? mb_strtoupper($_POST['rfc'],'UTF-8') : NULL ;
			$curp = ( $_POST['curp'] ) ? mb_strtoupper($_POST['curp'],'UTF-8') : NULL ;
			$cuip = ( $_POST['cuip'] ) ? mb_strtoupper($_POST['cuip'],'UTF-8') : NULL ;
			$t_puesto = ( $_POST['t_puesto'] ) ? $_POST['t_puesto'] : NULL ;
			$cargos = ( $_POST['cargo'] ) ? $_POST['cargo'] : NULL ;
			$genero = ( $_POST['genero'] ) ? $_POST['genero'] : NULL ;
			$procedencia = ( $_POST['procedencia'] ) ? $_POST['procedencia'] : NULL ;
			$media = ( $_POST['media'] ) ? mb_strtoupper($_POST['media'],'UTF-8') : NULL ;
			$this->sql 		= "
			UPDATE presuntos SET
				nombre = :nombre,
				genero = :genero,
				rfc = :rfc,
				curp = :curp,
				cuip = :cuip,
				t_puesto = :t_puesto,
				procedencia = :procedencia,
				cargo_id = :cargo_id,
				comentarios = :comentarios
				WHERE queja_id = :queja_id
			;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':nombre',$name_presunto,PDO::PARAM_STR);
			$this->stmt->bindParam(':genero',$genero,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(':rfc',$rfc,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':curp',$curp,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':cuip',$cuip,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':t_puesto',$t_puesto,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(':procedencia',$procedencia,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(':cargo_id',$cargos,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(':comentarios',$media,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			#editar los datos de la unidad implicada 
			if ( !empty($_POST['u_procedencia']) ) {
				$procedencia = ( !empty($_POST['u_procedencia']) ) ? mb_strtoupper($_POST['u_procedencia'],'UTF-8') : NULL ;
				$t_vehiculo = ( !empty($_POST['t_vehiculo']) ) ? mb_strtoupper($_POST['t_vehiculo'],'UTF-8') : NULL ;
				$color = ( !empty($_POST['color']) ) ? mb_strtoupper($_POST['color'],'UTF-8') : NULL ;
				$n_eco = ( !empty($_POST['n_eco']) ) ? mb_strtoupper($_POST['n_eco'],'UTF-8') : NULL ;
				$placas = ( !empty($_POST['placas']) ) ? mb_strtoupper($_POST['placas'],'UTF-8') : NULL ;
				$n_ser = ( !empty($_POST['n_ser']) ) ? mb_strtoupper($_POST['n_ser'],'UTF-8') : NULL ;
				$n_inv = ( !empty($_POST['n_inv']) ) ? mb_strtoupper($_POST['n_inv'],'UTF-8') : NULL ;
				$u_comentarios = ( !empty($_POST['u_comentarios']) ) ? mb_strtoupper($_POST['u_comentarios'],'UTF-8') : NULL ;
				$this->sql 		= "
				UPDATE u_implicadas SET
				procedencia = :procedencia ,
				t_vehiculo = :t_vehiculo ,
				color = :color ,
				n_eco = :n_eco ,
				placas = :placas ,
				serie = :serie ,
				inventario = :inventario ,
				comentario = :comentario 
				WHERE queja_id = :queja_id
				;";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':procedencia',$procedencia,PDO::PARAM_INT|PDO::PARAM_NULL);
				$this->stmt->bindParam(':t_vehiculo',$t_vehiculo,PDO::PARAM_INT|PDO::PARAM_NULL);
				$this->stmt->bindParam(':color',$color,PDO::PARAM_INT|PDO::PARAM_NULL);
				$this->stmt->bindParam(':n_eco',$n_eco,PDO::PARAM_STR|PDO::PARAM_NULL);
				$this->stmt->bindParam(':placas',$placas,PDO::PARAM_STR|PDO::PARAM_NULL);
				$this->stmt->bindParam(':serie',$n_ser,PDO::PARAM_STR|PDO::PARAM_NULL);
				$this->stmt->bindParam(':inventario',$n_inv,PDO::PARAM_STR|PDO::PARAM_NULL);
				$this->stmt->bindParam(':comentario',$u_comentarios,PDO::PARAM_STR|PDO::PARAM_NULL);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
			}
				
			#modificar los datos del semoviente
			$this->sql = "SELECT * FROM semovientes WHERE queja_id = :queja_id";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();	
			if ( $this->stmt->rowCount() > 0 ) {
				$tipo = ( !empty($_POST['t_animal']) ) ? mb_strtoupper($_POST['t_animal'],'UTF-8') : '' ;
				$raza = ( !empty($_POST['raza']) ) ? mb_strtoupper($_POST['raza'],'UTF-8') : '' ;
				$edad = ( !empty($_POST['edad']) ) ? mb_strtoupper($_POST['edad'],'UTF-8') : '' ;
				$color = ( !empty($_POST['color']) ) ? mb_strtoupper($_POST['color'],'UTF-8') : '' ;
				$nombre = ( !empty($_POST['n_animal']) ) ? mb_strtoupper($_POST['n_animal'],'UTF-8') : '' ;
				$inventario = ( !empty($_POST['inventario']) ) ? mb_strtoupper($_POST['inventario'],'UTF-8') : '' ;
				$this->sql = "
				UPDATE semovientes SET 
				tipo = :tipo, 
				raza = :raza,
				edad = :edad,
				color = :color,
				nombre = :nombre,
				inventario = :inventario
				WHERE queja_id = :queja_id";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':tipo',$tipo,PDO::PARAM_INT);
				$this->stmt->bindParam(':raza',$raza,PDO::PARAM_STR);
				$this->stmt->bindParam(':edad',$edad,PDO::PARAM_STR);
				$this->stmt->bindParam(':color',$color,PDO::PARAM_STR);
				$this->stmt->bindParam(':nombre',$nombre,PDO::PARAM_STR);
				$this->stmt->bindParam(':inventario',$inventario,PDO::PARAM_STR);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();	
			}else{
				$tipo = ( !empty($_POST['t_animal']) ) ? mb_strtoupper($_POST['t_animal'],'UTF-8') : '' ;
				$raza = ( !empty($_POST['raza']) ) ? mb_strtoupper($_POST['raza'],'UTF-8') : '' ;
				$edad = ( !empty($_POST['edad']) ) ? mb_strtoupper($_POST['edad'],'UTF-8') : '' ;
				$color = ( !empty($_POST['color']) ) ? mb_strtoupper($_POST['color'],'UTF-8') : '' ;
				$nombre = ( !empty($_POST['n_animal']) ) ? mb_strtoupper($_POST['n_animal'],'UTF-8') : '' ;
				$inventario = ( !empty($_POST['inventario']) ) ? mb_strtoupper($_POST['inventario'],'UTF-8') : '' ;
				$this->sql = "
				INSERT INTO semovientes(id, queja_id, tipo, raza, edad, color, nombre, inventario)
				VALUES ( '', :queja_id, :tipo, :raza, :edad, :color, :nombre, :inventario ) ;";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->bindParam(':tipo',$tipo,PDO::PARAM_INT);
				$this->stmt->bindParam(':raza',$raza,PDO::PARAM_STR);
				$this->stmt->bindParam(':edad',$edad,PDO::PARAM_STR);
				$this->stmt->bindParam(':color',$color,PDO::PARAM_STR);
				$this->stmt->bindParam(':nombre',$nombre,PDO::PARAM_STR);
				$this->stmt->bindParam(':inventario',$inventario,PDO::PARAM_STR);
				$this->stmt->execute();	
			}
			#AGREGAR LAS CONDUCTAS
			if ( !empty($_POST['conducta']) ) {
				$conducta_id = $_POST['conducta'];
				$this->sql = "INSERT INTO p_conductas (id, queja_id, conducta_id) 
				VALUES ( '',:queja_id, :conducta_id );";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->bindParam(':conducta_id',$conducta_id,PDO::PARAM_INT);
				$this->stmt->execute();	
			}
			return json_encode( array('status'=>'success','message'=>'EL EXPEDIENTE '.$_POST['cve_exp'].' A SIDO EDITADO DE MANERA EXITOSA.') );exit;

			#Insertar la pista de auditoria
			session_start();
			$logger = $_SESSION['id'];
			$desc = "SE EDITO LA QUEJA ".$queja_id;
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,3,1);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			
		
			
		} catch (Exception $e) {
			#if( $e->getCode()  ){}
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function getDocumento($file)
	{
		try {
			$this->sql = "SELECT archivo FROM documentos_quejas WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$file,PDO::PARAM_INT);
			$this->stmt->execute();
			$doc = $this->stmt->fetch(PDO::FETCH_OBJ);
			echo stripslashes($doc->archivo);
			
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function getDocumentoByOficio($oficio)
	{
		try {
			$this->sql = "SELECT archivo FROM documentos_qr WHERE oficio LIKE ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$oficio,PDO::PARAM_STR);
			$this->stmt->execute();
			$doc = $this->stmt->fetch(PDO::FETCH_OBJ);
			echo stripslashes($doc->archivo);
			
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function generateReporte()
	{
		try {

			$emptys = true ;
			$quejas = array();
			foreach ($_POST as $key => $value) {
				if ($key != 'option') {
					if ( !empty($_POST[$key]) ) {
						$emptys = false;
					}
				}
			}
			if ( $emptys ) {
				throw new Exception("TODOS LOS ELEMENTOS DEL FORMULARIO ESTAN VACIOS.\n 
					SI LO QUE NECESITA ES VER EL LISTADO COMPLETO, LE SUGUERIMOS IR 
					A LA SECCIÓN DE -LISTADO DE QUEJAS Y DENUNCIAS- \n 
					ESTA SECCIÓN SIRVE PARA REALIZAR UN MUESTREO DE DATOS MÁS ESPECIFICO. ", 1);
			}
			$wh = "";
			if ( !empty($_POST['t_asunto']) ) {
				$wh .= ' AND q.t_asunto = '. $_POST['t_asunto'];
			}
			#Las fechas 
			if ( !empty($_POST['f_ini']) && !empty($_POST['f_fin']) ) {
				$wh .= ' AND q.f_hechos BETWEEN "'. $_POST['f_ini']. '" AND "'.$_POST['f_fin'].'"';
			}elseif ( empty($_POST['f_ini']) && empty($_POST['f_fin']) ) {
				$wh .= '';#No agregar nada porque estan vacias y no se necesitan
			}else{
				throw new Exception("DEBE DE ELEGIR UN RANGO DE FECHAS.", 1);
			}
			#tipos de referencia
			if ( !empty($_POST['t_ref']) ) {
				$wh .= ' AND q.ref_id = '. $_POST['t_ref'];
			}
			#Seleccionar la prioridad
			if ( !empty($_POST['prioridad']) ) {
				$wh .= ' AND q.prioridad = '. $_POST['prioridad'];
			}
			#Seleccionar el estado guarda 
			if ( !empty($_POST['estado']) ) {
				$wh .= ' AND q.estado = '. $_POST['estado'];
			}
			#Seleccionar por evidencia 
			if ( !empty($_POST['evidencia']) ) {
				$wh .= ' AND q.evidencia = '. $_POST['evidencia'];
			}
			#Seleccionar por procedencia
			if ( !empty($_POST['procedencia']) ) {
				$wh .= ' AND q.procedencia = '. $_POST['procedencia'];
			}
			#Seleccionar por tipo de tramite 
			if ( !empty($_POST['t_tra']) ) {
				$wh .= ' AND q.t_tramite = '. $_POST['t_tra'];
			}
			#Seleccionar por genero 
			if ( !empty($_POST['genero']) ) {
				$wh .= ' AND q.genero = '. $_POST['genero'];
			}
			#Tipo de afectado
			if ( !empty($_POST['t_afecta']) ) {
				$wh .= ' AND q.t_afectado = '. $_POST['t_afecta'];
			}
			#Seleccionar por tipo de categoria
			if ( !empty($_POST['categoria']) ) {
				$wh .= ' AND q.categoria = '. $_POST['categoria'];
			}
			#Seleccionar si es denuncia anonima 
			if ( !empty($_POST['d_ano']) ) {
				$wh .= ' AND q.d_ano = '. $_POST['d_ano'];
			}
			#Seleccionar las quejas por municipio
			if ( !empty($_POST['municipio']) ) {
				$wh .= ' AND u.municipio = '. $_POST['municipio'];
			}
			#Seleccionar las quejas que contengan las palabras clave
			if ( !empty($_POST['descripcion']) ) {
				$full_phrase = $_POST['descripcion'];
				$aux = explode(' ', $full_phrase);
				$like = " AND ( descripcion LIKE '%".$full_phrase."%' ";
				for ($i=0; $i < count($aux); $i++) { 
					$like .= " OR descripcion LIKE '%$aux[$i]%' ";
				}
				$like .= " ) ";
				$wh .= $like;
			}

			#seleccionar a las quejas que contengan las conductas seleccionadas
			if (isset($_POST['conductas'])) {
				$sql_aux 		= "SELECT queja_id FROM p_conductas WHERE conducta_id IN (?)";
				$conductas 		= implode(',',$_POST['conductas']);
				
				$this->stmt 	= $this->pdo->prepare($sql_aux);
				$this->stmt->bindParam(1,$conductas,PDO::PARAM_STR);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ) {
					$pc_quejas 	= $this->stmt->fetchAll(PDO::FETCH_OBJ);
					#sacar las quejas
					$ids_quejas = array();
					foreach ($pc_quejas as $key => $queja) {
						array_push($ids_quejas, $queja->queja_id);
					}
					$ids_quejas = implode(',',$ids_quejas);
					$wh .= " AND q.id IN ($ids_quejas)";
				}else{
					throw new Exception("NO EXISTEN EXPEDIENTES RELACIONADOS A LAS CONDUCTAS SELECCIONADAS.", 1);					
				}
				
			}
			#Seleccionar la queja que contenga las vias de recepcion seleccionadas
			if (isset($_POST['vias_r'])) {
				
				$vias 			= implode(',',$_POST['vias_r']);
				$sql_vias 		= "SELECT queja_id FROM vias_recepcion WHERE via_id IN ($vias)";
				
				$this->stmt 	= $this->pdo->prepare($sql_vias);
				$this->stmt->bindParam(1,$vias,PDO::PARAM_STR);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ) {
					$vr_quejas 	= $this->stmt->fetchAll(PDO::FETCH_OBJ);
					#sacar las quejas
					$vias_quejas = array();

					foreach ($vr_quejas as $key => $via) {
						array_push($vias_quejas, $via->queja_id);
					}
					$vias_quejas = implode(',',$vias_quejas);
					$wh .= " AND q.id IN ($vias_quejas)";
				}else{
					throw new Exception("NO SE ENCONTRARON RESULTADOS CON SU CRITERIO DE BÚSQUEDA", 1);
					
				}
				
			}

			$this->sql = "SELECT q.*,UPPER(m.nombre) AS municipio,
			p.nombre AS procedencia, e.nombre AS n_estado, q.descripcion AS d_hechos
			 FROM quejas AS q
			LEFT JOIN ubicacion_referencia AS u ON u.queja_id = q.id
			LEFT JOIN municipios AS m ON m.id = u.municipio
			LEFT JOIN procedencias AS p ON p.id = q.procedencia
			LEFT JOIN estado_guarda AS e ON e.id = q.estado
			WHERE 1=1 AND q.created_at > '2019-07-01' $wh GROUP BY q.id";
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			if($this->stmt->rowCount() == 0){
				throw new Exception("NO SE ENCONTRARON RESULTADOS CON SU CRITERIO DE BÚSQUEDA", 1);
			}
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			foreach ($this->result as $key => $qd) {
				$aux['id'] = $qd->id;
				$aux['cve_ref'] = $qd->cve_ref;
				$aux['cve_exp'] = $qd->cve_exp;
				$aux['h_hechos'] = $qd->h_hechos;
				$aux['f_hechos'] = $qd->f_hechos;
				$aux['municipio'] = $qd->municipio;
				$aux['procedencia'] = $qd->procedencia;
				$aux['n_estado'] = $qd->n_estado;
				$aux['d_hechos'] = $qd->d_hechos;
				#Buscar las presuntas conductas de cada expediente
				$sql_conductas = "SELECT cc.id,cc.nombre  FROM p_conductas AS pc
				INNER JOIN catalogo_conductas AS cc ON cc.id = pc.conducta_id
				WHERE pc.queja_id = ?";
				$this->stmt = $this->pdo->prepare($sql_conductas);
				$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$conductas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['conductas'] = $conductas;
				
				array_push($quejas, $aux);
			}
			return json_encode($quejas);			
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	//CONTEO DE EXPEDIENTES POR ABOGADO
	public function getExpAbogados()
	{
		try {
			#p es el abogado
			$abogados=array();
			$this->sql = "
			SELECT p.id AS person_id, CONCAT(p.nombre,' ',p.ap_pat, ' ',p.ap_mat) AS full_name, COUNT( e.id ) AS total 
			FROM e_turnados AS e 
			INNER JOIN personal AS p ON p.id = e.persona_id
			INNER JOIN quejas AS q ON q.id = e.queja_id
			WHERE e.estado = 1 AND q.created_at > '2019-07-01'
			GROUP BY e.persona_id ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux = array();
			foreach ($this->result as $abogado) {
				$aux['nombre'] = $abogado->full_name;
				$aux['total'] = $abogado->total;
				$aux['person_id'] = $abogado->person_id;
				#Buscar los expedientes separados por estatus
				$this->sql = "
				SELECT queja_id 
				FROM e_turnados WHERE persona_id = ? AND estado = 1
				";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$abogado->person_id,PDO::PARAM_INT);
				$this->stmt->execute();
				$quejas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$e_aux = array();
				foreach ($quejas as $queja) {
					array_push($e_aux,$queja->queja_id);
				}
				$quejas = implode(',',$e_aux);#echo $quejas;
				$this->sql = "
				SELECT e.id,e.nombre AS estado,COUNT(e.id) AS total FROM quejas AS q
				INNER JOIN estado_guarda AS e ON e.id = q.estado 
				WHERE q.id IN ($quejas) AND q.created_at > '2019-07-01'
				GROUP BY q.estado
				";
				#echo $abogado->person_id." - ";
				
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->execute();
				$segmento = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['segmento'] = $segmento;
				array_push($abogados,$aux);
			}
			return $abogados;
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	//EXPEDIENTES POR ABOGADO Y ESTATUS 
	public function getExpByAbogado($p,$e)
	{
		try {

			$aux = array();$quejas = array();
			#p es el abogado y e es el estado 
			$abogado=array();
			if ( $e != false) {
				$wh = " AND q.estado = ".$e." AND et.persona_id = ".$p;
			}else{
				$wh = 'AND et.persona_id = '.$p;
			}
			$this->sql = "SELECT q.*,DATE(q.created_at) AS creado, UPPER(m.nombre) AS municipio,
			p.nombre AS procedencia, e.nombre AS n_estado, et.f_turno 
			FROM quejas AS q
			LEFT JOIN e_turnados AS et ON et.queja_id = q.id
			INNER JOIN ubicacion_referencia AS u ON u.queja_id = q.id
			INNER JOIN municipios AS m ON m.id = u.municipio
			LEFT JOIN procedencias AS p ON p.id = q.procedencia
			INNER JOIN estado_guarda AS e ON e.id = q.estado
			WHERE 1=1 $wh AND et.estado = 1 AND q.created_at > '2019-07-01'";
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			foreach ($this->result as $key => $qd) {
				$aux['id'] = $qd->id;
				$aux['cve_ref'] = $qd->cve_ref;
				$aux['cve_exp'] = $qd->cve_exp;
				$aux['h_hechos'] = $qd->h_hechos;
				$aux['f_hechos'] = $qd->f_hechos;
				$aux['municipio'] = $qd->municipio;
				$aux['procedencia'] = $qd->procedencia;
				$aux['n_estado'] = $qd->n_estado;
				#Buscar las presuntas conductas de cada expediente
				$sql_conductas = "SELECT cc.id,cc.nombre  FROM p_conductas AS pc
				INNER JOIN catalogo_conductas AS cc ON cc.id = pc.conducta_id
				WHERE pc.queja_id = ?";
				$this->stmt = $this->pdo->prepare($sql_conductas);
				$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$conductas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['conductas'] = $conductas;
				#Crear el contador de dias 
				if ($qd->estado == 1) {
					$sql_diff = "SELECT DATEDIFF(DATE(NOW()),?) AS dias ";
					$this->stmt = $this->pdo->prepare($sql_diff);
					$this->stmt->bindParam(1,$qd->creado,PDO::PARAM_STR);
					$this->stmt->execute();
					$dias = $this->stmt->fetch(PDO::FETCH_OBJ)->dias;	
					$aux['dias_t'] = $dias;	
				}else{
					$sql_diff = "SELECT DATEDIFF(?,?) AS dias ";
					$this->stmt = $this->pdo->prepare($sql_diff);
					$this->stmt->bindParam(1,$qd->f_turno,PDO::PARAM_STR);
					$this->stmt->bindParam(2,$qd->creado,PDO::PARAM_STR);
					$this->stmt->execute();
					$dias = $this->stmt->fetch(PDO::FETCH_OBJ)->dias;	
					$aux['dias_t'] = $dias;	
				}
						
				
				array_push($quejas, $aux);
			}
			return $quejas;
			
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	#Guardar el turnado del expediente
	public function saveTurno()
	{
		try {
			session_start();
			$acepto = ( isset($_POST['acepto']) ) ? $_POST['acepto'] : NULL;
			if ( is_null($acepto) ) {
				throw new Exception("EL CAMPO DE ACEPTACIÓN ES OBLIGATORIO PARA REALIZAR
				ESTA ACCIÓN, EN CASO DE TENER INQUIETUD SOBRE LA INFORMACIÓN QUE SE VA A TURNAR SE RECOMIENDA
				REVISAR LA CÉDULA O EN SU DEFECTO ACTUALIZAR LA INFORMACIÓN DANDO CLIC EN 'MODIFICAR'", 1);				
			}
			#validar las variables necesarias 
			if ( empty($_POST['oficio_e_id']) ) {
				throw new Exception("SE DEBE DE ESPECIFICAR UN NÚMERO DE OFICIO.", 1);
			}
			##session
			$nivel = $_SESSION['nivel'];
			$area_id = $_SESSION['area_id'];
			$edo = $_POST['estado'];
			$comentario = ( !empty($_POST['comentario']) ) ? mb_strtoupper($_POST['comentario'],'utf-8') : NULL ;
			$queja_id = $_POST['queja_id'];
			$f_turno = $_POST['f_turnado'];
			$sp_id = $_POST['sp_id'];
			$o = $_POST['oficio_envio']; 
			#Validacion de los campos
			if ( $_FILES['file']['error'] > 0 ) {
				throw new Exception("DEBE DE SELECCIONAR UN DOCUMENTO.", 1);
			}
			if ( $_FILES['file']['size'] > 10485760 ) {
				throw new Exception("EL DOCUMENTO EXCEDE EL TAMAÑO DE ARCHIVO ADMITIDO.", 1);	
			}
			if ( $_FILES['file']['type'] != 'application/pdf' ) {
				throw new Exception("EL FORMATO DE ARCHIVO NO ES ADMITIDO (SOLO PDF). ", 1);
			}
			#Recuperar las variables necesarias
			$doc_name = $_FILES['file']['name'];
			$doc_name = mb_strtoupper($doc_name,'UTF-8');
			$doc_type = $_FILES['file']['type'];
			$doc_size = $_FILES['file']['size'];
			$destino  = $_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/';
			#Mover el Doc
			move_uploaded_file($_FILES['file']['tmp_name'],$destino.$doc_name);
			#abrir el archivo
			$file 		= fopen($destino.$doc_name,'r');
			$content 	= fread($file, $doc_size);
			$content 	= addslashes($content);
			fclose($file);
			#Eliminar  el archivo 
			unlink($_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/'.$doc_name);	
			#Actualizar el estado guarda de la queja
			$this->sql = "UPDATE quejas SET estado = 7 WHERE id = :id;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':id',$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			#actualizar los turnos anteriores para descativarlos 
			$this->sql = "UPDATE e_turnados SET estado = 2 WHERE queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1, $queja_id, PDO::PARAM_INT);
			$this->stmt->execute();
			#Insertar el Documento 
			$this->sql = "INSERT INTO documentos_quejas (id, queja_id, nombre, descripcion, archivo) 
			VALUES ('', :queja_id, :nombre, 'SIN DESCRIPCIÓN', :archivo) ; ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':queja_id', $queja_id, PDO::PARAM_INT);
			$this->stmt->bindParam(':nombre', $doc_name, PDO::PARAM_STR);
			$this->stmt->bindParam(':archivo', $content, PDO::PARAM_LOB);
			$this->stmt->execute();
			$this->sql = "
			INSERT INTO e_turnados 
			(
				id, 
				queja_id, 
				persona_id, 
				t_tramite, 
				estado,
				comentarios,
				f_turno,
				oficio_cve,
				origen_id,
				motivo
			) VALUES (
				'',
				?,
				?,
				1,
				1,
				?,
				?,
				?,
				?,
				3
			);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$sp_id,PDO::PARAM_INT);
			$this->stmt->bindParam(3,$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$f_turno,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$o,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$area_id,PDO::PARAM_INT);
			$this->stmt->execute();
			#sI EL ORIGEN ES DE LAS DEVOLUCIONES
			if ( isset($_POST['origen']) ) {
				if ( $_POST['origen'] >= 1 ) {
					$dev = $_POST['origen'];
					$sql_dev = "UPDATE devoluciones SET estado = 2 WHERE id = ?";
					$this->stmt = $this->pdo->prepare($sql_dev);
					$this->stmt->bindParam(1,$dev,PDO::PARAM_INT);
					$this->stmt->execute();
				}
			}
			return json_encode( array( 'status'=>'success','message'=>'EL EXPEDIENTE YA A SIDO TURNADO A LA DIRECCIÓN DE RESPONSABILIDADES EN ASUNTOS INTERNOS.' ) );
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	//EXPEDIENTES DEVUELTOS DE RESPO A INVESTIGACION 
	public function getDevoluciones()
	{
		try {
			$this->sql = "
			SELECT d.*,q.cve_exp FROM devoluciones AS d 
			INNER JOIN quejas AS q ON q.id = d.queja_id
			WHERE d.estado = 1
			ORDER BY d.id DESC
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return $this->result;
		} catch (Exception $e) {
			return json_encode(array('status'=>'error','message'=>$e->getMessage()));
		}
	}
	//EXPEDIENTES PARA MIGRAR 
	public function getExpedientesForMigrate()
	{
		try {
			$wh = " 1=1 AND q.created_at > '2019-07-01'";
			$modo = $_POST['modo'];
			if ($modo == 1) {
				if (empty($_POST['sp_id'])) {
					throw new Exception("NO SELECCINO SERVIDOR PÚBLICO DE ORIGEN.", 1);
				}
				if (empty($_POST['destino_id'])) {
					throw new Exception("NO SELECCINO SERVIDOR PÚBLICO DESTINO.", 1);
				}
				$sp = $_POST['sp_id'];
				$wh .= ' AND p.id = '.$sp;
			}elseif ($modo == 2) {
				$fi = $_POST['f_ini'];
				$ff = $_POST['f_fin'];
				$wh .= " AND DATE(q.created_at) BETWEEN '$fi' AND '$ff'";
			}
			if ( isset($_POST['orden']) ) {
				if ($_POST['orden'] == 1) {
					$order = "ORDER BY q.cve_exp DESC";
				}
				if ($_POST['orden'] == 2) {
					$order = "ORDER BY q.cve_exp ASC";
				}
				if ($_POST['orden'] == 3) {
					$order = "ORDER BY DESC";
				}
				if ($_POST['orden'] == 4) {
					$order = "ORDER BY ASC";
				}
				if ($_POST['orden'] == 5) {
					$order = "ORDER BY DESC";
				}
				if ($_POST['orden'] == 6) {
					$order = "ORDER BY ASC";
				}
			}else{
				$order = "";
			}
			$this->sql = "
			SELECT q.id, q.cve_exp, ed.nombre, CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat) AS full_name FROM quejas AS q
			LEFT JOIN e_turnados AS e ON e.queja_id = q.id AND e.estado != 2
			LEFT JOIN personal AS p ON p.id = e.persona_id
			LEFT JOIN estado_guarda AS ed ON ed.id = q.estado
			WHERE $wh $order
			";
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	//EXPEDIENTES 
	public function MigrateQuejas()
	{
		try {
			session_start();
			$logger = $_SESSION['id'];
			$quejas = $_POST['quejas'];
			$destino = $_POST['destino'];
			$this->sql = "
			UPDATE e_turnados SET persona_id = ?, t_tramite = 3 WHERE queja_id = ? AND estado != 'VENCIDO' ;
			";
			for ($i=0; $i < count($quejas) ; $i++) { 
				$q = $quejas[$i];
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$destino,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$q,PDO::PARAM_INT);
				$this->stmt->execute();
				##iNSERTAR LA PISTA DE AUDITORIA 

				$desc = "SE ACTUALIZO EL TURNADO POR MEDIO DE LA MIGRACIÓN ";
				$sis = 1;
				$sql_pista = "INSERT INTO 
				pista_auditoria (id, descripcion,person_id,tipo, sistema) 
				VALUES 
				('',?,?,4,?);";
				$stmt = $this->pdo->prepare($sql_pista);
				$stmt->bindParam(1,$desc,PDO::PARAM_STR);
				$stmt->bindParam(2,$logger,PDO::PARAM_INT);
				$stmt->bindParam(3,$sis,PDO::PARAM_INT);
				$stmt->execute();
			}
			
			return json_encode(array('status'=>'success','message'=>'EXPEDIENTES ACTUALIZADOS DE MANERA EXITOSA.'));
		} catch (Exception $e) {
			return json_encode(array('status'=>'error','message'=>$e->getMessage()));
		}
	}
	public function readExp($queja)
	{
		try {
			$user = $_SESSION['id'];
			$this->sql = "
			UPDATE e_vistos SET estado = 2, p_visto = ? WHERE queja_id = ?;
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$user,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$queja,PDO::PARAM_INT);
			$this->stmt->execute();
		} catch (Exception $e) {
			return json_encode(array('status'=>'error','message'=>$e->getMessage()));
		}
	}
	public function getOINs()
	{
		try {

			$t = "%/".mb_strtoupper($_POST['tipo'])."/%";
			#$pass = '7W+Th_+uTh2X';
			$pass = '';
			$n_pdo = new PDO("mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8","root", $pass);
			$this->setPDO($n_pdo);
			$this->sql = "
			SELECT o.*, p.nom_completo,of.no_oficio,of.fecha_oficio FROM orden_inspeccion AS o
			LEFT JOIN personal AS p ON p.id_person = o.despachador_id
			LEFT JOIN oficios_generados AS of ON of.id_generado = o.oficio_id
			WHERE o.clave LIKE '$t'
			";
			
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode(array('status'=>'error','message'=>$e->getMessage()));
		}
	}
	public function contadorOINs()
	{
		try {

			$wh = " 1=1 ";
			if ( !empty($_POST['y']) ) {
				$wh .= " AND date(f_creacion) > '2019-07-01' AND YEAR(f_creacion) = ".$_POST['y'];
			}else{
				$wh .= " AND date(f_creacion) > '2019-07-01'";
			}
			#$pass = '7W+Th_+uTh2X';
			$pass = '';
			$n_pdo = new PDO("mysql:dbname=inspeccion;host=localhost;charset=utf8","root", $pass);
			$this->sql = "
			SELECT COUNT(id) AS cuenta, t_orden FROM orden_inspeccion 
			WHERE $wh
			GROUP BY t_orden
			";
			#echo $this->sql ;exit;
			$this->stmt = $n_pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode(array('status'=>'error','message'=>$e->getMessage()));
		}
	}
	public function getOINBy()
	{
		try {
			$wh = "1=1";
			#$pass = '7W+Th_+uTh2X';
			$pass = '';
			$n_pdo = new PDO("mysql:dbname=inspeccion;host=localhost;charset=utf8","root", $pass);
			$t = $_POST['t'];
			if ( !empty($_POST['y']) ) {
				$wh .= " AND date(oin.f_creacion) > '2019-07-01' AND YEAR(oin.f_creacion) = ".$_POST['y'];
			}else{
				$wh .= " AND date(oin.f_creacion) > '2019-07-01'";
			}
			$wh .= " AND oin.t_orden = '$t' ";
			$this->sql = "
			SELECT oin.*, o.no_oficio,  p.nom_completo  FROM orden_inspeccion as oin
			LEFT JOIN oficios_generados AS o ON o.id_generado = oin.oficio_id
			LEFT JOIN personal AS p ON p.id_person = oin.despachador_id
			WHERE $wh  
			";
			#echo $this->sql;exit;
			$this->stmt = $n_pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$t,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$y,PDO::PARAM_STR);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode(array('status'=>'error','message'=>$e->getMessage()));
		}
	}

	public function getAsignaciones($queja)
	{
		try {
			$this->sql = "SELECT e.*,CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat) AS turnado_a FROM e_turnados AS e 
			INNER JOIN personal AS p ON p.id = e.persona_id
			WHERE e.queja_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return $this->result;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function saveAsignar()
	{
		try {
			
			if ( empty($_POST['sp_id']) ) {
				throw new Exception("DEBE SELECCIONAR UN NOMBRE DE SERVIDOR PÚBLICO.", 1);	
			}
			if ( empty($_POST['jefe_depto_id']) ) {
				throw new Exception("DEBE SELECCIONAR JEFE DE DEPARTAMENTO.", 1);	
			}

			//$turno = $_POST['turno_id'];
			$sp_id = $_POST['sp_id'];
			$jefe_id = $_POST['jefe_depto_id'];
			$queja_id = $_POST['queja_id'];
			$area = $_SESSION['area_id'];
			$destino = NULL;
			$oficio = NULL;
			$obs = NULL;
			$f_turno = date('Y-m-d');
			$this->sql = "UPDATE e_turnados SET estado = 2 WHERE queja_id = ? ; ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->sql = "INSERT INTO e_turnados (
				id,
				queja_id,
				persona_id,
				jefe_id,
				origen_id,
				destino_id,
				t_tramite,
				estado,
				f_turno,
				oficio_cve,
				comentarios,
				of_sapa,
				f_sapa,
				org_destino,
				n_funcionario,
				motivo
				) VALUES (
				'',
				:queja_id,
				:persona_id,
				:jefe_id,
				:origen_id,
				:destino_id,
				1,
				1,
				:f_turno,
				:oficio_cve,
				:comentarios,
				NULL,
				NULL,
				NULL,
				NULL,
				NULL
			);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(':persona_id',$sp_id,PDO::PARAM_INT);
			$this->stmt->bindParam(':jefe_id',$jefe_id,PDO::PARAM_INT);
			$this->stmt->bindParam(':origen_id',$area,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(':destino_id',$destino,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(':oficio_cve',$oficio,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':f_turno',$f_turno,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':comentarios',$obs,PDO::PARAM_STR|PDO::PARAM_NULL);
			//$this->stmt->bindParam(':t_tramite',1,PDO::PARAM_INT);
			//$this->stmt->bindParam(9,'1',PDO::PARAM_STR);
			/*$this->stmt->bindParam(9,NULL,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(10,NULL,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(11,NULL,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(12,NULL,PDO::PARAM_STR|PDO::PARAM_NULL);*/
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'ASIGANCIÓN CREADA DE MANERA EXITOSA') );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getPenales()
	{
		try {
			$this->sql = "SELECT * FROM penales";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveOpinion()
	{
		try {
			$persona_id = $_POST['personal_id'];
			$queja_id = $_POST['queja_id'];
			if ( empty($_POST['comentario']) ) {
				throw new Exception("DEBE DE AGREGAR UN COMENTARIO", 1);				
			}else{
				$comentario = mb_strtoupper($_POST['comentario'],'utf-8');
			}
			
			$this->sql = "INSERT INTO opiniones_inv (id, queja_id, personal_id, comentario) VALUES ('', ?, ?, ?) ;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$persona_id,PDO::PARAM_INT);
			$this->stmt->bindParam(3,$comentario,PDO::PARAM_STR);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'OPINIÓN INSERTADA DE MANERA EXITOSA.') );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getExpTipo()
	{
		try {	
			$data = array();
			$wh = "";
			if ( empty($_POST['y']) ) {
				$wh .= "AND DATE(q.created_at) >= '2019-07-01'";
			}else{
				$wh .= " AND YEAR(q.created_at) = ".$_POST['y'];
			}
			#recuperar los de QDP 
			$this->sql = "SELECT COUNT(q.id) AS cuenta, t.nombre, q.t_tramite, q.t_asunto  FROM quejas AS q
			INNER JOIN tipos_tramite AS t ON t.id = q.t_tramite
			WHERE t_asunto = 1 AND t_tramite IN (1,2,3) $wh GROUP BY t_tramite  ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$policial = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$data['policial'] = $policial;
			#LOS ESPECIALES 
			$this->sql = "SELECT COUNT(q.id) AS cuenta, t.nombre , q.t_tramite, q.t_asunto  FROM quejas AS q
			INNER JOIN tipos_tramite AS t ON t.id = q.t_tramite
			WHERE t_asunto = 2 AND t_tramite IN (1,2,3) $wh GROUP BY t_tramite ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$especial = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$data['especial'] = $especial;
			return json_encode( $data );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getListadoTipo()
	{
		try {	
			$data = array();
			$t_asunto = $_POST['t_asunto'];
			$t_tramite = $_POST['t_tramite'];
			$wh = " 1=1 ";
			if ( empty($_POST['y']) ) {
				$wh .= "AND DATE(q.created_at) >= '2019-07-01'";
			}else{
				$wh .= " AND YEAR(q.created_at) = ".$_POST['y'];
			}
			$wh .= " AND q.t_tramite = $t_tramite AND q.t_asunto = $t_asunto ";
			$this->sql = "SELECT q.*,t.nombre AS n_tramite, e.nombre AS n_estado, p.nombre AS n_procedencia 
			FROM quejas AS q
			LEFT JOIN tipos_tramite AS t ON t.id = q.t_tramite
			LEFT JOIN estado_guarda AS e ON e.id = q.estado
			LEFT JOIN procedencias AS p ON p.id = q.procedencia
			where $wh";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$expedientes = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			
			return json_encode( $expedientes );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getCapitulos()
	{
		try {	
			$wh = "";
			if ( !empty($_POST['data']) && isset($_POST['data']) ) {
				$wh .= " ley_id = ".$_POST['data'];
			}else{
				$wh .= " 1=1 ";
			}
			$this->sql = "SELECT id, nombre FROM capitulos_leyes WHERE $wh";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}	
 	public function getCoordinaciones()
	{
		try {
			$this->sql = "SELECT * FROM catalogo_coordinacion";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode( $this->result );
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}		
} 	
?>