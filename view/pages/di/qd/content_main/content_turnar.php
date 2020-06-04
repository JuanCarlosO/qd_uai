<!-- Main content -->
<form id="frm_add_turno" action="#" method="post">
    <input type="hidden" name="option" value="76">
    <input type="hidden" name="queja_id" value="<?=$_GET['queja']?>">
    <input type="hidden" name="origen" value="<?=$a = ( isset($_GET['origen']) ) ? $_GET['origen'] : 0 ;?>">
        <section class="content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Listado de Quejas y Denuncias</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="div_turno"></div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Estado del expediente</label>
                                    <select id="estado" name="estado" class="form-control" required>
                                        <option value="">...</option>
                                        <option value="2">ACUMULADO</option>
                                        <option value="8">RESPONSABILIDADES</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Fecha de turnado</label>
                                    <input type="date" name="f_turnado" value="" required class="form-control">
                                </div>
                                <div class="col-md-4">
                                    
                                </div>
                            </div>  
                            <!--Cuando es tramite o prescrito-->
                            <div id="contenedor_0" class="hidden">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Turnar a</label>
                                            <input type="text" class="form-control" id="sp" name="sp">
                                            <input type="hidden" class="form-control" id="sp_id" name="sp_id">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Cuando es archivo -->
                            <div id="contenedor_1" class="row hidden">
                                <div class="col-md-6">
                                    <label>Fecha del acuerdo</label>
                                    <input type="date" name="f_acuerdo" value="" class="form-control">
                                </div>  
                                <div class="col-md-6">
                                    <label>Adjuntar documento</label>
                                    <input type="file" name="file" value="" class="form-control">
                                </div>
                            </div>
                            <!-- respo -->
                            <div id="contenedor_2" class="hidden">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Tipo de ley</label>
                                        <select name="t_ley" id="t_ley" class="form-control"></select>
                                    </div>  
                                    <div class="col-md-9">
                                        <label>Catalogo de conductas</label>
                                        <select name="conductas[]" id="conductas" class="form-control" >
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label>Turnar a</label>
                                        <input type="text" id="persona" name="persona" value="" class="form-control">
                                        <input type="hidden" id="persona_id" name="persona_id" value="">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Oficio con el que se envia</label>
                                            <input type="text" id="oficio_envio" name="oficio_envio" value=""  class="form-control">
                                            <input type="hidden" id="oficio_e_id" name="oficio_e_id" value=""  class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- incompetencia -->
                            <div id="contenedor_3" class=" hidden">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nombre de la dependencia</label>
                                            <select id="dependencia_f" name="dependencia_f" class="form-control"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nombre del funcionario</label>
                                            <input type="text" id="funcionario_f" name="funcionario_f" value="" class="form-control" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Número de oficio</label>
                                            <input type="text" id="oficio" name="oficio" value="" class="form-control">
                                            <input type="hidden" id="oficio_id" name="oficio_id" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- acumulado -->
                            <div id="contenedor_4" class="hidden">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Expediente al que se le acumula</label>
                                        <input type="text" id="expediente" name="expediente" value="" class="form-control">
                                        <input type="hidden" id="expediente_id" name="expediente_id" value="">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Servidor público</label>
                                        <input type="text" id="sp_uai" name="sp_uai" value="" class="form-control">
                                        <input type="hidden" id="sp_uai_id" name="sp_uai_id" value="" placeholder="">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Observaciones</label>
                                    <textarea name="comentario" class="form-control" style="resize: vertical;max-height: 200px;"></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <button id="btn_turnar" type="submit" class="btn btn-success btn-flat btn-block">
                                        <i class="fa fa-floppy-o"></i> Guardar turno
                                    </button>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</form>
    
    