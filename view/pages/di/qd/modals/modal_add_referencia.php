<form action="#" method="post" id="frm_add_referencia">
	<input type="hidden" name="option" value="3">
	<div class="modal fade" id="modal_add_referencia">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar un nuevo tipo de referencia</h4>
				</div>
				<div class="modal-body">
					<div id="am_ref"></div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="n_ref">Nombre de la referencia</label>
								<input type="text" id="n_ref" name="n_ref" value="" required="" placeholder="Nombre de la nueva referencia" maxlength="150" class="form-control">
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