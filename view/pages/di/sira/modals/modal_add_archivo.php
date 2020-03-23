<form action="#" id="frm_add_archivo" method="post" >
	<div class="modal fade" id="modal_add_archivo">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar un archivo</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4">
							<label>Nombre del archivo</label>
							<input type="text" name="" class="form-control" placeholder="Escriba un nombre para el archivo.">
						</div>
						
						<div class="col-md-4">
							<label>Seleccione un documento</label>
							<input type="file" id="file" name="file"  class="form-control">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">
						<i class="fa fa-floppy-o"></i> Guardar datos
					</button>
				</div>
			</div>
		</div>
	</div>
</form>
