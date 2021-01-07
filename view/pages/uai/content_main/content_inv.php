
<section class="content container-fluid  connectedSortable">
	<div class="nav-tabs-custom">
		<!-- Tabs within a box -->
		<ul class="nav nav-tabs pull-right">
			<li class="active"><a href="#qdp" data-toggle="tab">
			Asuntos Policiales
			</a></li>
			<li><a href="#especial" data-toggle="tab">Asuntos Especiales.</a></li>
			<li><a href="#ANDRES" data-toggle="tab">Actuaciones</a></li>
			<li class="pull-left header">
				<div class="col-md-11">
					<select id="year" name="year" class="form-control">
		                <option value="" selected>Todos los años disponibles</option>
		                <?php
		                $year = 2018;
		                $right = date('Y');
		                $diff = $right - $year;
		                for ($i=0; $i < $diff; $i++) { 
		                    echo "<option value='".($right-$i)."'>".($right-$i)."</option>";
		                }
		                ?>
		            </select>
				</div>
			</li>
		</ul>
		<div class="tab-content no-padding">
			<div class="chart tab-pane active" id="qdp">
				<div class="">
					<div class="box-body">
						<div class="row">
							<div class="col-md-6">
								<div class="table-responsive">
									<table id="dash" class="table table-hover table-condesed table-bordered">
										<caption class="bg-gray text-center">
											CONTADOR DE EXPEDIENTES POR ESTADO
										</caption>
										<thead>
											<tr class="bg-gray">
												<th width="50%">Estado del expediente</th>
												<th width="25%">Total</th>
												<th width="25%">Ver listado</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
							<div class="col-md-6">
								<div class="table-responsive">
									<table id="tbl_tipos_qdp" class="table table-hover table-condesed table-bordered">
										<caption class="bg-gray text-center">
											CONTADOR DE EXPEDIENTES POR TIPO
										</caption>
										<thead>
											<tr class="bg-gray">
												<th width="50%">Tipo de expediente</th>
												<th width="25%">Total</th>
												<th width="25%">Ver listado</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div> 
				
			</div>
			<div class="chart tab-pane" id="especial">
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="table-responsive">
								<table id="dash_especiales" class="table table-hover table-condesed table-bordered">
									<caption class="bg-gray text-center">
										CONTADOR DE EXPEDIENTES POR ESTADO
									</caption>
									<thead>
										<tr class="bg-gray">
											<th width="50%">Estado del expediente</th>
											<th width="25%">Total</th>
											<th width="25%">Ver listado</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
						<div class="col-md-6">
								<div class="table-responsive">
									<table id="tbl_tipos_especial" class="table table-hover table-condesed table-bordered">
										<caption class="bg-gray text-center">
											CONTADOR DE EXPEDIENTES POR TIPO
										</caption>
										<thead>
											<tr class="bg-gray">
												<th width="50%">Tipo de expediente</th>
												<th width="25%">Total</th>
												<th width="25%">Ver listado</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
					</div>
				</div>
			</div>
			<div class="chart tab-pane" id="ANDRES">
				<div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-6">
								<table id="dash_a" class="table table-hover table-condesed table-bordered">
								    <caption class="bg-gray text-center ">CONTADOR DE ACTUACIONES POR TIPO</caption>
								    <thead>
								        <tr class="bg-gray">
								            <th width="50%">Tipo de actuación</th>
								            <th width="25%">Total</th>
								            <th width="25%">Ver listado</th>
								        </tr>
								    </thead>
								    <tbody></tbody>
								</table>
							</div>
							<div class="col-md-6">
							    <div class="table-responsive">
							        <table id="tbl_ordenes" class="table table-hover table-bordered">
							            <caption class="bg-gray text-center">
							                CONTADOR DE ÓRDENES DE TRABAJO
							            </caption>
							            <thead>
							                <tr class="bg-gray">
							                    <th class="text-center">TIPO DE ORDEN</th>
							                    <th class="text-center">TOTAL</th>
							                    <th class="text-center">MOSTRAR</th>
							                </tr>
							            </thead>
							            <tbody></tbody>
							        </table>
							    </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="col-md-4 col-sm-6 col-xs-12">
	<div class="info-box">
		<span class="info-box-icon bg-green"><i class="fa fa-calculator"></i></span>

		<div class="info-box-content">
			<span class="info-box-text">
				TOTAL GENERAL POR ESTADO (ESPECIAL + POLICIAL)
			<span id="suma" class="info-box-number">0</span>
		</div>
	</div>
</div>
<section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
            	<div class="box">
            		<div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
					<div class="box-body">
					    <div id="div_general"></div>
					    <div id="qd_estado" class="hidden">
					        <div class="row">
					            <div class="col-md-12">
					                <div class="table-responsive">
					                    <table id="tbl_ee" class="table table-hover table-bordered">
					                        <caption class="text-center bg-gray">
					                            LISTADO DE EXPEDIENTES (QUEJAS Y DENUNCIAS)
					                        </caption>
					                        <thead>
					                            <tr class="bg-gray">
					                                <th width="10">#</th>
					                                <th width="30">Clave del expediente</th>
					                                <th width="15">Tipo asunto</th>
					                                <th width="15">Tipo de trámite</th>
					                                <th width="15">Procedencia</th>
					                                <th width="15">Fecha/hora de hechos</th>
					                            </tr>
					                        </thead>
					                        <tbody></tbody>
					                    </table>
					                </div>
					            </div>
					        </div>
					    </div>
					    <div id="actas" class="hidden">
					        <div class="row">
					            <div class="col-md-12">
					                <div class="table-responsive">
					                    <table id="tbl_actas" class="table table-hover table-bordered">
					                        <caption class="text-center bg-gray">
					                            LISTADO DE ACTUACIONES (SIRA)
					                        </caption>
					                        <thead>
					                            <tr class="bg-gray">
					                                <th width="10">#</th>
					                                <th width="30">Clave del acta</th>
					                                <th width="15">Quien elaboró</th>
					                                <th width="15">Fecha del acta</th>
					                                <th width="15">Procedencia</th>
					                            </tr>
					                        </thead>
					                        <tbody></tbody>
					                    </table>
					                </div>
					            </div>
					        </div>
					    </div>
					    <div id="div_oins" class="hidden">
					        <div class="row">
					            <div class="col-md-12">
					                <div class="table-responsive">
					                    <table id="tbl_oins" class="table table-hover table-bordered">
					                        <caption class="text-center bg-gray">
					                            LISTADO DE INVESTIGACIONES Y SUPERVISIONES
					                        </caption>
					                        <thead>
					                            <tr class="bg-gray">
					                                <th width="10">#</th>
					                                <th width="30">Clave</th>
					                                <th width="30">Número de oficio</th>
					                                <th width="30">Enlace operativo</th>
					                                <th width="15">Fecha</th>                               
					                            </tr>
					                        </thead>
					                        <tbody></tbody>
					                    </table>
					                </div>
					            </div>
					        </div>
					    </div>
					    
					</div> 
					<div class="box-footer">
						
					</div>                   
            	</div>
            </div>
        </div>
</section>
