
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Guardar acuse de expedientes asignados</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="alerta"></div>
                	<form action="#" id="frm_add_acuse" method="post"  enctype="multipart/form-data">
                		<input type="hidden" name="option" value="92">
                		<div class="row">
                			<div class="col-md-3">
                				<div class="form-group">
                					<label>Buscar oficio que envio la Dir. de Responsabilidades</label>
                					<input type="text" name="oficio" id="oficio" value="" class="form-control">
                					<input type="hidden" id="oficio_id" name="oficio_id" value="">
                				</div>
                			</div>
                			<div class="col-md-3">
                				<div class="form-group">
                					<label>Asunto</label>
                					<input type="text" name="asunto" value="" class="form-control" required>
                				</div>
                			</div>
                			<div class="col-md-3">
                				<div class="form-group">
                					<label>Seleccionar archivo</label>
                					<input type="file" name="file" value="" class="form-control" required accept=".pdf">
                				</div>
                			</div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha del acuse</label>
                                    <input type="date" name="f_acuse" value="" class="form-control" required>
                                </div>
                            </div>
                		</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea name="observaciones" id="observaciones" class="form-control" style="resize: vertical;max-height: 250px;"></textarea>
                                </div>
                            </div>
                        </div>
                		<div class="row">
                			<div class="col-md-4"></div>
                			<div class="col-md-4">
                				<button type="submit" class="btn btn-success btn-flat btn-block">
                					<i class="fa fa-floppy-o"></i> Guardar acuse
                				</button>
                			</div>
                			<div class="col-md-4"></div>
                		</div>
                	</form>
                                        	
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Listado de acuses almacenados</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <div id="tbl_acuses"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
