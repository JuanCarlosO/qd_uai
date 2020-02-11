<?php
/**
 * Controlador de Usuarios 
 */
include_once '../model/UserModel.php';
class UserController
{
	protected $model;
	function __construct()
	{
		$this->model = new UserModel();
	}
}
?>