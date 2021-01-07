<?php
require_once 'model/Connection.php';
require_once 'model/QDModel.php';
$error = false;
try {
    if ( !isset($_GET['queja']) ) { throw new Exception("VARIABLE INACCESIBLE", 1); }
    if ( empty($_GET['queja'])  ) { throw new Exception("ID DEL EXPEDIENTE NO IDENTIFICADO", 1); }
    $queja_id = $_GET['queja'];
    $q = new QDModel;
    $r = (object)$q->getQDOnly($queja_id)[0];
    #print_r($r->conductas);exit;
    if ( isset($r->scalar) ) {
        throw new Exception("A OCURRIO UN ERROR AL TRATAR DE RECUPERAR LOS DATOS DE LOS EXPEDIENTES", 1);
    }
    if (count($r->unidades) > 0) {
        $unidad = $r->unidades[0];
    }else{
        $unidad = array();
    }
    $conductas = $r->conductas;
    
    #echo "<pre>"; print_r($r->conductas);echo "</pre>";
    #Cargar los catalogos y verificar cual se selecciono
    #Tipos de referencia
    $tr = json_decode($q->getTR());
    #Tipos de tramite 
    $tt = json_decode($q->getTT());
    #Estados en los que se guarda el expediente.
    $estados = json_decode($q->getEstadosGuarda());
    #Municipios 
    $municipios = json_decode($q->getMunicipios());
    #Listado de procedencias
    $procedencias = json_decode($q->getProcedencias());
    #vias de recepcion
    $vias = json_decode($q->getVias());
    #Catalogo de colores 
    $colores = json_decode($q->getColores());
    #Catalogo de cargos 
    $cargos = json_decode($q->getCargos());
    #Catalogos de Operativos Secretaría de Seguridad
    $coordinaciones = json_decode($q->getCoordinaciones());
    $subdirecciones = json_decode($q->getSubdirecciones()); 
    $regiones = json_decode($q->getRegiones());
    $agrupamientos = json_decode($q->getAgrupamientos());
    #catalogos de Dirección de Policía de Tránsito
    $coordinaciones_tra = json_decode($q->getCargos());
    $agrupamientos_tra = json_decode($q->getCargos());
    #catalogos de Dirección General de Prevención y Reinserción Social
    $agrupamientos_cprs = json_decode($q->getCargos());
    #catalogos de Personal Administrativo
    $direcciones = json_decode($q->getCargos());
    $unidades = json_decode($q->getCargos());
    $d_areas = json_decode($q->getCargos());
    $subd_areas = json_decode($q->getCargos());
    $departamentos = json_decode($q->getCargos());

    $f_hechos = ($r->f_hechos != '0000-00-00') ? $r->f_hechos: ''; 
    $h_hechos = ($r->h_hechos != '00:00:00') ? $r->h_hechos: ''; 
    $categoria = ($r->categoria != '') ? $r->categoria: ''; 
    $d_ano = ($r->d_ano == 'SI') ? 'checked' : ''; 
} catch (Exception $e) {
    $error = true ;
    $message = $e->getMessage();
}
?>
<?php if ( !$error ): ?>
    <form action="#" id="frm_edit_queja">
        <input type="hidden" id="queja" name="queja_id" value="<?=$_GET['queja']?>">
        <input type="hidden" id="cve_exp" name="cve_exp" value="<?=$r->cve_exp?>">
        <input type="hidden" id="option" name="option" value="20">
        <section class="content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Datos del expediente: <b><?=$r->cve_exp?></b> </h3>
                        </div>
                        <div class="box-body">
                            <div id="edit_alert"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-group" id="accordion">
                                        <div class="panel box box-success box-solid">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#d_queja" aria-expanded="false" class="collapsed">
                                                        Datos de la queja o denuncia
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="d_queja" class="panel-collapse collapse in" aria-expanded="false" >
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-11">
                                                            <div class="form-group">
                                                                <label>Vía de recepción</label>
                                                                <select id="vias_r" name="vias_r[]" class="form-control select2" multiple="">
                                                                    <option value="">...</option>
                                                                    <?php foreach ($vias as $key => $v): ?>
                                                                    <option value="<?=$v->id?>"><?=$v->nombre?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <table class="table table-bordered table-condensed border-table">
                                                                <?php foreach ($r->vias as $key => $v): ?>
                                                                <tr id="<?=$v->id?>">
                                                                    <td><?=$v->via?></td>
                                                                    <td width="10px">
                                                                        <button type="button" onclick=" deleteVia(<?=$v->id?>); " class="btn btn-danger btn-flat btn-xs">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>    
                                                                <?php endforeach ?>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="evidencia">Evidencia</label>
                                                                <select id="evidencia" name="evidencia" class="form-control">
                                                                    <option value="">...</option>
                                                                    <option value="1" <?=($r->evidencia == 'CD') ? 'selected' : '' ;?> >CD/DVD</option>
                                                                    <option value="2" <?=($r->evidencia == 'USB') ? 'selected' : '' ;?> >MEMORIA USB</option>
                                                                    <option value="3" <?=($r->evidencia == 'FOTOS') ? 'selected' : '' ;?> >FOTOGRAFÍAS</option>
                                                                    <option value="4" <?=($r->evidencia == 'DOCUMENTOS') ? 'selected' : '' ;?> >DOCUMENTOS</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="fojas">Número de fojas</label>
                                                                <input type="number" name="fojas" value="<?=$r->fojas?>" placeholder="" class="form-control" autocomplete="off" min="0">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="procedencia">Procedencia <i class="fa fa-asterisk text-red"></i></label>
                                                                <select name="procedencia" class="form-control" required="">
                                                                    <option value="">...</option>
                                                                    <option value="1" <?=($r->procedencia_abre == 'CPRS') ? 'selected' : '' ;?>>CPRS</option>
                                                                    <option value="2" <?=($r->procedencia_abre == 'SS') ? 'selected' : '' ;?>>SECRETARÍA DE SEGURIDAD</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Fecha de hechos <i class="fa fa-asterisk text-red"></i></label>
                                                                <input type="date" id="f_hechos" name="f_hechos" value="<?=$f_hechos?>" class="form-control" required="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Hora de hechos</label>
                                                                <input type="time" id="h_hechos" name="h_hechos" value="<?=$h_hechos?>" placeholder="Campo automático" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="t_afecta">Tipo de afectada(o) <i class="fa fa-asterisk text-red"></i></label>
                                                                <select id="categoria" name="categoria" class="form-control" required>
                                                                    <option value="">...</option>
                                                                    <option value="1" <?=($categoria == 'CIUDADANO') ? 'selected' : '' ;?> >CIUDADANO</option>
                                                                    <option value="2" <?=($categoria == 'SERVIDOR PUBLICO') ? 'selected' : '' ;?> >SERVIDOR PÚBLICO</option>
                                                                    <option value="3" <?=($categoria == 'OTRO') ? 'selected' : '' ;?> >OTRO</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group" style="margin-top: 25px;">
                                                                <big>
                                                                    <label style="text-decoration: underline black;" for="d_ano">¿ES DENUNCIA ANÓNIMA?</label>
                                                                    &emsp;
                                                                    <big class="">
                                                                        <label for="d_ano">SÍ</label> <input type="checkbox" id="d_ano" name="d_ano" value="1" style="font-size: 110%; display: inline;" <?=$d_ano?>>
                                                                    </big>
                                                                </big>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel box box-success box-solid">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#d_quejoso" class="" aria-expanded="false">
                                                        Datos del quejoso
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="d_quejoso" class="panel-collapse collapse " aria-expanded="false" style="">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-3"></div>
                                                        <div class="col-md-3"></div>
                                                        <div class="col-md-3"></div>
                                                        <div class="col-md-3">
                                                            <button type="button" class="btn btn-success btn-flat btn-xs btn-block" onclick="open_modal('modal_add_quejoso')">
                                                                <i class="fa fa-plus"></i> Agregar un quejoso
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <table class="table table-bordered table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center bg-gray">NOMBRE COMPLETO</th>
                                                                <th class="text-center bg-gray">GÉNERO</th>
                                                                <th class="text-center bg-gray">TELÉFONO</th>
                                                                <th class="text-center bg-gray">CORREO</th>
                                                                <th class="text-center bg-gray">DIRECCIÓN</th>
                                                                <th class="text-center bg-gray">ELIMINAR</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($r->quejosos as $key => $quejoso): ?>
                                                                <tr id="quejoso_<?=$quejoso->id?>" class="text-center">
                                                                    <td><?=mb_strtoupper($quejoso->nombre,'UTF-8')?></td>
                                                                    <td><?=( $quejoso->genero == '' ) ? 'MASCULINO' : 'FEMENINO';?></td>
                                                                    <td><?=( is_null($quejoso->telefono) ) ? 'SIN TELÉFONO' : $quejoso->telefono;?></td>
                                                                    <td><?=mb_strtoupper($quejoso->email)?></td>
                                                                    <td class="text-left">
                                                                        <ul>
                                                                            <li><label>MUNICIPIO: </label><?=mb_strtoupper($quejoso->n_municipio,'UTF-8')?></li>
                                                                            <li><label>C.P.: </label><?=$quejoso->cp?></li>
                                                                            <li><label>NÚM. INT.: </label><?=$quejoso->n_int?></li>
                                                                            <li><label>NÚM. EXT.: </label><?=$quejoso->n_ext?></li>
                                                                        </ul>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-danger btn-flat" onclick="deleteQuejoso(<?=$quejoso->id?>);"> 
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel box box-success box-solid">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#d_lugar" class="" aria-expanded="false">
                                                        Datos del lugar de los hechos
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="d_lugar" class="panel-collapse collapse" aria-expanded="true" style="">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="municipios">Municipio <i class="fa fa-asterisk text-red"></i></label>
                                                                <select id="municipios" name="municipios" class="form-control" required>
                                                                    <option value="">...</option>
                                                                    <?php foreach ($municipios as $municipio): ?>
                                                                        <?php if ( $r->mun_id == $municipio->id ): ?>
                                                                            <option value="<?=$municipio->id?>" selected>
                                                                            <?=$municipio->nombre?>
                                                                            </option>
                                                                        <?php else: ?>
                                                                            <option value="<?=$municipio->id?>">
                                                                            <?=$municipio->nombre?>
                                                                            </option>
                                                                        <?php endif ?>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label for="c_principal">Calle principal</label>
                                                                <input type="text" id="c_principal" name="c_principal" class="form-control" placeholder="Nombre de la calle principal" autocomplete="off" value="<?=( !is_null($r->calle) ) ? $r->calle : '' ;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="e_calle">Entre calle </label>
                                                                <input type="text" id="e_calle" name="e_calle" class="form-control" autocomplete="off" value="<?=( !is_null($r->e_calle) ) ? $r->e_calle : '' ;?>"> 
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="y_calle">Y calle</label>
                                                                <input type="text" id="y_calle" name="y_calle" class="form-control" autocomplete="off" value="<?=( !is_null($r->y_calle) ) ? $r->y_calle : '' ;?>">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="edificacion">Colonia </label>
                                                                <input type="text" id="edificacion" name="edificacion" class="form-control" autocomplete="off" value="<?=( !is_null($r->colonia) ) ? mb_strtoupper($r->colonia,'UTF-8') : '' ;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="n_edificacion">Número</label>
                                                                <input type="text" id="n_edificacion" name="n_edificacion" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel box box-success box-solid">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#a_responsable" class="" aria-expanded="false">
                                                        Datos del probable responsable
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="a_responsable" class="panel-collapse collapse" aria-expanded="true" style="">
                                                <div class="box-body">
                                                    
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Nombre completo</label>
                                                                <input type="text" class="form-control" name="name_presunto" value="<?=( !empty($r->presuntos->nombre) ) ? $r->presuntos->nombre: 'NO SE REGISTRO';?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">                                                                
                                                                <label>RFC</label>
                                                                <input type="text" class="form-control" name="rfc" value="<?=(!empty($r->presuntos->rfc)) ? $r->presuntos->rfc : '' ;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">                                                                
                                                                <label>CURP</label>
                                                                <input type="text" class="form-control" name="curp" value="<?=(!empty($r->presuntos->curp)) ? $r->presuntos->curp : '' ;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">                                                                
                                                                <label>CUIP</label>
                                                                <input type="text" class="form-control" name="cuip" value="<?=(!empty($r->presuntos->cuip)) ? $r->presuntos->cuip : '' ;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">                                                                
                                                                <label>TIPO DE PUESTO</label>
                                                                <select class="form-control" name="t_puesto">
                                                                    <option value="">...</option>
                                                                    <option value="1" <?=( $r->presuntos->t_puesto == 'ADMINISTRATIVO' ) ? 'selected' : '';?> >Administrativo</option>
                                                                    <option value="2" <?=( $r->presuntos->t_puesto == 'OPERATIVO' ) ? 'selected' : '';?> >Operativo</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Seleccionar cargo</label>
                                                                <select class="form-control cargos" id="cargo" name="cargo">
                                                                    <option value="">...</option>
                                                                    <?php foreach ($cargos as $key => $cargo): ?>
                                                                        <?php if ( $r->presuntos->cargo_id == $cargo->id ): ?>
                                                                            <option value="<?=$cargo->id?>" selected><?=$cargo->nombre?></option>
                                                                        <?php else: ?>
                                                                            <option value="<?=$cargo->id?>"><?=$cargo->nombre?></option>
                                                                        <?php endif ?>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Género</label>
                                                                <select class="form-control" id="ge" name="genero">
                                                                    <option value="">...</option>
                                                                    <option value="1" <?=( !empty($r->presuntos->genero) && $r->presuntos->genero == 'M' ) ? 'selected' : '' ;?> >Hombre</option>
                                                                    <option value="2" <?=( !empty($r->presuntos->genero) && $r->presuntos->genero == 'F' ) ? 'selected' : '' ;?> >Mujer</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Procedencia</label>
                                                                <select class="form-control" id="procedencia" name="procedencia">
                                                                    <option value="">...</option>
                                                                    <option value="1" <?=( !empty($r->presuntos->procedencia) && $r->presuntos->procedencia == '1' ) ? 'selected' : '' ;?>>CPRS</option>
                                                                    <option value="2" <?=( !empty($r->presuntos->procedencia) && $r->presuntos->procedencia == '2' ) ? 'selected' : '' ;?>>SECRETARÍA DE SEGURIDAD</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Área</label>
                                                                <select name="area" id="area" class="form-control">
                                                                    <option value="">...</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div id="area_3" class="hidden">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Agrupamiento</label>
                                                                        <select name="" id="" class="form-control">
                                                                            <option value="">...</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="area_1" class="hidden">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Coordinación</label>
                                                                    <select name="coordina" id="" class="form-control">
                                                                        <option value="">...</option>
                                                                        <?php foreach ($coordinaciones as $key => $c): ?>
                                                                        <option value="<?=$c->id?>"><?=$c->nombre?></option>
                                                                        <?php endforeach ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Subdirección</label>
                                                                    <select name="subd" id="" class="form-control">
                                                                        <option value="">...</option>
                                                                        <?php foreach ($subdirecciones as $key => $s): ?>
                                                                        <option value="<?=$s->id?>"><?=$s->nombre?></option>
                                                                        <?php endforeach ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Región</label>
                                                                    <select name="region" id="" class="form-control">
                                                                        <option value="">...</option>
                                                                        <?php foreach ($regiones as $key => $r): ?>
                                                                        <option value="<?=$r->id?>"><?=$r->nombre?></option>
                                                                        <?php endforeach ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Agrupamiento</label>
                                                                    <select name="agrupa" id="" class="form-control">
                                                                        <option value="">...</option>
                                                                        <?php foreach ($agrupamientos as $key => $a): ?>
                                                                        <option value="<?=$a->id?>"><?=$a->nombre?></option>
                                                                        <?php endforeach ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="area_2" class="hidden">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Coordinación</label>
                                                                    <select name="" id="" class="form-control"></select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Agrupamiento</label>
                                                                    <select name="" id="" class="form-control"></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div id="area_4" class="hidden">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Dirección</label>
                                                                    <select name="" id="" class="form-control">
                                                                        <option value="">...</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Unidad</label>
                                                                    <select name="" id="" class="form-control">
                                                                        <option value="">...</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Dirección por áreas</label>
                                                                    <select name="" id="" class="form-control">
                                                                        <option value="">...</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Subdirección</label>
                                                                    <select name="" id="" class="form-control">
                                                                        <option value="">...</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Departamento</label>
                                                                    <select name="" id="" class="form-control">
                                                                        <option value="">...</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Media filiación</label>
                                                                <textarea class="form-control" id="media" name="media" style="resize: vertical; max-height: 250px;"><?=(!empty($r->presuntos->comentarios)) ? $r->presuntos->comentarios :'' ;?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel box box-success box-solid">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#d_unidad" class="" aria-expanded="false">
                                                        Datos de unidad implicada
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="d_unidad" class="panel-collapse collapse" aria-expanded="true" style="">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Procedencia</label>
                                                                <select id="u_procedencia" name="u_procedencia" class="form-control">
                                                                    <option value="">...</option>
                                                                    <option value="2" <?=( !empty($unidad->procedencia) && $unidad->procedencia == 'CPRS') ? 'selected' :'' ;?> >CPRS</option>
                                                                    <option value="1" <?=( !empty($unidad->procedencia) && $unidad->procedencia == 'SS') ? 'selected' :'' ;?> >SECRETARIA DE SEGURIDAD</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Tipo de vehículo</label>
                                                                <select id="t_vehiculo" name="t_vehiculo" class="form-control">
                                                                    <option value="">...</option>
                                                                    <!-- VALORES ENUM DE LA BD-->
                                                                    <option value="1" <?=( !empty($unidad->t_vehiculo) && $unidad->t_vehiculo == 'MOTO' ) ? 'selected'  :'' ;?>>MOTOCICLETA</option>
                                                                    <option value="2" <?=( !empty($unidad->t_vehiculo) && $unidad->t_vehiculo == 'CAMIONETA' ) ? 'selected'  :'' ;?>>CAMIONETA</option>
                                                                    <option value="3" <?=( !empty($unidad->t_vehiculo) && $unidad->t_vehiculo == 'AUTO' ) ? 'selected'  :'' ;?>>AUTOMOVIL</option>
                                                                    <option value="4" <?=( !empty($unidad->t_vehiculo) && $unidad->t_vehiculo == 'CAMION' ) ? 'selected'  :'' ;?>>CAMIÓN</option>
                                                                    <option value="5" <?=( !empty($unidad->t_vehiculo) && $unidad->t_vehiculo == 'PICKUP' ) ? 'selected'  :'' ;?>>PICKUP</option>
                                                                    <option value="6" <?=( !empty($unidad->t_vehiculo) && $unidad->t_vehiculo == 'ACUATICO' ) ? 'selected'  :'' ;?>>ACUÁTICO</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Color</label>
                                                                <select id="colores" name="color" class="form-control">
                                                                    <option value="">...</option>
                                                                    <?php foreach ($colores as $color): ?>
                                                                        <?php if ( $color->id == $unidad->color ): ?>
                                                                        <option value="<?=$color->id?>" selected><?=$color->nombre?></option>    
                                                                        <?php else: ?>
                                                                        <option value="<?=$color->id?>"><?=$color->nombre?></option>
                                                                        <?php endif ?>
                                                                    
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Número económico</label>
                                                                <input type="text" id="n_eco" name="n_eco" class="form-control" placeholder="Ej: 123-S" value="<?=(!empty($unidad->n_eco) ? $unidad->n_eco : '');?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Placas</label>
                                                                <input type="text" id="placas" name="placas" class="form-control" placeholder="123-22" value="<?=( !empty($unidad->placas) ) ? $unidad->placas : '' ;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Número de serie (Opcional)</label>
                                                                <input type="text" name="n_ser" class="form-control" placeholder="Ej: 123456789" value="<?=(!empty($unidad->serie)) ? $unidad->serie : '' ;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Número de inventario (Opcional)</label>
                                                                <input type="text" name="n_inv" class="form-control" placeholder="Ej: 987654321" value="<?=(!empty($unidad->inventario)) ? $unidad->inventario : '';?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Características de la unidad </label>
                                                                <textarea name="u_comentarios" class="form-control" style="resize: vertical; max-height: 250px;"><?=(!empty($unidad->comentario)) ? $unidad->comentario : '' ;?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel box box-success box-solid">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#semoviente" class="" aria-expanded="false">
                                                        Alta de semoviente
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="semoviente" class="panel-collapse collapse" aria-expanded="true" style="">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Tipo de semoviente</label>
                                                                <select id="t_animal" name="t_animal" class="form-control">
                                                                    <option value="">...</option>
                                                                    <option value="1" <?=( isset($r->semovientes->tipo) && !empty($r->semovientes->tipo) == 'CABALLO' ) ? 'selected' : '' ;?> >Caballo</option>
                                                                    <option value="2" <?=( isset($r->semovientes->tipo) && !empty($r->semovientes->tipo) == 'PERRO' ) ? 'selected' : '' ;?> >Perro</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Raza del semoviente</label>
                                                                <input type="text" id="raza" name="raza" class="form-control" value="<?=(isset($r->semovientes->raza) || !empty($r->semovientes->raza)) ? $r->semovientes->raza : '' ;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Edad</label>
                                                                <input type="text" id="edad" name="edad" class="form-control" value="<?=(isset($r->semovientes->edad) || !empty($r->semovientes->edad)) ? $r->semovientes->edad : '' ;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Color</label>
                                                                <input type="text" id="color" name="color" class="form-control" value="<?=(isset($r->semovientes->color) || !empty($r->semovientes->color)) ? $r->semovientes->color : '' ;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nombre del semoviente</label>
                                                                <input type="text" id="n_animal" name="n_animal" class="form-control" value="<?=(isset($r->semovientes->nombre) || !empty($r->semovientes->nombre)) ? $r->semovientes->nombre : '' ;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Número de inventario</label>
                                                                <input type="text" id="inventario" name="inventario" class="form-control" value="<?=(isset($r->semovientes->inventario) || !empty($r->semovientes->inventario)) ? $r->semovientes->inventario : '' ;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel box box-success box-solid">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#conductas" class="" aria-expanded="false">
                                                        Probable conducta
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="conductas" class="panel-collapse collapse" aria-expanded="true" style="">
                                                <div class="box-body">
                                                    <div id="div_conductas" class="">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label> Normatividad aplicable</label>
                                                                    <select id="t_ley" name="t_ley" class="form-control">
                                                                        <option value="">...</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Capítulo</label>
                                                                    <select id="capitulos" name="capitulo" class="form-control">
                                                                        <option value="">...</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="art">Número de artículo </label>
                                                                    <select id="art" name="art" class="form-control">
                                                                        <option value="">...</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="secciones">Secciones disponibles </label>
                                                                    <select id="secciones" name="secciones" class="form-control">
                                                                        <option value="">...</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="fracciones">Fracciones disponibles </label>
                                                                    <select id="fracciones" name="fracciones" class="form-control">
                                                                        <option value="">...</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="conductas">Presunta conducta </label>
                                                                    <select id="conducta" name="conducta" class="form-control" data-placeholder="Selecciona una conducta">
                                                                        <option value="">...</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>                            
                                                    </div>
                                                                                                        
                                                    <table class="table table-bordered table-condensed">
                                                        <thead>
                                                            <tr class="bg-gray">
                                                                <th>CONDUCTA COMPLETA</th>
                                                                <th>CONDUCTA RESUMIDA</th>
                                                                <th>TIPO</th>
                                                                <th>ELIMINAR</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- <tr>
                                                                <td>INASISTENCIA</td>
                                                                <td>INASISTENCIA</td>
                                                                <td>PRINCIPAL</td> 
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-flat"  onclick="deleteConducta(1,'PRINCIPAL');">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr> -->

                                                            <?php if ( isset($conductas) && count($conductas) > 0): ?>
                                                                <?php foreach ($conductas as $key => $conducta): ?>
                                                                    <tr id="c_<?=$conducta->id_presunta?>" class="text-justify">
                                                                        <td><?=mb_strtoupper($conducta->nombre,'UTF-8');?></td>
                                                                        <td><?=mb_strtoupper($conducta->nombre,'UTF-8');?></td>
                                                                        <td><?=mb_strtoupper($conducta->tipo,'UTF-8');?></td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-danger btn-flat"  onclick="deleteConducta(<?=$conducta->id_presunta?>,'<?=$conducta->tipo?>');">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach ?>
                                                            <?php else: ?>
                                                                <tr class="text-center text-red">
                                                                    <td colspan="4"><b>NO HAY CONDUCTAS.</b></td>
                                                                </tr>
                                                            <?php endif ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <button class="btn bg-olive btn-flat btn-block">
                                        <i class="fa fa-floppy-o"></i> Guardar cambios
                                    </button>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
<?php else: ?>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"> ERROR ENCONTRADO </h3>
                    </div>
                    <div class="box-body">
                        <H1 class="text-center ">SE ENCONTRÓ UN ERROR AL TRATAR DE RECUPERAR LOS DATOS DEL EXPEDIENTE. </H1>
                        <h3 class="text-center ">Informe esta falla al Departamento de Desarrollo de Sistemas.</h3>
                        <h4 class="text-center ">Ext: 129</h4>
                        <p><?=$message?></p>
                        <div class="row">
                            <div class="col-md-12">
                                <CENTER><img src="view/dist/img/error.png" alt="" class="" width="100px" height="100px"></CENTER>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif ?>


