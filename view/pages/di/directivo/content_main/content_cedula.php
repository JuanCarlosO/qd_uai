<?php
#generar la consulta de la info
require_once 'model/Connection.php';
require_once 'model/QDModel.php';
$queja_id = $_GET['exp'];

$q = new QDModel;
$r = $q->getQDOnly($queja_id);
#echo "<pre>";print_r($r);#exit;
#Historial de asiganciones
$as = $q->getAsignaciones($queja_id);

#Marcar como leido como el expediente 
$q->readExp($queja_id);
#echo "<pre>";print_r($r);echo "</pre>";
$hoy = date('Y-m-d');
$c_apertura     = $q->operacionesFechas('-',$hoy,$r[0]['f_apertura']);
$c_hechos       = $q->operacionesFechas('-',$hoy,$r[0]['f_hechos']);

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
                                <center>
                                    Expediente con número: <u> <?=$r[0]['cve_exp'] ?> </u>
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
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>Fecha y hora de hechos: </dt>
                                <dd> <?=$r[0]['f_hechos']." y ".$r[0]['h_hechos'] ?> </dd>

                                <dt>Tipo de trámite</dt>
                                <dd><?=$r[0]['n_tramite'];?></dd>

                                <dt>Presuntas conductas</dt>
                                <dd>
                                    <?php
                                    
                                    if ( count($r[0]['conductas']) == 0 ){
                                        echo "<li>CONDUCTA NO ESPECIFICADA EN LA LEY DE SEGURIDAD</li>";
                                    }else{
                                        echo "<ol>";
                                        foreach ($r[0]['conductas'] as $key => $conducta) {
                                            echo '<li>'.mb_strtoupper($conducta->nombre).'</li>';
                                        }
                                        echo "</ol>";
                                    }
                                    ?>  
                                </dd>

                                <dt>Ley aplicada</dt>
                                <?php if ( count($r[0]['conductas']) == 0): ?>
                                    <dd><a href='index.php?menu=cedula_old&cve_exp=<?=$r[0]['cve_exp']?>' target="__blank">LEY DE RESPONSABILIDADES ADMINISTRATIVAS DEL ESTADO DE MÉXICO Y MUNICIPIOS </a> </dd>
                                <?php else: ?>
                                    <dd> <?=$r[0]['conductas'][0]->n_ley?> </dd>
                                <?php endif ?>
                                

                                <dt>Vía(s) de recepción</dt>
                                <?php
                                if (count($r[0]['vias']) > 0 ) {
                                    for ($i=0; $i < count($r[0]['vias']) ; $i++) { 
                                        echo '<dd> '.$r[0]['vias'][$i]->via. '</dd>';
                                    }
                                }else{
                                    echo '<dd> NO REGISTRADO </dd>';
                                }
                                
                                ?>
                                <dt>Descripción de los hechos</dt>
                                <dd class="text-justify"> <?=$r[0]['descripcion']?> </dd>

                                <dt>Estado del expediente</dt>
                                <dd>  <?=mb_strtoupper($r[0]['n_estado'])?> </dd>

                                <dt>Prioridad</dt>
                                <dd> <?=mb_strtoupper($r[0]['prioridad'])?>  </dd>
                            </dl>
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
                                <!-- <tfoot>
                                    <tr class="bg-gray">
                                        <th class="text-right" colspan="2">TOTAL: </th>
                                        <th class="text-center"></th>
                                    </tr>
                                </tfoot> -->
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h1>  </h1>
                        </div>
                        <div class="col-md-9">
                            <table class="table table-hover table-bordered">
                                <caption class="bg-gray">
                                    <center> <b>UBICACIÓN DE LOS HECHOS</b> </center>
                                </caption>
                                <thead>
                                    <tr class="bg-gray">
                                        <th>Municipio</th>
                                        <th>Calle principal</th>
                                        <th>Entre calle</th>
                                        <th> Y calle</th>
                                        <th>Edificación y número</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> <?=mb_strtoupper($r[0]['ubicacion']->n_municipio)?>  </td>
                                        <td> <?=mb_strtoupper($r[0]['ubicacion']->calle)?> </td>
                                        <td> <?=mb_strtoupper($r[0]['ubicacion']->e_calle)?>  </td>
                                        <td> <?=mb_strtoupper($r[0]['ubicacion']->y_calle)?>  </td>
                                        <td> 
                                            <ol>
                                                <ul><?=mb_strtoupper($r[0]['ubicacion']->edificacion)?>  </ul>
                                                <ul><?=mb_strtoupper($r[0]['ubicacion']->numero)?></ul>
                                            </ol>
                                            
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                          
                    <div class="row">
                        <div class="col-md-9">
                            <table class="table tabler-hover tabler-bordered">
                                <caption class="bg-gray">
                                    <b><center>DATOS DEL(LOS) QUEJOSO(S)</center></b>
                                </caption>
                                <thead>
                                    <tr class="bg-gray">
                                        <th>Nombre completo</th>
                                        <th>Género</th>
                                        <th>Teléfono</th>
                                        <th>Medios de Localización</th>
                                        <th>Municipio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($r[0]['quejosos'] as $quejoso): ?>
                                        <tr>
                                            <td> <?=mb_strtoupper($quejoso->nombre)?> </td>
                                            <td> <?=mb_strtoupper($quejoso->genero)?> </td>
                                            <td> <?=mb_strtoupper($quejoso->telefono)?> </td>
                                            <td><a href="mailto:<?=mb_strtoupper($quejoso->email)?>"><?=mb_strtoupper($quejoso->email)?></a></td>
                                            <td>
                                                <ol>
                                                    <ul> <?=mb_strtoupper($quejoso->n_municipio)?> </ul>
                                                    <ul> <label>Número Interior: </label> <?=mb_strtoupper($quejoso->n_int)?> 
                                                    </ul>
                                                    <ul> <label>Número Exterior: </label> <?=mb_strtoupper($quejoso->n_ext)?> 
                                                    </ul>
                                                </ol>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="p_responsables">
                        <div class="row">
                            <div class="col-md-9">
                                <table class="table tabler-hover tabler-bordered">
                                    <caption class="bg-gray">
                                        <center> <b>DATOS DEL PRESUNTO RESPONSABLE(S)</b> </center>
                                    </caption>
                                    <thead>
                                        <tr class="bg-gray">
                                            <th>ID</th>
                                            <th>NOMBRE COMPLETO</th>
                                            <th>SEXO </th>
                                            <th>PROCEDENCIA</th>
                                            <th>MEDIA FILIACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($r[0]['presuntos'] as $key => $presunto): ?>
                                                <tr id="tr_pres_<?=$presunto->id?>">
                                                    <td><?=$presunto->id?></td>
                                                    <td><?=$presunto->nombre?></td>
                                                    <td>
                                                        <?php if ($presunto->genero == 'M'): ?>
                                                        HOMBRE
                                                        <?php elseif ($presunto->genero == 'F') : ?>
                                                        MUJER
                                                        <?php endif ?>
                                                    </td>
                                                   <td><?=$presunto->n_procedencia?></td>
                                                    <td><?=$presunto->comentarios?></td>
                                                    <!-- <?php if ($presunto->procedencia == 1 || $presunto->procedencia == 3): ?>
                                                        <td><?=$presunto->n_penal?></td>
                                                    <?php elseif($presunto->procedencia == 2): ?>
                                                        <td>
                                                            <ol>
                                                                <li><?=$presunto->coordinacion?></li>
                                                                <li><?=$presunto->n_subd?></li>
                                                                <li><?=$presunto->n_region?></li>
                                                                <li><?=$presunto->n_agrupamiento?></li>
                                                            </ol>
                                                        </td>
                                                    <?php elseif($presunto->procedencia == 4): ?>
                                                        <td><?=$presunto->adsp?></td>
                                                    <?php endif ?>
                                                     -->
                                                    
                                                </tr>
                                            <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>                      
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-hover table-bordered">
                                <caption class="text-center bg-gray"> <b>DATOS DE LAS UNIDADES.</b> </caption>
                                <thead>
                                    <tr class="bg-gray">
                                        <th class="text-center">Procedencia</th>
                                        <th class="text-center">Tipo de vehículo</th>
                                        <th class="text-center">Número económico</th>
                                        <th class="text-center">Placas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($r[0]['unidades'] as  $unidad): ?>
                                        <td> <?=$unidad->procedencia?> </td>
                                        <td> <?=$unidad->t_vehiculo?> </td>
                                        <td> <?=$unidad->n_eco?>  </td>
                                        <td> <?=mb_strtoupper($unidad->placas,'utf-8')?> </td>
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
                            <table class="table table-hover table-bordered">
                                <caption class="text-center bg-gray"> <b>RESPUESTAS (OFICIOS GENERADOS).</b> </caption>
                                <thead>
                                    <tr class="bg-gray">
                                        <th class="text-center">Oficio</th>
                                        <th class="text-center">Oficio de respuesta</th>
                                        <th class="text-center">Fecha oficio</th>
                                        <th class="text-center">Fecha de respuesta</th>
                                        <th class="text-center">Descripción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-condesed">
                                <caption class="bg-gray text-center">
                                    <b>LISTADO DE DEVOLUCIONES</b>
                                </caption>
                                        <thead>
                                            <tr class="bg-gray">
                                                <th>FECHA</th>
                                                <th>OFICIO</th>
                                                <th>MOTIVO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ( isset($r['devoluciones']) ): ?>
                                                <?php foreach ($r['devoluciones'] as   $key => $devuelto): ?>
                                                    <tr class="bg-gray">
                                                        <td><?=$devuelto->f_acuse?></td>
                                                        <td><?=$devuelto->oficio?></td>
                                                        <td><?=$devuelto->motivo?></td>
                                                    </tr>
                                                <?php endforeach ?>   
                                            <?php endif ?>

                                        </tbody>
                                    </table>
                        </div>
                        <!-- <div class="col-md-6">
                            <table class="table table-hover table-bordered">
                                <caption class="text-center bg-gray"> <b>LISTADO DE DEVOLUCIONES</b> </caption>
                                <thead>
                                    <tr class="bg-gray">
                                        <th class="text-center">Oficio</th>
                                        <th class="text-center">Fecha oficio</th>
                                        <th class="text-center">Fecha devolución</th>
                                        <th class="text-center">Motivo</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div> -->
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" border="1px">
                                    <caption class="bg-gray">
                                        <center>
                                            <b>LISTADO DE EXPEDIENTES ACUMULADOS</b>
                                        </center> 
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
                                                    <a href="index.php?menu=cedula&queja=<?=$acumulado->acumulado_id?>" target="_blank">
                                                        <?=$acumulado->acumulado?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condesed table-hover">
                                    <caption class="bg-gray text-center"> <b>OPINIONES DE ABOGADOS ANALISTAS (D.I.)</b> </caption>
                                    <thead>
                                        <tr class="bg-gray">
                                            <th>#</th>
                                            <th>Descripción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ( isset($r[0]['opiniones']) ): ?>
                                            <?php $ii = 1; foreach ($r[0]['opiniones'] as $key => $opt): ?>
                                                <tr class="bg-gray">
                                                    <td> <?=$ii;$ii++;?> </td>
                                                    <td>
                                                        <?=$opt->comentario;?>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-hover table-condensed table-bordered">
                                        <caption class="bg-gray">
                                            <center>
                                                <b>LISTADO DE DOCUMENTOS</b>
                                            </center> 
                                        </caption>
                                        <thead>
                                            <tr class="bg-gray">
                                                <th width="30%">Nombre de documento</th>
                                                <th width="60%">Descripción del documento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($r[0]['archivos'] as $file): ?>
                                            <tr id="file_<?=$file->id?>" class="info">
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
                                <ol>
                                    
                                </ol>
                            </div>
                    </div>                              
                </div>
            </div>
        </div>
    </div>
</section>
    
