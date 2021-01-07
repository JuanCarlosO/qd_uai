<form action="#" method="post" id="frm_add_cargo">
	<input type="hidden" name="option" value="101">
	<div class="modal fade" id="modal_add_cargo">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar un cargo nuevo</h4>
				</div>
				<div class="modal-body">
					<div id="m_cargo"></div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="cargo">Nombre nuevo del cargo</label>
								<input type="text" id="cargo" name="cargo" value="" required="" placeholder="Nombre nuevo del cargo" maxlength="150" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat pull-right">
						Guardar cargo
					</button>
				</div>
			</div>
		</div>
	</div>
</form>