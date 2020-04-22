<input type="hidden" id="nivel" name="nivel" value="<?=$_SESSION['nivel']?>">
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Reporte</h3>
                </div>
                <div class="box-body">
                    <div id="div_reportes"></div>
                    <form id="frm_reporte" action="#">
                        <input type="hidden" name="option" value="68">
                        <h5 class="text-red">El rango de fechas seleccionado busca por fecha de hechos</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha inicio</label>
                                    <input type="date" id="f_ini" name="f_ini" value="" placeholder="" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha inicio</label>
                                    <input type="date" id="f_fin" name="f_fin" value="" placeholder="" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estado guarda del expediente</label>
                                    <select name="estado" id="estado" class="form-control" required></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <button class="btn btn-flat btn-success center-block">
                                    <i class="fa fa-search"></i> Buscar expedientes
                                </button>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </form>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tbl_reporte" class="table table-hover">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th>#</th>
                                            <th>Clave</th>
                                            <th>Oficio</th>
                                            <th>Estado guarda</th>
                                            <th>Edo. procesal</th>
                                            <th width="50px">Ver cédula</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_reporte"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>                    	
                </div>
            </div>
        </div>
    </div>
</section>
