<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Gráfica de preguntas por agrupamiento</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <form action="#" id="frm_estadistic_censo" method="post">
                        <input type="hidden" id="option" name="option" value="136">
                       

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="n_pregunta">Pregunta: <i class="fa fa-asterisk text-red"></i></label>
                                        <select id="n_pregunta" name="n_pregunta" class="form-control" required>
                                            <option value="">...</option>   
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label>Procedencia<i class="fa fa-asterisk text-red"></i></label>
                                </div>
                                <div class="col-md-5">
                                    <select id="question_p" name="question_p" class="form-control" required >
                                        <option value="">...</option>
                                        <option value="1">CPRS</option>
                                        <option value="2">Secretaría de Seguridad</option>
                                    </select>
                                </div>                                
                            </div>
                            <div id="procedencia_ss" class="hidden">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Área<i class="fa fa-asterisk text-red"></i></label>
                                    </div>
                                    <div class="col-md-5">
                                        <select id="question_a" name="question_a" class="form-control">
                                            <option value="">...</option>
                                            <option value="1">Operativos Secretaría de Seguridad</option>
                                            <option value="2">Dirección de Policía de Tránsito</option>
                                            <option value="4">Personal Administrativo</option>
                                        </select>
                                    </div>                                
                                </div>
                            </div>

                            <div id="procedencia_cprs" class="hidden">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Área<i class="fa fa-asterisk text-red"></i></label>
                                    </div>
                                    <div class="col-md-5">
                                        <select id="question_a2" name="question_a2" class="form-control">
                                            <option value="">...</option>
                                            <option value="3">Dirección General de Prevención y Reinserción Social</option>
                                        </select>
                                    </div>                                
                                </div>
                            </div>

                            <br>

                            <div id="area_operativos" class="hidden">
                                <div class="row">
                                    <label for="agrupamiento" class="col-md-12 control-label">Agrupamiento </label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="agrupamiento" id="agrupamiento">
                                            <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div>                              
                            </div>

                            <div id="area_transito" class="hidden">
                                <div class="row">
                                    <label for="agrupamiento_t" class="col-sm-12 control-label">Agrupamiento </label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="agrupamiento_t" id="agrupamiento_t">
                                            <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div>                          
                            </div>

                            <div id="area_cprs" class="hidden">
                                <div class="row">
                                    <label for="agrupamiento_cprs" class="col-sm-12 control-label">Agrupamiento </label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="agrupamiento_cprs" id="agrupamiento_cprs">
                                            <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div>                               
                            </div>

                            <div id="area_admin" class="hidden">
                                <div class="row">
                                    <label for="niv5" class="col-md-12 control-label">Departamento </label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="niv5" id="niv5">
                                            <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div>                              
                            </div>

                            
                            <br>

                            <div class="col-md-5">
                                <button type="submit" class="btn btn-success btn-flat">
                                    <i class="fa fa-bar-chart"></i> Generar
                                </button>
                                <button class="btn btn-danger pull-right" role="link" onclick="window.location='index.php?menu=estadistica_censo'">Limpiar </button>
                            </div>
                       
                    </form>
                    <div id="columnchart_material"></div>                    
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->