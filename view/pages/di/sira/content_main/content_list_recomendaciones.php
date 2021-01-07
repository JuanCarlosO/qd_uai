<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';

    $input_id_frm = 'id="frm_add_respuesta_irr"';


?>
<!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">                         
                                Listado de irregularidades y recomendaciones                       
                            <br>
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div_alert"></div>
                        <form <?=$input_id_frm?> method="post" action="#">                      
                                <input type="hidden" id="option" name="option" value="">
                                <input type="hidden" id="ot_id" name="ot_id" value="<?=$_GET['ot']?>">
                            

                                <br>
                            
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-success btn-flat btn-block" data-toggle="modal" data-target="#modal_estatus">
                                            <i class="fa fa-plus"></i> Estatus general
                                        </button>
                                    </div>
                                </div>

                                <div class="box">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                 <div id="irr_recomendaciones"></div>
                                            </div>
                                        </div>  
                                    </div>
                                </div> 
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>