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

						#$wh .= " AND ".$filter['columna'] ." = ".$filter['valor'];
					}
				}
			}
			
			$this->sql = "
			SELECT q.id AS queja_id,q.*, t.nombre AS n_tramite , e.nombre AS n_estado, p.nombre AS n_procedencia, CONCAT(p1.nombre, p1.ap_pat, p1.ap_mat) AS n_jefatura, 
				CONCAT(p2.nombre, ' ' ,p2.ap_pat,' ', p2.ap_mat) AS n_analista,
			qr.*, DATEDIFF(DATE(qr.created_at),DATE(NOW())) AS dias_t
			FROM quejas_respo AS qr
			INNER JOIN quejas AS q ON q.id = qr.queja_id
			LEFT JOIN personal AS p1 ON p1.id = qr.jefatura
			LEFT JOIN personal AS p2 ON p2.id = qr.analista
			INNER JOIN tipos_tramite AS t ON t.id = q.t_tramite
			INNER JOIN estado_guarda AS e ON e.id = q.estado
			INNER JOIN procedencias AS p ON p.id = q.procedencia
			WHERE qr.estado = 1 $wh ORDER BY q.$anexgrid->columna $anexgrid->columna_orden LIMIT $anexgrid->pagina , $anexgrid->limite
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$quejas = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$total = $this->stmt->rowCount();
			$aux = array();
			$expedientes = array();
			foreach ($quejas as $key => $exp) {
				if($exp->estado == 'ACTIVO'){
					$aux['id'] = $exp->id;
					$aux['queja_id'] = $exp->queja_id;
					$aux['asunto'] = $exp->t_asunto;				
					$aux['cve_ref'] = $exp->cve_ref;			
					$aux['n_turno'] = $exp->n_turno;			
					$aux['n_tramite'] = $exp->n_tramite;			
					$aux['cve_exp'] = $exp->cve_exp;			
					$aux['f_hechos'] = $exp->f_hechos;			
					$aux['h_hechos'] = $exp->h_hechos;		
					$aux['oficio'] = $exp->oficio;
					$aux['estado'] = $exp->estado;
					$aux['categoria'] = $exp->categoria;
					$aux['n_jefatura'] = $exp->n_jefatura;
					$aux['n_analista'] = $exp->n_analista;
					$aux['e_procesal'] = $exp->e_procesal;
					$aux['dias_t'] = $exp->dias_t;
					#buscar los presuntos responsables.
					$sql_presuntos = "SELECT UPPER(nombre) AS nombre FROM presuntos WHERE queja_id = ? ;";
					$this->stmt = $this->pdo->prepare($sql_presuntos);
					$this->stmt->bindParam(1,$exp->queja_id);
					$this->stmt->execute();
					$presuntos 	= $this->stmt->fetchAll(PDO::FETCH_OBJ);
					$aux['presuntos'] = $presuntos;
					array_push($expedientes,$aux);
				}
			}
			#print_r($expedientes);
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
			$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
				
			$this->sql = "SELECT id_generado AS id, no_oficio AS value 
			FROM oficios_generados WHERE no_oficio LIKE ?			
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
			$this->sql = "SELECT * FROM catalogo_cargos ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return $this->result;
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
			INNER JOIN catalogo_cargos AS c ON c.id = p.cargo_id 
			INNER JOIN personal AS pe ON pe.id = q.jefatura
			INNER JOIN personal AS per ON per.id = q.analista
			WHERE q.queja_id = ? AND q.estado = 'ACTIVO' LIMIT 1			
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
			return $this->result;
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
			$this->sql = "INSERT INTO quejas_respo (id, queja_id, oficio, comentarios, jefatura, analista, estado, e_procesal, autoridad, f_acuse, n_semana, fojas, t_doc) 
			VALUES (
			'',
			?,
			?,
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
		    $motivo			= mb_strtoupper($_POST['motivo'],'utf-8');
		    $comentarios	= mb_strtoupper($_POST['comentarios'],'utf-8');		
		    #Definir el Query	
		    if ( $e_procesal == 3 ) {
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
					2,
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
			    t_doc= :t_doc 
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
			return json_encode( array('status'=>'success','message'=>'EL ESTADO PROCESAL SE ACTUALIZADO DE MANERA EXITOSA.') );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getCedula($queja_id)
	{
		try {
			$aux = array();
			$this->sql = "SELECT q.*,p.nombre AS n_procedencia, t.nombre AS n_tramite,
			e.nombre AS n_estado
			FROM quejas AS q
			INNER JOIN procedencias AS p ON p.id = q.procedencia
			INNER JOIN tipos_tramite AS t ON t.id = q.t_tramite
			INNER JOIN estado_guarda AS e ON e.id = q.estado
			WHERE q.id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
			$aux['queja'] = $this->result;
			#Buscar las presuntas conductas 
			$this->sql = "SELECT *
			FROM presuntos WHERE queja_id = ? ;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux['presuntos'] = $this->result;
			#buscar las presuntas conductas
			$this->sql = "SELECT c.id, UPPER(c.nombre) AS n_conducta, l.nombre AS n_ley FROM p_conductas AS pc
			INNER JOIN catalogo_conductas AS c ON c.id = pc.conducta_id
			INNER JOIN leyes AS l ON l.id = c.ley
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
			INNER JOIN municipios AS m ON m.id = q.municipio_id
			WHERE q.queja_id = ?;";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$aux['quejosos'] = $this->result;
			#buscar presuntos responsables
			$this->sql = "SELECT p.*, UPPER(m.nombre) AS n_municipio, c.nombre AS n_cargo FROM presuntos AS p
			LEFT JOIN municipios AS m ON m.id = p.municipio_id
			LEFT JOIN catalogo_cargos AS c ON c.id = p.cargo_id
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
			$aux['opiniones'] = $this->result;
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
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ )->turnado;
			$aux['turnado'] = $this->result;
			
			return $aux;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveOpinion()
	{
		try {
			session_start();
			$qd_res = $_POST['qd_res'];
			
			$persona_id = $_SESSION['id'];
			$comentario = ( !empty( $_POST['comentario'] ) ) ? mb_strtoupper($_POST['comentario'], 'utf-8') : NULL ;
			if (is_null($comentario)) {
			 	throw new Exception("DEBES DE AGREGAR UN COMENTARIO.", 1);
			} 
			#Buscar el id de la queja
			$this->sql = "SELECT queja_id FROM quejas_respo WHERE id = ?";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$qd_res,PDO::PARAM_INT);
			$this->stmt->execute();
			$queja_id = $this->stmt->fetch(PDO::FETCH_OBJ)->queja_id;
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
			$this->sql = "SELECT * FROM conductas_respo";
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
			$persona = $_POST['sp_id'];
			$queja = $_POST['queja_id'];
			#buscar los expedientes turnados a respo
			$this->sql = "INSERT INTO a_responsables(id,queja_id,persona_id) VALUES ('',?,?) ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$persona,PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE A INSERTADO EL ABOGADO RESPONSABLE DEL EXPEDIENTE DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getExpedientesSC()
	{
		try {
			$anexgrid = new AnexGrid();
			$wh = " 1=1 ";
			#Los filtros 
			foreach ($anexgrid->filtros as $filter) {
				
				if ( $filter['columna'] != '' ) {
					$wh .= " AND ".$filter['columna']." LIKE '%".$filter['valor']."%'";
				}
			}
			
			$this->sql = "
			SELECT q.*
			FROM quejas AS q
			INNER JOIN quejas_respo AS qr ON qr.queja_id = q.id
			WHERE qr.estado = 1 AND qr.e_procesal = 1 AND $wh ORDER BY q.$anexgrid->columna $anexgrid->columna_orden LIMIT $anexgrid->pagina , $anexgrid->limite
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$quejas = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			$total = $this->stmt->rowCount();
			$aux = array();
			$expedientes = array();
			foreach ($quejas as $key => $exp) {
				$aux['id'] = $exp->id;
				$aux['queja_id'] = $exp->id;
				$aux['asunto'] = $exp->t_asunto;				
				$aux['cve_ref'] = $exp->cve_ref;			
				$aux['n_turno'] = $exp->n_turno;			
				$aux['cve_exp'] = $exp->cve_exp;			
				$aux['f_hechos'] = $exp->f_hechos;			
				$aux['h_hechos'] = $exp->h_hechos;		
				
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
				#Buscar el responsable del lic reyes
				$sql_ar = "SELECT concat(p.nombre, ' ', p.ap_pat,' ',p.ap_mat) as a_responsable FROM a_responsables as a 
				inner join personal as p on p.id = a.persona_id
				WHERE a.queja_id = ? ;";
				$this->stmt = $this->pdo->prepare($sql_ar);
				$this->stmt->bindParam(1,$exp->id);
				$this->stmt->execute();
				$a_responsable 	= $this->stmt->fetch(PDO::FETCH_OBJ);
				$conteo = $this->stmt->rowCount();
				if($conteo > 0){
					$aux['a_responsable'] = $a_responsable->a_responsable;
				}else{
					$aux['a_responsable'] = '';
				}
				#datos del lic hinojosa
				$sql_sapa = "SELECT qr.*,concat(p.nombre,' ',p.ap_pat,' ',p.ap_mat) as remitente FROM quejas_respo AS qr
				INNER JOIN personal as p on p.id = qr.jefatura
				WHERE qr.queja_id = ? AND qr.estado = 1 ;";
				$this->stmt = $this->pdo->prepare($sql_sapa);
				$this->stmt->bindParam(1,$exp->id);
				$this->stmt->execute();
				$sapa 	= $this->stmt->fetch(PDO::FETCH_OBJ);
				$aux['sapa'] = $sapa;				

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
			
			$this->sql = "INSERT INTO resoluciones(id,queja_id,sancion,oficio,fecha,comentario) VALUES ('',?,?,?,?,?) ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$sancion,PDO::PARAM_INT);
			$this->stmt->bindParam(3,$oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$f_sancion,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$comentario,PDO::PARAM_STR);
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
			    r.comentario AS solucion
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
			    DATEDIFF(DATE(NOW()),f_reserva ) AS control 
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
			$this->result	= $this->stmt->fetch(PDO::FETCH_OBJ);
			return $this->result;
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
			$sql = "INSERT INTO r_demanda (id,demanda_id,f_resolucion,comentario) 
			VALUES ('',?,?,?);";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->bindParam(1,$demanda_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$f_res,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$comentario,PDO::PARAM_STR);
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
			$queja_id = $_POST['queja_id'];
			$f_oficio = $_POST['f_oficio'];
			$f_reserva = $_POST['f_reserva'];
			$duracion = $_POST['duracion'];
			$oficio = $_POST['oficio'];
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

			$this->sql = "INSERT INTO reservas (id,queja_id,oficio,f_oficio, f_reserva, duracion, comentario,f_limite) 
			VALUES ('',?,?,?,?,?,?,?);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$f_oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$f_reserva,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$duracion,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$f_final,PDO::PARAM_STR);
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
			$comentario =  mb_strtoupper($_POST['comentario'],'utf-8');
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
			$destino  = $_SERVER['DOCUMENT_ROOT'].'/qd_uai/uploads/';
			#Mover el Doc
			move_uploaded_file($_FILES['file']['tmp_name'],$destino.$name);
			#abrir el archivo
			$file 		= fopen($destino.$name,'r');
			$content 	= fread($file, $size);
			$content 	= addslashes($content);
			fclose($file);
			#Eliminar  el archivo 
			unlink($_SERVER['DOCUMENT_ROOT'].'/qd_uai/uploads/'.$name);	
			
			$this->sql = "INSERT INTO acuerdos_improcedencia (id, queja_id, f_acuerdo, f_turno, comentario, archivo) 
			VALUES ('',?,?,?,?,?);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$f_acuerdo,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$f_turno,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$content,PDO::PARAM_LOB);
			$this->stmt->execute();
			#CAMBIAR EL ESTADO GUARDA DEL EXPEDIENTE
			$this->sql = "UPDATE quejas SET estado = 11 WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,PDO::PARAM_INT);
			$this->stmt->execute();
			return json_encode( array('status'=>'success','message'=>'SE HA GUARDADO EL ACUERDO DE IMPORCEDENCIA DE MANERA EXITOSA.') );
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	
	public function getReporte()
	{
		try {
			
			$wh = "";
			$wh .= " AND q.f_hechos BETWEEN '".$_POST['f_ini']."' AND '".$_POST['f_fin']."' ";
			$wh .= " AND q.estado = ".$_POST['estado'];
			$this->sql = "
			SELECT qr.id AS qr_id, q.cve_exp, qr.oficio, e.nombre AS n_estado,
			q.id AS queja_id, qr.e_procesal
			FROM quejas_respo AS qr
			INNER JOIN quejas AS q ON q.id = qr.queja_id
			INNER JOIN estado_guarda AS e ON e.id = q.estado
			WHERE qr.estado = 1 $wh
			";
			
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$queja_id,);
			$this->stmt->execute();
			$q_respo = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			
			return json_encode($q_respo);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
}
?>