<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';

    $input_id_frm = 'id="frm_add_recordatorio"';
    $id = $_GET['censo'];
    $s = new SiraModel;
    $data = $s->getArea($id);


?>
<!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">                         
                                Formulario de recordatorio                           
                            <br>
                            <small>(<label>NOTA: </label>Campos obligatorios "<i class="fa fa-asterisk text-red"></i>" )</small>
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div id="div_alert"></div>
                        <form <?=$input_id_frm?> method="post" action="#">                      
                                
                                <input type="hidden" id="option" name="option" value="157">
                                <input type="hidden" name="censo_id" value="<?=$_GET['censo']?>">
                                
                            
                            <!-- Elegir el tipo de envío para el registro del censo -->
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="pull-right">Elija el tipo de vía por la que se envía el recordatorio<i class="fa fa-asterisk text-red"></i></label>
                                </div>
                                
                                <div class="col-md-3">
                                    <select id="question_r" name="question_r" class="form-control" required>
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
                                        <input type="date" id="f_envio_r" name="f_envio_r" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha de acuse/recepción <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="date" id="f_acuse_r" name="f_acuse_r" class="form-control">
                                    </div>
                                </div>
                            </div>

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
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Destinatario del correo <i class="fa fa-asterisk text-red"></i></label>
                                            <input type="text" id="destinatario_r" name="destinatario_r" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Asunto <i class="fa fa-asterisk text-red"></i></label>
                                            <input type="text" id="asunto_r" name="asunto_r" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha límite para respuestas (Recuerde seleccionar 3 días hábiles) <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="date" id="f_limite" name="f_limite" class="form-control">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Observaciones </label>
                                        <textarea name="observa_r" id="observa_r" class="form-control" style="resize: vertical;max-height: 300px;" ></textarea>
                                    </div>
                                </div>
                            </div>                          
                            
                            
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