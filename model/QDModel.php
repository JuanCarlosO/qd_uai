<?php
include_once 'anexgrid.php';
include_once 'UserModel.php';
class QDModel extends Connection
{
	private $sql;
	private $stmt;
	public $result;
	
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
			$this->sql = "SELECT * FROM catalogo_cargos ORDER BY id DESC";
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

	public function getQDs()
	{
		try {
			session_start();
			$perfil = "";
			
			$quejas = array();
			$anexgrid = new AnexGrid();
			$wh = " 1=1 ";
			if ( $_SESSION['perfil'] == "QDP") {
				$wh .= " AND t_asunto = 'POLICIAL'";
			}elseif ($_SESSION['perfil'] == "QDNP") {
				$wh .= " AND t_asunto = 'NO POLICIAL'";
			}
			#Los filtros 
			foreach ($anexgrid->filtros as $filter) {
				
				if ( $filter['columna'] != '' ) {
					if ( $filter['columna'] == 'q.cve_ref' || $filter['columna'] == 'q.cve_exp' ) {
						$wh .= " AND ".$filter['columna']." LIKE '%".$filter['valor']."%'";
					}else{
						
						$wh .= " AND ".$filter['columna'] ." = ".$filter['valor'];
					}
				}
			}
			$this->sql = "SELECT q.*,UPPER(m.nombre) AS municipio,
			p.nombre AS procedencia, e.nombre AS n_estado, DATEDIFF(DATE(NOW()),DATE(q.created_at) ) AS fase
			 FROM quejas AS q
			INNER JOIN ubicacion_referencia AS u ON u.queja_id = q.id
			INNER JOIN municipios AS m ON m.id = u.municipio
			INNER JOIN procedencias AS p ON p.id = q.procedencia
			INNER JOIN estado_guarda AS e ON e.id = q.estado
			WHERE $wh ORDER BY q.$anexgrid->columna $anexgrid->columna_orden LIMIT $anexgrid->pagina , $anexgrid->limite";
			
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			#La cuenta de datos 
			$total = $this->stmt->rowCount();
			foreach ($this->result as $key => $qd) {
				$aux['id'] = $qd->id;
				$aux['cve_ref'] = $qd->cve_ref;
				$aux['cve_exp'] = $qd->cve_exp;
				$aux['h_hechos'] = $qd->h_hechos;
				$aux['f_hechos'] = $qd->f_hechos;
				$aux['municipio'] = $qd->municipio;
				$aux['procedencia'] = $qd->procedencia;
				$aux['n_estado'] = $qd->n_estado;
				$aux['fase'] = $qd->fase;
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
			

			return $anexgrid->responde($quejas,$total);

		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}

	public function getProcedencias()
	{
		try {
			$this->sql = "SELECT * FROM procedencias";
			$this->stmt = $this->getPDO()->prepare($this->sql);
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
			$this->sql = "SELECT * FROM tipos_tramite";
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
	public function getConductas()
	{
		try {
			$this->sql = "SELECT id,  nombre FROM catalogo_conductas WHERE ley = ?";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$_POST['data'],PDO::PARAM_INT);
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
			$this->sql = "SELECT id, nombre FROM catalogo_vias";
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
			$text = "%".$_REQUEST['term']."%";
			$this->sql = "SELECT id , CONCAT(nombre,' ',ap_pat,' ',ap_mat) AS value FROM personal WHERE CONCAT(nombre,' ',ap_pat,' ',ap_mat) LIKE ?";
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
			return json_encode( array('status'=>'success','message'=>'TURNO ELIMINADO DE MANERA EXITOSA.' ) );
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
				return json_encode( array('status'=>'success','message'=>'TURNO ELIMINADO DE MANERA EXITOSA.' ) );
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
			$this->sql = "SELECT short_name AS sn FROM tipos_tramite WHERE id = ?";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$tt,PDO::PARAM_INT);
			$this->stmt->execute();
			$sn = $this->stmt->fetch(PDO::FETCH_OBJ);
			if( $sn->sn == "QD" ){
				$this->sql = "SELECT COUNT(*) AS total FROM quejas WHERE t_tramite IN (1,2,3) AND YEAR(created_at) = YEAR(NOW());";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->execute();
				$total = $this->stmt->fetch(PDO::FETCH_OBJ)->total;
				$total++;
				$this->result = "UAI/EDOMEX/".$total."/".$sn->sn."/IP/".date('Y');
			}else{
				$this->sql = "SELECT COUNT(*) AS total FROM quejas WHERE t_tramite = ? AND YEAR(created_at) = YEAR(NOW());";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->bindParam(1,$tt,PDO::PARAM_INT);
				$this->stmt->execute();
				$total = $this->stmt->fetch(PDO::FETCH_OBJ)->total;
				$total++;
				$this->result = "UAI/EDOMEX/".$total."/".$sn->sn."/IP/".date('Y');
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
	public function savePresunto()
	{
		try {
			$queja_id 		=( !empty($_POST['queja_id']) ) ? $_POST['queja_id'] : NULL;
			$procedencia 	=( !empty($_POST['procedencia']) ) ? $_POST['procedencia'] : NULL;
			if ( !is_null($procedencia) ) {
				if ($procedencia == 1) {
					$adscripcion 	= ( !empty($_POST['adscripcion']) ) ? mb_strtoupper($_POST['adscripcion']) : NULL ;
					$subdir 		= ( !empty($_POST['subdir']) ) ? $_POST['subdir'] : NULL ;
					$region 		= ( !empty($_POST['region']) ) ? $_POST['region'] : NULL ;
					$agrupamiento 	= ( !empty($_POST['agrupamiento']) ) ? $_POST['agrupamiento'] : NULL ;
				}
				if ($procedencia == 2) {
					$agencia 	= ( !empty($_POST['agencia']) ) ? mb_strtoupper($_POST['agencia'],'utf-8') : NULL ;
					$fiscalia 	= ( !empty($_POST['fiscalia']) ) ? mb_strtoupper($_POST['fiscalia'],'utf-8') : NULL ;
					$mesa 		= ( !empty($_POST['mesa']) ) ? mb_strtoupper($_POST['mesa'],'utf-8') : NULL ;
					$turno 		= ( !empty($_POST['turno']) ) ? mb_strtoupper($_POST['turno'],'utf-8') : NULL ;
				}
			}else{
				$adscripcion	= NULL;			
				$subdir			= NULL;	
				$region			= NULL;	
				$agrupamiento	= NULL;			
				$agencia		= NULL;		
				$fiscalia		= NULL;		
				$mesa			= NULL;	
				$turno			= NULL;	
			}
			$nombre 	= ( !empty($_POST['nombre']) ) ? mb_strtoupper($_POST['nombre'],'utf-8'): NULL;
			$ap_pat 	= ( !empty($_POST['ap_pat']) ) ? mb_strtoupper($_POST['ap_pat'],'utf-8'): NULL;
			$ap_mat 	= ( !empty($_POST['ap_mat']) ) ? mb_strtoupper($_POST['ap_mat'],'utf-8'): NULL;
			$full_name 	= mb_strtoupper($nombre,'utf-8')." ".mb_strtoupper($ap_pat,'utf-8')." ".mb_strtoupper($ap_mat,'utf-8'); 
			$genero 	= ( !empty($_POST['genero']) ) ? $_POST['genero'] : NULL;
			$cargo  	= ( !empty($_POST['cargo']) )  ? $_POST['cargo'] : NULL;
			$municipio  = ( !empty($_POST['municipios']) )  ? $_POST['municipios'] : NULL;
			$comentarios 	= ( !empty($_POST['comentarios']) ) ? mb_strtoupper($_POST['comentarios'],'utf-8'): NULL;
			
			$this->sql 	= "INSERT INTO presuntos 
			(id, queja_id, genero, nombre, procedencia, cargo_id, municipio_id, 
			comentarios, adscripcion, subdireccion, agrupamiento, agencia, fiscalia, mesa, turno)
			VALUES 
			(
			'', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
			);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id);
			$this->stmt->bindParam(2,$genero);
			$this->stmt->bindParam(3,$full_name);
			$this->stmt->bindParam(4,$procedencia);
			$this->stmt->bindParam(5,$cargo);
			$this->stmt->bindParam(6,$municipio);
			$this->stmt->bindParam(7,$comentarios);
			$this->stmt->bindParam(8,$adscripcion);
			$this->stmt->bindParam(9,$subdir);
			$this->stmt->bindParam(10,$agrupamiento);
			$this->stmt->bindParam(11,$agencia);
			$this->stmt->bindParam(12,$fiscalia);
			$this->stmt->bindParam(13,$mesa);
			$this->stmt->bindParam(14,$turno);
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
			$destino  = $_SERVER['DOCUMENT_ROOT'].'/qd_uai/uploads/';
			$name 		 	= mb_strtoupper($_POST['name_file'],'utf-8');
			$comentario		= mb_strtoupper($_POST['comentario'],'utf-8');
			$queja_id		= $_POST['queja_id'];

			#Mover el Doc
			move_uploaded_file($_FILES['file']['tmp_name'],$destino.$doc_name);
			#abrir el archivo
			$file 		= fopen($destino.$doc_name,'r');
			$content 	= fread($file, $doc_size);
			$content 	= addslashes($content);
			fclose($file);
			
			#Eliminar  el archivo 
			unlink($_SERVER['DOCUMENT_ROOT'].'/qd_uai/uploads/'.$doc_name);			
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
			$t_asunto = $_POST['t_asunto'];
			#print_r($_POST['pro'][1]);exit;
			#Valida el tipo de referencia 
			if( isset($_POST['t_ref']) ){
				$t_ref = $_POST['t_ref'];
				if( !empty($_POST['t_ref']) ){
					$cve_ref = mb_strtoupper($_POST['cve_ref'],'utf-8');
					$n_turno = mb_strtoupper($_POST['n_turno'],'utf-8');
				}
			}else{
				$t_ref = "";
				$cve_ref = "";
				$n_turno = "";
			}
			
			if ( isset($_POST['t_tra']) && !empty($_POST['t_tra']) ) {
				$t_tramite = $_POST['t_tra'];
			}else{
				throw new Exception("DEBE DE ELEGIR UN TIPO DE TRÁMITE.", 1);
			}
			$cve_exp 	= $_POST['cve_exp'];
			$f_hechos 	= $_POST['f_hechos'];
			$h_hechos 	= $_POST['h_hechos'];
			$genero 	= $_POST['genero'];
			$t_afectado	= $_POST['t_afecta'];
			$categoria	= $_POST['categoria'];
			$d_ano = (isset( $_POST['d_ano'] )) ? $_POST['d_ano'] : 2;
			$comentario = mb_strtoupper($_POST['comentario'],'utf-8');
			$descripcion= mb_strtoupper($_POST['descripcion']);
			$prioridad 	= $_POST['prioridad'];
			$fojas 		= $_POST['fojas'];
			$evidencia 	= $_POST['evidencia'];
			$estado 	= $_POST['estado'];
			$procedencia 	= $_POST['procedencia'];
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
					comentario,
					descripcion,
					prioridad,
					fojas,
					evidencia,
					estado,
					procedencia
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
				$this->stmt->bindParam(2,$ref_id,PDO::PARAM_INT);
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
				$this->stmt->bindParam(13,$comentario,PDO::PARAM_STR);
				$this->stmt->bindParam(14,$descripcion,PDO::PARAM_STR);
				$this->stmt->bindParam(15,$prioridad,PDO::PARAM_STR);
				$this->stmt->bindParam(16,$fojas,PDO::PARAM_STR);
				$this->stmt->bindParam(17,$evidencia,PDO::PARAM_STR);
				$this->stmt->bindParam(18,$estado,PDO::PARAM_STR);
				$this->stmt->bindParam(19,$procedencia,PDO::PARAM_STR);
				
				$this->stmt->execute();
				#RECUPERAR EL ID DE LA QUEJA INSERTADO 
				$queja_id = $this->pdo->lastInsertId();
				unset($this->sql);
				unset($this->stmt);

				#GUARDAR DATOS DE LA UBICACION
				$calle			= ( isset( $_POST['c_principal'] ) && !empty( $_POST['c_principal'] ) ) ? mb_strtoupper($_POST['c_principal']): 0;
				$e_calle		= ( isset( $_POST['e_calle'] ) && !empty( $_POST['e_calle'] ) ) ? mb_strtoupper($_POST['e_calle']): 0;
				$y_calle		= ( isset( $_POST['y_calle'] ) && !empty( $_POST['y_calle'] ) ) ? mb_strtoupper($_POST['y_calle']): 0;
				$edificacion 	= ( isset( $_POST['edificacion'] ) && !empty( $_POST['edificacion'] ) ) ? $_POST['edificacion']: 0;
				$n_edificacion	= ( isset( $_POST['n_edificacion'] ) && !empty( $_POST['n_edificacion'] ) ) ? $_POST['n_edificacion']: 0;
				$municipio		= ( isset( $_POST['municipios'] ) && !empty( $_POST['municipios'] ) ) ? $_POST['municipios']: 0;
				$this->sql 		= "INSERT INTO ubicacion_referencia 
				(
					id,queja_id,calle,e_calle,y_calle,edificacion,numero,municipio
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
				$this->stmt->bindParam(5,$nombre,PDO::PARAM_STR);
				$this->stmt->bindParam(6,$nombre,PDO::PARAM_STR);
				$this->stmt->bindParam(7,$municipio,PDO::PARAM_STR);
				$this->stmt->execute();
				#Guardar las presuntas conductas
				unset($this->sql);
				unset($this->stmt);
				for($i = 0; $i < count($_POST['conductas']); $i++ ){
					$conducta = $_POST['conductas'][$i];
					$this->sql = " INSERT INTO p_conductas (id,queja_id,conducta_id) VALUES ('',?,?); ";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
					$this->stmt->bindParam(2,$conducta,PDO::PARAM_INT);
					$this->stmt->execute();
				}
				#Agregar los datos de la averiguación previa.
				unset($this->sql);
				unset($this->stmt);

				if ($_POST['origen'][0] != '' ) {
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
				}
				#Insertar el turnado
				unset($this->sql);
				unset($this->stmt);
				$this->sql = "INSERT INTO e_turnados(id,queja_id,persona_id,t_tramite,estado) VALUES ('',?,?,1,1) ";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$_POST['sp_id'],PDO::PARAM_INT);
				$this->stmt->execute();
				#Insertar las vias de recepcion 
				unset($this->sql);
				unset($this->stmt);
				$this->sql = "INSERT INTO vias_recepcion (id,via_id,queja_id) VALUES ('',?,?) ";
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
					$this->sql = "SELECT short_name AS sn FROM tipos_tramite WHERE id = ?";
					$this->stmt = $this->getPDO()->prepare($this->sql);
					$this->stmt->bindParam(1,$t_tramite,PDO::PARAM_INT);
					$this->stmt->execute();
					$sn = $this->stmt->fetch(PDO::FETCH_OBJ);
					if( $sn->sn == "QD" ){
						$this->sql = "SELECT COUNT(*) AS total FROM quejas WHERE t_tramite IN (1,2,3) AND YEAR(created_at) = YEAR(NOW());";
						$this->stmt = $this->pdo->prepare($this->sql);
						$this->stmt->execute();
						$total = $this->stmt->fetch(PDO::FETCH_OBJ)->total;
						$total++;
						$c_exp = "UAI/EDOMEX/".$total."/".$sn->sn."/IP/".date('Y');
					}else{
						$this->sql = "SELECT COUNT(*) AS total FROM quejas WHERE t_tramite = ? AND YEAR(created_at) = YEAR(NOW());";
						$this->stmt = $this->pdo->prepare($this->sql);
						$this->stmt->bindParam(1,$t_tramite,PDO::PARAM_INT);
						$this->stmt->execute();
						$total = $this->stmt->fetch(PDO::FETCH_OBJ)->total;
						$total++;
						$c_exp = "UAI/EDOMEX/".$total."/".$sn->sn."/IP/".date('Y');
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
						comentario,
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
							?,
							?
							
						);
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$t_asunto,PDO::PARAM_INT);
					$this->stmt->bindParam(2,$ref_id,PDO::PARAM_INT);
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
					$this->stmt->bindParam(13,$comentario,PDO::PARAM_STR);
					$this->stmt->bindParam(14,$descripcion,PDO::PARAM_STR);
					$this->stmt->bindParam(15,$prioridad,PDO::PARAM_STR);
					$this->stmt->bindParam(16,$fojas,PDO::PARAM_STR);
					$this->stmt->bindParam(17,$evidencia,PDO::PARAM_STR);
					$this->stmt->bindParam(18,$estado,PDO::PARAM_STR);
					$this->stmt->bindParam(19,$procedencia,PDO::PARAM_STR);
					$this->stmt->bindParam(20,$aleatorio,PDO::PARAM_INT);
					
					$this->stmt->execute();
					#RECUPERAR EL ID DE LA QUEJA INSERTADO 
					$queja_id = $this->pdo->lastInsertId();
					unset($this->sql);
					unset($this->stmt);

					#GUARDAR DATOS DE LA UBICACION
					$calle			= ( isset( $_POST['c_principal'] ) && !empty( $_POST['c_principal'] ) ) ? mb_strtoupper($_POST['c_principal']): 0;
					$e_calle		= ( isset( $_POST['e_calle'] ) && !empty( $_POST['e_calle'] ) ) ? mb_strtoupper($_POST['e_calle']): 0;
					$y_calle		= ( isset( $_POST['y_calle'] ) && !empty( $_POST['y_calle'] ) ) ? mb_strtoupper($_POST['y_calle']): 0;
					$edificacion 	= ( isset( $_POST['edificacion'] ) && !empty( $_POST['edificacion'] ) ) ? $_POST['edificacion']: 0;
					$n_edificacion	= ( isset( $_POST['n_edificacion'] ) && !empty( $_POST['n_edificacion'] ) ) ? $_POST['n_edificacion']: 0;
					$municipio		= ( isset( $_POST['municipios'] ) && !empty( $_POST['municipios'] ) ) ? $_POST['municipios']: 0;
					$this->sql 		= "INSERT INTO ubicacion_referencia 
					(
						id,queja_id,calle,e_calle,y_calle,edificacion,numero,municipio
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
					$this->stmt->bindParam(5,$nombre,PDO::PARAM_STR);
					$this->stmt->bindParam(6,$nombre,PDO::PARAM_STR);
					$this->stmt->bindParam(7,$municipio,PDO::PARAM_STR);
					$this->stmt->execute();
					#Guardar las presuntas conductas
					
					for($a = 0; $a < count($_POST['conductas']); $a++ ){
						$conducta = $_POST['conductas'][$a];
						$this->sql = " INSERT INTO p_conductas (id,queja_id,conducta_id) VALUES ('',?,?); ";
						$this->stmt = $this->pdo->prepare($this->sql);
						$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
						$this->stmt->bindParam(2,$conducta,PDO::PARAM_INT);
						$this->stmt->execute();
					}
					#Agregar los datos de la averiguación previa.
					
					if ($_POST['origen'][0] != '' ) {
						for($b = 0; $b < count($_POST['origen']); $b++ ){
							$origen 	= $_POST['origen'][$b];
							$tramite	= $_POST['tramite_prev'][$b];
							$clave_prev	= $_POST['clave_prev'][$b];
							$this->sql = " INSERT INTO referencia_queja (id,clave,origen,tipo, queja_id) VALUES ('',?,?,?,?); ";
							$this->stmt = $this->pdo->prepare($this->sql);
							$this->stmt->bindParam(1,$clave_prev,PDO::PARAM_STR);
							$this->stmt->bindParam(2,$origen,PDO::PARAM_INT);
							$this->stmt->bindParam(3,$tramite_preva,PDO::PARAM_INT);
							$this->stmt->bindParam(4,$queja_id,PDO::PARAM_INT);
							$this->stmt->execute();
						}
					}
					#Insertar el turnado
					
					$this->sql = "INSERT INTO e_turnados(id,queja_id,persona_id,t_tramite,estado) VALUES ('',?,?,1,1) ";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
					$this->stmt->bindParam(2,$_POST['sp_id'],PDO::PARAM_INT);
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
					
					$pro 	=( !empty($_POST['pro'][$i]) ) ? $_POST['pro'][$i] : NULL;
					
					if ( !is_null($pro) ) {
						if ($pro == '1') {
							$adscripcion 	= ( !empty($_POST['adscripcion'][$i]) ) ? mb_strtoupper($_POST['adscripcion'][$i]) : NULL ;
							$subdir 		= ( !empty($_POST['subdir'][$i]) ) ? $_POST['subdir'][$i] : NULL ;
							$region 		= ( !empty($_POST['region'][$i]) ) ? $_POST['region'][$i] : NULL ;
							$agrupamiento 	= ( !empty($_POST['agrupamiento'][$i]) ) ? $_POST['agrupamiento'][$i] : NULL ;
						}
						if ($pro == '2') {
							$agencia 	= ( !empty($_POST['agencia'][$i]) ) ? mb_strtoupper($_POST['agencia'][$i],'utf-8') : NULL ;
							$fiscalia 	= ( !empty($_POST['fiscalia'][$i]) ) ? mb_strtoupper($_POST['fiscalia'][$i],'utf-8') : NULL ;
							$mesa 		= ( !empty($_POST['mesa'][$i]) ) ? mb_strtoupper($_POST['mesa'][$i],'utf-8') : NULL ;
							$turno 		= ( !empty($_POST['turno'][$i]) ) ? mb_strtoupper($_POST['turno'][$i],'utf-8') : NULL ;
						}
					}else{
						$adscripcion	= NULL;			
						$subdir			= NULL;	
						$region			= NULL;	
						$agrupamiento	= NULL;			
						$agencia		= NULL;		
						$fiscalia		= NULL;		
						$mesa			= NULL;	
						$turno			= NULL;	
					}
					$nombre 	= ( !empty($_POST['nombre'][$i]) ) ? mb_strtoupper($_POST['nombre'][$i],'utf-8'): NULL;
					$ap_pat 	= ( !empty($_POST['ap_pat'][$i]) ) ? mb_strtoupper($_POST['ap_pat'][$i],'utf-8'): NULL;
					$ap_mat 	= ( !empty($_POST['ap_mat'][$i]) ) ? mb_strtoupper($_POST['ap_mat'][$i],'utf-8'): NULL;
					$full_name 	= mb_strtoupper($nombre,'utf-8')." ".mb_strtoupper($ap_pat,'utf-8')." ".mb_strtoupper($ap_mat,'utf-8'); 
					$genero 	= ( !empty($_POST['ge'][$i]) ) ? $_POST['ge'][$i] : NULL;
					$cargo  	= ( !empty($_POST['cargo'][$i]) )  ? $_POST['cargo'][$i] : NULL;
					$mun  = ( !empty($_POST['mun'][$i]) )  ? $_POST['mun'][$i] : NULL;
					$media 	= ( !empty($_POST['media'][$i]) ) ? mb_strtoupper($_POST['media'][$i],'utf-8'): NULL;
					
					$this->sql 	= "INSERT INTO presuntos 
					(id, queja_id, genero, nombre, procedencia, cargo_id, municipio_id, 
					comentarios, adscripcion, subdireccion, agrupamiento, agencia, fiscalia, mesa, turno)
					VALUES 
					(
					'', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
					);";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$queja_id);
					$this->stmt->bindParam(2,$genero);
					$this->stmt->bindParam(3,$full_name);
					$this->stmt->bindParam(4,$pro);
					$this->stmt->bindParam(5,$cargo);
					$this->stmt->bindParam(6,$mun);
					$this->stmt->bindParam(7,$media);
					$this->stmt->bindParam(8,$adscripcion);
					$this->stmt->bindParam(9,$subdir);
					$this->stmt->bindParam(10,$agrupamiento);
					$this->stmt->bindParam(11,$agencia);
					$this->stmt->bindParam(12,$fiscalia);
					$this->stmt->bindParam(13,$mesa);
					$this->stmt->bindParam(14,$turno);
					$this->stmt->execute();
				}
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
			
			$this->sql = "SELECT q.*,UPPER(m.nombre) AS municipio,
			p.nombre AS procedencia,u.municipio AS mun_id,u.calle AS calle, u.e_calle AS e_calle, 
			u.y_calle AS y_calle, u.edificacion AS edificacion ,u.numero AS numero, tt.nombre AS n_tramite,
			e.nombre AS n_estado
			FROM quejas AS q
			INNER JOIN ubicacion_referencia AS u ON u.queja_id = q.id
			INNER JOIN municipios AS m ON m.id = u.municipio
			INNER JOIN procedencias AS p ON p.id = q.procedencia
			INNER JOIN tipos_tramite AS tt ON tt.id = q.t_tramite
			INNER JOIN estado_guarda AS e ON e.id = q.estado
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
			$aux['t_asunto'] 	= $qd->t_asunto;
			$aux['t_tramite'] 	= $qd->t_tramite;
			$aux['t_afectado'] 	= $qd->t_afectado;
			$aux['categoria'] 	= $qd->categoria;
			$aux['d_ano'] 		= $qd->d_ano;
			$aux['comentario'] 	= $qd->comentario;
			$aux['descripcion'] = $qd->descripcion;
			$aux['prioridad'] 	= $qd->prioridad;
			$aux['fojas'] 		= $qd->fojas;
			$aux['evidencia'] 	= $qd->evidencia;
			$aux['estado'] 		= $qd->estado;
			$aux['ref_id'] 		= $qd->ref_id;
			$aux['genero'] 		= $qd->genero;
			$aux['estado'] 		= $qd->estado;
			#Datos de la direccion de referencia
			$aux['calle'] 		= $qd->calle;
			$aux['e_calle'] 	= $qd->e_calle;
			$aux['y_calle'] 	= $qd->y_calle;
			$aux['edificacion'] = $qd->edificacion;
			$aux['numero'] 		= $qd->numero;
			$aux['mun_id'] 		= $qd->mun_id;
			$aux['n_tramite'] 	= $qd->n_tramite;
			$aux['n_estado'] 	= $qd->n_estado;
			#Buscar las presuntas conductas de cada expediente
			$sql_conductas = "SELECT pc.id AS id_presunta, cc.id,cc.nombre,l.nombre AS n_ley  FROM p_conductas AS pc
			INNER JOIN catalogo_conductas AS cc ON cc.id = pc.conducta_id
            INNER JOIN leyes AS l ON l.id = cc.ley
			WHERE pc.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_conductas);
			$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$conductas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['conductas'] = $conductas;

			#Buscar los turnos del expediente
			$sql_turno = "SELECT p.*,e.id AS id_turno FROM e_turnados AS e
			INNER JOIN personal AS p ON p.id = e.persona_id
			WHERE e.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_turno);
			$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$turnos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['turnos'] = $turnos;
			#Buscar las vias de recepcion
			
			$sql_vias = "SELECT v.id , cv.nombre AS via  
			FROM vias_recepcion AS v 
			INNER JOIN catalogo_vias AS cv ON cv.id = v.via_id
			WHERE v.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_vias);
			$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$vias = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['vias'] = $vias;
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
			INNER JOIN municipios AS m ON m.id = q.municipio_id
			WHERE q.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_quejosos);
			$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$quejosos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['quejosos'] = $quejosos;
			
			#Agregar presuntos responsables
			$sql_presuntos = "SELECT p.* ,m.nombre AS n_municipio 
			FROM presuntos AS p 
			LEFT JOIN municipios AS m ON m.id = p.municipio_id
			WHERE p.queja_id = ?";
			$this->stmt = $this->pdo->prepare($sql_presuntos);
			$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$presuntos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['presuntos'] = $presuntos;

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
			$comentarios = mb_strtoupper($_POST['comentario'],'utf-8') ;
			$this->sql = "INSERT INTO quejosos 
			(id,queja_id,nombre,telefono,email,municipio_id,cp,n_int,n_ext,comentarios,genero) 
			VALUES 
			('',?,?,?,?,?,?,?,?,?,?);";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$full_name,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$phone,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$mail,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$municipio,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$cp,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$n_int,PDO::PARAM_STR);
			$this->stmt->bindParam(8,$n_ext,PDO::PARAM_STR);
			$this->stmt->bindParam(9,$comentarios,PDO::PARAM_STR);
			$this->stmt->bindParam(10,$genero,PDO::PARAM_STR);
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

			$t_asunto = $_POST['t_asunto'];
			
			#Valida el tipo de referencia 
			$t_ref = $_POST['t_ref'];
			$cve_ref = mb_strtoupper($_POST['cve_ref'],'utf-8');
			$n_turno = $_POST['n_turno'];
			
			
			if ( isset($_POST['t_tra']) && !empty($_POST['t_tra']) ) {
				$t_tramite = $_POST['t_tra'];
			}else{
				throw new Exception("DEBE DE ELEGIR UN TIPO DE TRÁMITE.", 1);
			}
			$cve_exp 	= $_POST['cve_exp'];
			$f_hechos 	= $_POST['f_hechos'];
			$h_hechos 	= $_POST['h_hechos'];
			$genero 	= $_POST['genero'];
			$t_afectado	= $_POST['t_afecta'];
			$categoria	= $_POST['categoria'];
			$d_ano = (isset( $_POST['d_ano'] )) ? $_POST['d_ano'] : 2;
			$comentario = mb_strtoupper($_POST['comentario'],'utf-8');
			$descripcion= mb_strtoupper($_POST['descripcion']);
			$prioridad 	= $_POST['prioridad'];
			$fojas 		= $_POST['fojas'];
			$evidencia 	= $_POST['evidencia'];
			$estado 	= $_POST['estado'];
			$procedencia= $_POST['procedencia'];
			$queja_id	= $_POST['queja_id'];
			$this->sql = "
				UPDATE quejas SET
				t_asunto = ?,
				ref_id = ?,
				cve_ref = ?,
				n_turno = ?,
				t_tramite = ?,
				cve_exp = ?,
				f_hechos = ?,
				h_hechos = ?,
				genero = ?,
				t_afectado = ?,
				categoria = ?,
				d_ano = ?,
				comentario = ?,
				descripcion = ?,
				prioridad = ?,
				fojas = ?,
				evidencia = ?,
				estado = ?,
				procedencia = ?
				
				WHERE id = ? 
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$t_asunto);
			$this->stmt->bindParam(2,$t_ref);
			$this->stmt->bindParam(3,$cve_ref);
			$this->stmt->bindParam(4,$n_turno);
			$this->stmt->bindParam(5,$t_tramite);
			$this->stmt->bindParam(6,$cve_exp);
			$this->stmt->bindParam(7,$f_hechos);
			$this->stmt->bindParam(8,$h_hechos);
			$this->stmt->bindParam(9,$genero);
			$this->stmt->bindParam(10,$t_afectado);
			$this->stmt->bindParam(11,$categoria);
			$this->stmt->bindParam(12,$d_ano);
			$this->stmt->bindParam(13,$comentario);
			$this->stmt->bindParam(14,$descripcion);
			$this->stmt->bindParam(15,$prioridad);
			$this->stmt->bindParam(16,$fojas);
			$this->stmt->bindParam(17,$evidencia);
			$this->stmt->bindParam(18,$estado);
			$this->stmt->bindParam(19,$procedencia);
			$this->stmt->bindParam(20,$queja_id);
			$this->stmt->execute();
			#RECUPERAR EL ID DE LA QUEJA INSERTADO 
			
