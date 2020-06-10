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
									<option value="5">Con proyecto elaborado</option>
									<option value="4">En firma</option>
									<option value="1">Enviado a</option>
									<!--<option value="2">Trámite(quitar)</option> Esta implicito cuando lo turna a un abogado  -->
								</select>
							</div>
						</div>
						<div class="col-md-6">
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
								<div id="div_conducta" class="form-group hidden">
									<label>Seleccione una conducta</label>
									<div  class="input-group">
										<select id="conducta" name="conducta"  class="form-control">
											<option value="">...</option>
										</select>
										<span class="input-group-btn">
											<button type="button" class="btn btn-success btn-flat" onclick="add_conducta();"><i class="fa fa-plus"></i></button>
										</span>
									</div>
								</div>
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
						Guardar información <i class="fa fa-floppy-o"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</form>