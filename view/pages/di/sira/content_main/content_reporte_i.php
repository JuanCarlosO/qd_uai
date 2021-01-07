<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Reporte de recomendaciones</h3>
                </div>
                <div class="box-body">
                	<form id="frm_reporte_i" action="#" method="post">
                		<input type="hidden" id="option" name="option" value="150">
                		<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Fecha de: </label>
									<input type="date" name="f_ini" value="" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Fecha hasta:</label>
									<input type="date" name="f_fin" value="" class="form-control">
								</div>
							</div>
						</div>						
						<div class="row">
						    <div class="col-md-4"></div>
						    <div class="col-md-4">
						        <button type="submit" class="btn btn-flat btn-success btn-block">
						        	<i class="fa fa-search"></i>
						            Buscar información
						        </button>
						    </div>
						    <div class="col-md-4"></div>
						</div>
                	</form>
                	<div class="row">
                	    <div class="col-md-12">
                	        <div id="" class="table-responsive">
                	        	<table id="tbl_reporte_irr" class="table table-hover table-bordered ">
                	        		<thead>
                	        			<tr>
                	        				<th>ID</th>
                                            <th>TIPO DE ORDEN</th>
                	        				<th>ORDEN DE TRABAJO</th>
                	        				<th>OFICIO OT</th>
                	        				<th>EXPEDIENTE</th>
                                            <th>OFICIO DE RECOMENDACIONES</th>
                                            <th>OBSERVACIONES DEL ENVÍO</th>
                	        				<th>RECOMENDACIONES</th>
                                            <th>FECHA DE NOTIFICACIÓN</th>
                	        				<th>FECHA DE VENCIMIENTO</th>
                	        				<th>ESTATUS GENERAL</th>
                                            <th>FECHA DE RESPUESTA</th>
                                            <th>OBSERVACIONES DE LA RESPUESTA</th>               	        				
                                            <!--<th>FECHA DE RECORDATORIO</th>
                                            <th>FECHA DE VENCIMIENTO DEL RECORDATORIO</th>-->                                         
                                            <th>ACTA ADMINISTRATIVA</th>                
                	        			</tr>
                	        		</thead>
                	        		<tbody></tbody>
                	        	</table>
                	        </div>
                	    </div>
                	</div>
                </div>
            </div>
        </div>
    </div>
</section>