<?php
require_once 'model/Connection.php';
require_once 'model/QDOldModel.php';
if(!empty($_GET['cve_exp'])){
    $clave = $_GET['cve_exp'];
    #BUSCAR EL ID EN EL SISTEMA VIEJITO
    $qd = new QDOldModel();
    $cedula = $qd->getCedula($clave);
}else{
	$clave = "NO ESPECIFICADO";
}
?>
<div class="contente container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                    
                    <div class="row">
                    	<div class="col-md-3"></div>
                    	<div class="col-md-6">
                    		<table class="table table-hover table-bordered">
                    			<caption class="bg-gray text-center">
                    				CLAVE DEL EXPEDIENTE <b><?=$clave?></b>
                    			</caption>
                    			<thead></thead>
                    			<tbody>
                    				<tr>
                    					<th class="text-center">Fecha de hechos: </th>
                    					<td class="text-center"> <?=$cedula['f_hechos']; ?> </td>
                    				</tr>
                    				<tr>
                    					<th class="text-center">Hora de hechos: </th>
                    					<td class="text-center"> <?=$cedula['h_hechos']; ?> </td>
                    				</tr>
                    				<tr>
                    					<th class="text-center">Tipo de trámite: </th>
                    					<td class="text-center"> <?=$cedula['t_tramite']; ?> </td>
                    				</tr>
                    				<tr>
                    					<th class="text-center">Fecha de registro: </th>
                    					<td class="text-center"> <?=$cedula['f_apertura']; ?> </td>
                    				</tr>
                    				<tr>
                    					<th class="text-center">Presunta(s) conducta(s): </th>
                    					<td class="text-left">
                    						<ol>
                    						<?php
                    						for ($i=0; $i < count($cedula['conductas']); $i++) { 
                    							echo "<li>".$cedula['conductas'][$i]."</li>";
                    						}
                    						?>
                    						</ol>
                    					</td>
                    				</tr>
                    				<tr>
                    					<th class="text-center">Artículo: </th>
                    					<td class="text-center"> <?=$cedula['articulo']?> </td>
                    				</tr>
                    				<tr>
                    					<th class="text-center">Vía de recepción: </th>
                    					<td class="text-left"> 
                    						<ol>
                    						<?php
                    						for ($i=0; $i < count($cedula['conductas']); $i++) { 
                    							echo "<li>".$cedula['vias'][$i]."</li>";
                    						}
                    						?>
                    						</ol>
                    					</td>
                    				</tr>
                    				<tr>
                    					<th class="text-center">Descripción completa de hechos: </th>
                    					<td class="text-justify"> <p>
                    						<?=$cedula['d_hechos']; ?>
                    					</p> </td>
                    				</tr>
                    				<tr>
                    					<th class="text-center">Dirección: </th>
                    					<td class="text-center"> <?=$cedula['direccion']; ?> </td>
                    				</tr>
                    				<tr>
                    					<th class="text-center">Municipio: </th>
                    					<td class="text-center"> <?=$cedula['n_municipio']; ?> </td>
                    				</tr>
                    				<tr>
                    					<th class="text-center">Prioridad: </th>
                    					<td class="text-center"> <?=$cedula['prioridad']; ?> </td>
                    				</tr>
                    				<tr>
                    					<th class="text-center">Estado del expediente: </th>
                    					<td class="text-center"> <?=$cedula['n_estado']; ?> </td>
                    				</tr>
                    				
                    			</tbody>
                    		</table>
                    	</div>
                    	<div class="col-md-3"></div>
                    	
                    </div>
                    <div class="row">
                    	<div class="col-md-3"></div>
                    	<div class="col-md-6">
                    		<table class="table table-hover table-bordered">
                    			<caption class="bg-gray text-center">
                    				DATOS DEL QUEJOSO
                    			</caption>
                    			<thead>
                    				<tr>
                    					<th class="text-center">NOMBRE COMPLETO</th>
                    					<th class="text-center">NÚMERO DE TELÉFONO</th>
                    					<th class="text-center">MEDIOS DE LOCALIZACIÓN</th>
                    					<th class="text-center">DIRECCIÓN</th>
                    				</tr>
                    			</thead>
                    			<tbody>
                    			<?php if ( count($cedula['quejosos']) ): ?>
                    				<?php foreach ($cedula['quejosos'] as $key => $quejoso): ?>
                    				<tr>
                    					<td><?=$quejoso->nombreCompleto?></td>
                    					<td><?=$quejoso->telefono?></td>
                    					<td><?=$quejoso->medios_de_localizacion_del_quejoso?></td>
                    					<td><?=$quejoso->quejoso_direccion?></td>
                    				</tr>
                    				<?php endforeach ?>
                    			<?php else: ?>
                    				<tr class="text-center">
                    					<td colspan="4">SIN QUEJOSOS</td>
                    				</tr>
                    			<?php endif ?>
                    			</tbody>
                    		</table>
                    	</div>
                    	<div class="col-md-3"></div>
                    </div>
                    <div class="row">
                    	<div class="col-md-3"></div>
                    	<div class="col-md-6">
                    		<table class="table table-hover table-bordered">
                    			<caption class="bg-gray text-center">
                    				DATOS DEL PRESUNTO RESPONSABLE
                    			</caption>
                    			<thead>
                    				<tr>
                    					<th class="text-center">NOMBRE COMPLETO</th>
                    					<th class="text-center">PROCEDENCIA</th>
                    					<th class="text-center">MUNICIPIO</th>
                    					<th class="text-center">CARGO</th>
                    				</tr>
                    			</thead>
                    			<tbody>
                    				<?php if ( count($cedula['p_responsables']) ): ?>
                    					<?php foreach ($cedula['p_responsables'] as $key => $quejoso): ?>
                    					<tr>
                    						<td><?=$quejoso->nombreCompleto?></td>
                    						<td><?=$quejoso->procedencia?></td>
                    						<td><?=$quejoso->municipio?></td>
                    						<td><?=$quejoso->cargo?></td>
                    					</tr>
                    					<?php endforeach ?>
                    				<?php else: ?>
                    					<tr class="text-center">
                    						<td colspan="4">SIN PRESUNTO RESPONSABLE</td>
                    					</tr>
                    				<?php endif ?>
                    			</tbody>
                    		</table>
                    	</div>
                    	<div class="col-md-3"></div>
                    </div>
                    <div class="row">
                    	<div class="col-md-3"></div>
                    	<div class="col-md-6">
                    		<table class="table table-hover table-bordered">
                    			<caption class="bg-gray text-center">
                    				DATOS DE LA UNIDAD
                    			</caption>
                    			<thead>
                    				<tr>
                    					<th class="text-center">PROCEDENCIA</th>
                    					<th class="text-center">NÚMERO ECONÓMICO</th>
                    					<th class="text-center">DESCRIPCIÓN</th>
                    				</tr>
                    			</thead>
                    			<tbody>
                    				<?php if ( count($cedula['unidades']) ): ?>
                    					<?php foreach ($cedula['unidades'] as $key => $quejoso): ?>
                    					<tr>
                    						<td><?=$quejoso->procedencia?></td>
                    						<td><?=$quejoso->numero_economico?></td>
                    						<td><?=$quejoso->descripcion?></td>
                    					</tr>
                    					<?php endforeach ?>
                    				<?php else: ?>
                    					<tr class="text-center">
                    						<td colspan="3">SIN UNIDADES REGISTRADAS</td>
                    					</tr>
                    				<?php endif ?>
                    			</tbody>
                    		</table>
                    	</div>
                    	<div class="col-md-3"></div>
                    </div>
                    <div class="row">
                    	<div class="col-md-3"></div>
                    	<div class="col-md-6">
                    		<table class="table table-hover table-bordered">
                    			<caption class="bg-gray text-center">
                    				ESTADO DEL EXPEDIENTE
                    			</caption>
                    			<thead>
                    				<tr>
                    					<th class="text-center">NOMBRE DEL SERVIDOR PÚBLICO RESPONSABLE</th>
                    					<th class="text-center">FECHA DEL TURNO</th>
                    					<th class="text-center">ESTADO</th>
                    					<th class="text-center">FECHA DE CIERRE</th>
                    					<th class="text-center">DESCRIPCIÓN</th>
                    				</tr>
                    			</thead>
                    			<tbody>
                    				<?php if ( count($cedula['turnos']) ): ?>
                    					<?php foreach ($cedula['turnos'] as $key => $turno): ?>
                    					<tr>
                    						<td><?=$turno->nom_completo?></td>
                    						<td><?=$turno->Fecha_turnado_a?></td>
                    						<td><?=$turno->estado_guarda?></td>
                    						<td><?=$turno->fecha_cierre_turno?></td>
                    						<td><?=$turno->observaciones?></td>
                    					</tr>
                    					<?php endforeach ?>
                    				<?php else: ?>
                    					<tr class="text-center">
                    						<td colspan="5">SIN ASIGANCIONES REGISTRADAS</td>
                    					</tr>
                    				<?php endif ?>
                    			</tbody>
                    		</table>
                    	</div>
                    	<div class="col-md-3"></div>
                    </div>
                    <!-- <div class="row">
                    	<div class="col-md-3"></div>
                    	<div class="col-md-6">
                    		<table class="table table-hover table-bordered">
                    			<caption class="bg-gray text-center">
                    				DATOS ADICIONALES DEL TURNADO
                    			</caption>
                    			<thead>
                    				<tr>
                    					<th class="text-center">NOMBRE</th>
                    					<th class="text-center">FECHA DEL TURNO</th>
                    					<th class="text-center">DEPENDENCIA</th>
                    					<th class="text-center">FECHA DE CIERRE</th>
                    					<th class="text-center">DESCRIPCIÓN</th>
                    				</tr>
                    			</thead>
                    			<tbody>
                    				<?php if ( count($cedula['extra']) ): ?>
                    					<?php foreach ($cedula['extra'] as $key => $turno): ?>
                    					<tr>
                    						<td><?=$turno->nomCompleto?></td>
                    						<td><?=$turno->Fecha_turnado_a?></td>
                    						<td><?=$turno->estado_guarda?></td>
                    						<td><?=$turno->fecha_cierre_turno?></td>
                    						<td><?=$turno->observaciones?></td>
                    					</tr>
                    					<?php endforeach ?>
                    				<?php else: ?>
                    					<tr class="text-center">
                    						<td colspan="5">SIN DATOS ADICIONALES</td>
                    					</tr>
                    				<?php endif ?>
                    			</tbody>
                    		</table>
                    	</div>
                    	<div class="col-md-3"></div>
                    </div> -->
                    <div class="row">
                    	<div class="col-md-3"></div>
                    	<div class="col-md-6">
                    		<table class="table table-hover table-bordered">
                    			<caption class="bg-gray text-center">
                    				ACTUACIONES
                    			</caption>
                    			<thead>
                    				<tr>
                    					<th class="text-center">NÚMERO DE OFICIO</th>
                    					<th class="text-center">FECHA DEL OFICIO</th>
                    					<th class="text-center">INSTITUCIÓN DESTINO</th>
                    					<th class="text-center">DESCRIPCIÓN</th>
                    				</tr>
                    			</thead>
                    			<tbody>
                    				<?php if ( count($cedula['oficios']) ): ?>
                    					<?php foreach ($cedula['oficios'] as $key => $turno): ?>
                    					<tr>
                    						<td><?=$turno->no_oficio?></td>
                    						<td><?=$turno->fecha_oficio?></td>
                    						<td><?=$turno->remitido_a?></td>
                    						<td><?=$turno->asunto?></td>
                    					</tr>
                    					<?php endforeach ?>
                    				<?php else: ?>
                    					<tr class="text-center">
                    						<td colspan="5">SIN OFICIOS ENCONTRADOS</td>
                    					</tr>
                    				<?php endif ?>
                    			</tbody>
                    		</table>
                    	</div>
                    	<div class="col-md-3"></div>
                    </div>
                    <!-- <div class="row">
                    	<div class="col-md-3"></div>
                    	<div class="col-md-6">
                    		<table class="table table-hover table-bordered">
                    			<caption class="bg-gray text-center">
                    				RESPUESTAS
                    			</caption>
                    			<tr>
                    				<th></th>
                    			</tr>
                    		</table>
                    	</div>
                    	<div class="col-md-3"></div>
                    </div> -->
                    
                </div>
            </div>
        </div>
    </div>
</div>