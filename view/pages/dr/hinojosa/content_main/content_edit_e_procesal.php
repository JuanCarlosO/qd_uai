<?php

#generar la consulta de la info
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
$queja_id = $_GET['queja_id'];
$q = new DRModel;
$data = $q->getOnlyEdoProcesal($queja_id);

if (empty($data)) {
	echo "<h1>NO SE PUEDE EDITAR</h1>";exit;
}
$presunto = $data['presunto'] ;
$eprocesal = $data['eprocesal'];
#CONSULTAR LOS CARGOS DE LOS PRESUNTOS
$cargos = $q->getCargos();
$conductas = json_decode($q->getConductasRespo());
$clave = $q->getClave($queja_id);
echo "<pre>";print_r($eprocesal);echo "</pre>";
?>
<form action="#" method="post" id="frm_edit_edo_procesal">
	<input type="hidden" name="option" value="56">
	<input type="hidden" name="exp_respo" value="<?=$eprocesal->id?>">
	<input type="hidden" name="queja_id" value="<?=$_GET['queja_id']?>">
	<input type="hidden" id="presunto_id" name="presunto_id" value="<?=( isset($presunto->id) ) ? $presunto->id : 0 ;?>">
	<section class="content container-fluid">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="box">
	                <div class="box-header with-border">
	                    <h3 class="box-title">Formulario de edición del Estado Procesal <u><?=$clave?></u>. <small>Los campos obligatorios se encunetran marcados con un <i class="fa fa-asterisk text-red"></i> asterisco. </small></h3>
	                </div>
	                <div class="box-body">
	                	<div id="div_edo"></div>
	                	<div class="box-group" id="accordion">
	                		<div class="panel box box-success box-solid">
	                			<div class="box-header with-border">
	                				<h4 class="box-title">
	                					<a data-toggle="collapse" data-parent="#accordion" href="#edo_procesal">
	                						Estado procesal
	                					</a>
	                				</h4>
	                			</div>
	                			<div id="edo_procesal" class="panel-collapse collapse in">
	                				<div class="box-body">
	                					<div class="row">
	                						<div class="col-md-4">
	                							<div class="form-group">
	                								<label for="e_procesal">Estado procesal <i class="fa fa-asterisk text-red"></i></label>
	                								<select id="e_procesal" name="e_procesal" class="form-control" required>
	                									<option value="">...</option>
	                									<option value="3" <?=$var = ( $data['eprocesal']->e_procesal == 'DEVUELTO' ) ? 'selected' : '';?> >Devolver a D.I.</option>
	                									<option value="1" <?=$var = ( $data['eprocesal']->e_procesal == 'ENVIADO' ) ? 'selected' : '';?> >Enviado a</option>
	                									<option value="4" <?=$var = ( $data['eprocesal']->e_procesal == 'RESULETO' ) ? 'selected' : '';?> >Resuelto</option>
	                								</select>
	                							</div>
	                						</div>
	                						<div class="col-md-4 hidden" id="div_auto">
	                							<div class="form-group">
	                								<label for="autoridad">Autoridad Destinataria <i class="fa fa-asterisk text-red"></i></label>
	                								<select id="autoridad" name="autoridad" class="form-control" required>
	                									<option value="">...</option>
	                									<option value="1" <?=$var = ( $data['eprocesal']->autoridad == 'CHyJ' ) ? 'selected' : '';?> >CHyJ</option>
	                									<option value="2" <?=$var = ( $data['eprocesal']->autoridad == 'OIC' ) ? 'selected' : '';?> >OIC</option>
	                									<option value="3" <?=$var = ( $data['eprocesal']->autoridad == 'SC' ) ? 'selected' : '';?> >Subdirección de lo contencioso</option>
	                								</select>
	                							</div>
	                						</div>

	                					</div>
	                					<div class="row">
	                						<div class="col-md-6">
	                							<div class="form-group">
	                								<label>Jefe/Encargado del departamento <i class="fa fa-asterisk text-red"></i></label>
	                								<input type="text" id="jefe" name="jefe" value="<?=$data['eprocesal']->n_jefe?>" class=" form-control" required>
	                								<input type="hidden" id="jefe_id" name="jefe_id" value="<?=$data['eprocesal']->jefe_id?>">
	                							</div>
	                						</div>
	                						<div class="col-md-6">
	                							<div class="form-group">
	                								<label>Analista asignado <i class="fa fa-asterisk text-red"></i></label>
	                								<input type="text" id="analista" name="analista" value="<?=$eprocesal->n_analista?>" class="form-control" required>
	                								<input type="hidden" id="analista_id" name="analista_id" value="<?=$eprocesal->analista_id?>">
	                							</div>
	                						</div>
	                					</div>
	                					
	                					<fieldset>
	                						<legend>Datos de la documentación</legend>
	                						<div class="row">
	                							<div class="col-md-4">
	                								<div id="oficio" class="form-group hidden">
	                									<label>Número de oficio</label>
	                									<input type="text" name="n_oficio" id="n_oficio" value="" class="form-control ">
	                									<input type="hidden" id="n_oficio_id" name="n_oficio_id" value="">
	                								</div>
	                							</div>
	                							<div class="col-md-4">
	                								<div id="f_acuse" class="form-group hidden">
	                									<label>Fecha del acuse</label>
	                									<input type="date" name="f_acuse" value="" class="form-control ">
	                								</div>
	                							</div>
	                							<div class="col-md-4">
	                								<div class="form-group hidden" id="n_semana">
	                									<label>Número de semana</label>
	                									<input type="number" name="n_semana" value="" class="form-control" min="1">
	                								</div>
	                							</div>
	                						</div>
	                						<div class="row">
	                							<div class="col-md-4">
	                								<div class="form-group hidden" id="fojas">
	                									<label>Número de fojas</label>
	                									<input type="number"  name="fojas" value="" class="form-control" min="1">
	                								</div>
	                							</div>
	                							<div class="col-md-4">
	                								<div class="form-group hidden"  id="t_doc">
	                									<label>Tipo de documentación</label>
	                									<select name="t_doc" class="form-control ">
	                										<option value="">...</option>
	                										<option value="1">Original</option>
	                										<option value="2">Copias simples</option>
	                									</select>
	                								</div>
	                							</div>
	                							<div class="col-md-4">
	                								<div id="div_conducta" class="form-group hidden">
	                									<label>Seleccione una conducta</label>
	                									<select id="conducta" name="conducta"  class="form-control col-md-12">
	                										<option value="">...</option>
	                										<?php foreach ($conductas as $key => $c): ?>
	                											<?php if ( $eprocesal->conducta_id == $c->id ): ?>
	                												<option value="<?=$c->id?>" selected><?=$c->nombre?></option>		
	                											<?php else: ?>
	                												<option value="<?=$c->id?>"><?=$c->nombre?></option>
	                											<?php endif ?>
	                										
	                										<?php endforeach ?>
	                									</select>
	                								</div>
	                								<div id="div_motivo" class="form-group hidden">
	                									<label>Motivo del envio</label>
	                									<select id="motivo" name="motivo"  class="form-control">
	                										<option value="">...</option>
	                										<option value="INCOMPETENCIA">INCOMPETENCIA</option>
	                										<option value="ARCHIVO">ARCHIVO</option>
	                										<option value="RESERVA">RESERVA</option>
	                										<option value="IMPROCEDENCIA">IMPROCEDENCIA</option>
	                									</select>
	                								</div>

	                							</div>
	                						</div>
	                					</fieldset>
	                					<div id="resuelto" class="hidden">
	                						<div class="row">
	                							<div class="col-md-4">
	                								<div class="form-group">
	                									<label>Fecha de sesion</label>
	                									<input type="date" name="f_sesion" value="" class="form-control">
	                								</div>
	                							</div>
	                							<div class="col-md-4">
	                								<div class="form-group">
	                									<label>Fecha de notificación</label>
	                									<input type="date" name="f_notifica" value="" class="form-control">
	                								</div>
	                							</div>
	                							<div class="col-md-4">
	                								<div class="form-group">
	                									<label>Fecha de resolución</label>
	                									<input type="date" name="f_resolucion" value="" class="form-control">
	                								</div>
	                							</div>
	                						</div>
	                						<div class="row">
	                							<div class="col-md-4">
	                								<div class="form-group">
	                									<label>Sanción</label>
	                									<select name="castigo" id="castigos" class="form-control">
	                										<option value="">...</option>
	                										<option value="1">Amonestación</option>
	                										<option value="2">Inexistencia</option>
	                										<option value="3">Supensión</option>
	                										<option value="4">Remoción del cargo</option>
	                										<option value="5">Separación del servicio.</option>
	                									</select>
	                								</div>
	                							</div>
	                							<div class="col-md-4">
	                								<div class="form-group">
	                									<label>Días de suspensión </label>
	                									<select name="dias_s" id="dias_s" class="form-control">
	                										<option value="">...</option>
	                										<option value="1">1</option>
	                										<option value="2">2</option>
	                										<option value="3">3</option>
	                										<option value="4">4</option>
	                										<option value="5">5</option>
	                										<option value="6">6</option>
	                										<option value="7">7</option>
	                										<option value="8">8</option>
	                										<option value="9">9</option>
	                										<option value="10">10</option>
	                										<option value="11">11</option>
	                										<option value="12">12</option>
	                										<option value="13">13</option>
	                										<option value="14">14</option>
	                										<option value="15">15</option>
	                									</select>
	                								</div>
	                							</div>
	                							<div class="col-md-4">
	                								<div class="form-group">
	                									<label></label>
	                								</div>
	                							</div>
	                						</div>
	                					</div>
	                					<div class="row">
	                						<div class="col-md-12">
	                							<div class="form-group">
	                								<label>Comentarios</label>
	                								<textarea name="comentarios" class="form-control" style="max-height: 250px; resize: vertical;"><?=( isset($data->comentarios) ) ? $data->comentarios : '' ;?></textarea>
	                							</div>
	                						</div>
	                					</div>
	                				</div>
	                			</div>
	                		</div>
	                		<div class="panel box box-success box-solid">
	                			<div class="box-header with-border">
	                				<h4 class="box-title">
	                					<a data-toggle="collapse" data-parent="#accordion" href="#presunto">
	                						Datos del responsable
	                					</a>
	                				</h4>
	                			</div>
	                			<div id="presunto" class="panel-collapse collapse">
	                				<div class="box-body">
	                					<div class="row">
	                						<div class="col-md-12">
	                							<div class="form-group">
	                								<label>Nombre completo del responsable</label>
	                								<input type="text" name="name" value="<?=( isset($presunto->nombre) ) ? $presunto->nombre : '';?>" class="form-control">
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
	                								<label>Tipo de puesto</label>
	                								<select name="t_puesto" id="t_puesto" class="form-control">
	                									<option value="">...</option>
	                									<option value="1" <?=(!empty($presunto->t_puesto) && $presunto->t_puesto == 'ADMINISTRATIVO') ? 'selected' : '' ;?>>Administrativo</option>
	                									<option value="2" <?=(!empty($presunto->t_puesto) && $presunto->t_puesto == 'OPERATIVO') ? 'selected' : '' ;?>>Operativo</option>
	                								</select>
	                							</div>
	                						</div>
	                					</div>
	                					<div class="row">
	                						<div class="col-md-3">
	                							<div class="form-group">
	                								<label>Seleccionar cargo</label>
	                								<select name="cargo" id="" class="form-control">
	                									<option value="">...</option>
	                									<?php foreach ($cargos as $key => $car): ?>
	                										<?php if ( $eprocesal->conducta_id == $c->id ): ?>
	                											<option value="<?=$car->id?>" selected><?=$car->nombre?></option>
	                										<?php else: ?>
	                											<option value="<?=$car->id?>"><?=$car->nombre?></option>
	                										<?php endif ?>
	                										
	                									<?php endforeach ?>
	                								</select>
	                							</div>
	                						</div>
	                						<div class="col-md-3">
	                							<div class="form-group">
	                								<label>Género</label>
	                								<select name="genero" id="" class="form-control">
	                									<option value="">...</option>
	                									<option value="1" <?=( isset($presunto->genero) && $presunto->genero  ) ? 'selected' : '' ;?>>Hombre</option>
	                									<option value="2" <?=( isset($presunto->genero) && $presunto->genero  ) ? 'selected' : '' ;?>>Mujer</option>
	                								</select>
	                							</div>
	                						</div>
	                						<div class="col-md-3">
	                							<div class="form-group">
	                								<label>Procedencia</label>
	                								<select name="procedencia" id="" class="form-control">
	                									<option value="">...</option>
	                									<option value="1" <?=( isset($presunto->procedencia) && $presunto->procedencia == 1) ? 'selected' : '';?> >CPRS</option>
	                									<option value="2" <?=( isset($presunto->procedencia) && $presunto->procedencia == 2) ? 'selected' : '';?> >SECRETARIA DE SEGURIDAD</option>
	                								</select>
	                							</div>
	                						</div>
	                					</div>
	                					<div class="row">
	                						<div class="col-md-12">
	                							<div class="form-group">
	                								<label>Media filiación</label>
	                								<textarea name="media" id="" class="form-control" style="resize: vertical; max-height: 250px;"><?=( isset($presunto->comentarios) ) ? $presunto->comentarios : '';?></textarea>
	                							</div>
	                						</div>
	                					</div>
	                				</div>
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