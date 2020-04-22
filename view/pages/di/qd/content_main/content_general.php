<?php
if ( $_SESSION['perfil'] == 'QDP' ) { $t_as = 1; }
if ( $_SESSION['perfil'] == 'QDNP' ) { $t_as = 2; }
?>
<!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Formulario de Alta de Queja o Denuncia</h3>
                        (<label>NOTA: </label>Los campos obligatorios se encuentran marcados con un asterisco "<i class="fa fa-asterisk text-red"></i>" )
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div_alert"></div>
                    	<form id="frm_add_queja" method="post" action="#">
                    		<input type="hidden" id="option" name="option" value="11">
                            
                    		<div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tipo de asunto <i class="fa fa-asterisk text-red"></i> </label>
                                        <select id="t_asunto" name="t_asunto" class="form-control" required autofocus>
                                            <option value="">...</option>
                                            <option value="1">POLICIAL</option>
                                            <option value="2">NO POLICIAL</option>
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
                                                </select>
                                                <span class="input-group-btn">
                                                  <button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#modal_add_referencia">
                                                      <i class="fa fa-plus"></i>
                                                  </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="elements_tr" class="hidden">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Clave</label>
                                                <input type="text" id="cve_ref" name="cve_ref" value="" placeholder="Escriba la clave de referencia" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Número de turno</label>
                                                <input type="text" id="n_turno" name="n_turno" value="" placeholder="Escriba el número de turno" class="form-control" autocomplete="off">
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
                                            <label>Prioridad <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="prioridad" name="prioridad" class="form-control" required>
                                                <option value="1" selected>Normal</option>
                                                <option value="2">Urgente</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="estado">Estado guarda <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="estado" name="estado" class="form-control" required>
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="evidencia">Evidencia</label>
                                            <select id="evidencia" name="evidencia" class="form-control">
                                                <option value="">...</option>
                                                <option value="1">CD/DVD</option>
                                                <option value="2">MEMORIA USB</option>
                                                <option value="3">FOTOGRAFÍAS</option>
                                                <option value="4">DOCUMENTOS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="fojas">Número de fojas</label>
                                            <input type="number" name="fojas" value="0" placeholder="" class="form-control" autocomplete="off" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="procedencia">Procedencia</label>
                                            <select id="procedencia" name="procedencia" class="form-control">
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Tipo de trámite <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="t_tra" name="t_tra" class="form-control" required>
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Clave del expediente</label>
                                            <input type="text" id="cve_exp" name="cve_exp" value="" placeholder="Campo automático" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fecha de hechos</label>
                                            <input type="date" id="f_hechos" name="f_hechos" value="" placeholder="Campo automático" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Hora de hechos</label>
                                            <input type="time" id="h_hechos" name="h_hechos" value="" placeholder="Campo automático" class="form-control">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="genero">Género <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="genero" name="genero" class="form-control" required>
                                                <option value="">...</option>
                                                <option value="1">MASCULINO</option>
                                                <option value="2">FEMENINO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="t_afecta">Tipo de afectado <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="t_afecta" name="t_afecta" class="form-control" required>
                                                <option value="">...</option>
                                                <option value="1">QUEJOSO</option>
                                                <option value="2">DENUNCIANTE</option>
                                                <option value="3">VISTA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="categoria">Categoria <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="categoria" name="categoria" class="form-control" required>
                                                <option value="">...</option>
                                                <option value="1">CIUDADANO</option>
                                                <option value="2">SERVIDOR PÚBLICO</option>
                                                <option value="3">OTRO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" style="margin-top: 25px;">
                                            
                                            <big>
                                                <label style="text-decoration: underline black;" for="d_ano">¿ES DENUNCIA ANÓNIMA?</label>
                                                <big class="pull-right">
                                                    <label for="d_ano">SI</label> <input type="checkbox" id="d_ano" name="d_ano" value="1" style="font-size: 110%; display: inline;">
                                                </big>
                                            </big>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="t_ley">Tipo de ley <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="t_ley" name="t_ley" class="form-control" required>
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="conductas">Presunta conducta <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="conductas" name="conductas[]" class="form-control select2" multiple="multiple" data-placeholder="Selecciona uno o más conductas" required>
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label>Via de recepción <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="vias_r" name="vias_r[]" class="form-control select2" required multiple>
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="comentario">Comentario</label>
                                            <input type="text" id="comentario" name="comentario" value="" class="form-control" placeholder="Este dato permite identificar más rápido el sentido del expediente" autocomplete="off" maxlength="255">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="descripcion">Descripción completa de los hechos </label>
                                            <textarea id="descripcion" name="descripcion" class="form-control" style="resize: vertical; max-height: 500px;" rows="5"></textarea>
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
                                            <label for="municipios">Municipio <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="municipios" name="municipios" class="form-control" required>
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="c_principal">Calle principal</label>
                                            <input type="text" id="c_principal" name="c_principal" class="form-control" placeholder="Nombre de la calle principal" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="e_calle">Entre calle </label>
                                            <input type="text" id="e_calle" name="e_calle" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="y_calle">Y calle</label>
                                            <input type="text" id="y_calle" name="y_calle" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="edificacion">Edificación </label>
                                            <input type="text" id="edificacion" name="edificacion" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="n_edificacion">Número</label>
                                            <input type="text" id="n_edificacion" name="n_edificacion" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Multiple de admisión</legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>¿Desea agregar varios presuntos responsables?</label>
                                            <select name="pregunta" id="pregunta" class="form-control" required>
                                                <option value="">...</option>
                                                <option value="2">SI</option>
                                                <option value="1">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="cant_person" class="hidden">
                                        <div class="col-md-4">
                                            <label>¿Cuantas personas desea agregar?</label>
                                            <div class="input-group">
                                                <input type="number" id="cantidad" name="cantidad" value="" class="form-control" min="1" max="30" >
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-success btn-flat" onclick="addPresuntos();" > 
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="mult_presuntos" class="hidden">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="box box-success box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Sección de presuntos responsables</h3>
                                            </div>
                                            <div class="box-body">
                                                <div id="formularios"></div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>DATOS DEL TURNADO</legend>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Turnar a <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="text" id="sp" name="sp" value="" placeholder="Ej: Armando Jimenez" required class="form-control">
                                        <input type="hidden" id="sp_id" name="sp_id" value="">
                                    </div>
                                </div>
                                
                            </fieldset>
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
    
    