<input type="hidden" id="acciones_id" name="acciones_id" value="<?=$_GET['ac'];?>">

<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';

$accion = $_GET['ac'];
$a = new SiraModel;
$r = $a->getOnlyAcciones($accion);
 
?>
<section class="content container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
                    <h3 class="box-title">Seguimiento a las acciones realizadas a la OT: <b><?=$r[0]['clave']?></b></h3>
                </div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="box box-success box-solid ">
								<div class="box-header with-border">
									<h3 class="box-title">Presuntos(as) responsables </h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
										</button>
									</div>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-md-4 pull-right">
											<button id="btn_add_pr" class="btn btn-info btn-flat btn-block" data-toggle="modal" data-target="#modal_add_pr">
												<i class="fa fa-plus"></i> Agregar presunto
											</button>
										</div>
									</div>
									<div id="alert_pr"></div>
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table id="tbl_pr" class="table table-bordered table-hover">
													<thead>
														<tr>
															<th>#</th>
															<th>NOMBRE COMPLETO</th>
															<th>GENERO</th>
															<th>
																<i class="fa fa-trash text-red"></i>
															</th>
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
						<div class="col-md-6">
							<div class="box box-success box-solid ">
								<div class="box-header with-border">
									<h3 class="box-title">Quejosos(as)</h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
										</button>
									</div>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-md-4 pull-right">
											<button class="btn btn-info btn-flat btn-block " data-toggle="modal" data-target="#modal_add_quejoso">
												<i class="fa fa-plus"></i> Agregar quejoso
											</button>
										</div>
									</div>
									<div id="alert_quejoso"></div>
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table id="tbl_quejoso" class="table table-bordered table-hover">
													<thead>
														<tr>
															<th>#</th>
															<th>NOMBRE COMPLETO</th>
															<th>GENERO</th>
															<th>
																<i class="fa fa-trash text-red"></i>
															</th>
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
					<div class="row">
						<div class="col-md-6">
							<div class="box box-success box-solid ">
								<div class="box-header with-border">
									<h3 class="box-title">Vehículos implicados</h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
										</button>
									</div>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-md-4 pull-right">
											<button class="btn btn-info btn-flat btn-block "  data-toggle="modal" data-target="#modal_add_vehiculo">
												<i class="fa fa-plus"></i> Agregar vehiculo
											</button>
										</div>
									</div>
									<div id="alert_autos"></div>
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table id="tbl_autos" class="table table-bordered table-hover">
													<thead>
														<tr>
															<th>#</th>
															<th>CARACTERÍSTICAS</th>
															<th>DATOS DEL VEHÍCULO</th>
															<th>
																<i class="fa fa-trash text-red"></i>
															</th>
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
						<div class="col-md-6">
							<div class="box box-success box-solid ">
								<div class="box-header with-border">
									<h3 class="box-title">Semovientes</h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
										</button>
									</div>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-md-4 pull-right">
											<button class="btn btn-info btn-flat btn-block " data-toggle="modal" data-target="#modal_add_animal">
												<i class="fa fa-plus"></i> Agregar semoviente
											</button>
										</div>
									</div>
									<div id="alert_animales"></div>
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table id="tbl_animales" class="table table-bordered table-hover">
													<thead>
														<tr>
															<th>#</th>
															<th>CARACTERÍSTICAS</th>
															<th>DATOS DEL SEMOVIENTE</th>
															<th>
																<i class="fa fa-trash text-red"></i>
															</th>
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
					<div class="row">
						<div class="col-md-6">
							<div class="box box-success box-solid ">
								<div class="box-header with-border">
									<h3 class="box-title">Armas</h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
										</button>
									</div>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-md-4 pull-right">
											<button class="btn btn-info btn-flat btn-block " data-toggle="modal" data-target="#modal_add_arma">
												<i class="fa fa-plus"></i> Agregar armas
											</button>
										</div>
									</div>
									<div id="alert_armas"></div>
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table id="tbl_armas" class="table table-bordered table-hover">
													<thead>
														<tr>
															<th>#</th>
															<th>TIPO DE ARMA</th>
															<th>DATOS DEL ARMA</th>
															<th>
																<i class="fa fa-trash text-red"></i>
															</th>
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
						<!--<div class="col-md-6">
							<div class="box box-success box-solid ">
								<div class="box-header with-border">
									<h3 class="box-title">Archivos</h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
										</button>
									</div>
								</div>
								<div class="box-body">
									
									<div id="alert_docs"></div>
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table id="tbl_docs" class="table table-bordered table-hover">
													<thead>
														<tr>
															<th>#</th>
															<th>NOMBRE</th>
															<th>COMENTARIOS</th>
															<th>
																<i class="fa fa-eye"></i>
															</th>
															<th>
																<i class="fa fa-trash text-red"></i>
															</th>
														</tr>
													</thead>
													<tbody></tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>-->
					</div>
					                 	
				</div>
			</div>
		</div>
	</div>
</section>