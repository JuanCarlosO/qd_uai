<div class="modal fade" id="modal_resolver_demanda">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Resolver la demanda</h4>
			</div>
			<div class="modal-body">
				<div id="div_res_demanda"></div>
				<form id="frm_resolver_demanda" action="#">
					<input type="hidden" id="option" name="option" value="64">
					<input type="hidden" id="oficio_inv" name="oficio_inv" value="">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Número de expediente con el que envia los expedientes</label>
								<input type="text" id="oficio" name="oficio" value="" class="form-control" required>
								<input type="hidden" id="oficio_id" name="oficio_id" value="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<button type="submit" class="btn btn-flat btn-success btn-block">
								<i class="fa fa-floppy-o"></i> Enviar expedientes
							</button>
						</div>
						<div class="col-md-4"></div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-flat pull-right" data-dismiss="modal">Cerrar ventana</button>
			</div>
		</div>
	</div>
</div>