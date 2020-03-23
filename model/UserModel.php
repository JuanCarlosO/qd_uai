<?php 
/**
 * Modelo para los Usuarios 
 */
#include_once 'Connection.php';
#include_once 'anexgrid.php';
class UserModel extends Connection
{
	public $sql;
	public $stmt;
	public $result;
	public function getPersonByID()
	{
		try {
			$this->sql = "SELECT id, CONCAT(nombre,' ',ap_pat,' ',ap_mat) AS full_name FROM personal ORDER BY id DESC";
			$this->stmt = $this->pdo->prepare($this->sql);
			$this->stmt->execute();
			$this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
			return json_encode($this->result);
		} catch (Exception $e) {
			return json_encode( array('status'=>'error','message'=>$e->getMessage()) );
		}
	}

}
?>