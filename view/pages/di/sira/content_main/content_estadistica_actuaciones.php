
<section class="content container-fluid">
    <fieldset>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Gráfica de actuaciones</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form action="#" id="frm_estadistic_actuaciones" method="post">
                            <input type="hidden" id="option" name="option" value="139">
                            <div class="row">
                                <div class="col-md-2 text-right">
                                    <label>Selecciona un periodo a buscar:</label>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Fecha desde <i class="fa fa-asterisk text-red"></i></label>
                                            <input type="date" id="f_inicio" name="f_inicio" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Fecha hasta <i class="fa fa-asterisk text-red"></i></label>
                                            <input type="date" id="f_fin" name="f_fin" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-success btn-flat">
                                            <i class="fa fa-bar-chart"></i> Generar
                                        </button>
                                      <button class="btn btn-danger pull-right" role="link" onclick="window.location='index.php?menu=estadistica_actuaciones'">Limpiar </button>
                                    </div>
                                </div>
                                
                            </div>
                        </form>
                        <canvas id="columnchart_actuaciones"></canvas>                    
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Información General de las actuaciones en el periodo seleccionado</h3>
        
                    </div>

                    <table id="detalle_grafico_actuaciones" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Tipo de actuación  </th>
                                <th>Fecha</th>
                                <th>Clave</th>
                            </tr>
                        </thead>
                        <tbody>                         
                        </tbody>
                    </table>
                    </div>
                 </div>
            </div>
        </div>
</section>
<!-- /.content -->