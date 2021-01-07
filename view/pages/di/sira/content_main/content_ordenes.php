<section class="content container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <form action="#" id="getDashboard_OT" method="post">
                        <input type="hidden" id="option" name="option" value="160">
                        <div class="row">
                            <div class="col-md-6 text-right">
                                <label>Selecciona un año para consultar el contador de Órdenes de Trabajo:</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="year" id="year" class="form-control">
                                        <option value=""></option>
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success btn-flat">
                                    <i class="fa fa-bar-chart"></i> Consultar
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-danger" role="link" onclick="window.location='index.php?menu=ordenes'">Limpiar </button>
                            </div>
                        </div>
                    </form>
                        <br><br>                                        

                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">INSPECCIONES</span>
                                        <span class="info-box-number" id="c_ins">0</span>
                                    </div>
                                </div>
                            </div>        
                            <div class="col-md-3 col-xs-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">SUPERVISIONES</span>
                                        <span class="info-box-number" id="c_sup">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">INVESTIGACIONES</span>
                                        <span class="info-box-number" id="c_inv">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">VERIFICACIONES</span>
                                        <span class="info-box-number" id="c_ver">0</span>
                                    </div>
                                </div>
                            </div>                            
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">                 
                                    <div class = "box box-solid collapsed-box ">
                                        <div class="box-header with-border">
                                            <h3 class = "box-title"> Referencia de colores para el Listado de Órdenes de Trabajo </h3>
                                            <div class = "box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                                <i class="fa fa-plus"></i></button>
                                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div style = "display: none;" class = "cuerpo-caja">
                                            Clase de caja: <code> .box.box-solid </code>
                                            <p> bla bla </p>
                                        </div> 
                                   
                                        <div class="box-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="50px" class="bg-gray">VIGENTE</th>
                                                    <td width="50px" class="bg-green">13 días naturales</td>
                                                        
                                                    <th width="50px" class="bg-gray">CON RECORDATORIO GENERADO</th>
                                                    <td width="50px" class="bg-blue">2 días hábiles</td>
                                                </tr>
                                                <tr>
                                                    <th width="50px" class="bg-gray">POR VENCER</th>
                                                    <td width="50px" class="bg-yellow">a 2 días de vencer</td>

                                                    <th width="50px" class="bg-gray">CON RECORDATORIO POR VENCER</th>
                                                    <td width="50px" class="bg-aqua">a 1 día de vencer</td>
                                                </tr>
                                                <tr>
                                                    <th width="50px" class="bg-gray">VENCIDO</th>
                                                    <td width="50px" class="bg-red-active">más de 15 días naturales</td>

                                                    <th width="50px" class="bg-gray">CON RECORDATORIO VENCIDO</th>
                                                    <td width="50px" class="bg-maroon">más de 3 días hábiles</td>
                                                </tr>
                                                <tr>
                                                    <th width="50px" class="bg-gray">CON RESPUESTA</th>
                                                    <td width="50px" class="bg-purple"></td>
                                                    <!--<th width="50px" class="bg-gray">CON ACTA ADMINISTRATIVA -> SIN EXPEDIENTE</th>-->
                                                    <th width="50px" class="bg-gray">CON ACTA ADMINISTRATIVA</th>
                                                    <td width="50px" class="bg-teal"></td>
                                                </tr>
                                                <tr>
                                                    <th width="50px" class="bg-gray">ORDEN DE TRABAJO SIN RECOMENDACIONES</th>
                                                    <td width="50px" class="bg-white"></td>
                                                        
                                                    <!--<th width="50px" class="bg-gray">CON ACTA ADMINISTRATIVA -> CON EXPEDIENTE</th>-->
                                                    <th width="50px" class="bg-gray">EXPEDIENTE GENERADO A PARTIR DE LA OT</th>
                                                    <td width="50px" class="bg-olive"></td>
                                                </tr>
                                                <tr>
                                                    <th width="50px" class="bg-gray">ORDEN DE TRABAJO CANCELADA</th>
                                                    <td width="50px" class="bg-gray"></td>
                                                </tr>
                                                <tr>
                                                        
                                                </tr>                                
                                            </table>                   
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Listado de Órdenes de Trabajo</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="ordenes_trabajo" class="table-responsive"></div>
                                            </div>
                                        </div>                      
                                    </div>
                                </div>
                            </div>
                        </div>
                                       
                </div>
            </div>
        </div>
    </div>
</section>