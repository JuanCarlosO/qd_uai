<?php
/**
 * Metodos de QUEJAS Y DENUNCIAS 
 */
include_once '../model/QDModel.php';
class QDController
{
	protected $model;
	function __construct()
	{
		$this->model = new QDModel();
	}
	public function getExpedientesTC()
	{
		return $this->model->getExpedientesTC();
	}
	public function getTblCtrl()
	{
		return $this->model->getTblCtrl();
	}
	public function getTR()
	{
		return $this->model->getTR();
	}
	public function saveTR()
	{
		return $this->model->saveTR();
	}
	public function getProcedencias()
	{
		return $this->model->getProcedencias();
	}
	public function getTT()
	{
		return $this->model->getTT();
	}
	public function getCode()
	{
		return $this->model->getCode();
	}
	public function getLeyes()
	{
		return $this->model->getLeyes();
	}
	public function getConductas()
	{
		return $this->model->getConductas();
	}
	public function getVias()
	{
		return $this->model->getVias();
	}
	public function getMunicipios()
	{
		return $this->model->getMunicipios();
	}
	public function saveQueja()
	{
		return $this->model->saveQueja();
	}
	public function getQDs()
	{
		return $this->model->getQDs();
	}
	public function getPersonal()
	{
		return $this->model->getPersonal();
	}
	public function getEstadosGuarda()
	{
		return $this->model->getEstadosGuarda();
	}
	public function getQDOnly($queja)
	{
		return $this->model->getQDOnly($queja);
	}
	public function delete_turno()
	{
		return $this->model->delete_turno();
	}
	public function delete_conducta()
	{
		return $this->model->delete_conducta();
	}
	public function saveDoc()
	{
		return $this->model->saveDoc();
	}
	public function saveUnidad()
	{
		return $this->model->saveUnidad();
	}
	public function savePresunto()
	{
		return $this->model->savePresunto();
	}
	public function saveQuejoso()
	{
		return $this->model->saveQuejoso();
	}
	public function getDocumento($queja)
	{
		return $this->model->getDocumento($queja);
	}
	public function deleteFile()
	{
		return $this->model->deleteFile();
	}
	public function editQD()
	{
		return $this->model->editQD();
	}
	public function deleteVia()
	{
		return $this->model->deleteVia();
	}
	public function getCargos()
	{
		return $this->model->getCargos();
	}
	public function getAgrupamientos()
	{
		return $this->model->getAgrupamientos();
	}
	public function getRegiones()
	{
		return $this->model->getRegiones();
	}
	public function getSubdirecciones()
	{
		return $this->model->getSubdirecciones();
	}
	public function deletePresunto()
	{
		return $this->model->deletePresunto();
	}
	public function deleteUnidad()
	{
		return $this->model->deleteUnidad();
	}
	public function deleteQuejoso()
	{
		return $this->model->deleteQuejoso();
	}
	public function generateReporte()
	{
		return $this->model->generateReporte();
	}
	public function saveTurno()
	{
		return $this->model->saveTurno();
	}
	public function getDependenciasF()
	{
		return $this->model->getDependenciasF();
	}
	public function getTblCtrlSubd()
	{
		return $this->model->getTblCtrlSubd();
	}
	public function getExpedientesForMigrate()
	{
		return $this->model->getExpedientesForMigrate();
	}
	public function MigrateQuejas()
	{
		return $this->model->MigrateQuejas();
	}
	public function getOINs()
	{
		return $this->model->getOINs();
	}
	public function getOINBy()
	{
		return $this->model->getOINBy();
	}
	
	public function contadorOINs()
	{
		return $this->model->contadorOINs();
	}
	public function saveAcuse()
	{
		return $this->model->saveAcuse();
	}
	public function getDocumentoByOficio($oficio)
	{
		return $this->model->getDocumentoByOficio($oficio);
	}
	public function getAsignaciones()
	{
		return $this->model->getAsignaciones();
	}
	public function saveAsignar()
	{
		return $this->model->saveAsignar();
	}
	public function getArticulos()
	{
		return $this->model->getArticulos();
	}
	public function saveCargo()
	{
		return $this->model->saveCargo();
	}
	public function getProcedenciasP()
	{
		return $this->model->getProcedenciasP();
	}
	public function saveProcedenciasP()
	{
		return $this->model->saveProcedenciasP();
	}
	public function getSecciones()
	{
		return $this->model->getSecciones();
	}
	public function getFracciones()
	{
		return $this->model->getFracciones();
	}
	public function getPenales()
	{
		return $this->model->getPenales();
	}
	public function saveOpinion()
	{
		return $this->model->saveOpinion();
	}
	public function getColores()
	{
		return $this->model->getColores();
	}
	public function getExpTipo()
	{
		return $this->model->getExpTipo();
	}
	public function getListadoTipo()
	{
		return $this->model->getListadoTipo();
	}
	public function getCoordinacionesTra()
	{
		return $this->model->getCoordinacionesTra();
	}
	public function getAgrupamientosTra()
	{
		return $this->model->getAgrupamientosTra();
	}	
	public function getDireccionAdmin()
	{
		return $this->model->getDireccionAdmin();
	}	
	public function getUnidadAdmin()
	{
		return $this->model->getUnidadAdmin();
	}	
	public function getDirAreasAdmin()
	{
		return $this->model->getDirAreasAdmin();
	}	
	public function getSubdirAreasAdmin()
	{
		return $this->model->getSubdirAreasAdmin();
	}	
	public function getDepartamentosAdmin()
	{
		return $this->model->getDepartamentosAdmin();
	}	
	
}
?>