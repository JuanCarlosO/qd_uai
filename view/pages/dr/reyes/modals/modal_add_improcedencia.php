<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<div class="modal fade" id="modal_add_improcedencia">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Generar el acuerdo de improcedencia</h4>
			</div>
			<div class="modal-body">
				<div id="div_regresar"></div>
				<form action="#" id="frm_acuerdo_improcedencia">
				    <input type="hidden" name="option" value="66">
				    <input type="hidden" name="queja_id" value="">
				    <div id="div_acuerdo"></div>
				    <div class="row">
				        <div class="col-md-3">
				            <div class="form-group">
				                <label>Fecha del acuerdo</label>
				                <input type="date" id="f_acuerdo" name="f_acuerdo" value="" class="form-control">
				            </div>
				        </div>
				        <div class="col-md-3">
				            <div class="form-group">
				                <label>Fecha del turno</label>
				                <input type="date" id="f_turno" name="f_turno" value="" class="form-control">
				            </div>
				        </div>

				        <div class="col-md-3">
				            <div class="form-group">
				                <label>Acuerdo</label>
				                <input type="file" name="file" value="" class="form-control" required>
				            </div>
				        </div>
				    </div>
				    <div class="row">
				        <div class="col-md-12">
				            <div class="form-group">
				                <label>Comentario</label>
				                <textarea name="comentario" class="form-control" style="resize: vertical; max-height: 200px;"></textarea>
				            </div>
				        </div>
				    </div>
				    <div class="row">
				        <div class="col-md-4"></div>
				        <div class="col-md-4">
				            <button type="submit" class="btn btn-success btn-flat btn-block">
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