<?php
#generar la consulta de la info
require_once 'model/Connection.php';
require_once 'model/DRModel.php';

$queja_id = $_GET['exp_id'];
$q = new DRModel;
$data = $q->getCedula($queja_id);
#echo "<pre>";print_r($data);echo "</pre>";
if ($data['queja']->estado == '10') {
    $mayor = date('Y-m-d');
    $menor = $data['reserva']->f_reserva;
    $resta = $q->operacionesFechas('-',$mayor,$menor);    
}
$hoy = date('Y-m-d');
$c_apertura     = $q->operacionesFechas('-',$hoy,$data['queja']->f_apertura);
$c_hechos       = $q->operacionesFechas('-',$hoy,$data['queja']->f_hechos);
$documentos     = $q->getDocumentos($queja_id);
?>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Detalle de la cédula</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h1> 
                                <center>Expediente con número: 
                                    <u> <?=$data['queja']->cve_exp ?> </u>
                                </center> 
                            </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th class="bg-gray">Días transcurridos desde la fecha de apertura</th>
                                        <td class="bg-info"><?=$c_apertura->resta?></td>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="bg-gray">Días transcurridos desde la fecha de hechos</th>
                                        <td class="bg-info"><?=$c_hechos->resta?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th class="bg-gray">Días trabajados por la S.A.P.A.</th>
                                        <td class="bg-info"><?=$data['f_sapa']?></td>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="bg-gray">Días trabajados por la S.C.</th>
                                        <td class="bg-info"><?=$data['f_sc']?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-default box-solid "><!-- collapsed-box-->
                                <div class="box-header with-border ">
                                    <h3 class="box-title">Seguimiento de estado actual de expediente</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
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
                                                            <?php if ($dem->t_demanda == 'RECURSO DE REVISION'): ?>
                                                                <li>
                                                                    RECURSO DE REVISIÓN
                                                                    <ol>
                                                                        <li> <b>OFICIO:</b> <?=$dem->oficio ?> </li>
                                                                        <li> <b>ESTADO</b>: <?=$dem->estado ?> </li>
                                                                    </ol>
                                                                </li>
                                                            <?php else: ?>
                                                                <li>
                                                                    <?=$dem->t_demanda;?>
                                                                    <ol>
                                                                        <li> <b>OFICIO:</b> <?=$dem->oficio ?> </li>
                                                                        <li> <b>ESTADO</b>: <?=$dem->estado ?> </li>
                                                                    </ol>
                                                                </li>
                                                            <?php endif ?>
                                                            
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
                                            <td class="bg-gray">CONTADOR DESDE LA RESOLUCIÓN DE LA IMPUGNACIÓN SALA REGIONAL HASTA IMPUGNACIÓN SALA SUPERIOR</td>
                                      
                                            <?php if ( isset($data['c_rdem_dem2']) ): ?>
                                                <td><?php print_r("".$data['c_rdem_dem2']);?></td>
                                            <?php else: ?>
                                                <td>SIN DEFINIR</td>
                                            <?php endif ?>
                                            
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">CONTADOR DESDE LA FECHA DE IMPUGNACIÓN SALA SUPERIOR HASTA LA RESOLUCIÓN DE LA MISMA</td>
                                            <?php if ( isset($data['c_rdem2_res2'] ) ): ?>
                                                <td><?=$data['c_rdem2_res2']?></td>
                                            <?php else: ?>
                                                <td>SIN DEFINIR</td>
                                            <?php endif ?>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">SITUACIÓN DE LOS APERSONAMIENTOS:</td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="bg-gray">
                                                            <th width="25%">Oficio</th>
                                                            <th width="25%">Fecha apersonamiento</th>
                                                            <th width="50%">Descripción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if ( gettype($data['apersonamientos']) != 'NULL' ): ?>
                                                        <?php foreach ($data['apersonamientos'] as $a): ?>
                                                            <tr>
                                                                <td><?=$a->oficio?></td>
                                                                <td><?=(empty($a->f_apersonamiento)) ? 'SIN FECHA' : $a->f_apersonamiento ;?></td>
                                                                <td><?=$a->comentario?></td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                    <?php endif ?>
                                                    
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($data['queja']->estado == '10'): ?>
                        <h4> <u>DÍAS EN RESERVA:</u> <label> <?=$resta->resta?> </label> </h4> 
                    <?php endif ?>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>Fecha y hora de hechos: </dt>
                                <dd> <?=$data['queja']->f_hechos." y ".$data['queja']->h_hechos ?> </dd>

                                <dt>Tipo de trámite</dt>
                                <dd><?=$data['queja']->n_tramite;?></dd>

                                <dt>Presuntas conductas</dt>
                                <dd>
                                    <ol>
                                        <?php if (count($data['p_conductas']) > 0): ?>
                                        <?php
                                        foreach ($data['p_conductas'] as $key => $conducta) {
                                            echo '<li>'.$conducta->n_conducta.'</li>';
                                        }
                                        ?>    
                                        <?php else: ?>
                                            <li>CONDUCTA NO ESPECIFICADA EN LA LEY DE SEGURIDAD</li>
                                        <?php endif ?>
                                        
                                        
                                    </ol>
                                </dd>

                                <dt>Ley aplicada</dt>
                                <?php if ( count($data['p_conductas']) > 0): ?>
                                    <dd> <?=$data['p_conductas'][0]->n_ley?> </dd>    
                                <?php else: ?>
                                    <dd> LEY NO APLICABLE </dd>
                                <?php endif ?>
                                

                                <dt>Vía(s) de recepción</dt>
                                <dd>
                                    <?php
                                    foreach ($data['vias'] as $key => $via) {
                                        echo '<li>'.$via->n_via.'</li>';
                                    }
                                    ?>
                                </dd>
                                <dt>Descripción de los hechos</dt>
                                <dd class="text-justify"> <?=$data['queja']->descripcion?> </dd>

                                <dt>Estado del expediente</dt>
                                <dd>  <?=$data['queja']->n_estado?> </dd>

                                <dt>Prioridad</dt>
                                <dd> <?=$data['queja']->prioridad?>  </dd>
                                <dt>Asignado a (D.I.): </dt>
                                <dd> <?=$data['turnado']?>  </dd>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-condesed table-hover">
                                <caption class="bg-info caption"> <center> <b>Ubicación de los hechos</b> </center> </caption>
                                <thead>
                                    <tr class="bg-info">
                                        <th>#</th>
                                        <th>MUNICIPIO</th>
                                        <th>CALLE PRINCIPAL</th>
                                        <th>ENTRE CALLE</th>
                                        <th>Y CALLE</th>
                                        <th>EDIFICACIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-gray-active">
                                        <td>1</td>
                                        <td><?=$data['ubicacion']->n_municipio?></td>
                                        <td><?=mb_strtoupper($data['ubicacion']->calle)?></td>
                                        <td><?=mb_strtoupper($data['ubicacion']->e_calle)?></td>
                                        <td><?=mb_strtoupper($data['ubicacion']->y_calle)?></td>
                                        <td>
                                            <ol>
                                                <li> <label>Edificación: </label> <?=mb_strtoupper($data['ubicacion']->edificacion)?></li>
                                                <li><label>Número: </label><?=mb_strtoupper($data['ubicacion']->numero)?></li>
                                            </ol>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>      
                    
                    <table class="table table-condesed table-hover">
                        <caption class="bg-info"><b> <center>Datos del quejoso(s)</center> </b></caption>
                        <thead>
                            <tr class="bg-info">
                                <th>#</th>
                                <th>NOMBRE COMPLETO</th>
                                <th>GÉNERO</th>
                                <th>TELÉFONO</th>
                                <th>MEDIOS DE LOCALIZACIÓN</th>
                                <th>DOMICILIO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['quejosos'] as $quejoso): ?>
                            <tr class="bg-gray-active">
                                <td>1</td>
                                <td><?=mb_strtoupper($quejoso->nombre)?></td>
                                <td><?=mb_strtoupper($quejoso->genero)?></td>
                                <td><?=mb_strtoupper($quejoso->telefono)?></td>
                                <td>
                                    <a href="mailto:<?=mb_strtoupper($quejoso->email)?>"><?=mb_strtoupper($quejoso->email)?></a>
                                </td>
                                <td>
                                    <ol>
                                        <li> <label>Municipio: </label> <?=mb_strtoupper($quejoso->n_municipio)?></li>
                                        <li><label>Número: </label><?=mb_strtoupper($data['ubicacion']->numero)?></li>
                                        <li>
                                            <label>Número Interior: </label> <?=mb_strtoupper($quejoso->n_int)?>
                                        </li>
                                        <li>
                                            <label>Número Exterior: </label> <?=mb_strtoupper($quejoso->n_ext)?> 
                                        </li>
                                    </ol>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                   
                    <div id="p_responsables">
                        <table class="table table-condesed table-hover">
                            <caption class="bg-info"><b> <center>Datos del presunto responsable(s)</center> </b></caption>
                            <thead>
                                <tr class="bg-info">
                                    <th>#</th>
                                    <th>NOMBRE COMPLETO</th>
                                    <th>PROCEDENCIA</th>
                                    <th>MUNICIPIO</th>
                                    <th>CARGO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['presuntos'] as $key => $presunto): ?>
                                <tr class="bg-gray">
                                    <td><?=(++$key)?></td>
                                    <td><?=mb_strtoupper($presunto->nombre)?></td>
                                    <td><?=mb_strtoupper($presunto->procedencia)?></td>
                                    <td><?=mb_strtoupper($presunto->n_municipio)?></td>
                                    <td>
                                        <?=mb_strtoupper($presunto->n_cargo)?>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            
                            <table class="table table-condesed table-hover">
                                <caption class="bg-info"><b> <center>Datos de las unidades</center> </b></caption>
                                <thead>
                                    <tr class="bg-info">
                                        <th>#</th>
                                        <th>PROCEDENCIA</th>
                                        <th>TIPO </th>
                                        <th>PLACAS</th>
                                        <th>NÚMERO ECONÓMICO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach ($data['unidades'] as   $key => $unidad): ?>
                                    <tr class="bg-gray-active">
                                        <td> <?=$i;$i++;?> </td>
                                        <td><?=$unidad->procedencia?></td>
                                        <td><?=$unidad->t_vehiculo?></td>
                                        <td><?=mb_strtoupper($unidad->placas,'utf-8')?></td>
                                        <td>
                                            <?=$unidad->n_eco?>
                                        </td>
                                    </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                            
                        </div>
                        <div class="col-md-6">
                            <table class="table table-hover table-bordered">
                                <caption class="text-center bg-info"> <b>Actuaciones (Oficios Generados).</b> </caption>
                                <thead>
                                    <tr class="bg-info">
                                        <th class="text-center">Número de Oficio</th>
                                        <th class="text-center">Fecha de solicitud</th>
                                        <th class="text-center">Institución destinataria</th>
                                        <th class="text-center">Documentación solicitada</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div> 
                    <h3 class="bg-green"><center>INFORMACIÓN DE SUBDIRECCIÓN DE ANÁLISIS Y PROCEDIMIENTOS ADMINISTRATIVOS</center></h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover table-bordered">
                                        <caption  class="bg-info"> <b><center>Opiniones de los abogados.</center></b> </caption>
                                        <thead>
                                            <tr class="bg-info">
                                                <th>Fecha</th>
                                                <th>Abogado analista.</th>
                                                <th>Comentario</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($data['opiniones'] != '0'): ?>
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
                                    <table class="table table-condesed table-bordered">
                                        <caption  class="bg-info"> <b><center>Devoluciones</center></b> </caption>
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
                    <h3 class="bg-green"><center>SUBDIRECCIÓN DE LO CONTENCIOSO</center></h3>
                    
                    <h3 class="bg-green"><center>EXTRAS</center></h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-condesed table-hover">
                                    <caption class="bg-info"><b> <center>Expedientes acumulados</center> </b></caption>
                            <thead>
                                <tr class="bg-info">
                                    <th>#</th>
                                    <th>Expediente</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php $i = 1; foreach ($data['acumuladas'] as   $key => $acumulado): ?>
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
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-condensed table-bordered">
                                    <caption class="bg-info">
                                        <b><center>DOCUMENTOS DEL EXPEDIENTE</center> </b>
                                    </caption>
                                    <thead>
                                        <tr class="bg-info">
                                            <th width="30%">Nombre de documento</th>
                                            <th width="60%">Descripción del documento</th>
                                            <!-- <th width="10%" class="text-center"><i class="fa fa-trash"></i></th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($data['documentos'] as $file): ?>
                                        <tr id="file_<?=$file->id?>" class="bg-gray">
                                            <td>
                                                <a href="controller/puente.php?option=4&file=<?=$file->id?>" target="__blank">
                                                    <?=$file->nombre?>  
                                                </a>
                                            </td>
                                            <td>
                                                <?=$file->descripcion?>
                                            </td>
                                            <!-- <td>
                                                <button type="button" onclick="del_file(<?=$file->id?>);" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td> -->
                                        </tr>
                                        
                                    <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            <table class="table table-hover table-bordered">
                                <caption class="bg-info">
                                    <b><center >APERSONAMIENTOS REALIZADOS</center></b>
                                </caption>
                                <tr class="bg-info">
                                    <th>OFICIO</th>
                                    <th>FECHA DEL OFICIO</th>
                                    <th>FECHA DEL ACUSE</th>
                                    <th>FECHA DEL APERSONAMIENTO</th>
                                    <th>COMENTARIOS</th>
                                </tr>
                                <tbody>
                                    <?php if ($data['apersonamientos'] == '0'): ?>
                                        <?php foreach ($data['apersonamientos'] as $ape): ?>
                                            <tr>
                                                <td><?=( empty($ape->oficio) )  ? 'NO INSERTADO' : $ape->oficio ;?></td>
                                                <td><?=( empty($ape->f_oficio) )  ? 'NO INSERTADO' : $ape->f_oficio ;?></td>
                                                <td><?=( empty($ape->f_acuse) )  ? 'NO INSERTADO' : $ape->f_acuse ;?></td>
                                                <td><?=( empty($ape->f_apersonamiento) )  ? 'NO INSERTADO' : $ape->f_apersonamiento ;?></td>
                                                <td><?=( empty($ape->comentario) )  ? 'NO INSERTADO' : $ape->comentario ;?></td>
                                            </tr>
                                        <?php endforeach ?>    
                                    <?php endif ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-hover table-bordered">
                                    <caption class="bg-info text-center">
                                        <b>Documentos de asignación de la Subdirección de Análisis y Procedimientos Administrativos.</b>
                                    </caption>
                                    <thead>
                                        <tr class="bg-info">
                                            <th width="25%">Número de oficio</th>
                                            <th width="15%">Fecha del oficio</th>
                                            <th width="15%">Fecha del acuse</th>
                                            <th width="45%">Comentario</th>
                                            <th width="45%">Origen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ( isset($documentos['doc_sc']) ): ?>
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
                                        <?php if (isset($documentos['doc_sc'])): ?>
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
</section>