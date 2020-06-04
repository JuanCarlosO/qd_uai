<?php
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
if (isset($_GET['exp'])) {
	$queja_id = $_GET['exp'];
	$q = new DRModel;
    $r = $q->getDemandas($queja_id);
    $clave = $q->getClave($queja_id);
}

?>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Demandas <u><?=$clave?></u></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
					<div id="div_demanda"></div>
                    <div class="row">
                    	<div class="col-md-12">
                    		<div class="table-responsive">
                    			<table class="table table-hover table-bordered">
                    				<thead>
                    					<tr class="bg-gray">
                    						<th class="text-center" width="10%">Demanda</th>
                    						<th class="text-center" width="10%">Oficio</th>
                    						<th class="text-center" width="10%">Dependencia</th>
                    						<th class="text-center" width="35%">Descripción</th>
                    						<th class="text-center" width="35%"> Resolver </th>
                    					</tr>
                    				</thead>
                    				<tbody>
                    					<?php foreach ($r as $key => $dem): ?>
                    						<tr>
                    							<td><?=$dem->t_demanda?></td>
                    							<td><?=$dem->oficio?></td>
                    							<td><?=$dem->dependencia?></td>
                    							<td class="text-justify"><?=$dem->comentario?></td>
                    							<td class="text-center">
                    								<?php if ( $dem->resolucion_id == NULL): ?>
                    									<button type="button" class="btn btn-success btn-flat" onclick="resolverDemanda(<?=$dem->id?>);">
                    										Resolución
                    									</button>
                                                        
                    								<?php else: ?>
                                                        <button type="button" class="btn btn-success btn-flat" onclick="modal_apersonamiento(<?=$dem->id?>);">
                                                            Generar apersonamiento
                                                        </button>

                                                        <button type="button" class="btn btn-success btn-flat btn-block" onclick="editResolucion(<?=$dem->resolucion_id?>);">
                                                            Editar la resolución
                                                        </button>
                    									<ol>
                    										<li><label>Fecha de resolución: </label> <?=$dem->f_resolucion?></li>
                    										<li><label>Resolución: </label> <?=$dem->solucion?></li>
                                                            <li><label>Estado: </label> <?=$dem->estado?></li>
                    									</ol>
                    								<?php endif ?>
                    								
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
</section>
