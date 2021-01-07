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
			//$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
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
			
			$year = $_REQUEST['parametros'][0]['year'];
			$anexgrid = new AnexGrid();
			$wh = " 1=1 ";
			foreach ($anexgrid->filtros as $filter) {
				
				if ($filter['columna'] == 'o.clave') {
					$wh .= " AND ".$filter['columna'] ." LIKE '%".$filter['valor']."%'";
				}elseif($filter['columna'] == 'o.f_creacion' ){
					$wh .= " AND DATE(".$filter['columna'] .") = '".$filter['valor']."'";
				}else if ($filter['columna'] == 'ofi') {

					$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
					$sql_ofi = "SELECT id_generado FROM oficios_generados 
					WHERE no_oficio LIKE '%".$filter['valor']."%'";
					$this->stmt = $this->pdo->prepare($sql_ofi);
					//$this->stmt->bindParam(1,$cen->no_oficio,PDO::PARAM_INT);
					$this->stmt->execute();
					$ofi = $this->stmt->fetchAll(PDO::FETCH_OBJ);
					$aux['ofi'] = $ofi;

					$a = array();
						foreach ($ofi as $key => $ac) {
							array_push($a, $ac ->id_generado);
						}
					$a = implode(',',$a);

					$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
					$wh .= " AND o.oficio_id IN ($a)";

				} else{
					$wh .= " AND ".$filter['columna'] ." = ".$filter['valor'];
				}
				
			}
			
			#Generar la conexion nueva
	
			$this->sql = "
			SELECT o.id, o.oficio_id, o.clave, o.despachador_id, o.estatus, o.f_creacion, o.comentario, o.t_orden, a.id AS id_accion, ac.id AS abc 
			FROM orden_inspeccion AS o
            LEFT JOIN acciones_ot AS a ON a.ot_id = o.id            
            LEFT JOIN actas AS ac ON ac.oin_id = o.id
			WHERE $wh AND YEAR(f_creacion) = $year ORDER BY $anexgrid->columna $anexgrid->columna_orden LIMIT $anexgrid->pagina , $anexgrid->limite";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			
			$this->sql = "SELECT 
			count(*) as total
			FROM orden_inspeccion AS o
			WHERE $wh AND YEAR(f_creacion) = $year";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();

			$cuenta = $this->stmt->fetch(PDO::FETCH_OBJ);

			$cuenta = $cuenta->total;
			$aux = array();
			$ot = array();
			foreach ($this->result as $key => $oin) {
				$aux['id'] = $oin->id;
				$aux['clave'] = $oin->clave;
				$aux['f_creacion'] = $oin->f_creacion;
				$aux['comentario'] = $oin->comentario;
				$aux['estatus'] = $oin->estatus;
				$aux['oficio'] = $oin->oficio_id;
				$aux['t_orden'] = $oin->t_orden;
				$aux['id_accion'] = $oin->id_accion;
				if ($oin->t_orden == 'INS' ) { $aux['t_orden'] = 'INSPECCIÓN'; }
				if ($oin->t_orden == 'SUP' ) { $aux['t_orden'] = 'SUPERVISIÓN'; }
				if ($oin->t_orden == 'INV' ) { $aux['t_orden'] = 'INVESTIGACIÓN'; }
				if ($oin->t_orden == 'VER' ) { $aux['t_orden'] = 'VERIFICACIÓN'; }

			$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_oficios = "SELECT og.no_oficio AS ofi FROM oficios_generados AS og 
				WHERE og.id_generado = ?";
				//$this->stmt = $this->pdo->prepare($sql_oficios);
				$this->stmt = $this->getPDO()->prepare($sql_oficios);
				$this->stmt->bindParam(1,$oin->oficio_id,PDO::PARAM_INT);
				$this->stmt->execute();

				//$oficios = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				//$aux['oficios'] = $oficios;
				if ( $this->stmt->rowCount() > 0 ) {
					$oficios = $this->stmt->fetch(PDO::FETCH_OBJ);
						$aux['ofi'] = $oficios->ofi;
					}else{
						$aux['ofi'] = NULL;
					}			
	

			$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
			$sql_semaforo = "SELECT do.id, do.f_envio, do.f_acuse, 
				DATEDIFF(DATE(NOW()),DATE(do.f_acuse) ) AS dias, 
				DATEDIFF(DATE(do.f_limite),DATE(do.f_acuse) ) AS limite, 
				info.fecha AS respuesta, recor.f_acuse AS recordatorio, 
				DATEDIFF(DATE(NOW()),DATE(recor.f_acuse) ) AS diasrecor,
				DATEDIFF(DATE(recor.f_limite),DATE(recor.f_acuse) ) AS limiterecor 
				FROM datos_observaciones AS do
				LEFT JOIN info_seguimiento AS info ON info.id_ot = do.id_ot
                LEFT JOIN recordatorio_obs AS recor ON recor.ot_id = do.id_ot
				WHERE do.id_ot = ?";
				$this->stmt = $this->getPDO()->prepare($sql_semaforo);
				$this->stmt->bindParam(1,$oin->id,PDO::PARAM_INT);
				$this->stmt->execute();

				if ( $this->stmt->rowCount() > 0 ) {
					$semaforo = $this->stmt->fetch(PDO::FETCH_OBJ);
						$aux['f_envio'] = $semaforo->f_envio;
						$aux['f_acuse'] = $semaforo->f_acuse;
						$aux['dias'] = $semaforo->dias;
						$aux['limite'] = $semaforo->limite;
						$aux['respuesta'] = $semaforo->respuesta;
					}else{
						$aux['f_envio'] = NULL;
						$aux['f_acuse'] = NULL;
						$aux['dias'] = NULL;
						$aux['limite'] = NULL;
						$aux['respuesta'] = NULL;
					}

				$sql_reco = "SELECT MAX(f_acuse) AS recordatorio, 
				DATEDIFF(DATE(NOW()),DATE((SELECT MAX(f_acuse) FROM recordatorio_obs WHERE ot_id = ?))) AS diasrecor,
				DATEDIFF(DATE((SELECT MAX(f_limite) FROM recordatorio_obs WHERE ot_id = ?)),DATE((SELECT MAX(f_acuse) FROM recordatorio_obs WHERE ot_id = ?))) AS limiterecor 
				FROM recordatorio_obs AS recor WHERE ot_id = ?";
				//echo($sql_reco);
				//exit;
				
				$this->stmt = $this->getPDO()->prepare($sql_reco);
				$this->stmt->bindParam(1,$oin->id,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$oin->id,PDO::PARAM_INT);
				$this->stmt->bindParam(3,$oin->id,PDO::PARAM_INT);
				$this->stmt->bindParam(4,$oin->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$recordatorio = $this->stmt->fetch(PDO::FETCH_OBJ);
				
				$aux['recordatorio'] = $recordatorio->recordatorio;
				$aux['diasrecor'] = $recordatorio->diasrecor;
				$aux['limiterecor'] = $recordatorio->limiterecor;



//linea pendiente


			/*	$this->sql = "SELECT  *
					FROM oficios_generados_referencia 
					WHERE id_generado = ?";
					$this->stmt = $this->getPDO()->prepare($this->sql);
					$this->stmt->bindParam(1,$orden->oficio_id,PDO::PARAM_INT);
					$this->stmt->execute();
					if ( $this->stmt->rowCount() > 0 ) {
						$ref = $this->stmt->fetch(PDO::FETCH_OBJ);
						$aux['ref'] = $ref;
						$cve_exp = "%".$ref->referencia."%";
					}else{
						$cve_exp = NULL;
					}		*/	



				$sql_acta = "SELECT id FROM acta_admin WHERE detipo = 2 and id_tipo = ?";
				$this->stmt = $this->pdo->prepare($sql_acta);
				//$this->stmt = $this->getPDO()->prepare($sql_acta);
				$this->stmt->bindParam(1,$oin->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$actas = $this->stmt->fetchAll(PDO::FETCH_OBJ);

				$a = array();
					foreach ($actas as $key => $ac) {
						array_push($a, $ac ->id);
					}
				$a = implode(',',$a);
				$aux['actas'] = $actas;


				$sql_acta_old = "SELECT id FROM actas WHERE oin_id = ?";
				$this->stmt = $this->pdo->prepare($sql_acta_old);
				//$this->stmt = $this->getPDO()->prepare($sql_acta);
				$this->stmt->bindParam(1,$oin->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$acta_old = $this->stmt->fetchAll(PDO::FETCH_OBJ);

				$ac = array();
					foreach ($acta_old as $key => $act) {
						array_push($ac, $act ->id);
					}
				$ac = implode(',',$ac);
				$aux['acta_old'] = $acta_old;


			
				/*$sql_expediente = "SELECT cve_exp AS exp FROM quejas 
				WHERE acta_admin = $a";
				//$this->stmt = $this->pdo->prepare($sql_oficios);
				$this->stmt = $this->getPDO()->prepare($sql_expediente);
				//$this->stmt->bindParam(1,$actas,PDO::PARAM_INT);
				$this->stmt->execute();
				$expedientes = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['expedientes'] = $expedientes;*/

				$sql_expediente = "SELECT cve_exp AS exp FROM quejas
				INNER JOIN relacion_ot_expediente AS e ON e.expediente_id = quejas.id
				WHERE e.orden_id = ?";
				$this->stmt = $this->pdo->prepare($sql_expediente);
				$this->stmt->bindParam(1,$oin->id,PDO::PARAM_INT);
				$this->stmt->execute();				
				$expedientes = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['expedientes'] = $expedientes;


			
				
				array_push($ot, $aux);
			}
			
			//print_r($ot);
			//exit;
			return $anexgrid->responde($ot,$cuenta);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getOficioById($id)
	{
		try {
			$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
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
			$oin_id		= $_POST['orden_h'];
			#echo $clave;exit;				
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
				comentarios,
				oin_id
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
			$this->stmt->bindParam(10,$oin_id,PDO::PARAM_INT);
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

	public function saveAcciones()
	{
		try {

			$ot_id	= $_POST['ot_id'];

			$sql_orden = "SELECT t_orden FROM orden_inspeccion WHERE id = ?";			
			$this->stmt = $this->pdo->prepare($sql_orden);
			$this->stmt->bindParam(1,$ot_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$t_orden = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
			$t_orden = implode(', ',$t_orden[0]);
		
			if($t_orden == 'SUPERVISION'){
				$red_vial	= mb_strtoupper($_POST['red'],'utf-8');
				$referencia_red	= mb_strtoupper($_POST['referencia_red'],'utf-8');
				$calle	= NULL;
				$numero	= NULL;
				$colonia	= NULL;
				$cp		= 	NULL;


			} else if ($t_orden == 'INSPECCION') {
				$calle	= mb_strtoupper($_POST['calle'],'utf-8');
				$numero	= $_POST['numero'];
				$colonia	= mb_strtoupper($_POST['colonia'],'utf-8');
				$cp		= 	$_POST['cp'];
				$red_vial	= NULL;
				$referencia_red	= NULL;
			}
			
			$fecha	= $_POST['f_acta'];	
			$municipio	= $_POST['municipio'];			
			$acciones	= mb_strtoupper($_POST['acciones'],'utf-8');
			

			$this->sql = "INSERT INTO acciones_ot (
				id, 
				fecha, 
				red_vial, 
				referencia_red, 
				calle, 
				numero, 
				colonia, 
				cp, 
				municipio_id, 
				acciones,
				ot_id
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
				?
			)";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$fecha,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$red_vial,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$referencia_red,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$calle,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$numero,PDO::PARAM_INT);
			$this->stmt->bindParam(6,$colonia,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$cp,PDO::PARAM_INT);
			$this->stmt->bindParam(8,$municipio,PDO::PARAM_INT);
			$this->stmt->bindParam(9,$acciones,PDO::PARAM_STR);
			$this->stmt->bindParam(10,$ot_id,PDO::PARAM_INT);
			$this->stmt->execute();

			$ultimo = $this->pdo->lastInsertId();

			#INSERTAR LOS INVESTIGADORES 
			$cuenta_p = count($_POST['investigadores']); 
			$sql_insert_inv = "INSERT INTO investigadores 
			(id,acta_id,persona_id,rol) 
			VALUES 
			( '',?,?,1)
			";
			for ($i=0; $i < $cuenta_p; $i++) { 
				$this->stmt = $this->pdo->prepare($sql_insert_inv);
				$this->stmt->bindParam(1, $ultimo, PDO::PARAM_INT);
				$this->stmt->bindParam(2, $_POST['investigadores'][$i], PDO::PARAM_INT);			
				$this->stmt->execute();
			}
			#INSERTAR EL PERSONAL DE APOYO
			$cuenta_a = count($_POST['apoyo']); 
			$sql_insert_apo = "INSERT INTO investigadores 
			(id,acta_id,persona_id,rol) 
			VALUES 
			( '',?,?,2 )
			";
			for ($i=0; $i < $cuenta_a; $i++) { 
				$this->stmt = $this->pdo->prepare($sql_insert_apo);
				$this->stmt->bindParam(1, $ultimo, PDO::PARAM_INT);
				$this->stmt->bindParam(2, $_POST['apoyo'][$i], PDO::PARAM_INT);				
				$this->stmt->execute();
			}
			

			$area	= $_POST['question_a'];	
			$area2	= $_POST['question_a2'];

			if ( $area == '1' ) {
				$coord	= $_POST['coord'];	
				$subd	= $_POST['subd'];
				$region	= $_POST['region'];
				$agrupamiento	= $_POST['agrupamiento'];
				$sql_rel = "
				INSERT INTO area_acciones (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_acciones)
				VALUES ('',2,?,NULL,?,?,?,?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $coord, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $subd, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $region, PDO::PARAM_INT);
				$this->stmt->bindParam(5, $agrupamiento, PDO::PARAM_INT);
				$this->stmt->bindParam(6, $ultimo, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $area == '2' ) {
				$coord_t	= $_POST['coord_t'];
				$agrupamiento_t	= $_POST['agrupamiento_t'];
				$sql_rel = "
				INSERT INTO area_acciones (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_acciones)
				VALUES ('',2,?,NULL,?,NULL,NULL,?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $coord_t, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $agrupamiento_t, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $ultimo, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $area2 == '3' ) {
				$agrupamiento_cprs	= $_POST['agrupamiento_cprs'];	
				$sql_rel = "
				INSERT INTO area_acciones (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_acciones)
				VALUES ('',1,?,NULL,NULL,NULL,NULL,?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area2 , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $agrupamiento_cprs, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $ultimo, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $area == '4' ) {
				$niv1	= $_POST['niv1'];
				$niv2	= $_POST['niv2'];	
				$niv3	= $_POST['niv3'];
				$niv4	= $_POST['niv4'];
				$niv5	= $_POST['niv5'];
				$sql_rel = "
				INSERT INTO area_acciones (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_acciones)
				VALUES ('',2,?,?,?,?,?,?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $niv1, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $niv2, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $niv3, PDO::PARAM_INT);
				$this->stmt->bindParam(5, $niv4, PDO::PARAM_INT);
				$this->stmt->bindParam(6, $niv5, PDO::PARAM_INT);
				$this->stmt->bindParam(7, $ultimo, PDO::PARAM_INT);
				$this->stmt->execute();
			}


			$estado = $_POST['estado'];
			$comment = mb_strtoupper($_POST['observaciones']);

			$this->sql = "UPDATE orden_inspeccion SET 
				estatus = ? ,
				comentario = ?
				WHERE id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$estado,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$comment,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$ot_id,PDO::PARAM_INT);
			$this->stmt->execute();
			
			
			return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ LA INFORMACIÓN' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	#Extraer la clave del area 
	public function getCVE($area_id)
	{
		try {
			$cve = "";
			$this->sql = "SELECT clave FROM areas WHERE id = ?";	
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$area_id,PDO::PARAM_INT);
			$this->stmt->execute();
			$cve = $this->stmt->fetch(PDO::FETCH_OBJ)->clave;
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
			$wh = " 1=1 ";
			if ( $_SESSION['nivel'] == 'SUBDIRECTOR' ) {
				$wh .= " ";
			}else{
				$wh .= " AND persona_id = $id ";
			}
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
			WHERE $wh ORDER BY $anexgrid->columna $anexgrid->columna_orden LIMIT $anexgrid->pagina , $anexgrid->limite";
			
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$actas = $this->stmt->fetchAll(PDO::FETCH_OBJ);

			$this->sql = "SELECT 
			count(*) as total
			FROM actas AS a
			LEFT JOIN areas AS ar ON ar.id = a.area_id
			LEFT JOIN personal AS p ON p.id = a.persona_id
			LEFT JOIN municipios AS m ON m.id = a.municipio_id
			WHERE $wh";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$total = $this->stmt->fetch(PDO::FETCH_OBJ);
			$total = $total->total;


			//$total = $this->stmt->rowCount();
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
			$destino  = $_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/';

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
			unlink($_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/'.$doc_name);			
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
		/*	if ( $_FILES['file']['error'] > 0 ) {
				throw new Exception("DEBE DE SELECCIONAR UN DOCUMENTO.", 1);				
			}
			if ( $_FILES['file']['size'] > 10485760 ) {
				throw new Exception("EL DOCUMENTO EXCEDE EL TAMAÑO DE ARCHIVO ADMITIDO.", 1);				
			}
			if ( $_FILES['file']['type'] != 'application/pdf' ) {
				throw new Exception("EL FORMATO DE ARCHIVO NO ES ADMITIDO (SOLO PDF). ", 1);
			}*/
			
			#Recuperar las variables necesarias
			$doc_name = $_FILES['file']['name'];
			$doc_type = $_FILES['file']['type'];
			$doc_size = $_FILES['file']['size'];
			$destino  = $_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/';

			$name 		 	= mb_strtoupper($_POST['nombre'],'utf-8');
			$comentario		= mb_strtoupper($_POST['comentario'],'utf-8');
			$oin_id		= $_POST['oin_id'];
			$fecha		= $_POST['fecha_doc'];

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
			$this->sql = "INSERT INTO documentos_oin
			(id,oin_id,nombre,comentarios,archivo,fecha) 
			VALUES ('',?,?,?,?,?);";
			$this->stmt = $this->getPDO()->prepare($this->sql);	
			
			$this->stmt->bindParam(1,$oin_id,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$name,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$content,PDO::PARAM_LOB);
			$this->stmt->bindParam(5,$fecha,PDO::PARAM_STR);
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
			$this->sql = "SELECT p.*,c.nombre AS n_cargo FROM p_responsables AS p
			INNER JOIN cargos AS c ON c.id = p.cargo_id WHERE acta_id = ?";
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
				//$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
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

	public function getOnlyAcciones($accion)
	{
		try {
			$this->sql = "SELECT a.*, UPPER(m.nombre) AS n_municipio, o.t_orden, o.clave, ar.area
			FROM acciones_ot AS a
			INNER JOIN municipios AS m ON m.id = a.municipio_id 
            INNER JOIN orden_inspeccion AS o ON o.id = a.ot_id
            INNER JOIN area_acciones AS ar ON ar.id_acciones = a.id
			WHERE a.id = ?";
			//$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$accion,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux = array();
			//$aux['accion'] = $accion;
			$ac = array();

			foreach ($this->result as $key => $accion) {
				$aux['id'] = $accion->id;
				$aux['fecha'] = $accion->fecha;
				$aux['red_vial'] = $accion->red_vial;
				$aux['referencia_red'] = $accion->referencia_red;
				$aux['calle'] = $accion->calle;
				$aux['numero'] = $accion->numero;
				$aux['colonia'] = $accion->colonia;
				$aux['cp'] = $accion->cp;
				$aux['acciones'] = $accion->acciones;
				$aux['ot_id'] = $accion->ot_id;
				$aux['n_municipio'] = $accion->n_municipio;
				$aux['t_orden'] = $accion->t_orden;
				$aux['clave'] = $accion->clave;
				$aux['area'] = $accion->area;


		#Recuperar los participantes en el acta
			$sql_person = "
			SELECT i.id ,i.rol, CONCAT(p.nombre, ' ' , p.ap_pat,' ',p.ap_mat) AS full_name , 
			p.id AS id_persona
			FROM investigadores AS i 
			INNER JOIN personal AS p ON  p.id = i.persona_id
			WHERE i.rol = 1 AND i.acta_id = ?";
			$this->stmt = $this->getPDO()->prepare($sql_person);
			$this->stmt->bindParam(1,$accion->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$investigadores = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['investigadores'] = $investigadores;


			#Recuperar los participantes en el acta
			$sql_person = "
			SELECT i.id ,i.rol, CONCAT(p.nombre, ' ' , p.ap_pat,' ',p.ap_mat) AS full_name , 
			p.id AS id_persona
			FROM investigadores AS i 
			INNER JOIN personal AS p ON  p.id = i.persona_id
			WHERE i.rol = 2 AND i.acta_id = ?";
			$this->stmt = $this->getPDO()->prepare($sql_person);
			$this->stmt->bindParam(1,$accion->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$apoyo = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['apoyo'] = $apoyo;


		#AGREGAR A LOS QUEJOSOS
			$this->sql = "SELECT * FROM quejosos_sira WHERE acta_id = ?";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$accion->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$quejosos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['quejosos'] = $quejosos;

		#AGREGAR A LOS PRESUNTOS RESPONSABLES
			$this->sql = "SELECT p.*,c.nombre AS n_cargo FROM p_responsables AS p
			INNER JOIN cargos AS c ON c.id = p.cargo_id WHERE acta_id = ?";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$accion->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$pr = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['pr'] = $pr;

		#AGREGAR A LAS UNIDADES
			$this->sql = "SELECT u.*,s.nombre AS submarca, m.nombre AS marca 
			FROM u_implicadas_sira AS u
			INNER JOIN submarcas AS s ON s.id = u.sub_marca
			INNER JOIN marcas AS m ON m.id = s.marca_id 
			WHERE u.acta_id = ?";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$accion->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$unidades = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['unidades'] = $unidades;

		#AGREGAR A LAS ANIMALES
			$this->sql = "SELECT * FROM animales WHERE acta_id = ?";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$accion->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$animales = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['animales'] = $animales;

		#AGREGAR A LAS ARMAS
			$this->sql = "SELECT * FROM armas WHERE acta_id = ?";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$accion->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$armas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux['armas'] = $armas;

		#AGREGAR ÁREA
			$this->sql = "SELECT * FROM area_acciones WHERE id_acciones = ?";			
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$accion->id,PDO::PARAM_INT);
			$this->stmt->execute();
			$area = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
			$area = implode(', ',$area[0]);
		
			if($accion->area == 'Operativos Secretaría de Seguridad'){

				$this->sql = "SELECT aa.procedencia, aa.area, ca.nombre AS agrupamiento, cr.nombre AS region, cs.nombre AS subdireccion, cc.nombre AS coordinacion
					FROM area_acciones AS aa
					INNER JOIN catalogo_agrupamientos AS ca ON ca.id = aa.id_agrupamiento
					INNER JOIN catalogo_regiones AS cr ON cr.id = aa.id_region
					INNER JOIN catalogo_subdirecciones AS cs ON cs.id = aa.id_subdireccion
					INNER JOIN catalogo_coordinacion AS cc ON cc.id = aa.id_coordinacion
					WHERE id_acciones = ?";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->bindParam(1,$accion->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$n_area = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['n_area'] = $n_area;

			} else if($accion->area == 'Dirección de Policía de Tránsito'){

				$this->sql = "SELECT aa.procedencia, aa.area, ca.nombre AS agrupamiento, cc.nombre AS coordinacion 
				FROM area_acciones AS aa 
				INNER JOIN catalogo_transito_agrupamieno AS ca ON ca.id = aa.id_agrupamiento 
				INNER JOIN catalogo_transito_coordinacion AS cc ON cc.id = aa.id_coordinacion
				WHERE id_acciones = ?";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->bindParam(1,$accion->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$n_area = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['n_area'] = $n_area;

			} else if($accion->area == 'Dirección General de Prevención y Reinserción Social'){

				$this->sql = "SELECT aa.procedencia, aa.area, cd.nombre AS agrupamiento
				FROM area_acciones AS aa 
				INNER JOIN catalogo_dgprs AS cd ON cd.id = aa.id_agrupamiento
				WHERE id_acciones = ?";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->bindParam(1,$accion->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$n_area = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['n_area'] = $n_area;

			} else if($accion->area == 'Personal Administrativo'){

				$this->sql = "SELECT aa.procedencia, aa.area, ca.nombre AS agrupamiento, cr.nombre AS region, cs.nombre AS subdireccion, cc.nombre AS coordinacion, cd.nombre AS direccion
					FROM area_acciones AS aa
					INNER JOIN catalogo_nivel5_admini AS ca ON ca.id = aa.id_agrupamiento
					INNER JOIN catalogo_nivel2_admin AS cr ON cr.id = aa.id_region
					INNER JOIN catalogo_nivel3_admini AS cs ON cs.id = aa.id_subdireccion
					INNER JOIN catalogo_nivel2_admin AS cc ON cc.id = aa.id_coordinacion
					INNER JOIN catalogo_nivel1_admin AS cd ON cd.id = aa.id_direccion
					WHERE id_acciones = ?";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->bindParam(1,$accion->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$n_area = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['n_area'] = $n_area;
			}

				array_push($ac, $aux);
			}

			return $ac;

		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function getOnlyOrden($orden)
	{
		try {
			$this->sql = "SELECT *
			FROM orden_inspeccion WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$orden,PDO::PARAM_INT);
			$this->stmt->execute();
			$orden = $this->stmt->fetch(PDO::FETCH_OBJ);
			$aux = array();
			$aux['orden'] = $orden;
		
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
			//$this->stmt->bindParam(1, $_POST['acta'],PDO::PARAM_INT);
			$this->stmt->bindParam(1,$_POST['acciones'],PDO::PARAM_INT);
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
			//$this->stmt->bindParam(1, $_POST['acta'],PDO::PARAM_INT);
			$this->stmt->bindParam(1,$_POST['acciones'],PDO::PARAM_INT);
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
			//if ( !isset($_POST['acta']) ) {
			if ( !isset($_POST['acciones']) ) {	
				throw new Exception("NO SE RECIBIO EL ACTA QUE DESEA BUSCAR.", 1);				
			}
			//$acta_id = $_POST['acta'];
			$this->sql = "SELECT u.*,m.nombre AS n_marca ,s.nombre AS n_submarca FROM u_implicadas_sira AS u 	
			INNER JOIN submarcas AS s ON s.id = u.sub_marca 
			INNER JOIN marcas AS m ON m.id = s. marca_id
			WHERE u.acta_id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			//$this->stmt->bindParam(1, $acta_id,PDO::PARAM_INT);
			$this->stmt->bindParam(1,$_POST['acciones'],PDO::PARAM_INT);
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
			//if ( empty($_POST['acta_id']) ) {
			if ( empty($_POST['acciones_id']) ) {
				throw new Exception("SE DETECTO QUE NO HAY ACCIONES REGISTRADAS. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				//$acta_id 	= $_POST['acta_id'];				
				$acciones_id 	= $_POST['acciones_id'];
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
			$this->stmt->bindParam(8,$acciones_id,PDO::PARAM_STR);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE DIO DE ALTA UN PRESUNTO RESPONSABLE DE MANERA EXITOSA PARA EL ACTA: $acciones_id";
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
			//if ( empty($_POST['acta_id']) ) {
			if ( empty($_POST['acciones_id']) ) {
				throw new Exception("SE DETECTO QUE EL ACTA NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				//$acta_id 	= $_POST['acta_id'];
				$acciones_id 	= $_POST['acciones_id'];
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
			$this->stmt->bindParam(1,$acciones_id,PDO::PARAM_INT);
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
			$desc = "SE DIO DE ALTA UN VEHÍCULO IMPLICADO DE MANERA EXITOSA PARA EL ACTA: $acciones_id";
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
			$desc = "SE HA ELIMINDADO UN QUEJOSO CON ID: ".$e;
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,4,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE ELIMINÓ UN QUEJOSO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveQuejoso()
	{
		try {
			session_start();
			//if ( empty($_POST['acta_id']) ) {
			if ( empty($_POST['acciones_id']) ) {	
				throw new Exception("SE DETECTO QUE EL ACTA NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				//$acta_id 	= $_POST['acta_id'];
				$acciones_id 	= $_POST['acciones_id'];
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
			$this->stmt->bindParam(5,$phone,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$mail,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$dir,PDO::PARAM_STR);
			$this->stmt->bindParam(8,$acciones_id,PDO::PARAM_STR);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE DIO DE ALTA UN QUEJOSO DE MANERA EXITOSA PARA EL ACTA: $acciones_id";
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
			//$acta 		= $_POST['acta'];
			$acciones 		= $_POST['acciones'];
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
			$desc = "SE A ELIMINDADO UN VEHÍCULO CON ID: ".$e." PARA EL ACTA CON ID: ".$acciones;
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
			if ( empty($_POST['acciones_id']) ) {
			//if ( empty($_POST['acta_id']) ) {
				throw new Exception("SE DETECTO QUE EL ACTA NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				//$acta_id 	= $_POST['acta_id'];
				$acciones_id 	= $_POST['acciones_id'];
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
			$this->stmt->bindParam(8,$acciones_id,PDO::PARAM_INT);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE DIO DE ALTA UN ANIMAL DE MANERA EXITOSA PARA EL ACTA: $acciones_id";
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,2,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ UN ANIMAL DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function getAnimales()
	{
		try {
			//if ( !isset($_POST['acta']) ) {
			if ( !isset($_POST['acciones']) ) {	
				throw new Exception("NO SE RECIBIO EL ACTA QUE DESEA BUSCAR.", 1);			
			}
			//$acta_id = $_POST['acta'];
			
			$this->sql = "SELECT * FROM animales WHERE acta_id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$_POST['acciones'],PDO::PARAM_INT);
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
			//$acta 		= $_POST['acta'];
			$acciones 		= $_POST['acciones'];
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
			$desc = "SE HA ELIMINDADO UN ANIMAL CON ID: ".$e." PARA EL ACTA CON ID: ".$acciones;
			$sql_pista = "INSERT INTO 
			pista_auditoria (id, descripcion,person_id,tipo, sistema) 
			VALUES 
			('',?,?,4,2);";
			$stmt = $this->pdo->prepare($sql_pista);
			$stmt->bindParam(1,$desc,PDO::PARAM_STR);
			$stmt->bindParam(2,$logger,PDO::PARAM_INT);
			$stmt->execute();
			return json_encode( array('status'=>'success','message'=> 'SE ELIMINÓ UN ANIMAL IMPLICADO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveArma()
	{
		try {
			session_start();
			//if ( empty($_POST['acta_id']) ) {
			if ( empty($_POST['acciones_id']) ) {
				throw new Exception("SE DETECTO QUE EL ACTA NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				//$acta_id 	= $_POST['acta_id'];
				$acciones_id 	= $_POST['acciones_id'];
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
			$this->stmt->bindParam(5,$acciones_id,PDO::PARAM_INT);
			$this->stmt->execute();
			#Insertar la pista de auditoria 
			#1. Inicio de sesion
			#2. Alta 
			#3. Modificacion
			#4. Eliminacion 
			$logger = $_SESSION['id'];
			$desc = "SE DIO DE ALTA UN QUEJOSO DE MANERA EXITOSA PARA EL ACTA: $acciones_id";
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
			//if ( !isset($_POST['acta']) ) {
			if ( !isset($_POST['acciones']) ) {	
				throw new Exception("NO SE RECIBIO EL ACTA QUE DESEA BUSCAR.", 1);				
			}
			//$acta_id = $_POST['acta'];
			$this->sql = "SELECT * FROM armas WHERE acta_id = ? ";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$_POST['acciones'],PDO::PARAM_INT);
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
			//$acta 		= $_POST['acta'];
			$acciones 		= $_POST['acciones'];
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
			$desc = "SE A ELIMINDADO UN ARMA CON ID: ".$e." PARA EL ACTA CON ID: ".$acciones;
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
			//$wh = " and a.fecha > '2019-06-30'";
			$wh = " ";
			if ( !empty($_POST['f_ini']) AND !empty($_POST['f_fin']) ) {
				$f_ini = $_POST['f_ini'] ;
				$f_fin = $_POST['f_fin'] ;
				$wh .= "  AND a.fecha BETWEEN '$f_ini' AND '$f_fin' ";
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
				$wh = " AND a.comentarios LIKE '%$comentarios%' ";
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
			#WHERE fecha > '2019-06-30'
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
	public function getCedulaOT($id)
	{
		try {
			#Generar la conexion nueva

			$this->sql = "SELECT  oin.id, oin.oficio_id, oin.clave, oin.despachador_id, oin.estatus, oin.f_creacion, oin.comentario, oin.t_orden/*of.id_generado AS of_id, of.no_oficio, of.fecha_oficio*/, oc.motivo, oc.created_at AS f_cancela
			FROM orden_inspeccion AS oin
			#LEFT JOIN oficios_generados AS of ON of.id_generado = oin.oficio_id
			LEFT JOIN oin_cancelados AS oc ON oc.oin_id = oin.id
			WHERE oin.id = ?";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux = array();
			$ot = array();
			foreach ($this->result as $key => $orden) {
				$aux['id'] = $orden->id;
				$aux['clave'] = $orden->clave;
				$aux['estatus'] = $orden->estatus;
				$aux['comentario'] = $orden->comentario;
				$aux['oficio_id'] = $orden->oficio_id;
				//$aux['of_id'] = $orden->oficio_id;
				//$aux['no_oficio'] = $orden->no_oficio;
				//$aux['fecha_oficio'] = $orden->fecha_oficio;
				$aux['motivo'] = $orden->motivo;
				$aux['f_cancela'] = $orden->f_cancela;
				
				$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$this->sql = "SELECT  po.*, p.nom_completo AS nom_completo
				FROM participantes_oin AS po
				INNER JOIN personal AS p ON p.id_person = po.person_id
				WHERE po.oin_id = ?";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$participantes = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['participantes'] = $participantes;


				//$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_oficios = "SELECT of.id_generado AS of_id, of.no_oficio, of.fecha_oficio, of.asunto FROM oficios_generados AS of WHERE of.id_generado = ?";
				$this->stmt = $this->pdo->prepare($sql_oficios);
				$this->stmt->bindParam(1,$orden->oficio_id,PDO::PARAM_INT);
				$this->stmt->execute();
				$oficios = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['oficios'] = $oficios;


				if (!is_null( $orden->oficio_id)) {
					$this->sql = "SELECT  *
					FROM oficios_generados_referencia 
					WHERE id_generado = ?";
					$this->stmt = $this->getPDO()->prepare($this->sql);
					$this->stmt->bindParam(1,$orden->oficio_id,PDO::PARAM_INT);
					$this->stmt->execute();
					if ( $this->stmt->rowCount() > 0 ) {
						$ref = $this->stmt->fetch(PDO::FETCH_OBJ);
						$aux['ref'] = $ref;
						$cve_exp = "%".$ref->referencia."%";
					}else{
						$cve_exp = NULL;
					}					

				}


				$this->sql = "SELECT  ts.*, descripcion_solicitud
				FROM oficios_generados_tiposolicitud AS ts
				INNER JOIN catalogo_tiposolicitud AS ct ON ct.id_tiposolicitud = ts.id_tiposol
				WHERE ts.id_generado = ?";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->bindParam(1,$orden->oficio_id,PDO::PARAM_INT);
				$this->stmt->execute();
				$tipo_sol = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['tipo_sol'] = $tipo_sol;
				
				#LAS ACTAS
				$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$this->sql = "SELECT id, clave FROM actas WHERE oin_id = ?";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->bindParam(1,$orden->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$actas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['actas'] = $actas;
				
				#LAS ACCIONES
				$this->sql = "SELECT id, ot_id FROM acciones_ot WHERE ot_id = ?";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->bindParam(1,$orden->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$acciones = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['acciones'] = $acciones;


				#El expediente 
				if( !is_null($cve_exp) ){
					$this->sql = "SELECT *
					FROM quejas 
					WHERE cve_exp like ?
					";
					$this->stmt = $this->getPDO()->prepare($this->sql);
					$this->stmt->bindParam(1,$cve_exp,PDO::PARAM_STR);
					$this->stmt->execute();
					$queja = $this->stmt->fetch(PDO::FETCH_OBJ);
					$aux['queja']  = $queja ;
				}else{
					$aux['queja'] = array();
				}


				#AREA DE LAS RECOMENDACIONES
				$sql_area_r = "SELECT area, 
				CASE 
					WHEN area = 'Operativos Secretaría de Seguridad' THEN (SELECT c.nombre AS nombre FROM area_recomendaciones AS a INNER JOIN catalogo_agrupamientos AS c ON c.id = a.id_agrupamiento WHERE id_ot = ?)
					WHEN area = 'Dirección de Policía de Tránsito' THEN (SELECT c.nombre AS nombre FROM area_recomendaciones AS a INNER JOIN catalogo_transito_agrupamieno AS c ON c.id = a.id_agrupamiento WHERE id_ot = ?)
					WHEN area = 'Dirección General de Prevención y Reinserción Social' THEN (SELECT c.nombre AS nombre FROM area_recomendaciones AS a INNER JOIN catalogo_dgprs AS c ON c.id = a.id_agrupamiento WHERE id_ot = ?)
					WHEN area = 'Personal Administrativo' THEN (SELECT c.nombre AS nombre FROM area_recomendaciones AS a INNER JOIN catalogo_nivel5_admini AS c ON c.id = a.id_agrupamiento WHERE id_ot = ?)
				ELSE 0
				END AS nombre
				FROM area_recomendaciones WHERE id_ot = ?";
				$this->stmt = $this->getPDO()->prepare($sql_area_r);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$id,PDO::PARAM_INT);
				$this->stmt->bindParam(3,$id,PDO::PARAM_INT);
				$this->stmt->bindParam(4,$id,PDO::PARAM_INT);
				$this->stmt->bindParam(5,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$nom_area_r = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['nom_area_r'] = $nom_area_r;

				#EXPEDIENTE RELACIONADO
				$sql_expediente_rel = "SELECT cve_exp AS exp, t_asunto, e.comentarios, e.oficio FROM quejas AS q 
				INNER JOIN relacion_ot_expediente AS e ON q.id = e.expediente_id
				WHERE e.orden_id = $id";
				$this->stmt = $this->getPDO()->prepare($sql_expediente_rel);
				$this->stmt->execute();
				$expediente_ot = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['expediente_ot'] = $expediente_ot;

				$of = array();
					foreach ($expediente_ot as $key => $s) {
						array_push($of, $s ->oficio);
					}
				$of = implode(',',$of);

				#Número de oficio del seguimiento
				$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_ofi_segui = "SELECT no_oficio, fecha_oficio FROM oficios_generados 
				WHERE id_generado = $of";
				$this->stmt = $this->getPDO()->prepare($sql_ofi_segui);
				$this->stmt->execute();
				$ofi_expediente = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['ofi_expediente'] = $ofi_expediente;



				#ACTAS ADMINSTRATIVAS
				$sql_adm = "SELECT * FROM acta_admin WHERE detipo = 'ORDEN' AND id_tipo = ?";
				$this->stmt = $this->pdo->prepare($sql_adm);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$acta_admin = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['acta_admin'] = $acta_admin;

				$a = array();
					foreach ($acta_admin as $key => $ac) {
						array_push($a, $ac ->id);
					}
				$a = implode(',',$a);

				$aofi = array();
					foreach ($acta_admin as $key => $ac) {
						array_push($aofi, $ac ->oficio);
					}
				$aofi = implode(',',$aofi);

				#EXPEDIENTE DEL ACTA ADMINISTRATIVA
				$sql_expediente = "SELECT cve_exp AS exp FROM quejas AS q 
				WHERE acta_admin = $a";
				$this->stmt = $this->getPDO()->prepare($sql_expediente);
				$this->stmt->execute();
				$expedientes = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['expedientes'] = $expedientes;

				#Buscar las presuntas conductas de cada expediente
				$sql_conductas = "SELECT pc.id AS id_presunta, cc.id,cc.nombre,l.nombre AS n_ley  FROM p_conductas_admin AS pc
				INNER JOIN catalogo_conductas AS cc ON cc.id = pc.conducta_id
	            INNER JOIN leyes AS l ON l.id = cc.ley_id
				WHERE pc.acta_id = $a";
				$this->stmt = $this->pdo->prepare($sql_conductas);
				//$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
				$this->stmt = $this->getPDO()->prepare($sql_conductas);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ) {
					$conductas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
					$aux['conductas'] = $conductas;
				}else{
					$aux['conductas'] = array();
				}

				$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_ofi_ac = "SELECT no_oficio FROM oficios_generados 
				WHERE id_generado = $aofi";
				$this->stmt = $this->getPDO()->prepare($sql_ofi_ac);
				$this->stmt->execute();
				$ofi_acta = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['ofi_acta'] = $ofi_acta;

				$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));				
				#Agregar archivos 
				$sql_files = "SELECT *
				FROM documentos_obs AS d 
				WHERE d.ot_id = ?";
				$this->stmt = $this->pdo->prepare($sql_files);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$archivos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['archivos'] = $archivos;

				#Agregar archivos 
				$sql_files_a = "SELECT *
				FROM documentos_acta_admin WHERE acta_id = $a";
				$this->stmt = $this->pdo->prepare($sql_files_a);
				$this->stmt->execute();
				$archivos_ac = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['archivos_ac'] = $archivos_ac;

				#Agregar archivos 
				$sql_files_o = "SELECT *
				FROM documentos_oin 
				WHERE oin_id = ?";
				$this->stmt = $this->pdo->prepare($sql_files_o);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$archivo_oin = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['archivo_oin'] = $archivo_oin;

					#AREA DEL ACTA ADMINISTRATIVA
				$sql_area = "SELECT area, 
				CASE 
					WHEN area = 'Operativos Secretaría de Seguridad' THEN (SELECT c.nombre AS nombre FROM area_acta_admin AS a INNER JOIN catalogo_agrupamientos AS c ON c.id = a.id_agrupamiento WHERE id_acta = $a)
					WHEN area = 'Dirección de Policía de Tránsito' THEN (SELECT c.nombre AS nombre FROM area_acta_admin AS a INNER JOIN catalogo_transito_agrupamieno AS c ON c.id = a.id_agrupamiento WHERE id_acta = $a)
					WHEN area = 'Dirección General de Prevención y Reinserción Social' THEN (SELECT c.nombre AS nombre FROM area_acta_admin AS a INNER JOIN catalogo_dgprs AS c ON c.id = a.id_agrupamiento WHERE id_acta = $a)
					WHEN area = 'Personal Administrativo' THEN (SELECT c.nombre AS nombre FROM area_acta_admin AS a INNER JOIN catalogo_nivel5_admini AS c ON c.id = a.id_agrupamiento WHERE id_acta = $a)
				ELSE 0
				END AS nombre
				FROM area_acta_admin WHERE id_acta = $a";
				$this->stmt = $this->getPDO()->prepare($sql_area);
				$this->stmt->execute();
				$nom_area = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['nom_area'] = $nom_area;

				#Agregar recordatorios
				$sql_recor = "SELECT f_acuse, f_recordatorio,  no_oficio, destinatario, asunto, f_limite, observaciones FROM recordatorio_obs WHERE ot_id = ?";
				$this->stmt = $this->pdo->prepare($sql_recor);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$recordatorios = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['recordatorios'] = $recordatorios;

				#Agregar seguimiento de respuestas
				$sql_segui = "SELECT fecha, oficio, remitente, cargo, asunto, recibe, estatus, observaciones FROM info_seguimiento WHERE id_ot = ?";
				$this->stmt = $this->pdo->prepare($sql_segui);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$seguimiento = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['seguimiento'] = $seguimiento;

				$aseg = array();
					foreach ($seguimiento as $key => $s) {
						array_push($aseg, $s ->oficio);
					}
				$aseg = implode(',',$aseg);

				$arecibe = array();
					foreach ($seguimiento as $key => $s) {
						array_push($arecibe, $s ->recibe);
					}
				$arecibe = implode(',',$arecibe);

				#Número de oficio del seguimiento
				$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_ofi_segui = "SELECT oficio_respuesta AS ofi_resp FROM oficios_generadosrespuesta WHERE id_respuesta = $aseg";
				//$this->stmt = $this->pdo->prepare($sql_oficios);
				$this->stmt = $this->getPDO()->prepare($sql_ofi_segui);
				//$this->stmt->bindParam(1,$admin->acta,PDO::PARAM_INT);
				$this->stmt->execute();
				$ofi_seguimiento = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['ofi_seguimiento'] = $ofi_seguimiento;

				$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_rec_segui = "SELECT CONCAT(nombre,' ',ap_pat,' ',ap_mat) AS nom_completo FROM personal WHERE id = $arecibe";
				//$this->stmt = $this->pdo->prepare($sql_oficios);
				$this->stmt = $this->getPDO()->prepare($sql_rec_segui);
				//$this->stmt->bindParam(1,$admin->acta,PDO::PARAM_INT);
				$this->stmt->execute();
				$recibe_seguimiento = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['recibe_seguimiento'] = $recibe_seguimiento;


				#Agregar recordatorios
				$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_irreg = "SELECT o.id_ot, o.observacion, r.recomendacion,r.atendido, r.observaciones FROM  observaciones AS o LEFT JOIN recomendaciones AS r ON o.id = r.id_observa WHERE o.id_ot= ?";
				$this->stmt = $this->pdo->prepare($sql_irreg);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$irregularidades = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['irregularidades'] = $irregularidades;

				#AREA DEL REGISTRO DE RECOMENDACIONES
				$sql_area = "SELECT area FROM area_recomendaciones WHERE id_ot = ?";
				$this->stmt = $this->pdo->prepare($sql_area);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$area = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['area'] = $area;

				/*$b = array();
					foreach ($solicitud as $key => $bb) {
						array_push($b, $bb ->area);
					}
				$b = implode(',',$b);


				if ($b == 'Dirección General de Prevención y Reinserción Social'){
				$sql_area_sol = "SELECT c.nombre AS nombre FROM area_recomendaciones AS a 
				INNER JOIN catalogo_agrupamientos AS c ON c.id = a.id_agrupamiento
				WHERE id_ot = $id";
				$this->stmt = $this->getPDO()->prepare($sql_area_sol);
				$this->stmt->execute();
				$nom_area = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['nom_area'] = $nom_area;				
				}*/


				#REGISTRO DE ENVÍO DE RECOMENDACIONES
				$sql_sol = "SELECT * FROM datos_observaciones WHERE id_ot = ?";
				$this->stmt = $this->pdo->prepare($sql_sol);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$solicitud = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['solicitud'] = $solicitud;

				$sol = array();
					foreach ($solicitud as $key => $soli) {
						array_push($sol, $soli ->oficio);
					}
				$sol = implode(',',$sol);

				#OFICIO DEL REGISTRO DE ENVÍO DE RECOMENDACIONES
				$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_ofi_sol = "SELECT no_oficio FROM oficios_generados 
				WHERE id_generado = $sol";
				$this->stmt = $this->getPDO()->prepare($sql_ofi_sol);
				$this->stmt->execute();
				$ofi_sol = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['ofi_sol'] = $ofi_sol;



				array_push($ot, $aux);
			}

			return $ot;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	public function saveCancelaOT()
	{
		try {
			#vERIFICAR QUE EXITA UNA CANCELACION PREVIA
			$ot = $_POST['ot_id'];
			$motivo = mb_strtoupper($_POST['motivo'],'utf-8');
			//$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
			$this->sql = "SELECT * FROM oin_cancelados WHERE oin_id = ?;";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$ot,PDO::PARAM_INT);
			$this->stmt->execute();
			if ( $this->stmt->rowCount() > 0 ) {
				throw new Exception("LA ORDEN DE TRABAJO YA A SIDO CANCELADA.", 1);				
			}
			$this->sql = "INSERT INTO oin_cancelados (id,oin_id, motivo) VALUES ('',?,?);";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$ot,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$motivo,PDO::PARAM_STR);
			$this->stmt->execute();
			$this->sql = "UPDATE orden_inspeccion  SET estatus = 5 WHERE id = ? ";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$ot,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = array('status' => 'success', 'message'=>'CANCELACIÓN CREADA DE MANERA EXITOSA.' );;
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function saveCenso()
	{
		try {

			
			$tipo_envio	= $_POST['question'];
			$fecha_envio	= $_POST['f_envio'];						
			$fecha_acuse	= $_POST['f_acuse'];
			$oficio = ( isset($_POST['oficio_id']) ) ? $_POST['oficio_id'] : NULL ;			
			$observaciones	= mb_strtoupper($_POST['observa'],'utf-8');			
			$area	= $_POST['question_a'];	
			$area2	= $_POST['question_a2'];
			$fecha_limite	= $_POST['f_limite'];

			if($tipo_envio	== 1){
				$destinatario	= mb_strtoupper($_POST['destinatario_ofi'],'utf-8');
				$asunto	= mb_strtoupper($_POST['asunto_ofi'],'utf-8');
			} else if ($tipo_envio	== 2) {
				$destinatario	= mb_strtoupper($_POST['destinatario'],'utf-8');
				$asunto	= mb_strtoupper($_POST['asunto'],'utf-8');
			}			
			

			$this->sql = "INSERT INTO censo (
				id,
				tipo_envio, 
				f_envio,
				no_oficio, 
				f_acuse, 
				destinatario,
				observaciones,
				asunto,
				f_limite
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
			)";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$tipo_envio,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$fecha_envio,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$oficio,PDO::PARAM_INT);
			$this->stmt->bindParam(4,$fecha_acuse,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$destinatario,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$observaciones,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$asunto,PDO::PARAM_STR);
			$this->stmt->bindParam(8,$fecha_limite,PDO::PARAM_STR);
			$this->stmt->execute();
			$ultimo = $this->pdo->lastInsertId();

			if ( $area == '1' ) {
				$coord	= $_POST['coord'];	
				$subd	= $_POST['subd'];
				$region	= $_POST['region'];
				$agrupamiento	= $_POST['agrupamiento'];
				$sql_rel = "
				INSERT INTO area_censo (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_censo)
				VALUES ('',2,?,NULL,?,?,?,?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $coord, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $subd, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $region, PDO::PARAM_INT);
				$this->stmt->bindParam(5, $agrupamiento, PDO::PARAM_INT);
				$this->stmt->bindParam(6, $ultimo, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $area == '2' ) {
				$coord_t	= $_POST['coord_t'];
				$agrupamiento_t	= $_POST['agrupamiento_t'];
				$sql_rel = "
				INSERT INTO area_censo (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_censo)
				VALUES ('',2,?,NULL,?,NULL,NULL,?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $coord_t, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $agrupamiento_t, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $ultimo, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $area2 == '3' ) {
				$agrupamiento_cprs	= $_POST['agrupamiento_cprs'];	
				$sql_rel = "
				INSERT INTO area_censo (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_censo)
				VALUES ('',1,?,NULL,NULL,NULL,NULL,?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area2 , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $agrupamiento_cprs, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $ultimo, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $area == '4' ) {
				$niv1	= $_POST['niv1'];
				$niv2	= $_POST['niv2'];	
				$niv3	= $_POST['niv3'];
				$niv4	= $_POST['niv4'];
				$niv5	= $_POST['niv5'];
				$sql_rel = "
				INSERT INTO area_censo (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_censo)
				VALUES ('',2,?,?,?,?,?,?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $niv1, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $niv2, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $niv3, PDO::PARAM_INT);
				$this->stmt->bindParam(5, $niv4, PDO::PARAM_INT);
				$this->stmt->bindParam(6, $niv5, PDO::PARAM_INT);
				$this->stmt->bindParam(7, $ultimo, PDO::PARAM_INT);
				$this->stmt->execute();
			}
			
			#INSERTAR LAS PREGUNTAS 
			$cuenta_p = count($_POST['censo_preguntas']); 
			$sql_insert_pre = "INSERT INTO censo_preguntas 
			(id,censo_id,pregunta_id) 
			VALUES 
			( '',?,? )
			";
			for ($i=0; $i < $cuenta_p; $i++) { 
				$this->stmt = $this->pdo->prepare($sql_insert_pre);
				$this->stmt->bindParam(1, $ultimo, PDO::PARAM_INT);
				$this->stmt->bindParam(2, $_POST['censo_preguntas'][$i], PDO::PARAM_INT);
				$this->stmt->execute();
			}	

			
			return json_encode( array('status'=>'success','message'=> 'SE HAN REGISTRADO LOS DATOS DEL CENSO.' ));
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function getListCenso()
	{
		try {
			
			$censos = array();
			$anexgrid = new AnexGrid();
			$wh = " 1=1 ";
			
			foreach ($anexgrid->filtros as $filter) {
				if ( $filter['columna'] == 'c.asunto' || $filter['columna'] == 'nombre'|| $filter['columna'] == 'observaciones' || $filter['columna'] == 'destinatario') {
					$wh .= " AND ".$filter['columna'] ." LIKE '%".$filter['valor']."%'";
					
				} else if ($filter['columna'] == 'ofi') {

					$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
					$sql_ofi = "SELECT id_generado FROM oficios_generados 
					WHERE no_oficio LIKE '%".$filter['valor']."%'";
					$this->stmt = $this->pdo->prepare($sql_ofi);
					//$this->stmt->bindParam(1,$cen->no_oficio,PDO::PARAM_INT);
					$this->stmt->execute();
					$ofi = $this->stmt->fetchAll(PDO::FETCH_OBJ);
					$aux['ofi'] = $ofi;

					$a = array();
						foreach ($ofi as $key => $ac) {
							array_push($a, $ac ->id_generado);
						}
					$a = implode(',',$a);

					$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
					$wh .= " AND c.no_oficio IN ($a)";

				} else if ($filter['columna'] == 'ofi') {

					$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
					$sql_ofi = "SELECT id_generado FROM oficios_generados 
					WHERE no_oficio LIKE '%".$filter['valor']."%'";
					$this->stmt = $this->pdo->prepare($sql_ofi);
					//$this->stmt->bindParam(1,$cen->no_oficio,PDO::PARAM_INT);
					$this->stmt->execute();
					$ofi = $this->stmt->fetchAll(PDO::FETCH_OBJ);
					$aux['ofi'] = $ofi;

					$a = array();
						foreach ($ofi as $key => $ac) {
							array_push($a, $ac ->id_generado);
						}
					$a = implode(',',$a);

					$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
					$wh .= " AND c.no_oficio IN ($a)";

				} else{
										
					$wh .= " AND ".$filter['columna'] ." = ".$filter['valor'];
				}
				
			}
			//$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
			$this->sql = "SELECT c.id, c.tipo_envio, c.f_envio, c.no_oficio, c.f_acuse, c.destinatario, c.observaciones, c.asunto, ac.area, ac.id_coordinacion,ac.id_subdireccion, ac.id_region, ac.id_agrupamiento, 
			DATEDIFF(DATE(NOW()),DATE(c.f_acuse) ) AS dias, 
			DATEDIFF(DATE(c.f_limite),DATE(c.f_acuse) ) AS limite, 
			resp.f_oficio 
			FROM censo AS c
				INNER JOIN area_censo AS ac ON ac.id_censo = c.id 
				INNER JOIN catalogo_agrupamientos AS ag ON ag.id = ac.id_agrupamiento
                LEFT JOIN info_respuesta AS resp ON resp.id_censo = c.id
				WHERE $wh ORDER BY $anexgrid->columna $anexgrid->columna_orden LIMIT $anexgrid->pagina , $anexgrid->limite";

			//echo($this->sql);
			//exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);


			//$censos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$total = $this->stmt->rowCount();

			$aux = array();
			foreach ($this->result as $key => $cen) {

				$aux['id'] = $cen->id;
				$aux['tipo_envio'] = $cen->tipo_envio;
				$aux['f_envio'] = $cen->f_envio;
				$aux['no_oficio'] = $cen->no_oficio;
				$aux['f_acuse'] = $cen->f_acuse;
				$aux['destinatario'] = $cen->destinatario;
				$aux['observaciones'] = $cen->observaciones;
				$aux['asunto'] = $cen->asunto;
				$aux['area'] = $cen->area;
				$aux['id_agrupamiento'] = $cen->id_agrupamiento;
				$aux['dias'] = $cen->dias;
				$aux['limite'] = $cen->limite;
				$aux['f_oficio'] = $cen->f_oficio;



				$sql_max = "SELECT MAX(f_acuse) AS mas 
				FROM recordatorio AS recor WHERE id_censo = ?";
				$this->stmt = $this->getPDO()->prepare($sql_max);
				$this->stmt->bindParam(1,$cen->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$max = $this->stmt->fetch(PDO::FETCH_OBJ);				
				$aux['mas'] = $max->mas;
 
				
				$sql_reco = "SELECT MAX(f_acuse) AS recordatorio, 
				DATEDIFF(DATE(NOW()),DATE((SELECT MAX(f_acuse) FROM recordatorio WHERE id_censo = ?))) AS diasrecor,
				DATEDIFF(DATE((SELECT MAX(f_limite) FROM recordatorio WHERE id_censo = ?)),DATE((SELECT MAX(f_acuse) FROM recordatorio WHERE id_censo = ?))) AS limiterecor 
				FROM recordatorio AS recor WHERE id_censo = ?";
				//echo($sql_reco);
				//exit;
				
				$this->stmt = $this->getPDO()->prepare($sql_reco);
				$this->stmt->bindParam(1,$cen->id,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$cen->id,PDO::PARAM_INT);
				$this->stmt->bindParam(3,$cen->id,PDO::PARAM_INT);
				$this->stmt->bindParam(4,$cen->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$recordatorio = $this->stmt->fetch(PDO::FETCH_OBJ);
				
				$aux['recordatorio'] = $recordatorio->recordatorio;
				$aux['diasrecor'] = $recordatorio->diasrecor;
				$aux['limiterecor'] = $recordatorio->limiterecor;
	

				$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_oficios = "SELECT og.no_oficio AS ofi FROM oficios_generados AS og 
				WHERE og.id_generado = ?";
				$this->stmt = $this->pdo->prepare($sql_oficios);
				$this->stmt->bindParam(1,$cen->no_oficio,PDO::PARAM_INT);
				$this->stmt->execute();
				$oficios = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['oficios'] = $oficios;


				$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
					$sql_acta = "SELECT id FROM acta_admin WHERE detipo = 1 and id_tipo = ?";
				$this->stmt = $this->pdo->prepare($sql_acta);
				$this->stmt->bindParam(1,$cen->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$actas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['actas'] = $actas;

				$a = array();
					foreach ($actas as $key => $ac) {
						array_push($a, $ac ->id);
					}
				$a = implode(',',$a);

				/*$sql_expediente = "SELECT cve_exp AS exp FROM quejas AS q 
				WHERE acta_admin = $a";
				//$this->stmt = $this->pdo->prepare($sql_oficios);
				$this->stmt = $this->getPDO()->prepare($sql_expediente);
				//$this->stmt->bindParam(1,$adm->acta,PDO::PARAM_INT);
				$this->stmt->execute();
				$expedientes = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['expedientes'] = $expedientes;*/

				$sql_expediente = "SELECT cve_exp AS exp FROM quejas
				INNER JOIN relacion_censo_expediente AS e ON e.expediente_id = quejas.id
				WHERE e.censo_id = ?";
				$this->stmt = $this->pdo->prepare($sql_expediente);
				$this->stmt->bindParam(1,$cen->id,PDO::PARAM_INT);
				$this->stmt->execute();				
				$expedientes = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['expedientes'] = $expedientes;



				if ($cen->area == 'Dirección General de Prevención y Reinserción Social'){

				$sql_area = "SELECT nombre FROM catalogo_dgprs WHERE id = ?";
				$this->stmt = $this->pdo->prepare($sql_area);
				$this->stmt->bindParam(1,$cen->id_agrupamiento,PDO::PARAM_INT);
				$this->stmt->execute();
				$areas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['areas'] = $areas;

				} else if ($cen->area == 'Dirección de Policía de Tránsito'){
					
				$sql_area = "SELECT nombre FROM catalogo_transito_agrupamieno WHERE id = ?";
				$this->stmt = $this->pdo->prepare($sql_area);
				$this->stmt->bindParam(1,$cen->id_agrupamiento,PDO::PARAM_INT);
				$this->stmt->execute();
				$areas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['areas'] = $areas;

				} else if ($cen->area == 'Operativos Secretaría de Seguridad'){
					
				$sql_area = "SELECT nombre FROM catalogo_agrupamientos WHERE id = ?";
				$this->stmt = $this->pdo->prepare($sql_area);
				$this->stmt->bindParam(1,$cen->id_agrupamiento,PDO::PARAM_INT);
				$this->stmt->execute();
				$areas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['areas'] = $areas;

				} else if ($cen->area == 'Personal Administrativo'){
					
				$sql_area = "SELECT nombre FROM catalogo_nivel5_admini WHERE id = ?";
				$this->stmt = $this->pdo->prepare($sql_area);
				$this->stmt->bindParam(1,$cen->id_agrupamiento,PDO::PARAM_INT);
				$this->stmt->execute();
				$areas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['areas'] = $areas;

				}
			
				
				array_push($censos, $aux);
			}
			
			return $anexgrid->responde($censos,$total);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
 	

public function saveRecordatorio()
	{
		try {

			if ( empty($_POST['censo_id']) ) {
				throw new Exception("SE DETECTO QUE EL CENSO NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$censo_id 	= $_POST['censo_id'];
			}
			
			#Las variables
		/*	if ( !isset($_POST['f_oficio']) AND empty($_POST['f_oficio']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO.", 1);
			}
			if ( !isset($_POST['f_acuse']) OR empty($_POST['f_acuse']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO.", 1);
			}
			if ( !isset($_POST['oficio']) OR empty($_POST['oficio']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO.", 1);
			}
			if ( !isset($_POST['destinatario']) OR empty($_POST['destinatario']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO.", 1);
			}
			if ( !isset($_POST['area']) OR empty($_POST['area']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO.", 1);
			}
			if ( !isset($_POST['observa']) OR empty($_POST['observa']) ) {
				throw new Exception("NO EXISTE UN DATO CORRECTO.", 1);
			}	*/		
			//$censo = $_POST['censo'];
			$tipo_envio	= $_POST['question_r'];
			$fecha_envio	= $_POST['f_envio_r'];
			$oficio = ( isset($_POST['oficio_id']) ) ? $_POST['oficio_id'] : NULL ;			
			$fecha_acuse	= $_POST['f_acuse_r'];	
			$observaciones	= mb_strtoupper($_POST['observa_r'],'utf-8');
			$fecha_limite	= $_POST['f_limite'];
			

			if($tipo_envio	== 1){
				$destinatario	= mb_strtoupper($_POST['destinatario_ofi'],'utf-8');
				$asunto	= mb_strtoupper($_POST['asunto_ofi'],'utf-8');
			} else if ($tipo_envio	== 2) {
				$destinatario	= mb_strtoupper($_POST['destinatario_r'],'utf-8');
				$asunto	= mb_strtoupper($_POST['asunto_r'],'utf-8');
			}	

			$this->sql = "INSERT INTO recordatorio (
				id,
				tipo_envio, 
				f_recordatorio,
				no_oficio, 
				f_acuse, 
				destinatario,
				observaciones,
				asunto,
				f_limite,
				id_censo
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
			)";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$tipo_envio,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$fecha_envio,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$oficio,PDO::PARAM_INT);
			$this->stmt->bindParam(4,$fecha_acuse,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$destinatario,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$observaciones,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$asunto,PDO::PARAM_STR);
			$this->stmt->bindParam(8,$fecha_limite,PDO::PARAM_STR);
			$this->stmt->bindParam(9,$censo_id,PDO::PARAM_INT);
			$this->stmt->execute();
						
			return json_encode( array('status'=>'success','message'=> 'SE HA REGISTRADO UN RECORATORIO' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function saveRespuesta()
	{
		try {

			if ( empty($_POST['censo_id']) ) {
				throw new Exception("SE DETECTO QUE EL CENSO NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$censo_id 	= $_POST['censo_id'];

				$this->sql = "SELECT id FROM info_respuesta WHERE id_censo = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$censo_id,PDO::PARAM_STR);
				$this->stmt->execute();
				$datos = $this->stmt->fetchAll(PDO::FETCH_OBJ);

			}


			if ( empty($datos) ) {
		
				if ( !isset($_POST['question_res']) AND empty($_POST['question_res']) ) {
					throw new Exception("NO EXISTE UN DATO CORRECTO.", 1);
				}

				$tipo_envio	= $_POST['question_res'];
				$fecha_oficio	= $_POST['f_recepcion'];
				$oficio = ( isset($_POST['oficio_id']) ) ? $_POST['oficio_id'] : NULL ;	
				$remitente	= mb_strtoupper($_POST['remitente'],'utf-8');
				$cargo	= mb_strtoupper($_POST['cargo'],'utf-8');
				$asunto	= mb_strtoupper($_POST['asunto'],'utf-8');
				$fecha_desde	= $_POST['f_desde'];
				$fecha_hasta	= $_POST['f_hasta'];

				$this->sql = "INSERT INTO info_respuesta (
					id,
					tipo_envio, 
					f_oficio,
					no_oficio,
					remitente,
					cargo,
					asunto,
					recibe,
					fecha_desde,
					fecha_hasta,
					id_censo
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
					?
				)";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$tipo_envio,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$fecha_oficio,PDO::PARAM_STR);
				$this->stmt->bindParam(3,$oficio,PDO::PARAM_INT);
				$this->stmt->bindParam(4,$remitente,PDO::PARAM_STR);
				$this->stmt->bindParam(5,$cargo,PDO::PARAM_STR);
				$this->stmt->bindParam(6,$asunto,PDO::PARAM_STR);
				$this->stmt->bindParam(7,$_POST['sp_id'],PDO::PARAM_INT);
				$this->stmt->bindParam(8,$fecha_desde,PDO::PARAM_STR);
				$this->stmt->bindParam(9,$fecha_hasta,PDO::PARAM_STR);
				$this->stmt->bindParam(10,$censo_id,PDO::PARAM_INT);
				$this->stmt->execute();
							
				return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ LA INFORMACIÓN DE LA RESPUESTA' ) );

				} else {

					$tipo_envio	= $_POST['question_res'];
					$fecha_oficio	= $_POST['f_recepcion'];
					$remitente	= mb_strtoupper($_POST['remitente'],'utf-8');
					$cargo	= mb_strtoupper($_POST['cargo'],'utf-8');
					$asunto	= mb_strtoupper($_POST['asunto'],'utf-8');
					$recibe = $_POST['sp_id'];				
					$fecha_desde	= $_POST['f_desde'];
					$fecha_hasta	= $_POST['f_hasta'];
					$censo_id = $_POST['censo_id'];
				

					if($tipo_envio	== 1){
						$oficio = ( isset($_POST['oficio_id']) ) ? $_POST['oficio_id'] : NULL ;	

					} else if ($tipo_envio	== 2) {
						$oficio =  NULL ;					
					}		

					$this->sql = "UPDATE info_respuesta SET 
						tipo_envio =?,
						f_oficio =?,
						no_oficio =?, 
						remitente =?,
						cargo =?,
						asunto =?,
						recibe =?,
						fecha_desde =?,
						fecha_hasta = ?
						WHERE id_censo = ?
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$tipo_envio,PDO::PARAM_INT);
					$this->stmt->bindParam(2,$fecha_oficio,PDO::PARAM_STR);
					$this->stmt->bindParam(3,$oficio,PDO::PARAM_INT);
					$this->stmt->bindParam(4,$remitente,PDO::PARAM_STR);
					$this->stmt->bindParam(5,$cargo,PDO::PARAM_STR);
					$this->stmt->bindParam(6,$asunto,PDO::PARAM_STR);
					$this->stmt->bindParam(7,$recibe,PDO::PARAM_STR);
					$this->stmt->bindParam(8,$fecha_desde,PDO::PARAM_STR);
					$this->stmt->bindParam(9,$fecha_hasta,PDO::PARAM_STR);
					$this->stmt->bindParam(10,$censo_id,PDO::PARAM_INT);
					$this->stmt->execute();			
		
				return json_encode( array('status'=>'success','message'=>'SE EDITÓ LA INFORMACIÓN DE LA RESPUESTA') );
				}
			
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function getPreguntas()
	{
		try {
			$term = "%".$_REQUEST['term']."%";
			$this->sql = "SELECT id, pregunta AS value FROM preguntas WHERE pregunta LIKE ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$term,PDO::PARAM_STR);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function getCuestionario(){
			try {
				
				$anexgrid = new AnexGrid();
				
				$this->sql = "
				SELECT p.id AS no, cp.id as pe, p.pregunta FROM censo_preguntas AS cp 
					INNER JOIN preguntas AS p ON cp.pregunta_id = p.id
					WHERE cp.censo_id = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$_GET['c'],PDO::PARAM_INT);
				$this->stmt->execute();
				$cuestionario = $this->stmt->fetchAll(PDO::FETCH_OBJ);

				$total = $this->stmt->rowCount();
				return $anexgrid->responde($cuestionario,$total);
				
			} catch (Exception $e) {
				return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
			}
	}

	public function saveResponder()
	{
		try {

			if ( empty($_POST['pre_id']) ) {
				throw new Exception("SE DETECTO QUE LA PREGUNTA NO EXISTE. REGRESE E INTENTE NUEVAMENTE.", 1);
			}else{
				$pregunta	= $_POST['pre_id'];

				$this->sql = "SELECT pregunta FROM respuestas WHERE pregunta = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$pregunta,PDO::PARAM_STR);
				$this->stmt->execute();
				$datos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			}

			if ( empty($datos) ) {	

			$pregunta	= $_POST['pre_id'];
			$respuesta	= $_POST['respuesta'];
			
			$this->sql = "INSERT INTO respuestas (
				id,
				respuesta, 
				pregunta
			) VALUES (
				'',
				?,
				?
			)";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$respuesta,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$pregunta,PDO::PARAM_INT);
			$this->stmt->execute();
						
			return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ LA RESPUESTA' ) );
		} else {

			$respuesta	= $_POST['respuesta'];
			$pregunta	= $_POST['pre_id'];

			$this->sql = "UPDATE respuestas SET 
						respuesta = ?
						WHERE pregunta = ?
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$respuesta,PDO::PARAM_INT);
					$this->stmt->bindParam(2,$pregunta,PDO::PARAM_INT);
					$this->stmt->execute();			
		
				return json_encode( array('status'=>'success','message'=>'SE EDITÓ LA RESPUESTA') );

		}
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}


	public function Responder_Comisionados()
	{
		try {
			if ( empty($_POST['pre_id_c']) ) {
				throw new Exception("SE DETECTO QUE LA PREGUNTA NO EXISTE. REGRESE E INTENTE NUEVAMENTE.", 1);
			}else{
				$pregunta	= $_POST['pre_id_c'];

				$this->sql = "SELECT pregunta FROM respuestas_comisionados WHERE pregunta = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$pregunta,PDO::PARAM_STR);
				$this->stmt->execute();
				$datos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			}

			if ( empty($datos) ) {	

				$pregunta	= $_POST['pre_id_c'];
				$total	= $_POST['total'];
				$respuesta1	= $_POST['deotras'];
				$respuesta2	= $_POST['aotras'];
				
				$this->sql = "INSERT INTO respuestas_comisionados (
					id,
					total,
					deotras, 
					aotras,
					pregunta
				) VALUES (
					'',
					?,
					?,
					?,
					?
				)";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$total,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$respuesta1,PDO::PARAM_INT);
				$this->stmt->bindParam(3,$respuesta2,PDO::PARAM_INT);
				$this->stmt->bindParam(4,$pregunta,PDO::PARAM_INT);
				$this->stmt->execute();
							
				return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ LA RESPUESTA' ) );

			} else {

				$pregunta	= $_POST['pre_id_c'];
				$total	= $_POST['total'];
				$respuesta1	= $_POST['deotras'];
				$respuesta2	= $_POST['aotras'];

			$this->sql = "UPDATE respuestas_comisionados SET 
						total = ?,
						deotras = ?,
						aotras = ?
						WHERE pregunta = ?
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$total,PDO::PARAM_INT);
					$this->stmt->bindParam(2,$respuesta1,PDO::PARAM_INT);
					$this->stmt->bindParam(3,$respuesta2,PDO::PARAM_INT);
					$this->stmt->bindParam(4,$pregunta,PDO::PARAM_INT);
					$this->stmt->execute();			
		
				return json_encode( array('status'=>'success','message'=>'SE EDITÓ LA RESPUESTA') );

			}
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}


	public function Responder_Turnos()
	{
		try {

			if ( empty($_POST['pre_id_t']) ) {
				throw new Exception("SE DETECTO QUE LA PREGUNTA NO EXISTE. REGRESE E INTENTE NUEVAMENTE.", 1);
			}else{
				$pregunta	= $_POST['pre_id_t'];

				$this->sql = "SELECT pregunta FROM respuestas_turnos WHERE pregunta = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$pregunta,PDO::PARAM_STR);
				$this->stmt->execute();
				$datos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			}

			if ( empty($datos) ) {

				$pregunta	= $_POST['pre_id_t'];
				$total	= $_POST['total'];
				$respuesta1	= $_POST['matutino'];
				$respuesta2	= $_POST['vespertino'];
				$respuesta3	= $_POST['nocturno'];
				
				$this->sql = "INSERT INTO respuestas_turnos (
					id,
					total, 
					matutino,
					vespertino,
					nocturno,
					pregunta
				) VALUES (
					'',
					?,
					?,
					?,
					?,
					?
				)";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$total,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$respuesta1,PDO::PARAM_INT);
				$this->stmt->bindParam(3,$respuesta2,PDO::PARAM_INT);
				$this->stmt->bindParam(4,$respuesta3,PDO::PARAM_INT);
				$this->stmt->bindParam(5,$pregunta,PDO::PARAM_INT);
				$this->stmt->execute();

			} else {

				$pregunta	= $_POST['pre_id_t'];
				$total	= $_POST['total'];
				$respuesta1	= $_POST['matutino'];
				$respuesta2	= $_POST['vespertino'];
				$respuesta3	= $_POST['nocturno'];

			$this->sql = "UPDATE respuestas_turnos SET 
						total = ?,
						matutino = ?,
						vespertino = ?,
						nocturno = ?
						WHERE pregunta = ?
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$total,PDO::PARAM_INT);
					$this->stmt->bindParam(2,$respuesta1,PDO::PARAM_INT);
					$this->stmt->bindParam(3,$respuesta2,PDO::PARAM_INT);
					$this->stmt->bindParam(4,$respuesta3,PDO::PARAM_INT);
					$this->stmt->bindParam(5,$pregunta,PDO::PARAM_INT);
					$this->stmt->execute();			
		
				return json_encode( array('status'=>'success','message'=>'SE EDITÓ LA RESPUESTA') );

			}
						
			return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ LA RESPUESTA' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function Responder_Armas()
	{
		try {

			if ( empty($_POST['pre_id_a']) ) {
				throw new Exception("SE DETECTO QUE LA PREGUNTA NO EXISTE. REGRESE E INTENTE NUEVAMENTE.", 1);
			}else{
				$pregunta	= $_POST['pre_id_a'];

				$this->sql = "SELECT pregunta FROM respuestas_armas WHERE pregunta = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$pregunta,PDO::PARAM_STR);
				$this->stmt->execute();
				$datos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			}

			if ( empty($datos) ) {

			$pregunta	= $_POST['pre_id_a'];
			$total	= $_POST['total'];
			$respuesta1	= $_POST['cortas'];
			$respuesta2	= $_POST['largas'];
			
			$this->sql = "INSERT INTO respuestas_armas (
				id,
				total,
				cortas, 
				largas,
				pregunta
			) VALUES (
				'',
				?,
				?,
				?,
				?
			)";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$total,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$respuesta1,PDO::PARAM_INT);
			$this->stmt->bindParam(3,$respuesta2,PDO::PARAM_INT);
			$this->stmt->bindParam(4,$pregunta,PDO::PARAM_INT);
			$this->stmt->execute();
						
			return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ LA RESPUESTA' ) );

			} else {

				$pregunta	= $_POST['pre_id_a'];
				$total	= $_POST['total'];
				$respuesta1	= $_POST['cortas'];
				$respuesta2	= $_POST['largas'];

				$this->sql = "UPDATE respuestas_armas SET 
							total = ?,
							cortas = ?,
							largas = ?
							WHERE pregunta = ?
						";
						$this->stmt = $this->pdo->prepare($this->sql);
						$this->stmt->bindParam(1,$total,PDO::PARAM_INT);
						$this->stmt->bindParam(2,$respuesta1,PDO::PARAM_INT);
						$this->stmt->bindParam(3,$respuesta2,PDO::PARAM_INT);
						$this->stmt->bindParam(4,$pregunta,PDO::PARAM_INT);
						$this->stmt->execute();			
		
				return json_encode( array('status'=>'success','message'=>'SE EDITÓ LA RESPUESTA') );

			}
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}


	public function Responder_Gas()
	{
		try {

			if ( empty($_POST['pre_id_g']) ) {
				throw new Exception("SE DETECTO QUE LA PREGUNTA NO EXISTE. REGRESE E INTENTE NUEVAMENTE.", 1);
			}else{
				$pregunta	= $_POST['pre_id_g'];

				$this->sql = "SELECT pregunta FROM respuestas_gas WHERE pregunta = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$pregunta,PDO::PARAM_STR);
				$this->stmt->execute();
				$datos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			}

			if ( empty($datos) ) {

			$pregunta	= $_POST['pre_id_g'];
			$extra	= $_POST['extra'];
			$respuesta1	= $_POST['semana'];
			$respuesta2	= $_POST['quincena'];
			$respuesta3	= $_POST['mes'];
			
			$this->sql = "INSERT INTO respuestas_gas (
				id,
				extra,
				semana, 
				quincena,
				mes,
				pregunta
			) VALUES (
				'',
				?,
				?,
				?,
				?,
				?
			)";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$extra,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$respuesta1,PDO::PARAM_INT);
			$this->stmt->bindParam(3,$respuesta2,PDO::PARAM_INT);
			$this->stmt->bindParam(4,$respuesta3,PDO::PARAM_INT);
			$this->stmt->bindParam(5,$pregunta,PDO::PARAM_INT);
			$this->stmt->execute();
						
			return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ LA RESPUESTA' ) );

			} else {

			$pregunta	= $_POST['pre_id_g'];
			$extra	= $_POST['extra'];
			$respuesta1	= $_POST['semana'];
			$respuesta2	= $_POST['quincena'];
			$respuesta3	= $_POST['mes'];

			$this->sql = "UPDATE respuestas_gas SET 
						extra = ?,
						semana = ?, 
						quincena = ?,
						mes = ?
						WHERE pregunta = ?
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$extra,PDO::PARAM_INT);
					$this->stmt->bindParam(2,$respuesta1,PDO::PARAM_INT);
					$this->stmt->bindParam(3,$respuesta2,PDO::PARAM_INT);
					$this->stmt->bindParam(4,$respuesta3,PDO::PARAM_INT);
					$this->stmt->bindParam(5,$pregunta,PDO::PARAM_INT);
					$this->stmt->execute();			
		
				return json_encode( array('status'=>'success','message'=>'SE EDITÓ LA RESPUESTA') );

			}
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}


	public function Responder_Vehiculo()
	{
		try {

			if ( empty($_POST['pre_id_v']) ) {
				throw new Exception("SE DETECTO QUE LA PREGUNTA NO EXISTE. REGRESE E INTENTE NUEVAMENTE.", 1);
			}else{
				$pregunta	= $_POST['pre_id_v'];

				$this->sql = "SELECT pregunta FROM respuestas_vehiculo WHERE pregunta = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$pregunta,PDO::PARAM_STR);
				$this->stmt->execute();
				$datos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			}

			if ( empty($datos) ) {	

				$pregunta	= $_POST['pre_id_v'];
				$total	= $_POST['total'];
				$respuesta1	= $_POST['sedan'];
				$respuesta2	= $_POST['pickup'];
				$respuesta3	= $_POST['moto'];
				$respuesta4	= $_POST['acuatico'];
				$respuesta5	= $_POST['aeronave'];
				$respuesta6	= $_POST['dron'];
				
				$this->sql = "INSERT INTO respuestas_vehiculo (
					id,
					total,
					sedan, 
					pickup,
					moto,
					acuatico,
					aeronave,
					dron,
					pregunta
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
				)";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$total,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$respuesta1,PDO::PARAM_INT);
				$this->stmt->bindParam(3,$respuesta2,PDO::PARAM_INT);
				$this->stmt->bindParam(4,$respuesta3,PDO::PARAM_INT);
				$this->stmt->bindParam(5,$respuesta4,PDO::PARAM_INT);
				$this->stmt->bindParam(6,$respuesta5,PDO::PARAM_INT);
				$this->stmt->bindParam(7,$respuesta6,PDO::PARAM_INT);
				$this->stmt->bindParam(8,$pregunta,PDO::PARAM_INT);
				$this->stmt->execute();
						
				return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ LA RESPUESTA' ) );
			
			} else {

				$pregunta	= $_POST['pre_id_v'];
				$total	= $_POST['total'];
				$respuesta1	= $_POST['sedan'];
				$respuesta2	= $_POST['pickup'];
				$respuesta3	= $_POST['moto'];
				$respuesta4	= $_POST['acuatico'];
				$respuesta5	= $_POST['aeronave'];
				$respuesta6	= $_POST['dron'];

				$this->sql = "UPDATE respuestas_vehiculo SET 
							total = ?,
							sedan = ?,
							pickup = ?,
							moto = ?,
							acuatico = ?,
							aeronave = ?,
							dron = ?
							WHERE pregunta = ?
						";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$total,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$respuesta1,PDO::PARAM_INT);
				$this->stmt->bindParam(3,$respuesta2,PDO::PARAM_INT);
				$this->stmt->bindParam(4,$respuesta3,PDO::PARAM_INT);
				$this->stmt->bindParam(5,$respuesta4,PDO::PARAM_INT);
				$this->stmt->bindParam(6,$respuesta5,PDO::PARAM_INT);
				$this->stmt->bindParam(7,$respuesta6,PDO::PARAM_INT);
				$this->stmt->bindParam(8,$pregunta,PDO::PARAM_INT);
				$this->stmt->execute();			
		
				return json_encode( array('status'=>'success','message'=>'SE EDITÓ LA RESPUESTA') );

			}

		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}


	public function saveRecomendacion()
	{
		try {


			if ( empty($_POST['ot_id']) ) {
				throw new Exception("SE DETECTO QUE LA ORDEN DE TRABAJO NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$ot_id 	= $_POST['ot_id'];
			}
			
			$ot_id 	= $_POST['ot_id'];
			$fecha_envio	= $_POST['f_envio'];
			$fecha_acuse	= $_POST['f_acuse'];
			$oficio = ( isset($_POST['oficio_id']) ) ? $_POST['oficio_id'] : NULL ;	
			$destinatario	= mb_strtoupper($_POST['destinatario_ofi'],'utf-8');
			$cargo	= mb_strtoupper($_POST['cargo_remi'],'utf-8');	
			$asunto	= mb_strtoupper($_POST['asunto_ofi'],'utf-8');			
			$comentario	= mb_strtoupper($_POST['comentario'],'utf-8');
			$area	= $_POST['question_a'];	
			$area2	= $_POST['question_a2'];
			//$fecha_limite	= $_POST['f_limite'];

			$this->sql = "INSERT INTO datos_observaciones (
				id,
				f_envio,
				oficio, 
				f_acuse,
				destinatario,
				comentario,
				asunto,
				id_ot,
				f_limite
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
			)";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$fecha_envio,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$oficio,PDO::PARAM_INT);			
			$this->stmt->bindParam(3,$fecha_acuse,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$destinatario,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$asunto,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$ot_id,PDO::PARAM_INT);
			$this->stmt->bindParam(8,$fecha_limite,PDO::PARAM_STR);			
			$this->stmt->execute();
		
			
			for($i = 0; $i < count($_POST['observa']); $i++ ){	
			$observa= mb_strtoupper($_POST['observa'][$i]);
			$this->sql = "INSERT INTO observaciones (id,observacion,id_ot)
				VALUES ('',?,?);";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$observa,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$ot_id,PDO::PARAM_INT);
			$this->stmt->execute();
			//}				
			#Recuperar ultima observación 
			$ultimo = $this->pdo->lastInsertId();

			if ( $area == '1' ) {
				$coord	= $_POST['coord'];	
				$subd	= $_POST['subd'];
				$region	= $_POST['region'];
				$agrupamiento	= $_POST['agrupamiento'];
				$sql_rel = "
				INSERT INTO area_recomendaciones (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_ot)
				VALUES ('',2,?,NULL,?,?,?,?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $coord, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $subd, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $region, PDO::PARAM_INT);
				$this->stmt->bindParam(5, $agrupamiento, PDO::PARAM_INT);
				$this->stmt->bindParam(6, $ot_id, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $area == '2' ) {
				$coord_t	= $_POST['coord_t'];
				$agrupamiento_t	= $_POST['agrupamiento_t'];
				$sql_rel = "
				INSERT INTO area_recomendaciones (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_ot)
				VALUES ('',2,?,NULL,?,NULL,NULL,?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $coord_t, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $agrupamiento_t, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $ot_id, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $area2 == '3' ) {
				$agrupamiento_cprs	= $_POST['agrupamiento_cprs'];	
				$sql_rel = "
				INSERT INTO area_recomendaciones (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_ot)
				VALUES ('',1,?,NULL, NULL,NULL,NULL,?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area2 , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $agrupamiento_cprs, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $ot_id, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $area == '4' ) {
				$niv1	= $_POST['niv1'];
				$niv2	= $_POST['niv2'];	
				$niv3	= $_POST['niv3'];
				$niv4	= $_POST['niv4'];
				$niv5	= $_POST['niv5'];
				$sql_rel = "
				INSERT INTO area_recomendaciones (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_ot)
				VALUES ('',2,?,?,?,?,?,?,?);
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $niv1, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $niv2, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $niv3, PDO::PARAM_INT);
				$this->stmt->bindParam(5, $niv4, PDO::PARAM_INT);
				$this->stmt->bindParam(6, $niv5, PDO::PARAM_INT);
				$this->stmt->bindParam(7, $ot_id, PDO::PARAM_INT);
				$this->stmt->execute();
			}

			//for ($i=0; $i < count($_POST['recomenda']); $i++) { 
			
			$recomenda = mb_strtoupper($_POST['recomenda'][$i]);
			$this->sql = "INSERT INTO recomendaciones (id,recomendacion,id_observa,atendido, observaciones)VALUES ('',?,?,4,'SIN OBSERVACIONES')";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$recomenda,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$ultimo,PDO::PARAM_INT);
			$this->stmt->execute();
			}	
					
			return json_encode( array('status'=>'success','message'=>'SE HA INSERTADO LA INFORMACIÓN CORRECTAMENTE.') );
		} catch (Exception $e) {
			#if( $e->getCode()  ){}
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}


	public function saveDocObs()
	{
		try {
			$size = $_FILES['archivo']['size'];
			$type = $_FILES['archivo']['type'];
			$name = $_FILES['archivo']['name'];
			$destiny = $_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/';
			
			if ( $size > 10485760 ) 
			{
				throw new Exception("EL ARCHIVO EXCEDE EL TAMAÑO ADMITIDO (10MB)", 1);
			}
			else
			{
				if ( $type != 'application/pdf' AND $type != 'image/png' AND $type != 'image/jpeg' ) 
				{
					throw new Exception("EL FORMATO DEL ARCHIVO ES INCORRECTO.", 1);
				}
				else
				{
					#convertir a bytes
					move_uploaded_file($_FILES['archivo']['tmp_name'],$destiny.$name);
					$file = fopen($destiny.$name,'r');
					$content = fread($file,$size);
					$content = addslashes($content);
					fclose($file);
					$comentario		= mb_strtoupper($_POST['comentario'],'utf-8');
					#Insertar en la BD
					$this->sql = "
					INSERT INTO documentos_obs(
						id,
						fecha,
						nombre, 
						formato,
						archivo,
						comentario,
						ot_id) 
					VALUES ('',?,?,?,?,?,?);
					";
					$this->stmt = $this->pdo->prepare( $this->sql );					
					$this->stmt->bindParam(1,$_POST['fecha_doc'],PDO::PARAM_STR);
					$this->stmt->bindParam(2,$_POST['nombre'],PDO::PARAM_STR);
					$this->stmt->bindParam(3,$type,PDO::PARAM_STR);
					$this->stmt->bindParam(4,$content,PDO::PARAM_LOB);
					$this->stmt->bindParam(5,$comentario,PDO::PARAM_STR);
					$this->stmt->bindParam(6,$_POST['ot_id'],PDO::PARAM_INT);
					
					$this->stmt->execute();
					unlink($destiny.$name);

					return json_encode(array('status'=>'success','message'=>'Se guardó el documento con éxito.' ));
				}
			}
		} catch (Exception $e) {
			return json_encode(array('status'=>'error','message'=>$e->getMessage() ));
		}
	}

	public function saveRecordatorioObs()
	{
		try {

			if ( empty($_POST['ot_id']) ) {
				throw new Exception("SE DETECTO QUE LA ORDEN DE TRABAJO NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$ot_id 	= $_POST['ot_id'];
			}
			
			#Las variables
			if ( !isset($_POST['f_acuse_r']) AND empty($_POST['f_acuse_r']) ) {
				throw new Exception("NO SE REGISTRÓ LA FECHA DEL ACUSE", 1);
			}
			if ( !isset($_POST['oficio']) OR empty($_POST['oficio']) ) {
				throw new Exception("NO SE REGISTRÓ UN NÚMERO DE OFICIO.", 1);
			}
			
					
			$oficio = ( isset($_POST['oficio_id']) ) ? $_POST['oficio_id'] : NULL ;			
			$observaciones	= mb_strtoupper($_POST['observar'],'utf-8');
			$fecha_envio	= $_POST['f_envio_r'];		
			$fecha_acuse	= $_POST['f_acuse_r'];
			$fecha_limite	= $_POST['f_limite'];
			$destinatario	= mb_strtoupper($_POST['destinatario_ofi'],'utf-8');
			$asunto	= mb_strtoupper($_POST['asunto_ofi'],'utf-8');


			$this->sql = "INSERT INTO recordatorio_obs (
				id,
				f_recordatorio,
				no_oficio,
				f_acuse,
				destinatario,
				observaciones,
				asunto,
				f_limite,
				ot_id
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
			)";
			$this->stmt = $this->pdo->prepare($this->sql);	
			$this->stmt->bindParam(1,$fecha_envio,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$oficio,PDO::PARAM_INT);		
			$this->stmt->bindParam(3,$fecha_acuse,PDO::PARAM_STR);			
			$this->stmt->bindParam(4,$destinatario,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$observaciones,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$asunto,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$fecha_limite,PDO::PARAM_STR);
			$this->stmt->bindParam(8,$ot_id,PDO::PARAM_INT);
			$this->stmt->execute();		
			
						
			return json_encode( array('status'=>'success','message'=> 'SE HA REGISTRADO UN RECORATORIO' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function getCoordinaciones()
	{
		try {
			$this->sql = " SELECT * FROM catalogo_coordinacion ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}

	public function getSubdirecciones($coord)
	{
		try {
			$this->sql = " SELECT * FROM catalogo_subdirecciones WHERE id_coordinacion = ? ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$coord,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}


	public function getRegiones($subd)
	{
		try {
			$this->sql = " SELECT * FROM catalogo_regiones WHERE id_subdireccion = ? ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$subd,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}


	public function getAgrupamientos($region)
	{
		try {
			$this->sql = " SELECT * FROM catalogo_agrupamientos WHERE id_region = ? ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$region,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}


	public function getCoordinacionesT()
	{
		try {
			$this->sql = " SELECT * FROM catalogo_transito_coordinacion ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}


	public function getAgrupamientosT($coord_t)
	{
		try {
			$this->sql = " SELECT * FROM catalogo_transito_agrupamieno WHERE id_coordinacion = ? ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$coord_t,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}


	public function getAgrupamientosCPRS()
	{
		try {
			$this->sql = " SELECT * FROM catalogo_dgprs ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}

	public function getNivel1()
	{
		try {
			$this->sql = " SELECT * FROM catalogo_nivel1_admin ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}

	public function getNivel2($niv1)
	{
		try {
			$this->sql = " SELECT * FROM catalogo_nivel2_admin WHERE id_nivel1 = ? ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$niv1,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}


	public function getNivel3($niv2)
	{
		try {
			$this->sql = " SELECT * FROM catalogo_nivel3_admini WHERE id_nivel2 = ? ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$niv2,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}

	public function getNivel4($niv3)
	{
		try {
			$this->sql = " SELECT * FROM catalogo_nivel4_admini WHERE id_nivel3 = ? ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$niv3,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}


	public function getNivel5($niv4)
	{
		try {
			$this->sql = " SELECT * FROM catalogo_nivel5_admini WHERE id_nivel4 = ? ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$niv4,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}




	public function saveSeguimiento()
	{
		try {

			if ( empty($_POST['ot_id']) ) {
				throw new Exception("SE DETECTO QUE LA ORDEN DE TRABAJO NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$ot_id 	= $_POST['ot_id'];

				$this->sql = "SELECT id FROM info_seguimiento WHERE id_ot = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$ot_id,PDO::PARAM_STR);
				$this->stmt->execute();
				$datos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			}

			if ( empty($datos) ) {				
			
				$fecha	= $_POST['f_recepcion'];
				$oficio = ( isset($_POST['oficio_id']) ) ? $_POST['oficio_id'] : NULL ;
				$recibe = ( isset($_POST['sp_id']) ) ? $_POST['sp_id'] : NULL ;
				$remitente	= mb_strtoupper($_POST['remitente'],'utf-8');
				$cargo	= mb_strtoupper($_POST['cargo'],'utf-8');
				$asunto	= mb_strtoupper($_POST['asunto'],'utf-8');

				$this->sql = "INSERT INTO info_seguimiento (
					id, 
					fecha,
					oficio,
					remitente,
					cargo,
					asunto,
					recibe,
					estatus,
					observaciones,
					id_ot
				) VALUES (
					'',
					?,
					?,
					?,
					?,
					?,
					?,
					3,
					'SIN OBSERVACIONES',
					?
				)";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$fecha,PDO::PARAM_STR);
				$this->stmt->bindParam(2,$oficio,PDO::PARAM_INT);
				$this->stmt->bindParam(3,$remitente,PDO::PARAM_STR);
				$this->stmt->bindParam(4,$cargo,PDO::PARAM_STR);
				$this->stmt->bindParam(5,$asunto,PDO::PARAM_STR);
				$this->stmt->bindParam(6,$recibe,PDO::PARAM_INT);
				$this->stmt->bindParam(7,$ot_id,PDO::PARAM_INT);
				$this->stmt->execute();
							
				return json_encode( array('status'=>'success','message'=> 'SE REGISTRÓ LA INFORMACIÓN DEL SEGUIMIENTO' ) );
			
			} else{

				$fecha	= $_POST['f_recepcion'];
				$oficio = ( isset($_POST['oficio_id']) ) ? $_POST['oficio_id'] : NULL ;
				$recibe = ( isset($_POST['sp_id']) ) ? $_POST['sp_id'] : NULL ;
				$remitente	= mb_strtoupper($_POST['remitente'],'utf-8');
				$cargo	= mb_strtoupper($_POST['cargo'],'utf-8');
				$asunto	= mb_strtoupper($_POST['asunto'],'utf-8');
				$ot_id 	= $_POST['ot_id'];


				$this->sql = "UPDATE info_seguimiento SET 
						fecha = ?,
						oficio = ?,
						remitente = ?,
						cargo = ?,
						asunto = ?,
						recibe = ?
						WHERE id_ot = ?
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					$this->stmt->bindParam(1,$fecha,PDO::PARAM_STR);
					$this->stmt->bindParam(2,$oficio,PDO::PARAM_INT);
					$this->stmt->bindParam(3,$remitente,PDO::PARAM_STR);
					$this->stmt->bindParam(4,$cargo,PDO::PARAM_STR);
					$this->stmt->bindParam(5,$asunto,PDO::PARAM_STR);
					$this->stmt->bindParam(6,$recibe,PDO::PARAM_STR);
					$this->stmt->bindParam(7,$ot_id,PDO::PARAM_INT);
					$this->stmt->execute();			
		
				return json_encode( array('status'=>'success','message'=>'SE EDITÓ LA INFORMACIÓN DEL SEGUIMIENTO') );
				}

			

		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function getRecomendaciones(){
			try {
				
				$anexgrid = new AnexGrid();
				
				$this->sql = "
				SELECT r.id, o.observacion AS irregularidad, r.recomendacion, r.atendido, r.observaciones FROM recomendaciones As r
				INNER JOIN observaciones AS o
				ON r.id_observa = o.id WHERE o.id_ot = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$_GET['o'],PDO::PARAM_INT);
				$this->stmt->execute();
				$cuestionario = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$total = $this->stmt->rowCount();
				return $anexgrid->responde($cuestionario,$total);
				
			} catch (Exception $e) {
				return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
			}
	}


	public function saveReco_respuesta()
	{
		try {

				$desc = mb_strtoupper($_POST['desc'],'utf-8');			
				$this->sql = "
				UPDATE recomendaciones SET atendido = ?, observaciones = ? WHERE id = ?;
				";
				$this->stmt = $this->pdo->prepare( $this->sql );				
				$this->stmt->bindParam(1,$_POST['atendido'],PDO::PARAM_INT);
				$this->stmt->bindParam(2,$desc,PDO::PARAM_STR);
				$this->stmt->bindParam(3,$_POST['id'],PDO::PARAM_INT);
				$this->stmt->execute();

				
				return json_encode(array('status'=>'success','message'=>'SE REALIZÓ EL REGISTRO' ));
			
		} catch (Exception $e) {
			return json_encode(array('status'=>'error','message'=>$e->getMessage() ));
		}
	}

	public function saveEstatus()
	{
		try {


			if ( empty($_POST['id']) ) {
				throw new Exception("SE DETECTO QUE LA ORDEN DE TRABAJO NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$ot 	= $_POST['id'];
			}

				$desc = mb_strtoupper($_POST['desc'],'utf-8');			
				$this->sql = "
				UPDATE 	info_seguimiento SET estatus = ?, observaciones = ? WHERE id_ot = ?;
				";
				$this->stmt = $this->pdo->prepare( $this->sql );				
				$this->stmt->bindParam(1,$_POST['atendido'],PDO::PARAM_INT);
				$this->stmt->bindParam(2,$desc,PDO::PARAM_STR);
				$this->stmt->bindParam(3,$ot,PDO::PARAM_INT);
				$this->stmt->execute();

				
				return json_encode(array('status'=>'success','message'=>'SE REALIZÓ EL REGISTRO' ));
			
		} catch (Exception $e) {
			return json_encode(array('status'=>'error','message'=>$e->getMessage() ));
		}
	}

	public function getArea($id)
	{
		try {

			$aux = array();

			$this->sql = " SELECT * FROM area_censo WHERE id_censo = ? ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
			$this->stmt->execute();
			$area = $this->stmt->fetchAll(PDO::FETCH_OBJ);


			if ( $area == 'Operativos Secretaría de Seguridad' ) {
				$censo = "";
				$this->sql = " SELECT a.*, ca.nombre FROM area_censo AS a
				INNER JOIN catalogo_agrupamientos AS ca 
				ON a.id_censo = ca.id WHERE id_censo = ? ";
				$this->stmt = $this->pdo->prepare( $this->sql );				
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
				$aux['queja'] = $this->result;
			

			} else if ( $area == 'Dirección de Policía de Tránsito' ) {
				$this->sql = " SELECT a.*, ca.nombre FROM area_censo AS a
				INNER JOIN catalogo_transito_agrupamieno AS ca 
				ON a.id_censo = ca.id WHERE id_censo = ? ";
				$this->stmt = $this->pdo->prepare( $this->sql );				
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
				$aux['queja'] = $this->result;
			
			} else if ( $area == 'Dirección General de Prevención y Reinserción Social' ) {
				$this->sql = " SELECT a.*, ca.nombre FROM area_censo AS a
				INNER JOIN catalogo_dgprs AS ca 
				ON a.id_censo = ca.id WHERE id_censo = ? ";	
				$this->stmt = $this->pdo->prepare( $this->sql );			
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);				
				$this->stmt->execute();				
				$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
				$aux['queja'] = $this->result;
			
			} else if ( $area == 'Personal Administrativo' ) {
				$this->sql = " SELECT a.*, ca.nombre FROM area_censo AS a
				INNER JOIN catalogo_nivel5_admini AS ca 
				ON a.id_censo = ca.id WHERE id_censo = ? ";	
				$this->stmt = $this->pdo->prepare( $this->sql );			
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);				
				$this->stmt->execute();				
				$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
				$aux['queja'] = $this->result;
			
			}

	
			return $aux;

			//$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			//return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}


	public function graphic_censo($n_pregunta, $question_a, $question_a2, $agrupamiento, $agrupamiento_t, $agrupamiento_cprs, $niv5)
	{
		try {

			$pregunta = $question_a;


			if($_POST['question_a'] == 1){
		
			
			$this->sql = "
			SELECT  c.id FROM censo AS c 
			INNER JOIN area_censo AS ac ON ac.id_censo = c.id
			INNER JOIN  catalogo_agrupamientos AS cat_a ON ac.id_agrupamiento = cat_a.id 
			WHERE ac.area = 'Operativos Secretaría de Seguridad' AND ac.id_agrupamiento = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$agrupamiento,PDO::PARAM_STR);
			$this->stmt->execute();
			$censos = $this->stmt->fetchAll(PDO::FETCH_OBJ);

			$c = array();
			foreach ($censos as $key => $ce) {
				array_push($c, $ce ->id);
			}
			$c = implode(',',$c);


			$this->sql = "
			SELECT cp.id FROM censo_preguntas AS cp WHERE cp.censo_id IN ($c)  AND cp.pregunta_id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			//$this->stmt->bindParam(1,$c,PDO::PARAM_STR);
			$this->stmt->bindParam(1,$n_pregunta,PDO::PARAM_INT);
			$this->stmt->execute();
			$pregunta = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			

			$p = array();
			foreach ($pregunta as $key => $pre) {
				array_push($p, $pre ->id);
			}
			$p = implode(',',$p);

				if($_POST['n_pregunta'] == 1 || $_POST['n_pregunta'] == 3 || $_POST['n_pregunta'] == 4 || $_POST['n_pregunta'] == 5 || $_POST['n_pregunta'] == 10){

	 				$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.respuesta FROM respuestas AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

	 			} else if($_POST['n_pregunta'] == 2){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.deotras, r.aotras FROM respuestas_comisionados AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 6){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.matutino, r.vespertino, r.nocturno FROM respuestas_turnos AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 7){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.sedan, r.pickup, r.moto, r.acuatico, r.aeronave, r.dron FROM respuestas_vehiculo AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 8){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.cortas, r.largas FROM respuestas_armas AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 9){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.semana, r.quincena, r.mes FROM respuestas_gas AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);
				}
			
 			
	

			return json_encode($grafica);


			} else if($_POST['question_a'] == 2){
		
			
			$this->sql = "
			SELECT  c.id FROM censo AS c 
			INNER JOIN area_censo AS ac ON ac.id_censo = c.id
			INNER JOIN  catalogo_transito_agrupamieno AS cat_a ON ac.id_agrupamiento = cat_a.id 
			WHERE ac.area = 'Dirección de Policía de Tránsito' AND ac.id_agrupamiento = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$agrupamiento_t,PDO::PARAM_STR);
			$this->stmt->execute();
			$censos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			//$censos = implode(',',$censos);
			$c = array();
			//$aux = array();
			foreach ($censos as $key => $ce) {
				array_push($c, $ce ->id);
			}
			$c = implode(',',$c);


			$this->sql = "
			SELECT cp.id FROM censo_preguntas AS cp WHERE cp.censo_id IN ($c)  AND cp.pregunta_id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			//$this->stmt->bindParam(1,$c,PDO::PARAM_STR);
			$this->stmt->bindParam(1,$n_pregunta,PDO::PARAM_INT);
			$this->stmt->execute();
			$pregunta = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			
			//$pregunta = implode(', ',$pregunta[0]);
			$p = array();
			foreach ($pregunta as $key => $pre) {
				array_push($p, $pre ->id);
			}
			$p = implode(',',$p);	 			


	 			if($_POST['n_pregunta'] == 1 || $_POST['n_pregunta'] == 3 || $_POST['n_pregunta'] == 4 || $_POST['n_pregunta'] == 5 || $_POST['n_pregunta'] == 10){

	 				$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.respuesta FROM respuestas AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

	 			} else if($_POST['n_pregunta'] == 2){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.deotras, r.aotras FROM respuestas_comisionados AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 6){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.matutino, r.vespertino, r.nocturno FROM respuestas_turnos AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 7){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.sedan, r.pickup, r.moto, r.acuatico, r.aeronave, r.dron FROM respuestas_vehiculo AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 8){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.cortas, r.largas FROM respuestas_armas AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 9){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.semana, r.quincena, r.mes FROM respuestas_gas AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);
				}

			return json_encode($grafica);


			} else if($_POST['question_a2'] == 3){
		
			
			$this->sql = "
			SELECT  c.id FROM censo AS c 
			INNER JOIN area_censo AS ac ON ac.id_censo = c.id
			INNER JOIN  catalogo_dgprs AS cat_a ON ac.id_agrupamiento = cat_a.id 
			WHERE ac.area = 'Dirección General de Prevención y Reinserción Social' AND ac.id_agrupamiento = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$agrupamiento_cprs,PDO::PARAM_STR);
			$this->stmt->execute();
			$censos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			//$censos = implode(',',$censos);
			$c = array();
			//$aux = array();
			foreach ($censos as $key => $ce) {
				array_push($c, $ce ->id);
			}
			$c = implode(',',$c);


			$this->sql = "
			SELECT cp.id FROM censo_preguntas AS cp WHERE cp.censo_id IN ($c)  AND cp.pregunta_id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			//$this->stmt->bindParam(1,$c,PDO::PARAM_STR);
			$this->stmt->bindParam(1,$n_pregunta,PDO::PARAM_INT);
			$this->stmt->execute();
			$pregunta = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			
			//$pregunta = implode(', ',$pregunta[0]);
			$p = array();
			foreach ($pregunta as $key => $pre) {
				array_push($p, $pre ->id);
			}
			$p = implode(',',$p);


				if($_POST['n_pregunta'] == 1 || $_POST['n_pregunta'] == 3 || $_POST['n_pregunta'] == 4 || $_POST['n_pregunta'] == 5 || $_POST['n_pregunta'] == 10){

	 				$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.respuesta FROM respuestas AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
					";

					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

	 			} else if($_POST['n_pregunta'] == 2){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.deotras, r.aotras FROM respuestas_comisionados AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 6){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.matutino, r.vespertino, r.nocturno FROM respuestas_turnos AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 7){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.sedan, r.pickup, r.moto, r.acuatico, r.aeronave, r.dron FROM respuestas_vehiculo AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 8){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.cortas, r.largas FROM respuestas_armas AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 9){

					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.semana, r.quincena, r.mes FROM respuestas_gas AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);
				}
			

			//print_r($data);
			//exit;		

			return json_encode($grafica);


			} else if($_POST['question_a'] == 4){
		
			
			$this->sql = "
			SELECT  c.id FROM censo AS c 
			INNER JOIN area_censo AS ac ON ac.id_censo = c.id
			INNER JOIN  catalogo_nivel5_admini AS cat_a ON ac.id_agrupamiento = cat_a.id 
			WHERE ac.area = 'Personal Administrativo' AND ac.id_agrupamiento = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$niv5,PDO::PARAM_STR);
			$this->stmt->execute();
			$censos = $this->stmt->fetchAll(PDO::FETCH_OBJ);

			$c = array();
			foreach ($censos as $key => $ce) {
				array_push($c, $ce ->id);
			}
			$c = implode(',',$c);


			$this->sql = "
			SELECT cp.id FROM censo_preguntas AS cp WHERE cp.censo_id IN ($c)  AND cp.pregunta_id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			//$this->stmt->bindParam(1,$c,PDO::PARAM_STR);
			$this->stmt->bindParam(1,$n_pregunta,PDO::PARAM_INT);
			$this->stmt->execute();
			$pregunta = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			

			$p = array();
			foreach ($pregunta as $key => $pre) {
				array_push($p, $pre ->id);
			}
			$p = implode(',',$p);

				if($_POST['n_pregunta'] == 1 || $_POST['n_pregunta'] == 3 || $_POST['n_pregunta'] == 4 || $_POST['n_pregunta'] == 5 || $_POST['n_pregunta'] == 10){

	 				//$this->sql = "
					//SELECT pregunta, respuesta FROM respuestas WHERE pregunta IN ($p)
					//";
					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.respuesta FROM respuestas AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
					";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

	 			} else if($_POST['n_pregunta'] == 2){

					/*$this->sql = "
					SELECT pregunta, deotras, aotras FROM respuestas_comisionados WHERE pregunta IN ($p)
					";*/
					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.deotras, r.aotras FROM respuestas_comisionados AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 6){

					/*$this->sql = "
					SELECT pregunta, matutino, vespertino, nocturno FROM respuestas_turnos WHERE pregunta IN ($p)
					";*/
					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.matutino, r.vespertino, r.nocturno FROM respuestas_turnos AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 7){

					/*$this->sql = "
					SELECT pregunta, sedan, pickup, moto, acuatico, aeronave, dron FROM respuestas_vehiculo WHERE pregunta IN ($p)
					";*/
					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.sedan, r.pickup, r.moto, r.acuatico, r.aeronave, r.dron FROM respuestas_vehiculo AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 8){

					/*$this->sql = "
					SELECT pregunta, cortas, largas FROM respuestas_armas WHERE pregunta IN ($p)
					";*/
					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.cortas, r.largas FROM respuestas_armas AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);

				} else if($_POST['n_pregunta'] == 9){

					/*$this->sql = "
					SELECT pregunta, semana, quincena, mes FROM respuestas_gas WHERE pregunta IN ($p)
					";*/
					$this->sql = "
					SELECT CONCAT(i.fecha_desde, ' / ', i.fecha_hasta) AS pregunta, r.semana, r.quincena, r.mes FROM respuestas_gas AS r
						INNER JOIN censo_preguntas AS cp ON cp.id=r.pregunta 
						INNER JOIN censo AS c ON c.id=cp.censo_id
						INNER JOIN info_respuesta AS i ON i.id_censo=c.id
						 WHERE r.pregunta IN ($p)
						 ";
					$this->stmt = $this->pdo->prepare($this->sql);
					//$this->stmt->bindParam(1,$p,PDO::PARAM_STR);
					$this->stmt->execute();
					$grafica = $this->stmt->fetchAll(PDO::FETCH_NUM);
				}
			
 			
	

			return json_encode($grafica);


			}


		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}


	public function getOFsRes()
	{
		try {
			$term = "%".$_REQUEST['term']."%";
			$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				
			$this->sql = "SELECT id_respuesta AS id, oficio_respuesta AS value 
			FROM oficios_generadosrespuesta WHERE oficio_respuesta LIKE ? LIMIT 0,20		
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

	public function getDetalle($pe)
	{
		try {


			$sql_select_pregunta = "SELECT p.id FROM preguntas AS p 
			INNER JOIN censo_preguntas AS cp
			ON p.id = cp.pregunta_id WHERE cp.id = ?";
			$this->stmt = $this->pdo->prepare($sql_select_pregunta);
			$this->stmt->bindParam(1,$pe,PDO::PARAM_INT);
			$this->stmt->execute();
			$pregunta = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
			$pregunta = implode(', ',$pregunta[0]);

			if ($pregunta == '1' || $pregunta == '3' || $pregunta == '4' || $pregunta == '5' || $pregunta == '10') {

				$detalle = array();
			#recuperar los datos
			$this->sql = "
				SELECT r.respuesta FROM censo_preguntas AS cp
					INNER JOIN respuestas AS r ON cp.id = r.pregunta
					WHERE cp.id = ?";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$pe,PDO::PARAM_INT);
			$this->stmt->execute();
			$resp1 = $this->stmt->fetch(PDO::FETCH_ASSOC);

			} else if ($pregunta == '2'){

				$detalle = array();
			#recuperar los datos
			$this->sql = "
				SELECT CONCAT('Total: ',r.total, ', de otras áreas: ', r.deotras,', a otras áreas: ',r.aotras) AS respuesta FROM censo_preguntas AS cp
					INNER JOIN respuestas_comisionados AS r ON cp.id = r.pregunta
					WHERE cp.id = ?";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$pe,PDO::PARAM_INT);
			$this->stmt->execute();
			$resp1 = $this->stmt->fetch(PDO::FETCH_ASSOC);
			} else if ($pregunta == '6'){

				$detalle = array();
			#recuperar los datos
			$this->sql = "
				SELECT CONCAT('Total: ',r.total, ', matutino: ', r.matutino,', vespertino: ',r.vespertino,', nocturno: ',r.nocturno) AS respuesta FROM censo_preguntas AS cp
					INNER JOIN respuestas_turnos AS r ON cp.id = r.pregunta
					WHERE cp.id = ?";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$pe,PDO::PARAM_INT);
			$this->stmt->execute();
			$resp1 = $this->stmt->fetch(PDO::FETCH_ASSOC);
			} else if ($pregunta == '7'){

				$detalle = array();
			#recuperar los datos
			$this->sql = "
				SELECT CONCAT('Total: ',r.total, ', sedan: ', r.sedan,', pickup: ',r.pickup,', moto: ',r.moto,', acuatico: ',r.acuatico,', aeronave: ',r.aeronave,', dron: ',r.dron) AS respuesta FROM censo_preguntas AS cp
					INNER JOIN respuestas_vehiculo AS r ON cp.id = r.pregunta
					WHERE cp.id = ?";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$pe,PDO::PARAM_INT);
			$this->stmt->execute();
			$resp1 = $this->stmt->fetch(PDO::FETCH_ASSOC);
			} else if ($pregunta == '8'){

				$detalle = array();
			#recuperar los datos
			$this->sql = "
				SELECT CONCAT('Total: ',r.total, ', cortas: ', r.cortas,', largas: ',r.largas) AS respuesta FROM censo_preguntas AS cp
					INNER JOIN respuestas_armas AS r ON cp.id = r.pregunta
					WHERE cp.id = ?";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$pe,PDO::PARAM_INT);
			$this->stmt->execute();
			$resp1 = $this->stmt->fetch(PDO::FETCH_ASSOC);
			} else if ($pregunta == '9'){

				$detalle = array();
			#recuperar los datos
			$this->sql = "
				SELECT CONCAT('Combusible semanal: ',r.semana, ', quincenal: ', r.quincena,', mensual: ',r.mes,', extraordinario: ',r.extra) AS respuesta FROM censo_preguntas AS cp
					INNER JOIN respuestas_gas AS r ON cp.id = r.pregunta
					WHERE cp.id = ?";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$pe,PDO::PARAM_INT);
			$this->stmt->execute();
			$resp1 = $this->stmt->fetch(PDO::FETCH_ASSOC);
			}

			if ( isset($resp1) && !empty($resp1) ) {
				$detalle['resp1'] 	= $resp1;
			}else{
				$resp1 = array('estado'=>'empty','message'=>'Sin respuesta');
				$detalle['resp1'] 	= $resp1;
			}

			return json_encode($detalle) ;
			
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage() ) );
		}
	}



	public function getAcuse_Irr($file)
	{
		try {
			$this->sql = "SELECT archivo FROM documentos_obs WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$file,PDO::PARAM_INT);
			$this->stmt->execute();
			$doc = $this->stmt->fetch(PDO::FETCH_OBJ);
			echo stripslashes($doc->archivo);
			
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}

	 public function graphic_actuaciones($f_inicio,$f_fin)
    {
    	$this->sql = "
    	SELECT
            t_actuacion AS actuacion, COUNT(t_actuacion) AS total
        FROM
            actas
        WHERE
            DATE(fecha) BETWEEN ? AND ?
        GROUP BY t_actuacion
        ORDER BY COUNT(t_actuacion) DESC
        LIMIT 10
    	";
    	$this->statement = $this->pdo->prepare( $this->sql );
    	$this->statement->bindParam(1,$f_inicio);
    	$this->statement->bindParam(2,$f_fin);
    	$this->statement->execute();
    	$this->result = $this->statement->fetchAll( PDO::FETCH_ASSOC );

    	return json_encode($this->result);
    } 


    public function graphic_ordenes($f_inicio,$f_fin)
    {
    	//$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
    	$this->sql = "
    	SELECT
            estatus AS estatus, COUNT(estatus) AS total
        FROM
            orden_inspeccion
        WHERE
            DATE(f_creacion) BETWEEN ? AND ?
        GROUP BY estatus
        ORDER BY COUNT(estatus) DESC
        LIMIT 10
    	";
    	$this->statement = $this->getPDO()->prepare($this->sql);
    	//$this->statement = $this->pdo->prepare( $this->sql );
    	$this->statement->bindParam(1,$f_inicio);
    	$this->statement->bindParam(2,$f_fin);
    	$this->statement->execute();
    	$this->result = $this->statement->fetchAll( PDO::FETCH_ASSOC );
    	return json_encode($this->result);
    } 


    public function getOficio($o)
	{
		try {
			$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
			$this->sql = "
			SELECT
			    id_generado, remitido_a, cargo, asunto
			FROM
			    oficios_generados
			WHERE id_generado = ?";
			$this->stmt = $this->pdo->prepare( $this->sql );			
			$this->stmt->bindParam(1,$o,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}
	

	public function getAreaPersonal($p)
	{
		try {
			$this->sql = "
			SELECT  a.nombre
			FROM personal as p
                INNER JOIN areas AS a
                ON a.id = p.area_id
			WHERE p.id = ?";
			$this->stmt = $this->pdo->prepare( $this->sql );			
			$this->stmt->bindParam(1,$p,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetch( PDO::FETCH_OBJ );
			return json_encode( $this->result );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function getPreguntasCenso()
	{
		try {
			$this->sql = " SELECT id, CONCAT(id,'. ',pregunta) AS nombre FROM preguntas ";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}

	public function getTransito_Est()
	{
		try {
			$this->sql = " SELECT * FROM catalogo_transito_agrupamieno";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}

	public function getOperativos_Est()
	{
		try {
			$this->sql = " SELECT * FROM catalogo_agrupamientos";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}

	public function getCPRS_Est()
	{
		try {
			$this->sql = " SELECT * FROM catalogo_dgprs";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}

	public function getAdmin_Est()
	{
		try {
			$this->sql = " SELECT * FROM catalogo_nivel5_admini";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll( PDO::FETCH_OBJ );
			return json_encode($this->result) ;
		} catch (Exception $e) {
			$this->result = array('error' => $e->getMessage()  );
		}
	}

	public function tabla_actuaciones($fi,$ff)
    {
    	$this->sql = "
    	SELECT
            t_actuacion AS actuacion, fecha, clave 
        FROM actas
        WHERE
            DATE(fecha) BETWEEN ? AND ?
    	";
    	$this->statement = $this->pdo->prepare( $this->sql );
    	$this->statement->bindParam(1,$fi,PDO::PARAM_STR);
    	$this->statement->bindParam(2,$ff,PDO::PARAM_STR);
    	$this->statement->execute();
    	$this->result = $this->statement->fetchAll( PDO::FETCH_ASSOC );
    	return json_encode($this->result);
    }

    public function getOnlyCenso($censo)
	{
		try {
			$this->sql = "SELECT c.*, ac.area, ac.id_coordinacion,ac.id_subdireccion, ac.id_region, ac.id_agrupamiento
			FROM censo AS c
			INNER JOIN area_censo AS ac ON ac.id_censo = c.id 
			WHERE c.id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$censo,PDO::PARAM_INT);
			$this->stmt->execute();
			$censos = $this->stmt->fetch(PDO::FETCH_OBJ);
			$aux = array();
			$aux['censos'] = $censos;
		
			return $aux;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function editCenso()
	{
		try {
						
			$censo_id = $_POST['censo_id'];
			$tipo_envio	= $_POST['question'];
			$fecha_envio	= $_POST['f_envio'];						
			$fecha_acuse	= $_POST['f_acuse'];		
			$observaciones	= mb_strtoupper($_POST['observa'],'utf-8');			
			$area	= $_POST['question_a'];	
			$area2	= $_POST['question_a2'];
			$fecha_limite	= $_POST['f_limite'];

			if($tipo_envio	== 1){
				$destinatario	= mb_strtoupper($_POST['destinatario_ofi'],'utf-8');
				$asunto	= mb_strtoupper($_POST['asunto_ofi'],'utf-8');
				$oficio = ( isset($_POST['oficio_id']) ) ? $_POST['oficio_id'] : NULL ;	

			} else if ($tipo_envio	== 2) {
				$destinatario	= mb_strtoupper($_POST['destinatario'],'utf-8');
				$asunto	= mb_strtoupper($_POST['asunto'],'utf-8');
				$oficio =  NULL ;
			
			}						
			$this->sql = "UPDATE censo SET 
				tipo_envio =?,
				f_envio =?,
				no_oficio =?, 
				f_acuse =?,
				destinatario =?,
				observaciones =?,
				asunto =?,
				f_limite =?
				WHERE id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$tipo_envio,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$fecha_envio,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$oficio,PDO::PARAM_INT);
			$this->stmt->bindParam(4,$fecha_acuse,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$destinatario,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$observaciones,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$asunto,PDO::PARAM_STR);
			$this->stmt->bindParam(8,$fecha_limite,PDO::PARAM_STR);
			$this->stmt->bindParam(9,$censo_id,PDO::PARAM_INT);
			$this->stmt->execute();


			if ( $_POST['question_a'] == 1 ) {
				$coord	= $_POST['coord'];	
				$subd	= $_POST['subd'];
				$region	= $_POST['region'];
				$agrupamiento	= $_POST['agrupamiento'];
				$sql_rel = "
				UPDATE area_censo SET procedencia = 2, area = ?, id_direccion = 'NULL', id_coordinacion = ?, id_subdireccion = ?, id_region =?, id_agrupamiento = ? WHERE id_censo = ?
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $coord, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $subd, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $region, PDO::PARAM_INT);
				$this->stmt->bindParam(5, $agrupamiento, PDO::PARAM_INT);
				$this->stmt->bindParam(6, $censo_id, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $_POST['question_a'] == 2 ) {
				$coord_t	= $_POST['coord_t'];
				$agrupamiento_t	= $_POST['agrupamiento_t'];
				$sql_rel = "
				UPDATE area_censo SET procedencia = 2, area = ?, id_direccion = 'NULL', id_coordinacion = ?, id_subdireccion = 'NULL', id_region = 'NULL', id_agrupamiento = ? WHERE id_censo = ?
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $coord_t, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $agrupamiento_t, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $censo_id, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $_POST['question_a2'] == 3 ) {
				$agrupamiento_cprs	= $_POST['agrupamiento_cprs'];	
				$sql_rel = "
				UPDATE area_censo SET procedencia = 1, area = ?, id_direccion = 'NULL', id_coordinacion = 'NULL', id_subdireccion = 'NULL', id_region = 'NULL', id_agrupamiento = ? WHERE id_censo = ?
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area2 , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $agrupamiento_cprs, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $censo_id, PDO::PARAM_INT);
				$this->stmt->execute();
			
			} else if ( $_POST['question_a'] == 4 ) {
				$niv1	= $_POST['niv1'];
				$niv2	= $_POST['niv2'];	
				$niv3	= $_POST['niv3'];
				$niv4	= $_POST['niv4'];
				$niv5	= $_POST['niv5'];
				$sql_rel = "
				UPDATE area_censo SET procedencia = 2, area = ?, id_direccion = ?, id_coordinacion = ?, id_subdireccion = ?, id_region =?, id_agrupamiento = ? WHERE id_censo = ?
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $niv1, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $niv2, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $niv3, PDO::PARAM_INT);
				$this->stmt->bindParam(5, $niv4, PDO::PARAM_INT);
				$this->stmt->bindParam(6, $niv5, PDO::PARAM_INT);
				$this->stmt->bindParam(7, $censo_id, PDO::PARAM_INT);
				$this->stmt->execute();
			}


		
			return json_encode( array('status'=>'success','message'=> 'SE EDITÓ EL CENSO DE MANERA EXITOSA.' ) );
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function saveEditRecomendacion()
	{
		try {


			if ( empty($_POST['ot_id']) ) {
				throw new Exception("SE DETECTO QUE LA ORDEN DE TRABAJO NO EXISTE. REGRESE AL LISTADO E INTENTE NUEVAMENTE.", 1);
			}else{
				$ot_id 	= $_POST['ot_id'];

				$this->sql = "SELECT id FROM datos_observaciones WHERE id_ot = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1,$ot_id,PDO::PARAM_STR);
				$this->stmt->execute();
				$datos = $this->stmt->fetchAll(PDO::FETCH_OBJ);


				if ( empty($datos) ) {
				throw new Exception("SE DETECTO QUE NO HAY INFORMACIÓN DE IRREGULARIDDES Y RECOMENDACIONES PARA LA ORDEN DE TRABAJO.", 1);
				}
			}
			
			$ot_id 	= $_POST['ot_id'];
			$fecha_envio	= $_POST['f_envio'];
			$fecha_acuse	= $_POST['f_acuse'];
			$oficio = ( isset($_POST['oficio_id']) ) ? $_POST['oficio_id'] : NULL ;	
			$destinatario	= mb_strtoupper($_POST['destinatario_ofi'],'utf-8');
			$cargo	= mb_strtoupper($_POST['cargo_remi'],'utf-8');	
			$asunto	= mb_strtoupper($_POST['asunto_ofi'],'utf-8');			
			$comentario	= mb_strtoupper($_POST['comentario'],'utf-8');
			$area	= $_POST['question_a'];	
			$area2	= $_POST['question_a2'];
			//$area	= $_POST['question_a'];	
			

			$this->sql = "UPDATE datos_observaciones SET
				f_envio = ?,
				oficio = ?,
				f_acuse = ?,
				destinatario = ?,
				comentario = ?,
				asunto = ?,
				f_limite = 'NULL' 
				WHERE id_ot = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$fecha_envio,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$oficio,PDO::PARAM_INT);			
			$this->stmt->bindParam(3,$fecha_acuse,PDO::PARAM_STR);
			$this->stmt->bindParam(4,$destinatario,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$comentario,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$asunto,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$ot_id,PDO::PARAM_INT);		
			$this->stmt->execute();

			if ( $_POST['question_a'] == 1 ) {
				$coord	= $_POST['coord'];	
				$subd	= $_POST['subd'];
				$region	= $_POST['region'];
				$agrupamiento	= $_POST['agrupamiento'];
				$sql_rel = "
				UPDATE area_recomendaciones SET procedencia = 2, area = ?, id_direccion = NULL, id_coordinacion = ?, id_subdireccion = ?, id_region =?, id_agrupamiento = ? WHERE id_ot = ?
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $coord, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $subd, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $region, PDO::PARAM_INT);
				$this->stmt->bindParam(5, $agrupamiento, PDO::PARAM_INT);
				$this->stmt->bindParam(6, $ot_id, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $_POST['question_a'] == 2 ) {
				$coord_t	= $_POST['coord_t'];
				$agrupamiento_t	= $_POST['agrupamiento_t'];
				$sql_rel = "
				UPDATE area_recomendaciones SET procedencia = 2, area = ?, id_direccion = NULL, id_coordinacion = ?, id_subdireccion = NULL, id_region = NULL, id_agrupamiento = ? WHERE id_ot = ?
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $coord_t, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $agrupamiento_t, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $ot_id, PDO::PARAM_INT);
				$this->stmt->execute();

			} else if ( $_POST['question_a2'] == 3 ) {
				$agrupamiento_cprs	= $_POST['agrupamiento_cprs'];	
				$sql_rel = "
				UPDATE area_recomendaciones SET procedencia = 1, area = ?, id_direccion = NULL, id_coordinacion = NULL, id_subdireccion = NULL, id_region = NULL, id_agrupamiento = ? WHERE id_ot = ?
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area2 , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $agrupamiento_cprs, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $ot_id, PDO::PARAM_INT);
				$this->stmt->execute();
			
			} else if ( $_POST['question_a'] == 4 ) {
				$niv1	= $_POST['niv1'];
				$niv2	= $_POST['niv2'];	
				$niv3	= $_POST['niv3'];
				$niv4	= $_POST['niv4'];
				$niv5	= $_POST['niv5'];
				$sql_rel = "
				UPDATE area_recomendaciones SET procedencia = 2, area = ?, id_direccion = ?, id_coordinacion = ?, id_subdireccion = ?, id_region =?, id_agrupamiento = ? WHERE id_ot = ?
				";
				$this->stmt = $this->pdo->prepare($sql_rel);
				$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
				$this->stmt->bindParam(2, $niv1, PDO::PARAM_INT);
				$this->stmt->bindParam(3, $niv2, PDO::PARAM_INT);
				$this->stmt->bindParam(4, $niv3, PDO::PARAM_INT);
				$this->stmt->bindParam(5, $niv4, PDO::PARAM_INT);
				$this->stmt->bindParam(6, $niv5, PDO::PARAM_INT);
				$this->stmt->bindParam(7, $ot_id, PDO::PARAM_INT);
				$this->stmt->execute();
			}		
		
			return json_encode( array('status'=>'success','message'=>'SE EDITARON LOS DATOS DE LAS IRREGULARIDADES Y RECOMENDACIONES DE MANERA EXITOSA') );
		} catch (Exception $e) {
			#if( $e->getCode()  ){}
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}


	public function saveActaAdministrativa()
	{
		try {

			$size = $_FILES['archivo']['size'];
			$type = $_FILES['archivo']['type'];
			$name = $_FILES['archivo']['name'];
			$destiny = $_SERVER['DOCUMENT_ROOT'].'/siqd/uploads/';
			
			if ( $size > 10485760 ) 
			{
				throw new Exception("EL ARCHIVO EXCEDE EL TAMAÑO ADMITIDO (10MB)", 1);
			}
			else
			{
				if ( $type != 'application/pdf' AND $type != 'image/png' AND $type != 'image/jpeg' ) 
				{
					throw new Exception("EL FORMATO DEL ARCHIVO ES INCORRECTO.", 1);
				}
				else
				{
					#convertir a bytes
					move_uploaded_file($_FILES['archivo']['tmp_name'],$destiny.$name);
					$file = fopen($destiny.$name,'r');
					$content = fread($file,$size);
					$content = addslashes($content);
					fclose($file);

					
					$fecha = $_POST['fecha'];						
					$hora	= $_POST['hora'];
					$motivo	= mb_strtoupper($_POST['motivo'],'utf-8');	
					//$conducta	= $_POST['conductas'];	
					//$presunto	= mb_strtoupper($_POST['presunto'],'utf-8');
					$area	= $_POST['question_a'];
					$area2	= $_POST['question_a2'];
					if ( !isset($_POST['censo_id']) OR empty($_POST['censo_id']) ) {
						$id_tipo = $_POST['ot_id'];
						$detipo = 2;
					} else if ( !isset($_POST['ot_id']) OR empty($_POST['ot_id']) ){
						$id_tipo 	= $_POST['censo_id'];
						$detipo 	= 1;
					}
					$full_name = mb_strtoupper($_POST['nombre'],'utf-8')." ".mb_strtoupper($_POST['ap_pat'],'utf-8')." ".mb_strtoupper($_POST['ap_mat'],'utf-8');

					


					#Insertar en la BD
					$this->sql = "
					INSERT INTO acta_admin(
						id,
						fecha, 
						hora,
						motivo,
						presunto,
						estatus,
						detipo,
						id_tipo) 
					VALUES ('',?,?,?,?,1,?,?);
					";
					$this->stmt = $this->pdo->prepare( $this->sql );
					$this->stmt->bindParam(1,$fecha,PDO::PARAM_STR);
					$this->stmt->bindParam(2,$hora,PDO::PARAM_STR);
					$this->stmt->bindParam(3,$motivo,PDO::PARAM_STR);
					$this->stmt->bindParam(4,$full_name,PDO::PARAM_STR);
					$this->stmt->bindParam(5,$detipo,PDO::PARAM_INT);
					$this->stmt->bindParam(6,$id_tipo,PDO::PARAM_INT);
					$this->stmt->execute();
					//unlink($destiny.$name);

					$ultimo = $this->pdo->lastInsertId();

					if ( $area == '1' ) {
						$coord	= $_POST['coord'];	
						$subd	= $_POST['subd'];
						$region	= $_POST['region'];
						$agrupamiento	= $_POST['agrupamiento'];
						$sql_rel = "
						INSERT INTO area_acta_admin (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_acta)
						VALUES ('',2,?,NULL,?,?,?,?,?);
						";
						$this->stmt = $this->pdo->prepare($sql_rel);
						$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
						$this->stmt->bindParam(2, $coord, PDO::PARAM_INT);
						$this->stmt->bindParam(3, $subd, PDO::PARAM_INT);
						$this->stmt->bindParam(4, $region, PDO::PARAM_INT);
						$this->stmt->bindParam(5, $agrupamiento, PDO::PARAM_INT);
						$this->stmt->bindParam(6, $ultimo, PDO::PARAM_INT);
						$this->stmt->execute();

					} else if ( $area == '2' ) {
						$coord_t	= $_POST['coord_t'];
						$agrupamiento_t	= $_POST['agrupamiento_t'];
						$sql_rel = "
						INSERT INTO area_acta_admin (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_acta)
						VALUES ('',2,?,NULL,?,NULL,NULL,?,?);
						";
						$this->stmt = $this->pdo->prepare($sql_rel);
						$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
						$this->stmt->bindParam(2, $coord_t, PDO::PARAM_INT);
						$this->stmt->bindParam(3, $agrupamiento_t, PDO::PARAM_INT);
						$this->stmt->bindParam(4, $ultimo, PDO::PARAM_INT);
						$this->stmt->execute();

					} else if ( $area2 == '3' ) {
						$agrupamiento_cprs	= $_POST['agrupamiento_cprs'];	
						$sql_rel = "
						INSERT INTO area_acta_admin (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_acta)
						VALUES ('',1,?,NULL,NULL,NULL,NULL,?,?);
						";
						$this->stmt = $this->pdo->prepare($sql_rel);
						$this->stmt->bindParam(1, $area2 , PDO::PARAM_INT);
						$this->stmt->bindParam(2, $agrupamiento_cprs, PDO::PARAM_INT);
						$this->stmt->bindParam(3, $ultimo, PDO::PARAM_INT);
						$this->stmt->execute();

					} else if ( $area == '4' ) {
						$niv1	= $_POST['niv1'];
						$niv2	= $_POST['niv2'];	
						$niv3	= $_POST['niv3'];
						$niv4	= $_POST['niv4'];
						$niv5	= $_POST['niv5'];

						$sql_rel = "
						INSERT INTO area_acta_admin (id, procedencia, area, id_direccion, id_coordinacion,id_subdireccion, id_region,id_agrupamiento,id_acta)
						VALUES ('',2,?,?,?,?,?,?,?);
						";
						$this->stmt = $this->pdo->prepare($sql_rel);
						$this->stmt->bindParam(1, $area , PDO::PARAM_INT);
						$this->stmt->bindParam(2, $niv1, PDO::PARAM_INT);
						$this->stmt->bindParam(3, $niv2, PDO::PARAM_INT);
						$this->stmt->bindParam(4, $niv3, PDO::PARAM_INT);
						$this->stmt->bindParam(5, $niv4, PDO::PARAM_INT);
						$this->stmt->bindParam(6, $niv5, PDO::PARAM_INT);
						$this->stmt->bindParam(7, $ultimo, PDO::PARAM_INT);
						$this->stmt->execute();
					}

					/*for($i = 0; $i < count($_POST['conductas']); $i++ ){
						$conducta = $_POST['conductas'][$i];
						$this->sql = " INSERT INTO p_conductas_admin (id,acta_id,conducta_id) VALUES ('',?,?); ";
						$this->stmt = $this->pdo->prepare($this->sql);
						$this->stmt->bindParam(1,$ultimo,PDO::PARAM_INT);
						$this->stmt->bindParam(2,$conducta,PDO::PARAM_INT);
						$this->stmt->execute();
					}*/


					$this->sql = "
					INSERT INTO documentos_acta_admin(
						id, 
						formato,
						archivo,
						acta_id) 
					VALUES ('',?,?,?);
					";
					$this->stmt = $this->pdo->prepare( $this->sql );
					
					$this->stmt->bindParam(1,$type,PDO::PARAM_STR);
					$this->stmt->bindParam(2,$content,PDO::PARAM_LOB);
					$this->stmt->bindParam(3,$ultimo, PDO::PARAM_INT);
					
					$this->stmt->execute();
					unlink($destiny.$name);

					return json_encode(array('status'=>'success','message'=>'Se registró el acta administrativa.' ));
				}
			}

		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function getListActaAdmin()
	{
		try {
			
			$admin = array();
			$anexgrid = new AnexGrid();
			$wh = " 1=1 ";
			
			foreach ($anexgrid->filtros as $filter) {
				if ($filter['columna'] == 'ofi' || $filter['columna'] == 'asunto' || $filter['columna'] == 'nombre'|| $filter['columna'] == 'observaciones' || $filter['columna'] == 'destinatario') {
					$wh .= " AND ".$filter['columna'] ." LIKE '%".$filter['valor']."%'";
					
				}else{
					$wh .= " AND ".$filter['columna'] ." = ".$filter['valor'];
				}
				
			}

			$this->sql = "SELECT * FROM acta_admin
				WHERE $wh ORDER BY $anexgrid->columna $anexgrid->columna_orden LIMIT $anexgrid->pagina , $anexgrid->limite";			
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);


			$this->sql = "SELECT 
			count(*) as total
			FROM acta_admin 
			WHERE $wh";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$total = $this->stmt->fetch(PDO::FETCH_OBJ);
			$total = $total->total;

			//$total = $this->stmt->rowCount();

			$aux = array();
			foreach ($this->result as $key => $adm) {

				$aux['id'] = $adm->id;
				$aux['fecha'] = $adm->fecha;
				$aux['hora'] = $adm->hora;
				$aux['motivo'] = $adm->motivo;
				//$aux['conducta'] = $adm->conducta;
				$aux['presunto'] = $adm->presunto;
				$aux['detipo'] = $adm->detipo;
				$aux['estatus'] = $adm->estatus;


			$sql_expediente = "SELECT cve_exp AS exp FROM quejas AS q 
				WHERE acta_admin = ?";
				//$this->stmt = $this->pdo->prepare($sql_oficios);
				$this->stmt = $this->getPDO()->prepare($sql_expediente);
				$this->stmt->bindParam(1,$adm->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$expedientes = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['expedientes'] = $expedientes;

			$sql_conductas = "SELECT cc.id,cc.nombre  FROM p_conductas_admin AS pc
				INNER JOIN catalogo_conductas AS cc ON cc.id = pc.conducta_id
				WHERE pc.acta_id = ?";
				$this->stmt = $this->pdo->prepare($sql_conductas);
				$this->stmt->bindParam(1,$adm->id,PDO::PARAM_INT);
				$this->stmt->execute();
				$conductas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['conductas'] = $conductas;
				

			/*	$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
				$sql_oficios = "SELECT og.no_oficio AS ofi FROM oficios_generados AS og 
				WHERE og.id_generado = ?";
				$this->stmt = $this->pdo->prepare($sql_oficios);
				$this->stmt->bindParam(1,$cen->no_oficio,PDO::PARAM_INT);
				$this->stmt->execute();
				$oficios = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['oficios'] = $oficios;
				*/
				
				array_push($admin, $aux);
			}
			
			return $anexgrid->responde($admin,$total);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}


	public function EnviarActa()
	{
		try {
			$id = $_POST['acta_id'];
			$f_oficio = $_POST['f_oficio'];
			$f_recepcion = $_POST['f_recepcion'];
			$destinatario	= mb_strtoupper($_POST['destinatario_ofi'],'utf-8');
			$asunto	= mb_strtoupper($_POST['asunto_ofi'],'utf-8');
			$oficio = ( isset($_POST['oficio_id']) ) ? $_POST['oficio_id'] : NULL ;
			$cargo	= mb_strtoupper($_POST['cargo_remi'],'utf-8');
			

			$this->sql = "
				UPDATE acta_admin SET estatus = 2, f_oficio = ?, f_recepcion = ?, oficio = ?, destinatario = ?, cargo = ?, asunto = ?  WHERE id = ?
			";
			$this->stmt = $this->pdo->prepare( $this->sql );			
			$this->stmt->bindParam(1,$f_oficio,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$f_recepcion,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$oficio,PDO::PARAM_INT);
			$this->stmt->bindParam(4,$destinatario,PDO::PARAM_STR);
			$this->stmt->bindParam(5,$cargo,PDO::PARAM_STR);
			$this->stmt->bindParam(6,$asunto,PDO::PARAM_STR);
			$this->stmt->bindParam(7,$id,PDO::PARAM_INT);

			$this->stmt->execute();
			
			return json_encode( array('message'=>'ACTA ENVIADA A QUEJAS Y DENUNCIAS')  );	
		} catch (Exception $e) {
			return json_encode( array( 'message'=>$e->getMessage() ) );
		}
	}


	public function CancelarActa()
	{
		try {
			$id = $_POST['id'];

			$this->sql = "
				UPDATE acta_admin SET estatus = 3 WHERE id = ?
			";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->bindParam(1,$id);
			$this->stmt->execute();
			
			return json_encode( array('message'=>'success')  );	
		} catch (Exception $e) {
			return json_encode( array( 'message'=>$e->getMessage() ) );
		}
	}

	public function generateReporteI()
	{
		try {
			$f_ini	= $_POST['f_ini'];						
			$f_fin	= $_POST['f_fin'];

			$tabla = array();



			$this->sql = "SELECT id_ot, do.oficio, t_orden, (SELECT count(recomendacion) as total
			FROM recomendaciones WHERE id_observa IN (1,2)) AS recomendaciones, o.clave, o.oficio_id, a.oficio AS acta, a.detipo FROM datos_observaciones AS do
            LEFT JOIN orden_inspeccion AS o ON o.id = id_ot
            LEFT JOIN acta_admin AS a ON a.id_tipo = id_ot AND a.detipo = 'ORDEN'
             WHERE DATE(f_envio) BETWEEN ? AND ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$f_ini,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$f_fin,PDO::PARAM_STR);
			$this->stmt->execute();
    		$orden = $this->stmt->fetchAll( PDO::FETCH_OBJ);
    		$tabla['orden'] = $orden;
				$oi = array();
					foreach ($orden as $key => $o) {
						array_push($oi, $o ->oficio_id);

						$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
						$sql_ofi_ins = "SELECT og.id_generado, og.no_oficio FROM oficios_generados AS og
						LEFT JOIN oficios_generados_referencia AS ref ON  ref. id_generado = og.id_generado
						WHERE og.id_generado = ?";
						//$this->stmt = $this->getPDO()->prepare($sql_ofi_ins);
						$this->stmt = $this->pdo->prepare($sql_ofi_ins);
						$this->stmt->bindParam(1,$o->oficio_id,PDO::PARAM_STR);
						$this->stmt->execute();
						$ofi_ins = $this->stmt->fetch(PDO::FETCH_OBJ);
						$tabla['ofi_ins'][$key] = $ofi_ins;
					}
				$oi = implode(',',$oi); 

					$ob = array();
					foreach ($orden as $key => $o) {
						array_push($ob, $o ->oficio_id);

				
						$sql_ofi_ins2 = "SELECT og.id_generado, ref.referencia FROM oficios_generados AS og
						LEFT JOIN oficios_generados_referencia AS ref ON  ref. id_generado = og.id_generado
						WHERE og.id_generado = ?";
						//$this->stmt = $this->getPDO()->prepare($sql_ofi_ins);
						$this->stmt = $this->pdo->prepare($sql_ofi_ins2);
						$this->stmt->bindParam(1,$o->oficio_id,PDO::PARAM_STR);
						$this->stmt->execute();
						$ofi_ins2 = $this->stmt->fetch(PDO::FETCH_OBJ);
						$tabla['ofi_ins2'][$key] = $ofi_ins2;
					}
				$ob = implode(',',$ob);


				$or = array();
					foreach ($orden as $key => $o) {
						array_push($or, $o ->oficio);
					}
				$or = implode(',',$or);


				$ot = array();
					foreach ($orden as $key => $o) {

						array_push($ot, $o ->id_ot);									

						$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
						$sql_num_rec = "SELECT  id_ot, count(id) as total  FROM observaciones  WHERE id_ot = ?";
						//$this->stmt = $this->getPDO()->prepare($sql_num_rec);
						$this->stmt = $this->pdo->prepare($sql_num_rec);
						$this->stmt->bindParam(1,$o->id_ot,PDO::PARAM_STR);
						$this->stmt->execute();
						$total = $this->stmt->fetch(PDO::FETCH_OBJ);	
						$tabla['total'][$key] = $total;

							

					}
				$ot = implode(',',$ot);

				$ad = array();
					foreach ($orden as $key => $o) {
						array_push($ad, $o ->acta);
					}
				$ad = implode(',',$ad);


			#nÚMERO DE OFICIO Y EXPEDIENTE DE LA ORDEN DE TRABAJO	
			/*$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
			$sql_ofi_ins = "SELECT og.id_generado, og.no_oficio, ref.referencia FROM oficios_generados AS og
			LEFT JOIN oficios_generados_referencia AS ref ON  ref. id_generado = og.id_generado
			WHERE og.id_generado IN ($oi)";
			$this->stmt = $this->getPDO()->prepare($sql_ofi_ins);
			$this->stmt->execute();
			$ofi_ins = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$tabla['ofi_ins'] = $ofi_ins;*/


			#NÚMERO DE OFICIO DE LAS RECOMENDACIONES
			$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
			$sql_ofi_rec = "SELECT og.id_generado, og.no_oficio FROM oficios_generados AS og
						WHERE og.id_generado IN ($or)";
			$this->stmt = $this->getPDO()->prepare($sql_ofi_rec);
			$this->stmt->execute();
			$ofi_rec = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$tabla['ofi_rec'] = $ofi_rec;

			#FECHA DE NOTIFICACION Y VENCIMIENTO DE LAS RECOMENDACIONES
			$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
			$sql_fec_rec = "SELECT id_ot, f_acuse, (select DATE_ADD(f_acuse,INTERVAL 15 DAY)) AS vencimiento FROM datos_observaciones WHERE DATE(f_envio) BETWEEN ? AND ?";
			$this->stmt = $this->pdo->prepare($sql_fec_rec);
			$this->stmt->bindParam(1,$f_ini,PDO::PARAM_STR);
			$this->stmt->bindParam(2,$f_fin,PDO::PARAM_STR);
			$this->stmt->execute();
    		$fechas = $this->stmt->fetchAll( PDO::FETCH_OBJ);
    		$tabla['fechas'] = $fechas;



    		$sql_obs_rec = "SELECT id_ot, comentario FROM datos_observaciones WHERE id_ot IN ($ot)";
			$this->stmt = $this->getPDO()->prepare($sql_obs_rec);
			$this->stmt->execute();
			$obs_rec = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$tabla['obs_rec'] = $obs_rec;				

    		

    		#INFO DE LA RESPUESTA
			$sql_resp = "SELECT id_ot, fecha FROM info_seguimiento WHERE id_ot IN ($ot)";
			$this->stmt = $this->getPDO()->prepare($sql_resp);
			$this->stmt->execute();
			$resp = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$tabla['resp'] = $resp;

			#INFO DE LA RESPUESTA
			$sql_resp1 = "SELECT id_ot, estatus FROM info_seguimiento WHERE id_ot IN ($ot)";
			$this->stmt = $this->getPDO()->prepare($sql_resp1);
			$this->stmt->execute();
			$resp1 = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$tabla['resp1'] = $resp1;

			#INFO DE LA RESPUESTA
			$sql_resp2 = "SELECT id_ot, observaciones FROM info_seguimiento WHERE id_ot IN ($ot)";
			$this->stmt = $this->getPDO()->prepare($sql_resp2);
			$this->stmt->execute();
			$resp2 = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$tabla['resp2'] = $resp2;


			#INFO DEL ACTA ADMINISTRATIVA
			$sql_acta = "SELECT id_tipo, oficio, CONCAT('Fecha:',fecha,' / Motivo: ',motivo,' / Estatus: ',estatus) AS acta FROM acta_admin WHERE detipo = 'ORDEN' AND id_tipo IN ($ot)";
			$this->stmt = $this->getPDO()->prepare($sql_acta);
			$this->stmt->execute();
			$acta = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$tabla['acta'] = $acta;

			
			/*#OFICIO DEL ACTA ADMINISTRATIVA			
			$sql_of_acta = "SELECT og.id_generado, og.no_oficio FROM oficios_generados AS og
						WHERE og.id_generado IN ($ad)";
			$this->stmt = $this->getPDO()->prepare($sql_of_acta);
			$this->stmt->execute();
			$of_acta = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$tabla['of_acta'] = $of_acta;*/



			return json_encode($tabla);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}


	public function getAcuse_Acta($file)
	{
		try {
			$this->sql = "SELECT archivo FROM documentos_acta_admin WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$file,PDO::PARAM_INT);
			$this->stmt->execute();
			$doc = $this->stmt->fetch(PDO::FETCH_OBJ);
			echo stripslashes($doc->archivo);
			
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}

	public function getAcuse_OIN($file)
	{
		try {
			$this->sql = "SELECT archivo FROM documentos_oin WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$file,PDO::PARAM_INT);
			$this->stmt->execute();
			$doc = $this->stmt->fetch(PDO::FETCH_OBJ);
			echo stripslashes($doc->archivo);
			
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}

	public function saveComentario()
	{
		try {

			$ot 	= $_POST['ot_id'];
			$comment = "";

			
			#buscar que tenga commentarios
			$this->sql = "SELECT comentario FROM orden_inspeccion WHERE id = ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$ot,PDO::PARAM_INT);
			$this->stmt->execute();
			$past_comment = $this->stmt->fetch(PDO::FETCH_OBJ);

			if ( is_null($past_comment->comentario) ) 
			{
				$comment = "";
			}else{
				$comment = $past_comment->comentario." <br> ";
			}
			
			$estado = $_POST['estado'];
			$comment .= $_POST['txt_complemento'];
			$comment .= $_POST['observaciones'];
			$comment .= "<br>";
			$comment = mb_strtoupper($comment);

			$this->sql = "UPDATE orden_inspeccion SET 
				estatus = ? ,
				comentario = ?
				WHERE id = ?
			";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$estado,PDO::PARAM_INT);
			$this->stmt->bindParam(2,$comment,PDO::PARAM_STR);
			$this->stmt->bindParam(3,$ot,PDO::PARAM_INT);
			$this->stmt->execute();

			return json_encode(array('estado'=>'success','message'=>'Comentario agregado correctamente'));
				
			
		} catch (Exception $e) {
			return json_encode(array('status'=>'error','message'=>$e->getMessage() ));
		}
	}

	public function getDashboard_OT()
	{
		try {
			
			$año = $_POST['year'];

			/*if($_POST['year'] != NULL){
				$año = $_POST['year'];
				$wh = "AND YEAR(f_creacion)" = .$año.;
			} else {
				$wh = " ";
			}
*/
			$this->sql 	= "SELECT t_orden, COUNT(t_orden) AS cuenta FROM orden_inspeccion 
			#WHERE f_creacion > '2019-06-30'
			WHERE YEAR(f_creacion) = $año
			GROUP BY t_orden";
			$this->stmt 	= $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result 	= $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);

		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}


	public function getCedulaCenso($id)
	{
		try {
			$this->sql = "SELECT * FROM censo WHERE id = ?";
			$this->stmt = $this->getPDO()->prepare($this->sql);
			$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			$aux = array();
			$cen = array();
			foreach ($this->result as $key => $censo) {
				$aux['id'] = $censo->id;
				$aux['tipo_envio'] = $censo->tipo_envio;
				$aux['f_envio'] = $censo->f_envio;
				$aux['no_oficio'] = $censo->no_oficio;
				$aux['f_acuse'] = $censo->f_acuse;
				$aux['destinatario'] = $censo->destinatario;
				$aux['observaciones'] = $censo->observaciones;
				$aux['asunto'] = $censo->asunto;
				$aux['f_limite'] = $censo->f_limite;


				#OFICIO DE LA SOLICITUD
				$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$this->sql = "SELECT no_oficio FROM oficios_generados 
				WHERE id_generado = ?
				";
				$this->stmt = $this->getPDO()->prepare($this->sql);
				$this->stmt->bindParam(1,$censo->no_oficio,PDO::PARAM_INT);
				$this->stmt->execute();
				$sol_censo = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['sol_censo'] = $sol_censo;	


				#Agregar seguimiento de respuestas
				$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_segui = "SELECT tipo_envio, f_oficio, no_oficio, remitente, cargo, asunto, recibe, CONCAT(fecha_desde,' - ',fecha_hasta) AS periodo  FROM info_respuesta WHERE id_censo = ?";
				$this->stmt = $this->pdo->prepare($sql_segui);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$seguimiento = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['seguimiento'] = $seguimiento;

				$aseg = array();
					foreach ($seguimiento as $key => $s) {
						array_push($aseg, $s ->no_oficio);
					}
				$aseg = implode(',',$aseg);

				$arecibe = array();
					foreach ($seguimiento as $key => $s) {
						array_push($arecibe, $s ->recibe);
					}
				$arecibe = implode(',',$arecibe);

				#Número de oficio del seguimiento
				$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_ofi_segui = "SELECT oficio_respuesta AS ofi_resp FROM oficios_generadosrespuesta WHERE id_respuesta = $aseg";
				$this->stmt = $this->getPDO()->prepare($sql_ofi_segui);
				$this->stmt->execute();
				$ofi_seguimiento = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['ofi_seguimiento'] = $ofi_seguimiento;

				$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_rec_segui = "SELECT CONCAT(nombre,' ',ap_pat,' ',ap_mat) AS nom_completo FROM personal WHERE id = $arecibe";
				$this->stmt = $this->getPDO()->prepare($sql_rec_segui);
				$this->stmt->execute();
				$recibe_seguimiento = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['recibe_seguimiento'] = $recibe_seguimiento;

				#AREA DEL CENSO
				$sql_area_c = "SELECT area, 
				CASE 
					WHEN area = 'Operativos Secretaría de Seguridad' THEN (SELECT c.nombre AS nombre FROM area_censo AS a INNER JOIN catalogo_agrupamientos AS c ON c.id = a.id_agrupamiento WHERE id_censo = ?)
					WHEN area = 'Dirección de Policía de Tránsito' THEN (SELECT c.nombre AS nombre FROM area_censo AS a INNER JOIN catalogo_transito_agrupamieno AS c ON c.id = a.id_agrupamiento WHERE id_censo = ?)
					WHEN area = 'Dirección General de Prevención y Reinserción Social' THEN (SELECT c.nombre AS nombre FROM area_censo AS a INNER JOIN catalogo_dgprs AS c ON c.id = a.id_agrupamiento WHERE id_censo = ?)
					WHEN area = 'Personal Administrativo' THEN (SELECT c.nombre AS nombre FROM area_censo AS a INNER JOIN catalogo_nivel5_admini AS c ON c.id = a.id_agrupamiento WHERE id_censo = ?)
				ELSE 0
				END AS nombre
				FROM area_censo WHERE id_censo = ?";
				$this->stmt = $this->getPDO()->prepare($sql_area_c);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->bindParam(2,$id,PDO::PARAM_INT);
				$this->stmt->bindParam(3,$id,PDO::PARAM_INT);
				$this->stmt->bindParam(4,$id,PDO::PARAM_INT);
				$this->stmt->bindParam(5,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$nom_area_c = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['nom_area_c'] = $nom_area_c;

				#EXPEDIENTE RELACIONADO
				$sql_expediente_rel = "SELECT cve_exp AS exp, t_asunto, e.comentarios, e.oficio FROM quejas AS q 
				INNER JOIN relacion_censo_expediente AS e ON q.id = e.expediente_id
				WHERE e.censo_id = $id";
				$this->stmt = $this->getPDO()->prepare($sql_expediente_rel);
				$this->stmt->execute();
				$expediente_censo = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['expediente_censo'] = $expediente_censo;

				$of = array();
					foreach ($expediente_censo as $key => $s) {
						array_push($of, $s ->oficio);
					}
				$of = implode(',',$of);

				#Número de oficio del seguimiento
				$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_ofi_segui = "SELECT no_oficio, fecha_oficio FROM oficios_generados 
				WHERE id_generado = $of";
				$this->stmt = $this->getPDO()->prepare($sql_ofi_segui);
				$this->stmt->execute();
				$ofi_expediente = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['ofi_expediente'] = $ofi_expediente;





				#***************************************************************
				#****************************************************************			

				#ACTAS ADMINSTRATIVAS
				$sql_adm = "SELECT * FROM acta_admin WHERE detipo = 'CENSO' AND id_tipo = ?";
				$this->stmt = $this->pdo->prepare($sql_adm);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$acta_admin = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['acta_admin'] = $acta_admin;


				$a = array();
					foreach ($acta_admin as $key => $ac) {
						array_push($a, $ac ->id);
					}
				$a = implode(',',$a);

				$aofi = array();
					foreach ($acta_admin as $key => $ac) {
						array_push($aofi, $ac ->oficio);
					}
				$aofi = implode(',',$aofi);

				#EXPEDIENTE DEL ACTA ADMINISTRATIVA
				$sql_expediente = "SELECT cve_exp AS exp FROM quejas AS q 
				WHERE acta_admin = $a";
				$this->stmt = $this->getPDO()->prepare($sql_expediente);
				$this->stmt->execute();
				$expedientes = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['expedientes'] = $expedientes;

				#Buscar las presuntas conductas de cada expediente
				$sql_conductas = "SELECT pc.id AS id_presunta, cc.id,cc.nombre,l.nombre AS n_ley  FROM p_conductas_admin AS pc
				INNER JOIN catalogo_conductas AS cc ON cc.id = pc.conducta_id
	            INNER JOIN leyes AS l ON l.id = cc.ley_id
				WHERE pc.acta_id = $a";
				$this->stmt = $this->pdo->prepare($sql_conductas);
				//$this->stmt->bindParam(1,$qd->id,PDO::PARAM_INT);
				$this->stmt = $this->getPDO()->prepare($sql_conductas);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ) {
					$conductas = $this->stmt->fetchAll(PDO::FETCH_OBJ);
					$aux['conductas'] = $conductas;
				}else{
					$aux['conductas'] = array();
				}

				#OFICIO DEL ACTA ADMINISTRATIVA
				$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
				$sql_ofi_ac = "SELECT no_oficio FROM oficios_generados 
				WHERE id_generado = $aofi";
				$this->stmt = $this->getPDO()->prepare($sql_ofi_ac);
				$this->stmt->execute();
				$ofi_acta = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['ofi_acta'] = $ofi_acta;			


				$this->setPDO(new PDO('mysql:dbname=db_siqd;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));	
				
				#Agregar archivos 
				$sql_files_a = "SELECT *
				FROM documentos_acta_admin WHERE acta_id = $a";
				$this->stmt = $this->pdo->prepare($sql_files_a);
				$this->stmt->execute();
				$archivos_ac = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['archivos_ac'] = $archivos_ac;

				#AREA DEL ACTA ADMINISTRATIVA
				$sql_area = "SELECT area, 
				CASE 
					WHEN area = 'Operativos Secretaría de Seguridad' THEN (SELECT c.nombre AS nombre FROM area_acta_admin AS a INNER JOIN catalogo_agrupamientos AS c ON c.id = a.id_agrupamiento WHERE id_acta = $a)
					WHEN area = 'Dirección de Policía de Tránsito' THEN (SELECT c.nombre AS nombre FROM area_acta_admin AS a INNER JOIN catalogo_transito_agrupamieno AS c ON c.id = a.id_agrupamiento WHERE id_acta = $a)
					WHEN area = 'Dirección General de Prevención y Reinserción Social' THEN (SELECT c.nombre AS nombre FROM area_acta_admin AS a INNER JOIN catalogo_dgprs AS c ON c.id = a.id_agrupamiento WHERE id_acta = $a)
					WHEN area = 'Personal Administrativo' THEN (SELECT c.nombre AS nombre FROM area_acta_admin AS a INNER JOIN catalogo_nivel5_admini AS c ON c.id = a.id_agrupamiento WHERE id_acta = $a)
				ELSE 0
				END AS nombre
				FROM area_acta_admin WHERE id_acta = $a";
				$this->stmt = $this->getPDO()->prepare($sql_area);
				$this->stmt->execute();
				$nom_area = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['nom_area'] = $nom_area;

				#Agregar archivos 
				$sql_files = "SELECT *
				FROM documentos_obs AS d 
				WHERE d.ot_id = ?";
				$this->stmt = $this->pdo->prepare($sql_files);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$archivos = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['archivos'] = $archivos;

				#Agregar recordatorios
				$sql_recor = "SELECT f_acuse, f_recordatorio, tipo_envio,  no_oficio, destinatario, asunto, f_limite, observaciones FROM recordatorio WHERE id_censo = ?";
				$this->stmt = $this->pdo->prepare($sql_recor);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$recordatorios = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['recordatorios'] = $recordatorios;

			

				#AREA DEL REGISTRO DE RECOMENDACIONES
				$sql_area = "SELECT area FROM area_recomendaciones WHERE id_ot = ?";
				$this->stmt = $this->pdo->prepare($sql_area);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$area = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['area'] = $area;




				


				#REGISTRO DE ENVÍO DE RECOMENDACIONES
				$sql_censo = "SELECT * FROM censo WHERE id = ?";
				$this->stmt = $this->pdo->prepare($sql_censo);
				$this->stmt->bindParam(1,$id,PDO::PARAM_INT);
				$this->stmt->execute();
				$censo = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['censo'] = $censo;

				/*$sol = array();
					foreach ($censo as $key => $soli) {
						array_push($sol, $soli ->oficio);
					}
				$sol = implode(',',$sol);

				#OFICIO DEL REGISTRO DE ENVÍO DE RECOMENDACIONES
				$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root',''));
				$sql_ofi_sol = "SELECT no_oficio FROM oficios_generados 
				WHERE id_generado = $sol";
				$this->stmt = $this->getPDO()->prepare($sql_ofi_sol);
				$this->stmt->execute();
				$ofi_sol = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				$aux['ofi_sol'] = $ofi_sol;*/

				array_push($cen, $aux);
			}

			return $cen;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

	public function generateReporteOT()
	{
		try {
			$wh = " ";
			if ( !empty($_POST['f_ini']) AND !empty($_POST['f_fin']) ) {
				$f_ini = $_POST['f_ini'] ;
				$f_fin = $_POST['f_fin'] ;
				$wh .= "  AND ot.f_creacion BETWEEN '$f_ini' AND '$f_fin' ";
			}
			if ( !empty($_POST['t_orden']) ) {
				$t_orden = $_POST['t_orden'] ;
				$wh .= " AND ot.t_orden = $t_orden ";
			}
			if ( !empty($_POST['estatus']) ) {
				$estatus = $_POST['estatus'] ;
				$wh .= " AND ot.estatus = $estatus ";
			}
			if ( !empty($_POST['clave']) ) {
				$clave = $_POST['clave'] ;
				$wh .= " AND ot.clave LIKE '%$clave%' ";
			}	
			if ( !empty($_POST['question_p']) ) {
				$procedencia = $_POST['question_p'] ;
				$wh .= " AND aa.procedencia =$procedencia ";
			}
			if ( !empty($_POST['question_a']) ) {
				$area = $_POST['question_a'] ;
				//$wh .= " AND aa.area LIKE '%$area%' ";
				$wh .= " AND aa.area = $area ";
			}
			if ( !empty($_POST['question_a2']) ) {
				$area = $_POST['question_a2'] ;
				$wh .= " AND aa.area = $area ";
			}
			if ( !empty($_POST['agrupamiento_cprs']) ) {
				$agrupamiento = $_POST['agrupamiento_cprs'] ;
				$wh .= " AND aa.id_agrupamiento = $agrupamiento ";
			}	
			if ( !empty($_POST['agrupamiento']) ) {
				$agrupamiento = $_POST['agrupamiento'] ;
				$wh .= " AND aa.id_agrupamiento = $agrupamiento ";
			}
			if ( !empty($_POST['agrupamiento_t']) ) {
				$agrupamiento = $_POST['agrupamiento_t'] ;
				$wh .= " AND aa.id_agrupamiento = $agrupamiento ";
			}
			if ( !empty($_POST['niv5']) ) {
				$agrupamiento = $_POST['niv5'] ;
				$wh .= " AND aa.id_agrupamiento = $agrupamiento ";
			}			
			if ( !empty($_POST['municipio']) ) {
				$municipio  = $_POST['municipio'];
				$wh .= " AND a.municipio_id = $municipio ";
			}	

						
			$this->sql = "SELECT ot.*, a.acciones, m.nombre, aa.procedencia, aa.area
			FROM orden_inspeccion AS ot 
			LEFT JOIN acciones_ot AS a ON a.ot_id = ot.id
			LEFT JOIN municipios AS m ON m.id = a.municipio_id
			LEFT JOIN area_acciones AS aa ON aa.id_acciones = a.id 
			WHERE  1=1 ".$wh;
			//print_r($this->sql);exit;
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$docs = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			
			return json_encode($docs);
		} catch (Exception $e) {
			return json_encode( array( 'status'=>'error','message'=>$e->getMessage() ) );
		}
	}

		public function getPersonalApoyo()
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
			WHERE CONCAT(nombre,' ',ap_pat,' ',ap_mat) LIKE ? AND estado = 1 AND nivel = 'APOYO' $areas";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$text);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}


	public function getExpedientes()
	{
		try {
			$term = "%".$_REQUEST['term']."%";				
			$this->sql = "SELECT id, cve_exp AS value 
			FROM quejas WHERE cve_exp LIKE ? LIMIT 0,20		
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

	public function RelacionarExpediente()
	{
		try {
			$orden 	= $_POST['ot_id'];
			$expediente 	= $_POST['expediente_id'];
			$oficio 	= $_POST['oficio_id'];
			$comentario 	= $_POST['comentario'];
			//print_r($expediente);
			//exit;
			$this->sql = "INSERT INTO relacion_ot_expediente (id,orden_id,expediente_id,comentarios,oficio) 
			VALUES (
				'',
				?,
				?,
				?,
				?);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$orden);
			$this->stmt->bindParam(2,$expediente);
			$this->stmt->bindParam(3,$comentario);
			$this->stmt->bindParam(4,$oficio);
			$this->stmt->execute();

			return json_encode(array('estado'=>'success','message'=>'EXPEDIENTE RELACIONADO CORRECTAMENTE'));
		} catch (Exception $e) {
			return json_encode(array('estado'=>'error','message'=>$e->getMessage()));
		}
	}

	public function RelacionarExpedienteCenso()
	{
		try {
			$censo 	= $_POST['censo_id'];
			$expediente 	= $_POST['expediente_id'];
			$comentario 	= $_POST['comentario'];
			$oficio 	= $_POST['oficio_id'];
			//print_r($expediente);
			//exit;
			$this->sql = "INSERT INTO relacion_censo_expediente (id,censo_id,expediente_id,comentarios,oficio) 
			VALUES (
				'',
				?,
				?,
				?,
				?);";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$censo);
			$this->stmt->bindParam(2,$expediente);
			$this->stmt->bindParam(3,$comentario);
			$this->stmt->bindParam(4,$oficio);
			$this->stmt->execute();

			return json_encode(array('estado'=>'success','message'=>'EXPEDIENTE RELACIONADO CORRECTAMENTE'));
		} catch (Exception $e) {
			return json_encode(array('estado'=>'error','message'=>$e->getMessage()));
		}
	}



	
}
?>