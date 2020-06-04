<?php
require_once 'model/Connection.php';
require_once 'model/QDModel.php';
$queja_id = $_GET['queja'];

$q = new QDModel;
$r = $q->getQDOnly($queja_id);
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
                                        <th class="bg-gray">Días transcurridos desde la fecha de hechos</th>
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
                                	<ol>
                                		<?php
                                        foreach ($r[0]['conductas'] as $key => $conducta) {
                                            echo '<li>'.$conducta->nombre.'</li>';
                                        }
                                		?>
                                	</ol>
                                </dd>

                                <dt>Ley aplicada</dt>
                                <dd> <?=$r[0]['conductas'][0]->n_ley?> </dd>

                                <dt>Via(s) de recepcion</dt>
                                <?php
                                for ($i=0; $i < count($r[0]['vias']) ; $i++) { 
                                	echo '<dd> '.$r[0]['vias'][$i]->via. '</dd>';
                                }
                                ?>
                                <dt>Descripcion de los hechos</dt>
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
                                <tfoot>
                                    <tr class="bg-gray">
                                        <th class="text-right" colspan="2">TOTAL: </th>
                                        <th class="text-center"></th>
                                    </tr>
                                </tfoot>
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
                                        <th>Edificacion y número</th>
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
                                                    <ul> <label>Númmero Interior: </label> <?=mb_strtoupper($quejoso->n_int)?> 
                                                    </ul>
                                                    <ul> <label>Númmero Exterior: </label> <?=mb_strtoupper($quejoso->n_ext)?> 
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
                                            <th>Nombre completo</th>
                                            <th>Procedencia</th>
                                            <th>Municipio</th>
                                            <th>Nivel/Rango </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($r[0]['presuntos'] as $presunto): ?>
                                            <tr>
                                                <td> <?=mb_strtoupper($presunto->nombre)?> </td>
                                                <td><?=mb_strtoupper($presunto->procedencia)?></td>  
                                                <td><?=mb_strtoupper($presunto->n_municipio)?></td>   
                                                <td><?=mb_strtoupper($presunto->cargo_id)?></td> 
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>                    	
                    </div>
                    <div class="row">
                    	<div class="col-md-6">
                    		<div class="row">
                    		    <div class="col-md-12">
                    		        <h1> <center>Datos de las unidades</center> </h1>
                    		    </div>
                    		</div>
                    		<?php foreach ($r[0]['unidades'] as  $unidad): ?>
                    			<div class="row">
                    			    <div class="col-md-12">
                    			        <dl class="dl-horizontal">
                    			            <dt>Procedencia</dt>
                    			            <dd> <?=$unidad->procedencia?> </dd>
                    			            <dt>Tipo de vehículo</dt>
                    			            <dd> <?=$unidad->t_vehiculo?> </dd>
                    			            <dt>Número económico</dt>
                    			            <dd> <?=$unidad->n_eco?>  </dd>
                    			            <dt>Placas</dt>
                    			            <dd> <?=mb_strtoupper($unidad->placas,'utf-8')?> </dd>
                    			        </dl>
                    			    </div>
                    			</div>
                    		<?php endforeach ?>
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
                    		<div class="row">
                    		    <div class="col-md-12">
                    		        <h1> <center>Respuestas (Oficios generados).</center> </h1>
                    		    </div>
                    		</div>
                    		<div class="row">
                    		    <div class="col-md-12">
                    		        <dl class="dl-horizontal">
                    		            <dt>Oficio</dt>
                    		            <dd> Perro </dd>
                    		            <dt>Oficio de respuesta</dt>
                    		            <dd> Pastor Malinoins Belga </dd>
                    		            <dt>Fecha de respuesta</dt>
                    		            <dd> Rambo </dd>
                    		            <dt># de entrada</dt>
                    		            <dd> 3 años </dd>
                    		        </dl>
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
                    		        <dl class="dl-horizontal">
                    		            <dt>Fecha</dt>
                    		            <dd> Perro </dd>
                    		            <dt>Motivo</dt>
                    		            <dd> Pastor Malinoins Belga </dd>
                    		            <dt>Registrado por </dt>
                    		            <dd> Rambo </dd>
                    		            <dt>Oficio</dt>
                    		            <dd> 3 años </dd>
                    		        </dl>
                    		    </div>
                    		</div>
                    	</div>
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
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-hover table-condensed table-bordered">
                                        <caption class="bg-gray">
                                            <center>
                                                <b>LISTADO DE EXPEDIENTES ACUMULADOS</b>
                                            </center> 
                                        </caption>
                                        <thead>
                                            <tr class="bg-gray">
                                                <th width="30%">Nombre de documento</th>
                                                <th width="60%">Descripcion del documento</th>
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
    
