<?php 
/**
 * Modelo para los Usuarios 
 */
include_once 'Connection.php';
include_once 'anexgrid.php';
class UserModel extends Connection
{
	public $sql;
	public $stmt;
	public $result;
}
?>