<input type="hidden" id="nivel" name="nivel" value="<?=$_SESSION['nivel']?>">
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Generador de reportes</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <i class="fa fa-circle text-red"></i> <label> NO SANCIONADO</label>
                        </div>
                        <div class="col-md-2">
                            <i class="fa fa-circle text-green"></i> <label> SANCIONADO</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!--Expedientes de la subd. de lo contencioso -->
                            <div id="expedientes_sc" class="table-responsive"></div>
                        </div>
                    </div>                    	
                </div>
            </div>
        </div>
    </div>
</section>
