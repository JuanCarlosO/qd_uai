<?php
/**
 * Controlador de la UAI
 */
include_once '../model/QDOldModel.php';
class QDOldController
{
	protected $model;
	function __construct()
	{
		$this->model = new QDOldModel();
	}
}
?>