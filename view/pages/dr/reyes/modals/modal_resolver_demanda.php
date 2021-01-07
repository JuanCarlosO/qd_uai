<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<div class="modal fade" id="modal_resolver_demanda">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Resolver la demanda</h4>
			</div>
			<div class="modal-body">
				<div id="div_res_demanda"></div>
				<form id="frm_resolver_demanda" action="#">
					<input type="hidden" id="option" name="option" value="64">
					<input type="hidden" id="dem_id" name="dem_id" value="">
					<input type="hidden" id="resolucion" name="resolucion" value="">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label> Fecha de resolución </label>
								<input type="date" id="f_res" name="f_res" value="" class="form-control" required="">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label> Estado del resultado </label>
								<select name="edo" id="edo" class="form-control">
									<option value="">...</option>
									<option value="1">CON RESOLUCIÓN VALIDEZ</option>
									<option value="2">CON RESOLUCIÓN INVALIDEZ</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label> No. de Oficio </label>
								<input type="text" id="oficio" name="oficio" value="" class="form-control">
								<input type="hidden" id="oficio_id" name="oficio_id" value="" class="form-control">
							</div>
						</div>
						
						
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label> Fecha del oficio </label>
								<input type="date" id="f_oficio" name="f_oficio" value="" class="form-control" required="">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label> Fecha del acuse </label>
								<input type="date" id="f_acuse" name="f_acuse" value="" class="form-control" required="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Descripción de la resolución</label>
								<textarea id="comentario" name="comentario" style="resize: vertical; max-height: 200px;" class="form-control" required></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<button class="btn btn-success btn-flat btn-block">
								<i class="fa fa-floppy-o"></i> Guardar datos
							</button>
						</div>
						<div class="col-md-4"></div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-flat pull-right" data-dismiss="modal">Cerrar ventana</button>
			</div>
		</div>
	</div>
</div>