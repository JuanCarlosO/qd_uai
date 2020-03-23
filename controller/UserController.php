<?php
/**
 * Controlador de Usuarios 
 */
#include_once '../model/UserModel.php';
include_once 'Security.php';
class UserController extends Security
{
	protected $model;
	function __construct()
	{
		$this->model = new Security();
	}

	public function validateAccess()
	{
		return $this->model->search_data_login($_POST['username'],$_POST['pass']);
	}

}
?>