<?php
require_once 'model/Connection.php';
require_once 'model/QDModel.php';
require_once 'model/DRModel.php';
require_once 'model/UAIModel.php';

if(!empty($_GET['exp'])){
    $queja_id = $_GET['exp'];
    $q = new QDModel;
    $m = new DRModel;
    $uai = new UAIModel;
    $r = $q->getQDOnly($queja_id);
    $data = $m->getCedula($queja_id);
    $tbl_ctrl = $uai->getContadores($queja_id);
    $demandas = $uai->getDataSC($queja_id);
    $documentos = $m->getDocumentos($queja_id);
    #Historial de asiganciones
    $as = $q->getAsignaciones($queja_id);    
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
                                            <?=(empty($tbl_ctrl['estado'])) ? 'SIN INFORMACIÓN' : $tbl_ctrl['estado'] ; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" width="35%">Días transcurridos desde la apertura:</th>
                                        <td class="text-center">
                                            <?=$tbl_ctrl['d_apertura']; ?>
                                            
                                        </td>
                                        <th class="text-left" width="35%">Días trabajados por la Dirección de Investigación: </th>
                                        <td class="text-center">
                                            <?=(empty($tbl_ctrl['d_hechos'])) ? 'SIN INFORMACIÓN' : $tbl_ctrl['d_apertura'] ; ?>
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" width="35%">Días transcurridos desde fecha de hechos:</th>
                                        <td class="text-center">
                                            
                                                <?=(empty($tbl_ctrl['d_hechos'] )) ? 'SIN INFORMACIÓN' : $tbl_ctrl['d_hechos']  ; ?>
                                            </td>
                                        <th class="text-left" width="35%">Días trabajados por la Dirección de Responsabilidades en A. I.: </th>
                                        <td class="text-center">
                                            <?=(empty($tbl_ctrl['d_dr'] )) ? 'SIN INFORMACIÓN' : $tbl_ctrl['d_dr']  ; ?>
                                            
                                        </td>
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
                                    <tr class="bg-gray">
                                        <th class="text-center">Subdirección</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Descripción de la resolución</th>
                                    </tr>
                                    <?php foreach ($demandas as $key => $demanda): ?>
                                        
                                        <tr>
                                            <th class="bg-gray">Subdirección de lo Contencioso</th>
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
                            <li><a href="#dr" data-toggle="tab">Dirección de Responsabilidades en A. I.</a></li>                 
                            <li class="pull-left header"><i class="fa fa-th"></i> Panel Integral de Cédulas Generales por Dirección</li>
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
                                                        <th>Tipo de Trámite</th>
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
                                                            <?php if ( isset($r[0]['conductas'][0]->n_ley) ): ?>
                                                                <?php if ( empty($r[0]['conductas'][0]->n_ley)): ?>
                                                                    LEY NO APLICABLE
                                                                <?php else: ?>
                                                                    <?=$r[0]['conductas'][0]->n_ley?>  
                                                                <?php endif ?>
                                                                  
                                                            <?php else: ?>
                                                                <a href='index.php?menu=cedula_old&cve_exp=<?=$r[0]['cve_exp'] ?>' target="__blank">LEY DE RESPONSABILIDADES ADMINISTRATIVAS DEL ESTADO DE MÉXICO Y MUNICIPIOS </a>
                                                            <?php endif ?>
                                                            
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <table id="tbl_asignaciones" class="table table-hover table-bordered">
                                            <caption class="bg-gray text-center"> <B>HISTORIAL DE ASIGNACIONES</B> </caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th>NOMBRE COMPLETO </th>
                                                    <th>FECHA DE ASIGNACIÓN</th>                       
                                                    <th>ESTADO </th>                       
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($as as $key => $asi): ?>
                                                    <tr>
                                                       <td><?=$asi->turnado_a?></td> 
                                                       <td><?=$asi->f_turno?></td>
                                                        <?php if ($asi->estado == 'VENCIDO'): ?>
                                                            <td>TERMINDADO</td>      
                                                        <?php else: ?> 
                                                            <td>EN PROCESO</td>   
                                                        <?php endif ?> 
                                                       
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                           <!--  <tfoot>
                                               <tr class="bg-gray">
                                                   <th class="text-right" colspan="2">TOTAL: </th>
                                                   <th class="text-center"></th>
                                               </tr> -->
                                            </tfoot>
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
                                                    <th>Conducta</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php if (count($r[0]['conductas']) > 0): ?>
                                                <?php
                                                    foreach ($r[0]['conductas'] as $key => $conducta) {
                                                        echo '<tr>';
                                                            echo '<td>'.(++$key).'</td>';
                                                            echo '<td>'.mb_strtoupper($conducta->nombre,'utf-8').'</td>';
                                                        echo '</tr>';
                                                    }
                                                ?>
                                            <?php else: ?>
                                                <tr class="text-center">
                                                    <td colspan="2">CONDUCTA NO ESPECIFICADA EN LA LEY DE SEGURIDAD
                                                   </td>
                                                </tr>
                                            <?php endif ?>
                                                 
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php print_r($r[0]);exit;?>
                                    <div class="col-md-6">
                                        <table class="table table-hover table-bordered">
                                            <caption class="bg-gray text-center">
                                            Vías de recepción
                                            </caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th>#</th>
                                                    <th>Nombre</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                for ($i=0; $i < count($r[0]['vias']) ; $i++) { 
                                                    $conta = $i +1;
                                                    echo '<tr>';
                                                        echo "<td>".$conta."</td>";
                                                        echo "<td>".$r[0]['vias'][$i]->via."</td>";
                                                    echo '</tr>';
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
                                                    <th>Edificación</th>
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
                                                            <?=mb_strtoupper($presunto->n_procedencia)?>
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
                                                
                                                <?php if ( gettype($r[0]['unidades']) == 'array' ): ?>
                                                    <?php foreach ($r[0]['unidades'] as  $unidad): ?>
                                                    <tr>
                                                        <td> <?=$unidad->procedencia?> </td>
                                                        <td> <?=$unidad->t_vehiculo?> </td>
                                                        <td> <?=$unidad->n_eco?>  </td>
                                                        <td> <?=mb_strtoupper($unidad->placas,'utf-8')?> </td>
                                                    </tr>
                                                    <?php endforeach ?>
                                                <?php endif ?>
                                                
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
                                                    <th>Destino</th>
                                                    <th>Descripción</th>
                                                </tr>
                                            </thead>
                                            <?php if ( count($data['oficios_generados']) > 0 ): ?>
                                                <?php foreach ($data['oficios_generados'] as $key => $of): ?>
                                                <tr>
                                                    <td> <?=$of->no_oficio?> </td>
                                                    <td> <?=$of->fecha_oficio?> </td>
                                                    <td> <?=$of->cargo?> </td>
                                                    <td> <?=$of->asunto?> </td>
                                                </tr>    
                                                <?php endforeach ?>
                                                
                                            <?php endif ?>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <caption class="bg-gray text-center">
                                                Expedientes acumulados
                                                </caption>

                                                <thead>
                                                    <tr class="bg-gray">
                                                        <th>#</th>
                                                        <th>Expediente</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; foreach ($r[0]['acumuladas'] as   $key => $acumulado): ?>
                                                    <tr class="bg-gray">
                                                        <td> <?=$i;$i++;?> </td>
                                                        <td>
                                                            <a href="index.php?menu=cedula&exp=<?=$acumulado->acumulado_id?>" target="_blank">
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
                                                <h1> <center>Opiniones de los abogado(s) responsable(s).</center> </h1>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>Fecha</th>
                                                            <th>Abogado responsable.</th>
                                                            <th>Comentario</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if ( $data['opiniones'] != 0 ): ?>
                                                            <?php foreach ($data['opiniones'] as $key => $opinion): ?>
                                                            <tr class="bg-gray">
                                                                <td><?=$opinion->created_at?></td>
                                                                <td><?=$opinion->abogado?></td>
                                                                <td><?=$opinion->comentario?></td>
                                                            </tr>
                                                            <?php endforeach ?>
                                                        <?php endif ?>
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
                                                                            <?=( $dem->t_demanda == 'RECURSO DE REVISION') ?  'RECURSO DE REVISIÓN' : $dem->t_demanda ; ?>
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
                                                        <td class="bg-gray">CONTADOR DESDE LA PRIMER RESOLUCIÓN HASTA IMPUGNACIÓN SALA REGIONAL</td>
                                                        <?php if ( $data['c_res_dem'] != false ): ?>
                                                            <td><?=$data['c_res_dem']?></td>    
                                                        <?php else: ?>
                                                            <td>SIN DEFINIR</td>
                                                        <?php endif ?>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-gray">CONTADOR DESDE LA RESOLUCIÓN DE LA IMPUGNACIÓN SALA REGIONAL HASTA IMPUGNACIÓN SALA SUPERIOR</td>
                                                        <?php if ( isset($data['c_rdem_dem2']) ): ?>
                                                            <td><?php print_r("".$data['c_rdem_dem2']);?></td>
                                                        <?php else: ?>
                                                            <td>SIN DEFINIR</td>
                                                        <?php endif ?>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-gray">CONTADOR DESDE LA FECHA DE IMPUGNACIÓN SALA SUPERIOR HASTA LA RESOLUCIÓN DE LA MISMA</td>
                                                        <?php if ( isset($data['c_rdem2_res2']) ): ?>
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
                                                <caption class="bg-gray text-center">
                                                    Listado de demandas
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
                                                        <td><?=( $dem->t_demanda == 'RECURSO DE REVISION') ?  'RECURSO DE REVISIÓN' : $dem->t_demanda ; ?></td>
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
                                                    <th width="60%">Descripción del documento</th>
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
                                <div class="row">
                                    <div class="col-md-8">
                                        <table class="table table-hover table-bordered">
                                            <caption class="bg-gray text-center">
                                                Documentos de asignación de la Subdirección de Análisis y Procedimientos Administrativos.
                                            </caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th width="25%">Número de oficio</th>
                                                    <th width="15%">Fecha del oficio</th>
                                                    <th width="15%">Fecha del acuse</th>
                                                    <th width="45%">Comentario</th>
                                                    <th width="45%">Origen</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php if ( !is_null($documentos['doc_sc']) ): ?>
                                                <?php foreach ($documentos['doc_sc'] as $key => $doc): ?>
                                                    <tr>
                                                        <td>
                                                            <a href="controller/puente.php?option=17&doc=<?=$doc->id?>&tbl=documentos_sc" target="_blank"><?=$doc->oficio?></a>
                                                        </td>
                                                        <td><?=$doc->f_oficio?></td>
                                                        <td><?=$doc->f_acuse?></td>
                                                        <td><?=$doc->comentario?></td>
                                                        <td>Subdirección de lo Contencioso</td>
                                                    </tr>
                                                <?php endforeach ?>    
                                                <?php endif ?>
                                                <?php if ( isset($documentos['doc_sapa']) ): ?>
                                                    <?php foreach ($documentos['doc_sapa'] as $key => $doc): ?>
                                                        <tr>
                                                            <td>
                                                                <a href="controller/puente.php?option=17&doc=<?=$doc->id?>&tbl=documentos_turno" target="_blank"><?=$doc->oficio?></a>
                                                            </td>
                                                            <td><?=$doc->f_oficio?></td>
                                                            <td><?=$doc->f_acuse?></td>
                                                            <td><?=$doc->comentario?></td>
                                                            <td>Subdirección de Análisis y Procedimientos Administrativos</td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                <?php endif ?>
                                                
                                                
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