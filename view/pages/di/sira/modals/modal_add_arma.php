<form action="#" id="frm_add_arma" method="post" >
	<div class="modal fade" id="modal_add_arma">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar un arma implicada</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4">
							<label>Tipo</label>
							<select id="t_arma" name="t_arma" class="form-control">
								<option value="">...</option>
								<option value="1">FUEGO</option>
								<option value="2">BLANCA</option>
								<option value="3">OTRO(A)</option>
							</select>
						</div>
					</div>
					<div id="a_fuego">
						<div class="row">
							<div class="col-md-6">
								<label>Tipo</label>
								<select id="animal" name="animal" class="form-control">
									<option value="">...</option>
									<option value="1">Larga</option>
									<option value="2">Corta</option>
								</select>
							</div>
							<div class="col-md-6">
								<label>Tipo</label>
								<select id="animal" name="animal" class="form-control">
									<option value="">...</option>
									<option value="1">Escuadra</option>
									<option value="2">Revolver</option>
									<option value="3">Rifle</option>
									<option value="4">Escopeta</option>
									<option value="5">Metralleta</option>
									<option value="6">Automatica</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<label>Marca</label>
								<input type="text" id="marca" name="marca" class="form-control">
							</div>
							<div class="col-md-4">
								<label>Calibre</label>
								<input type="text" id="cal" name="cal" class="form-control">
							</div>
							<div class="col-md-4">
								<label>Color</label>
								<select id="color" name="color" class="form-control" c>
									<option value="">...</option>
								</select>
							</div>
						</div>
					</div>
					<div class="a_blanca">
						<div class="row">
							<div class="col-md-6">
								<label>Tipo de arma</label>
								<select class="form-control" id="t_cuchillo" name="t_cuchillo">
									<option value="">...</option>
								</select>
							</div>
							<div class="col-md-6">
								<label>Color</label>
								<select class="form-control" id="color" name="color">
									<option value="">...</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label> Matrícula </label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label> Inventario </label>
							</div>
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
