<?php
/**
 * Controlador de la UAI
 */
include_once '../model/UAIModel.php';
class UAIController
{
	protected $model;
	function __construct()
	{
		$this->model = new UAIModel();
	}
	public function getDashboard()
	{
		return $this->model->getDashboard();
	}
	public function getClavesExp()
	{
		return $this->model->getClavesExp();
	}
	public function getExpedientesEstado()
	{
		return $this->model->getExpedientesEstado();
	}
	public function getDashboardActas()
	{
		return $this->model->getDashboardActas();
	}
	public function getActasTipo()
	{
		return $this->model->getActasTipo();
	}
	public function getCoincidencias()
	{
		return $this->model->getCoincidencias();
	}
	
}
?>