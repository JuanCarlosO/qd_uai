<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Reportes</h3>
                </div>
                <div class="box-body">
                	<form id="frm_reportes" action="#" method="post">
                		<input type="hidden" id="option" name="option" value="">
                		<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Fecha de: </label>
									<input type="date" name="f_ini" value="" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Fecha hasta:</label>
									<input type="date" name="f_fin" value="" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Tipo de actuación:</label>
									<select name="" class="form-control">
										<option value="">...</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Procedecia:</label>
									<select name="" class="form-control">
										<option value="">...</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Municipio</label>
									<select name="" class="form-control">
										<option value="">...</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Zona</label>
									<select name="" class="form-control">
										<option value="">...</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Presunto responsable</label>
									<input type="text" name="f_ini" value="" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Quejoso</label>
									<input type="text" name="f_ini" value="" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
						    <div class="col-md-6">
					            <div class="form-group">
					                <label for="municipio">Buscar palabras clave </label>
					                <small class="label pull-right bg-green" title="EFICIENTE E INTELIGENTE" data-toggle="popover" data-trigger="hover" data-content="El nuevo buscador de descripción de hechos ahora es más eficiente e inteligente, pues permite encontrar mayor cantidad de coincidencias con menor cantidad de palabras.">Buscardor mejorado</small>
					                <textarea name="" class="form-control" placeholder="Escriba una fase o palabras clave " style="resize: vertical;max-height: 300px;"></textarea>
					            </div>
					        </div>
						    <div class="col-md-6"></div>
						</div>
						
						<div class="row">
						    <div class="col-md-4"></div>
						    <div class="col-md-4">
						        <button type="submit" class="btn btn-flat btn-success btn-block">
						        	<i class="fa fa-search"></i>
						            Buscar información
						        </button>
						    </div>
						    <div class="col-md-4"></div>
						</div>
                	</form>
                	<div class="row">
                	    <div class="col-md-12">
                	        <div id="reporte_qd" class="table-responsive"></div>
                	    </div>
                	</div>
                </div>
            </div>
        </div>
    </div>
</section>




