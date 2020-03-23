<form action="#" id="frm_add_pr" method="post" >
	<input type="hidden" name="option" value="33">
	<input type="hidden" name="acta_id" value="<?=$_GET['acta']?>">
	<div class="modal fade" id="modal_add_pr">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar un presunto responsable</h4>
				</div>
				<div class="modal-body">
					<div id="alert_pr"></div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Nombre *</label>
								<input type="text" id="nombre" name="nombre" class="form-control" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Apellido paterno</label>
								<input type="text" id="ap_pat" name="ap_pat" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Apellido materno</label>
								<input type="text" id="ap_mat" name="ap_mat" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label>Genero * </label>
							<select id="genero" name="genero" class="form-control" required>
								<option value="">...</option>
								<option value="1">Hombre</option>
								<option value="2">Mujer</option>
							</select>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Cargo </label>
								<select id="cargo" name="cargo" class="form-control">
									<option value="">...</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<label>Procedencia *</label>
							<select id="procedencia" name="procedencia" class="form-control" required>
								<option value="">...</option>
								<option value="1">Secretaría de Seguridad</option>
								<option value="2">CPRS</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Media filiciación</label>
								<textarea id="media_f" name="media_f" class="form-control" style="resize: vertical; max-height: 300px;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">
						<i class="fa fa-floppy-o"></i> Guardar datos
					</button>
				</div>
			</div>
		</div>
	</div>
</form>
