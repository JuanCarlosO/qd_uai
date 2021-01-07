<form action="#" method="post" id="frm_add_adsp">
	<input type="hidden" name="option" value="103">
	<div class="modal fade" id="modal_add_adsp">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar un área de adscripción </h4>
				</div>
				<div class="modal-body">
					<div id="m_adsp"></div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="cargo">Nombre del área</label>
								<input type="text" id="nombre " name="nombre " value="" required="" placeholder="Nombre nuevo del area" maxlength="255" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat pull-right">
						Guardar área
					</button>
				</div>
			</div>
		</div>
	</div>
</form>