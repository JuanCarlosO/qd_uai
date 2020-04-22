<?php
/**
 * Acciones de la direccion de responsabilidades
 */
include_once '../model/DRModel.php';
class DRController
{
	protected $model;
	function __construct()
	{
		return $this->model = new DRModel();
	}
	public function getOnlyExp()
	{
		return $this->model->getOnlyExp();
	}
	public function getExpedientes()
	{
		return $this->model->getExpedientes();
	}
	public function saveEdoProcesal()
	{
		return $this->model->saveEdoProcesal();
	}
	public function getOFs()
	{
		return $this->model->getOFs();
	}
	public function delete_responsable()
	{
		return $this->model->delete_responsable();
	}
	public function updateEdoProcesal()
	{
		return $this->model->updateEdoProcesal();
	}
	public function saveOpinion()
	{
		return $this->model->saveOpinion();
	}
	public function getConductasRespo()
	{
		return $this->model->getConductasRespo();
	}
	public function saveConductaRespo()
	{
		return $this->model->saveConductaRespo();
	}
	public function saveAbogadoRes()
	{
		return $this->model->saveAbogadoRes();
	}
	public function getExpedientesSC()
	{
		return $this->model->getExpedientesSC();
	}
	public function saveDemanda()
	{
		return $this->model->saveDemanda();
	}
	public function saveResolucion()
	{
		return $this->model->saveResolucion();
	}
	public function saveResolucionDemanda()
	{
		return $this->model->saveResolucionDemanda();
	}
	public function saveReserva()
	{
		return $this->model->saveReserva();
	}
	public function saveAImprocedencia()
	{
		return $this->model->saveAImprocedencia();
	}
	public function devolverExp()
	{
		return $this->model->devolverExp();
	}
	public function getReporte()
	{
		return $this->model->getReporte();
	}
	public function editResolucion()
	{
		return $this->model->editResolucion();
	}
	public function getResolucion()
	{
		return $this->model->getResolucion();
	}
	
}
?>