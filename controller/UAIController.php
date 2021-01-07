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
	public function countSendCHyJ()
	{
		return $this->model->countSendCHyJ();
	}
	public function getExpByDemanda()
	{
		return $this->model->getExpByDemanda();
	}
	public function getExpByResCom()
	{
		return $this->model->getExpByResCom();
	}
	public function getExpByEdoDem()
	{
		return $this->model->getExpByEdoDem();
	}
	public function getExpedientesEstadoNP()
	{
		return $this->model->getExpedientesEstadoNP();
	}
	
}
?>