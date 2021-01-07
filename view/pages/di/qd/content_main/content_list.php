<!-- Main content -->
<input type="hidden" id="nivel" name="nivel" value="<?=$_SESSION['nivel']?>">
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Listado de Quejas y Denuncias</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderd table-hover">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th>INVESTIGAR</th>
                                            <th>COMPLEMENTO DE INVESTIGACIÓN</th>
                                            <th>DETERMINACIÓN</th>
                                            <th>CRÍTICO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td class="bg-green"> 0 a 70 días</td>
                                            <td class="bg-yellow"> 71 a 88 </td>
                                            <td class="bg-red-active"> 89 a 90 </td>
                                            <td class="bg-purple"> Mayor a 90</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="box box-default box-solid collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Simbología y su significado</h3>

                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-condesed">
                                                <tr class="bg-gray">
                                                    <th class="text-center">Significado</th>
                                                    <th class="text-center">Icono</th>
                                                </tr>
                                                <tr class="text-center">
                                                    <td>DÍA 1 AL 456</td>
                                                    <td>
                                                        <i class="fa fa-smile-o" style="font-size: 25px;"></i>
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td>DÍA 457 AL 639</td>
                                                    <td>
                                                        <i class="fa  fa-exclamation-triangle" style="font-size: 25px;"></i>
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td>DÍA 640 AL 822</td>
                                                    <td>
                                                        <i class="fa  fa-ban" style="font-size: 25px;"></i>
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td>DÍA 823 AL 1095</td>
                                                    <td>
                                                        <i class="fa fa-thumbs-o-down" style="font-size: 25px;"></i>
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td>EN ARCHIVO</td>
                                                    <td>
                                                        <i class="fa fa-archive" style="font-size: 25px;"></i>
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td>EN RESPONSABILIDADES</td>
                                                    <td>
                                                        <i class="fa fa-balance-scale" style="font-size: 25px;"></i>
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td>PRESCRITO</td>
                                                    <td>
                                                        <i class="fa fa-file-o" style="font-size: 25px;"></i>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="lista_qd" class="table-responsive"></div>
                            </div>
                        </div>                    	
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    