<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';
?>
<!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">                         
                                Resumen de preguntas                           
                            <br>
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div_alert"></div>
                     
                                <input type="hidden" id="option" name="option" value="">
                                <input type="hidden" id="censo_id" name="censo_id" value="<?=$_GET['censo']?>">
                            
                                <br>
                            
                                <div class="box">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                 <div id="preguntas_resumen"></div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>                           
                   
                    </div>
                </div>
            </div>
        </div>
    </section>