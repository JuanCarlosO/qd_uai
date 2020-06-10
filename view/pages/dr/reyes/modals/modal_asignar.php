<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<form action="#" method="post" id="frm_asignar" enctype="multipart/form-data">
	<input type="hidden" name="option" value="100">
	<input type="hidden" id="queja_respo" name="queja_respo" value="">
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
						<div class="col-md-4">
							<div class="form-group">
								<label>Número de oficio </label>
								<input type="text" id="oficio_a" name="oficio_a" value="" class="form-control">
								<input type="hidden" id="oficio_a_id" name="oficio_a_id" value="">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label> Fecha del oficio </label>
								<input type="date" id="f_oficio" name="f_oficio" value="" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label> Fecha del acuse </label>
								<input type="date" id="f_acuse" name="f_acuse" value="" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Asunto del acuse</label>
								<input type="text" id="asunto" name="asunto" value="" class="form-control">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Seleccionar el archivo</label>
								<input type="file" id="file" name="file" value="" class="form-control" >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Escriba el nombre del abogado analista </label>
								<input type="text" id="analista" name="analista" value="" class="form-control">
								<input type="hidden" id="analista_id" name="analista_id" value="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Observaciones</label>
								<textarea name="obs" id="obs" class="form-control" style="max-height: 200px; resize: vertical;"></textarea>
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