<form action="#" id="frm_add_animal" method="post" >
	<div class="modal fade" id="modal_add_animal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar un animal implicado</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4">
							<label>Tipo de animal</label>
							<select id="animal" name="animal" class="form-control">
								<option value="">...</option>
							</select>
						</div>
						<div class="col-md-4">
							<label>Raza</label>
							<select id="raza" name="raza" class="form-control">
								<option value="">...</option>
							</select>
						</div>
						<div class="col-md-4">
							<label>Nombre del animal</label>
							<input type="text" id="nombre" name="nombre"  class="form-control">
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<label>Edad</label>
							<input type="number" class="form-control" id="edad" name="edad">
						</div>
						<div class="col-md-4">
							<label>Color</label>
							<select id="color" name="color" class="form-control">
								<option value="">...</option>
							</select>
						</div>
						<div class="col-md-4">
							<label>Inventario</label>
							<input type="text" id="inv" name="inv" class="form-control">
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