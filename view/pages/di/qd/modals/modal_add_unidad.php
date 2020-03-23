<form action="#" method="post" id="frm_add_unidad">
	<input type="hidden" name="option" value="16">
	<input type="hidden" name="queja_id" value="<?=$r[0]['id']?>">
	<div class="modal fade" id="modal_add_unidad">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar una unidad implicada</h4>
				</div>
				<div class="modal-body">
					<div id="am_unidad"></div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Procedencia</label>
								<select id="procedencia" name="procedencia" class="form-control">
									<option value="">...</option>
									<option value="1">ESTATAL</option>
									<option value="2">CPRS</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Tipo de vehículo</label>
								<select id="t_vehiculo" name="t_vehiculo" class="form-control">
									<option value="">...</option>
									<!-- VALORES ENUM DE LA BD-->
									<option value="1">MOTOCICLETA</option>
									<option value="2">CAMIONETA</option>
									<option value="3">AUTOMOVIL</option>
									<option value="4">CAMIÓN</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Color</label>
								<select id="color" name="color" class="form-control">
									<option value="">...</option>
									<?php foreach ($colores as $color): ?>
									<option value="<?=$color->id?>"><?=$color->nombre?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Número economico</label>
								<input type="text" id="n_eco" name="n_eco" class="form-control" value="" placeholder="Ej: 123-S">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Placas</label>
								<input type="text" id="placas" name="placas" class="form-control" value="" placeholder="123-22">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Comentarios</label>
								<textarea name="comentarios" class="form-control" style="resize: vertical; max-height: 250px;"></textarea>
							</div>
						</div>
					</div>	
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat pull-right">Guardar referencia</button>
				</div>
			</div>
		</div>
	</div>
</form>