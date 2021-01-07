<?php
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
$queja_id = $_GET['exp'];
$q = new DRModel;
$clave = $q->getClave($queja_id);
?>
<input type="hidden" id="nivel" name="nivel" value="<?=$_SESSION['nivel']?>">
<form action="#" id="frm_acuerdo_improcedencia">
    <input type="hidden" name="option" value="66">
    <input type="hidden" name="queja_id" value="<?=$_GET['exp']?>">
    <input type="hidden" id="estado_exp" name="estado_exp" value="">

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Registro de acuerdo  <u><?=$clave?></u></h3>
                    </div>
                    <div class="box-body">
                        <div id="div_acuerdo"></div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha del acuerdo</label>
                                    <input type="date" id="f_acuerdo" name="f_acuerdo" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha del turno</label>
                                    <input type="date" id="f_turno" name="f_turno" value="" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Acuerdo</label>
                                    <input type="file" name="file" value="" class="form-control" required accept=".pdf">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Motivo del cierre</label>
                                    <select name="motivo" id="motivo" class="form-control">
                                        <option value="">...</option>
                                        <option value="INCOMPETENCIA">INCOMPETENCIA</option>
                                        <option value="ARCHIVO">ARCHIVO</option>
                                        <option value="IMPROCEDENCIA">IMPROCEDENCIA</option>
                                        <option value="RESERVA">RESERVA</option>
                                        <option value="CIERRE">CIERRE DE EXPEDIENTE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Asunto</label>
                                    <input type="text" name="asunto" value="" placeholder="Ej:Asunto del documento" class="form-control" required="" maxlength="255">
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Comentario</label>
                                    <textarea name="comentario" class="form-control" style="resize: vertical; max-height: 200px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success btn-flat btn-block">
                                    <i class="fa fa-floppy-o"></i> Guardar datos
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
