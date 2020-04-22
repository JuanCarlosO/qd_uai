<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<div class="modal fade" id="modal_regresar_exp">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Regresar el expedinete a la Dirección de Investigación</h4>
			</div>
			<div class="modal-body">
				<div id="div_regresar"></div>
				<form id="frm_regresar_exp" action="#">
					<input type="hidden" name="option" value="67">
					<input type="hidden" id="queja_id" name="queja_id" value="">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label> Fecha de devolución </label>
								<input type="date" id="f_devolucion" name="f_devolucion" value="" class="form-control" required="">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label> Fecha del oficio </label>
								<input type="date" id="f_oficio" name="f_oficio" value="" class="form-control" required="">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label> Oficio </label>
								<input type="text" id="oficio" name="oficio" value="" class="form-control" required="">
								<input type="hidden" id="oficio_id" name="oficio_id" value="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Motivo</label>
								<input type="text" id="motivo" name="motivo" value="" class="form-control" required="" maxlength="255">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<button class="btn btn-success btn-flat btn-block">
								<i class="fa fa-floppy-o"></i> Guardar datos
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