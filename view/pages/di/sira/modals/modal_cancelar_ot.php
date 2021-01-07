
<form action="#" method="post" id="frm_cancelar_ot">
	<input type="hidden" name="option" value="114">
	<input type="hidden" id="ot_id" name="ot_id" value="">

	<div class="modal fade" id="modal_cancelar_ot">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Motivo de cancelación de la OT </h4>
				</div>
				<div class="modal-body">
					<div id="m_cancelar_ot"></div>		
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label></label>
								<textarea name="motivo" id="motivo" rows="10" style="resize: vertical;max-height: 250px;" class="form-control"></textarea>
							</div>
						</div>
					</div>	
						
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat pull-right"><i class="fa fa-floppy-o"></i> Guardar motivo de cancelación</button>
				</div>
			</div>
		</div>
	</div>
</form>