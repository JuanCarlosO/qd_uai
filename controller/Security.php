<?php 
/**
 * Clase encargada en cifrar y descifrar passwords
 */
require_once '../model/Connection.php';
define('METHOD','AES-256-CBC');
define('SECRET_KEY','$J@M35$_');
define('SECRET_IV','22242522');

class Security 
{
	public $pdo ;
	public $pass;
	public $output;
	#Nuevas variables
	public $stmt;
	public $result;
	public $sql;
	function __construct()
	{
		#INICILIZAR LA VARIBLE DE CONEXION
		$aux = new Connection;
		$this->pdo = $aux->getPDO();
	}
	public function encrypt_pass($param)
	{
		$this->pass = $param;
		$this->output=FALSE;
		$key=hash('sha256', SECRET_KEY);
		$iv=substr(hash('sha256', SECRET_IV), 0, 16);
		$this->output=openssl_encrypt($this->pass, METHOD, $key, 0, $iv);
		$this->output=base64_encode($this->output);
		return $this->output;
	}

	public function decrypt_pass($param)
	{
		$this->pass = $param;
		$key=hash('sha256', SECRET_KEY); 
		$iv=substr(hash('sha256', SECRET_IV), 0, 16);
		$this->output=openssl_decrypt(base64_decode($this->pass), METHOD, $key, 0, $iv);
		return $this->output;
	}
	
	public function search_data_login($user, $password)
	{
		try {
			$sql = "SELECT u.*,p.nombre AS name, CONCAT(p.nombre, ' ',p.ap_pat,' ',p.ap_mat) AS n_completo, a.nombre AS n_area, a.id AS area_id FROM usuarios AS u 
			INNER JOIN personal AS p ON p.id = u.personal_id
			INNER JOIN areas AS a ON a.id = p.area_id
			WHERE  u.nick = ? AND u.estado = 1 LIMIT 1";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindParam(1,$user);
			$stmt->execute();
			$res = $stmt->fetch( PDO::FETCH_OBJ );
			if (empty($res)) {
				throw new Exception("Error: El nombre de usuario no existe.", 1);				
			}
			$pass_decrypt = $this->decrypt_pass($res->pass);
			
			if ( is_object($res) AND isset($res->pass)  ) 
			{
				if ( $pass_decrypt == $password ) {

					return $res;
				} else {
					throw new Exception("Error: La contraseña no coincide.");
				}
			}
			else
			{
				throw new Exception("Error: No existe el nombre de usuario");
			}
		} 
		catch (Exception $e) 
		{
			return  array('status'=>'error','message'=>$e->getMessage());
		}
	}

	public function getModulos($user)
	{
		# la siguiente funcion permite obtner los modulos de acceso de un usuario.
		try {
			$this->sql = "SELECT m.id,m.n_short,m.n_long FROM permisos AS p 
			INNER JOIN modulos AS m ON m.id = p.model_id
			WHERE user_id = ?";
			$this->stmt = $this->pdo->prepare( $this->sql );
			$this->stmt->execute(array($user));
			return $this->result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
		} catch (Exception $e) {
			return array('estado'=>'error','message'=>$e->getMessage);
		}
	}

	public function insert_user_DB($user,$pass)
	{
		#ESTA FUNCION, PERMITIRA DAR DE ALTA AL LOS USUARIOS EN LA BASE DE DATOS
		$stmt = $this->pdo->prepare('INSERT INTO REGISTRY (name, value) VALUES (?, ?)');
		$stmt->bindParam(1,$user);
		$stmt->bindParam(2,$pass);
		$stmt->execute();
		return 'Exitoso';
	}

}

/*$o = new Security;
echo $o->encrypt_pass('james2020');*/


