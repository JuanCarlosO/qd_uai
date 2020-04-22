<?php
#generar la consulta de la info
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
$queja_id = $_GET['exp'];
$q = new DRModel;
$data = $q->getCedula($queja_id);
#echo "<pre>";print_r($data);echo "</pre>";
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
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>Fecha y hora de hechos: </dt>
                                <dd> <?=$data['queja']->f_hechos." y ".$data['queja']->h_hechos ?> </dd>

                                <dt>Tipo de trámite</dt>
                                <dd><?=$data['queja']->n_tramite;?></dd>

                                <dt>Presuntas conductas</dt>
                                <dd>
                                    <ol>
                                        <?php
                                        foreach ($data['p_conductas'] as $key => $conducta) {
                                            echo '<li>'.$conducta->n_conducta.'</li>';
                                        }
                                        ?>
                                    </ol>
                                </dd>

                                <dt>Ley aplicada</dt>
                                <dd> <?=$data['p_conductas'][0]->n_ley?> </dd>

                                <dt>Via(s) de recepcion</dt>
                                <dd>
                                    <?php
                                    foreach ($data['vias'] as $key => $via) {
                                        echo '<li>'.$via->n_via.'</li>';
                                    }
                                    ?>
                                </dd>
                                <dt>Descripcion de los hechos</dt>
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
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Documentos del expediente</center> </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="30%">Nombre de documento</th>
                                            <th width="60%">Descripcion del documento</th>
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
                            <ol>
                                
                            </ol>
                        </div>
                    </div>
                            
                </div>
            </div>
        </div>
    </div>
</section>