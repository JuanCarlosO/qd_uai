<form action="#" id="frm_add_vehiculo" method="post" >
	<div class="modal fade" id="modal_add_vehiculo">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar un vehículo implicado</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Marca</label>
								<select id="marca" name="marca" class="form-control" required>
									<option value="">...</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Submarca</label>
								<select id="submarca" name="submarca" class="form-control" required>
									<option value="">...</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Tipo de vehículo</label>
								<select id="t_vehiculo" name="t_vehiculo" class="form-control" required>
									<option value="">...</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label>Modelo</label>
							<select id="modelo" name="modelo" class="form-control">
								<option value="">...</option>
								<?php
								for ($i=1; $i <= 41; $i++) { 
									$anio = 1979+$i;
									echo "<option value='".$anio."'>".$anio."</option>";
								}
								?>
							</select>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Color</label>
								<select id="color" name="color" class="form-control">
									<option value="">...</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<label>Placa</label>
							<input type="text" class="form-control" id="placa" name="placa">
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<label>NIV</label>
							<input type="text" class="form-control" id="niv" name="niv">
						</div>
						<div class="col-md-4">
							<label>Inventario</label>
							<input type="text" class="form-control" id="inv" name="inv">
						</div>
						<div class="col-md-4">
							<label>Corporación</label>
							<select id="corporacion" name="corporacion" class="form-control">
								<option value="">...</option>
							</select>
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
