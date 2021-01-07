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
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="100px" class="bg-gray">Reserva</th>
                                    <td width="50px" class="bg-orange-active">Con  acuerdo</td>
                                    <td width="50px" class="bg-orange disabled"> Sin acuerdo</td>

                                    <th width="100px" class="bg-gray">Archivo</th>
                                    <td width="50px" class="bg-light-blue-active">Con  acuerdo</td>
                                    <td width="50px" class="bg-light-blue disabled"> Sin acuerdo</td>
                                </tr>
                                <tr>
                                    <th width="100px" class="bg-gray">Improcedencia</th>
                                    <td width="50px" class="bg-maroon-active">Con  acuerdo</td>
                                    <td width="50px" class="bg-maroon disabled"> Sin acuerdo</td>

                                    <th width="100px" class="bg-gray">Incompetencia</th>
                                    <td width="50px" class="bg-purple-active">Con  acuerdo</td>
                                    <td width="50px" class="bg-purple  disabled"> Sin acuerdo</td>
                                </tr>
                                <tr>
                                    <th width="100px" class="bg-gray">Enviados a la Comisión</th>
                                    <td class="bg-green-active" colspan="2"></td>

                                    <th width="100px" class="bg-gray">Para atender (Nuevos)</th>
                                    <td class="bg-teal disabled " colspan="2"></td>
                                </tr>
                                <tr>
                                    
                                </tr>
                                
                            </table>
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
