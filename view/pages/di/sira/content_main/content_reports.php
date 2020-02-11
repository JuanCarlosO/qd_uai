<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Reportes</h3>
                </div>
                <div class="box-body">
                	<form id="frm_reportes" action="#" method="post">
                		<input type="hidden" id="option" name="option" value="">
                		<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>TIPO DE ASUNTO</label>
									<select name="t_asunto" class="form-control">
										<option value="">...</option>
										<option value="1">POLICIAL</option>
										<option value="2">NO POLICIAL</option>
									</select>
								</div>
							</div>
						</div>
                		<fieldset>
                			<legend>Buscar por rango de fechas</legend>
                			<div class="row">
                				<div class="col-md-3">
                					<div class="form-group">
                						<label>FECHA INICIO:</label>
                						<input type="date" id="f_ini" name="f_ini" value="" class="form-control" placeholder="">
                					</div>
                				</div>
                				<div class="col-md-3">
                					<div class="form-group">
                						<label>FECHA FINAL</label>
                						<input type="date" id="f_fin" name="f_fin" value="" class="form-control" placeholder="">
                					</div>
                				</div>
                			</div>
                		</fieldset>
						
						<fieldset>
						    <legend>DATOS DE LA REFERENCIA</legend>
						    <div class="row">
						        <div class="col-md-4">
						            <div class="form-group">
						                <label>Tipo</label>
						                <select id="t_ref" name="t_ref" class="form-control" required>
						                    <option value="">...</option>
						                </select>
						            </div>
						        </div>
						    </div>
						</fieldset>
						<fieldset>
						    <legend>DATOS DEL EXPEDIENTE</legend>
						    <div class="row">
						        <div class="col-md-2">
						            <div class="form-group">
						                <label>Prioridad</label>
						                <select id="prioridad" name="prioridad" class="form-control" required>
						                    <option value="">...</option>
						                </select>
						            </div>
						        </div>
						        <div class="col-md-2">
						            <div class="form-group">
						                <label for="estado">Estado guarda</label>
						                <select id="estado" name="estado" class="form-control" required>
						                    <option value="">...</option>
						                </select>
						            </div>
						        </div>
						        <div class="col-md-2">
						            <div class="form-group">
						                <label for="evidencia">Evidencia</label>
						                <select id="evidencia" name="evidencia" class="form-control" required>
						                    <option value="">...</option>
						                </select>
						            </div>
						        </div>
						        
						        <div class="col-md-2">
						            <div class="form-group">
						                <label for="procedencia">Procedencia</label>
						                <select id="procedencia" name="procedencia" class="form-control" required>
						                    <option value="">...</option>
						                </select>
						            </div>
						        </div>
						        
						    </div>
						    <div class="row">
						        <div class="col-md-2">
						            <div class="form-group">
						                <label>Tipo de trámite</label>
						                <select id="t_tra" name="t_tra" class="form-control" required>
						                    <option value="">...</option>
						                </select>
						            </div>
						        </div>
						    </div>
						    <div class="row">
						        <div class="col-md-2">
						            <div class="form-group">
						                <label for="genero">Género</label>
						                <select id="genero" name="genero" class="form-control" required="">
						                    <option value="">...</option>
						                    <option value="1">MASCULINO</option>
						                    <option value="2">FEMENINO</option>
						                </select>
						            </div>
						        </div>
						        <div class="col-md-3">
						            <div class="form-group">
						                <label for="t_afecta">Tipo de afectado</label>
						                <select id="t_afecta" name="t_afecta" class="form-control" required="">
						                    <option value="">...</option>
						                    <option value="1">QUEJOSO</option>
						                    <option value="2">DENUNCIANTE</option>
						                    <option value="3">VISTA</option>
						                </select>
						            </div>
						        </div>
						        <div class="col-md-3">
						            <div class="form-group">
						                <label for="categoria">Categoria</label>
						                <select id="categoria" name="categoria" class="form-control" required="">
						                    <option value="">...</option>
						                    <option value="1">CIUDADANO</option>
						                    <option value="2">SERVIDOR PÚBLICO</option>
						                    <option value="3">OTRO</option>
						                </select>
						            </div>
						        </div>
						        <div class="col-md-3">
						            <div class="form-group" style="margin-top: 25px;">
						                
						                <big>
						                    <label style="text-decoration: underline black;">BUSCAR DENUNCIAS ANÓNIMAS: </label>
						                    <big class="pull-right">
						                        <label for="d_ano">SI</label> <input type="checkbox" id="d_ano" name="d_ano" value="1" style="font-size: 110%; display: inline;">
						                    </big>
						                </big>
						            </div>
						        </div>
						    </div>
						    <div class="row">
						        <div class="col-md-4">
						            <div class="form-group">
						                <label id="t_ley">Tipo de ley</label>
						                <select id="t_ley" name="t_ley" class="form-control" required>
						                    <option value="">...</option>
						                </select>
						            </div>
						        </div>
						        <div class="col-md-8">
						            <div class="form-group">
						                <label id="t_ley">Presunta conducta</label>
						                <select id="t_ley" name="t_ley" class="form-control select2" multiple="multiple" data-placeholder="Selecciona uno o más conductas" required>
						                    <option value="">...</option>
						                </select>
						            </div>
						        </div>
						    </div>
						    <div class="row">
						        <div class="col-md-12">
						            <div class="form-group">
						                <label>Vias de recepción</label>
						                <select id="vias_r" name="vias_r" class="form-control" required>
						                    <option value="">...</option>
						                </select>
						            </div>
						        </div>
						    </div>
						</fieldset>
						<fieldset>
						    <legend>DATOS DEL LUGAR DE LOS HECHOS</legend>
						    <div class="row">
						        <div class="col-md-4">
						            <div class="form-group">
						                <label for="municipio">Municipio</label>
						                <select id="municipio" name="municipio" class="form-control" required>
						                    <option value="">...</option>
						                </select>
						            </div>
						        </div>
						    </div>
						</fieldset>
						<div class="row">
						   
						    <div class="col-md-6">
					            <div class="form-group">
					                <label for="municipio">Buscar palabras clave </label>
					                <small class="label pull-right bg-green" title="EFICIENTE E INTELIGENTE" data-toggle="popover" data-trigger="hover" data-content="El nuevo buscador de decripción de hechos ahora es más eficiente e inteligente, pues permite encontrar mayor cantidad de coincidencias con menor cantidad de palabras.">Buscardor mejorado</small>
					                <textarea name="" class="form-control" placeholder="Escriba una fase o palabras clave " style="resize: vertical;max-height: 300px;"></textarea>
					            </div>
					        </div>
						    <div class="col-md-6"></div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="box box-success box-solid collapsed-box">
									<div class="box-header with-border">
										<h3 class="box-title">Búsqueda por criterios del seguimiento</h3>
										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
											</button>
										</div>
									</div>
									<div class="box-body">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label>Buscar por unidad</label>
													<input type="text" id="" name="" value="" class="form-control" placeholder="">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Buscar por presunto responsable</label>
													<input type="text" id="" name="" value="" class="form-control" placeholder="">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label> Buscar por quejoso</label>
													<input type="text" id="" name="" value="" class="form-control" placeholder="">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label>Buscar por arma</label>
													<input type="text" id="" name="" value="" class="form-control" placeholder="">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Buscar por animales</label>
													<input type="text" id="" name="" value="" class="form-control" placeholder="">
												</div>
											</div>
										</div>
									</div>
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
                	        <div id="reporte_qd" class="table-responsive"></div>
                	    </div>
                	</div>
                </div>
            </div>
        </div>
    </div>
</section>




