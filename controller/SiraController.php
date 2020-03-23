<?php
/**
 * Metodos del Sistema de registro de actas
 */
include_once '../model/SiraModel.php';
class SiraController
{
	protected $model;
	function __construct()
	{
		$this->model = new SiraModel();
	}
	public function getAreas()
	{
		return $this->model->getAreas();
	}
	public function getOINs()
	{
		return $this->model->getOINs();
	}
	public function saveActa()
	{
		return $this->model->saveActa();
	}
	public function getActas()
	{
		return $this->model->getActas();
	}
	public function saveDocActa()
	{
		return $this->model->saveDocActa();
	}
	public function editActa()
	{
		return $this->model->editActa();
	}
	public function savePresuntoR()
	{
		return $this->model->savePresuntoR();
	}
	
}
?>