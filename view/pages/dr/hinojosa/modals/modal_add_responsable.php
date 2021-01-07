<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<form action="#" method="post" id="frm_add_responsable">
	<input type="hidden" name="option" value="93">
	<input type="hidden" id="q_id" name="queja_id" value="">
	<div class="modal fade" id="modal_add_responsable">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Asignar a personal a su cargo. <u id="etiqueta_modal_add_responsable"></u></h4>
				</div>
				<div class="modal-body">
					<div id="m_responsable"></div>	
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Nombre del jefe de departamento </label>
								<input type="text" id="jefe" name="jefe" value="" class="form-control" required>
								<input type="hidden" id="jefe_id" name="jefe_id" value="">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat pull-right">
						Asignar expediente <i class="fa fa-floppy-o"></i> 
					</button>
				</div>
			</div>
		</div>
	</div>
</form>