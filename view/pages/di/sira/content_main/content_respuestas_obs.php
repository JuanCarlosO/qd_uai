<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';

    $input_id_frm = 'id="frm_add_respuesta_irr"';
    $ot = $_GET['ot'];
    $a = new SiraModel;
    $cedula = $a->getCedulaOT($ot);


?>
<!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">                         
                                Seguimiento de irregularidades y recomendaciones para la OT: <b><?=$cedula[0]['clave'];?></b>                          
                            <br>
                            <small>(<label>NOTA: </label>Campos obligatorios "<i class="fa fa-asterisk text-red"></i>" )</small>
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div_alert"></div>
                        <form <?=$input_id_frm?> method="post" action="#">                      
                                <input type="hidden" id="option" name="option" value="127">
                                <input type="hidden" id="ot_id" name="ot_id" value="<?=$_GET['ot']?>">
                            
                            <!-- Elegir el tipo de envío para el registro del censo -->
                           
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha de recepción <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="date" id="f_recepcion" name="f_recepcion" class="form-control" required >
                                    </div>
                                </div>
                            </div>

                            <div class="row">                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Número de oficio <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="text" id="oficio" name="oficio" class="form-control" required >
                                        <input type="hidden" id="oficio_id" name="oficio_id" value="">
                                    </div>
                                </div>
                            </div>                                


                            <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Remitente <i class="fa fa-asterisk text-red"></i></label>
                                            <input type="text" id="remitente" name="remitente" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Cargo del remitente <i class="fa fa-asterisk text-red"></i></label>
                                            <input type="text" id="cargo" name="cargo" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Asunto <i class="fa fa-asterisk text-red"></i></label>
                                            <input type="text" id="asunto" name="asunto" class="form-control" required>
                                        </div>
                                    </div>
                                </div> 

                                <div class="row">
                                    <div class="col-md-5">
                                        <label>Personal que recibe <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="text" id="sp" name="sp" value="" placeholder="Ej: Armando Jimenez" required class="form-control">
                                        <input type="hidden" id="sp_id" name="sp_id" value="">
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                          <label>Área del personal que recibe</label>
                                          <input type="text" class="form-control" id="area_recibe" name="area_recibe" readonly>
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

                             <br> 
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

