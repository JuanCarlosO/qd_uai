<input type="hidden" id="nivel" name="nivel" value="<?=$_SESSION['nivel']?>">
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Listado de Expedientes</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-bordered table-condensed table-hover">
                                <tr>
                                    <th>Estado procesal</th>
                                    <th> Color </th>
                                </tr>
                                <tr>
                                    <td>TRÁMITE</td>
                                    <td class="bg-yellow"></td>
                                </tr>
                                <tr>
                                    <td>ENVIADO</td>
                                    <td class="bg-green"></td>
                                </tr>
                                <tr>
                                    <td>DEVUELTO</td>
                                    <td class="bg-gray"></td>
                                </tr>
                                <tr>
                                    <td>PARA ATENDER</td>
                                    <td class="bg-teal disabled"></td>
                                </tr>
                                
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="expedientes" class="table-responsive"></div>
                        </div>
                    </div>                    	
                </div>
            </div>
        </div>
    </div>
</section>
