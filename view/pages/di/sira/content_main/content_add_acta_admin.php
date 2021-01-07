<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';
   
    $input_id_frm = 'id="frm_acta_admin_censo"';
    $id = $_GET['censo'];
?>

<!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">                         
                                Formulario de registro de acta administrativa por censo                           
                            <br>
                            <small>(<label>NOTA: </label>Campos obligatorios "<i class="fa fa-asterisk text-red"></i>" )</small>
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div_alert"></div>
                        <form <?=$input_id_frm?> method="post" action="#">   
                                <input type="hidden" id="option" name="option" value="147">
                                <input type="hidden" name="censo_id" value="<?=$_GET['censo']?>">                         

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="date" id="fecha" name="fecha" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Hora <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="time" id="hora" name="hora" class="form-control">
                                    </div>
                                </div>
                               
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Motivo</label>
                                        <input type="text" class="form-control" id="motivo" name="motivo">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="t_ley">Tipo de ley <i class="fa fa-asterisk text-red"></i></label>
                                        <select id="t_ley" name="t_ley" class="form-control" required>
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="art">Número de artículo <i class="fa fa-asterisk text-red"></i></label>
                                        <select id="art" name="art" class="form-control">
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="art">Secciones disponibles <i class="fa fa-asterisk text-red"></i></label>
                                        <select id="secciones" name="secciones" class="form-control">
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="art">Fracciones disponibles <i class="fa fa-asterisk text-red"></i></label>
                                        <select id="fracciones" name="fracciones" class="form-control">
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                </div>                                    
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="conductas">Presunta conducta <i class="fa fa-asterisk text-red"></i></label>
                                        <select id="conductas" name="conductas[]" class="form-control select2" multiple="multiple" data-placeholder="Selecciona uno o más conductas" required>
                                        <option value="">...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                                <h4>Presunto responsable </h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="n_ref">Nombre <i class="fa fa-asterisk text-red"></i></label>
                                            <input type="text" id="nombre" name="nombre" value="" required="" placeholder="Nombre(s)" maxlength="50" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="ap_pat">Apellido paterno <i class="fa fa-asterisk text-red"></i></label>
                                            <input type="text" id="ap_pat" name="ap_pat" value="" required="" placeholder="Apellido paterno" maxlength="50" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="ap_mat">Apellido materno <i class="fa fa-asterisk text-red"></i></label>
                                            <input type="text" id="ap_mat" name="ap_mat" value="" required="" placeholder="Apellido materno" maxlength="50" class="form-control">
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
                                <label for="coord" class="col-md-12 control-label">Coordinación </label>
                                    <div class="col-sm-3">
                                        <select class="form-control " name="coord" id="coord">
                                        <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div>  

                                <div class="row">
                                <label for="subd" class="col-sm-12 control-label">Subdirección </label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="subd" id="subd">
                                        <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div> 

                                <div class="row">
                                <label for="subd" class="col-sm-12 control-label">Región </label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="region" id="region">
                                        <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div> 

                                <div class="row">
                                <label for="agrupamiento" class="col-sm-12 control-label">Agrupamiento </label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="agrupamiento" id="agrupamiento">
                                        <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div>

                                <br>                               
                            </div>


                            <div id="area_transito" class="hidden">
                               <div class="row">
                                <label for="coord_t" class="col-md-12 control-label">Coordinación </label>
                                    <div class="col-sm-3">
                                        <select class="form-control " name="coord_t" id="coord_t">
                                        <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                <label for="agrupamiento_t" class="col-sm-12 control-label">Agrupamiento </label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="agrupamiento_t" id="agrupamiento_t">
                                        <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div>
                                <br>
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
                                <label for="niv1" class="col-md-12 control-label">Dirección </label>
                                    <div class="col-sm-3">
                                        <select class="form-control " name="niv1" id="niv1">
                                        <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                <label for="niv2" class="col-md-12 control-label">Unidad </label>
                                    <div class="col-sm-3">
                                        <select class="form-control " name="niv2" id="niv2">
                                        <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div>  

                                <div class="row">
                                <label for="niv3" class="col-sm-12 control-label">Dirección de Área </label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="niv3" id="niv3">
                                        <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div> 

                                <div class="row">
                                <label for="niv4" class="col-sm-12 control-label">Subdirección </label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="niv4" id="niv4">
                                        <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div> 

                                <div class="row">
                                <label for="niv5" class="col-sm-12 control-label">Departamento </label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="niv5" id="niv5">
                                        <option value="" selected>...</option>
                                        </select>
                                    </div>
                                </div>

                                <br>                               
                             </div>

                            <br>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="doc">Adjuntar el acuse</label>
                                        <input type="file" id="doc" name="archivo" value="" class="form-control" accept=".pdf" >
                                    </div>
                                </div>
                            </div>
                               
                               

                            <br>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-flat btn-success btn-block">
                                        Guardar información
                                    </button>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                        </form>                   
                    </div>
                </div>
            </div>
        </div>
    </section>