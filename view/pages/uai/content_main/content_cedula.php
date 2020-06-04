<?php
require_once 'model/Connection.php';
require_once 'model/QDModel.php';
require_once 'model/DRModel.php';
require_once 'model/UAIModel.php';
$queja_id = $_GET['exp_id'];

if(!empty($_GET['exp_id'])){
    $queja_id = $_GET['exp_id'];
    $q = new QDModel;
    $m = new DRModel;
    $uai = new UAIModel;
    $r = $q->getQDOnly($queja_id);
    $data = $m->getCedula($queja_id);
    $tbl_ctrl = $uai->getContadores($queja_id);
    $demandas = $uai->getDataSC($queja_id);
    #print_r($demandas);
}

?>
<div class="contente container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                    <h3>
                        <center>
                            Clave del expediente <b><?=$r[0]['cve_exp'] ?></b>
                        </center>
                    </h3>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover table-bordered">
                                <caption class="text-center bg-gray">
                                    Contadores generales
                                </caption>
                                <thead>
                                    <tr>
                                        <th class="text-right" width="35%">Estado del expediente: </th>
                                        <td class="text-center" colspan="2">
                                            <?=$tbl_ctrl['estado'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" width="35%">Dias trancurridos desde la apertura:</th>
                                        <td class="text-center">
                                            <?=$tbl_ctrl['d_apertura'] ?>
                                        </td>
                                        <th class="text-left" width="35%">Días trabajados por la Dirección de Investigación: </th>
                                        <td class="text-center">
                                            <?=$tbl_ctrl['d_hechos'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-left" width="35%">Dias trancurridos desde fecha de hechos:</th>
                                        <td class="text-center"><?=$tbl_ctrl['d_hechos'] ?></td>
                                        <th class="text-left" width="35%">Días trabajados por la Dirección de Responsabilides: </th>
                                        <td class="text-center"><?=$tbl_ctrl['d_dr'] ?></td>
                                    </tr>
                                    
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (count($demandas) > 0): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <?php foreach ($demandas as $key => $demanda): ?>
                                        <tr class="bg-gray">
                                            <th>Subdirección</th>
                                            <th>Estado</th>
                                            <th>Descripción de la resolución</th>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray" rowspan="2">Subdirección de lo Contencioso</th>
                                            <td><?=$demanda->estado?></td>
                                            <td><?=$demanda->comentario?></td>
                                        </tr>
                                    <?php endforeach ?>
                                </table>
                            </div>
                        </div>
                    <?php endif ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                            <li class="active"><a href="#di" data-toggle="tab">Dirección de Investigación</a></li>
                            <li><a href="#dr" data-toggle="tab">Dirección de Responsabilidades</a></li>                 
                            <li class="pull-left header"><i class="fa fa-th"></i> Panel Integral de Cédulas Generales Por Dirección</li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="di">
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-hover ">
                                            <caption class="bg-gray text-center">
                                                Datos generales del expediente
                                            </caption>
                                            <tr class="bg-gray">
                                                <thead>
                                                    <tr>
                                                        <th>Tipo de Tramite</th>
                                                        <th>Fecha/Hora de hechos</th>
                                                        <th>Estado del expediente</th>
                                                        <th>Prioridad </th>
                                                        <th>Ley aplicada</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <?=$r[0]['n_tramite'];?>
                                                        </td>
                                                        <td>
                                                            <?=$r[0]['f_hechos']." y ".$r[0]['h_hechos'] ?> 
                                                        </td>
                                                        <td>
                                                            <?=mb_strtoupper($r[0]['n_estado'])?>
                                                        </td>
                                                        <td>
                                                            <?=mb_strtoupper($r[0]['prioridad'])?>
                                                        </td>
                                                        <td>
                                                            <?=$r[0]['conductas'][0]->n_ley?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-hover">
                                            <caption class="bg-gray text-center">Presuntas conductas</caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th>#</th>
                                                    <th>Conducta completa</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php
                                                foreach ($r[0]['conductas'] as $key => $conducta) {
                                                    echo '<tr>';
                                                        echo '<td>'.(++$key).'</td>';
                                                        echo '<td>'.mb_strtoupper($conducta->nombre,'utf-8').'</td>';
                                                    echo '</tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-hover table-bordered">
                                            <caption class="bg-gray text-center">
                                            Vias de recepción
                                            </caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th>#</th>
                                                    <th>Via de recepción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                for ($i=0; $i < count($r[0]['vias']) ; $i++) { 
                                                    $conta = $i +1;
                                                    echo '<tr>';
                                                        echo "<td>".$conta."</td>";
                                                        echo "<td>".$r[0]['vias'][$i]->via."</td>";
                                                    echo '</tr';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripción completa de los hechos</label>
                                            <p class="text-justify">
                                                <?=$r[0]['descripcion']?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-hover table-bordered">
                                            <caption class="bg-gray text-center">
                                                Lugar de los hechos
                                            </caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th>Municipio</th>
                                                    <th>Calle principal</th>
                                                    <th>Entre calle </th>
                                                    <th>Y Calle</th>
                                                    <th>Edificacion</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?=mb_strtoupper($r[0]['ubicacion']->n_municipio)?></td>
                                                    <td>
                                                        <?=mb_strtoupper($r[0]['ubicacion']->calle)?>
                                                    </td>
                                                    <td>
                                                        <?=mb_strtoupper($r[0]['ubicacion']->e_calle)?>
                                                    </td>
                                                    <td>
                                                        <?=mb_strtoupper($r[0]['ubicacion']->y_calle)?>
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            <li>
                                                                <?=mb_strtoupper($r[0]['ubicacion']->edificacion)?>
                                                            </li>
                                                            <li>
                                                                <?=mb_strtoupper($r[0]['ubicacion']->numero)?>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-hover table-bordered">
                                            <caption class="bg-gray text-center">Información del o los Quejosos </caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th>Nombre Completo</th>
                                                    <th>Teléfono</th>
                                                    <th>Género</th>
                                                    <th>Correo Electrónico</th>
                                                    <th>Municipio</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($r[0]['quejosos'] as $quejoso): ?>
                                                    <tr>
                                                        <td>
                                                            <?=mb_strtoupper($quejoso->nombre)?>
                                                        </td>
                                                        <td>
                                                            <?=mb_strtoupper($quejoso->telefono)?>
                                                        </td>
                                                        <td>
                                                            <?=mb_strtoupper($quejoso->genero)?>
                                                        </td>
                                                        <td>
                                                            <a href="mailto:<?=mb_strtoupper($quejoso->email)?>"><?=mb_strtoupper($quejoso->email)?></a>
                                                        </td>
                                                        <td>
                                                            <?=mb_strtoupper($quejoso->n_municipio)?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-hover table-bordered">
                                            <caption class="bg-gray text-center">Datos del presunto responsable(s)</caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th>Nombre Completo</th>
                                                    <th>Procedencia</th>
                                                    <th>Municipio</th>
                                                    <th>Nivel/Rango</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($r[0]['presuntos'] as $presunto): ?>
                                                    <tr>
                                                        <td>
                                                            <?=mb_strtoupper($presunto->nombre)?>
                                                        </td>
                                                        <td>
                                                            <?=mb_strtoupper($presunto->procedencia)?>
                                                        </td>
                                                        <td>
                                                            <?=mb_strtoupper($presunto->n_municipio)?>
                                                        </td>
                                                        <td>
                                                            <?=mb_strtoupper($presunto->cargo_id)?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-bordered table hover">
                                            <caption class="bg-gray text-center">Unidades implicadas</caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th>Procedencia</th>
                                                    <th>Tipo de vehículo</th>
                                                    <th>Número económico</th>
                                                    <th>Placas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($r[0]['unidades'] as  $unidad): ?>
                                                <tr>
                                                    <td> <?=$unidad->procedencia?> </td>
                                                    <td> <?=$unidad->t_vehiculo?> </td>
                                                    <td> <?=$unidad->n_eco?>  </td>
                                                    <td> <?=mb_strtoupper($unidad->placas,'utf-8')?> </td>
                                                </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-hover table-bordered">
                                            <caption class="bg-gray text-center">Actuaciones (Oficios generados).</caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th>Número de Oficio</th>
                                                    <th>Fecha de solicitud</th>
                                                    <th>Institución destinataria</th>
                                                    <th>Documentacion solicitada</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <caption class="bg-gray text-center">Expedientes acumulados</caption>

                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>#</th>
                                                        <th>Expediente</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; foreach ($r[0]['acumuladas'] as   $key => $acumulado): ?>
                                                    <tr class="bg-gray">
                                                        <td> <?=$i;$i++;?> </td>
                                                        <td>
                                                            <a href="index.php?menu=cedula&exp_id=<?=$acumulado->acumulado_id?>" target="_blank">
                                                                <?=$acumulado->acumulado?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="dr"> 
                            <h3 class="bg-gray"><center>INFORMACIÓN DE SUBDIRECCIÓN DE ANÁLISIS Y PROCEDIMIENTOS ADMINISTRATIVOS.</center></h3> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h1> <center>Opiniones de los abogados.</center> </h1>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>Fecha</th>
                                                            <th>Abogado analista.</th>
                                                            <th>Comentario</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($data['opiniones'] as $key => $opinion): ?>
                                                        <tr class="bg-gray">
                                                            <td><?=$opinion->created_at?></td>
                                                            <td><?=$opinion->abogado?></td>
                                                            <td><?=$opinion->comentario?></td>
                                                        </tr>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h1> <center>Devoluciones.</center> </h1>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-condesed">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>FECHA</th>
                                                            <th>OFICIO</th>
                                                            <th>MOTIVO</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                         <?php foreach ($data['devoluciones'] as   $key => $devuelto): ?>
                                                            <tr class="bg-gray">
                                                                <td><?=$devuelto->f_acuse?></td>
                                                                <td><?=$devuelto->oficio?></td>
                                                                <td><?=$devuelto->motivo?></td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                        
                                                    </tbody>
                                                </table>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="bg-gray"><center>INFORMACIÓN DE SUBDIRECCIÓN DE LO CONTENCIOSO</center></h3> 
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-default box-solid "><!-- collapsed-box-->
                                            <div class="box-header with-border ">
                                                <h3 class="box-title">Seguimiento de estado actual de expediente</h3>
                                                <div class="box-tools pull-right">
                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="box-body">
                                                <table class="table table-hover table-bordered">
                                                    <tr>
                                                        <td class="bg-gray">Fecha del primer apersonamiento:</td>
                                                        <?php if ( !empty($data['apersona_uno']->f_apersonamiento) ): ?>
                                                            <td><?=$data['apersona_uno']->f_apersonamiento ?></td>
                                                        <?php else: ?>
                                                            <td> SIN APERSONAMIENTO </td>
                                                        <?php endif ?>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-gray">Resolución de la Comisión de Honor y Justicia:</td>
                                                        <?php if ( !empty($data['resolucion_ape']->fecha) ): ?>
                                                            <td>
                                                                ¿HUBO SANCIÓN?: <b><?=$data['resolucion_ape']->sancion ?></b> <br>
                                                                FECHA DE LA RESOLUCIÓN: <b><?=$data['resolucion_ape']->fecha ?></b>
                                                            </td>
                                                        <?php else: ?>
                                                            <td> SIN RESOLUCIÓN </td>
                                                        <?php endif ?>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-gray">Situación de demandas:</td>
                                                        <?php if ( $data['demandas'] != false): ?>
                                                            <td>
                                                            <?php foreach ($data['demandas'] as $key => $dem): ?>
                                                                    <ul>
                                                                        <li>
                                                                            <?=$dem->t_demanda ?>
                                                                            <ol>
                                                                                <li> <b>OFICIO:</b> <?=$dem->oficio ?> </li>
                                                                                <li> <b>ESTADO</b>: <?=$dem->estado ?> </li>
                                                                            </ol>
                                                                        </li>
                                                                    </ul>
                                                            <?php endforeach ?>
                                                            </td>
                                                        <?php else: ?>
                                                            <td> SIN DEMANDAS REGISTRADAS </td>
                                                        <?php endif ?>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-gray">CONTADOR DESDE LA PRIMER RESOLUCIÓN HASTA LA PRIMER DEMANDA</td>
                                                        <?php if ( $data['c_res_dem'] != false ): ?>
                                                            <td><?=$data['c_res_dem']?></td>    
                                                        <?php else: ?>
                                                            <td>SIN DEFINIR</td>
                                                        <?php endif ?>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-gray">CONTADOR DESDE LA RESOLUCIÓN DE LA PRIMER DEMANDA HASTA LA SEGUNDA DEMANDA</td>
                                                        <?php if ( $data['c_rdem_dem2'] != false ): ?>
                                                            <td><?php print_r("sex".$data['c_rdem_dem2']);?></td>
                                                        <?php else: ?>
                                                            <td>SIN DEFINIR</td>
                                                        <?php endif ?>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-gray">CONTADOR DESDE LA FECHA DE LA SEGUNDA DEMANDA HASTA LA RESOLUCIÓN DE LA MISMA</td>
                                                        <?php if ( $data['c_rdem2_res2'] != false ): ?>
                                                            <td><?=$data['c_rdem2_res2']?></td>
                                                        <?php else: ?>
                                                            <td>SIN DEFINIR</td>
                                                        <?php endif ?>
                                                    </tr>
                                                </table>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <caption class="bg-gray">
                                                    LISTADO DE DEMANDAS 
                                                </caption>
                                                <thead>
                                                    <tr>
                                                        <th>Demanda</th>
                                                        <th>Oficio</th>
                                                        <th>Fecha del oficio</th>
                                                        <th>Fecha del acuse</th>
                                                        <th>Dependencia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($demandas as $key => $demanda): ?>
                                                    <tr>
                                                        <td><?=$demanda->t_demanda?></td>
                                                        <td><?=$demanda->oficio?></td>
                                                        <td><?=$demanda->f_oficio?></td>
                                                        <td><?=$demanda->f_acuse?></td>
                                                        <td><?=$demanda->dependencia?></td>
                                                    </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-hover table-bordered"> 
                                            <caption class="bg-gray text-center">
                                                Listado de Documentos 
                                            </caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th width="30%">Nombre de documento</th>
                                                    <th width="60%">Descripcion del documento</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($data['documentos'] as $file): ?>
                                                <tr id="file_<?=$file->id?>">
                                                    <td>
                                                        <a href="controller/puente.php?option=4&file=<?=$file->id?>" target="__blank">
                                                            <?=$file->nombre?>  
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?=$file->descripcion?>
                                                    </td>
                                                </tr>
                                                
                                            <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>