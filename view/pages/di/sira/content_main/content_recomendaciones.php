<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';

    $input_id_frm = 'id="frm_add_recomendaciones"';
    $ot = $_GET['ot'];
    $a = new SiraModel;
    $cedula = $a->getCedulaOT($ot);



?>
<!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">                         
                                Formulario de registro de irregularidades y recomendaciones para la OT: <b><?=$cedula[0]['clave'];?></b>                           
                            <br>
                            <small>(<label>NOTA: </label>Campos obligatorios "<i class="fa fa-asterisk text-red"></i>" )</small>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div_alert"></div>
                        <form <?=$input_id_frm?> method="post" action="#">              
                            <input type="hidden" id="option" name="option" value="124">
                            <input type="hidden" name="ot_id" value="<?=$_GET['ot']?>">
                            
                         
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha del oficio <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="date" id="f_envio" name="f_envio" class="form-control" required >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha de acuse de recepción <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="date" id="f_acuse" name="f_acuse" class="form-control" required>
                                    </div>
                                </div>
                               
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Número de oficio <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="text" id="oficio" name="oficio" class="form-control" required>
                                        <input type="hidden" id="oficio_id" name="oficio_id" value="">
                                    </div>
                                </div>
                            </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                          <label>Destinatario</label>
                                          <input type="text" class="form-control" id="destinatario_ofi" name="destinatario_ofi" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                          <label>Cargo del destinatario</label>
                                          <input type="text" class="form-control" id="cargo_remi" name="cargo_remi" readonly>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                          <label>Asunto</label>
                                          <input type="text" class="form-control" id="asunto_ofi" name="asunto_ofi" readonly>
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

                                <br>                                 
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

                         <!--    <div class="row">    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha límite para respuestas (Recuerde seleccionar 15 días naurales) <i class="fa fa-asterisk text-red"></i></label>
                                        <input type="date" id="f_limite" name="f_limite" class="form-control">
                                    </div>
                                </div>
                            </div>-->

                            <div class="row">    
                                <div class="col-md-3">
                                    <label>¿Cuántas irregularidades desea agregar?</label>
                                    <div class="input-group">
                                        <input type="number" id="cantidad" name="cantidad" value="" class="form-control" min="1" max="30" required>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-success btn-flat" onclick="addObservaciones();" > 
                                                            <i class="fa fa-check"></i>
                                                </button>
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div id="mult_observaciones" class="hidden">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-success box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Sección de irregularidades y recomendaciones</h3>
                                            </div>
                                            <div class="box-body">
                                                <div id="formularios"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Comentarios </label>
                                        <textarea name="comentario" id="comentario" class="form-control"  style="resize: vertical;max-height: 300px;" ></textarea>
                                    </div>
                                </div>
                            </div>


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