
<div class="modal fade" id="modal_add_apersonamiento">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Regresar el expedinete a la Dirección de Investigación</h4>
			</div>
			<div class="modal-body">
				<div id="div_apersonamiento"></div>
				<form id="frm_add_apersonamiento" action="#">
					<input type="hidden" name="option" value="91">
					<input type="hidden" id="demanda_id" name="demanda_id" value="">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label> Fecha del apersonamiento </label>
								<input type="date" id="f_aperson" name="f_aperson" value="" class="form-control" required="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Descripción</label>
								<textarea name="comentario" id="comentario" class="form-control" style="resize: vertical;"></textarea>
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