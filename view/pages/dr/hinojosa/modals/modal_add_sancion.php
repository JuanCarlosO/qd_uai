<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<form action="#" method="post" id="frm_add_sancion">
	<input type="hidden" name="option" value="2X">
	<input type="hidden" id="q_id" name="queja_id" value="">
	<div class="modal fade" id="modal_add_sancion">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar sanción al expediente <u id="etiqueta_modal_add_sancion"></u></h4>
				</div>
				<div class="modal-body">
					<div id="a_sancion"></div>	
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Nombre completo del presunto responsable</label>
								<input type="text" class="form-control" name="n_responsable" value="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Adscripción</label>
								<input type="text" class="form-control" name="adscripcion" value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>RFC</label>
								<input type="text" class="form-control" name="rfc" value="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>CUIP</label>
								<input type="text" class="form-control" name="cuip" value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>CURP</label>
								<input type="text" class="form-control" name="curp" value="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Fecha de determinación</label>
								<input type="date" class="form-control" name="f_determina" value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label> Fecha de sesión </label>
								<input type="date" name="f_sesion" value="" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label> Fecha de notificación </label>
								<input type="date" name="f_notificacion" value="" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label> Fecha de resolución </label>
								<input type="date" name="f_resolucion" value="" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Sanción</label>
								<select name="castigo" id="castigos" class="form-control">
									<option value="">...</option>
									<option value="1">Amonestación</option>
									<option value="2">Inexistencia</option>
									<option value="3">Supensión</option>
									<option value="4">Remoción del cargo</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Días de suspensión </label>
								<select name="dias_s" id="dias_s" class="form-control">
									<option value="">...</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>¿Se notificó?</label>
								<select name="notificado" id="notificado" class="form-control">
									<option value="">...</option>
									<option value="1">Si</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>Número de oficio de resolución</label>
								<input type="text" class="form-control" name="oficio_sa" id="oficio_sa" value="">
								<input type="hidden" class="form-control" name="oficio_sa_id" id="oficio_sa_id" value="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Observaciones</label>
								<textarea name="comentario" id="" class="form-control" style="max-height: 250px; resize: vertical;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-default btn-flat pull-left">
						<i class="fa fa-paint-brush"></i>  Limpiar formulario
					</button>
					<button type="submit" class="btn btn-success btn-flat pull-right">
						<i class="fa fa-floppy-o"></i>  Guardar sanción
					</button>
				</div>
			</div>
		</div>
	</div>
</form>