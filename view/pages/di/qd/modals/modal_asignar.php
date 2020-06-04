<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<form action="#" method="post" id="frm_asignar">
	<input type="hidden" name="option" value="100">
	<input type="hidden" id="turno_id" name="turno_id" value="">
	<div class="modal fade" id="modal_asignar">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Asignar el expediente a un abogado analista</h4>
				</div>
				<div class="modal-body">
					<div id="m_asignar"></div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="sp">Nombre del Servidor Público</label>
								<input type="text" id="sp" name="sp" value="" maxlength="150" class="form-control">
								<input type="hidden" id="sp_id" name="sp_id" value="">
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