<div class="modal fade" id="modal_add_seguimiento">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Formulario de seguimiento</h4>
			</div>
			<div class="modal-body">
				<div id="div_seguimiento"></div>
				<form id="frm_add_seguimiento" action="#">
					<input type="hidden" name="option" value="58">
					<input type="hidden" name="qd_res" value="">
					<input type="hidden" id="queja_id" name="queja_id" value="">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label> Comentario final del abogado </label>
								<textarea name="comentario" id="comentario" class="form-control" style="resize: vertical; max-height: 200px;"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<button class="btn btn-success btn-flat btn-block">
								<i class="fa fa-floppy-o"></i> Terminar proceso
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