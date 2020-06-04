<div class="modal fade" id="modal_send_sapa">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Enviar el conjunto de expedientes a S.A.P.A.</h4>
			</div>
			<div class="modal-body">
				<div id="div_acuse"></div>
				<form id="frm_send_sapa" action="#">
					<input type="hidden" id="option" name="option" value="90">
					<input type="hidden" id="oficio" name="oficio" value="">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Fecha del oficio</label>
								<input type="date" name="f_oficio" value="" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Fecha de acuse</label>
								<input type="date" name="f_acuse" value="" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Archivo</label>
								<input type="file" name="file" class="form-control" accept=".pdf">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Observaciones</label>
								<textarea name="obs" id="obs" class="form-control" rows="3" style="resize: vertical;max-height: 200px;"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<button class="btn btn-success btn-flat btn-block">
								<i class="fa fa-floppy-o"></i> Guardar acuse
							</button>
						</div>
						<div class="col-md-4"></div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-flat pull-right" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>