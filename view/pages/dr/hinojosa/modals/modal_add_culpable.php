<style type="text/css" media="screen">
	ul.ui-autocomplete.ui-menu { z-index:2147483647; }
</style>
<form action="#" method="post" id="frm_add_culpable">
	<input type="hidden" name="option" value="95">
	<input type="hidden" id="queja_id" name="queja_id" value="">
	<div class="modal fade" id="modal_add_culpable">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar servidor público. <u id="etiqueta_modal_add_culpable"></u></h4>
				</div>
				<div class="modal-body">
					<div id="m_culpable"></div>
				    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nombre completo</label>
                                <input type="text" class="form-control" name="name_presunto" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">                                                                
                                <label>RFC</label>
                                <input type="text" class="form-control" name="rfc" value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">                                            
                                <label>CURP</label>
                                <input type="text" class="form-control" name="curp" value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">                                                                
                                <label>CUIP</label>
                                <input type="text" class="form-control" name="cuip" value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">                                                                
                                <label>TIPO DE PUESTO</label>
                                <select class="form-control" name="t_puesto">
                                    <option value="">...</option>
                                    <option value="1" >Administrativo</option>
                                    <option value="2" >Operativo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Seleccionar cargo</label>
                                <select class="form-control cargos" id="cargo" name="cargo">
                                    <option value="">...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Género</label>
                                <select class="form-control" id="ge" name="genero">
                                    <option value="">...</option>
                                    <option value="1">Hombre</option>
                                    <option value="2">Mujer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Procedencia</label>
                                <select class="form-control" name="procedencia">
                                    <option value="">...</option>
                                    <option value="1">CPRS</option>
                                    <option value="2">SECRETARÍA DE SEGURIDAD</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Media filiación</label>
                                <textarea class="form-control" id="media" name="media" style="resize: vertical; max-height: 250px;"></textarea>
                            </div>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat pull-right">Guardar responsable</button>
				</div>
			</div>
		</div>
	</div>
</form>