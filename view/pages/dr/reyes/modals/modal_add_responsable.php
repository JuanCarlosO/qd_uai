<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<div class="modal fade" id="modal_add_responsable">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Asignar jefe de departamento responsable <u id="etiqueta_modal_add_responsable"></u></h4>
			</div>
			<div class="modal-body">
				<div id="div_responsable"></div>
				<form id="frm_add_responsable" action="#" enctype="multipart/form-data">
					<input type="hidden" name="option" value="61">
					<input type="hidden" id="queja_id" name="queja_id" value="">
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
								<label>Escriba el nombre del Jefe de Departamento </label>
								<input type="text" id="jefe" name="jefe" value="" class="form-control">
								<input type="hidden" id="jefe_id" name="jefe_id" value="">
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

					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<button class="btn btn-success btn-flat btn-block">
								<i class="fa fa-floppy-o"></i> Guardar datos del responsable
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