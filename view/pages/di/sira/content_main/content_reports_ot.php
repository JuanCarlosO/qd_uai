<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Reporte de Órdenes de Trabajo</h3>
                </div>
                <div class="box-body">
                	<form id="frm_reportes_ot" action="#" method="post">
                		<input type="hidden" id="option" name="option" value="162">
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
							<div class="col-md-3">
								<div class="form-group">
									<label>Tipo de orden:</label>
									<select name="t_orden" class="form-control">
										<option value="">...</option>
										<option value="1">INSPECCIÓN</option>
										<option value="3">SUPERVISIÓN</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Estatus:</label>
									<select name="estatus" class="form-control">
										<option value="">...</option>
										<option value="1">Cumplida</option>
										<option value="2">Parcial sin resultado</option>
										<option value="3">Parcial con resultado</option>
										<option value="4">Cumplida sin resultado</option>
										<option value="5">Cancelada</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Clave</label>
									<input type="text" name="clave" class="form-control">
								</div>
							</div>

							
							<div class="col-md-4">
								<div class="form-group">
									<label>Municipio</label>
									<select id="municipio" name="municipio" class="form-control">
										<option value="">...</option>
									</select>
								</div>
							</div>

						</div>


						<div class="row">

							
                            <div class="col-md-4">
                            	<div class="form-group">
                                    <label>Procedencia</label>
                                    <select id="question_p" name="question_p" class="form-control">
                                        <option value="">...</option>
                                        <option value="1">CPRS</option>
                                        <option value="2">Secretaría de Seguridad</option>
                                    </select>
                                </div>
                            </div>

                            <div id="procedencia_ss" class="hidden">
                                <div class="col-md-4">
                                	<div class="form-group">
	                                    <label>Área</label>
	                                    <select id="question_a" name="question_a" class="form-control">
	                                        <option value="">...</option>
	                                        <option value="1">Operativos Secretaría de Seguridad</option>
	                                        <option value="2">Dirección de Policía de Tránsito</option>
	                                        <option value="4">Personal Administrativo</option>
	                                    </select>
	                                </div>
                                </div>
                            </div>

                            <div id="procedencia_cprs" class="hidden">
                                <div class="col-md-4">
                                	<div class="form-group">
	                                    <label>Área</label>
	                                    <select id="question_a2" name="question_a2" class="form-control">
	                                        <option value="">...</option>
                                            <option value="3">Dirección General de Prevención y Reinserción Social</option>
	                                    </select>
	                                </div>
                                </div>
                            </div>                           

							<div id="area_operativos" class="hidden">
								<div class="row">
									<label for="agrupamiento" class="col-md-4 control-label">Agrupamiento </label>
									<div class="col-sm-3">
										<select class="form-control" name="agrupamiento" id="agrupamiento">
											<option value="" selected>...</option>
										</select>
									</div>
								</div>                              
							</div>

							<div id="area_transito" class="hidden">
								<div class="row">
									<label for="agrupamiento_t" class="col-sm-4 control-label">Agrupamiento </label>
									<div class="col-sm-3">
										<select class="form-control" name="agrupamiento_t" id="agrupamiento_t">
											<option value="" selected>...</option>
										</select>
									</div>
								</div>                          
							</div>

							<div id="area_cprs" class="hidden">
								<div class="row">
									<label for="agrupamiento_cprs" class="col-sm-4 control-label">Agrupamiento </label>
									<div class="col-sm-3">
										<select class="form-control" name="agrupamiento_cprs" id="agrupamiento_cprs">
											<option value="" selected>...</option>
										</select>
									</div>
								</div>                               
							</div>

							<div id="area_admin" class="hidden">
								<div class="row">
									<label for="niv5" class="col-md-4 control-label">Departamento </label>
									<div class="col-sm-3">
										<select class="form-control" name="niv5" id="niv5">
											<option value="" selected>...</option>
										</select>
									</div>
								</div>                              
							</div>
						</div>	
						
						
						<div class="row">						    
						    <div class="col-md-4">
						        <button type="submit" class="btn btn-flat btn-success btn-block">
						        	<i class="fa fa-search"></i>
						            Buscar información
						        </button>
						    </div>
						    <div class="col-md-4">
						    	<button class="btn btn-danger" role="link" onclick="window.location='index.php?menu=reports_ot'">Limpiar </button>
						    </div>
						</div>

						<br>

               		</form>

                	<div class="row">
                	    <div class="col-md-12">
                	        <div id="" class="table-responsive">
                	        	<table id="tbl_reporte_ot" class="table table-hover table-bordered ">
                	        		<thead>
                	        			<tr>
                	        				<th>ID</th>
                	        				<th>FECHA</th>
                	        				<th>TIPO DE ORDEN</th>
                	        				<th>CLAVE</th>
                	        				<th>ESTATUS</th>
                	        				<th>MUNICIPIO</th>
                	        				<th>PROCEDENCIA</th>
                	        				<th>ÁREA</th>
                	        				<th>DESCRIPCIÓN</th>
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