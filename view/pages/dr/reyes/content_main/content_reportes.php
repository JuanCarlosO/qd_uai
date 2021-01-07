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
                    <!-- <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table id="tbl_sc" class="table table-hover table-bordered">
                                    <caption class="bg-gray text-center">
                                        SUBDIRECCIÓN DE LO CONTENCIOSO.
                                    </caption>
                                    <thead>
                                        <tr class="bg-gray">
                                            <th class="text-center"> ESTADO </th>
                                            <th class="text-center"> CANTIDAD </th>
                                            <th class="text-center"> MOSTRAR </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div> -->
                    <form id="frm_reporte" action="#">
                        <input type="hidden" name="option" value="68">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Forma de busqueda:</label>
                                    <select class="form-control" name="f_buscar" id="f_buscar" required="">
                                        <option value="">...</option>
                                        <option value="1">Por datos de expediente</option>
                                        <option value="2">Por datos de resolución</option>
                                        <option value="3">Por datos de demanda</option>
                                        <option value="4">Por datos de resolución de demanda
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="campos_queja" class="hidden">
                            <fieldset>
                                <legend class="bg-gray"> <h3> <center>CAMPOS DE EXPEDIENTE</center> </h3> </legend>
                            </fieldset>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fecha de hechos (inicio)</label>
                                        <input type="date" id="f_ini" name="f_ini" value="" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fecha de hechos (final)</label>
                                        <input type="date" id="f_fin" name="f_fin" value="" placeholder=""  class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Estado guarda del expediente</label>
                                        <select name="estado" id="estado" class="form-control">
                                            <option value="">...</option>
                                            <option value="10">RESERVA</option>
                                            <option value="11">IMPROCEDENCIA</option>
                                            <option value="2">ARCHIVO</option>
                                            <option value="3">INCOMPETENCIA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="campos_res" class="hidden">
                            <fieldset>
                                <legend class="bg-gray"> <h3> <center>CAMPOS DE LA RESOLUCIÓN</center> </h3> </legend>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> Fecha resolución inicio </label>
                                            <input type="date" class="form-control" id="fi_res" name="fi_res" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fecha resolución final</label>
                                            <input type="date" class="form-control" id="ff_res" name="ff_res" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>¿Con sanción?</label>
                                            <select name="sancion" id="sancion" class="form-control">
                                                <option value="">...</option>
                                                <option value="1">SI</option>
                                                <option value="2">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div id="campos_dem" class="hidden">
                            <fieldset>
                                <legend class="bg-gray"> <h3> <center>CAMPOS DE LA DEMANDA</center> </h3> </legend>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fecha inicio</label>
                                            <input type="date" id="fi_dem" name="fi_dem" value="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fecha fin</label>
                                            <input type="date" id="ff_dem" name="ff_dem" value="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tipo de demanda</label>
                                            <select name="t_demanda" id="t_demanda" class="form-control">
                                                <option value="">...</option>
                                                <option value="1">IMPUGNACIÓN SALA REGIONAL</option>
                                                <option value="2">IMPUGNACIÓN SALA SUPERIOR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Resultado de demanda</label>
                                            <select name="r_dem" id="r_dem" class="form-control">
                                                <option value="">...</option>
                                                <option value="1">VALIDEZ</option>
                                                <option value="2">INVALIDEZ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div id="campos_rdem" class="hidden">
                            <fieldset>
                                <legend class="bg-gray"> <h3> <center>CAMPOS DE RESOLUCIÓN DE DEMANDAS DE EXPEDIENTES</center> </h3> </legend>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fecha de resolución inicial</label>
                                            <input type="date" class="form-control" id="fi_rde" name="fi_rde" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fecha de resolución final</label>
                                            <input type="date" class="form-control" id="ff_rde" name="ff_rde" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Estado de la resolución</label>
                                            <select name="edo_res" id="edo_res" class="form-control">
                                                <option value="">...</option>
                                                <option value="1">SATISFACTORIA</option>
                                                <option value="2">INSATISFACTORIA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <button class="btn btn-flat btn-success center-block btn-block">
                                    Buscar  <i class="fa fa-search"></i>
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
                                            <th>Número del expediente</th>
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
