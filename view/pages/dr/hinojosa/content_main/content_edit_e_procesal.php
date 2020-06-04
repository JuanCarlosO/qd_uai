<?php
#generar la consulta de la info
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
$queja_id = $_GET['exp'];
$q = new DRModel;
$data = $q->getOnlyEdoProcesal($queja_id);
if (empty($data)) {
	echo "Esta vacio";
}
#CONSULTAR LOS CARGOS DE LOS PRESUNTOS
$cargos = $q->getCargos();
$clave = $q->getClave($queja_id);
?>
<form action="#" method="post" id="frm_edit_edo_procesal">
	<input type="hidden" name="option" value="56">
	<input type="hidden" name="exp_respo" value="<?=$data->id?>">
	<input type="hidden" name="exp_id" value="<?=$_GET['exp']?>">
	<input type="hidden" id="presunto_id" name="presunto_id" value="<?=$data->presunto_id?>">
	<section class="content container-fluid">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="box">
	                <div class="box-header with-border">
	                    <h3 class="box-title">Formulario de edición del Estado Procesal <u><?=$clave?></u>. <small>Los campos obligatorios se encunetran marcados con un <i class="fa fa-asterisk text-red"></i> asterisco. </small></h3>
	                </div>
	                <!-- /.box-header -->
	                <div class="box-body">
	                	<div id="div_edo"></div>
	                	<div class="row">
	                		<div class="col-md-4">
	                			<div class="form-group">
	                				<label for="autoridad">Autoridad Destinataria <i class="fa fa-asterisk text-red"></i></label>
	                				<select id="autoridad" name="autoridad" class="form-control" required>
	                					<option value="">...</option>
	                					<option value="1" <?=$var = ( $data->autoridad == 'CHyJ' ) ? 'selected' : '';?> >CHyJ</option>
	                					<option value="2" <?=$var = ( $data->autoridad == 'OIC' ) ? 'selected' : '';?> >OIC</option>
	                					<option value="3" <?=$var = ( $data->autoridad == 'SC' ) ? 'selected' : '';?> >Subdirección de lo contencioso</option>
	                				</select>
	                			</div>
	                		</div>
	                		<div class="col-md-4">
	                			<div class="form-group">
	                				<label for="e_procesal">Estado procesal <i class="fa fa-asterisk text-red"></i></label>
	                				<select id="e_procesal" name="e_procesal" class="form-control" required>
	                					<option value="">...</option>
	                					<option value="1" <?=$var = ( $data->e_procesal == 'ENVIADO' ) ? 'selected' : '';?> >Enviado a</option>
	                					<option value="2" <?=$var = ( $data->e_procesal == 'TRÁMITE' ) ? 'selected' : '';?> >Trámite</option>
	                					<option value="3" <?=$var = ( $data->e_procesal == 'DEVUELTO' ) ? 'selected' : '';?> >Devolver a D.I.</option><option value="4" <?=$var = ( $data->e_procesal == 'EN FIRMA' ) ? 'selected' : '';?> >En firma</option><option value="5" <?=$var = ( $data->e_procesal == 'CON PROYECTO ELABORADO' ) ? 'selected' : '';?> >Con proyecto elaborado</option>
	                				</select>
	                			</div>
	                		</div>
	                		
	                	</div>
	                	<div id="motivo_sc" class="hidden">
	                		<!--MOTIVO DE LA DEVOLUCION A LA SC -->
	                		<div class="row">
	                			<div class="col-md-4">
	                				<div class="form-group">
	                					<label>Motivo del turnado</label>
	                					<select name="motivo_sc" id="motivo_sc" class="form-control">
	                						<option value="">...</option>
	                						<option value="IMPROCEDNECIA">IMPROCEDENCIA</option>
	                						<option value="RESERVA">RESERVA</option>
	                					</select>
	                				</div>
	                			</div>
	                		</div>
	                	</div>
	                	<div id="motivo" class="hidden">
	                		<h3> <center> <u>Sección de devolucion</u> </center> </h3>
	                		<div class="row">
	                			<div class="col-md-4">
	                				<label>Motivo de devolución</label>
	                				<input type="text" name="motivo" value="" class="form-control">
	                			</div>
	                			<div class="col-md-4">
	                				<div class="form-group">
	                					<label>Documento de devolución</label>
	                					<input type="file" name="file" value="" class="form-control">
	                				</div>
	                			</div>
	                		</div>
	                		
	                	</div>
	                	<div class="row">
	                		<div class="col-md-6">
	                			<div class="form-group">
	                				<label>Jefe/Encargado del departamento <i class="fa fa-asterisk text-red"></i></label>
	                				<input type="text" id="jefe" name="jefe" value="<?=$data->n_jefe?>" class=" form-control" required>
	                				<input type="hidden" id="jefe_id" name="jefe_id" value="<?=$data->jefe_id?>">
	                			</div>
	                		</div>
	                		<div class="col-md-6">
	                			<div class="form-group">
	                				<label>Analista asignado <i class="fa fa-asterisk text-red"></i></label>
	                				<input type="text" id="analista" name="analista" value="<?=$data->n_analista?>" class="form-control" required>
	                				<input type="hidden" id="analista_id" name="analista_id" value="<?=$data->analista_id?>">
	                			</div>
	                		</div>
	                	</div>
	                	<?php $class = (isset($data->n_presunto)) ? 'hidden' : '';?>
	                	<fieldset id="field_res" class="<?=$class?>">
	                		<legend>Información del servidor público.</legend>
	                		<div class="row">
	                			<div class="col-md-3">
	                				<div class="form-group">
	                					<label for="">Nombre <i class="fa fa-asterisk text-red"></i></label>
	                					<input type="text" name="nombre" id="nombre" value="" class="form-control" >
	                				</div>
	                			</div>
	                			<div class="col-md-3">
	                				<div class="form-group">
	                					<label for="">Primer Apellido <i class="fa fa-asterisk text-red"></i></label>
	                					<input type="text" name="ap_pat" id="ap_pat" value="" class="form-control">
	                				</div>
	                			</div>
	                			<div class="col-md-3">
	                				<div class="form-group">
	                					<label for="">Segundo Apellido <i class="fa fa-asterisk text-red"></i></label>
	                					<input type="text" id="ap_mat" name="ap_mat" value="" class="form-control">
	                				</div>
	                			</div>
	                			<div class="col-md-3">
	                				<div class="form-group">
	                					<label for="">Género <i class="fa fa-asterisk text-red"></i></label>
	                					<select id="genero" name="genero" class="form-control">
	                						<option value="" <?=( empty($data->genero) ) ? 'selected' : '' ;?>>...</option>
	                						<option value="1" <?=( $data->genero == 'HOMBRE' ) ? 'selected' : '' ;?>>Hombre</option>
	                						<option value="2" <?=( $data->genero == 'MUJER' ) ? 'selected' : '' ;?>>Mujer</option>
	                					</select>
	                				</div>
	                			</div>
	                		</div>
	                		<div class="row">
	                			<div class="col-md-3">
	                				<label>Nombre del cargo</label>
	                				<select id="cargo" name="cargo" class="form-control" >
	                					<option value="">...</option>
	                					<?php foreach ($cargos as $key => $cargo): ?>
	                					<option value="<?=$cargo->id?>"> <?=$cargo->nombre?> </option>
	                					<?php endforeach ?>
	                				</select>
	                			</div>
	                			<div class="col-md-3">
	                				<label>Adscripción <i class="fa fa-asterisk text-red"></i></label>
	                				<select id="adscripcion" name="adscripcion" class="form-control">
	                					<option value="">...</option>
	                					<option value="1" <?=( $data->procedencia == 'ESTATAL' ) ? 'selected' : '' ;?>>Secretaría de Seguridad</option>
	                					<option value="2" <?=( $data->procedencia == 'CPRS' ) ? 'selected' : '' ;?>>CPRS</option>
	                				</select>
	                			</div>
	                		</div>
	                		
	                	</fieldset>
	                	<?php $hidden = (isset($data->n_presunto)) ? '' : 'hidden';?>
	                	<div id="div_tbl_res" class="row <?=$hidden?>">
	                		<div class="col-md-12">
	                			<div class="table-responsive">
	                				<table id="tbl_responsables" class="table table-bordered table-condensed table-hover">
	                					<thead>
	                						<tr class="bg-green">
	                							<th class="text-center">#</th>
	                							<th class="text-center">Nombre del responsable</th>
	                							<th class="text-center">Género</th>
	                							<th class="text-center">Cargo</th>
	                							<th class="text-center">Adscripción</th>
	                							<th class="text-center">Eliminar</th>
	                						</tr>
	                					</thead>
	                					<tbody>
	                						<tr id="tr_<?=$data->presunto_id?>" class="text-center bg-teal">
	                							<td>1</td>
	                							<td><?=$data->n_presunto?></td>
	                							<td><?=$data->genero?></td>
	                							<td><?=$data->n_cargo?></td>
	                							<td>
	                								<?php if ($data->procedencia == 'ESTATAL'): ?>
	                									SECRETARÍA DE SEGURIDAD
	                								<?php else: ?>
	                									<?=$data->procedencia?>
	                								<?php endif ?>
	                							</td>
	                							<td>
	                								<button type="button" class="btn btn-danger btn-flat btn-sm" onclick="delete_responsable(<?=$data->presunto_id?>);">
	                									<i class="fa fa-trash"></i>
	                								</button>
	                							</td>
	                						</tr>
	                					</tbody>
	                				</table>
	                			</div>
	                		</div>
	                	</div> 	
	                    <fieldset>
	                    	<legend>Datos de la documentación</legend>
	                    </fieldset>
	                    
	                    <div class="row">
	                    	<div class="col-md-4">
	                    		<label>Oficio del envio <i class="fa fa-asterisk text-red"></i></label>
	                    		<input type="text" id="oficio" name="oficio" value="<?=$data->oficio?>" class="form-control" required>
	                    		<input type="hidden" id="oficio_id" name="oficio_id" value="">
	                    	</div>
	                    	<div class="col-md-4">
	                    		<label>Fecha del acuse <i class="fa fa-asterisk text-red"></i></label>
	                    		<input type="date" name="fecha" value="<?=$data->f_acuse?>" class="form-control" required>
	                    	</div>
	                    	<div class="col-md-4">
	                    		<label>Número de semana <i class="fa fa-asterisk text-red"></i></label>
	                    		<input type="number" name="semana" value="<?=$data->n_semana?>" class="form-control" min="1" max="52" required>
	                    	</div>
	                    </div>
	                    <div class="row">
	                    	<div class="col-md-4">
	                    		<label>Número de fojas <i class="fa fa-asterisk text-red"></i></label>
	                    		<input type="number" name="fojas" value="<?=$data->fojas?>" class="form-control" min="1" max="1000" required >
	                    	</div>
	                    	<div class="col-md-4">
	                    		<label>Tipo de documentación <i class="fa fa-asterisk text-red"></i></label>
	                    		<select name="t_doc" class="form-control" required>
	                    			<option value="">...</option>
	                    			<option value="1" <?=( $data->t_doc == 'ORIGINAL' ) ? 'selected' : '' ;?>>Original</option>
	                    			<option value="2" <?=( $data->t_doc == 'COPIAS' ) ? 'selected' : '' ;?>>Copias simples</option>
	                    		</select>
	                    	</div>
	                    	
	                    </div>
	                    
	                    
	                    <div class="row">
	                    	<div class="col-md-12">
	                    		<div class="form-group">
	                    			<label>Comentarios</label>
	                    			<textarea name="comentarios" class="form-control" style="max-height: 250px; resize: vertical;"><?=$data->comentarios?></textarea>
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