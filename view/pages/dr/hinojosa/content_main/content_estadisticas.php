<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Generador de Estadísticas</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="estadistica"></div>
                	<form id="frm_estadistica" action="#" method="post">
                        <input type="hidden" name="option" value="96">
                		<div class="row">
                			<div class="col-md-3">
                				<label>Fecha de inicio</label>
                				<input type="date" id="f_ini" name="f_ini" value="" required class="form-control">
                			</div>
                			<div class="col-md-3">
                				<label>Fecha fin</label>
                				<input type="date" id="f_fin" name="f_fin" value="" required class="form-control">
                			</div>
                			<div class="col-md-3">
                				<label>Estado procesal</label>
                				<select name="e_procesal" id="e_procesal" class="form-control">
                					<option value="">...</option>
                					<option value="1">Enviado</option>
                					<option value="2">Trámite</option>
                					<option value="3">Devueltos a D.I.</option>
                                    <option value="4">En firma</option>
                                    <option value="5">Con proyecto elaborado</option>
                				</select>
                			</div>
                			<div class="col-md-3">
                                <div class="form-group">
                                    <label>Estado del expediente</label>
                                    <select name="edo" id="edo" class="form-control">
                                        <option value="">...</option>
                                        <option value="10">RESERVA</option>
                                        <option value="11">IMPROCEDENCIA</option>
                                    </select>
                                </div>
                            </div>
                		</div>
                        <br>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class=" col-md-4 ">
                                <button type="submit" class="btn btn-success btn-flat btn-block center-block">
                                    <i class="fa fa-search"></i> Buscar información
                                </button> 
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                	</form>                    	
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="div_contadores">
        

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header"></div>
                <div class="box-body">
                    <table id="tbl_estadistica" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Expediente</th>
                                <th>Procedencia</th>
                                <th>Oficio</th>
                                <th>Origen de la queja</th>
                                <th>Estado procesal</th>
                                <th>Autoridad destino</th>
                                <th>Fecha de asignación S.A.P.A.</th>
                                <th>Fecha envio S.C.</th><!-- Pendiente por agregar-->
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>