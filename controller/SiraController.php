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
	public function saveCancelaOT()
	{
		return $this->model->saveCancelaOT();
	}
	public function getListCenso()
	{
		return $this->model->getListCenso();
	}
	public function saveCenso()
	{
		return $this->model->saveCenso();
	}
	public function saveRecordatorio()
	{
		return $this->model->saveRecordatorio();
	}
	public function saveRespuesta()
	{
		return $this->model->saveRespuesta();
	}
	public function getPreguntas()
	{
		return $this->model->getPreguntas();
	}
	public function getCuestionario()
	{
		return $this->model->getCuestionario();
	}
	public function saveResponder()
	{
		return $this->model->saveResponder();
	}
	public function Responder_Comisionados()
	{
		return $this->model->Responder_Comisionados();
	}
	public function Responder_Turnos()
	{
		return $this->model->Responder_Turnos();
	}
	public function Responder_Armas()
	{
		return $this->model->Responder_Armas();
	}
	public function Responder_Gas()
	{
		return $this->model->Responder_Gas();
	}
	public function Responder_Vehiculo()
	{
		return $this->model->Responder_Vehiculo();
	}
	public function saveRecomendacion()
	{
		return $this->model->saveRecomendacion();
	}
	public function saveDocObs()
	{
		return $this->model->saveDocObs();
	}
	public function saveRecordatorioObs()
	{
		return $this->model->saveRecordatorioObs();
	}
	public function getCoordinaciones()
	{
		return $this->model->getCoordinaciones();
	}
	public function getSubdirecciones($id)
	{
		return $this->model->getSubdirecciones($id);
	}
	public function getRegiones($id)
	{
		return $this->model->getRegiones($id);
	}
	public function getAgrupamientos($id)
	{
		return $this->model->getAgrupamientos($id);
	}
	public function getRecomendaciones()
	{
		return $this->model->getRecomendaciones();
	}
	public function saveSeguimiento()
	{
		return $this->model->saveSeguimiento();
	}
	public function saveReco_respuesta()
	{
		return $this->model->saveReco_respuesta();
	}
	public function getCoordinacionesT()
	{
		return $this->model->getCoordinacionesT();
	}
	public function getAgrupamientosT($id)
	{
		return $this->model->getAgrupamientosT($id);
	}
	public function getAgrupamientosCPRS()
	{
		return $this->model->getAgrupamientosCPRS();
	}
	public function graphic_censo($n_pregunta, $question_a, $question_a2, $agrupamiento, $agrupamiento_t, $agrupamiento_cprs, $niv5)
	{
		return $this->model->graphic_censo($n_pregunta, $question_a, $question_a2, $agrupamiento, $agrupamiento_t, $agrupamiento_cprs, $niv5);
	}
	public function getOFsRes()
	{
		return $this->model->getOFsRes();
	}
	public function getDetalle($pe)
	{
		return $this->model->getDetalle($pe);
	}
	public function getDetalleComis($pe)
	{
		return $this->model->getDetalleComis($pe);
	}
	public function getAcuse_Irr($ot)
	{
		return $this->model->getAcuse_Irr($ot);
	}
	public function graphic_actuaciones($f_inicio,$f_fin)
	{
		return $this->model->graphic_actuaciones($f_inicio,$f_fin);
	}
	public function graphic_ordenes($f_inicio,$f_fin)
	{
		return $this->model->graphic_ordenes($f_inicio,$f_fin);
	}
	public function getOficio($o)
	{
		return $this->model->getOficio($o);
	}
	public function getAreaPersonal($p)
	{
		return $this->model->getAreaPersonal($p);
	}
	public function getPreguntasCenso()
	{
		return $this->model->getPreguntasCenso();
	}
	public function getOperativos_Est()
	{
		return $this->model->getOperativos_Est();
	}
	public function getTransito_Est()
	{
		return $this->model->getTransito_Est();
	}
	public function getCPRS_Est()
	{
		return $this->model->getCPRS_Est();
	}
	public function getAdmin_Est()
	{
		return $this->model->getAdmin_Est();
	}
	public function tabla_actuaciones($fi,$ff)
	{
		return $this->model->tabla_actuaciones($fi,$ff);
	}
	public function editCenso()
	{
		return $this->model->editCenso();
	}
	public function saveEditRecomendacion()
	{
		return $this->model->saveEditRecomendacion();
	}
	public function saveActaAdministrativa()
	{
		return $this->model->saveActaAdministrativa();
	}
	public function getListActaAdmin()
	{
		return $this->model->getListActaAdmin();
	}
	public function EnviarActa()
	{
		return $this->model->EnviarActa();
	}
	public function CancelarActa()
	{
		return $this->model->CancelarActa();
	}
	public function generateReporteI()
	{
		return $this->model->generateReporteI();
	}
	public function getAcuse_Acta($file)
	{
		return $this->model->getAcuse_Acta($file);
	}
	public function getNivel1()
	{
		return $this->model->getNivel1();
	}
	public function getNivel2($id)
	{
		return $this->model->getNivel2($id);
	}
	public function getNivel3($id)
	{
		return $this->model->getNivel3($id);
	}
	public function getNivel4($id)
	{
		return $this->model->getNivel4($id);
	}
	public function getNivel5($id)
	{
		return $this->model->getNivel5($id);
	}
	public function saveEstatus()
	{
		return $this->model->saveEstatus();
	}
	public function saveComentario()
	{
		return $this->model->saveComentario();
	}
	public function getAcuse_OIN($file)
	{
		return $this->model->getAcuse_OIN($file);
	}
	public function getDashboard_OT()
	{
		return $this->model->getDashboard_OT();
	}
	public function saveAcciones()
	{
		return $this->model->saveAcciones();
	}
	public function generateReporteOT()
	{
		return $this->model->generateReporteOT();
	}
	public function getPersonalApoyo()
	{
		return $this->model->getPersonalApoyo();
	}
	public function getExpedientes()
	{
		return $this->model->getExpedientes();
	}
	public function RelacionarExpediente()
	{
		return $this->model->RelacionarExpediente();
	}
	public function RelacionarExpedienteCenso()
	{
		return $this->model->RelacionarExpedienteCenso();
	}

}
?>