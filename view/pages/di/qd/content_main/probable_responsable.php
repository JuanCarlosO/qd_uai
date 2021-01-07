<?php 
$q = new QDModel;
$coordinaciones = json_decode($q->getCoordinaciones());
$subdirecciones = json_decode($q->getSubdirecciones()); 
$regiones = json_decode($q->getRegiones());
$agrupamientos = json_decode($q->getAgrupamientos());
#catalogos de Dirección General de Prevención y Reinserción Social
$agrupamientos_cprs = json_decode($q->getCargos());
#catalogos de Dirección de Policía de Tránsito
$coordinaciones_tra = json_decode($q->getCoordinacionesTra());
$agrupamientos_tra = json_decode($q->getAgrupamientosTra());
#catalogos de Personal Administrativo
$direcciones = json_decode($q->getDireccionAdmin());
$unidades = json_decode($q->getUnidadAdmin());
$d_areas = json_decode($q->getDirAreasAdmin());
$subd_areas = json_decode($q->getSubdirAreasAdmin());
$departamentos = json_decode($q->getDepartamentosAdmin());
?>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label>Nombre completo</label>
			<input type="text" class="form-control" name="name_presunto" value="<?=( !empty($r->presuntos->nombre) ) ? $r->presuntos->nombre: 'NO SE REGISTRO';?>">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<div class="form-group">                                                                
			<label>RFC</label>
			<input type="text" class="form-control" name="rfc" value="<?=(!empty($r->presuntos->rfc)) ? $r->presuntos->rfc : '' ;?>">
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">                                                                
			<label>CURP</label>
			<input type="text" class="form-control" name="curp" value="<?=(!empty($r->presuntos->curp)) ? $r->presuntos->curp : '' ;?>">
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">                                                                
			<label>CUIP</label>
			<input type="text" class="form-control" name="cuip" value="<?=(!empty($r->presuntos->cuip)) ? $r->presuntos->cuip : '' ;?>">
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">                                                                
			<label>TIPO DE PUESTO</label>
			<select class="form-control" name="t_puesto">
				<option value="">...</option>
				<option value="1">Administrativo</option>
				<option value="2">Operativo</option>
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label>Seleccionar cargo</label>
			<select class="form-control cargos" id="cargo" name="cargo">
				<option value="">...</option>
				<?php foreach ($cargos as $key => $cargo): ?>
					<?php if ( $r->presuntos->cargo_id == $cargo->id ): ?>
						<option value="<?=$cargo->id?>" selected><?=$cargo->nombre?></option>
						<?php else: ?>
							<option value="<?=$cargo->id?>"><?=$cargo->nombre?></option>
						<?php endif ?>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Género</label>
				<select class="form-control" id="ge" name="genero">
					<option value="">...</option>
					<option value="1">Hombre</option>
					<option value="2">Mujer</option>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Procedencia</label>
				<select class="form-control" id="procedencia_quejoso" name="procedencia_quejoso">
					<option value="">...</option>
					<option value="1">CPRS</option>
					<option value="2">SECRETARÍA DE SEGURIDAD</option>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label>Área</label>
				<select name="area" id="area" class="form-control">
					<option value="">...</option>
				</select>
			</div>
		</div>
		<div id="area_3" class="hidden">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Agrupamiento</label>
						<select name="" id="" class="form-control">
							<option value="">...</option>
							<?php foreach ($agrupamientos_cprs as $key => $c): ?>
								<option value="<?=$c->id?>"><?=$c->nombre?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="area_1" class="hidden">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Coordinación</label>
					<select name="coordina" id="" class="form-control">
						<option value="">...</option>
						<?php foreach ($coordinaciones as $key => $c): ?>
							<option value="<?=$c->id?>"><?=$c->nombre?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Subdirección</label>
					<select name="subd" id="" class="form-control">
						<option value="">...</option>
						<?php foreach ($subdirecciones as $key => $s): ?>
							<option value="<?=$s->id?>"><?=$s->nombre?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Región</label>
					<select name="region" id="" class="form-control">
						<option value="">...</option>
						<?php foreach ($regiones as $key => $r): ?>
							<option value="<?=$r->id?>"><?=$r->nombre?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Agrupamiento</label>
					<select name="agrupa" id="" class="form-control">
						<option value="">...</option>
						<?php foreach ($agrupamientos as $key => $a): ?>
							<option value="<?=$a->id?>"><?=$a->nombre?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div id="area_2" class="hidden">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Coordinación</label>
					<select name="" id="" class="form-control">
						<option value="">...</option>
						<?php foreach ($coordinaciones_tra as $key => $s): ?>
							<option value="<?=$s->id?>"><?=$s->nombre?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Agrupamiento</label>
					<select name="" id="" class="form-control">
						<option value="">...</option>
						<?php foreach ($agrupamientos_tra as $key => $s): ?>
							<option value="<?=$s->id?>"><?=$s->nombre?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
	</div>

	<div id="area_4" class="hidden">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Dirección</label>
					<select name="" id="" class="form-control">
						<option value="">...</option>
						<?php foreach ($direcciones as $key => $s): ?>
							<option value="<?=$s->id?>"><?=$s->nombre?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Unidad</label>
					<select name="" id="" class="form-control">
						<option value="">...</option>
						<?php foreach ($unidades as $key => $s): ?>
							<option value="<?=$s->id?>"><?=$s->nombre?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Dirección por áreas</label>
					<select name="" id="" class="form-control">
						<option value="">...</option>
						<?php foreach ($d_areas as $key => $s): ?>
							<option value="<?=$s->id?>"><?=$s->nombre?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Subdirección</label>
					<select name="" id="" class="form-control">
						<option value="">...</option>
						<?php foreach ($subd_areas as $key => $s): ?>
							<option value="<?=$s->id?>"><?=$s->nombre?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Departamento</label>
					<select name="" id="" class="form-control">
						<option value="">...</option>
						<?php foreach ($departamentos as $key => $s): ?>
							<option value="<?=$s->id?>"><?=$s->nombre?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Media filiación</label>
				<textarea class="form-control" id="media" name="media" style="resize: vertical; max-height: 250px;"><?=(!empty($r->presuntos->comentarios)) ? $r->presuntos->comentarios :'' ;?></textarea>
			</div>
		</div>
	</div>