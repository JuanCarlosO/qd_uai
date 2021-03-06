<?php
/**
 * Direccion de Responsabilidade
 */
include_once 'anexgrid.php';
include_once 'UserModel.php';
class DRModel extends Connection
{
	private $sql;
	private $stmt;
	public $result;
	public function getOnlyExp()
	{
		try {
			$this->sql = "SELECT * FROM quejas WHERE id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function getExpedientes()
	{
		try {
			$anexgrid = new AnexGrid();
			$wh = " AND 1=1 ";
			#Los filtros 
			foreach ($anexgrid->filtros as $filter) {
				
				if ( $filter['columna'] != '' ) {
					if ( $filter['columna'] == 'q.cve_ref' || $filter['columna'] == 'et.of_sapa') {
						$wh .= " AND ".$filter['columna']." LIKE '%".$filter['valor']."%'";
					}else{
						if ( $filter['columna'] == 'qr.jefatura' || $filter['columna'] == 'qr.analista' ) {
							#BUSCAR EL ID DE LA PERSONA
							$term = "%".$filter['valor']."%";
							$this->sql = "SELECT id FROM personal WHERE CONCAT( nombre, ' ',ap_pat,' ',ap_mat) LIKE ?;";
							$this->stmt = $this->pdo->prepare($this->sql);
							$this->stmt->bindParam(1,$term, PDO::PARAM_STR);
							$this->stmt->execute();
							$jefe = $this->stmt->fetch(PDO::FETCH_OBJ)->id;
							$wh .= " AND ".$filter['columna'] ." = ".$jefe;
						}else{
							$wh .= " AND ".$filter['columna'] ." = ".$filter['valor'];
						}
						#$wh .= " AND ".$filter['columna'] ." = ".$filter['valor'];
					}
				}
			}
			
			$this->sql = "
			SELECT 
				q.id AS queja_id,
				q.*, 
				t.nombre AS n_tramite , 
				e.nombre AS n_estado, 
				et.f_turno, 
				p.nombre AS n_procedencia,
				qr.e_procesal,
				qr.f_acuse, 
				CONCAT(p1.nombre,' ',p1.ap_pat,' ', p1.ap_mat) AS jefe, 
				CONCAT(p2.nombre,' ',p2.ap_pat,' ', p2.ap_mat) AS analista,
				
				qr.id AS qd_res, 
				qr.autoridad,
				qr.f_disponibilidad,
				et.f_sapa,
				et.of_sapa
			FROM quejas AS q 
				INNER JOIN e_turnados AS et ON et.queja_id = q.id
				left JOIN documentos_sapa AS ds ON ds.oficio LIKE et.oficio_cve
				INNER JOIN tipos_tramite AS t ON t.id = q.t_tramite
				INNER JOIN estado_guarda AS e ON e.id = q.estado
				LEFT JOIN procedencias AS p ON p.id = q.procedencia
				LEFT JOIN quejas_respo AS qr ON qr.queja_id = q.id
				LEFT JOIN personal AS p1 ON p1.id = qr.jefatura
				LEFT JOIN personal AS p2 ON p2.id = qr.analista
				
			WHERE 
				et.estado = 1 $wh 
			ORDER BY q.$anexgrid->columna $anexgrid->columna_orden 
			LIMIT $anexgrid->pagina , $anexgrid->limite
			
			";
			#print_r($this->sql);exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$quejas = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$t = $this->stmt->rowCount();

			$aux = array();
			$expedientes = array();
			foreach ($quejas as $key => $exp) {
				$aux['id'] = $exp->id;
				$aux['queja_id'] = $exp->queja_id;
				$aux['asunto'] = $exp->t_asunto;				
				$aux['cve_ref'] = $exp->cve_ref;			
				$aux['n_turno'] = $exp->n_turno;			
				$aux['n_tramite'] = $exp->n_tramite;			
				$aux['cve_exp'] = $exp->cve_exp;			
				$aux['f_hechos'] = $exp->f_hechos;			
				$aux['h_hechos'] = $exp->h_hechos;		
				$aux['n_procedencia'] = $exp->n_procedencia;		
				$aux['categoria'] = $exp->categoria;		
				$aux['e_procesal'] = $exp->e_procesal;		
				$aux['jefe'] = $exp->jefe;		
				$aux['analista'] = $exp->analista;		
				$aux['qd_res'] = $exp->qd_res;		
				$aux['autoridad'] = $exp->autoridad;		
				$aux['f_sapa'] = $exp->f_sapa;		
				$aux['of_sapa'] = $exp->of_sapa;		
				#calcular los dias transcurridos
				if (is_null($exp->f_sapa)) {
					$aux['dias_t'] = 'SIN ESTADO PROCESAL';
				}else{
					$sql_facuse = "SELECT f_acuse FROM quejas_respo WHERE queja_id = ? AND estado = 1";
					$this->stmt = $this->pdo->prepare($sql_facuse);
					$this->stmt->bindParam(1,$exp->queja_id,PDO::PARAM_INT);
					$this->stmt->execute();
					$f_acuse = $this->stmt->fetch(PDO::FETCH_OBJ);
					$total = (int) $this->stmt->rowCount();
					#print_r($this->stmt->rowCount());
					if ( $total > 0 ) {
						
						$sql_diff = "SELECT DATEDIFF(?,?) AS dias_t";
						$this->stmt = $this->pdo->prepare($sql_diff);
						$this->stmt->bindParam(1,$f_acuse->f_acuse,PDO::PARAM_STR);
						$this->stmt->bindParam(2,$exp->f_sapa,PDO::PARAM_STR);
						$this->stmt->execute();
						$fecha = $this->stmt->fetch(PDO::FETCH_OBJ);
						$aux['dias_t'] = $fecha->dias_t;
					}else{
						$aux['dias_t'] = 'SIN ESTADO PROCESAL';
					}
				}
				
				#buscar los presuntos responsables.
				$sql_presuntos = "SELECT UPPER(presuntos.nombre) AS nombre, procedencias.nombre AS n_procedencia FROM presuntos 
					INNER JOIN procedencias ON procedencias.id = presuntos.procedencia
					WHERE queja_id = ?;";
				$this->stmt = $this->pdo->prepare($sql_presuntos);
				$this->stmt->bindParam(1,$exp->queja_id);
				$this->stmt->execute();
				$presuntos 	= $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['presuntos'] = $presuntos;
				#Buscar que abogado tiene asignado ese expediente y contar los dias que tiene con el 
				$sql_analista = "SELECT analista,DATE(created_at) AS creado FROM quejas_respo WHERE queja_id = ? ;";
				$this->stmt = $this->pdo->prepare($sql_analista);
				$this->stmt->bindParam(1,$exp->queja_id);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0  ) {
					$analista 	= $this->stmt->fetch(PDO::FETCH_OBJ);
					$sql_comment = "SELECT DATE(created_at) AS f_alta FROM opiniones_analistas WHERE queja_id = ? AND persona_id = ? ORDER BY id DESC LIMIT 1;";
					$this->stmt = $this->pdo->prepare($sql_comment);
					$this->stmt->bindParam(1,$exp->queja_id);
					$this->stmt->bindParam(2,$analista->analista);
					$this->stmt->execute();
					if ($this->stmt->rowCount() > 0) {
						$opinion 	= $this->stmt->fetch(PDO::FETCH_OBJ);
						$dias_analista = $this->operacionesFechas('-',$opinion->f_alta,$analista->creado)->resta;
					}else{
						$d = date('Y-m-d');
						$dias_analista = $this->operacionesFechas('-',$d,$analista->creado)->resta;
					}
				}else{
					$dias_analista = 'NO ASIGNADO';
				}
				
				$aux['diast_analista'] = $dias_analista;
				array_push($expedientes,$aux);
			}
			#print_r($expedientes);
			return $anexgrid->responde($expedientes,$t);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getExpedientesAnalista()
	{
		try {
			session_start();
			$analista = $_SESSION['id'];
			$anexgrid = new AnexGrid();
			if ( $_SESSION['nivel'] == 'JEFE') {
				$wh = " AND 1=1 AND qr.jefatura = $analista";
			}else{
				$wh = " AND 1=1 AND qr.analista = $analista";
			}
			
			#Los filtros 
			foreach ($anexgrid->filtros as $filter) {
				
				if ( $filter['columna'] != '' ) {
					if ( $filter['columna'] == 'q.cve_ref' || $filter['columna'] == 'qr.oficio') {
						$wh .= " AND ".$filter['columna']." LIKE '%".$filter['valor']."%'";
					}else{
						if ( $filter['columna'] == 'qr.jefatura' || $filter['columna'] == 'qr.analista' ) {
							#BUSCAR EL ID DE LA PERSONA
							$term = "%".$filter['valor']."%";
							$this->sql = "SELECT id FROM personal WHERE CONCAT( nombre, ' ',ap_pat,' ',ap_mat) LIKE ?;";
							$this->stmt = $this->pdo->prepare($this->sql);
							$this->stmt->bindParam(1,$term, PDO::PARAM_STR);
							$this->stmt->execute();
							$jefe = $this->stmt->fetch(PDO::FETCH_OBJ)->id;
							$wh .= " AND ".$filter['columna'] ." = ".$jefe;
						}else{
							$wh .= " AND ".$filter['columna'] ." = ".$filter['valor'];
						}
					}
				}
			}
			
			$this->sql = "
			SELECT 
				q.id AS queja_id, q.*, 
				t.nombre AS n_tramite , e.nombre AS n_estado, 
				et.f_turno, 
				p.nombre AS n_procedencia,
				qr.e_procesal,
				qr.f_acuse, 
				CONCAT(p1.nombre,' ',p1.ap_pat,' ', p1.ap_mat) AS jefe, 
				CONCAT(p2.nombre,' ',p2.ap_pat,' ', p2.ap_mat) AS analista, 
				qr.oficio, 
				qr.id AS qd_res
			FROM quejas AS q 
			LEFT JOIN quejas_respo AS qr ON qr.queja_id = q.id
			LEFT JOIN personal AS p1 ON p1.id = qr.jefatura
			LEFT JOIN personal AS p2 ON p2.id = qr.analista
			INNER JOIN e_turnados AS et ON et.queja_id = q.id
			INNER JOIN tipos_tramite AS t ON t.id = q.t_tramite
			INNER JOIN estado_guarda AS e ON e.id = q.estado
			INNER JOIN procedencias AS p ON p.id = q.procedencia
			WHERE et.estado != 2 $wh 
			ORDER BY q.$anexgrid->columna $anexgrid->columna_orden LIMIT $anexgrid->pagina , $anexgrid->limite
			";
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$quejas = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$total = $this->stmt->rowCount();
			$aux = array();
			$expedientes = array();
			foreach ($quejas as $key => $exp) {
				$aux['id'] = $exp->id;
				$aux['queja_id'] = $exp->queja_id;
				$aux['asunto'] = $exp->t_asunto;				
				$aux['cve_ref'] = $exp->cve_ref;			
				$aux['n_turno'] = $exp->n_turno;			
				$aux['n_tramite'] = $exp->n_tramite;			
				$aux['cve_exp'] = $exp->cve_exp;			
				$aux['f_hechos'] = $exp->f_hechos;			
				$aux['h_hechos'] = $exp->h_hechos;		
				$aux['n_procedencia'] = $exp->n_procedencia;		
				$aux['categoria'] = $exp->categoria;		
				$aux['e_procesal'] = $exp->e_procesal;		
				$aux['jefe'] = $exp->jefe;		
				$aux['analista'] = $exp->analista;		
				$aux['oficio'] = $exp->oficio;		
				$aux['qd_res'] = $exp->qd_res;			
				#calcular los dias transcurridos
				if (is_null($exp->f_acuse)) {
					$sql_dias = "SELECT DATEDIFF(DATE(NOW()),? ) AS dias_t";
					$this->stmt = $this->pdo->prepare($sql_dias);
					$this->stmt->bindParam(1,$exp->f_turno);
					$this->stmt->execute();
					$dias_t = $this->stmt->fetch(PDO::FETCH_OBJ)->dias_t;
					$aux['dias_t'] = $dias_t;
				}else{
					$sql_dias = "SELECT DATEDIFF( ? ,? ) AS dias_t";
					$this->stmt = $this->pdo->prepare($sql_dias);
					$this->stmt->bindParam(1,$exp->f_acuse);
					$this->stmt->bindParam(2,$exp->f_turno);
					$this->stmt->execute();
					$dias_t = $this->stmt->fetch(PDO::FETCH_OBJ)->dias_t;
					$aux['dias_t'] = $dias_t;
				}
				#buscar los presuntos responsables.
				$sql_presuntos = "SELECT UPPER(nombre) AS nombre FROM presuntos WHERE queja_id = ? ;";
				$this->stmt = $this->pdo->prepare($sql_presuntos);
				$this->stmt->bindParam(1,$exp->queja_id);
				$this->stmt->execute();
				$presuntos 	= $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['presuntos'] = $presuntos;
				#Buscar que abogado tiene asignado ese expediente y contar los dias que tiene con el 
				$sql_analista = "SELECT analista,DATE(created_at) AS creado FROM quejas_respo WHERE queja_id = ? ;";
				$this->stmt = $this->pdo->prepare($sql_analista);
				$this->stmt->bindParam(1,$exp->queja_id);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0  ) {
					$analista 	= $this->stmt->fetch(PDO::FETCH_OBJ);
					$sql_comment = "SELECT DATE(created_at) AS f_alta FROM opiniones_analistas WHERE queja_id = ? AND persona_id = ? ORDER BY id DESC LIMIT 1;";
					$this->stmt = $this->pdo->prepare($sql_comment);
					$this->stmt->bindParam(1,$exp->queja_id);
					$this->stmt->bindParam(2,$analista->analista);
					$this->stmt->execute();
					if ($this->stmt->rowCount() > 0) {
						$opinion 	= $this->stmt->fetch(PDO::FETCH_OBJ);
						$dias_analista = $this->operacionesFechas('-',$opinion->f_alta,$analista->creado)->resta;
					}else{
						$d = date('Y-m-d');
						$dias_analista = $this->operacionesFechas('-',$d,$analista->creado)->resta;
					}
				}else{
					$dias_analista = 'NO ASIGNADO';
				}
				array_push($expedientes,$aux);
			}
			#print_r($expedientes);
			return $anexgrid->responde($expedientes,$total);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getCorrespondencia()
	{
		try {
			session_start();
			$analista = $_SESSION['id'];
			$anexgrid = new AnexGrid();
			$wh = " 1=1 ";
			#Los filtros 
			foreach ($anexgrid->filtros as $filter) {
				if ( $filter['columna'] != '' ) {
					if ( $filter['columna'] == 'q.cve_ref') {
						$wh .= " AND ".$filter['columna']." LIKE '%".$filter['valor']."%'";
					}
				}
			}
			#localizar los expedientes que se turnaron a respo 
			$this->sql = "SELECT id FROM quejas WHERE estado = 7";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$on_respo = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux = array();
			foreach ($on_respo as $key => $res) {
				array_push($aux,$res->id);
			}
			$aux_qd = implode(',', $aux);

			$wh .= " AND queja_id IN ($aux_qd) ";
			$this->sql = "
			SELECT * FROM e_turnados WHERE $wh AND estado != 2
			GROUP BY oficio_cve 
			ORDER BY $anexgrid->columna $anexgrid->columna_orden 
			LIMIT $anexgrid->pagina , $anexgrid->limite
			";
			#print_r($this->sql);exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			#$total = $this->stmt->rowCount();
			$aux = array();
			$expedientes = array();
			foreach ($this->result as $key => $exp) {
				
				#buscar los expedientes por oficio
				if ( !empty($exp->oficio_cve) ) {
					$aux['id'] = $exp->id;
					$aux['oficio'] = $exp->oficio_cve;
					$aux['queja_id'] = $exp->queja_id;
					$aux['fecha'] = $exp->created_at;
					$aux['t_tramite'] = $exp->t_tramite;
					if ($exp->f_sapa == null) {
						$aux['f_sapa'] = 'SIN TURNAR';
					}else{
						$aux['f_sapa'] = $exp->f_sapa;
					}
					$this->sql = "
					SELECT q.id, q.cve_exp FROM quejas AS q 
					INNER JOIN e_turnados AS et ON et.queja_id = q.id
					WHERE oficio_cve LIKE ? AND et.estado = 1
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$exp->oficio_cve,PDO::PARAM_STR);
					$this->stmt->execute();
					$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
					$aux['claves'] = $this->result;
					#Datos de un acuse
					$this->sql = "
					SELECT * FROM documentos_qr
					WHERE oficio LIKE ?
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$exp->oficio_cve,PDO::PARAM_STR);
					$this->stmt->execute();
					$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );

					if ( $this->stmt->rowCount() == 0 ) {
						$aux['f_oficio'] = "SIN ACUSE";#datos del acuse
						$aux['f_acuse'] = "SIN ACUSE";#datos del acuse
						$aux['comentario'] = "SIN ACUSE";#datos del acuse
					}else{
						$aux['f_oficio'] = $this->result->f_oficio;#datos del acuse
						$aux['f_acuse'] = $this->result->f_acuse;#datos del acuse
						$aux['comentario'] = $this->result->comentario;#datos del acuse
					}
					
					array_push($expedientes,$aux);
				}
				
			}
			#print_r( count($expedientes) );
			$total = count($expedientes);
			return $anexgrid->responde($expedientes,$total);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getQuejas()
	{
		try {
			#buscar los expedientes turnados a respo
			$this->sql = "SELECT * FROM quejas WHERE id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getOFs()
	{
		try {
			$term = "%".$_REQUEST['term']."%";
			$pass = '7W+Th_+uTh2X';
			#$pass = '';
			$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',$pass));
				
			$this->sql = "SELECT id_generado AS id, no_oficio AS value 
			FROM oficios_generados WHERE no_oficio LIKE ? LIMIT 0,20		
			";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$term,PDO::PARAM_STR);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getCargos()
	{
		try {
			$this->sql = "SELECT * FROM cargos ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return $this->result;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getSanciones()
	{
		try {
			$wh = "";
			if ( $_POST['data'] != 0  ) {
				$wh .= " AND queja_id = ".$_POST['data'];
			}
			$this->sql = "SELECT id, nombre_pr AS nombre FROM sanciones WHERE 1=1 $wh";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getFullSanciones($queja_id)
	{
		try {
			$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root',''));
			$wh = "";
			$this->sql = "SELECT s.*, CONCAT(p.nombre, ' ', p.ap_pat,' ', p.ap_mat) AS full_name 
			FROM sanciones AS s 
			INNER JOIN personal AS p ON p.id = s.quien_r 
			WHERE s.queja_id = :id";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':id', $queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$san = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return $san;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getFullVerificaciones($queja_id)
	{
		try {
			$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root',''));
			$this->sql = "SELECT id  FROM sanciones WHERE 1=1 AND queja_id = $queja_id;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			if ( $this->stmt->rowCount() > 0 ) {
				$aux = array();
				foreach ($this->result as $key => $sancion) {
					array_push($aux, $sancion->id);
				}
				$aux = implode(',', $aux);
				$this->sql = "SELECT v.* , 
				CONCAT(p.nombre, ' ',p.ap_pat,' ', p.ap_mat) AS full_name,
				s.nombre_pr 
				FROM verificaciones AS v 
				INNER JOIN sanciones AS s ON s.id = v.sancion_id
				INNER JOIN personal AS p ON s.quien_r = p.id 
				WHERE v.sancion_id IN ($aux) ";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->execute();
				$verificaciones = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			}else{
				$verificaciones = array();
			}
			return $verificaciones;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function saveSancion()
	{
		try {
			#recupera variables 
			session_start();
			$quien = $_SESSION['id'];
			$oficio_sa = ( !empty($_POST['oficio_sa']) ) ? mb_strtoupper($_POST['oficio_sa'],'UTF-8') : NULL;
			$nombre_pr = ( !empty($_POST['n_responsable']) ) ? mb_strtoupper($_POST['n_responsable'],'UTF-8') : NULL;
			$adscripcion = ( !empty($_POST['adscripcion']) ) ? mb_strtoupper($_POST['adscripcion'],'UTF-8') : NULL;
			$rfc = ( !empty($_POST['rfc']) ) ? mb_strtoupper($_POST['rfc'],'UTF-8') : NULL;
			$curp = ( !empty($_POST['curp']) ) ? mb_strtoupper($_POST['curp'],'UTF-8') : NULL;
			$cuip = ( !empty($_POST['cuip']) ) ? mb_strtoupper($_POST['cuip'],'UTF-8') : NULL;
			$f_determina = ( !empty($_POST['f_determina']) ) ? mb_strtoupper($_POST['f_determina'],'UTF-8') : NULL;
			$f_notificacion = ( !empty($_POST['f_notificacion']) ) ? mb_strtoupper($_POST['f_notificacion'],'UTF-8') : NULL;
			$f_resolucion = ( !empty($_POST['f_resolucion']) ) ? mb_strtoupper($_POST['f_resolucion'],'UTF-8') : NULL;
			$f_sesion = ( !empty($_POST['f_sesion']) ) ? mb_strtoupper($_POST['f_sesion'],'UTF-8') : NULL;
			$notificado = ( !empty($_POST['notificado']) ) ? $_POST['notificado'] : NULL;
			$castigo = ( !empty($_POST['castigo']) ) ? mb_strtoupper($_POST['castigo'],'UTF-8') : NULL;
			$comentario = ( !empty($_POST['comentario']) ) ? mb_strtoupper($_POST['comentario'],'UTF-8') : NULL;
			$queja_id = ( !empty($_POST['queja_id']) ) ? mb_strtoupper($_POST['queja_id'],'UTF-8') : NULL;
			$dias_s = ( !empty($_POST['dias_s']) ) ? mb_strtoupper($_POST['dias_s'],'UTF-8') : NULL;
			#print_r($_POST);exit;
			#agrega validaciones
			if ( is_null($oficio_sa) ) { throw new Exception("EL NÚMERO DE OFICIO ES UN CAMPO OBLIGATORIO.", 1);}
			if ( is_null($nombre_pr) ) { throw new Exception("EL NOMBRE DEL RESPONSABLE ES UN CAMPO OBLIGATORIO.", 1);}
			if ( is_null($queja_id) ) { throw new Exception("NO FUE POSIBLE IDENTIFICAR EL ID DEL EXPEDIENTE INTENTE ACTUALIZANDO, DE LO CONTRARIO REPORTE ESTA FALLA A DESARROLLO DE SISTEMAS.", 1);	}
			if ( is_null($castigo) ) { throw new Exception("EL CAMPO DE SANCIÓN ES OBLIGATORIO.", 1);	}
			#almacena la info
			$this->sql = "
			INSERT INTO sanciones(
			    id,
			    queja_id,
			    nombre_pr,
			    adscripcion,
			    rfc,
			    curp,
			    cuip,
			    f_determina,
			    f_notificacion,
			    f_resolucion,
			    f_sesion,
			    castigo,
			    dias_sus,
			    oficio,
			    comentario,
			    quien_r,
			    notificado
			)
			VALUES (
				'',
				:queja_id,
				:nombre_pr,
				:adscripcion,
				:rfc,
				:curp,
				:cuip,
				:f_determina,
				:f_notificacion,
				:f_resolucion,
				:f_sesion,
				:castigo,
				:dias_sus,
				:oficio,
				:comentario,
				:quien_r,
				:notificado
			)
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(':nombre_pr',$nombre_pr,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':adscripcion',$adscripcion,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':rfc',$rfc,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':curp',$curp,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':cuip',$cuip,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':f_determina',$f_determina,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':f_notificacion',$f_notificacion,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':f_resolucion',$f_resolucion,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':f_sesion',$f_sesion,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':notificado',$notificado,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':castigo',$castigo,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(':dias_sus',$dias_s,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(':oficio',$oficio_sa,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':comentario',$comentario,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':quien_r',$quien,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->execute();
			
			return json_encode( array('status'=>'success','message'=>'LA SANCIÓN A SIDO GUARDADA DE MANERA EXITOSA') );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveVerificacion()
	{
		try {
			#recupera variables 
			session_start();
			$quien = $_SESSION['id'];
			$sancion = ( !empty($_POST['sancion']) ) ? mb_strtoupper($_POST['sancion'],'UTF-8') : NULL;
			$f_notifica_sp = ( !empty($_POST['f_notifica_sp']) ) ? mb_strtoupper($_POST['f_notifica_sp'],'UTF-8') : NULL;
			$f_notifica_rh = ( !empty($_POST['f_notifica_rh']) ) ? mb_strtoupper($_POST['f_notifica_rh'],'UTF-8') : NULL;
			$capt_rnpsp = ( !empty($_POST['capt_rnpsp']) ) ? mb_strtoupper($_POST['capt_rnpsp'],'UTF-8') : NULL;
			$f_ejec = ( !empty($_POST['f_ejec']) ) ? mb_strtoupper($_POST['f_ejec'],'UTF-8') : NULL;
			$comentario = ( !empty($_POST['comentario']) ) ? mb_strtoupper($_POST['comentario'],'UTF-8') : NULL;
			#print_r($_POST);exit;
			#agrega validaciones
			if ( is_null($sancion) ) { throw new Exception("DEBE SELECCIONAR UNA SANCIÓN.", 1);}
			#almacena la info
			$this->sql = "
			INSERT INTO verificaciones(
			    id,
			    sancion_id,
			    f_not_sp,
			    f_not_rh,
			    f_cpt_rnpsp,
			    f_ejec,
			    quien,
			    comentario
			)
			VALUES (
				'',
				:sancion_id,
				:f_not_sp,
				:f_not_rh,
				:f_cpt_rnpsp,
				:f_ejec,
				:quien,
				:comentario
			)
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':sancion_id',$sancion,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(':f_not_sp',$f_notifica_sp,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':f_not_rh',$f_notifica_rh,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':f_cpt_rnpsp',$capt_rnpsp,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':f_ejec',$f_ejec,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->bindParam(':quien',$quien,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(':comentario',$comentario,PDO::PARAM_STR|PDO::PARAM_NULL);
			$this->stmt->execute();
			
			return json_encode( array('status'=>'success','message'=>'LA VERIFICACIÓN A SIDO GUARDADA DE MANERA EXITOSA.') );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function delete_responsable()
	{
		try {
			$id = $_POST['pr'];
			
			$this->sql = "
			DELETE FROM presuntos WHERE id = ?		
			";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
			$this->stmt->execute();
			#insertar la pista de auditoria
			session_start();
			$logger = $_SESSION['id'];
			$desc = "SE A ELIMINADO UN RESPONSABLE CON ID: $id .";
			$sis = 3;#RESPO
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,4,?);";#1: INICIO DE SESION, 2: ALTA, 3: MOFDIFICACIÓN, 4: ELIMINACION.
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->bindParam(3,$sis,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE A ELIMINADO A EL RESPONSABLE DE MANERA EXITOSA.' ) );
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getOnlyEdoProcesal($queja_id)
	{
		try {
			$this->sql = "SELECT q.*, CONCAT(pe.nombre,' ',pe.ap_pat,' ',pe.ap_mat) AS n_jefe,
			pe.id AS jefe_id, CONCAT(per.nombre,' ',per.ap_pat,' ',per.ap_mat) AS n_analista,
			per.id AS analista_id, p.nombre AS n_presunto, p.id AS presunto_id,p.genero, p.procedencia,c.nombre AS n_cargo
			FROM quejas_respo AS q
			LEFT JOIN presuntos AS p ON p.queja_id = q.queja_id and p.a_determina = 'RES'
			LEFT JOIN cargos AS c ON c.id = p.cargo_id 
			LEFT JOIN personal AS pe ON pe.id = q.jefatura
			LEFT JOIN personal AS per ON per.id = q.analista
			WHERE q.queja_id = ? AND q.estado = 'ACTIVO' LIMIT 1			
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
			$result['eprocesal'] = $this->result;
			$this->sql = "
			SELECT * FROM presuntos WHERE queja_id = :queja_id AND a_determina = 1
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
			$result['presunto'] = $this->result;
			
			return $result;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveEdoProcesal()
	{
		try {
			$ultimo = 0;
			$queja_id = $_POST['exp_id'];
			#BUSCAR ESTADO PROCESAL PREVIO
			$this->sql = "SELECT * FROM quejas_respo WHERE queja_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$resultado = $this->stmt->rowCount();
			if ($resultado > 0 ) {
				throw new Exception("YA SE A GENERADO UN ESTADO PROCESAL PREVIO DE ESTE EXPEDIENTE.", 1);				
			}
			$name 	= ( !empty($_POST['nombre']) ) ? mb_strtoupper($_POST['nombre']) : 'S/A' ;
			$ap_pat = ( !empty($_POST['ap_pat']) ) ? mb_strtoupper($_POST['ap_pat']) : 'S/A' ;
			$ap_mat = ( !empty($_POST['ap_mat']) ) ? mb_strtoupper($_POST['ap_mat']) : 'S/A' ;
			$full_name = $name. " ".$ap_pat." ".$ap_mat;
			$genero = $_POST['genero'];
			$cargo = $_POST['cargo'];
			$adscripcion = $_POST['adscripcion'];
			$autoridad = ( !empty($_POST['autoridad']) ) ? mb_strtoupper($_POST['autoridad']) : 'S/A' ;
			$e_procesal = ( !empty($_POST['e_procesal']) ) ? mb_strtoupper($_POST['e_procesal']) : 'S/A' ;
			$jefe_id = ( !empty($_POST['jefe_id']) ) ? $_POST['jefe_id'] : 'S/J' ;
			$analista_id = ( !empty($_POST['analista_id']) ) ? $_POST['analista_id'] : 'S/A' ;
			$oficio = ( !empty($_POST['oficio']) ) ? $_POST['oficio'] : 'S/A' ;
			$fecha = ( !empty($_POST['fecha']) ) ? $_POST['fecha'] : 'S/A' ;
			$semana = ( !empty($_POST['semana']) ) ? $_POST['semana'] : 'S/A' ;
			$fojas = ( !empty($_POST['fojas']) ) ? $_POST['fojas'] : 'S/A' ;
			$t_doc = ( !empty($_POST['t_doc']) ) ? $_POST['t_doc'] : 'S/A' ;
			$comentarios = ( !empty($_POST['comentarios']) ) ? mb_strtoupper($_POST['comentarios']) : 'S/C' ;
			$motivo = ( !empty($_POST['motivo_sc']) ) ? mb_strtoupper($_POST['motivo_sc'],'utf-8') : NULL ;
			$this->sql = "INSERT INTO quejas_respo (id, queja_id, oficio, motivo, comentarios, jefatura, analista, estado, e_procesal, autoridad, f_acuse, n_semana, fojas, t_doc) 
			VALUES (
			'',
			?,
			?,
			'$motivo',
			?,
			?,
			?,
			1,
			?,
			?,
			?,
			?,
			?,
			?
			);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$comentarios,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$jefe_id,PDO::PARAM_INT);
			$this->stmt->bindParam(5,$analista_id,PDO::PARAM_INT);
			
			$this->stmt->bindParam(6,$e_procesal,PDO::PARAM_INT);
			$this->stmt->bindParam(7,$autoridad,PDO::PARAM_INT);
			$this->stmt->bindParam(8,$fecha,PDO::PARAM_STR);
			$this->stmt->bindParam(9,$semana,PDO::PARAM_INT);
			$this->stmt->bindParam(10,$fojas,PDO::PARAM_INT);
			$this->stmt->bindParam(11,$t_doc,PDO::PARAM_INT);
			$this->stmt->execute();
			#Recuperar 
			$ultimo = $this->pdo->lastInsertID();
			#Insertar el presunto responsable con el distintivo de que lo registro respo.
			$this->sql = "INSERT INTO presuntos (
				id, queja_id, genero, nombre, cargo_id, adscripcion, a_determina
			) 
			VALUES (
			'',
			?,
			?,
			?,
			?,
			?,
			2
			);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$genero);
			$this->stmt->bindParam(3,$full_name,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$cargo,PDO::PARAM_INT);
			$this->stmt->bindParam(5,$adscripcion,PDO::PARAM_INT);
			$this->stmt->execute();
			#insertar la pista de auditoria
			if ( $ultimo > 0 ) {
				session_start();
				$logger = $_SESSION['id'];
				$desc = "SE A INSERTADO UN NUEVO ESTADO PROCESAL CON ID: $ultimo .";
				$sis = 3;#RESPO
				$sql_pista = "INSERT INTO 
				pista_auditoria (id, descripcion,person_id,tipo, sistema) 
				VALUES 
				('',?,?,2,?);";#1: INICIO DE SESION, 2: ALTA, 3: MOFDIFICACIÓN, 4: ELIMINACION.
				$stmt = $this->pdo->prepare($sql_pista);
				$stmt->bindParam(1,$desc,PDO::PARAM_STR);
				$stmt->bindParam(2,$logger,PDO::PARAM_INT);
				$stmt->bindParam(3,$sis,PDO::PARAM_INT);
				$stmt->execute();
				return json_encode( array('status'=>'success','message'=>'SE HA GENERADO EL ESTADO PROCESAL DE MANERA EXITOSA.') );
			}else{
				throw new Exception("Ocurrio un error al tratar de eliminar.", 1);
			}
						
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function updateEdoProcesal()
	{
		try {
			#queja_id
			$f_sesion = (!empty($_POST['f_sesion'])) ? $_POST['f_sesion'] : ''  ;
			$f_notifica = (!empty($_POST['f_notifica'])) ? $_POST['f_notifica'] : ''  ;
			$f_resolucion = (!empty($_POST['f_resolucion'])) ? $_POST['f_resolucion'] : ''  ;
			#EDITAR EL PRESUNTO
			$presunto_id = $_POST['presunto_id'];
			$nombre = $_POST['name'];
			$genero = $_POST['genero'];
			$rfc = $_POST['rfc'];
			$curp = $_POST['curp'];
			$cuip = $_POST['cuip'];
			$t_puesto = $_POST['t_puesto'];
			$procedencia = $_POST['procedencia'];
			$cargo_id = $_POST['cargo'];
			$comentarios = $_POST['comentarios'];
			$this->sql = "UPDATE presuntos SET 
			    nombre = :nombre ,
			    genero = :genero ,
			    rfc = :rfc ,
			    curp = :curp ,
			    cuip = :cuip ,
			    t_puesto = :t_puesto ,
			    procedencia = :procedencia ,
			    cargo_id = :cargo_id ,
			    a_determina = 1 ,
			    comentarios = :comentarios
			 WHERE id = :id;";
	    	$this->stmt=$this->pdo->prepare($this->sql);
	    	$this->stmt->bindParam(':nombre',$nombre,PDO::PARAM_INT);
	    	$this->stmt->bindParam(':genero',$genero,PDO::PARAM_INT);
	    	$this->stmt->bindParam(':rfc',$rfc,PDO::PARAM_INT);
	    	$this->stmt->bindParam(':curp',$curp,PDO::PARAM_INT);
	    	$this->stmt->bindParam(':cuip',$cuip,PDO::PARAM_INT);
	    	$this->stmt->bindParam(':t_puesto',$t_puesto,PDO::PARAM_INT);
	    	$this->stmt->bindParam(':procedencia',$procedencia,PDO::PARAM_INT);
	    	$this->stmt->bindParam(':cargo_id',$cargo_id,PDO::PARAM_INT);
	    	$this->stmt->bindParam(':comentarios',$comentarios,PDO::PARAM_INT);
	    	$this->stmt->bindParam(':id',$presunto_id,PDO::PARAM_INT);
	    	$this->stmt->execute();
	    	$e_procesal		= $_POST['e_procesal'];
	    	$exp_id			= ( !empty($_POST['queja_id']) ) ? $_POST['queja_id'] : NULL;
    	    if ( $e_procesal == 3 ) {
    	    	#Deshabilitar las demas demandas
    	    	$sql_dev = "UPDATE devoluciones SET estado = 2 WHERE queja_id = ?;";
    	    	$this->stmt=$this->pdo->prepare($sql_dev);
    	    	$this->stmt->bindParam(1,$exp_id,PDO::PARAM_INT);
    	    	$this->stmt->execute();
    	    	#SI ES DEVOLUCION INSERTA LA DEVOLUCION 
    	    	$sql_dev = "INSERT INTO devoluciones (id, queja_id, f_devolucion, f_oficio, oficio, motivo, estado, archivo) VALUES ('',?,DATE(NOW()),?,?,?,1,?);";
    	    	$this->stmt=$this->pdo->prepare($sql_dev);
    	    	$this->stmt->bindParam(1,$exp_id,PDO::PARAM_INT);
    	    	$this->stmt->bindParam(2,$fecha,PDO::PARAM_STR);
    	    	$this->stmt->bindParam(3,$oficio,PDO::PARAM_STR);
    	    	$this->stmt->bindParam(4,$motivo,PDO::PARAM_STR);
    	    	$this->stmt->bindParam(5,$content,PDO::PARAM_LOB);
    	    	$this->stmt->execute();
    	    	#ACTULIZAR EL ESTADO DE LA QUEJA_RESPO 
    	    	$this->sql = "UPDATE quejas_respo SET estado = 2 WHERE queja_id = ?";
    	    	$this->stmt = $this->pdo->prepare($this->sql);
    	    	$this->stmt->bindParam(1,$exp_id,PDO::PARAM_INT);
    	    	$this->stmt->execute();

    	    	$this->sql = "INSERT INTO quejas_respo (id, queja_id, oficio, comentarios, jefatura, analista, estado, e_procesal, autoridad, f_acuse, n_semana, fojas, t_doc,motivo) 
    			VALUES (
    				'',
    				:queja_id,
    				:oficio,
    				:comentarios,
    				:jefatura,
    				:analista,
    				1,
    				:e_procesal,
    				:autoridad,
    				:f_acuse,
    				:n_semana,
    				:fojas,
    				:t_doc,
    				:motivo
    			);";
    			$this->stmt = $this->pdo->prepare($this->sql);
    		    $this->stmt->bindParam(':queja_id',$exp_id,PDO::PARAM_INT);
    		    $this->stmt->bindParam(':oficio',$oficio,PDO::PARAM_STR);
    		    $this->stmt->bindParam(':comentarios',$comentarios,PDO::PARAM_STR);
    		    $this->stmt->bindParam(':jefatura',$jefe_id,PDO::PARAM_STR);
    		    $this->stmt->bindParam(':analista',$analista_id,PDO::PARAM_STR);
    		    $this->stmt->bindParam(':e_procesal',$e_procesal,PDO::PARAM_INT);
    		    $this->stmt->bindParam(':autoridad',$autoridad,PDO::PARAM_STR);
    		    $this->stmt->bindParam(':f_acuse',$fecha,PDO::PARAM_STR);
    		    $this->stmt->bindParam(':n_semana',$semana,PDO::PARAM_INT);
    		    $this->stmt->bindParam(':fojas',$fojas,PDO::PARAM_INT);
    		    $this->stmt->bindParam(':t_doc',$t_doc,PDO::PARAM_INT);
    		    $this->stmt->bindParam(':motivo',$motivo,PDO::PARAM_STR);
    		    $this->stmt->execute();
    		    #$exp_id = $this->pdo->lastInsertId();
    		    
    	    }else{
    	    	$exp_respo		= $_POST['exp_respo'];
			    	
			    $autoridad		= $_POST['autoridad'];
			    $jefe_id		= $_POST['jefe_id'];	
			    $analista_id	= $_POST['analista_id'];
			    $oficio			= $_POST['n_oficio'];
			    $fecha			= $_POST['f_acuse'];
			    //$semana			= $_POST['semana'];
			    $fojas			= $_POST['fojas'];
			    $t_doc			= $_POST['t_doc'];
    	    	$this->sql = "
    		    UPDATE quejas_respo 
    		    SET 
    		    
    		    jefatura= :jefatura ,
    		    analista= :analista ,
    		    e_procesal= :e_procesal ,
    		    autoridad= :autoridad ,
    		    f_acuse= :f_acuse ,
    		    n_semana= :n_semana ,
    		    fojas= :fojas ,
    		    t_doc= :t_doc
    		     WHERE id= :exp_respo
    		    ";
    		    $this->stmt = $this->pdo->prepare($this->sql);
    		    
    		    
    		    $this->stmt->bindParam(':jefatura',$jefe_id,PDO::PARAM_STR);
    		    $this->stmt->bindParam(':analista',$analista_id,PDO::PARAM_STR);
    		    $this->stmt->bindParam(':e_procesal',$e_procesal,PDO::PARAM_INT);
    		    $this->stmt->bindParam(':autoridad',$autoridad,PDO::PARAM_STR);
    		    $this->stmt->bindParam(':f_acuse',$fecha,PDO::PARAM_STR);
    		    $this->stmt->bindParam(':n_semana',$semana,PDO::PARAM_INT);
    		    $this->stmt->bindParam(':fojas',$fojas,PDO::PARAM_INT);
    		    $this->stmt->bindParam(':t_doc',$t_doc,PDO::PARAM_INT);
    		    
    		    $this->stmt->bindParam(':exp_respo',$exp_respo,PDO::PARAM_STR);
    		    $this->stmt->execute();
    	    }

			return json_encode( array('status'=>'success','message'=>'EL ESTADO PROCESAL SE ACTUALIZADO DE MANERA EXITOSA.') );
			exit;
			session_start();
			#recuperar los campos 
		    $exp_respo		= $_POST['exp_respo'];
		    $exp_id			= ( !empty($_POST['exp_id']) ) ? $_POST['exp_id'] : NULL;	
		    $autoridad		= $_POST['autoridad'];	
		    $e_procesal		= $_POST['e_procesal'];
		    
		    $jefe_id		= $_POST['jefe_id'];	
		    $analista_id	= $_POST['analista_id'];
		    	
		    $oficio			= $_POST['oficio'];
		    $fecha			= $_POST['fecha'];
		    $semana			= $_POST['semana'];
		    $fojas			= $_POST['fojas'];
		    $t_doc			= $_POST['t_doc'];
		    $exp_id			= $_POST['exp_id'];
		    if ( !empty($_POST['motivo']) ) {
		    	$motivo			= mb_strtoupper($_POST['motivo'],'utf-8');
		    }elseif (!empty($_POST['motivo_sc'])) {
		    	$motivo			= mb_strtoupper($_POST['motivo_sc'],'utf-8');
		    }else{
		    	$motivo			= "";
		    }
		    
		    $comentarios	= mb_strtoupper($_POST['comentarios'],'utf-8');	
		    #Validacion de los campos del archivo
		    
		    if ( !empty($_FILES['file']['name']) ) {
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
		    	$name = $_FILES['file']['name'];
		    	$type = $_FILES['file']['type'];
		    	$size = $_FILES['file']['size'];
		    	$destino  = $_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/';
		    	#Mover el Doc
		    	move_uploaded_file($_FILES['file']['tmp_name'],$destino.$name);
		    	#abrir el archivo
		    	$file 		= fopen($destino.$name,'r');
		    	$content 	= fread($file, $size);
		    	$content 	= addslashes($content);
		    	fclose($file);
		    	#Eliminar  el archivo 
		    	unlink($_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/'.$name);		# code...
		    }else{
		    	$content = NULL;
		    }
		    
		    #Definir el Query	
		    if ( $e_procesal == 3 ) {
		    	#Deshabilitar las demas demandas
		    	$sql_dev = "UPDATE devoluciones SET estado = 2 WHERE queja_id = ?;";
		    	$this->stmt=$this->pdo->prepare($sql_dev);
		    	$this->stmt->bindParam(1,$exp_id,PDO::PARAM_INT);
		    	$this->stmt->execute();
		    	#SI ES DEVOLUCION INSERTA LA DEVOLUCION 
		    	$sql_dev = "INSERT INTO devoluciones (id, queja_id, f_devolucion, f_oficio, oficio, motivo, estado, archivo) VALUES ('',?,DATE(NOW()),?,?,?,1,?);";
		    	$this->stmt=$this->pdo->prepare($sql_dev);
		    	$this->stmt->bindParam(1,$exp_id,PDO::PARAM_INT);
		    	$this->stmt->bindParam(2,$fecha,PDO::PARAM_STR);
		    	$this->stmt->bindParam(3,$oficio,PDO::PARAM_STR);
		    	$this->stmt->bindParam(4,$motivo,PDO::PARAM_STR);
		    	$this->stmt->bindParam(5,$content,PDO::PARAM_LOB);
		    	$this->stmt->execute();
		    	#ACTULIZAR EL ESTADO DE LA QUEJA_RESPO 
		    	$this->sql = "UPDATE quejas_respo SET estado = 2 WHERE queja_id = ?";
		    	$this->stmt = $this->pdo->prepare($this->sql);
		    	$this->stmt->bindParam(1,$exp_id,PDO::PARAM_INT);
		    	$this->stmt->execute();

		    	$this->sql = "INSERT INTO quejas_respo (id, queja_id, oficio, comentarios, jefatura, analista, estado, e_procesal, autoridad, f_acuse, n_semana, fojas, t_doc,motivo) 
				VALUES (
					'',
					:queja_id,
					:oficio,
					:comentarios,
					:jefatura,
					:analista,
					1,
					:e_procesal,
					:autoridad,
					:f_acuse,
					:n_semana,
					:fojas,
					:t_doc,
					:motivo
				);";
				$this->stmt = $this->pdo->prepare($this->sql);
			    $this->stmt->bindParam(':queja_id',$exp_id,PDO::PARAM_INT);
			    $this->stmt->bindParam(':oficio',$oficio,PDO::PARAM_STR);
			    $this->stmt->bindParam(':comentarios',$comentarios,PDO::PARAM_STR);
			    $this->stmt->bindParam(':jefatura',$jefe_id,PDO::PARAM_STR);
			    $this->stmt->bindParam(':analista',$analista_id,PDO::PARAM_STR);
			    $this->stmt->bindParam(':e_procesal',$e_procesal,PDO::PARAM_INT);
			    $this->stmt->bindParam(':autoridad',$autoridad,PDO::PARAM_STR);
			    $this->stmt->bindParam(':f_acuse',$fecha,PDO::PARAM_STR);
			    $this->stmt->bindParam(':n_semana',$semana,PDO::PARAM_INT);
			    $this->stmt->bindParam(':fojas',$fojas,PDO::PARAM_INT);
			    $this->stmt->bindParam(':t_doc',$t_doc,PDO::PARAM_INT);
			    $this->stmt->bindParam(':motivo',$motivo,PDO::PARAM_STR);
			    $this->stmt->execute();
			    #$exp_id = $this->pdo->lastInsertId();
			    
		    }else{
		    	$this->sql = "
			    UPDATE quejas_respo 
			    SET 
			    oficio= :oficio ,
			    comentarios= :comentarios ,
			    jefatura= :jefatura ,
			    analista= :analista ,
			    e_procesal= :e_procesal ,
			    autoridad= :autoridad ,
			    f_acuse= :f_acuse ,
			    n_semana= :n_semana ,
			    fojas= :fojas ,
			    t_doc= :t_doc,
			    motivo = :motivo
			     WHERE id= :exp_respo
			    ";
			    $this->stmt = $this->pdo->prepare($this->sql);
			    $this->stmt->bindParam(':oficio',$oficio,PDO::PARAM_STR);
			    $this->stmt->bindParam(':comentarios',$comentarios,PDO::PARAM_STR);
			    $this->stmt->bindParam(':jefatura',$jefe_id,PDO::PARAM_STR);
			    $this->stmt->bindParam(':analista',$analista_id,PDO::PARAM_STR);
			    $this->stmt->bindParam(':e_procesal',$e_procesal,PDO::PARAM_INT);
			    $this->stmt->bindParam(':autoridad',$autoridad,PDO::PARAM_STR);
			    $this->stmt->bindParam(':f_acuse',$fecha,PDO::PARAM_STR);
			    $this->stmt->bindParam(':n_semana',$semana,PDO::PARAM_INT);
			    $this->stmt->bindParam(':fojas',$fojas,PDO::PARAM_INT);
			    $this->stmt->bindParam(':t_doc',$t_doc,PDO::PARAM_INT);
			    $this->stmt->bindParam(':motivo',$motivo,PDO::PARAM_STR);
			    $this->stmt->bindParam(':exp_respo',$exp_respo,PDO::PARAM_STR);
			    $this->stmt->execute();
		    }
		    
		    #Si el nombre del presunto no esta vacio insertalo
		    if ( empty($_POST['presunto_id']) ) {
		    	
		    	$nombre			= mb_strtoupper($_POST['nombre'],'utf-8');
		    	$ap_pat			= mb_strtoupper($_POST['ap_pat'],'utf-8');
		    	$ap_mat			= mb_strtoupper($_POST['ap_mat'],'utf-8');
		    	$full_name 		= $nombre. " ".$ap_pat." ".$ap_mat;
		    	$genero			= $_POST['genero'];
		    	$cargo			= $_POST['cargo'];
		    	$adscripcion	= $_POST['adscripcion'];

		    	$sql_presunto 	= "
		    	INSERT INTO presuntos (
					id, queja_id, genero, nombre, cargo_id, adsp, a_determina
				) 
				VALUES (
				'',
				?,
				?,
				?,
				?,
				?,
				2
				)
		    	";
		    	$stmt_presunto 	= $this->pdo->prepare( $sql_presunto ); 
		    	$stmt_presunto->bindParam(1,$exp_id,PDO::PARAM_INT);
		    	$stmt_presunto->bindParam(2,$genero, PDO::PARAM_INT);
		    	$stmt_presunto->bindParam(3,$full_name, PDO::PARAM_STR);
		    	$stmt_presunto->bindParam(4,$cargo, PDO::PARAM_INT);
		    	$stmt_presunto->bindParam(5,$adscripcion, PDO::PARAM_INT);
		    	$stmt_presunto->execute(); 

		    	$p = $this->pdo->lastInsertId();
		    	#insertar la pista de auditoria
		    	
		    	$logger = $_SESSION['id'];
		    	$desc = "SE A INSERTADO UN RESPONSABLE NUEVO EN LA TABLA DE presuntos CON ID: $p .";
		    	$sis = 3;#RESPO
		    	$sql_pista = "INSERT INTO 
		    	pista_auditoria (id, descripcion,person_id,tipo, sistema) 
		    	VALUES 
		    	('',?,?,2,?);";#1: INICIO DE SESION, 2: ALTA, 3: MOFDIFICACIÓN, 4: ELIMINACION.
		    	$stmt = $this->pdo->prepare($sql_pista);
		    	$stmt->bindParam(1,$desc,PDO::PARAM_STR);
		    	$stmt->bindParam(2,$logger,PDO::PARAM_INT);
		    	$stmt->bindParam(3,$sis,PDO::PARAM_INT);
		    	$stmt->execute();
		    }
			#insertar la pista de auditoria
			
			$logger = $_SESSION['id'];
			$desc = "SE A EDITADO EL REGISTRO DE LA TABLA DE quejas_respo CON ID: $exp_respo .";
			$sis = 3;#RESPO
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,3,?);";#1: INICIO DE SESION, 2: ALTA, 3: MOFDIFICACIÓN, 4: ELIMINACION.
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->bindParam(3,$sis,PDO::PARAM_INT);
			$stmt->execute();
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getCedula($queja_id)
	{
		try {
			$aux = array();
			$this->sql = "SELECT q.*,p.nombre AS n_procedencia, t.nombre AS n_tramite,
			e.nombre AS n_estado,DATE(q.created_at) AS f_apertura
			FROM quejas AS q
			LEFT JOIN procedencias AS p ON p.id = q.procedencia
			LEFT JOIN tipos_tramite AS t ON t.id = q.t_tramite
			LEFT JOIN estado_guarda AS e ON e.id = q.estado
			WHERE q.id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
			$aux['queja'] = $this->result;
			#OPINIONES DE LOS ABOGADOS DE D.I.
			$this->sql = "SELECT *
			FROM opiniones_inv AS o
			WHERE o.id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			if ($this->stmt->rowCount() > 0 ) {
				$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
				$aux['opiniones'] = $this->result;
			}else{
				$aux['opiniones'] = NULL;
			}
			#Buscar los datos de los apersonamientos
			$this->sql = "SELECT *
			FROM apersonamientos 
			WHERE queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			if ($this->stmt->rowCount() > 0 ) {
				$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
				$aux['apersonamientos'] = $this->result;
			}else{
				$aux['apersonamientos'] = NULL;
			}
			#buscar las presuntas conductas
			$this->sql = "SELECT c.id, UPPER(c.nombre) AS n_conducta, l.nombre AS n_ley FROM p_conductas AS pc
			INNER JOIN catalogo_conductas AS c ON c.id = pc.conducta_id
			INNER JOIN leyes AS l ON l.id = c.ley_id
			WHERE pc.queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux['p_conductas'] = $this->result;
			#buscar las vias de recepcion
			$this->sql = "SELECT v.id, c.nombre AS n_via FROM vias_recepcion AS v
			INNER JOIN catalogo_vias AS c ON c.id = v.via_id
			WHERE v.queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux['vias'] = $this->result;
			#buscar datos de la ubicacion
			$this->sql = "SELECT u.*, UPPER(m.nombre) AS n_municipio FROM ubicacion_referencia AS u
			INNER JOIN municipios AS m ON m.id = u.municipio
			WHERE u.queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
			$aux['ubicacion'] = $this->result;
			#buscar datos de los quejosos
			$this->sql = "SELECT q.*, UPPER(m.nombre) AS n_municipio FROM quejosos AS q
			LEFT JOIN municipios AS m ON m.id = q.municipio_id
			WHERE q.queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux['quejosos'] = $this->result;
			#buscar presuntos responsables
			$this->sql = "SELECT p.*, UPPER(m.nombre) AS n_municipio, c.nombre AS n_cargo, pr.nombre AS n_procedencia
			FROM presuntos AS p
			LEFT JOIN municipios AS m ON m.id = p.municipio_id
			LEFT JOIN cargos AS c ON c.id = p.cargo_id
			LEFT JOIN procedencias AS pr ON pr.id = p.procedencia
			WHERE p.queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux['presuntos'] = $this->result;
			#buscar UNIDADES involucradas
			$this->sql = "SELECT * FROM u_implicadas AS u
			WHERE queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux['unidades'] = $this->result;

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
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux['acumuladas'] = $this->result;
			#buscar documnetos del expediente 
			$this->sql = "
			SELECT
			    id,nombre,descripcion
			FROM
			    documentos_quejas
			WHERE queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux['documentos'] = $this->result;

			#buscar documnetos de sapa
			/*$this->sql = "
			SELECT
			    id,asunto,comentario
			FROM
			    documentos_sapa
			WHERE queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux['documentos_sapa'] = $this->result;*/
			
			
			#buscar las devoluciones 
			$this->sql = "
			SELECT
			    id,queja_id,motivo,f_acuse,oficio
			FROM
			    quejas_respo
			WHERE queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux['devoluciones'] = $this->result;
			#buscar las opiniones 
			$this->sql = "
			SELECT
			    CONCAT(p.nombre, ' ',p.ap_pat,' ',p.ap_mat) AS abogado, o.comentario,o.created_at
			FROM
			    opiniones_analistas AS o 
			INNER JOIN 
				personal AS p ON p.id = o.persona_id
			WHERE queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			if ( $this->stmt->rowCount() >0 ) {
				$aux['opiniones'] = $this->result;
			}else{
				$aux['opiniones'] = 0;
			}
			
			#buscar persona al que se encuentra turnado
			$this->sql = "
			SELECT
			    CONCAT(p.nombre, ' ',p.ap_pat,' ',p.ap_mat) AS turnado
			FROM
			    e_turnados AS e
			INNER JOIN 
				personal AS p ON p.id = e.persona_id
			WHERE e.queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			if ( $this->stmt->rowCount() > 0 ) {
				$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
				$aux['turnado'] = $this->result->turnado;
			}else{
				$aux['turnado'] = 'NO SE A TURNADO EL EXPEDIENTE';
			}
			
			#BUSCAR EN QUEJAS DE RESPO 
			#Variable requerida para un contador 
			$f_turno = "";
			$this->sql = "
			SELECT *,DATE(created_at) as f_turno FROM quejas_respo
			WHERE queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			if($this->stmt->rowCount() > 0 ){
				$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
				$f_turno = $this->result->f_turno;
				$aux['qr'] = $this->result;
				$aux['f_turno'] = $f_turno;
			}else{
				$aux['f_turno'] = 'SIN FECHA DE TURNADO';
			}
			
			$this->sql = "
			SELECT id, queja_id, f_devolucion, f_oficio, oficio, motivo, estado, created_at FROM devoluciones
			WHERE queja_id = ? AND estado = 1;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
			$aux['devolucion'] = $this->result;

			$this->sql = "
			SELECT * FROM reservas
			WHERE queja_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
			$aux['reserva'] = $this->result;

			#Contador de dias trabajados por las subdoirecciones
			$this->sql = "SELECT f_turno FROM e_turnados WHERE queja_id = ? and estado != 3";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
			if ( $this->stmt->rowCount() > 0 ) {
				$f_turno= $this->result->f_turno;
			}
			$this->sql = "SELECT f_acuse,autoridad FROM quejas_respo WHERE queja_id = ? and estado = 1";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );

			if ( $this->stmt->rowCount() > 0  ) {
				$f_acuse = $this->result->f_acuse;
				##Fecha inicial para SC
				$f_ini = $this->result->f_acuse;
				##Buscar el fin como improcedencia, reserva o demandas
				$this->sql = "SELECT f_reserva FROM reservas WHERE queja_id = ? ;";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ) {
					$f_fin = $this->stmt->fetch( PDO::FETCH_OBJ );
				}
				$this->sql = "SELECT f_turno FROM acuerdos_improcedencia WHERE queja_id = ? ;";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ) {
					$f_fin = $this->stmt->fetch( PDO::FETCH_OBJ );
				}
				$this->sql = "
				SELECT f_acuse FROM demandas 
				WHERE queja_id = ? 
				ORDER BY id DESC;
				";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
				if ( $this->stmt->rowCount() > 0 ) {
					$f_fin = $this->result->f_acuse;
				}else{
					$f_fin = date('Y-m-d');
				}
				$aux['f_sc'] = $this->operacionesFechas('-',$f_fin,$f_ini)->resta;
			}else{ 
				$f_acuse = date('Y-m-d'); 
				$aux['f_sc'] = "NO SE ESTA TRABAJANDO AQUI.";
			}
			$aux['f_sapa'] = $this->operacionesFechas('-',$f_acuse,$f_turno)->resta;

			#obtener los apersonamientos de las demandas
			$this->sql = "SELECT * FROM demandas WHERE queja_id = ? and t_demanda = 1 ORDER BY id DESC;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$demanda = $this->stmt->fetch( PDO::FETCH_OBJ );

			
			#$aux['presuntos'] = $this->result; 

			$this->sql = "
			SELECT DATEDIFF(a.f_apersonamiento, r.f_resolucion) AS resta FROM demandas AS d 
			INNER JOIN r_demanda as r on r.demanda_id = d.id 
			INNER JOIN apersonamientos AS a ON a.queja_id = d.id 
			WHERE d.queja_id AND d.t_demanda = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$conta = $this->stmt->fetch( PDO::FETCH_OBJ );
			$aux['conta_2'] = $conta;
			#SEGIMIENTO DEL ESTADO ACTUAL DEL EXPEDIENTE 
			$this->sql = "SELECT * FROM apersonamientos WHERE queja_id = ? ORDER BY id ASC LIMIT 1";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$seguimiento = $this->stmt->fetch(PDO::FETCH_OBJ);
			$aux['apersona_uno'] = $seguimiento;

			$this->sql = "SELECT * FROM resoluciones WHERE queja_id = ? ORDER BY id DESC LIMIT 1";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$resolucion = $this->stmt->fetch(PDO::FETCH_OBJ);
			$aux['resolucion_ape'] = $resolucion;#Resolucion del apersonamiento

			$this->sql = "
			SELECT d.*,r.f_resolucion FROM demandas AS d
			LEFT JOIN r_demanda AS r ON r.demanda_id = d.id 
			WHERE d.queja_id = ? ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$demandas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			if ( $this->stmt->rowCount() > 0 ) {
				$aux['demandas'] = $demandas;#Resolucion del apersonamiento
			}else{
				$aux['demandas'] = false;
			}
			#CONTADORES DEL PUNTO 14
			$this->sql = "
			SELECT fecha FROM resoluciones 
			WHERE queja_id = ? ORDER BY id DESC LIMIT 1";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			if ( $this->stmt->rowCount() > 0 ) {
				$f_res1 = $this->stmt->fetch(PDO::FETCH_OBJ)->fecha;#fecha de la resolucion
				$this->sql = "
				SELECT id, f_acuse FROM demandas 
				WHERE queja_id = ? AND t_demanda = 1 ORDER BY id DESC LIMIT 1";
				$this->stmt = $this->pdo->prepare( $this->sql );
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				if ($this->stmt->rowCount() > 0) {
					$res1 = $this->stmt->fetch(PDO::FETCH_OBJ);
					$f_acuse = $res1->f_acuse;
					$aux['c_res_dem'] = $this->operacionesFechas('-',$f_acuse,$f_res1)->resta;
					# BUSCAR LA FECHA DE LA RESOLUCION DE LA PRIMER DEMANDA
					$this->sql = "
					SELECT f_resolucion FROM r_demanda 
					WHERE demanda_id = ? LIMIT 1";
					$this->stmt = $this->pdo->prepare( $this->sql );
					$this->stmt->bindParam(1,$res1->id,PDO::PARAM_INT);
					$this->stmt->execute();
					if ($this->stmt->rowCount() > 0) {
						$res2 = $this->stmt->fetch(PDO::FETCH_OBJ);
						$aux['c_rdem_dem2'] = $this->operacionesFechas('-',$res2->f_resolucion,$f_acuse)->resta;
						#Buscar fecha de la segunda demanda 
						$this->sql = "
						SELECT id, f_acuse FROM demandas 
						WHERE queja_id = ? AND t_demanda = 2 ORDER BY id DESC LIMIT 1";
						$this->stmt = $this->pdo->prepare( $this->sql );
						$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
						$this->stmt->execute();
						if ($this->stmt->rowCount() > 0){
							$res3 = $this->stmt->fetch(PDO::FETCH_OBJ);
							#Buscar la fecha de la resolucion de la segunda demanda
							$this->sql = "
							SELECT f_resolucion FROM r_demanda 
							WHERE demanda_id = ? LIMIT 1";
							$this->stmt = $this->pdo->prepare( $this->sql );
							$this->stmt->bindParam(1,$res3->id,PDO::PARAM_INT);
							$this->stmt->execute();
							if ($this->stmt->rowCount() > 0){
								$f_resolucion = $this->stmt->fetch(PDO::FETCH_OBJ)->f_resolucion;
								$aux['c_rdem2_res2'] = $this->operacionesFechas('-',$f_resolucion,$res3->f_acuse)->resta;
							}else{
								$aux['c_rdem2_res2'] = false;
							}
						}else{
							$aux['c_rdem2_res2'] = false;
						}
					}else{
						$aux['c_rdem_dem2'] = false;
					}
				}else{
					$aux['c_res_dem'] = false;
				}
			}else{
				$aux['c_res_dem'] = false;
			}
			#echo $aux['queja']->cve_exp;
			#$pass = "7W+Th_+uTh2X";
			$pass = "";
			$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',$pass));
			$pdo_inspeccion = $this->getPDO();
			$this->sql = "SELECT of.* FROM oficios_generados_referencia AS ofr
			INNER JOIN oficios_generados AS of ON of.id_generado = ofr.id_generado
			WHERE referencia LIKE '%".$aux['queja']->cve_exp."%';";
			$this->stmt = $pdo_inspeccion->prepare($this->sql);
			$this->stmt->execute();
			if ($this->stmt->rowCount() > 0 ) {
				$aux['oficios_generados'] = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			}else{
				$aux['oficios_generados'] = array();
			}
			

			return $aux;
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
	public function saveOpinion()
	{
		try {
			session_start();
			$queja_id = $_POST['queja_id'];
			
			$persona_id = $_SESSION['id'];
			$comentario = ( !empty( $_POST['comentario'] ) ) ? mb_strtoupper($_POST['comentario'], 'utf-8') : NULL ;
			if (is_null($comentario)) {
			 	throw new Exception("DEBES DE AGREGAR UN COMENTARIO.", 1);
			} 
			#Buscar el id de la queja
			/*$this->sql = "SELECT queja_id FROM quejas_respo WHERE id = ?";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$qd_res,PDO::PARAM_INT);
			$this->stmt->execute();
			$queja_id = $qd_res;
			*/
			#insertar la opinion
			$this->sql = "INSERT INTO 
			opiniones_analistas (id,persona_id,queja_id,comentario) 
			VALUES ('',?,?,?);
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$persona_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(3,$comentario,PDO::PARAM_STR);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'SE A GUARDADO LA OBSERVACIÓN DE MANERA EXITOSA.') );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getConductasRespo()
	{
		try {
			#buscar los expedientes turnados a respo
			$this->sql = "SELECT id, nombre FROM catalogo_conductas";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveConductaRespo()
	{
		try {
			$nombre = ( !empty($_POST['nombre']) ) ? mb_strtoupper($_POST['nombre'],'utf-8') : NULL ;
			if( is_null( $nombre ) ){
				throw new Exception("DEBE DE ESCRIBIR LA CONDUCTA, EL CAMPO NO DEBE ESTAR VACIO.", 1);
				
			}
			#buscar los expedientes turnados a respo
			$this->sql = "INSERT INTO conductas_respo(id,nombre) VALUES ('',?) ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$nombre);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE A INSERTADO LA CONDUCTA DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveAbogadoRes()
	{
		try {
			session_start();
			$jefe = ( isset($_POST['jefe_id']) && !empty($_POST['jefe_id']) ) ? $_POST['jefe_id'] : NULL ;
			$analista = ( isset($_POST['analista_id']) && !empty($_POST['analista_id']) ) ? $_POST['analista_id'] : NULL ;
			$queja_id = ( isset($_POST['queja_id']) && !empty($_POST['queja_id']) ) ? $_POST['queja_id'] :NULL ;
			$this->sql = "
			UPDATE quejas_respo SET jefatura = :jefe, analista = :analista WHERE queja_id = :queja_id AND estado = 1 AND autoridad = 3
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':jefe',$jefe,PDO::PARAM_INT);
			$this->stmt->bindParam(':analista',$analista,PDO::PARAM_INT);
			$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE A GENERADO LA ASIGNACIÓN DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getExpedientesSC()
	{
		try {
			session_start();
			$anexgrid = new AnexGrid();
			$wh = " 1=1 ";
			#Los filtros 
			foreach ($anexgrid->filtros as $filter) {
				if ( $filter['columna'] != '' ) {
					$wh .= " AND ".$filter['columna']." LIKE '%".$filter['valor']."%'";
				}
			}

			#Si el jefe de depto o el abogado analista abren el listado ... mostrar las propias
			if ( $_SESSION['nivel'] != 'SUBDIRECTOR' ) {
				$sql_filtro = "SELECT queja_id FROM documentos_sc WHERE persona_id = ? ";
				$this->stmt = $this->pdo->prepare($sql_filtro);
				$this->stmt->bindParam(1,$_SESSION['id'],PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ) {
					$quejas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
					$aux = array();
					foreach ($quejas as $key => $qd) { array_push($aux, $qd->queja_id); }
					$aux = implode(',', $aux);
					$wh .= " AND q.id IN ($aux) ";
				}else{
					$wh .= " AND q.id IN (0) ";
				}
			}			

			$this->sql = "
			SELECT q.*, qr.f_acuse, qr.autoridad, qr.id AS qr_id,
			CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat) AS jefe,
			CONCAT(p2.nombre,' ',p2.ap_pat,' ',p2.ap_mat) AS abogado
			FROM quejas AS q
			INNER JOIN quejas_respo AS qr ON qr.queja_id = q.id
			INNER JOIN personal AS p ON qr.jefatura = p.id 
			LEFT JOIN personal AS p2 ON qr.analista = p2.id 
			WHERE qr.estado = 1 AND (qr.autoridad = 1 OR qr.autoridad = 3) AND $wh 
			ORDER BY q.$anexgrid->columna $anexgrid->columna_orden 
			LIMIT $anexgrid->pagina , $anexgrid->limite
			";
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$quejas = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$total = $this->stmt->rowCount();
			$aux = array();
			$expedientes = array();

			foreach ($quejas as $key => $exp) {
				$aux['id'] = $exp->qr_id;
				$aux['queja_id'] = $exp->id;
				$aux['asunto'] = $exp->t_asunto;				
				$aux['cve_ref'] = $exp->cve_ref;			
				$aux['n_turno'] = $exp->n_turno;			
				$aux['cve_exp'] = $exp->cve_exp;			
				$aux['f_hechos'] = $exp->f_hechos;			
				$aux['h_hechos'] = $exp->h_hechos;		
					
				$aux['estado'] = $exp->estado;
				$aux['autoridad'] = $exp->autoridad;
				$aux['jefe'] = $exp->jefe;
				$aux['abogado'] = $exp->abogado;
				$aux['procedencia'] = $exp->procedencia;
				#buscar los presuntos responsables.
				$sql_presuntos = "SELECT UPPER(nombre) AS nombre FROM presuntos WHERE queja_id = ? ;";
				$this->stmt = $this->pdo->prepare($sql_presuntos);
				$this->stmt->bindParam(1,$exp->id);
				$this->stmt->execute();
				$presuntos 	= $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['presuntos'] = $presuntos;
				#Buscar resolucion
				$sql_res = "SELECT * FROM resoluciones WHERE queja_id = ? ;";
				$this->stmt = $this->pdo->prepare($sql_res);
				$this->stmt->bindParam(1,$exp->id);
				$this->stmt->execute();
				$resoluciones 	= $this->stmt->fetch(PDO::FETCH_OBJ);

				$conteo = $this->stmt->rowCount();
				if($conteo > 0){
					$aux['sancion'] = $resoluciones->sancion;
					$aux['resoluciones'] = $resoluciones;
				}else{
					$aux['sancion'] = 'VACIA';
					$aux['resoluciones'] = [];
				}
				#Contador de dias hasta el dia del cierre
				if ($exp->estado == 10 || $exp->f_hechos == 11) {
					if ($exp->estado == 11) {#concluido por imporcedencia
						$sql_sapa = "SELECT DATEDIFF(a.f_acuerdo,DATE(q.created_at))  AS f_cierre FROM quejas_respo AS q
						#INNER JOIN acuerdos_improcedencia as a ON a.queja_id = q.queja_id
						INNER JOIN acuerdos as a ON a.queja_id = q.queja_id
						WHERE q.queja_id=? and q.estado = 1;";
						$this->stmt = $this->pdo->prepare($sql_sapa);
						$this->stmt->bindParam(1,$exp->id);
						$this->stmt->execute();
						$f_cierre 	= $this->stmt->fetch(PDO::FETCH_OBJ)->f_cierre;
					}
					if ($exp->estado == 10) {#concluido por reserva
						$sql_sapa = "SELECT DATEDIFF(r.f_reserva,DATE(q.created_at))  AS f_cierre 
						FROM quejas_respo AS q
						INNER JOIN reservas as r ON r.queja_id = q.queja_id
						WHERE q.queja_id=? and q.estado = 1;";
						$this->stmt = $this->pdo->prepare($sql_sapa);
						$this->stmt->bindParam(1,$exp->id);
						$this->stmt->execute();
						$f_cierre 	= $this->stmt->fetch(PDO::FETCH_OBJ)->f_cierre;
					}
				}else{
					$f_cierre = '0 (Aún no se concluye)';
				}
				$aux['f_cierre'] = $f_cierre;
				#$aux['f_cierre'] = '';
				
				#Buscar si existen acuerdos de improcedencia
				if ( $exp->estado == '11' ) {
					$sql_improcedencia = "SELECT * FROM acuerdos WHERE queja_id = ? AND archivo IS NOT NULL;";
					$this->stmt = $this->pdo->prepare($sql_improcedencia);
					$this->stmt->bindParam(1,$exp->id);
					$this->stmt->execute();
					$cuenta = $this->stmt->rowCount();
					if ($cuenta > 0 ) {
						$aux['edo_tr'] = 'SI';
					}else{
						$aux['edo_tr'] = 'NO';
					}
				}elseif ( $exp->estado == '10' ) {
					$sql_reserva = "SELECT * FROM reservas WHERE queja_id = ?;";
					$this->stmt = $this->pdo->prepare($sql_reserva);
					$this->stmt->bindParam(1,$exp->id);
					$this->stmt->execute();
					$cuenta = $this->stmt->rowCount();
					if ($cuenta > 0 ) {
						$aux['edo_tr'] = 'SI';
					}else{
						$aux['edo_tr'] = 'NO';
					}
				}else{
					$aux['edo_tr'] = 'NA';
				}
				
				array_push($expedientes,$aux);
			}
			#print_r($expedientes);
			return $anexgrid->responde($expedientes,$total);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveResolucion()
	{
		try {
			$sancion = $_POST['sancion'];
			$oficio = $_POST['oficio'];
			$f_sancion = $_POST['f_sancion'];
			$comentario = mb_strtoupper($_POST['comentario'],'utf-8');
			$queja_id = $_POST['queja_id'];
			$estado = $_POST['estado'];
			$oficio_e = $_POST['oficio_e'];
			$f_oficio = $_POST['f_oficio'];
			$f_acuse = $_POST['f_acuse'];

			#Contar 
			$this->sql = "INSERT INTO resoluciones (id, queja_id, sancion, oficio, fecha,estado, comentario, oficio_e,f_oficio, f_acuse) VALUES ('',?,?,?,?,?,?,?,?,?) ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$sancion,PDO::PARAM_INT);
			$this->stmt->bindParam(3,$oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$f_sancion,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$estado,PDO::PARAM_INT);
			$this->stmt->bindParam(6,$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$oficio_e,PDO::PARAM_STR);
			$this->stmt->bindParam(8,$f_oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(9,$f_acuse,PDO::PARAM_STR);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE A INSERTADO LA RESOLUCIÓN DEL EXPEDIENTE DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveDemanda()
	{
		try {
			
			$queja_id = $_POST['queja_id'];
			#buscar la cantidade de demandas del expediente 
			$sql = "SELECT * FROM demandas WHERE queja_id = ? ;";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$demandas 	= $this->stmt->fetch(PDO::FETCH_OBJ);
			$cuenta = $this->stmt->rowCount();
			if ($cuenta == 2) {
				throw new Exception("NO ES POSIBLE GENERAR UNA NUEVA DEMANDA, YA QUE SE HAN AGOTADO EL NÚMERO DE VECES DE DEMANDAS PERMITIDO.", 1);
			}
			#
			$t_demanda = $_POST['t_demanda'];
			$oficio = $_POST['oficio'];
			$f_oficio = $_POST['f_oficio'];
			$f_acuse = $_POST['f_acuse'];
			$dep =  mb_strtoupper($_POST['dep'],'utf-8'); 
			$comentario = mb_strtoupper($_POST['comentario'],'utf-8');
						
			$this->sql = "INSERT INTO demandas(id, queja_id, t_demanda, oficio, f_oficio, f_acuse, dependencia, comentario) VALUES  ('',?,?,?,?,?,?,?) ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$t_demanda,PDO::PARAM_INT);
			$this->stmt->bindParam(3,$oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$f_oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$f_acuse,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$dep,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$comentario,PDO::PARAM_STR);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE A INSERTADO LA DEMANDA DEL EXPEDIENTE DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getDemandas($id)
	{
		try {
			$sql = "SELECT
			    d.*,
			    r.id AS resolucion_id,
			    r.f_resolucion,
			    r.comentario AS solucion,r.estado
			FROM
			    demandas AS d
			LEFT JOIN r_demanda AS r
			ON
			    r.demanda_id = d.id
			WHERE
			    d.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result	= $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return $this->result;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getReserva($id)
	{
		try {
			$sql = "
			SELECT
			    r.*,
			    q.cve_exp AS clave,
			    DATEDIFF(DATE(NOW()),f_hechos ) AS control 
			FROM
			    reservas AS r
			INNER JOIN quejas AS q
			ON
			    q.id = r.queja_id
			WHERE
			    r.queja_id = ?
			";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
			$this->stmt->execute();
			if ( $this->stmt->rowCount() > 0 ) {
				$this->result	= $this->stmt->fetch(PDO::FETCH_OBJ);
				return $this->result;	
			}else{
				return array();
			}
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function saveResolucionDemanda()
	{
		try {
			$demanda_id = $_POST['dem_id'];
			$f_res 		= $_POST['f_res'];
			$comentario =  mb_strtoupper($_POST['comentario'],'utf-8');
			$edo = $_POST['edo'];
			$oficio = $_POST['oficio'];
			$f_oficio = $_POST['f_oficio'];
			$f_acuse = $_POST['f_acuse'];
			$sql = "INSERT INTO r_demanda (id, demanda_id, f_resolucion, comentario, estado, oficio, f_oficio, f_acuse) 
			VALUES ('',?,?,?,?,?,?,?);";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->bindParam(1,$demanda_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$f_res,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$edo,PDO::PARAM_INT);
			$this->stmt->bindParam(5,$oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$f_oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$f_acuse,PDO::PARAM_STR);
			$this->stmt->execute();
			#Actualizar el estado de la demanda
			$sql = "UPDATE demandas SET estado = ? WHERE id = ? ;";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->bindParam(1,$edo,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$demanda_id,PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'SE HA GUARDADO LA RESOLUCIÓN DEL DE LA DEMANDA DE MANERA EXITOSA.') );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function editResolucion()
	{
		try {
			$resolucion = $_POST['resolucion'];
			$f_res 		= $_POST['f_res'];
			$comentario =  mb_strtoupper($_POST['comentario'],'utf-8');
			$sql = "UPDATE r_demanda SET f_resolucion = ? ,comentario = ? WHERE id = ?";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->bindParam(1,$f_res,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$resolucion,PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'SE HA EDITADO LA RESOLUCION DEL DE LA DEMANDA DE MANERA EXITOSA.') );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getResolucion()
	{
		try {
			$id = $_POST['resolucion'];
			$sql = "SELECT * FROM r_demanda WHERE id = ? ";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->bindParam(1,$id,PDO::PARAM_STR);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch(PDO::FETCH_OBJ);
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function saveReserva()
	{
		try {
			##Verificar lo del documento
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
			$name = $_FILES['file']['name'];
			$type = $_FILES['file']['type'];
			$size = $_FILES['file']['size'];
			$destino  = $_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/';
			#Mover el Doc
			move_uploaded_file($_FILES['file']['tmp_name'],$destino.$name);
			#abrir el archivo
			$file 		= fopen($destino.$name,'r');
			$content 	= fread($file, $size);
			$content 	= addslashes($content);
			fclose($file);
			#Eliminar  el archivo 
			unlink($_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/'.$name);	
			################################################################
			$queja_id = $_POST['queja_id'];
			$f_reserva = $_POST['f_reserva'];
			$duracion = $_POST['duracion'];
			#$oficio = $_POST['oficio'];$f_oficio = $_POST['f_oficio'];
			$comentario =  mb_strtoupper($_POST['comentario'],'utf-8');
			#calcular la fecha final 
			$sql_f_hechos = "SELECT f_hechos FROM quejas WHERE id = ?";
			$this->stmt = $this->pdo->prepare($sql_f_hechos);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$f_hechos = $this->stmt->fetch(PDO::FETCH_OBJ)->f_hechos;
			
			$sql_ff 	= "SELECT DATE_ADD('$f_hechos', INTERVAL $duracion DAY) AS f_final;";
			$this->stmt = $this->pdo->prepare($sql_ff);
			$this->stmt->execute();
			$f_final 	= $this->stmt->fetch(PDO::FETCH_OBJ)->f_final;

			$this->sql = "INSERT INTO reservas (id, queja_id, f_reserva, duracion, comentario, f_limite, archivo) 
			VALUES ('',?,?,?,?,?,?);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$f_reserva,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$duracion,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$f_final,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$content,PDO::PARAM_LOB);
			$this->stmt->execute();
			#CAMBIAR EL ESTADO GUARDA DEL EXPEDIENTE
			$this->sql = "UPDATE quejas SET estado = 10 WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'SE HA GUARDADO LA RESERVA DE MANERA EXITOSA.') );

		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function devolverExp()
	{
		try {
			$queja_id = $_POST['queja_id'];
			$f_oficio = $_POST['f_oficio'];
			$f_devolucion = $_POST['f_devolucion'];
			$oficio = $_POST['oficio'];
			$motivo =  mb_strtoupper($_POST['motivo'],'utf-8');
			/*Vallidar que ya este turnado */
			$this->sql = "SELECT count(id) AS cuenta FROM devoluciones WHERE queja_id = ? AND estado = 1;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$cuenta  = $this->stmt->fetch( PDO::FETCH_OBJ )->cuenta;
			if($cuenta > 0){ 
				throw new Exception("EL EXPEDIENTE YA HA SIDO DEVUELTO A D. INVESTIGACIÓN.", 1);
			}
			/*---------------------------------------------------------------------------*/
			$this->sql = "INSERT INTO devoluciones (id, queja_id, f_devolucion, f_oficio, oficio, motivo) 
			VALUES ('',?,?,?,?,?);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$f_devolucion,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$f_oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$motivo,PDO::PARAM_STR);
			$this->stmt->execute();
			#CAMBIAR EL ESTADO GUARDA DEL EXPEDIENTE
			$this->sql = "UPDATE quejas SET estado = 10 WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'SE HA GUARDADO LA RESERVA DE MANERA EXITOSA.') );

		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function saveAImprocedencia()
	{
		try {

			$queja_id = $_POST['queja_id'];
			$f_acuerdo = $_POST['f_acuerdo'];
			$f_turno = $_POST['f_turno'];
			$motivo = $_POST['motivo'];
			$edo = $_POST['estado_exp'];
			$comentario =  mb_strtoupper($_POST['comentario'],'utf-8');
			$asunto =  mb_strtoupper($_POST['asunto'],'utf-8');
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
			$name = $_FILES['file']['name'];
			$type = $_FILES['file']['type'];
			$size = $_FILES['file']['size'];
			$destino  = $_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/';
			#Mover el Doc
			move_uploaded_file($_FILES['file']['tmp_name'],$destino.$name);
			#abrir el archivo
			$file 		= fopen($destino.$name,'r');
			$content 	= fread($file, $size);
			$content 	= addslashes($content);
			fclose($file);
			#Eliminar  el archivo 
			unlink($_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/'.$name);	
			
			$this->sql = "INSERT INTO acuerdos (id, queja_id, f_acuerdo, f_turno, comentario,asunto,tipo, archivo) 
			VALUES ('',?,?,?,?,?,?,?);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$f_acuerdo,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$f_turno,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$asunto,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$motivo,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$content,PDO::PARAM_LOB);
			$this->stmt->execute();
			#CAMBIAR EL ESTADO GUARDA DEL EXPEDIENTE
			$this->sql = "UPDATE quejas SET estado = ? WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$edo,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'SE HA GUARDADO EL ACUERDO DE IMPORCEDENCIA DE MANERA EXITOSA.') );
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function getReporte()
	{
		try {
			$wh = "  1=1 ";
			$f_buscar = $_POST['f_buscar'];
			#Recuperar las variables
			if ( $f_buscar == '1' ) {
				$fi = $_POST['f_ini']; $ff = $_POST['f_fin'];
				if( empty($fi) || empty($ff) ){
					throw new Exception("LOS CAMPOS DE FECHAS SON OBLIGATORIOS", 1);
				}
				$wh .= " AND q.created_at BETWEEN '$fi' AND '$ff' ";
				$edo = $_POST['estado'];
				if( !empty($edo) ){
					$wh .= " AND q.estado = $edo ";
				}
				$this->sql = "
				SELECT qr.id AS qr_id, q.cve_exp, qr.oficio, e.nombre AS n_estado,
				q.id AS queja_id, qr.e_procesal
				FROM quejas_respo AS qr
				LEFT JOIN quejas AS q ON q.id = qr.queja_id
				INNER JOIN estado_guarda AS e ON e.id = q.estado
				WHERE qr.estado = 1 and $wh
				GROUP BY q.id
				";
				#print_r($this->sql);exit;
			}
			if ( $f_buscar == '2' ) {
				$fi = $_POST['fi_res']; $ff = $_POST['ff_res'];
				if( empty($fi) || empty($ff) ){
					throw new Exception("LOS CAMPOS DE FECHAS SON OBLIGATORIOS", 1);
				}
				$wh .= " AND q.f_hechos BETWEEN '$fi' AND '$ff' ";
				$san = isset($_POST['sansion']) ? $_POST['sansion'] : '';
				if( !empty($san) ){
					$wh .= " AND r.sancion = $san ";
				}
				$this->sql = "
				SELECT qr.id AS qr_id, q.cve_exp, qr.oficio, e.nombre AS n_estado,
				q.id AS queja_id, qr.e_procesal
				FROM quejas_respo AS qr
				INNER JOIN quejas AS q ON q.id = qr.queja_id
				INNER JOIN estado_guarda AS e ON e.id = q.estado
				INNER JOIN resoluciones AS r ON r.queja_id = q.id
				WHERE qr.estado = 1 and $wh
				GROUP BY q.id
				";
			}
			if ( $f_buscar == '3' ) {
				$fi = $_POST['fi_dem']; $ff = $_POST['ff_dem'];
				if( empty($fi) || empty($ff) ){
					throw new Exception("LOS CAMPOS DE FECHAS SON OBLIGATORIOS", 1);
				}
				$wh .= " AND q.f_hechos BETWEEN '$fi' AND '$ff' ";
				$tdem = $_POST['t_demanda'];
				if( !empty($tdem) ){
					$wh .= " AND d.t_demanda = $tdem ";
				}
				$res = $_POST['r_dem'];
				if( !empty($res) ){
					$wh .= " AND d.estado = $res ";
				}
				$this->sql = "
				SELECT qr.id AS qr_id, q.cve_exp, qr.oficio, e.nombre AS n_estado,
				q.id AS queja_id, qr.e_procesal
				FROM quejas_respo AS qr
				INNER JOIN quejas AS q ON q.id = qr.queja_id
				INNER JOIN estado_guarda AS e ON e.id = q.estado
				INNER JOIN demandas AS d ON d.queja_id = q.id
				WHERE qr.estado = 1 and $wh
				GROUP BY q.id
				";
			}
			if ( $f_buscar == '4' ) {
				$fi = $_POST['fi_rde']; $ff = $_POST['ff_rde'];
				if( empty($fi) || empty($ff) ){
					throw new Exception("LOS CAMPOS DE FECHAS SON OBLIGATORIOS", 1);
				}
				
				$res = $_POST['edo_res'];
				if( !empty($res) ){
					$wh .= " AND rd.estado = $res ";
				}
				
				$this->sql = "
				SELECT demanda_id FROM r_demanda AS rd
				WHERE $wh
				";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				$demandas = $this->stmt->fetchAll( PDO::FETCH_OBJ );
				$aux=array();
				foreach ($demandas as $key => $demanda) {
					$x = $demanda->demanda_id;
					array_push($aux, $x) ;
				}
				$aux = implode(',', $aux);
				$this->sql = "
				SELECT id FROM demandas 
				WHERE id IN ($aux)
				";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				$quejas = $this->stmt->fetchAll( PDO::FETCH_OBJ );
				$aux=array();
				foreach ($demandas as $key => $demanda) {
					$x = $demanda->demanda_id;
					array_push($aux, $x) ;
				}
				$aux = implode(',', $aux);
				$wh = " AND q.id IN ($aux) ";
				$wh .= " AND q.f_hechos BETWEEN '$fi' AND '$ff' ";
				$this->sql = "
				SELECT qr.id AS qr_id, q.cve_exp, qr.oficio, e.nombre AS n_estado,
				q.id AS queja_id, qr.e_procesal
				FROM quejas_respo AS qr
				INNER JOIN quejas AS q ON q.id = qr.queja_id
				INNER JOIN estado_guarda AS e ON e.id = q.estado
				WHERE qr.estado = 1 $wh
				GROUP BY q.id
				";
			}
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$q_respo = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			
			return json_encode($q_respo);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	//
	public function getTblCtrl()
	{
		try {
			$tablero = array();
			$terminados = 0;
			$pendientes = 0;
			$this->sql = "
			SELECT t_demanda, COUNT(*) AS cuenta FROM demandas GROUP BY t_demanda
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$tablero['demandas'] = $this->result; 
			#Agregar los envios a la CHyJ
			$this->sql = "
			SELECT t_demanda, COUNT(*) AS cuenta FROM demandas GROUP BY t_demanda
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$tablero['demandas'] = $this->result; 
			#Agregar los contenidos de SAPA
			$chyj = 0;
			$sc   = 0;
			$this->sql = "
			SELECT DISTINCT
			    (queja_id),
			    autoridad,
			    COUNT(queja_id) AS cuenta
			FROM
			    quejas_respo
			WHERE autoridad != '' and estado = 1 
			GROUP BY
			    autoridad
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			foreach ($this->result as $key => $sapa) {
				if ($sapa->autoridad == 'CHyJ') {
					$chyj = $sapa->cuenta;
				}elseif ($sapa->autoridad == 'SC') {
					$sc = $sapa->cuenta;
				}
			}
			$tablero['sapa'] = array( 'chyj'=>$chyj,'sc'=>$sc );
			#CREAR CONTAOR EXPEDIENTES EN ARCHIVO IMPROCEDENCIA, RESERVA E INCOMPETENCIA
			$this->sql = "
			SELECT q.estado, e.nombre, COUNT(q.estado) AS cuenta 
			FROM quejas AS q
			INNER JOIN estado_guarda  AS e ON e.id = q.estado
			WHERE q.estado IN (2,3,10,11) AND q.created_at > '2019-07-01'
			GROUP BY estado ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$tablero['q_estados'] = $this->stmt->fetchAll(PDO::FETCH_OBJ);#Quejas por estados
			#contar las resoluciones de la primer demanda
			$this->sql = "SELECT rd.estado as resultado, COUNT(*) AS cuenta 
			FROM r_demanda AS rd 
			INNER JOIN demandas AS d 
				ON d.id = rd.demanda_id 
			WHERE d.t_demanda = 1 
			GROUP BY rd.estado ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$tablero['res_primer_d'] = $this->stmt->fetchAll(PDO::FETCH_OBJ);#Quejas por estados
			$this->sql = "SELECT rd.estado as resultado, COUNT(*) AS cuenta 
			FROM r_demanda AS rd 
			INNER JOIN demandas AS d 
				ON d.id = rd.demanda_id 
			WHERE d.t_demanda = 2 GROUP BY rd.estado ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$tablero['res_rr_d'] = $this->stmt->fetchAll(PDO::FETCH_OBJ);#resolucion de demandas recurso de rev
			$this->sql = "SELECT COUNT(*) AS cuenta FROM apersonamientos GROUP BY queja_id ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$tablero['apersona'] = $this->stmt->fetch(PDO::FETCH_OBJ);#apersonamientos
			#recuperar las resoluciones de la CHyJ 
			$this->sql = "SELECT queja_id FROM quejas_respo WHERE autoridad = 1 and estado = 1";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$quejas_ids = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux = array();
			foreach ($quejas_ids as $key => $queja) {
				array_push($aux, $queja->queja_id);
			}
			$aux = implode(',', $aux);
			$this->sql = "SELECT count(sancion) as cuenta, sancion FROM resoluciones WHERE queja_id IN ($aux) group by sancion;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$tablero['res_chyj'] = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$tablero['sc'] = array('primer'=>0);

			return json_encode($tablero);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getExpedientesTC()
	{
		try {
			#1. expedientes en rec. rev , 2. con una demanda
			#3. expedientes en la chYj , 4. exp turnados a la SC
			$tipo = $_POST['tipo']; 
			if ( $tipo == '1') {
				$this->sql = "
				SELECT DISTINCT(queja_id) FROM demandas
				WHERE t_demanda = 1;
				";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->execute();
				$ids = $this->stmt->fetchAll( PDO::FETCH_OBJ );
				$aux = array();
				foreach ($ids as $key => $v) {
					array_push($aux, $v->queja_id);
				}
				$ids = implode(',', $aux);
			}
			if ( $tipo == '2') {
				$this->sql = "
				SELECT COUNT(queja_id) AS cuenta , queja_id 
				FROM demandas GROUP BY queja_id
				";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->execute();
				$ids = $this->stmt->fetchAll( PDO::FETCH_OBJ );
				$aux = array();
				foreach ($ids as $key => $v) {
					if ( $v->cuenta == '1' ) {
						array_push($aux, $v->queja_id);
					}
				}
				$ids = implode(',', $aux);
			}
			if ( $tipo == '3') {
				$this->sql = "
				SELECT queja_id FROM quejas_respo WHERE estado = 1 and autoridad = 1
				";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->execute();
				$ids = $this->stmt->fetchAll( PDO::FETCH_OBJ );
				$aux = array();
				foreach ($ids as $key => $v) {
					array_push($aux, $v->queja_id);
				}
				$ids = implode(',', $aux);
			}
			if ( $tipo == '4') {
				$this->sql = "
				SELECT queja_id FROM quejas_respo WHERE estado = 1 and autoridad = 3
				";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->execute();
				$ids = $this->stmt->fetchAll( PDO::FETCH_OBJ );
				$aux = array();
				foreach ($ids as $key => $v) {
					array_push($aux, $v->queja_id);
				}
				$ids = implode(',', $aux);
			}
			
			$this->sql = "
				SELECT q.*,qr.oficio,qr.e_procesal,qr.estado, p.nombre AS n_procedencia FROM quejas AS q
				LEFT JOIN quejas_respo AS qr ON qr.queja_id = q.id
				LEFT JOIN procedencias AS p ON p.id = q.procedencia
				WHERE q.id IN ($ids) AND qr.estado = 1
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveAcuse()
	{
		try {
			#Variables 
			$f_acuse = $_POST['f_acuse'];
			$oficio = $_POST['oficio'];
			$f_oficio = $_POST['f_oficio'];
			$obs = mb_strtoupper($_POST['obs'],'utf-8');
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
			$this->sql = "INSERT INTO documentos_qr 
			(id,oficio,f_oficio,f_acuse,comentario,archivo) 
			VALUES ('',?,?,?,?,?);
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$f_oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$f_acuse,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$obs,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$content,PDO::PARAM_LOB);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'DOCUMENTO ALMACENADO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode(array('status'=>'error','message'=>$e->getMessage()));
		}
	}
	public function sendSAPA()
	{
		try {
			#Recibir las variables 
			$oficio_dr = $_POST['oficio_dr'];
			$oficio_inv = $_POST['oficio_inv'];
			
			$this->sql = "SELECT estado FROM e_turnados WHERE oficio_cve LIKE ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$oficio_inv,PDO::PARAM_STR);
			$this->stmt->execute();
			$res = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$des = 0 ;
			foreach ($res as $key => $et) {
				if ($et->estado != 'ENVIADO'){
					$des == 1;
				}
			}
			if ( $des == 1) {
				throw new Exception("LOS EXPEDIENTES YA HAN SIDO TURNADOS", 1);
			}
			$this->sql = "UPDATE e_turnados SET estado = 1, f_sapa = DATE(NOW()), t_tramite = 4, of_sapa = :of_sapa WHERE oficio_cve LIKE :oficio_cve";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':oficio_cve',$oficio_inv,PDO::PARAM_STR);
			$this->stmt->bindParam(':of_sapa',$oficio_dr,PDO::PARAM_STR);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'EXPEDIENTES DEL OFICIO:'.$oficio_inv.' HAN SIDO TURNADOS DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function getDocumentoDevoluciones($file)
	{
		try {
			$this->sql = "SELECT archivo FROM devoluciones WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$file,PDO::PARAM_INT);
			$this->stmt->execute();
			$doc = $this->stmt->fetch(PDO::FETCH_OBJ);
			echo stripslashes($doc->archivo);
			
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function saveApersonamiento()
	{
		try {
			$queja_id = $_POST['queja_id'];
			$oficio = ( isset($_POST['oficio']) ) ? $_POST['oficio'] : $_POST['oficioa'] ;
			$f_oficio = $_POST['f_oficio'];
			$f_acuse = $_POST['f_acuse'];
			$f_aperson = $_POST['f_apersonamiento'];
			$comentario = mb_strtoupper($_POST['comentario'],'utf-8');
			
			$this->sql = "INSERT INTO apersonamientos (id, queja_id, oficio, f_oficio, f_acuse, f_apersonamiento, comentario ) VALUES ('', ?, ?, ?, ?, ?, ?);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$f_oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$f_acuse,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$f_apersonamiento,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$comentario,PDO::PARAM_STR);
			$this->stmt->execute();
			return json_encode( array( 'status'=>'success','message'=>'SE A INSERTADO EL APERSONAMIENTO DE MANERA CORRECTA.' ) );
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function saveAcuseSapa()
	{
		try {
			$asunto = mb_strtoupper($_POST['asunto'],'utf-8');
			$observaciones = mb_strtoupper($_POST['observaciones'],'utf-8');
			$oficio = $_POST['oficio'];
			$f_acuse = $_POST['f_acuse'];
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
			#Mover el Doc
			move_uploaded_file($_FILES['file']['tmp_name'],$destino.$doc_name);
			#abrir el archivo
			$file 		= fopen($destino.$doc_name,'r');
			$content 	= fread($file, $doc_size);
			$content 	= addslashes($content);
			fclose($file);
			#Eliminar  el archivo 
			unlink($_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/'.$doc_name);	

			$this->sql = "INSERT INTO documentos_sapa (id, oficio, asunto, comentario, archivo, f_acuse ) VALUES ('', ?, ?, ?, ?, ?);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$asunto,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$observaciones,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$content,PDO::PARAM_LOB);
			$this->stmt->bindParam(5,$f_acuse,PDO::PARAM_STR);
			$this->stmt->execute();
			#ACTUALIZAR LOS E_TURNADOS 
			$this->sql = "UPDATE e_turnados SET t_tramite = 2, f_sapa = :f_acuse WHERE oficio_cve LIKE :of AND estado = 1; ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':of',$oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(':f_acuse',$f_acuse,PDO::PARAM_STR);
			$this->stmt->execute();
			#insertar la queja en respo
			/*$this->sql = "INSERT INTO quejas_respo 
			(
				id,
				queja_id, 
				jefatura, 
				analista, 
				estado, 
				e_procesal
			) VALUES ( 
				'', 
				:queja_id, 
				0, 
				0, 
				1, 
				2 );";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();*/
			return json_encode( array( 'status'=>'success','message'=>'SE A INSERTADO EL ACUSE DE MANERA CORRECTA.' ) );
		} catch (Exception $e) {
			$pos = strpos($e->getMessage(), 'Duplicate entry');
			$error = "";
			if ($pos != false) {
				$error = 'ESTE OFICIO YA TIENE ACUSE';
			}else{
				$error = $e->getMessage();
			}
			return json_encode( array( 'status'=>'error','message'=>$error ) );
		}
	}
	public function asignarPersonal()
	{
		try {
			session_start();
			if($_SESSION['nivel'] == 'SUBDIRECTOR'){
				$this->sql = "UPDATE quejas_respo SET jefatura = :jefe, estado = 1, e_procesal = 2 WHERE queja_id = :queja_id AND estado =1;";
			}else{
				$this->sql = "UPDATE quejas_respo SET analista = :jefe, estado = 1, e_procesal = 2 WHERE queja_id = :queja_id AND estado =1;";
			}
			$queja_id = $_POST['queja_id'];
			$jefe = $_POST['jefe_id'];
			//$analista = $_POST['analista_id'];
			
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(':jefe',$jefe,PDO::PARAM_INT);
			//$this->stmt->bindParam(':analista',$analista,PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array( 'status'=>'success','message'=>'SE INICIADO EL TRABAJO DEL EXPEDIENTE Y SE ASIGNÓ EL PERSONAL DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function saveEProcesal()
	{
		try {
			session_start();

			$oficio = $_POST['n_oficio'];
			$f_acuse = $_POST['f_acuse'];
			$n_semana = $_POST['n_semana'];
			$fojas = $_POST['fojas'];
			$t_doc = $_POST['t_doc'];
			$conducta = (!empty($_POST['conducta'])) ? $_POST['conducta'] : NULL ;
			$autoridad = $_POST['autoridad'];
			$e_procesal = $_POST['e_procesal'];
			$motivo = $_POST['motivo'];
			$queja_id = $_POST['queja_id'];
			$comentario = mb_strtoupper( $_POST['comentario'],'utf-8' ) ;
			#SI ES DEVOLVER A D.I INSERTA EL REGISTRO DE DEVOLUCION
			if ( $e_procesal == '3' ) {
				#revisar que no se haya devuelto antes 
				$this->sql = "
				SELECT * FROM devoluciones WHERE queja_id = :queja_id
				";

				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();

				#las devoluciones anteriores se deben de deshabilitar
				if ( $this->stmt->rowCount() > 0  ) {
					$this->sql = "
					UPDATE devoluciones SET estado = 2 WHERE queja_id = :queja_id
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
					$this->stmt->execute();
				}

				$this->sql = "
				INSERT INTO devoluciones(
				    id,
				    queja_id,
				    f_devolucion,
				    f_oficio,
				    oficio,
				    motivo,
				    estado,
				    archivo
				)
				VALUES(
					'',
					:queja_id,
					DATE(NOW()),
					:f_oficio,
					:oficio,
					:motivo,
					1,
					NULL
				);
				";

				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->bindParam(':f_oficio',$f_acuse,PDO::PARAM_STR);
				$this->stmt->bindParam(':oficio',$oficio,PDO::PARAM_STR);
				$this->stmt->bindParam(':motivo',$comentario,PDO::PARAM_STR);
				$this->stmt->execute();

				#si se regresa el expediente, debera cambiar a tramite
				$this->sql = "
				UPDATE quejas
				SET 
					estado = 8
				WHERE id = :queja_id 
				;";

				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				
			}
			# si el estado procesal es Resuelto
			if ($e_procesal == '4') {
				#BUSCAR QUE NO EXISTA UNA SANSION
				$this->sql = "
				SELECT * FROM sanciones WHERE queja_id = :queja_id
				";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT|PDO::PARAM_NULL);
				$this->stmt->execute();
				if ($this->stmt->rowCount() > 0) {
					$msg = "SE A REGISTRADO EL NUEVO ESTADO PROCESAL, PERO YA EXISTE UNA SANCIÓN LO CUAL IMPIDE AGREGAR LOS DATOS DE FECHA Y SANCIÓN";					
				}else{
					$f_notificacion = ( !empty($_POST['f_notifica']) ) ? $_POST['f_notifica'] : NULL;
					$f_resolucion = ( !empty($_POST['f_resulucion']) ) ? $_POST['f_resulucion'] : NULL;
					$f_sesion = ( !empty($_POST['f_sesion']) ) ? $_POST['f_sesion'] : NULL;
					$castigo = ( !empty($_POST['castigo']) ) ? $_POST['castigo'] : NULL;
					$dias_s = ( !empty($_POST['dias_s']) ) ? $_POST['dias_s'] : NULL;
					$comentario = ( !empty($_POST['comentario']) ) ? mb_strtoupper( $_POST['comentario'],'UTF-8')  : NULL;
					$quien = $_SESSION['id'];
					$this->sql = "
					INSERT INTO sanciones (
							id,
						    queja_id,
						    nombre_pr,
						    adscripcion,
						    rfc,
						    curp,
						    cuip,
						    f_determina,
						    f_notificacion,
						    f_resolucion,
						    f_sesion,
						    castigo,
						    dias_sus,
						    oficio,
						    comentario,
						    quien_r,
						    notificado
						) VALUES (
							'',
							:queja_id,
							'QUIEN RESULTE RESPONSABLE',
							'NO REGISTRADO',
							'NO REGISTRADO',
							'NO REGISTRADO',
							'NO REGISTRADO',
							DATE(NOW()),
							:f_notificacion,
							:f_resolucion,
							:f_sesion,
							:castigo,
							:dias_s,
							NULL,
							:comentario,
							:quien,
							NULL
						);";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT|PDO::PARAM_NULL);
					$this->stmt->bindParam(':f_notificacion',$queja_id,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':f_resolucion',$queja_id,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':f_sesion',$queja_id,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':castigo',$queja_id,PDO::PARAM_INT|PDO::PARAM_NULL);
					$this->stmt->bindParam(':dias_s',$queja_id,PDO::PARAM_INT|PDO::PARAM_NULL);
					$this->stmt->bindParam(':comentario',$queja_id,PDO::PARAM_STR|PDO::PARAM_NULL);
					$this->stmt->bindParam(':quien',$queja_id,PDO::PARAM_INT|PDO::PARAM_NULL);
					$this->stmt->execute();
					$msg = "LOS DATOS DEL ESTADO PROCESAL SE HAN INSERTADO DE MANERA EXITOSA.";
				}
				
				$autoridad = 1;

			}
			

			#print_r($_POST);
			$this->sql = "
			UPDATE quejas_respo 
			SET 
				f_acuse = :f_acuse,
				n_semana = :n_semana,
				fojas = :fojas,
				t_doc = :t_doc,
				#c_respo = :conducta,
				e_procesal = :e_procesal,
				autoridad = :autoridad
			WHERE queja_id = :queja_id AND estado = 1
			;";

			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
			
			$this->stmt->bindParam(':f_acuse',$f_acuse,PDO::PARAM_STR);
			
			$this->stmt->bindParam(':n_semana',$n_semana,PDO::PARAM_INT);
			$this->stmt->bindParam(':fojas',$fojas,PDO::PARAM_INT);
			$this->stmt->bindParam(':t_doc',$t_doc,PDO::PARAM_INT);
			$this->stmt->bindParam(':conducta',$conducta,PDO::PARAM_INT|PDO::PARAM_NULL);
			$this->stmt->bindParam(':autoridad',$autoridad,PDO::PARAM_INT);
			$this->stmt->bindParam(':e_procesal',$e_procesal,PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array( 'status'=>'success','message'=>$msg ) );
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function saveCulpable()
	{
		try {
			$queja_id 	= ( !empty($_POST['queja_id']) ) ? mb_strtoupper($_POST['queja_id'],'utf-8'): NULL;
			$nombre 	= ( !empty($_POST['nombre']) ) ? mb_strtoupper($_POST['nombre'],'utf-8'): NULL;
			$ap_pat 	= ( !empty($_POST['ap_pat']) ) ? mb_strtoupper($_POST['ap_pat'],'utf-8'): NULL;
			$ap_mat 	= ( !empty($_POST['ap_mat']) ) ? mb_strtoupper($_POST['ap_mat'],'utf-8'): NULL;
			$full_name 	= mb_strtoupper($nombre,'utf-8')." ".mb_strtoupper($ap_pat,'utf-8')." ".mb_strtoupper($ap_mat,'utf-8'); 
			$genero 	= ( !empty($_POST['ge']) ) ? $_POST['ge'] : NULL;
			$cargo  	= ( !empty($_POST['cargo']) )  ? $_POST['cargo'] : NULL;
			$media 		= ( !empty($_POST['media']) ) ? mb_strtoupper($_POST['media'],'utf-8'): NULL;
			#campos nuevos
			$rfc = ( !empty($_POST['rfc']) ) ? mb_strtoupper($_POST['rfc'],'utf-8') : NULL;
			$curp = ( !empty($_POST['curp']) ) ? mb_strtoupper($_POST['curp'],'utf-8') : NULL;
			$cuip = ( !empty($_POST['cuip']) ) ? mb_strtoupper($_POST['cuip'],'utf-8') : NULL;
			$t_puesto = ( !empty($_POST['t_puesto']) ) ? mb_strtoupper($_POST['t_puesto'],'utf-8') : NULL;
			
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
						1,
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
			return json_encode( array('status'=>'success','message'=>'PRESUNTO RESPONSABLE AGREGADO CORRECTAMENTE.' ) );
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function getEstadistica()
	{
		try {
			$wh = "1=1";
			#recuperar las variables 
			if ( !empty( $_POST['f_ini']) && !empty( $_POST['f_fin']) ) {
				$wh .= " AND DATE(q.created_at) BETWEEN '".$_POST['f_ini']."' AND '".$_POST['f_fin']."'";
			}else{
				throw new Exception("LAS FECHAS NO PUEDEN SER CAMPOS VACIOS", 1);
			}
			if (!empty($_POST['e_procesal'])  ) {
				$wh .= " AND qr.e_procesal = ".$_POST['e_procesal'];
			}
			if (!empty($_POST['edo'])  ) {
				$wh .= " AND q.estado = ".$_POST['edo'];
			}
			$this->sql = "
			SELECT q.*, qr.estado, qr.e_procesal, qr.autoridad,
			e.nombre AS n_estado,
			p.nombre AS n_procedencia
			FROM quejas AS q
			LEFT JOIN quejas_respo AS qr ON qr.queja_id = q.id
			INNER JOIN procedencias AS p ON p.id = q.procedencia
			INNER JOIN estado_guarda AS e ON e.id = q.estado
			WHERE $wh
			";
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode( $this->result );
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function getClave($id)
	{
		try {
			$this->sql = "
			SELECT cve_exp FROM quejas WHERE id = :id
			;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':id',$id,PDO::PARAM_INT);
			$this->stmt->execute();
			$clave = $this->stmt->fetch(PDO::FETCH_OBJ)->cve_exp;
			return $clave;
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function getContadoresByEdo()
	{
		try {
			$edo = $_POST['edo'];
			$wh = "";
			if ( !empty($_POST['fi']) && !empty($_POST['ff']) ) {
				$wh .= " AND date(q.created_at) BETWEEN '".$_POST['fi']."' AND '".$_POST['ff']."'";
			}
			$this->sql = "
			SELECT qr.e_procesal,count(qr.e_procesal) AS cuenta FROM quejas_respo AS qr
			INNER JOIN quejas AS q ON q.id = qr.queja_id
			WHERE 1=1 $wh
			GROUP BY qr.e_procesal ;
			";
			#echo $this->sql;exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$cuentas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($cuentas);
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	
	public function getAcusesSAPA()
	{
		try {
			$anexgrid = new AnexGrid();
			$wh = " 1=1 ";
			foreach ($anexgrid->filtros as $filter) {
				if ( $filter['columna'] != '' ) {
					if ( $filter['columna'] == 'q.cve_ref' || $filter['columna'] == 'et.of_sapa') {
						$wh .= " AND ".$filter['columna']." LIKE '%".$filter['valor']."%'";
					}
				}
			}
			$this->sql = "
			SELECT id,oficio, f_acuse, asunto, comentario FROM documentos_sapa 
			WHERE 
				$wh 
			ORDER BY $anexgrid->columna $anexgrid->columna_orden 
			LIMIT $anexgrid->pagina , $anexgrid->limite
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$total = $this->stmt->rowCount();
			return $anexgrid->responde($this->result,$total);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getAcuse($file)
	{
		try {
			$this->sql = "SELECT archivo FROM documentos_sapa WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$file,PDO::PARAM_INT);
			$this->stmt->execute();
			$doc = $this->stmt->fetch(PDO::FETCH_OBJ);
			echo stripslashes($doc->archivo);
			
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function viewDoc($id,$tbl)
	{
		try {
			$this->sql = "SELECT archivo FROM $tbl WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
			$this->stmt->execute();
			$doc = $this->stmt->fetch(PDO::FETCH_OBJ);
			echo stripslashes($doc->archivo);
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function getDocumentos($queja_id)
	{
		try {
			$aux = array();
			#documentos de sc
			$this->sql = "SELECT id, oficio, f_oficio, f_acuse, asunto, comentario FROM documentos_sc WHERE queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			if ($this->stmt->rowCount() > 0 ) {
				$doc_sc = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['doc_sc'] = $doc_sc;
			}else{
				$aux['doc_sc'] = NULL;
			}

			#documentos de sapa
			$sql_qr = "SELECT id FROM quejas_respo WHERE queja_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$ids = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$id_aux = array();
			foreach ($ids as $key => $id) { array_push($id_aux,$id->id); }

			$id_aux = implode(',', $id_aux);
			if ( !empty($id_aux) ) {
				$this->sql = "SELECT id, oficio, f_oficio, f_acuse, asunto, comentario FROM documentos_turno WHERE qd_res IN ($id_aux);";

				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->execute();
				$doc_sapa = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['doc_sapa'] = $doc_sapa;
			}
			return $aux;
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function getSituacionSC()
	{
		try {
			$estado = $_POST['edo'];
			$aux = array();
			#documentos de sc
			$this->sql = "
			SELECT COUNT(*) AS cuenta
			FROM acuerdos AS a
			INNER JOIN quejas AS q ON q.id = a.queja_id
			WHERE q.estado = ? AND a.estado = 1 AND a.archivo IS NULL;
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$estado,PDO::PARAM_INT);
			$this->stmt->execute();
			$aux['acuerdos_sin'] = $this->stmt->fetch(PDO::FETCH_OBJ)->cuenta;
			$this->sql = "
			SELECT COUNT(*) AS cuenta
			FROM acuerdos AS a
			INNER JOIN quejas AS q ON q.id = a.queja_id
			WHERE q.estado = ? AND a.estado = 1 AND a.archivo IS NOT NULL;
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$estado,PDO::PARAM_INT);
			$this->stmt->execute();
			$aux['acuerdos_con'] = $this->stmt->fetch(PDO::FETCH_OBJ)->cuenta;
			#Contar quejas con resoluciones por sancion
			$this->sql = "
			SELECT COUNT(*) AS cuenta
			FROM resoluciones AS a
			INNER JOIN quejas AS q ON q.id = a.queja_id
			WHERE q.estado = ? 
			GROUP BY sancion;
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$estado,PDO::PARAM_INT);
			$this->stmt->execute();
			$aux['resolucion_con'] = $this->stmt->fetch(PDO::FETCH_OBJ)->cuenta;
			$this->sql = "
			SELECT sancion, COUNT(*) AS cuenta
			FROM resoluciones AS a
			INNER JOIN quejas AS q ON q.id = a.queja_id
			WHERE q.estado = ? 
			GROUP BY sancion;
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$estado,PDO::PARAM_INT);
			$this->stmt->execute();
			$aux['sanciones'] = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			#estado de las sanciones
			$this->sql = "
			SELECT sancion, COUNT(*) AS cuenta
			FROM resoluciones AS a
			INNER JOIN quejas AS q ON q.id = a.queja_id
			WHERE q.estado = ? 
			GROUP BY sancion;
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$estado,PDO::PARAM_INT);
			$this->stmt->execute();
			$aux['sanciones'] = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($aux);
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	
	public function saveAsignar()
	{
		try {
			
			$jefatura = (isset($_POST['jefe_sapa_id']) && !empty($_POST['jefe_sapa_id'])) ? $_POST['jefe_sapa_id'] : NULL;
			$analista = (isset($_POST['analista_id']) && !empty($_POST['analista_id'])) ? $_POST['analista_id'] : NULL;
			$queja_id = (isset( $_POST['queja_id']) && !empty( $_POST['queja_id'])) ?  $_POST['queja_id'] : NULL;
			if (is_null($jefatura)) {
				throw new Exception("NO SE HA SELECCIONADO UN JEFE DE DEPARTAMENTO. \n INTENTE DE NUEVO.", 1);
			}
			if (is_null($analista)) {
				throw new Exception("NO SE A SELECCIONADO UN ABOGADO ANALISTA \n INTENTE DE NUEVO. ", 1);
			}
			if (is_null($queja_id)) {
				throw new Exception("NO SE A IDENTIFICADO EL EXPEDIENTE, ESTO PUEDE DEBERSE A UNA FALLA DE RELACIÓN DE INFORMACIÓN. \n NOTIFIQUE ESTA FALLA AL DEPARTAMENTO DE DESARROLLO DE SISTEMAS.", 1);
			}
			
			$this->sql = "
			INSERT INTO quejas_respo(
			    id,
			    queja_id,
			    jefatura,
			    analista,
			    estado,
			    e_procesal
			)
			VALUES(
			    '',
			    :queja_id,
			    :jefe,
			    :analista,
			    1,
			    2
			);
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':queja_id',$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(':jefe',$jefatura,PDO::PARAM_INT);
			$this->stmt->bindParam(':analista',$analista,PDO::PARAM_INT);
			$this->stmt->execute();
			#Insertar el abogado analista en 
			return json_encode( array( 'status'=>'success','message'=>'ASIGNACIÓN CREADA DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function save_acuse()
	{
		try {
			session_start();
			$queja_id = $_POST['queja_id'];
			$oficio = ( isset($_POST['oficio_id']) ) ? $_POST['oficio_id'] : NULL ;
			$f_oficio = $_POST['f_oficio'];
			$f_acuse = $_POST['f_acuse'];
			$asunto = mb_strtoupper($_POST['asunto'],'utf-8');
			$comentario = mb_strtoupper($_POST['comentario'],'utf-8');
			
			if ( !empty($_FILES['file']['name']) ) {
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
				$name = $_FILES['file']['name'];
				$type = $_FILES['file']['type'];
				$size = $_FILES['file']['size'];
				$destino  = $_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/';
				#Mover el Doc
				move_uploaded_file($_FILES['file']['tmp_name'],$destino.$name);
				#abrir el archivo
				$file 		= fopen($destino.$name,'r');
				$content 	= fread($file, $size);
				$content 	= addslashes($content);
				fclose($file);
				#Eliminar  el archivo 
				unlink($_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/'.$name);		# code...
			}else{
				throw new Exception("NO SE SELECCIONO NINGÚN DOCUMENTO.", 1);				
			}
			$this->sql = "INSERT INTO 
			documentos_sc(
				id, 
				queja_id, 
				oficio,
				f_oficio,
				f_acuse,
				asunto,
				archivo,
				persona_id,
				nivel,
				comentario
			) VALUES (
			'', ?, ?, ?, ?, ?, ?, ?, ?, ?
			);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$oficio,PDO::PARAM_INT);
			$this->stmt->bindParam(3,$f_oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$f_acuse,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$asunto,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$content,PDO::PARAM_LOB);
			$this->stmt->bindParam(7,$_SESSION['id'],PDO::PARAM_INT);
			$this->stmt->bindParam(8,$_SESSION['nivel'],PDO::PARAM_STR);
			$this->stmt->bindParam(9,$comentario,PDO::PARAM_STR);
			$this->stmt->execute();
			return json_encode( array( 'status'=>'success','message'=>'SE A INSERTADO EL ACUSE DE MANERA CORRECTA.' ) );
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function getExpSapaByEdo()
	{
		try {
			$estado = $_POST['edo'];
			$this->sql = "SELECT * FROM quejas WHERE estado = ? AND created_at >= '2019-07-01';";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$estado,PDO::PARAM_INT);
			$this->stmt->execute();
			$quejas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux = array();
			$list = array();
			foreach ($quejas as $key => $queja) {
				$aux['id'] = $queja->id;
				$aux['cve_exp'] = $queja->cve_exp;
				#Buscar jefe y analista 
				$sql_respo = "SELECT qr.*, CONCAT(p1.nombre, ' ',p1.ap_pat, ' ',p1.ap_mat) AS jefe,CONCAT(p2.nombre, ' ',p2.ap_pat, ' ',p2.ap_mat) AS abogado  FROM quejas_respo AS qr
				LEFT JOIN personal AS p1 ON p1.id = qr.jefatura
				LEFT JOIN personal AS p2 ON p2.id = qr.analista
				WHERE qr.queja_id = ? AND qr.estado = 1;
				";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja->id,PDO::PARAM_INT);
				$this->stmt->execute();
				if ($this->stmt->rowCount() > 0) {
					$dr = $this->stmt->fetch(PDO::FETCH_OBJ);
					$aux['jefe'] = $dr->jefe;
					$aux['analista'] = $dr->abogado;
					$aux['oficio'] = $dr->oficio;
				}else{
					$aux['jefe'] = "NO SE REGISTRÓ";
					$aux['analista'] = "NO SE REGISTRÓ";
					$aux['oficio'] = "NO SE REGISTRÓ";
				}
				#Datos de la conclucion
				$sql_acuerdo = "SELECT * FROM acuerdos WHERE queja_id = ? ;";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja->id,PDO::PARAM_INT);
				$this->stmt->execute();
				if ($this->stmt->rowCount() > 0) {
					$acuerdo = $this->stmt->fetch(PDO::FETCH_OBJ);
					$aux['f_acuerdo'] 	= $acuerdo->f_acuerdo;	
					$aux['comentario'] 	= $acuerdo->comentario;
				}else{
					$aux['f_acuerdo'] 	= "NO SE REGISTRÓ";	
					$aux['comentario'] 	= "NO SE REGISTRÓ";
				}
				array_push($list, $aux)	;
			}
			return json_encode($list);
				
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function getSancion($queja)
	{
		try {
			$this->sql = "SELECT * FROM sanciones WHERE queja_id = :queja";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':queja',$queja,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch(PDO::FETCH_OBJ);
			return $this->result;
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function getVerificacion($sancion)
	{
		try {
			$this->sql = "SELECT * FROM verificaciones WHERE sancion_id = :sancion";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':sancion',$sancion,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch(PDO::FETCH_OBJ);
			return $this->result;
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function editSanVer()
	{
		try {
			#edita las verificaciones
			$sancion_id = ( !empty($_POST['sancion_id']) ) ? $_POST['sancion_id'] : NULL ;
			$f_not_sp = ( !empty($_POST['f_notifica_sp']) ) ? $_POST['f_notifica_sp'] : NULL ;
			$f_not_rh = ( !empty($_POST['f_notifica_rh']) ) ? $_POST['f_notifica_rh'] : NULL ;
			$f_cpt_rnpsp = ( !empty($_POST['capt_rnpsp']) ) ? $_POST['capt_rnpsp'] : NULL ;
			$f_ejec = ( !empty($_POST['f_ejec']) ) ? $_POST['f_ejec'] : NULL ;
			$quien = ( !empty($_POST['quien']) ) ? $_POST['quien'] : NULL ;
			$comentario_v = ( !empty($_POST['comentario_ver']) ) ? mb_strtoupper($_POST['comentario_ver'],'UTF-8') : NULL ;
			$this->sql = "
			UPDATE
			    verificaciones
			SET
			    sancion_id =  :sancion_id,
			    f_not_sp =  :f_not_sp,
			    f_not_rh =  :f_not_rh,
			    f_cpt_rnpsp =  :f_cpt_rnpsp,
			    f_ejec =  :f_ejec,
			    quien =  :quien,
			    comentario = :comentario 
			WHERE
			    id = :id_ver
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':sancion_id',$sancion_id,PDO::PARAM_INT);
			$this->stmt->bindParam(':id_ver',$sancion_id,PDO::PARAM_INT);
			$this->stmt->bindParam(':f_not_sp',$f_not_sp,PDO::PARAM_STR);
			$this->stmt->bindParam(':f_not_rh',$f_not_rh,PDO::PARAM_STR);
			$this->stmt->bindParam(':f_cpt_rnpsp',$f_cpt_rnpsp,PDO::PARAM_STR);
			$this->stmt->bindParam(':f_ejec',$f_ejec,PDO::PARAM_STR);
			$this->stmt->bindParam(':quien',$quien,PDO::PARAM_INT);
			$this->stmt->bindParam(':comentario',$comentario_v,PDO::PARAM_STR);
			$this->stmt->execute();
			#Edita las sanciones
			$nombre_pr	= ( !empty($_POST['n_responsable']) ) ? mb_strtoupper($_POST['n_responsable'],'UTF-8') : NULL ;
			$adscripcion	= ( !empty($_POST['adscripcion']) ) ? mb_strtoupper($_POST['adscripcion'],'UTF-8') : NULL ;
			$rfc	= ( !empty($_POST['rfc']) ) ? mb_strtoupper($_POST['rfc'],'UTF-8') : NULL ;
			$curp	= ( !empty($_POST['curp']) ) ? mb_strtoupper($_POST['curp'],'UTF-8') : NULL ;
			$cuip	= ( !empty($_POST['cuip']) ) ? mb_strtoupper($_POST['cuip'],'UTF-8') : NULL ;
			$f_determina	= ( !empty($_POST['f_determina']) ) ? $_POST['f_determina'] : NULL ;
			$f_notificacion	= ( !empty($_POST['f_notificacion']) ) ? $_POST['f_notificacion'] : NULL ;
			$f_resolucion	= ( !empty($_POST['f_resolucion']) ) ? $_POST['f_resolucion'] : NULL ;
			$f_sesion	= ( !empty($_POST['f_sesion']) ) ? $_POST['f_sesion'] : NULL ;
			$castigo	= ( !empty($_POST['castigo']) ) ? $_POST['castigo'] : NULL ;
			$dias_sus	= ( !empty($_POST['dias_s']) ) ? $_POST['dias_s'] : NULL ;
			$oficio	= ( !empty($_POST['oficio_sa']) ) ? $_POST['oficio_sa'] : NULL ;
			$comentario	= ( !empty($_POST['comentario_san']) ) ? mb_strtoupper($_POST['comentario_san'],'UTF-8') : NULL ;
			$quien_r	= ( !empty($_POST['quien_r']) ) ? $_POST['quien_r'] : NULL ;
			$notificado	= ( !empty($_POST['notificado']) ) ? $_POST['notificado'] : NULL ;
			$id_san	= ( !empty($_POST['id_san']) ) ? $_POST['id_san'] : NULL ;
			$this->sql = "
			UPDATE
			    sanciones
			SET
			    nombre_pr = :nombre_pr ,
			    adscripcion = :adscripcion ,
			    rfc = :rfc ,
			    curp = :curp ,
			    cuip = :cuip ,
			    f_determina = :f_determina ,
			    f_notificacion = :f_notificacion ,
			    f_resolucion = :f_resolucion ,
			    f_sesion = :f_sesion ,
			    castigo = :castigo ,
			    dias_sus = :dias_sus ,
			    oficio = :oficio ,
			    comentario = :comentario ,
			    quien_r = :quien_r ,
			    notificado = :notificado 
			WHERE
			    id = :id_san 
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(':nombre_pr',$nombre_pr,PDO::PARAM_STR);
			$this->stmt->bindParam(':adscripcion',$adscripcion,PDO::PARAM_STR);
			$this->stmt->bindParam(':rfc',$rfc,PDO::PARAM_STR);
			$this->stmt->bindParam(':curp',$curp,PDO::PARAM_STR);
			$this->stmt->bindParam(':cuip',$cuip,PDO::PARAM_STR);
			$this->stmt->bindParam(':f_determina',$f_determina,PDO::PARAM_INT);
			$this->stmt->bindParam(':f_notificacion',$f_notificacion,PDO::PARAM_INT);
			$this->stmt->bindParam(':f_resolucion',$f_resolucion,PDO::PARAM_INT);
			$this->stmt->bindParam(':f_sesion',$f_sesion,PDO::PARAM_INT);
			$this->stmt->bindParam(':castigo',$castigo,PDO::PARAM_INT);
			$this->stmt->bindParam(':dias_sus',$dias_sus,PDO::PARAM_INT);
			$this->stmt->bindParam(':oficio',$oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(':comentario',$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(':quien_r',$quien_r,PDO::PARAM_INT);
			$this->stmt->bindParam(':notificado',$notificado,PDO::PARAM_INT);
			$this->stmt->bindParam(':id_san',$id_san,PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array( 'status'=>'success','message'=>' LOS CAMBIOS SE HAN REALIZADO DE MANERA EXITOSA. ' ) );
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
		
}
?>