<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<div class="modal fade" id="modal_add_apersonamiento">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Regresar el expedinete a la Dirección de Investigación</h4>
			</div>
			<div class="modal-body">
				<div id="div_apersonamiento"></div>
				<form id="frm_add_apersonamiento" action="#">
					<input type="hidden" name="option" value="91">
					<input type="hidden" id="demanda_id" name="demanda_id" value="">
					<input type="hidden" id="queja_id" name="queja_id" value="<?=$_GET['exp']?>">
					<div class="row">
	        			<div class="col-md-3">
	        				<div class="form-group">
	        					<label>Oficio</label>
	        					<input type="text" id="oficioa" name="oficioa" value="" class="form-control">
	        					<input type="hidden" id="oficioa_id" name="oficioa_id" value="" class="form-control">
	        				</div>
	        			</div>
	        			<div class="col-md-3">
	        				<div class="form-group">
	        					<label>Fecha del oficio</label>
	        					<input type="date" name="f_oficio" value="" class="form-control" required="">
	        				</div>
	        			</div>
	        			<div class="col-md-3">
	        				<div class="form-group">
	        					<label>Fecha del acuse</label>
	        					<input type="date" name="f_acuse" value="" class="form-control">
	        				</div>
	        			</div>
	        			<div class="col-md-3">
	        				<div class="form-group">
	        					<label>Fecha del apersonamiento</label>
	        					<input type="date" name="f_apersonamiento" value="" class="form-control">
	        				</div>
	        			</div>
	        		</div>
	        		<div class="row">
	        			<div class="col-md-12">
	        				<div class="form-group">
	        					<label>Observaciones</label>
	        					<textarea name="comentario" class="form-control" style="resize: vertical;max-height: 200px;"></textarea>
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