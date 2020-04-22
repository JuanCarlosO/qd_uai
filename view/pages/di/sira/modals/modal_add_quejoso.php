<form action="#" id="frm_add_quejoso" method="post" >
	<input type="hidden" name="option" value="36">
	<input type="hidden" name="acta_id" value="<?=$_GET['acta']?>">
	<div class="modal fade" id="modal_add_quejoso">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar un quejoso</h4>
				</div>
				<div class="modal-body">
					<div id="modal_quejoso"></div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Nombre</label>
								<input type="text" id="nombre" name="nombre" class="form-control" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Apellido paterno</label>
								<input type="text" id="ap_pat" name="ap_pat" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Apellido materno</label>
								<input type="text" id="ap_mat" name="ap_mat" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-9">
							<label>Teléfono</label>
							<input type="text" id="phone" name="phone" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
						</div>
						<div class="col-md-3">
							<label>Genero</label>
							<select name="genero" class="form-control" required>
								<option value="">...</option>
								<option value="1">Hombre</option>
								<option value="2">Mujer</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Correo electrónico</label>
							<input type="email" id="email" name="email" class="form-control">
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Direccion</label>
								<textarea id="direccion" name="direccion" class="form-control" style="resize: vertical; max-height: 300px;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary btn-flat">
						<i class="fa fa-floppy-o"></i> Guardar datos
					</button>
				</div>
			</div>
		</div>
	</div>
</form>
