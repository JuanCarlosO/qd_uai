<?php 
/**
 * Modelo para consultar informacion del Registro anterior de QD
 */
include_once 'Connection.php';
#include_once 'anexgrid.php';
class QDOldModel extends Connection
{
	public $sql;
	public $stmt;
	public $result;
	function __construct()
	{
		$this->setPDO(new PDO('mysql:dbname=inspeccion;host=127.0.0.1;charset=utf8','root','7W+Th_+uTh2X'));
	}
	public function getCedula($clave)
	{
		try {
			$cve = "%".$clave."%";
			$cedula = array();
			#buscar 
			$this->sql = "SELECT q.*, e.estado_guarda AS n_estado, UPPER(m.municipio) AS n_municipio FROM queja AS q 
			INNER JOIN catalogo_estadoguarda AS e ON e.id_estadoguarda = q.estado_guarda
			INNER JOIN zona_municipios AS m ON m.id_municipio = q.hechos_Municipio
			WHERE num_expediente LIKE ?";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->bindParam(1,$cve,PDO::PARAM_STR);
			$this->stmt->execute();
			if ( $this->stmt->rowCount() > 0 ) {
				$this->result = $this->stmt->fetch(PDO::FETCH_OBJ);
				$cedula['id'] = $this->result->id_queja ;
				$cedula['f_hechos'] = $this->result->Fecha_que_Ocurrieron_los_Hechos ;
				$cedula['f_apertura'] = $this->result->Fecha_de_Folio_Registro ;
				$cedula['h_hechos'] = $this->result->hora_hechos ;
				$cedula['t_tramite'] = $this->result->tipo_tramite ;
				$cedula['articulo'] = $this->result->ley ;
				$cedula['n_municipio'] = $this->result->n_municipio ;
				$cedula['n_estado'] = $this->result->n_estado ;
				$cedula['d_hechos'] = $this->result->Descripcion_Completa_Hechos ;
				$direccion = "";
				if ( !empty($this->result->hechos_calle) ) {
					$direccion .= "EN ".$this->result->hechos_calle;
				}
				if ( !empty($this->result->hechos_calle1) ) {
					$direccion .= " ENTRE ".$this->result->hechos_calle1;
				}
				if ( !empty($this->result->hechos_calle2) ) {
					$direccion .= " Y ".$this->result->hechos_calle2;
				}
				
				$cedula['direccion'] = $direccion;
				$cedula['municipio'] = $this->result->n_municipio ;
				$conductas = explode('--!--',$this->result->Tipo_de_Infraccion);
				$cedula['conductas'] =  array_filter($conductas);#tipo de infraccion(es)
				$vias = explode('--!--', $this->result->Via_de_Recepcion);
				$cedula['vias'] = array_filter($vias);#via(s) de recepcion(es)
				if ( $this->result->prioridad_queja == '1' ) {
					$prioridad = "NORMAL";
				}elseif ( $this->result->prioridad_queja == '2' ) {
					$prioridad = "URGENTE";
				}else{
					$prioridad = "SIN PRIORIDAD REGISTRADA";
				}
				$cedula['prioridad'] = $prioridad;
				$cedula['estado'] = $this->result->n_estado ;
				$cedula['articulo'] = $this->result->ley ;
				#AGREGAR DATOS DEL QUEJOSO
				$this->sql = "SELECT * FROM quejoso
				WHERE id_queja = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1, $this->result->id_queja,PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ){
					$cedula['quejosos'] = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				}else{
					$cedula['quejosos'] = array();
				}
				#AGREGAR DATOS DEL PRESUNTO RESPONSABLE
				$this->sql = "SELECT * FROM presunto_responsable
				WHERE id_queja = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1, $this->result->id_queja,PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ){
					$cedula['p_responsables'] = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				}else{
					$cedula['p_responsables'] = array();
				}
				#UNIDAD IMPLICADA 
				$this->sql = "SELECT * FROM unidad_implicada
				WHERE id_queja = ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1, $this->result->id_queja,PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ){
					$cedula['unidades'] = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				}else{
					$cedula['unidades'] = array();
				}
				#UNIDAD ABOGADO RESPONSABLE 
				$this->sql = "SELECT e.*,p.nom_completo FROM expediente_turnoa AS e 
				INNER JOIN personal AS p ON p.id_person = e.turnado_a 
				WHERE e.id_queja= ?";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1, $this->result->id_queja,PDO::PARAM_INT);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ){
					$cedula['turnos'] = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				}else{
					$cedula['turnos'] = array();
				}
				#datos adicionales 
				$this->sql = "
				SELECT * FROM oficios_generados OG INNER JOIN oficios_generados_referencia OGR ON OG.`id_generado` = OGR.`id_generado` AND referencia LIKE ? ";
				$this->stmt = $this->pdo->prepare($this->sql);
				$this->stmt->bindParam(1, $cve ,PDO::PARAM_STR);
				$this->stmt->execute();
				if ( $this->stmt->rowCount() > 0 ){
					$cedula['oficios'] = $this->stmt->fetchAll(PDO::FETCH_OBJ);
				}else{
					$cedula['oficios'] = array();
				}
				
				/*$cedula['id'] = $this->result->id_queja ;
				$cedula['id'] = $this->result->id_queja ;*/
			}
			return $cedula;
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

}
?>