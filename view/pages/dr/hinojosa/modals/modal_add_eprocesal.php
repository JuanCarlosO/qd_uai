<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<form action="#" method="post" id="frm_add_eprocesal">
	<input type="hidden" name="option" value="94">
	<input type="hidden" id="queja_id" name="queja_id" value="">
	<div class="modal fade" id="modal_add_eprocesal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Integrar el estado procesal <u id="etiqueta_modal_add_eprocesal"></u></h4>
				</div>
				<div class="modal-body">
					<div id="m_eprocesal"></div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Estado procesal</label>
								<select name="e_procesal" id="e_procesal" class="form-control">
									<option value="">...</option>
									<option value="3">Devolver a D.I.</option> 
									<option value="1">Enviar a</option>
									<option value="4">Resuelto</option>
								</select>
							</div>
						</div>
						<div id="div_auto" class="col-md-6 hidden">
							<div class="form-group">
								<label>Autoridad destinataria</label>
								<select name="autoridad" id="autoridad" class="form-control">
									<option value="">...</option>
									<option value="1">CHyJ</option>
									<option value="2">OIC</option>
									<option value="3">Subdirección de lo Contencioso</option>
								</select>
							</div>
						</div>
						
					</div>
					<fieldset>
						<legend>Datos de la documentación</legend>
						<div class="row">
							<div class="col-md-4">
								<div id="oficio" class="form-group hidden">
									<label>Número de oficio</label>
									<input type="text" name="n_oficio" id="n_oficio" value="" class="form-control ">
									<input type="hidden" id="n_oficio_id" name="n_oficio_id" value="">
								</div>
							</div>
							<div class="col-md-4">
								<div id="f_acuse" class="form-group hidden">
									<label>Fecha del acuse</label>
									<input type="date" name="f_acuse" value="" class="form-control ">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group hidden" id="n_semana">
									<label>Número de semana</label>
									<input type="number" name="n_semana" value="" class="form-control" min="1">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group hidden" id="fojas">
									<label>Número de fojas</label>
									<input type="number"  name="fojas" value="" class="form-control" min="1">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group hidden"  id="t_doc">
									<label>Tipo de documentación</label>
									<select name="t_doc" class="form-control ">
										<option value="">...</option>
										<option value="1">Original</option>
										<option value="2">Copias simples</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<!-- <div id="div_conducta" class="form-group hidden">
									<label>Seleccione una conducta</label>
									<select id="conducta" name="conducta"  class="form-control col-md-12">
											<option value="">...</option>
										</select>
								</div> -->
								<div id="div_motivo" class="form-group hidden">
									<label>Motivo del envio</label>
									<select id="motivo" name="motivo"  class="form-control">
										<option value="">...</option>
										<option value="INCOMPETENCIA">INCOMPETENCIA</option>
										<option value="ARCHIVO">ARCHIVO</option>
										<option value="RESERVA">RESERVA</option>
										<option value="IMPROCEDENCIA">IMPROCEDENCIA</option>
									</select>
								</div>

							</div>
						</div>
					</fieldset>
					<div id="div_normatividad" class="hidden">
						<div class="row">
						    <div class="col-md-3">
						        <div class="form-group">
						            <label for="t_ley"> Normatividad aplicable<i class="fa fa-asterisk text-red"></i></label>
						            <select id="t_ley" name="t_ley" class="form-control" required>
						                <option value="">...</option>
						            </select>
						        </div>
						    </div>
						    <div class="col-md-3">
						        <div class="form-group">
						            <label>Capítulo</label>
						            <select id="capitulos" name="capitulo" class="form-control">
						                <option value="">...</option>
						            </select>
						        </div>
						    </div>
						</div>
						<div class="row">
						    
						    <div class="col-md-3">
						        <div class="form-group">
						            <label for="art">Número de artículo <i class="fa fa-asterisk text-red"></i></label>
						            <select id="art" name="art" class="form-control">
						                <option value="">...</option>
						            </select>
						        </div>
						    </div>
						    <div class="col-md-3">
						        <div class="form-group">
						            <label for="art">Secciones disponibles <i class="fa fa-asterisk text-red"></i></label>
						            <select id="secciones" name="secciones" class="form-control">
						                <option value="">...</option>
						            </select>
						        </div>
						    </div>
						    <div class="col-md-3">
						        <div class="form-group">
						            <label for="art">Fracciones disponibles <i class="fa fa-asterisk text-red"></i></label>
						            <select id="fracciones" name="fracciones" class="form-control">
						                <option value="">...</option>
						            </select>
						        </div>
						    </div>
						    
						</div>
						<div class="row">
						    <div class="col-md-12">
						        <div class="form-group">
						            <label for="conductas">Presunta conducta <i class="fa fa-asterisk text-red"></i></label>
						            <select id="conducta" name="conducta" class="form-control" data-placeholder="Selecciona una conducta" required>
						                <option value="">...</option>
						            </select>
						        </div>
						    </div>
						</div>
					</div>
					<div id="resuelto" class="hidden">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Fecha de sesion</label>
									<input type="date" name="f_sesion" value="" class="form-control">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Fecha de notificación</label>
									<input type="date" name="f_notifica" value="" class="form-control">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Fecha de resolución</label>
									<input type="date" name="f_resulucion" value="" class="form-control">
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
										<option value="5">Separación del servicio.</option>
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
									<label></label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Observaciones</label>
								<textarea name="comentario" id="comentario" class="form-control" style="resize: vertical;max-height: 200px;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat pull-right ">
						<i class="fa fa-floppy-o"></i> Guardar información
					</button>
				</div>
			</div>
		</div>
	</div>
</form>