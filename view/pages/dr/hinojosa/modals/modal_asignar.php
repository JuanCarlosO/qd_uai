<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<form action="#" method="post" id="frm_asignar" enctype="multipart/form-data">
	<input type="hidden" name="option" value="100">
	<input type="hidden" id="queja_id" name="queja_id" value="">
	<div class="modal fade" id="modal_asignar">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Asignar abogado al expediente <u id="etiqueta_modal_asignar"></u> </h4>
				</div>
				<div class="modal-body">
					<div id="m_asignar"></div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Jefe de departamento </label>
								<input type="text" id="jefe_sapa" name="jefe_sapa" value="" class="form-control">
								<input type="hidden" id="jefe_sapa_id" name="jefe_sapa_id" value="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Abogado analista </label>
								<input type="text" id="analista" name="analista" value="" class="form-control">
								<input type="hidden" id="analista_id" name="analista_id" value="">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat pull-right">
						Asignar expediente
					</button>
				</div>
			</div>
		</div>
	</div>
</form>