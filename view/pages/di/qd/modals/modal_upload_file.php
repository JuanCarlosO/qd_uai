<form action="#" id="frm_upload_file" method="post" enctype="multipart/form-data">
	<input type="hidden" name="option" value="15">
	<input type="hidden" name="queja_id" id="queja_id" value="">
	<div class="modal fade" id="modal_upload_file">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Adjuntar documento al expediente. (Solo pdf)</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<p>Los campos obligatorios se encuentran marcados con un asterisco (*)</p>
						</div>
					</div>
					<div id="upload_file"></div>
					<div class="row">
						<div class="col-md-6">
							<label>Nombre del archivo *</label>
							<input type="text" name="name_file" class="form-control" placeholder="Escriba un nombre para el archivo.">
						</div>
						
						<div class="col-md-6">
							<label>Seleccione un documento *</label>
							<input type="file" id="file" name="file"  class="form-control" accept=".pdf" required>
						</div>
					</div>
					<div class="row"> 
						<div class="col-md-12">	
							<div class="form-group">
								<label>Descripción del documento</label>
								<textarea class="form-control" id="comentario" name="comentario" placeholder="Formatos de listas de asistencia" style="resize: vertical; max-height: 200px;"></textarea>
							</div>	
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary btn-flat">
						<i class="fa fa-floppy-o"></i> Guardar datos
					</button>
				</div>
			</div>
		</div>
	</div>
</form>
