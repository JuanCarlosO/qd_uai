<form action="#" id="frm_ot_upload" method="post" >
	<input type="hidden" name="option" value="53">
	<input type="hidden" name="oin_id" value="">
	<div class="modal fade" id="modal_ot_upload">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Adjuntar Documento a la Orden de trabajo</h4>
				</div>
				<div class="modal-body">
					<div id="div_doc_oin"></div>
					<div class="row">
						<div class="col-md-6">
							<label>Nombre del archivo</label>
							<input type="text" name="" class="form-control" placeholder="Escriba un nombre para el archivo.">
						</div>
						
						<div class="col-md-6">
							<label>Seleccione un documento</label>
							<input type="file" id="file" name="file"  class="form-control" accept=".pdf">
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
