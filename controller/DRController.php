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
	public function getExpedientesAnalista()
	{
		return $this->model->getExpedientesAnalista();
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
	public function getTblCtrl()
	{
		return $this->model->getTblCtrl();
	}
	public function getExpedientesTC()
	{
		return $this->model->getExpedientesTC();
	}
	public function getCorrespondencia()
	{
		return $this->model->getCorrespondencia();
	}
	public function saveAcuse()
	{
		return $this->model->saveAcuse();
	}
	public function sendSAPA()
	{
		return $this->model->sendSAPA();
	}
	public function getDocumentoDevoluciones()
	{
		return $this->model->getDocumentoDevoluciones();
	}
	public function saveApersonamiento()
	{
		return $this->model->saveApersonamiento();
	}
	public function saveAcuseSapa()
	{
		return $this->model->saveAcuseSapa();
	}
	public function asignarPersonal()
	{
		return $this->model->asignarPersonal();
	}
	public function saveEProcesal()
	{
		return $this->model->saveEProcesal();
	}
	public function saveCulpable()
	{
		return $this->model->saveCulpable();
	}
	public function getEstadistica()
	{
		return $this->model->getEstadistica();
	}
	public function getClave($id)
	{
		return $this->model->getClave($id);
	}
	public function getAcusesSAPA()
	{
		return $this->model->getAcusesSAPA();
	}
	public function getAcuse($id)
	{
		return $this->model->getAcuse($id);
	}
	public function getContadoresByEdo()
	{
		return $this->model->getContadoresByEdo();
	}
		
}
?>