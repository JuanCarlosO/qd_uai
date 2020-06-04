<?php
require_once 'model/Connection.php';
require_once 'model/QDModel.php';

$q = new QDModel;

#Variables revividas
if ( isset($_GET['estado']) ) {
	$r = $q->getExpByAbogado($_GET['person'],$_GET['estado']);
}else{
	$r = $q->getExpByAbogado($_GET['person'],false);
}
#echo "<pre>";print_r($r);echo "</pre>";
?>

<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Listado de Quejas y Denuncias</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                            	<table id="tbl_abogado" class="table table-hover table-bordered table-condesed">
                            		<thead>
                            			<tr>
                            				<th>ID</th>
                            				<th>No. Folio</th>
                            				<th>No. expediente</th>
                            				<th>Estado</th>
                            				<th>Fecha/Hora de hechos</th>
                            				<th>Infracción(es)</th>
                            				<th>Municipio</th>
                            				<th>Procedencia</th>
                                            <th>Días trabajados (desde apertura)</th>
                            			</tr>
                            		</thead>
                            		<tbody>
                            			<?php foreach ($r as $queja): ?>
                            				<tr>
                            					<td><?=$queja['id']?></td>
                            					<td><?=$queja['cve_ref']?></td>
                            					<td><?=$queja['cve_exp']?></td>
                            					<td><?=$queja['n_estado']?></td>
                            					<td><?=$queja['f_hechos']."/".$queja['h_hechos']?></td>
                            					<td>
                            						<ol>
                            						<?php foreach ($queja['conductas'] as $key => $con): ?>
                            							<li><?=$con->nombre ?></li>
                            						<?php endforeach ?>
                            						</ol>
                            					</td>
                            					<td><?=$queja['municipio']?></td>
                            					<td><?=$queja['procedencia']?></td>
                                                <td><?=$queja['dias_t']?></td>
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
    
    