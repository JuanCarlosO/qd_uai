<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<div class="modal fade" id="modal_turnar">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Resolver la demanda</h4>
			</div>
			<div class="modal-body">
				<div id="div_turnar"></div>
				<form id="frm_turnar" action="#">
					<input type="hidden" id="option" name="option" value="90">
					<input type="hidden" id="oficio_inv" name="oficio_inv" value="">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Número de oficio con el que envia</label>
								<input type="text" id="oficio_dr" name="oficio_dr" value="" class="form-control" required>
								<input type="hidden" id="oficio_dr_id" name="oficio_dr_id" value="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<button type="submit" class="btn btn-flat btn-success btn-block">
								Enviar expedientes <i class="fa fa-arrow-right"></i>
							</button>
						</div>
						<div class="col-md-1"></div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-flat pull-right" data-dismiss="modal">Cerrar ventana</button>
			</div>
		</div>
	</div>
</div>