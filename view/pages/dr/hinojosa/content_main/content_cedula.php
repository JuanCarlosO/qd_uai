<?php
#generar la consulta de la info
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
$queja_id = $_GET['exp_id'];
$q = new DRModel;
$data = $q->getCedula($queja_id);
#generar consulta de sanciones y verificaciones
$san = $q->getFullSanciones($queja_id);
$ver = $q->getFullVerificaciones($queja_id);

#echo "<pre>";print_r($data);echo "</pre>";
if ($data['qr']->e_procesal == 'DEVUELTO') {
    $mayor = date('Y-m-d');
    $menor = $data['devolucion']->f_devolucion;
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
                                        <td class="bg-gray"><?=$c_apertura->resta?></td>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="bg-gray">Días transcurridos desde la fecha de hechos</th>
                                        <td class="bg-gray"><?=$c_hechos->resta?></td>
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
                                <?php if ( isset($r[0]['conductas'][0]->n_ley) ): ?>
                                    <?=$r[0]['conductas'][0]->n_ley?>    
                                <?php else: ?>
                                    <dd>LEY NO APLICABLE</dd>
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
                                    <tr class="bg-gray">
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
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Datos del quejoso(s)</center> </h1>
                        </div>
                    </div>
                    <table class="table table-condesed table-hover">
                        <thead>
                            <tr class="bg-gray">
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
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Datos del presunto responsable(s)</center> </h1>
                        </div>
                    </div>
                    <div id="p_responsables">
                        <table class="table table-condesed table-hover table-bordered">
                            <thead>
                                <tr class="bg-gray">
                                    <th>#</th>
                                    <th>NOMBRE COMPLETO</th>
                                    <th>PROCEDENCIA</th>
                                    <th>MUNICIPIO</th>
                                    <th>CARGO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['presuntos'] as $key => $presunto): ?>
                                <tr class="">
                                    <td><?=(++$key)?></td>
                                    <td><?=mb_strtoupper($presunto->nombre)?></td>
                                    <td><?=mb_strtoupper($presunto->n_procedencia)?></td>
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
                                    <h1>  </h1>
                                </div>
                            </div>
                            <table class="table table-condesed table-hover">
                                <caption>
                                    <center>Datos de las unidades</center>
                                </caption>
                                <thead>
                                    <tr class="bg-gray">
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
                                <caption class="text-center bg-gray"> <b>ACTUACIONES (OFICIOS GENERADOS).</b> </caption>
                                <thead>
                                    <tr class="bg-gray">
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
                                <tr class="bg-gray">
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
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-condensed table-bordered">
                                    <caption class="text-center bg-gray"><center>Documentos del expediente</center></caption>
                                    <thead>
                                        <tr>
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
                            <ol>
                                
                            </ol>
                        </div>
                        
                    </div>
                    <h2 class="bg-gray"> <center> <u>Dirección de Responsabilidades en Asuntos Internos</u> </center> </h2>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <caption class="text-center bg-gray ">
                                    <b>LISTADO DE SANCIONES</b>
                                </caption>
                                <thead>
                                    <tr class="bg-gray">
                                        <th width="30%">Datos del responsable</th>
                                        <th>Número de oficio</th>
                                        <th>Fecha de determinación</th>
                                        <th>Sanción</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (count($san) > 0): ?>
                                    <?php foreach ($san as $key => $s): ?>
                                        <tr>
                                            <td>
                                                <ul>
                                                    <li><label>Nombre:</label><?=$s->nombre_pr ?></li>
                                                    <li><label>Adscripción: </label><?=$s->adscripcion ?></li>
                                                    <li><label>RFC:</label><?=$s->rfc ?></li>
                                                    <li><label>CURP:</label><?=$s->curp ?></li>
                                                    <li><label>CUIP:</label><?=$s->cuip ?></li>
                                                </ul>
                                            </td>
                                            <td><?=$s->oficio ?></td>
                                            <td><?=$s->f_determina ?></td>
                                            <td><?=$s->castigo ?> </td>
                                            <td>
                                                <p class="text-justify"><?=$s->comentario ?></p>
                                            </td>
                                            
                                        </tr>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <tr class="text-center">
                                        <td colspan="5">
                                            NO HAY RESULTADOS
                                        </td>
                                    </tr>
                                <?php endif ?>
                                
                                </tbody>
                            </table>
                         </div>
                     </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <caption class="text-center bg-gray ">
                                    <b>VERIFICACIONES DE CUMPLIMIENTO </b>
                                </caption>
                                <thead>
                                    <tr class="bg-gray">
                                        <th>Verificación para</th>
                                        <th>Notificacion al ser. pub.</th>
                                        <th>Notificación a R.H.</th>
                                        <th>Captura en RNPSP</th>
                                        <th>Fecha de ejecución</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($ver) > 0): ?>
                                        <?php foreach ($ver as $key => $v): ?>
                                            <tr>
                                                <td><?=$v->nombre_pr ?></td>
                                                <td>
                                                    <?=$v->f_not_sp ?>
                                                </td>
                                                <td><?=$v->f_not_rh ?></td>
                                                <td><?=$v->f_cpt_rnpsp ?></td>
                                                <td><?=$v->f_ejec ?></td>
                                                
                                                <td>
                                                    <p class="text-justify"><?=$s->comentario ?></p>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
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
                                             <tr class="bg-gray">
                                                 <th>Fecha de observación</th>
                                                 <th>Abogado analista.</th>
                                                 <th>Comentario</th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                            
                                            <?php if (  gettype($data['opiniones']) == 'array' || gettype($data['opiniones']) == 'object' ): ?>
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
                                     <h1> <center>Devoluciones a D.I.</center> </h1>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-md-12">
                                     <table class="table table-condesed">
                                         <thead>
                                             <tr class="bg-gray">
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
</section>
    
