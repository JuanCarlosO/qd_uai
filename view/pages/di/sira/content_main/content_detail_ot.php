<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div id="detalle" class="row">
            	<div class="col-md-12">
            		<div class="panel-group" id="accordion">
            			<div class="panel panel-primary">
            				<div class="panel-heading">
            					<h4 class="panel-title">
            						<a data-toggle="collapse" data-parent="#accordion" href="#d_oin">
            							Detalles de la Orden de Trabajo 
            						</a>
            					</h4>
            				</div>
            				<div id="d_oin" class="panel-collapse collapse in">
            					<div class="panel-body">
            						<div class="row">
            							<div class="col-md-2">
            								<label>ID</label>
            								<input type="text" value="1" placeholder="" readonly="" class="form-control">
            							</div>
            							<div class="col-md-3">
            								<label>Clave de OT</label>
            								<input type="text" name="" value="206C03010000000/INS/1/2020" readonly="" class="form-control">
            							</div>
            							<div class="col-md-3">
            								<label>Estatus</label>
            								<input type="text" name="" value="PARCIAL SIN RESULTADO" readonly="" class="form-control">
            							</div>
            						</div>
            						<div class="row">
            							<div class="col-md-12">
            								<label>Participantes</label>
            								<ol>
            									<li> Nombre del participante 1 </li>
            									<li> Nombre del participante 2 </li>
            								</ol>
            							</div>
            						</div>
            						<h3> <center>INFORMACIÓN DEL OFICIO </center> </h3>
            						<div class="row">
            							<div class="col-md-4">
            								<label>ID oficio</label>
            								<input type="text" id="id_of" value="1" class="form-control" readonly="">
            							</div>
            							<div class="col-md-4">
            								<label>Fecha oficio</label>
            								<input type="text" id="date_of" value="<?=date('d-m-Y')?>" class="form-control" readonly="">
            							</div>
            							<div class="col-md-4">
            								<label>ID oficio</label>
            								<input type="text" id="date_of" value="206C03010000000/66/2020" class="form-control" readonly="">
            							</div>
            						</div>
            					</div>
            					
            				</div>
            			</div>
            			<div class="panel panel-success">
            				<div class="panel-heading">
            					<h4 class="panel-title">
            						<a data-toggle="collapse" data-parent="#accordion" href="#d_sira">
            							Listado y detalle de actas 
            						</a>
            					</h4>
            				</div>
            				<div id="d_sira" class="panel-collapse collapse">
            					No existen actas relacionadas a esta orden de inspección
            					<ol id="sira_actas">
            						<li> 
            							<a href="#"  data-toggle="modal" data-target="#modal_acta"> 21000001220/inv/1/2020 </a> 
            						</li>
            					</ol>
            				</div>
            			</div>
            			<div class="panel panel-info">
            				<div class="panel-heading">
            					<h4 class="panel-title">
            						<a data-toggle="collapse" data-parent="#accordion" href="#d_quejas">
            							Quejas y Denuncias
            						</a>
            					</h4>
            				</div>
            				<div id="d_quejas" class="panel-collapse collapse">
            					<div class="row">
            						<div class="col-md-12">
            							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            							tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            							quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            							consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            							cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            							proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            						</div>
            					</div>
            				</div>
            			</div>

            		</div>
            	</div>
            </div>
        </div>
    </div>
</section>