			unset($this->sql);
			unset($this->stmt);

			#GUARDAR DATOS DE LA UBICACION
			$calle			= ( isset( $_POST['c_principal'] ) && !empty( $_POST['c_principal'] ) ) ? mb_strtoupper($_POST['c_principal']): 0;
			$e_calle		= ( isset( $_POST['e_calle'] ) && !empty( $_POST['e_calle'] ) ) ? mb_strtoupper($_POST['e_calle']): 0;
			$y_calle		= ( isset( $_POST['y_calle'] ) && !empty( $_POST['y_calle'] ) ) ? mb_strtoupper($_POST['y_calle']): 0;
			$edificacion 	= ( isset( $_POST['edificacion'] ) && !empty( $_POST['edificacion'] ) ) ? $_POST['edificacion']: 0;
			$n_edificacion	= ( isset( $_POST['n_edificacion'] ) && !empty( $_POST['n_edificacion'] ) ) ? $_POST['n_edificacion']: 0;
			$municipio		= ( isset( $_POST['municipios'] ) && !empty( $_POST['municipios'] ) ) ? $_POST['municipios']: 0;
			$this->sql 		= "
			UPDATE ubicacion_referencia SET
			calle = ?,
			e_calle = ?,
			y_calle = ?,
			edificacion = ?,
			numero = ?,
			municipio = ?
			WHERE queja_id = ?
			;";
			$this->stmt = $this->pdo->prepare($this->sql);
			
