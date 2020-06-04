<?php
#generar la consulta de la info
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
$queja_id = $_GET['exp_id'];
$q = new DRModel;
$data = $q->getCedula($queja_id);
#echo "<pre>";print_r($data);echo "</pre>";
if ($data['qr']->e_procesal == 'DEVUELTO') {
    $mayor = date('Y-m-d');
    $menor = $data['devolucion']->f_devolucion;
    $resta = $q->operacionesFechas('-',$mayor,$menor);
    
}
$hoy = date('Y-m-d');
$c_apertura     = $q->operacionesFechas('-',$hoy,$data['queja']->f_apertura);
$c_hechos       = $q->operacionesFechas('-',$hoy,$data['queja']->f_hechos);
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
                            <?php if ($data['qr']->e_procesal == 'DEVUELTO'): ?>
                                    <h4> <u>DÍAS EN DEVOLUCIÓN:</u> <label> <?=$resta->resta?> </label> </h4> 
                            <?php endif ?>
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
                    </div>
                    <h2 class="bg-gray"> <center> <u>Dirección de Investigación</u> </center> </h2>
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
                                <dd>  <?=$data['queja']->n_estado?> 
                                
                                </dd>

                                <dt>Prioridad</dt>
                                <dd> <?=$data['queja']->prioridad?>  </dd>
                                <dt>Asignado a (D.I.): </dt>
                                <dd> <?=$data['turnado']?>  </dd>
                            </dl>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Ubicación de los hechos</center> </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-condesed table-hover">
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
                                                <li> <label>Edificacion: </label> <?=mb_strtoupper($data['ubicacion']->edificacion)?></li>
                                                <li><label>Número: </label><?=mb_strtoupper($data['ubicacion']->numero)?></li>
                                            </ol>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>      
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Datos del quejoso(s)</center> </h1>
                        </div>
                    </div>
                    <table class="table table-condesed table-hover">
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
                                            <label>Númmero Interior: </label> <?=mb_strtoupper($quejoso->n_int)?>
                                        </li>
                                        <li>
                                            <label>Númmero Exterior: </label> <?=mb_strtoupper($quejoso->n_ext)?> 
                                        </li>
                                    </ol>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
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
                                    <h1> <center>Datos de las unidades</center> </h1>
                                </div>
                            </div>
                            <table class="table table-condesed table-hover">
                                <thead>
                                    <tr class="bg-info">
                                        <th>#</th>
                                        <th>PROCEDENCIA</th>
                                        <th>TIPO </th>
                                        <th>PLACAS</th>
                                        <th>NÚMERO ECONOMICO</th>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <h1> <center>Actuaciones (Oficios generados).</center> </h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <dl class="dl-horizontal">
                                        <dt>Número de Oficio</dt>
                                        <dd> Perro </dd>
                                        <dt>Fecha de solicitud</dt>
                                        <dd> Pastor Malinoins Belga </dd>
                                        <dt>Institución destinataria</dt>
                                        <dd> Rambo </dd>
                                        <dt>Documentacion solicitada</dt>
                                        <dd> 3 años </dd>
                                    </dl>
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
                     <h2 class="bg-gray"> <center> <u>Dirección de Responsabilidades</u> </center> </h2>
                     <div class="row">
                         <div class="col-md-6">
                             <div class="row">
                                 <div class="col-md-12">
                                     <h1> <center>Observaciones del abogado.</center> </h1>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-md-12">
                                     <table class="table table-hover table-bordered">
                                         <thead>
                                             <tr class="bg-info">
                                                 <th>Fecha de observación</th>
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
                                     <h1> <center>Devoluciones a D.I.</center> </h1>
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
                                                 <th>VER DOCUMENTO</th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                              <?php foreach ($data['devoluciones'] as   $key => $devuelto): ?>
                                                 <tr class="bg-gray">
                                                     <td><?=$devuelto->f_acuse?></td>
                                                     <td><?=$devuelto->oficio?></td>
                                                     <td><?=$devuelto->motivo?></td>
                                                     <td>
                                                        <a href="controller/puente.php?option=4B&dev=<?=$devuelto->id?>" class="btn btn-success btn-flat" target="__blank">
                                                            <i class="fa fa-eye"></i>
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
                </div>
            </div>
        </div>
    </div>
</section>
    
