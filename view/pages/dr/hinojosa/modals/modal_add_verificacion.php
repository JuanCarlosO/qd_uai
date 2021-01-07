<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<form action="#" method="post" id="frm_add_verificacion">
	<input type="hidden" name="option" value="3X">
	<input type="hidden" id="v_queja_id" name="queja_id" value="">
	<div class="modal fade" id="modal_add_verificacion">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar verificación de cumplimiento. <u id="etiqueta_modal_add_verificacion"></u></h4>
				</div>
				<div class="modal-body">
					<div id="a_verificacion"></div>	
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label> Seleccione la sanción a la que aplica </label>
								<select name="sancion" id="sanciones" class="form-control" required="">
									<option value="">...</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Fecha de notificación al servidor público</label>
								<input type="date" name="f_notifica_sp" value="" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Fecha de notificación a R.H. </label>
								<input type="date" name="f_notifica_rh" value="" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Fecha de captura RNPSP</label>
								<input type="date" name="capt_rnpsp" value="" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Fecha de ejecución </label>
								<input type="date" name="f_ejec" value="" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Observaciones </label>
								<textarea name="comentario" id="comentario" class="form-control" style="max-height: 250px; resize: vertical;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat pull-right">
						<i class="fa fa-floppy-o"></i> Guardar verificación
					</button>
				</div>
			</div>
		</div>
	</div>
</form>