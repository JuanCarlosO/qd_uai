<?php
require_once 'model/Connection.php';
require_once 'model/QDModel.php';
$queja_id = $_GET['queja'];
$q = new QDModel;
$r = $q->getQDOnly($queja_id);
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
                            <h1> <center>Expediente con número: <u> <?=$r[0]['cve_exp'] ?> </u></center> </h1>
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
                        <div class="col-md-12">
                            <h1> <center>Ubicación de los hechos</center> </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>Municipio</dt>
                                <dd> <?=mb_strtoupper($r[0]['ubicacion']->n_municipio)?>  </dd>
                                <dt>Calle principal</dt>
                                <dd><?=mb_strtoupper($r[0]['ubicacion']->calle)?> </dd>
                                <dt>Entre calle</dt>
                                <dd> <?=mb_strtoupper($r[0]['ubicacion']->e_calle)?>  </dd>
                                <dt> Y calle</dt>
                                <dd> <?=mb_strtoupper($r[0]['ubicacion']->y_calle)?>  </dd>
                                
                                <dt>Edificacion</dt>
                                <dd> <?=mb_strtoupper($r[0]['ubicacion']->edificacion)?>  </dd>
                                <dd> <?=mb_strtoupper($r[0]['ubicacion']->numero)?> </dd>
                            </dl>
                        </div>
                    </div>      
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Datos del quejoso(s)</center> </h1>
                        </div>
                    </div>
                    <?php foreach ($r[0]['quejosos'] as $quejoso): ?>
                    	<div class="row">
                    	    <div class="col-md-12">
                    	        <dl class="dl-horizontal">
                    	            <dt>Nombre completo</dt>
                    	            <dd> <?=mb_strtoupper($quejoso->nombre)?> </dd>
                    	            <dt>Género</dt>
                    	            <dd> <?=mb_strtoupper($quejoso->genero)?> </dd>
                    	            <dt>Teléfono</dt>
                    	            <dd><?=mb_strtoupper($quejoso->telefono)?></dd>
                    	            <dt>Medios de Localización</dt>
                    	            <dd><a href="mailto:<?=mb_strtoupper($quejoso->email)?>"><?=mb_strtoupper($quejoso->email)?></a></dd>
                    	            <dt>Municipio</dt>
                    	            <dd> <?=mb_strtoupper($quejoso->n_municipio)?> </dd>
                    	            <dd> <label>Númmero Interior: </label> <?=mb_strtoupper($quejoso->n_int)?> 
                    	        	</dd>
                    	        	<dd> <label>Númmero Exterior: </label> <?=mb_strtoupper($quejoso->n_ext)?> 
                    	        	</dd>

                    	            
                    	        </dl>
                    	    </div>
                    	</div>
                    <?php endforeach ?>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Datos del presunto responsable(s)</center> </h1>
                        </div>
                    </div>
                    <div id="p_responsables">
                    	<?php foreach ($r[0]['presuntos'] as $presunto): ?>
                    		<div class="row">
                    			<div class="col-md-12">
                    				<dl class="dl-horizontal">
                    					<dt>Nombre completo</dt>
                    					<dd> <?=mb_strtoupper($presunto->nombre)?> </dd>
                    					
                    					<dt>Procedencia</dt>
                    					<dd><?=mb_strtoupper($presunto->procedencia)?></dd>  
                    					  
                    					<dt>Municipio</dt>
                    					<dd><?=mb_strtoupper($presunto->n_municipio)?></dd>   
                    					
                    					<dt>Nivel/Rango </dt>
                    					<dd><?=mb_strtoupper($presunto->cargo_id)?></dd>   
                    				</dl>
                    			</div>
                    		</div>
                    	<?php endforeach ?>
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
                        <div class="col-md-12">
                            <h1> <center>Expedientes acumulados</center> </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover  table-condensed" border="1px">
                                    <thead>
                                        <tr>
                                            <th>header</th><th>header</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>data</td> <td>data</td>
                                        </tr><tr>
                                            <td>data</td> <td>data</td>
                                        </tr>
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
    
