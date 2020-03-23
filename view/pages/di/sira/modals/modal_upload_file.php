<form action="#" id="frm_upload_file" method="post" >
	<input type="hidden" name="option" value="31">
	<input type="hidden" name="acta_id" value="">
	<div class="modal fade" id="modal_upload_file">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Adjuntar Documento al Acta</h4>
				</div>
				<div class="modal-body">
					<div id="upload_file"></div>
					<div class="row">
						<div class="col-md-6">
							<label>Nombre del archivo</label>
							<input type="text" name="nombre" class="form-control" placeholder="Escriba un nombre para el archivo.">
						</div>
						
						<div class="col-md-6">
							<label>Seleccione un documento</label>
							<input type="file" id="file" name="file" class="form-control" accept=".pdf" required>
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
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-floppy-o"></i> Guardar datos
					</button>
				</div>
			</div>
		</div>
	</div>
</form>
