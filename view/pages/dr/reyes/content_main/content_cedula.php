<?php
#error_reporting(0);
#generar la consulta de la info
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
$queja_id = $_GET['exp'];
$q = new DRModel;
$data = $q->getCedula($queja_id);
#echo "<pre>";print_r($data);echo "</pre>";
$hoy = date('Y-m-d');
$c_apertura     = $q->operacionesFechas('-',$hoy,$data['queja']->f_apertura);
$c_hechos       = $q->operacionesFechas('-',$hoy,$data['queja']->f_hechos);

if (!empty($data['apersonamiento'][0]->f_apersonamiento)) {
    $c_primer_ap       = $q->operacionesFechas('-',$data['apersonamiento'][0]->f_apersonamiento,$data['f_turno'])->resta;
}else{
    $c_primer_ap = "NO ESTÁ DEFINIDA";
}
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
                            <h1> <center>Expediente con número: <u> <?=$data['queja']->cve_exp ?> </u></center> </h1>
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
                                        <th class="bg-gray">Contador desde el primer apersonamiento</th>
                                        <td class="bg-info"><?=$c_primer_ap?></td>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="bg-gray">Contador Fecha apersonamiento hasta fecha resolución</th>
                                        <td class="bg-info"><?#( empty($data['conta_2']) ? '0' : $data['conta_2'])?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-default box-solid "><!-- collapsed-box-->
                                <div class="box-header with-border ">
                                    <h3 class="box-title">Seguimiento del estado actual de expediente</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <table class="table table-hover table-bordered">
                                        <?php if ( $data['demandas'] == false ): ?>
                                            <h1>SIN DATOS PARA MOSTRAR</h1>
                                        <?php else: ?>
                                                
                                            <?php if ( $data['apersonamientos'] == NULL ): ?>
                                                <tr>
                                                    <td class="bg-gray" width="200px">Apersonamientos:</td>
                                                    <td> SIN APERSONAMIENTO </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php $i = 1; foreach ($data['apersonamientos'] as $key => $aper): ?>
                                                    <tr>
                                                        <td class="bg-gray" width="200px">Apersonamiento #<?=($i++)?>:</td>
                                                        <td><?=$aper->f_apersonamiento; ?></td>
                                                    </tr>
                                                <?php endforeach ?>
                                                
                                            <?php endif ?>
                                            
                                            <tr>
                                                <td class="bg-gray">Resolución de la Comisión de Honor y Justicia:</td>
                                                <?php if ( !empty($data['resolucion_ape']->fecha) ): ?>
                                                    <td>
                                                        ¿HUBO SANCIÓN?: <b><?=$data['resolucion_ape']->sancion ?></b> <br>
                                                        FECHA DE LA RESOLUCIÓN: <b><?=$data['resolucion_ape']->fecha ?></b>
                                                        <br>
                                                        <b>DESCRIPCIÓN DE LA RESOLUCIÓN: </b> <?=$data['resolucion_ape']->comentario?>

                                                    </td>
                                                <?php else: ?>
                                                    <td> SIN RESOLUCIÓN </td>
                                                <?php endif ?>
                                            </tr>
                                            <tr>
                                                <td class="bg-gray">Medios de Impugnación del TRIJAEM:</td>
                                                <?php if ( $data['demandas'] != false): ?>
                                                    <td>
                                                    <?php foreach ($data['demandas'] as $key => $dem): ?>
                                                            <ul>
                                                                <li>
                                                                    <?=$dem->t_demanda ?>
                                                                    <ol>
                                                                        <li> <b>OFICIO:</b> <?=$dem->oficio ?> </li>
                                                                        <li> <b>ESTADO</b>: <?=$dem->estado ?> </li>
                                                                        <li> <b>FECHA DE DEMANDA</b>: <?=$dem->f_acuse ?> </li>
                                                                        <li> <b>FECHA DE RESOLUCIÓN</b>: <?=$dem->f_resolucion ?> </li>
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
                                                <td class="bg-gray">CONTADOR DESDE LA RESOLUCIÓN DE IMPUGNACIÓN SALA REGIONAL HASTA IMPUGNACIÓN SALA SUPERIOR</td>
                                                <?php if ( isset($data['c_rdem_dem2']) ): ?>
                                                    <?php if ( $data['c_rdem_dem2'] != false ): ?>
                                                        <td><?php print_r("sex".$data['c_rdem_dem2']);?></td>
                                                    <?php endif ?>
                                                <?php else: ?>
                                                    <td>SIN DEFINIR</td>
                                                <?php endif ?>
                                                
                                            </tr>
                                            <tr>
                                                <td class="bg-gray">CONTADOR DESDE LA FECHA DE LA SEGUNDA DEMANDA HASTA LA RESOLUCIÓN DE LA MISMA</td>
                                                
                                                <?php if ( isset($data['c_rdem2_res2']) && $data['c_rdem2_res2'] != false ): ?>
                                                    <td><?=$data['c_rdem2_res2']?></td>
                                                <?php else: ?>
                                                    <td>SIN DEFINIR</td>
                                                <?php endif ?>
                                            </tr>
                                        <?php endif ?>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
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

                                <dt>Vía(s) de recepcion</dt>
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
                            <h1> <center>Datos del presunto responsable(s)</center> </h1>
                        </div>
                    </div>
                    <div id="p_responsables">
                        <table class="table table-condesed table-hover">
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
                                            <?php if ($data['opiniones'] != 0): ?>
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
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h1> <center>Expedientes acumulados</center> </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-condesed table-hover">
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
                                    <caption class="text-center bg-gray"><center>Documentos del expediente</center></caption>
                                    <thead class="bg-gray">
                                        <tr>
                                            <th width="30%">Nombre de documento</th>
                                            <th width="60%">Descripción del documento</th>
                                            <!-- <th width="10%" class="text-center"><i class="fa fa-trash"></i></th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($data['documentos'] as $file): ?>
                                        <tr id="file_<?=$file->id?>" class="">
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
                            <ol>
                                
                            </ol>
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
                                        <?php if ( isset($documentos['doc_sc'])): ?>
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