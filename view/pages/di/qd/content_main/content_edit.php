<?php

require_once 'model/Connection.php';
require_once 'model/QDModel.php';
$queja_id = $_GET['queja'];
$q = new QDModel;
$r = $q->getQDOnly($queja_id);

#Cargar los catalogos y verificar cual se selecciono
#Tipos de referencia
$tr = json_decode($q->getTR());
#Tipos de tramite 
$tt = json_decode($q->getTT());
#Estados en los que se guarda el expediente.
$estados = json_decode($q->getEstadosGuarda());
#Municipios 
$municipios = json_decode($q->getMunicipios());
#Listado de procedencias
$procedencias = json_decode($q->getProcedencias());
#vias de recepcion
$vias = json_decode($q->getVias());

?>
<!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Formulario de Edición de Queja o Denuncia</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div_alert"></div>
                    	<form id="frm_edit_queja" method="post" action="#">
                    		<input type="hidden" id="option" name="option" value="20">
                    		<input type="hidden" id="queja_id" name="queja_id" value="<?=$r[0]['id']?>">
                    		<div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tipo de asunto</label>
                                        <select id="t_asunto" name="t_asunto" class="form-control" required autofocus disabled="">
                                        	<?php if ( $r[0]['t_asunto'] == 'POLICIAL' ): ?>
                                        	<option value="">...</option>
                                            <option value="1" selected>POLICIAL</option>
                                            <option value="2">NO POLICIAL</option>
                                        	<?php else: ?>
                                        	<option value="">...</option>
                                            <option value="1">POLICIAL</option>
                                            <option value="2" selected>NO POLICIAL</option>
                                        	<?php endif ?>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <fieldset>
                                <legend>DATOS DE LA REFERENCIA</legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tipo de referencia</label>
                                            
                                            <div class="input-group input-group-md">
                                                <select id="t_ref" name="t_ref" class="form-control">
                                                    <option value="">...</option>
                                                    <?php 
                                                    if( empty($r[0]['ref_id']) ){
                                                    	for ($i = 0; $i<count($tr); $i++){
                                                    		echo '<option value="'.$tr[$i]->id.'">'.$tr[$i]->nombre.'</option>';
                                                    	} 
                                                    }else{
                                                    	for ($i = 0; $i<count($tr); $i++){
                                                    		if($r[0]['ref_id'] == $tr[$i]->id){
                                                    			echo '<option value="'.$tr[$i]->id.'" selected>'.$tr[$i]->nombre.'</option>';
                                                    		}else{
                                                    			echo '<option value="'.$tr[$i]->id.'">'.$tr[$i]->nombre.'</option>';
                                                    		}
                                                    		
                                                    	}
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="elements_tr" class="">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Clave</label>
                                                <input type="text" id="cve_ref" name="cve_ref" value="<?=$r[0]['cve_ref']?>" placeholder="Escriba la clave de referencia" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Número de turno</label>
                                                <input type="text" id="n_turno" name="n_turno" value="<?=$r[0]['n_turno']?>" placeholder="Escriba el número de turno" class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>DATOS DEL EXPEDIENTE</legend>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Prioridad</label>
                                            <select id="prioridad" name="prioridad" class="form-control" required>
                                            	<option value="">...</option>
                                            	<?php if (empty($r[0]['prioridad'])){
                                            		echo '<option value="1">Normal</option>';
                                            		echo '<option value="2">Urgente</option>';
                                            	}else{
                                            		if ( $r[0]['prioridad'] == 'NORMAL' ) {
                                            			echo '<option value="1" selected>Normal</option>';
                                            			echo '<option value="2">Urgente</option>';
                                            		}else{
                                            			echo '<option value="1">Normal</option>';
                                            			echo '<option value="2" selected>Urgente</option>';
                                            		}
                                            	}?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="estado">Estado guarda</label>
                                            <select id="estado" name="estado" class="form-control" required>
                                                <option value="">...</option>
                                                <?php 
                                                    if( empty($r[0]['estado']) ){
                                                    	for ($i = 0; $i<count($estados); $i++){
                                                    		echo '<option value="'.$estados[$i]->id.'">'.$estados[$i]->nombre.'</option>';
                                                    	} 
                                                    }else{
                                                    	for ($i = 0; $i<count($estados); $i++){
                                                    		if($r[0]['estado'] == $estados[$i]->id){
                                                    			echo '<option value="'.$estados[$i]->id.'" selected>'.$estados[$i]->nombre.'</option>';
                                                    		}else{
                                                    			echo '<option value="'.$estados[$i]->id.'">'.$estados[$i]->nombre.'</option>';
                                                    		}
                                                    		
                                                    	}
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="evidencia">Evidencia</label>
                                            <select id="evidencia" name="evidencia" class="form-control">
                                                <option value="">...</option>
                                                <?php
                                                $o = $r[0]['evidencia'];
                                                switch ($o) {
                                                	case 'CD/DVD':
                                                		echo '<option value="1" selected>CD/DVD</option>';
                                                		echo '<option value="2">MEMORIA USB</option>';
                                                		echo '<option value="3">FOTOGRAFÍAS</option>';
                                                		echo '<option value="4">DOCUMENTOS</option>';
                                                		break;
                                                	case 'USB':
                                                		
                                                		break;
                                                	case 'FOTOGRAFIAS':
                                                		echo '<option value="1" >CD/DVD</option>';
                                                		echo '<option value="2" >MEMORIA USB</option>';
                                                		echo '<option value="3" selected>FOTOGRAFÍAS</option>';
                                                		echo '<option value="4">DOCUMENTOS</option>';
                                                		break;
                                                	case 'DOCUMENTOS':
                                                		echo '<option value="1" >CD/DVD</option>';
                                                		echo '<option value="2" >MEMORIA USB</option>';
                                                		echo '<option value="3" >FOTOGRAFÍAS</option>';
                                                		echo '<option value="4" selected>DOCUMENTOS</option>';
                                                		break;
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="fojas">Número de fojas</label>
                                            <input type="number" name="fojas" value="<?=$r[0]['fojas']?>" placeholder="" class="form-control" autocomplete="off" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="procedencia">Procedencia</label>
                                            <select id="procedencia" name="procedencia" class="form-control">
                                                <option value="">...</option>
                                                <?php 
                                                if( empty($r[0]['procedencia']) ){
                                                	for ($i = 0; $i<count($procedencias); $i++){
                                                		echo '<option value="'.$procedencias[$i]->id.'">'.$procedencias[$i]->nombre.'</option>';
                                                	} 
                                                }else{
                                                	for ($i = 0; $i<count($procedencias); $i++){
                                                		if($r[0]['procedencia'] == $procedencias[$i]->nombre){
                                                			echo '<option value="'.$procedencias[$i]->id.'" selected>'.$procedencias[$i]->nombre.'</option>';
                                                		}else{
                                                			echo '<option value="'.$procedencias[$i]->id.'">'.$procedencias[$i]->nombre.'</option>';
                                                		}
                                                		
                                                	}
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Tipo de trámite</label>
                                            <input type="hidden" name="t_tra" value="<?=$r[0]['t_tramite']?>">
                                            <select id="" name="" class="form-control" required disabled>
                                                <option value="">...</option>
                                                <?php 
                                                if( empty($r[0]['t_tramite']) ){
                                                	for ($i = 0; $i<count($tt); $i++){
                                                		echo '<option value="'.$tt[$i]->id.'">'.$tt[$i]->nombre.'</option>';
                                                	} 
                                                }else{
                                                	for ($i = 0; $i<count($tt); $i++){
                                                		if($r[0]['t_tramite'] == $tt[$i]->id){
                                                			echo '<option value="'.$tt[$i]->id.'" selected>'.$tt[$i]->nombre.'</option>';
                                                		}else{
                                                			echo '<option value="'.$tt[$i]->id.'">'.$tt[$i]->nombre.'</option>';
                                                		}
                                                	}
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Clave del expediente</label>
                                            <input type="text" id="cve_exp" name="cve_exp" value="<?=$r[0]['cve_exp']?>" placeholder="Campo automático" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fecha de hechos</label>
                                            <input type="date" id="f_hechos" name="f_hechos" value="<?=$r[0]['f_hechos']?>" placeholder="Campo automático" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Hora de hechos</label>
                                            <input type="time" id="h_hechos" name="h_hechos" value="<?=$r[0]['h_hechos']?>" placeholder="Campo automático" class="form-control">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="genero">Género</label>
                                            <select id="genero" name="genero" class="form-control" required="">
                                                <option value="">...</option>
                                                <?php if ( empty($r[0]['genero']) ): ?>
                                                <option value="1">MASCULINO</option>
                                                <option value="2">FEMENINO</option>
                                                <?php else: ?>
                                                	<?php if ($r[0]['genero'] == 'MASCULINO'): ?>
                                                		<option value="1" selected>MASCULINO</option>
                                                		<option value="2">FEMENINO</option>
                                                	<?php endif ?>
                                                	<?php if ($r[0]['genero'] == 'FEMENINO'): ?>
                                                		<option value="1">MASCULINO</option>
                                                		<option value="2" selected>FEMENINO</option>
                                                	<?php endif ?>
                                                <?php endif ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="t_afecta">Tipo de afectado</label>
                                            <select id="t_afecta" name="t_afecta" class="form-control" required="">
                                                <option value="">...</option>
                                                <option value="1" <?php echo $val = ( $r[0]['t_afectado'] == 'QUEJOSO' ) ? 'selected' : '' ;?> >QUEJOSO</option>
                                                <option value="2" <?php echo $val = ( $r[0]['t_afectado'] == 'DENUNCIANTE' ) ? 'selected' : '' ;?>>DENUNCIANTE</option>
                                                <option value="3" <?php echo $val = ( $r[0]['t_afectado'] == 'VISTA' ) ? 'selected' : '' ;?>>VISTA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="categoria">Categoria</label>
                                            <select id="categoria" name="categoria" class="form-control" required="">
                                                <option value="">...</option>
                                                <option value="1" <?php echo $val = ( $r[0]['categoria'] == 'CIUDADANO' ) ? 'selected' : '' ;?>>CIUDADANO</option>
                                                <option value="2" <?php echo $val = ( $r[0]['categoria'] == 'SERVIDOR PÚBLICO' ) ? 'selected' : '' ;?>>SERVIDOR PÚBLICO</option>
                                                <option value="3" <?php echo $val = ( $r[0]['categoria'] == 'OTRO' ) ? 'selected' : '' ;?>>OTRO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" style="margin-top: 25px;">
                                            
                                            <big>
                                                <label style="text-decoration: underline black;" for="d_ano">¿ES DENUNCIA ANÓNIMA?</label>
                                                <big class="pull-right">
                                                    <label for="d_ano">SI</label> <input type="checkbox" id="d_ano" name="d_ano" value="1" style="font-size: 110%; display: inline;" <?php echo $val = ( $r[0]['d_ano'] == 'SI' ) ? 'checked' : '' ;?>>
                                                </big>
                                            </big>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="t_ley">Tipo de ley</label>
                                            <select id="t_ley" name="t_ley" class="form-control">
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="conductas">Presunta conducta</label>
                                            <select id="conductas" name="conductas[]" class="form-control select2" multiple="multiple" data-placeholder="Selecciona uno o más conductas">
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                	<div class="col-md-12">
                                		<h3> <u>LISTADO DE PRESUNTAS CONDUCTAS SELECCIONADAS PARA ESTE EXPEDIENTE</u> </h3>
                                		<?php foreach ($r[0]['conductas'] as $key => $conducta): ?>
                                			<p>
                                				<li id="presunta_<?=$conducta->id_presunta?>"><?=$conducta->nombre?> 
                                					<button type="button" title="Eliminar esta conducta." onclick="delete_conducta(<?=$conducta->id_presunta?>)" class="btn btn-danger btn-sm btn-flat" >
                                						<i class="fa fa-trash "></i>
                                					</button> 
                                				</li>
                                			</p>
                                				
                                			
                                		<?php endforeach ?>
                                		
                                	</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label>Vias de recepción</label>
                                            <select id="vias_r" name="vias_r[]" class="form-control select2"  multiple>
                                                <option value="">...</option>
                                                <?php
                                                foreach ($vias as $via) {
                                            		echo '<option value="'.$via->id.'">'.$via->nombre.'</option>';
                                            	}
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                	<div class="col-md-12">
                                		<?php foreach ($r[0]['vias'] as $key => $via): ?>
                                			<li id="via_<?=$via->id?>">
                                				<?=$via->via?>
                                				<button type="button" title="Eliminar esta via de recepción." onclick="delete_via(<?=$via->id?>)" class="btn btn-danger btn-sm btn-flat" >
                                					<i class="fa fa-trash "></i>
                                				</button> 	
                                			</li>
                                		<?php endforeach ?>
                                		
                                	</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="comentario">Comentario</label>
                                            <input type="text" id="comentario" name="comentario" value="<?=$r[0]['comentario']?>" class="form-control" placeholder="Este dato permite identificar más rápido el sentido del expediente" autocomplete="off" maxlength="255">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="descripcion">Descripción completa de los hechos </label>
                                            <textarea id="descripcion" name="descripcion" class="form-control" style="resize: vertical; max-height: 500px;" rows="5"><?=trim($r[0]['descripcion'])?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-success box-solid collapsed-box">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">AGREGAR DATOS DE AVERIGUACIÓN PREVIA</h3>
                                                <div class="box-tools pull-right">
                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Origen de la investigación</label>
                                                            <select id="origen" name="origen[]" class="form-control">
                                                                <option value="">...</option>
                                                                <option value="1">FGJEM</option>
                                                                <option value="2">TRIBUNAL</option>
                                                                <option value="3">CODHEM</option>
                                                                <option value="4">UAI</option>
                                                                <option value="5">SS</option>
                                                                <option value="6">MUNICIPAL</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Tipo de trámite</label>
                                                            <select id="tramite_prev" name="tramite_prev[]" class="form-control">
                                                                <option value="">...</option>
                                                                <option value="1">Acta Circunstanciada</option>
                                                                <option value="2">Averiguación Previa</option>
                                                                <option value="3">Noticia Criminal</option>
                                                                <option value="4">Carpeta de Investigación</option>
                                                                <option value="5">Causa Penal</option>
                                                                <option value="6">Expediente</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Clave</label>
                                                            <input type="text" name="clave_prev[]" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="otras_aver"></div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <button type="button" class="btn btn-success btn-flat btn-block" onclick="add_aver_prev();">
                                                            <i class="fa fa-plus"></i>
                                                         AGREGAR 
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                      </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>DATOS DEL LUGAR DE LOS HECHOS</legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="municipios">Municipio</label>
                                            <select id="municipios" name="municipios" class="form-control" required>
                                                <option value="">...</option>
                                                <?php 
                                                if( empty($r[0]['mun_id']) ){
                                                	for ($i = 0; $i<count($municipios); $i++){
                                                		echo '<option value="'.$municipios[$i]->id.'">'.$municipios[$i]->nombre.'</option>';
                                                	} 
                                                }else{
                                                	for ($i = 0; $i<count($municipios); $i++){
                                                		if($r[0]['mun_id'] == $municipios[$i]->id){
                                                			echo '<option value="'.$municipios[$i]->id.'" selected>'.$municipios[$i]->nombre.'</option>';
                                                		}else{
                                                			echo '<option value="'.$municipios[$i]->id.'">'.$municipios[$i]->nombre.'</option>';
                                                		}
                                                		
                                                	}
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="c_principal">Calle principal</label>
                                            <input type="text" id="c_principal" name="c_principal" value="<?=$r[0]['calle']?>" class="form-control" placeholder="Nombre de la calle principal" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="e_calle">Entre calle </label>
                                            <input type="text" id="e_calle" name="e_calle" value="<?=$r[0]['e_calle']?>" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="y_calle">Y calle</label>
                                            <input type="text" id="y_calle" name="y_calle" value="<?=$r[0]['y_calle']?>" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="edificacion">Edificación </label>
                                            <input type="text" id="edificacion" name="edificacion" value="<?=$r[0]['edificacion']?>" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="n_edificacion">Número</label>
                                            <input type="text" id="n_edificacion" name="n_edificacion" value="<?=$r[0]['numero']?>" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                </div>
                            </fieldset>
                            <!-- <fieldset>
                                <legend>DATOS DEL TURNADO</legend>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Turnar a</label>
                                        <input type="text" id="sp" name="sp" value="" placeholder="Ej: Armando Jimenez" requiredc class="form-control">
                                        <input type="hidden" id="sp_id" name="sp_id" value="">
                                    </div>
                                </div>
                                <div class="row">
                                	<div class="col-md-10">
                                		<p>
                                			<ul>
                                				<?php foreach ($r[0]['turnos'] as $key => $turno): ?>
                                					
                                						<li id="<?=$turno->id_turno?>"><?=$turno->nombre." ".$turno->ap_pat." ".$turno->ap_mat ?> 
                                							<button type="button" title="Eliminar este turno." onclick="delete_turno(<?=$turno->id_turno?>)" class="btn btn-danger btn-sm btn-flat" >
                                								<i class="fa fa-trash "></i>
                                							</button> 
                                						</li>
                                					
                                				<?php endforeach ?>
                                				
                                			</ul>
                                		</p>
                                	</div>
                                </div>
                            </fieldset> -->
                            <br>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-flat btn-success btn-block">
                                        <i class="fa fa-floppy-o"></i>
                                        Guardar información
                                    </button>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                    	</form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    