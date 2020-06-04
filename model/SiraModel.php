<?php
include_once 'anexgrid.php';
include_once 'UserModel.php';
class SiraModel extends Connection
{
	private $sql;
	private $stmt;
	public $result;
	
	public function getAreas()
	{
		try {
			$term = "%".$_REQUEST['term']."%";
			$this->sql = "SELECT id, nombre AS value FROM areas WHERE nombre LIKE ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$term,PDO::PARAM_STR);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getOINs()
	{
		try {
			#Generar la conexion nueva
			$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
			$term = "%".$_REQUEST['term']."%";
			$this->sql = "SELECT id, clave AS value FROM orden_inspeccion WHERE clave LIKE ?";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$term,PDO::PARAM_STR);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getListONIs()
	{
		try {
			$anexgrid = new AnexGrid();
			$wh = " 1=1 ";
			foreach ($anexgrid->filtros as $filter) {
				if ($filter['columna'] == 'a.comentarios' || $filter['columna'] == 'a.clave') {
					$wh .= " AND ".$filter['columna'] ." LIKE '%".$filter['valor']."%'";
					
				}else{
					$wh .= " AND ".$filter['columna'] ." = ".$filter['valor'];
				}
				
			}
			
			#Generar la conexion nueva
			$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
	
			$this->sql = "SELECT id, oficio_id, clave, despachador_id, estatus, f_creacion, comentario 
			FROM orden_inspeccion
			WHERE $wh ORDER BY $anexgrid->columna $anexgrid->columna_orden LIMIT $anexgrid->pagina , $anexgrid->limite
			";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$total = $this->stmt->rowCount();
			$aux = array();
			foreach ($this->result as $oin) {
				$aux['id'] = $oin->id;
				$aux['clave'] = $oin->clave;
				$aux['f_creacion'] = $oin->f_creacion;
				$aux['comentario'] = $oin->comentario;
				$aux['estatus'] = $oin->estatus;
				/*$sql_participantes = " SELECT * FROM participantes_oin AS p
				INNER JOIN personal AS pe ON pe.id = p.person_id
				WHERE pe.oin_id = ? 
				";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->execute();
				$participantes = $this->stmt->fetchAll(PDO::FETCH_OBJ);*/
				$oficio = $this->getOficioById($oin->oficio_id);
				if ( is_array($oficio) ) {
					$aux['oficio'] = $oficio->no_oficio;
				}else{
					$aux['oficio'] = $oficio;
				}
				
			}
			
			#print_r($this->result);
			return $anexgrid->responde($aux,$total);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getOficioById($id)
	{
		try {
			$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
			$this->sql = " SELECT * FROM oficios_generados 
				WHERE id = ? 
				";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
			$this->stmt->execute();
			
			if ($this->stmt->rowCount() > 0 ) {
				$this->result =  $this->stmt->fetchAll(PDO::FETCH_OBJ);
			}else{
				$this->result = "NO SE ENCONTRÓ ";
			}
			return $this->result;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveActa()
	{
		try {
			session_start();
			#print_r(count($_POST['investigadores']) );exit;
			#Las variables
			if ( !isset($_POST['area_h']) OR empty($_POST['area_h']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN EL CAMPO DE ÁREA.", 1);
			}
			if ( !isset($_POST['f_acta']) AND empty($_POST['f_acta']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN LA FECHA DEL ACTA.", 1);
			}
			if ( !isset($_POST['t_actuacion']) OR empty($_POST['t_actuacion']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN LA FECHA DEL ACTA.", 1);
			}
			if ( !isset($_POST['procedencia']) OR empty($_POST['procedencia']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN LA FECHA DEL ACTA.", 1);
			}
			if ( !isset($_POST['municipio']) OR empty($_POST['municipio']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN EL CAMPO DE MUNICIPIO.", 1);
			}
			if ( !isset($_POST['lugar']) OR empty($_POST['lugar']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN EL CAMPO DEL LUGAR.", 1);
			}
			if ( !isset($_POST['accion']) OR empty($_POST['accion']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN EL CAMPO DEL ACCIONES REALIZADAS.", 1);
			}
			if ( !isset($_POST['investigadores']) ) {
				throw new Exception("NO SE HA SELECCIONADO NINGUN INVESTIGADOR.", 1);
			}

			$area			= $_POST['area_h'];	
			$cve_area 		= $this->getCVE($area);	
			$persona		= $_SESSION['id'];	
			#Generar la clave del acta
			$this->sql = "SELECT COUNT(id) AS total FROM actas WHERE t_actuacion = ? AND YEAR(created_at) = YEAR(NOW())";	
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$_POST['t_actuacion'],PDO::PARAM_INT);
			$this->stmt->execute();
			$total = $this->stmt->fetch(PDO::FETCH_OBJ)->total;
			$ta = $_POST['t_actuacion'];
			/*if ( $_POST['t_actuacion'] == '1' ) {
				$ta = "INS";
			}
			if ( $_POST['t_actuacion'] == '2' ) {
				$ta = "VER";
			}
			if ( $_POST['t_actuacion'] == '3' ) {
				$ta = "SUP";
			}*/
			$clave			= $cve_area."/".$ta."/".(++$total)."/".date('Y');
			$fecha			= $_POST['f_acta'];		
			$t_actuacion	= $_POST['t_actuacion'];				
			$procedencia	= $_POST['procedencia'];				
			$municipio		= $_POST['municipio'];			
			$lugar			= mb_strtoupper($_POST['lugar'],'utf-8');		
			$comentarios	= mb_strtoupper($_POST['accion'],'utf-8');				
			$this->sql = "INSERT INTO actas (
				id, 
				area_id, 
				persona_id, 
				queja_id, 
				clave, 
				fecha, 
				t_actuacion, 
				procedencia, 
				municipio_id, 
				lugar, 
				comentarios
			) VALUES (
				'',
				?,
				?,
				NULL,
				?,
				?,
				?,
				?,
				?,
				?,
				?
			)";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$area,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$persona,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$clave,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$fecha,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$t_actuacion,PDO::PARAM_INT);
			$this->stmt->bindParam(6,$procedencia,PDO::PARAM_INT);
			$this->stmt->bindParam(7,$municipio,PDO::PARAM_INT);
			$this->stmt->bindParam(8,$lugar,PDO::PARAM_STR);
			$this->stmt->bindParam(9,$comentarios,PDO::PARAM_STR);
			$this->stmt->execute();
			$ultimo = $this->pdo->lastInsertId();
			#INSERTAR LOS INVESTIGADORES 
			$cuenta_p = count($_POST['investigadores']); 
			$sql_insert_inv = "INSERT INTO investigadores 
			(id,persona_id,rol,acta_id) 
			VALUES 
			( '',?,1,? )
			";
			for ($i=0; $i < $cuenta_p; $i++) { 
				$this->stmt = $this->pdo->prepare($sql_insert_inv);
				$this->stmt->bindParam(1, $_POST['investigadores'][$i], PDO::PARAM_INT);
				$this->stmt->bindParam(2, $ultimo, PDO::PARAM_INT);
				$this->stmt->execute();
			}
			#INSERTAR EL PERSONAL DE APOYO
			$cuenta_a = count($_POST['apoyo']); 
			$sql_insert_apo = "INSERT INTO investigadores 
			(id,persona_id,rol,acta_id) 
			VALUES 
			( '',?,2,? )
			";
			for ($i=0; $i < $cuenta_a; $i++) { 
				$this->stmt = $this->pdo->prepare($sql_insert_apo);
				$this->stmt->bindParam(1, $_POST['apoyo'][$i], PDO::PARAM_INT);
				$this->stmt->bindParam(2, $ultimo, PDO::PARAM_INT);
				$this->stmt->execute();
			}
			#INSERTAR LA RELACION CON UNA OIN
			if ( $_POST['question'] == '1' ) {
				$o = $_POST['orden_h']; 
				$sql_rel = "
				INSERT INTO relacion_oin_actas (id,oin_id, acta_id)
				VALUES ('',?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $o , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $ultimo, PDO::PARAM_INT);
				$this->stmt->execute();
			}
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE INSERTÓ UN ACTA NUEVA";
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,2,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE A GENERADO EL NÚMERO DE ACTA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	#Extraer la clave del area 
	public function getCVE($area_id)
	{
		try {
			$cve = "";
			$this->sql = "SELECT codigo FROM areas WHERE id = ?";	
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$area_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$cve = $this->stmt->fetch(PDO::FETCH_OBJ)->codigo;
			return $cve;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getActas()
	{
		try {
			session_start();
			$id = $_SESSION['id'];
			$actas = array();
			$anexgrid = new AnexGrid();
			$wh = " 1=1 AND persona_id = $id ";
			foreach ($anexgrid->filtros as $filter) {
				if ($filter['columna'] == 'a.comentarios' || $filter['columna'] == 'a.clave') {
					$wh .= " AND ".$filter['columna'] ." LIKE '%".$filter['valor']."%'";
					
				}else{
					$wh .= " AND ".$filter['columna'] ." = ".$filter['valor'];
				}
				
			}
			$this->sql = "SELECT a.id,a.clave,a.fecha,a.t_actuacion,a.procedencia,
			a.lugar,UPPER(a.comentarios) AS comentarios,ar.nombre AS n_area,UPPER(m.nombre) AS n_municipio,
			CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat) AS persona 
			FROM actas AS a 
			LEFT JOIN areas AS ar ON ar.id = a.area_id
			LEFT JOIN personal AS p ON p.id = a.persona_id
			LEFT JOIN municipios AS m ON m.id = a.municipio_id
			WHERE $wh ORDER BY a.$anexgrid->columna $anexgrid->columna_orden LIMIT $anexgrid->pagina , $anexgrid->limite";
			
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$actas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$total = $this->stmt->rowCount();
			return $anexgrid->responde($actas,$total);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveDocActa()
	{
		try {
			session_start();
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

			$name 		 	= mb_strtoupper($_POST['nombre'],'utf-8');
			$comentario		= mb_strtoupper($_POST['comentario'],'utf-8');
			$acta_id		= $_POST['acta_id'];

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
			$this->sql = "INSERT INTO documentos_sira
			(id,nombre,archivo,acta_id,comentarios) 
			VALUES ('',?,?,?,?);";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$name,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$content,PDO::PARAM_LOB);
			$this->stmt->bindParam(3,$acta_id,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$comentario,PDO::PARAM_STR);
			$this->stmt->execute();
			$ultimo = $this->pdo->lastInsertId();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE ALMACENÓ UN DOCUMENTO DE MANERA EXITOSA PARA EL ACTA: $acta_id";
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,2,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=>'DOCUMENTO ALMACENADO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveDocOIN()
	{
		try {
			session_start();
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

			$name 		 	= mb_strtoupper($_POST['nombre'],'utf-8');
			$comentario		= mb_strtoupper($_POST['comentario'],'utf-8');
			$oin_id		= $_POST['oin_id'];

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
			$this->sql = "INSERT INTO documentos_oin
			(id,nombre,archivo,oin_id,comentarios) 
			VALUES ('',?,?,?,?);";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$name,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$content,PDO::PARAM_LOB);
			$this->stmt->bindParam(3,$oin_id,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$comentario,PDO::PARAM_STR);
			$this->stmt->execute();
			$ultimo = $this->pdo->lastInsertId();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE ALMACENÓ UN DOCUMENTO DE MANERA EXITOSA PARA EL OIN: $oin_id";
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,2,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=>'DOCUMENTO ALMACENADO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getOnlyActa($acta)
	{
		try {
			$this->sql = "SELECT a.*, ar.nombre AS n_area, 
			CONCAT( p.nombre,' ',p.ap_pat,' ',p.ap_mat ) AS full_name,
			UPPER(m.nombre) AS n_municipio
			FROM actas AS a
			INNER JOIN areas AS ar ON ar.id = a.area_id 
			INNER JOIN personal AS p ON p.id = a.persona_id
			INNER JOIN municipios AS m ON m.id = a.municipio_id 
			WHERE a.id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$acta,PDO::PARAM_INT);
			$this->stmt->execute();
			$actas = $this->stmt->fetch(PDO::FETCH_OBJ);
			$aux = array();
			$aux['actas'] = $actas;
		#Recuperar los participantes en el acta
			$sql_person = "
			SELECT i.id ,i.rol, CONCAT(p.nombre, ' ' , p.ap_pat,' ',p.ap_mat) AS full_name , 
			p.id AS id_persona
			FROM investigadores AS i 
			INNER JOIN personal AS p ON  p.id = i.persona_id
			WHERE i.acta_id = ?
			";
			$this->stmt = $this->pdo->prepare($sql_person);
			$this->stmt->bindParam(1,$acta,PDO::PARAM_INT);
			$this->stmt->execute();
			$investigadores = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['inv']=$investigadores;
		#AGREGAR A LOS QUEJOSOS
			$this->sql = "SELECT * FROM quejosos_sira WHERE acta_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$acta,PDO::PARAM_INT);
			$this->stmt->execute();
			$quejosos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['quejosos'] = $quejosos;
		#AGREGAR A LOS PRESUNTOS RESPONSABLES
			$this->sql = "SELECT * FROM p_responsables WHERE acta_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$acta,PDO::PARAM_INT);
			$this->stmt->execute();
			$pr = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['pr'] = $pr;
		#AGREGAR A LAS UNIDADES
			$this->sql = "SELECT u.*,s.nombre AS submarca, m.nombre AS marca 
			FROM u_implicadas_sira AS u
			INNER JOIN submarcas AS s ON s.id = u.sub_marca
			INNER JOIN marcas AS m ON m.id = s.marca_id 
			WHERE u.acta_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$acta,PDO::PARAM_INT);
			$this->stmt->execute();
			$unidades = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['unidades'] = $unidades;
		#AGREGAR A LAS ANIMALES
			$this->sql = "SELECT * FROM animales WHERE acta_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$acta,PDO::PARAM_INT);
			$this->stmt->execute();
			$animales = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['animales'] = $animales;
		#AGREGAR A LAS ARMAS
			$this->sql = "SELECT * FROM armas WHERE acta_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$acta,PDO::PARAM_INT);
			$this->stmt->execute();
			$armas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['armas'] = $armas;

			#Recuperar la relacion con OINs
			$sql_select_oin = "SELECT oin_id FROM relacion_oin_actas WHERE acta_id = ?";
			$this->stmt = $this->pdo->prepare($sql_select_oin);
			$this->stmt->bindParam(1,$acta,PDO::PARAM_INT);
			$this->stmt->execute();
			$conteo = $this->stmt->rowCount();
			if( $conteo>0  ){
				$oin_id = $this->stmt->fetch(PDO::FETCH_OBJ)->oin_id;
				$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
				$sql_oin = "
				SELECT * FROM orden_inspeccion WHERE id = ?
				";
				$this->stmt = $this->getPDO()->prepare($sql_oin);
				$this->stmt->bindParam(1,$oin_id,PDO::PARAM_INT);
				$this->stmt->execute();
				$oin = $this->stmt->fetch(PDO::FETCH_OBJ);
				$aux['oin']=$oin;
			}else{
				$aux['oin']=NULL;
			}
			return $aux;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getMunicipios()
	{
		try {
			$this->sql = "SELECT id, UPPER(nombre) AS nombre FROM municipios";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getPresuntos()
	{
		try {
			$this->sql = "SELECT *, CONCAT(nombre,' ',ap_pat,' ',ap_mat) AS full_name FROM p_responsables WHERE acta_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$_POST['acta'],PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getQuejosos()
	{
		try {
			$this->sql = "SELECT *, CONCAT(nombre,' ',ap_pat,' ',ap_mat) AS full_name FROM quejosos_sira WHERE acta_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1, $_POST['acta'],PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getMarcas()
	{
		try {
			$this->sql = "SELECT s.id, CONCAT(s.nombre,' (',m.nombre,')') AS nombre FROM submarcas AS s
			INNER JOIN marcas AS m ON m.id = s.marca_id";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getAutos()
	{
		try {
			if ( !isset($_POST['acta']) ) {
				throw new Exception("NO SE RECIBIO EL ACTA QUE DESEA BUSCAR.", 1);				
			}
			$acta_id = $_POST['acta'];
			$this->sql = "SELECT u.*,m.nombre AS n_marca ,s.nombre AS n_submarca FROM u_implicadas_sira AS u 	
			INNER JOIN submarcas AS s ON s.id = u.sub_marca 
			INNER JOIN marcas AS m ON m.id = s. marca_id
			WHERE u.acta_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1, $acta_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function editActa()
	{
		try {
			session_start();
			#print_r($_POST['investigadores'] );exit;
			#Las variables
			if ( !isset($_POST['acta_id']) OR empty($_POST['acta_id']) ) {
				throw new Exception("ACTA NO RECONOCIDA.", 1);
			}
			if ( !isset($_POST['area_h']) OR empty($_POST['area_h']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN EL CAMPO DE ÁREA.", 1);
			}
			if ( !isset($_POST['f_acta']) OR empty($_POST['f_acta']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN LA FECHA DEL ACTA.", 1);
			}
			
			if ( !isset($_POST['procedencia']) OR empty($_POST['procedencia']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN LA PROCEDENCIA.", 1);
			}
			if ( !isset($_POST['municipio']) OR empty($_POST['municipio']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN EL CAMPO DE MUNICIPIO.", 1);
			}
			if ( !isset($_POST['lugar']) OR empty($_POST['lugar']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN EL CAMPO DEL LUGAR.", 1);
			}
			if ( !isset($_POST['accion']) OR empty($_POST['accion']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN EL CAMPO DEL ACCIONES REALIZADAS.", 1);
			}
			if ( !isset($_POST['investigadores']) ) {
				throw new Exception("NO SE HA SELECCIONADO NINGUN INVESTIGADOR.", 1);
			}
			$acta_id 		= $_POST['acta_id'];
			$area			= $_POST['area_h'];	
			$persona		= $_SESSION['id'];	
			$fecha			= $_POST['f_acta'];		
			$t_actuacion	= $_POST['t_actuacion'];				
			$procedencia	= $_POST['procedencia'];				
			$municipio		= $_POST['municipio'];			
			$lugar			= mb_strtoupper($_POST['lugar'],'utf-8');		
			$comentarios	= mb_strtoupper($_POST['accion'],'utf-8');				
			$this->sql = "UPDATE actas SET 
				area_id = ? ,
				persona_id = ? ,
				fecha = ? ,
				
				procedencia = ? ,
				municipio_id = ? ,
				lugar = ? ,
				comentarios = ?,
				t_actuacion = ?
				WHERE id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$area,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$persona,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$fecha,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$procedencia,PDO::PARAM_INT);
			$this->stmt->bindParam(5,$municipio,PDO::PARAM_INT);
			$this->stmt->bindParam(6,$lugar,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$comentarios,PDO::PARAM_STR);
			$this->stmt->bindParam(8,$t_actuacion,PDO::PARAM_STR);
			$this->stmt->bindParam(9,$acta_id,PDO::PARAM_INT);
			$this->stmt->execute();
			
			#INSERTAR LOS INVESTIGADORES
			 
			$sql_delete_inv = "
			DELETE FROM investigadores WHERE acta_id = ?
			";
			$this->stmt = $this->pdo->prepare($sql_delete_inv);
			$this->stmt->bindParam(1, $acta_id , PDO::PARAM_INT);
			$this->stmt->execute();

			$cuenta_p = count($_POST['investigadores']);
			for ($i=0; $i < $cuenta_p; $i++) { 
				$sql_insert_inv = "INSERT INTO investigadores 
				(id,persona_id,rol,acta_id) 
				VALUES 
				( '',?,1,? )
				";
				$this->stmt = $this->pdo->prepare($sql_insert_inv);
				$this->stmt->bindParam(1, $_POST['investigadores'][$i], PDO::PARAM_INT);
				$this->stmt->bindParam(2, $acta_id, PDO::PARAM_INT);
				$this->stmt->execute();
			}
			#INSERTAR EL PERSONAL DE APOYO
			
			if ( !empty($_POST['apoyo']) ) {
				if ( count($_POST['apoyo']) > 0 ) {
					$cuenta_a = count($_POST['apoyo']);
					for ($i=0; $i < $cuenta_a; $i++) { 
						$sql_insert_apo = "INSERT INTO investigadores 
						(id,persona_id,rol,acta_id) 
						VALUES 
						( '',?,2,? )
						";
						$this->stmt = $this->pdo->prepare($sql_insert_apo);
						$this->stmt->bindParam(1, $_POST['apoyo'][$i], PDO::PARAM_INT);
						$this->stmt->bindParam(2, $acta_id, PDO::PARAM_INT);
						$this->stmt->execute();
					}
				}
			}
			#INSERTAR LA RELACION CON UNA OIN
			if ( $_POST['question'] == '1' ) {
				$sql_delete_inv = "
				DELETE FROM relacion_oin_actas WHERE acta_id = ?
				";
				$this->stmt = $this->pdo->prepare($sql_delete_inv);
				$this->stmt->bindParam(1, $acta_id , PDO::PARAM_INT);
				$this->stmt->execute();

				$o = $_POST['orden_h']; 
				$sql_rel = "
				INSERT INTO relacion_oin_actas (id,oin_id, acta_id)
				VALUES ('',?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $o , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $acta_id, PDO::PARAM_INT);
				$this->stmt->execute();
			}
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE EDITÓ EL ACTA CON ID: ".$acta_id;
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,3,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE A EDITADO EL ACTA DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function savePresuntoR()
	{
		try {
			session_start();
			if ( empty($_POST['acta_id']) ) {
				throw new Exception("SE DETECTO QUE EL ACTA NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$acta_id 	= $_POST['acta_id'];
			}
			if ( empty( $_POST['nombre'] ) ) {
				throw new Exception("EL CAMPO NOMBRE ES OBLIGATORIO. ", 1);
				
			}
			$nombre 		= mb_strtoupper($_POST['nombre'],'utf-8');
			$ap_pat 		= ( !empty( $_POST['ap_pat'] ) ) ? mb_strtoupper($_POST['ap_pat'],'utf-8') : NULL ;
			$ap_mat 		= ( !empty( $_POST['ap_mat'] ) ) ? mb_strtoupper($_POST['ap_mat'],'utf-8') : NULL ;
			$genero 		= ( !empty( $_POST['genero'] ) ) ? $_POST['genero'] : NULL ;
			$cargo 			= ( !empty( $_POST['cargo'] ) ) ? $_POST['cargo']: NULL ;
			$procedencia 	= ( !empty( $_POST['procedencia'] ) ) ? $_POST['procedencia']: NULL ;
			$media_f 		= ( !empty( $_POST['media_f'] ) ) ? mb_strtoupper($_POST['media_f'],'utf-8'): NULL ;

			$this->sql = "
			INSERT INTO p_responsables(
				id, 
				nombre, 
				ap_pat, 
				ap_mat, 
				genero, 
				cargo_id, 
				procedencia, 
				media_f,
				acta_id
			) VALUES (
				'',
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?
			)
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$nombre,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$ap_pat,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$ap_mat,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$genero,PDO::PARAM_INT);
			$this->stmt->bindParam(5,$cargo,PDO::PARAM_INT);
			$this->stmt->bindParam(6,$procedencia,PDO::PARAM_INT);
			$this->stmt->bindParam(7,$media_f,PDO::PARAM_STR);
			$this->stmt->bindParam(8,$acta_id,PDO::PARAM_STR);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE DIO DE ALTA UN PRESUNTO RESPONSABLE DE MANERA EXITOSA PARA EL ACTA: $acta_id";
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,2,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ UN PRESUNTO RESPONSABLE DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveAuto()
	{
		try {
			session_start();
			if ( empty($_POST['acta_id']) ) {
				throw new Exception("SE DETECTO QUE EL ACTA NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$acta_id 	= $_POST['acta_id'];
			}
			$sub_marca		= ( !empty($_POST['submarca']) ) ? $_POST['submarca'] : NULL ; 		
			$t_auto			= ( !empty($_POST['t_vehiculo']) ) ? $_POST['t_vehiculo'] : NULL ; 	
			$modelo			= ( !empty($_POST['modelo']) ) ? $_POST['modelo'] : NULL ; 	
			$color			= ( !empty($_POST['color']) ) ? mb_strtoupper($_POST['color']) : NULL ; 	
			$placa			= ( !empty($_POST['placa']) ) ? mb_strtoupper($_POST['placa']) : NULL ; 	
			$niv			= ( !empty($_POST['niv']) ) ? mb_strtoupper($_POST['niv']) : NULL ; 	
			$n_inventario	= ( !empty($_POST['inv']) ) ? mb_strtoupper($_POST['inv']) : NULL ; 			
			$corporacion	= ( !empty($_POST['corporacion']) ) ? $_POST['corporacion'] : NULL ;			
			$this->sql = "
			INSERT INTO u_implicadas_sira (
				id,
				acta_id,
				sub_marca,
				t_auto,
				modelo,
				color,
				placa,
				niv,
				n_inventario,
				corporacion
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
				?
				
			)
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$acta_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$sub_marca,PDO::PARAM_INT);
			$this->stmt->bindParam(3,$t_auto,PDO::PARAM_INT);
			$this->stmt->bindParam(4,$modelo,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$color,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$placa,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$niv,PDO::PARAM_STR);
			$this->stmt->bindParam(8,$n_inventario,PDO::PARAM_STR);
			$this->stmt->bindParam(9,$corporacion,PDO::PARAM_STR);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE DIO DE ALTA UN VEHÍCULO IMPLICADO DE MANERA EXITOSA PARA EL ACTA: $acta_id";
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,2,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE AGREGÓ UN VEHÍCULO IMPLICADO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function deletePR()
	{
		try {
			session_start();
			$e 		= $_POST['e'];
			$this->sql = "
			DELETE FROM p_responsables WHERE id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$e,PDO::PARAM_INT);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE A ELIMINDADO UN PRESUNTO RESPONSABLE CON ID: ".$e;
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,4,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE ELIMINÓ UN PRESUNTO RESPONSABLE DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function deleteQuejoso()
	{
		try {
			session_start();
			$e 		= $_POST['e'];
			$this->sql = "
			DELETE FROM quejosos_sira WHERE id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$e,PDO::PARAM_INT);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE A ELIMINDADO UN QUEJOSO CON ID: ".$e;
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,4,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE ELIMINÓ UN PRESUNTO RESPONSABLE DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveQuejoso()
	{
		try {
			session_start();
			if ( empty($_POST['acta_id']) ) {
				throw new Exception("SE DETECTO QUE EL ACTA NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$acta_id 	= $_POST['acta_id'];
			}
			if ( empty( $_POST['nombre'] ) ) {
				throw new Exception("EL CAMPO NOMBRE ES OBLIGATORIO. ", 1);
				
			}
			$nombre 		= mb_strtoupper($_POST['nombre'],'utf-8');
			$ap_pat 		= ( !empty( $_POST['ap_pat'] ) ) ? mb_strtoupper($_POST['ap_pat'],'utf-8') : NULL ;
			$ap_mat 		= ( !empty( $_POST['ap_mat'] ) ) ? mb_strtoupper($_POST['ap_mat'],'utf-8') : NULL ;
			$genero 		= ( !empty( $_POST['genero'] ) ) ? $_POST['genero'] : NULL ;
			$mail 			= ( !empty($_POST['email']) ) ? mb_strtolower($_POST['email']) : NULL;
			$dir 			= ( !empty( $_POST['direccion'] ) ) ? mb_strtoupper($_POST['direccion'],'utf-8') : NULL ;
			$phone 			= ( !empty( $_POST['phone'] ) ) ? $_POST['phone'] : NULL ;
			$this->sql = "
			INSERT INTO quejosos_sira(
				id, 
				nombre, 
				ap_pat, 
				ap_mat, 
				genero, 
				phone, 
				email, 
				direccion,
				acta_id
			) VALUES (
				'',
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?
			)
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$nombre,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$ap_pat,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$ap_mat,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$genero,PDO::PARAM_INT);
			$this->stmt->bindParam(5,$phone,PDO::PARAM_INT);
			$this->stmt->bindParam(6,$mail,PDO::PARAM_INT);
			$this->stmt->bindParam(7,$direccion,PDO::PARAM_STR);
			$this->stmt->bindParam(8,$acta_id,PDO::PARAM_STR);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE DIO DE ALTA UN QUEJOSO DE MANERA EXITOSA PARA EL ACTA: $acta_id";
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,2,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ UN QUEJOSO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function deleteAuto()
	{
		try {
			session_start();
			$e 		= $_POST['e'];
			$acta 		= $_POST['acta'];
			$this->sql = "
			DELETE FROM u_implicadas_sira WHERE id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$e,PDO::PARAM_INT);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE A ELIMINDADO UN VEHÍCULO CON ID: ".$e." PARA EL ACTA CON ID: ".$acta;
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,4,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE ELIMINÓ UN VEHICULO IMPLICADO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveAnimal()
	{
		try {
			session_start();
			if ( empty($_POST['acta_id']) ) {
				throw new Exception("SE DETECTO QUE EL ACTA NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$acta_id 	= $_POST['acta_id'];
			}
			if ( empty( $_POST['nombre'] ) ) {
				throw new Exception("EL CAMPO NOMBRE ES OBLIGATORIO. ", 1);
			}
			if ( empty( $_POST['raza'] ) ) {
				throw new Exception("EL CAMPO NOMBRE ES OBLIGATORIO. ", 1);
			}
			$t_animal	= ( !empty($_POST['animal']) ) ? $_POST['animal'] : NULL ;
			$raza		= ( !empty($_POST['raza']) ) ? mb_strtoupper($_POST['raza'],'utf-8') : NULL ;
			$nombre 	= ( !empty($_POST['nombre']) ) ? mb_strtoupper($_POST['nombre'],'utf-8') : NULL ;
			$edad 		= ( !empty($_POST['edad']) ) ? mb_strtoupper($_POST['edad'],'utf-8') : NULL ;
			$color 		= ( !empty($_POST['color']) ) ? mb_strtoupper($_POST['color'],'utf-8') : NULL ;
			$inv 		= ( !empty($_POST['inv']) ) ? mb_strtoupper($_POST['inv'],'utf-8') : NULL ;
			$corp 		= ( !empty($_POST['corporacion']) ) ? $_POST['corporacion'] : NULL ;
			$this->sql = "
			INSERT INTO animales(id, tipo, raza, nombre, edad, color, inv, corporacion, acta_id)
			VALUES (
				'',?,?,?,?,?,?,?,?
			)
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$t_animal,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$raza,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$nombre,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$edad,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$color,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$inv,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$corp,PDO::PARAM_INT);
			$this->stmt->bindParam(8,$acta_id,PDO::PARAM_INT);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE DIO DE ALTA UN QUEJOSO DE MANERA EXITOSA PARA EL ACTA: $acta_id";
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,2,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ UN QUEJOSO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getAnimales()
	{
		try {
			if ( !isset($_POST['acta']) ) {
				throw new Exception("NO SE RECIBIO EL ACTA QUE DESEA BUSCAR.", 1);				
			}
			$acta_id = $_POST['acta'];
			$this->sql = "SELECT * FROM animales WHERE acta_id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1, $acta_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function deleteAnimal()
	{
		try {
			session_start();
			$e 		= $_POST['e'];
			$acta 		= $_POST['acta'];
			$this->sql = "
			DELETE FROM animales WHERE id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$e,PDO::PARAM_INT);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE A ELIMINDADO UN ANIMAL CON ID: ".$e." PARA EL ACTA CON ID: ".$acta;
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,4,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE ELIMINÓ UN VEHICULO IMPLICADO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveArma()
	{
		try {
			session_start();
			if ( empty($_POST['acta_id']) ) {
				throw new Exception("SE DETECTO QUE EL ACTA NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$acta_id 	= $_POST['acta_id'];
			}
			$t_arma		= ( !empty($_POST['t_arma']) ) ? $_POST['t_arma'] : NULL ;
			$inv 		= ( !empty($_POST['inv']) ) ? mb_strtoupper($_POST['inv'],'utf-8') : NULL ;
			$corp 		= ( !empty($_POST['corp']) ) ? $_POST['corp'] : NULL ;
			$matricula 		= ( !empty($_POST['matricula']) ) ? $_POST['matricula'] : NULL ;
			$this->sql = "
			INSERT INTO armas(id, tipo, matricula, inv, corporacion, acta_id)
			VALUES (
				'',?,?,?,?,?
			)
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$t_arma,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$matricula,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$inv,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$corp,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$acta_id,PDO::PARAM_INT);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE DIO DE ALTA UN QUEJOSO DE MANERA EXITOSA PARA EL ACTA: $acta_id";
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,2,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ UN ARMA DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getArmas()
	{
		try {
			if ( !isset($_POST['acta']) ) {
				throw new Exception("NO SE RECIBIO EL ACTA QUE DESEA BUSCAR.", 1);				
			}
			$acta_id = $_POST['acta'];
			$this->sql = "SELECT * FROM armas WHERE acta_id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1, $acta_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function deleteArma()
	{
		try {
			session_start();
			$e 		= $_POST['e'];
			$acta 		= $_POST['acta'];
			$this->sql = "
			DELETE FROM armas WHERE id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$e,PDO::PARAM_INT);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE A ELIMINDADO UN ARMA CON ID: ".$e." PARA EL ACTA CON ID: ".$acta;
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,4,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE ELIMINÓ UN ARMA DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getDocumentos()
	{
		try {
			if ( empty($_POST['acta']) ) {
				throw new Exception("NO SE ENCONTRO EL ACTA QUE DESEA CONSULTAR.", 1);
			}
			$acta_id = $_POST['acta'];
			$this->sql = "SELECT id,nombre,comentarios FROM documentos_sira WHERE acta_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$acta_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$docs = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			
			return json_encode($docs);
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function deleteDoc()
	{
		try {
			session_start();
			$e 		= $_POST['e'];
			$acta 		= $_POST['acta'];
			$this->sql = "
			DELETE FROM documentos_sira WHERE id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$e,PDO::PARAM_INT);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE A ELIMINDADO UN DOCUMENTO CON ID: ".$e." PARA EL ACTA CON ID: ".$acta;
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,4,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE ELIMINÓ UN DOCUMENTO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function generateReporte()
	{
		try {
			$wh = "";
			if ( !empty($_POST['f_ini']) AND !empty($_POST['f_fin']) ) {
				$f_ini = $_POST['f_ini'] ;
				$f_fin = $_POST['f_fin'] ;
				$wh .= " AND a.fecha BETWEEN '$f_ini' AND '$f_fin' ";
			}
			if ( !empty($_POST['t_actuacion']) ) {
				$t_actuacion = $_POST['t_actuacion'] ;
				$wh .= " AND a.t_actuacion = $t_actuacion ";
			}
			if ( !empty($_POST['procedencia']) ) {
				$procedencia = $_POST['procedencia'] ;
				$wh .= " AND a.procedencia = $procedencia ";
			}
			if ( !empty($_POST['municipio']) ) {
				$municipio  = $_POST['municipio'];
				$wh .= " AND a.municipio_id = $municipio ";
			}
			if ( !empty($_POST['pr']) ) {
				$pr = $_POST['pr'] ;
				$wh = " AND a.id IN (SELECT acta_id FROM p_responsables WHERE CONCAT( nombre, ' ', ap_pat,' ',ap_mat ) LIKE '%$pr%') ";
			}
			if ( !empty($_POST['quejoso']) ) {
				$quejoso = $_POST['quejoso'];
				$wh .= " AND a.id IN (SELECT acta_id FROM quejosos_sira WHERE CONCAT( nombre, ' ', ap_pat,' ',ap_mat ) LIKE '%$quejoso%') ";
			}
			if ( !empty($_POST['comentarios']) ) {
				$comentarios = $_POST['comentarios'] ;
				$wh = " AND a.comentarios LIKE '%$comentarios%') ";
			}
						
			$this->sql = "SELECT a.*,m.nombre AS n_municipio FROM actas AS a  
			INNER JOIN municipios AS m ON m.id = a.municipio_id
			WHERE  1=1 ".$wh;
			#print_r($this->sql);exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$acta_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$docs = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			
			return json_encode($docs);
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function getDashboard()
	{
		try {
			$this->sql 		= "SELECT t_actuacion, COUNT(t_actuacion) AS cuenta FROM actas 
			#WHERE YEAR(fecha) = YEAR(NOW())
			GROUP BY t_actuacion";
			$this->stmt 	= $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$acta_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result 	= $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}
	public function getActasBy()
	{
		try {
			$ta = $_POST['ta'];
			$this->sql = "SELECT a.*,ar.nombre as n_area, CONCAT(p.nombre,' ',p.ap_pat,' ',p.ap_mat) AS n_abogado, UPPER(m.nombre) as n_municipio
			FROM actas AS a
			INNER JOIN areas AS ar ON  ar.id = a.area_id
			INNER JOIN personal AS p ON  p.id = a.persona_id
			INNER JOIN municipios AS m ON m.id = a.municipio_id
			WHERE a.t_actuacion = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$ta,PDO::PARAM_STR);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
} 	
?>