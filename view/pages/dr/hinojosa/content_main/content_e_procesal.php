 <form action="#" method="post" id="frm_edo_procesal">
	<input type="hidden" name="option" value="54">
	<input type="hidden" name="exp_id" value="<?=$_GET['exp']?>">
	<section class="content container-fluid">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="box">
	                <div class="box-header with-border">
	                    <h3 class="box-title">Seguimiento de Estado Procesal. <small>Los campos obligatorios se encunetran marcados con un <i class="fa fa-asterisk text-red"></i> asterisco. </small></h3>
	                </div>
	                <!-- /.box-header -->
	                <div class="box-body">
	                	<div id="div_edo"></div>
	                	<fieldset>
	                		<legend>Datos de la documentación</legend>
	                	</fieldset>
	                	
	                	<div class="row">
	                		<div class="col-md-4">
	                			<label>Oficio del envio <i class="fa fa-asterisk text-red"></i></label>
	                			<input type="text" id="oficio" name="oficio" value="" class="form-control" required>
	                			<input type="hidden" id="oficio_id" name="oficio_id" value="">
	                		</div>
	                		<div class="col-md-4">
	                			<label>Fecha del acuse <i class="fa fa-asterisk text-red"></i></label>
	                			<input type="date" name="fecha" value="" class="form-control"required>
	                		</div>
	                		<div class="col-md-4">
	                			<label>Número de semana <i class="fa fa-asterisk text-red"></i></label>
	                			<input type="number" name="semana" value="" class="form-control" min="1" max="52" required>
	                		</div>
	                	</div>
	                	<div class="row">
	                		<div class="col-md-4">
	                			<label>Número de fojas <i class="fa fa-asterisk text-red"></i></label>
	                			<input type="number" name="fojas" value="" class="form-control" min="1" max="1000" required >
	                		</div>
	                		<div class="col-md-4">
	                			<label>Tipo de documentación <i class="fa fa-asterisk text-red"></i></label>
	                			<select name="t_doc" class="form-control" required>
	                				<option value="">...</option>
	                				<option value="1">Original</option>
	                				<option value="2">Copias simples</option>
	                			</select>
	                		</div>
	                		
	                	</div>
	                	
	                	
	                	
	                	<div class="row">
	                		<div class="col-md-12">
	                			<div class="form-group">
	                				<label>Seleccione una conducta <i class="fa fa-asterisk text-red"></i></label>
	                				<div class="input-group">
	                					<select name="conducta" id="conducta" class="form-control" required="">
	                						<option value="">...</option>
	                					</select>
	                					<span class="input-group-btn">
	                						<button type="button" class="btn btn-success btn-flat" onclick="add_conducta();">Agregar nueva conducta <i class="fa fa-plus"></i></button>
	                					</span>
	                				</div>
	                			</div>
	                		</div>
	                	</div>
	                	<fieldset>
	                		<legend>Información del servidor público.</legend>
	                		<div class="row">
	                			<div class="col-md-3">
	                				<div class="form-group">
	                					<label for="">Nombre <i class="fa fa-asterisk text-red"></i></label>
	                					<input type="text" name="nombre" value="" class="form-control" required>
	                				</div>
	                			</div>
	                			<div class="col-md-3">
	                				<div class="form-group">
	                					<label for="">Primer Apellido <i class="fa fa-asterisk text-red"></i></label>
	                					<input type="text" name="" value="" class="form-control" required>
	                				</div>
	                			</div>
	                			<div class="col-md-3">
	                				<div class="form-group">
	                					<label for="">Segundo Apellido <i class="fa fa-asterisk text-red"></i></label>
	                					<input type="text" name="" value="" class="form-control" required>
	                				</div>
	                			</div>
	                			<div class="col-md-3">
	                				<div class="form-group">
	                					<label for="">Género <i class="fa fa-asterisk text-red"></i></label>
	                					<select name="genero" class="form-control" required>
	                						<option value="">...</option>
	                						<option value="">Hombre</option>
	                						<option value="">Mujer</option>
	                					</select>
	                				</div>
	                			</div>
	                		</div>
	                		<div class="row">
	                			<div class="col-md-3">
	                				<label>Nombre del cargo</label>
	                				<select id="cargo" name="cargo" class="form-control" required>
	                					<option value="">...</option>
	                				</select>
	                			</div>
	                			<div class="col-md-3">
	                				<label>Adscripción <i class="fa fa-asterisk text-red"></i></label>
	                				<select name="adscripcion" class="form-control" required>
	                					<option value="">...</option>
	                					<option value="1">Secretaría de Seguridad</option>
	                					<option value="2">CPRS</option>
	                				</select>
	                			</div>
	                		</div>
	                	</fieldset>
	                	<div class="row">
	                		<div class="col-md-6">
	                			<div class="form-group">
	                				<label>Jefe/Encargado del departamento <i class="fa fa-asterisk text-red"></i></label>
	                				<input type="text" id="jefe" name="jefe" value="" class=" form-control" required>
	                				<input type="hidden" id="jefe_id" name="jefe_id" value="">
	                			</div>
	                		</div>
	                		<div class="col-md-6">
	                			<div class="form-group">
	                				<label>Analista asignado <i class="fa fa-asterisk text-red"></i></label>
	                				<input type="text" id="analista" name="analista" value="" class="form-control" required>
	                				<input type="hidden" id="analista_id" name="analista_id" value="">
	                			</div>
	                		</div>
	                	</div>
	                	<div class="row">
	                		<div class="col-md-4">
	                			<div class="form-group">
	                				<label for="autoridad">Autoridad Destinataria <i class="fa fa-asterisk text-red"></i></label>
	                				<select id="autoridad" name="autoridad" class="form-control" required>
	                					<option value="">...</option>
	                					<option value="1">CHyJ</option>
	                					<option value="2">OIC</option>
	                					<option value="3">Subdirección de lo contencioso</option>
	                				</select>
	                			</div>
	                		</div>
	                		<div class="col-md-4">
	                			<div class="form-group">
	                				<label for="e_procesal">Estado procesal <i class="fa fa-asterisk text-red"></i></label>
	                				<select id="e_procesal" name="e_procesal" class="form-control" required>
	                					<option value="">...</option>
	                					<option value="1">Enviado</option>
	                					<option value="2">Trámite</option>
	                					<option value="3">Devolver a D.I.</option>
	                				</select>
	                			</div>
	                		</div>
	                	</div>
	                    <div class="row">
	                		<div class="col-md-12">
	                			<div class="form-group">
	                				<label>Comentarios</label>
	                				<textarea name="comentarios" class="form-control" style="max-height: 250px; resize: vertical;"></textarea>
	                			</div>
	                		</div>
	                	</div>
	                    <div class="row">
	                    	<div class="col-md-4"></div>
	                    	<div class="col-md-4">
	                    		<button type="submit" class="btn btn-success btn-flat btn-block">
	                    			<i class="fa fa-floppy-o"></i> Guardar información
	                    		</button>
	                    	</div>
	                    	<div class="col-md-4"></div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>
</form>