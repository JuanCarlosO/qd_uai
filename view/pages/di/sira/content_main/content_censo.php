<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';

if ( isset($_GET['censo']) ) {
    $censo = $_GET['censo'];    
    $input_id_frm = 'id="frm_add_censo"';
    
} else{

    $input_id_frm = 'id="frm_add_censo"';
}

?>
<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">                         
                        Formulario de registro de censo                            
                        <br>
                        <small>(<label>NOTA: </label>Campos obligatorios "<i class="fa fa-asterisk text-red"></i>" )</small>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="div_alert"></div>
                    <form <?=$input_id_frm?> method="post" action="#">
                        <?php if ( isset($_GET['censo']) ): ?>
                            <input type="hidden" id="option" name="option" value="145">
                            <input type="hidden" id="censo_id" name="censo_id" value="<?=$censo?>">
                            <?php else: ?>
                                <input type="hidden" id="option" name="option" value="156">
                            <?php endif ?>              
                            
                            
                            <!-- Elegir el tipo de envío para el registro del censo -->

                            <div class="row">
                                <div class="col-md-12">
                                    <label>Elija el tipo de vía por la que se envió el censo<i class="fa fa-asterisk text-red"></i></label>
                                </div>
                                <div class="col-md-3">
                                    <select id="question" name="question" class="form-control" required >
                                        <option value="">...</option>
                                        <option value="1">Oficio</option>
                                        <option value="2">Correo</option>
                                    </select>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha del oficio/correo <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="date" id="f_envio" name="f_envio" class="form-control" required >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha de acuse/recepción <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="date" id="f_acuse" name="f_acuse" class="form-control" required >
                                    </div>
                                </div>

                            </div>


                            <br>

                            <div id="censo_oficio" class="hidden">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Número de oficio <i class="fa fa-asterisk text-red"></i></label>
                                            <input type="text" id="oficio" name="oficio" class="form-control" >
                                            <input type="hidden" id="oficio_id" name="oficio_id" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                          <label>Destinatario</label>
                                          <input type="text" class="form-control" id="destinatario_ofi" name="destinatario_ofi" readonly>
                                      </div>
                                  </div>
                              </div>

                              <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                      <label>Cargo del destinatario</label>
                                      <input type="text" class="form-control" id="cargo_remi" name="cargo_remi" readonly>
                                  </div>
                              </div>
                          </div>


                          <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                  <label>Asunto</label>
                                  <input type="text" class="form-control" id="asunto_ofi" name="asunto_ofi" readonly>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div id="censo_correo" class="hidden">                        
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Destinatario del correo <i class="fa fa-asterisk text-red"></i></label>
                                <input type="text" id="destinatario" name="destinatario" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Asunto <i class="fa fa-asterisk text-red"></i></label>
                                <input type="text" id="asunto" name="asunto" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-12">
                        <label>Procedencia<i class="fa fa-asterisk text-red"></i></label>
                    </div>
                    <div class="col-md-5">
                        <select id="question_p" name="question_p" class="form-control" required >
                            <option value="">...</option>
                            <option value="1">CPRS</option>
                            <option value="2">Secretaría de Seguridad</option>
                        </select>
                    </div>                                
                </div>
                <div id="procedencia_ss" class="hidden">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Área<i class="fa fa-asterisk text-red"></i></label>
                        </div>
                        <div class="col-md-5">
                            <select id="question_a" name="question_a" class="form-control">
                                <option value="">...</option>
                                <option value="1">Operativos Secretaría de Seguridad</option>
                                <option value="2">Dirección de Policía de Tránsito</option>
                                <option value="4">Personal Administrativo</option>
                            </select>
                        </div>                                
                    </div>
                </div>

                <div id="procedencia_cprs" class="hidden">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Área<i class="fa fa-asterisk text-red"></i></label>
                        </div>
                        <div class="col-md-5">
                            <select id="question_a2" name="question_a2" class="form-control">
                                <option value="">...</option>
                                <option value="3">Dirección General de Prevención y Reinserción Social</option>
                            </select>
                        </div>                                
                    </div>
                </div>

                <br>

                <div id="area_operativos" class="hidden">
                    <div class="row">
                        <label for="coord" class="col-md-12 control-label">Coordinación </label>
                        <div class="col-sm-3">
                            <select class="form-control " name="coord" id="coord">
                                <option value="" selected>...</option>
                            </select>
                        </div>
                    </div>  

                    <div class="row">
                        <label for="subd" class="col-sm-12 control-label">Subdirección </label>
                        <div class="col-sm-3">
                            <select class="form-control" name="subd" id="subd">
                                <option value="" selected>...</option>
                            </select>
                        </div>
                    </div> 

                    <div class="row">
                        <label for="subd" class="col-sm-12 control-label">Región </label>
                        <div class="col-sm-3">
                            <select class="form-control" name="region" id="region">
                                <option value="" selected>...</option>
                            </select>
                        </div>
                    </div> 

                    <div class="row">
                        <label for="agrupamiento" class="col-sm-12 control-label">Agrupamiento </label>
                        <div class="col-sm-3">
                            <select class="form-control" name="agrupamiento" id="agrupamiento">
                                <option value="" selected>...</option>
                            </select>
                        </div>
                    </div>

                    <br>                               
                </div>


                <div id="area_transito" class="hidden">
                   <div class="row">
                    <label for="coord_t" class="col-md-12 control-label">Coordinación </label>
                    <div class="col-sm-3">
                        <select class="form-control " name="coord_t" id="coord_t">
                            <option value="" selected>...</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <label for="agrupamiento_t" class="col-sm-12 control-label">Agrupamiento </label>
                    <div class="col-sm-3">
                        <select class="form-control" name="agrupamiento_t" id="agrupamiento_t">
                            <option value="" selected>...</option>
                        </select>
                    </div>
                </div>

                <br> 

            </div>

            <div id="area_cprs" class="hidden">
                <div class="row">
                    <label for="agrupamiento_cprs" class="col-sm-12 control-label">Agrupamiento </label>
                    <div class="col-sm-3">
                        <select class="form-control" name="agrupamiento_cprs" id="agrupamiento_cprs">
                            <option value="" selected>...</option>
                        </select>
                    </div>
                </div>
                <br>                                 
            </div>

            <div id="area_admin" class="hidden">
                <div class="row">
                    <label for="niv1" class="col-md-12 control-label">Dirección </label>
                    <div class="col-sm-3">
                        <select class="form-control " name="niv1" id="niv1">
                            <option value="" selected>...</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <label for="niv2" class="col-md-12 control-label">Unidad </label>
                    <div class="col-sm-3">
                        <select class="form-control " name="niv2" id="niv2">
                            <option value="" selected>...</option>
                        </select>
                    </div>
                </div>  

                <div class="row">
                    <label for="niv3" class="col-sm-12 control-label">Dirección de Área </label>
                    <div class="col-sm-3">
                        <select class="form-control" name="niv3" id="niv3">
                            <option value="" selected>...</option>
                        </select>
                    </div>
                </div> 

                <div class="row">
                    <label for="niv4" class="col-sm-12 control-label">Subdirección </label>
                    <div class="col-sm-3">
                        <select class="form-control" name="niv4" id="niv4">
                            <option value="" selected>...</option>
                        </select>
                    </div>
                </div> 

                <div class="row">
                    <label for="niv5" class="col-sm-12 control-label">Departamento </label>
                    <div class="col-sm-3">
                        <select class="form-control" name="niv5" id="niv5">
                            <option value="" selected>...</option>
                        </select>
                    </div>
                </div>

                <br>                               
            </div>

            <br>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha límite para respuestas (Recuerde seleccionar 5 días hábiles) <i class="fa fa-asterisk text-red"></i></label>
                        <input type="date" id="f_limite" name="f_limite" class="form-control" required >
                    </div>
                </div>
            </div> 

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Observaciones </label>
                        <textarea name="observa" id="observa" class="form-control"  style="resize: vertical;max-height: 300px;" ></textarea>
                    </div>
                </div>
            </div>

            <?php if ( isset($_GET['censo']) ): ?>

                <?php else: ?>
                    <fieldset>
                        <legend>Formulario de preguntas</legend>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Seleccione una pregunta</label>
                                    <input type="text" id="pregunta" name="pregunta" value="" placeholder="Escriba y seleccione una pregunta de la lista" autocomplete="off" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>PREGUNTAS</label>
                                <ol id="censo_preguntas" title="PREGUNTAS">
                                    <?php
                                    if ( isset($_GET['censo']) ) {
                                        for ($i=0; $i < count( $r['pre'] ) ; $i++) {

                                            echo '<li id="'.$r['pre'][$i]->id.'">';
                                            echo '<input type="hidden" name="censo_preguntas[]" value="'.$r['pre'][$i]->id.'">';
                                            echo $r['pre'][$i]->pregunta;echo '</li>';                                                
                                        }
                                    }
                                    ?>
                                </ol>
                            </div>
                        </div>
                    </fieldset>
                    
                <?php endif ?> 

                <br>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-flat btn-success btn-block">
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