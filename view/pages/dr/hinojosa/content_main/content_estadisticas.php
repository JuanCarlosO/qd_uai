<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Generador de Estadísticas</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                	<form id="frm_estadistica" action="#">
                		<div class="row">
                			<div class="col-md-4">
                				<label>Fecha de inicio</label>
                				<input type="date" id="f_ini" name="f_ini" value="" required class="form-control">
                			</div>
                			<div class="col-md-4">
                				<label>Fecha fin</label>
                				<input type="date" id="f_fin" name="f_fin" value="" required class="form-control">
                			</div>
                			<div class="col-md-4">
                				<label>Estado procesal</label>
                				<select name="e_procesal" id="e_procesal" class="form-control" required>
                					<option value="">...</option>
                					<option value="">Enviado</option>
                					<option value="">Trámite</option>
                					<option value="">Devueltos a D.I.</option>
                				</select>
                			</div>
                			<div class="col-md-3"></div>
                		</div>
                        <br>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class=" col-md-4 ">
                                <button type="submit" class="btn btn-success btn-flat center-block">
                                    <i class="fa fa-search"></i> Buscar información
                                </button> 
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                	</form>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                  <h3 id="e_cuenta">150</h3>
                                  <p>Enviados</p>
                              </div>
                              <div class="icon">
                                  <i class="ion ion-bag"></i>
                              </div>
                          </div>
                      </div>
                        <div class="col-md-3">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                  <h3 id="t_cuenta">150</h3>
                                  <p>Trámite</p>
                              </div>
                              <div class="icon">
                                  <i class="ion ion-bag"></i>
                              </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3 id="d_cuenta">150</h3>
                                    <p>Devueltos</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Expediente</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                    	
                </div>
            </div>
        </div>
    </div>
</section>
