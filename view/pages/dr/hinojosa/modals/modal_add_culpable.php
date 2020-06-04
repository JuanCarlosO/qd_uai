<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<form action="#" method="post" id="frm_add_culpable">
	<input type="hidden" name="option" value="95">
	<input type="hidden" id="queja_id" name="queja_id" value="">
	<div class="modal fade" id="modal_add_culpable">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar servidor público. <u id="etiqueta_modal_add_culpable"></u></h4>
				</div>
				<div class="modal-body">
					<div id="m_culpable"></div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Nombre</label>
								<input type="text" name="nombre" value="" class="form-control" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Apellido paterno</label>
								<input type="text" name="ap_pat" value="" class="form-control" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Apellido materno</label>
								<input type="text" name="ap_mat" value="" class="form-control" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Género</label>
								<select name="genero" id="genero" required class="form-control">
									<option value="">...</option>
									<option value="1">Hombre</option>
									<option value="2">Mujer</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Cargo que ocupa</label>
								<select name="cargo" id="cargo" class="form-control"></select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Adscripción</label>
								<select name="adscripcion" id="adscripcion" class="form-control">
									<option value="">...</option>
									<option value="1">Secretaría de Seguridad</option>
									<option value="2">CPRS</option>
								</select>
							</div>
						</div>
					</div>					
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat pull-right">Guardar turno</button>
				</div>
			</div>
		</div>
	</div>
</form>