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
	public function saveQuejoso()
	{
		return $this->model->saveQuejoso();
	}
	public function getPresuntos()
	{
		return $this->model->getPresuntos();
	}
	public function getQuejosos()
	{
		return $this->model->getQuejosos();
	}
	public function deleteQuejoso()
	{
		return $this->model->deleteQuejoso();
	}
	public function deletePR()
	{
		return $this->model->deletePR();
	}
	public function saveAuto()
	{
		return $this->model->saveAuto();
	}
	public function getMarcas()
	{
		return $this->model->getMarcas();
	}
	public function getAutos()
	{
		return $this->model->getAutos();
	}
	public function deleteAuto()
	{
		return $this->model->deleteAuto();
	}
	public function saveAnimal()
	{
		return $this->model->saveAnimal();
	}
	public function getAnimales()
	{
		return $this->model->getAnimales();
	}
	public function deleteAnimal()
	{
		return $this->model->deleteAnimal();
	}
	public function getArmas()
	{
		return $this->model->getArmas();
	}
	public function saveArma()
	{
		return $this->model->saveArma();
	}
	public function deleteArma()
	{
		return $this->model->deleteArma();
	}
	public function getDocumentos()
	{
		return $this->model->getDocumentos();
	}
	public function deleteDoc()
	{
		return $this->model->deleteDoc();
	}
	public function generateReporte()
	{
		return $this->model->generateReporte();
	}
	public function getListONIs()
	{
		return $this->model->getListONIs();
	}
	public function saveDocOIN()
	{
		return $this->model->saveDocOIN();
	}
	public function getDashboard()
	{
		return $this->model->getDashboard();
	}
	public function getActasBy()
	{
		return $this->model->getActasBy();
	}
	
}
?>