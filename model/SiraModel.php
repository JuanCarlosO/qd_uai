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
	public function saveActa()
	{
		try {
			session_start();
			#print_r(count($_POST['investigadores']) );exit;
			#Las variables
			if ( !isset($_POST['area_h']) OR empty($_POST['area_h']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO EN EL CAMPO DE ÁREA.", 1);
			}
			if ( !isset($_POST['f_acta']) OR empty($_POST['f_acta']) ) {
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
			if ( $_POST['t_actuacion'] == '1' ) {
				$ta = "INS";
			}
			if ( $_POST['t_actuacion'] == '2' ) {
				$ta = "VER";
			}
			if ( $_POST['t_actuacion'] == '3' ) {
				$ta = "SUP";
			}
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
			INNER JOIN areas AS ar ON ar.id = a.area_id
			INNER JOIN personal AS p ON p.id = a.persona_id
			INNER JOIN municipios AS m ON m.id = a.municipio_id
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
	public function SAVE($acta)
	{
		try {
			session_start();
			if ( empty($_POST['acta_id']) ) {
				throw new Exception("SE DETECTO QUE EL ACTA NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$acta_id 	= $_POST['acta_id'];
			}
			$nombre 		= $_POST['nombre'];
			$ap_pat 		= ( !empty( $_POST['ap_pat'] ) ) ? $_POST['ap_pat'] : 'NO CAPTURADO' ;
			$ap_mat 		= ( !empty( $_POST['ap_mat'] ) ) ? $_POST['ap_mat'] : 'NO CAPTURADO' ;
			$genero 		= ( !empty( $_POST['genero'] ) ) ? $_POST['genero'] : 'NO CAPTURADO' ;
			$cargo 			= ( !empty( $_POST['cargo'] ) ) ? $_POST['cargo']: NULL ;
			$procedencia 	= ( !empty( $_POST['procedencia'] ) ) ? $_POST['procedencia']: NULL ;
			$media_f 		= ( !empty( $_POST['media_f'] ) ) ? $_POST['media_f']: NULL ;

			$this->sql = "
			INSERT INTO p_responsables(
				id, 
				nombre, 
				ap_pat, 
				ap_mat, 
				genero, 
				cargo_id, 
				procedencia, 
				media_f
			) VALUES (
				'',
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
} 	
?>