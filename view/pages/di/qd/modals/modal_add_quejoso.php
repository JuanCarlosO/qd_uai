<form action="#" method="post" id="frm_add_quejoso">
	<input type="hidden" name="option" value="18">
	<input type="hidden" name="queja_id" value="<?=$_GET['queja']?>">
	<div class="modal fade" id="modal_add_quejoso">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar un quejoso nuevo</h4>
				</div>
				<div class="modal-body">
					<div id="am_quejoso"></div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nombre">Nombre </label>
								<input type="text" id="nombre" name="nombre" value="" required=""  maxlength="150" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ap_pat">Apellido paterno</label>
								<input type="text" id="ap_pat" name="ap_pat" value="" required=""  maxlength="150" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ap_mat">Apellido paterno</label>
								<input type="text" id="ap_mat" name="ap_mat" value="" required="" maxlength="150" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Teléfono</label>
								<input type="text" name="phone" value="" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Correo electrónico</label>
								<input type="email" name="mail" value="" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Género</label>
								<select id="genero" name="genero" class="form-control">
									<option value="">...</option>
									<option value="1">HOMBRE</option>
									<option value="2">MUJER</option>
									
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Municipio</label>
								<select id="municipio" name="municipio" class="form-control">
									<option value="">...</option>
									<?php foreach ($municipios as $key => $mun): ?>
									<option value="<?=$mun->id?>"><?=$mun->nombre?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Código postal</label>
								<input type="text" name="cp" value="" class="form-control">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Núm. Int.</label>
								<input type="text" name="n_int" value="" class="form-control">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Núm. Ext.</label>
								<input type="text" name="n_ext" value="" class="form-control">
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