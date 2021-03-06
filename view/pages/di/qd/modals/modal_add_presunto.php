<form action="#" method="post" id="frm_add_presunto">
	<input type="hidden" name="option" value="17">
	<input type="hidden" name="queja_id" value="<?=$r[0]['id']?>">
	<div class="modal fade" id="modal_add_presunto">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar un nuevo presunto responsable</h4>
				</div>
				<div class="modal-body">
					<div id="am_presunto"></div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="n_ref">Nombre </label>
								<input type="text" id="nombre" name="nombre" value="" required="" placeholder="Nombre(s)" maxlength="50" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ap_pat">Apellido paterno</label>
								<input type="text" id="ap_pat" name="ap_pat" value="" required="" placeholder="Nombre de la nueva referencia" maxlength="50" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ap_mat">Apellido materno</label>
								<input type="text" id="ap_mat" name="ap_mat" value="" required="" placeholder="Apellido materno" maxlength="50" class="form-control">
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="genero">Género</label>
								<select id="genero" name="genero" class="form-control">
									<option value="">...</option>
									<option value="1">Hombre</option>
									<option value="2">Mujer</option>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label for="cargo">Seleccione el cargo</label>
								<div class="input-group">
									<select id="cargo" name="cargo" class="form-control cargos">
										<option value="">...</option>
									</select>
									<span class="input-group-btn">
										<button onclick="open_modal('modal_add_cargo','');" type="button" class="btn btn-info btn-flat" >
											<i class="fa fa-plus"></i>
										</button>
									</span>
								</div>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label>Municipio</label>
								<select id="municipios" name="municipios" class="form-control">
									<option value="">...</option>
								</select>
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Procedencia</label>
								<select id="procedencia" name="procedencia" class="form-control">
									<option value="">...</option>
								</select>
							</div>
						</div>
						<div id="cprs" class="hidden">
							<div class="col-md-3">
								<div class="form-group">
									<label>Listado de penales</label>
									<select class="form-control" id="penales" name="penal">
										<option value="">...</option>
									</select>
								</div>
							</div>
						</div>
											
					</div>
					<div id="estatal" class="hidden">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Coordinación</label>
									<input type="text" name="adscripcion" class="form-control">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Subdirección</label>
									<select id="subdir" name="subdir" class="form-control">
										<option value="">...</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Region</label>
									<select id="region" name="region" class="form-control">
										<option value="">...</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Agrupamiento</label>
									<select id="agrupamiento" name="agrupamiento" class="form-control">
										<option value="">...</option>
									</select>
								</div>
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label for="a_presunto">
								Adscripción del presunto (datos adicionales)
								</label>
								
								<div class="input-group">
									<select id="a_presunto" name="a_presunto" class="form-control">
										<option value="">...</option>
									</select>
									<span class="input-group-btn">
										<button onclick="open_modal('modal_add_adsp','');" type="button" class="btn btn-info btn-flat" >
											<i class="fa fa-plus"></i>
										</button>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Media filiación</label>
								<textarea name="comentarios" class="form-control" style="resize: vertical; max-height: 250px;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat pull-right">Guardar referencia</button>
				</div>
			</div>
		</div>
	</div>
</form>