			$this->stmt->bindParam(1,$calle,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$e_calle,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$y_calle,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$nombre,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$nombre,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$municipio,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$queja_id,PDO::PARAM_STR);
			$this->stmt->execute();
			#Guardar las presuntas conductas
			unset($this->sql);
			unset($this->stmt);
			if ( isset($_POST['conductas']) ) {
				for($i = 0; $i < count($_POST['conductas']); $i++ ){
					$conducta = $_POST['conductas'][$i];
					$this->sql = " INSERT INTO p_conductas (id,queja_id,conducta_id) VALUES ('',?,?); ";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
					$this->stmt->bindParam(2,$conducta,PDO::PARAM_INT);
					$this->stmt->execute();
				}
			}
			
			#Agregar los datos de la averiguación previa.
			unset($this->sql);
			unset($this->stmt);

			if ($_POST['origen'][0] != '' ) {
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
			}
			#Insertar el turnado
			unset($this->sql);
			unset($this->stmt);
			if ( !empty($_POST['sp_id']) ) {
				$this->sql = "INSERT INTO e_turnados(id,queja_id,persona_id,t_tramite,estado) VALUES ('',?,?,1,1) ";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$_POST['sp_id'],PDO::PARAM_INT);
				$this->stmt->execute();
			}
			
			#Insertar las vias de recepcion 
			unset($this->sql);
			unset($this->stmt);
			#Liminar los turnos
			if (isset($_POST['vias_r'])) {
				$this->sql = "DELETE FROM vias_recepcion WHERE queja_id = ? ";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
				$this->stmt->execute();
				for ($i=0; $i < count($_POST['vias_r']); $i++) { 
					$this->sql = "INSERT INTO vias_recepcion (id,via_id,queja_id) VALUES ('',?,?) ";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$_POST['vias_r'][$i],PDO::PARAM_INT);
					$this->stmt->bindParam(2,$queja_id,PDO::PARAM_INT);
					$this->stmt->execute();
				}
			}
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
			return json_encode( array('status'=>'success','message'=>'EL EXPEDIENTE '.$_POST['cve_exp'].' A SIDO EDITADO DE MANERA EXITOSA.') );
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
					A LA SECCION DE -LISTADO DE QUEJAS Y DENUNCIAS- \n 
					ESTA SECCIÓN SIRVE PARA REALIZAR UN MUESTREO DE DATOS MÁS ESPECIFICO. ", 1);
			}
			$wh = "";
			if ( !empty($_POST['t_asunto']) ) {
				$wh .= ' AND q.t_asunto = '. $_POST['t_asunto'];
			}
			#Las fechas 
			if ( !empty($_POST['f_ini']) && !empty($_POST['f_fin']) ) {
				$wh .= ' AND q.f_hechos BETEEWEN '. $_POST['f_ini']. ' AND '.$_POST['f_fin'];
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
				$wh .= ' AND q.t_afecta = '. $_POST['t_afecta'];
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
				#print_r($conductas);exit;
				$this->stmt 	= $this->pdo->prepare($sql_aux);
				$this->stmt->bindParam(1,$conductas,PDO::PARAM_STR);
				$this->stmt->execute();
				$pc_quejas 	= $this->stmt->fetchAll(PDO::FETCH_OBJ);
				#sacar las quejas
				$ids_quejas = array();

				foreach ($pc_quejas as $key => $queja) {
					array_push($ids_quejas, $queja->queja_id);
				}
				$ids_quejas = implode(',',$ids_quejas);
				$wh .= " AND q.id IN ($ids_quejas)";
			}
			#Seleccionar la queja que contenga las vias de recepcion seleccionadas
			if (isset($_POST['vias_r'])) {
				$sql_vias 		= "SELECT queja_id FROM vias_recepcion WHERE via_id IN (?)";
				$vias 			= implode(',',$_POST['vias_r']);
				#print_r($vias);exit;
				$this->stmt 	= $this->pdo->prepare($sql_vias);
				$this->stmt->bindParam(1,$vias,PDO::PARAM_STR);
				$this->stmt->execute();
				$vr_quejas 	= $this->stmt->fetchAll(PDO::FETCH_OBJ);
				#sacar las quejas
				$vias_quejas = array();

				foreach ($vr_quejas as $key => $via) {
					array_push($vias_quejas, $via->queja_id);
				}
				$vias_quejas = implode(',',$vias_quejas);
				$wh .= " AND q.id IN ($vias_quejas)";
			}

			$this->sql = "SELECT q.*,UPPER(m.nombre) AS municipio,
			p.nombre AS procedencia, e.nombre AS n_estado
			 FROM quejas AS q
			INNER JOIN ubicacion_referencia AS u ON u.queja_id = q.id
			INNER JOIN municipios AS m ON m.id = u.municipio
			INNER JOIN procedencias AS p ON p.id = q.procedencia
			INNER JOIN estado_guarda AS e ON e.id = q.estado
			WHERE 1=1 $wh GROUP BY q.id";
			
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
				FROM e_turnados WHERE persona_id = ?
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
				WHERE q.id IN ($quejas)
				GROUP BY q.estado
				";
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
				$wh = " AND q.estado = ".$e;
			}else{
				$wh = '';
			}
			$this->sql = "SELECT q.*,UPPER(m.nombre) AS municipio,
			p.nombre AS procedencia, e.nombre AS n_estado
			 FROM quejas AS q
			INNER JOIN e_turnados AS et ON et.queja_id = q.id
			INNER JOIN ubicacion_referencia AS u ON u.queja_id = q.id
			INNER JOIN municipios AS m ON m.id = u.municipio
			INNER JOIN procedencias AS p ON p.id = q.procedencia
			INNER JOIN estado_guarda AS e ON e.id = q.estado
			WHERE 1=1 $wh";
			
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
				
				array_push($quejas, $aux);
			}
			return $quejas;
			
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
} 	
?>