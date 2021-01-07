<?php
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
try {
    $error = false;
    if ( isset($_GET['queja_id']) && !empty($_GET['queja_id']) ) {
        $queja_id = $_GET['queja_id'];
        $r = new DRModel;
        $san = $r->getSancion($queja_id);
        if (!$san) {
            throw new Exception("NO HAY DATOS PARA MODIFICAR", 1);        
        }
        $ver = $r->getVerificacion($san->id);
    }
} catch (Exception $e) {
    $error = true;
    $msg = $e->getMessage();
}
#echo "<pre>"; print_r($ver); echo "</pre>";
?>
<?php if ($error): ?>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulario de edición</h3>
                </div>
                <div class="box-body">
                    <h1 class="text-red text-center"><?=$msg?></h1>
                    <p>Debe de registrar una sanción y una verificación. Dicha funcionalidad se encuentran en el boton de acciones del
                    listado de expedientes.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php else: ?>
<form action="#" id="frm_edit_sv" method="post">
    <input type="hidden" id="nivel" name="nivel" value="<?=$_SESSION['nivel']?>">
    <input type="hidden" name="san_id" value="<?=( !empty($san->id) ) ? $san->id : 0 ;?>">
    <input type="hidden" name="ver_id" value="<?=( isset($ver->id) ) ? $ver->id : 0 ;?>">
    <input type="hidden" name="quien" value="<?=$_SESSION['id']?>">
    <input type="hidden" name="option" value="5X">
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Formulario de edición</h3>
                    </div>
                    <div class="box-body">
                        <div id="san_ver"></div>
                        <div class="box-group" id="accordion">
                            <div class="panel box box-success box-solid">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#sancion">
                                            Editar sanción
                                        </a>
                                    </h4>
                                </div>
                                <div id="sancion" class="panel-collapse collapse">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Nombre completo del presunto responsable</label>
                                                    <input type="text" class="form-control" name="n_responsable" value="<?=( !empty($san->nombre_pr) ) ? $san->nombre_pr : '' ;?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Adscripción</label>
                                                    <input type="text" class="form-control" name="adscripcion" value="<?=( !empty($san->adscripcion) ) ? $san->adscripcion : '' ;?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>RFC</label>
                                                    <input type="text" class="form-control" name="rfc" value="<?=( !empty($san->rfc) ) ? $san->rfc : '' ;?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>CUIP</label>
                                                    <input type="text" class="form-control" name="cuip" value="<?=( !empty($san->cuip) ) ? $san->cuip : '' ;?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>CURP</label>
                                                    <input type="text" class="form-control" name="curp" value="<?=( !empty($san->curp) ) ? $san->curp : '' ;?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Fecha de determinación</label>
                                                    <input type="date" class="form-control" name="f_determina" value="<?=( !empty($san->f_determina) ) ? $san->f_determina : '' ;?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Fecha de sesión </label>
                                                    <input type="date" name="f_sesion" value="<?=( !empty($san->f_sesion) ) ? $san->f_sesion : '' ;?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Fecha de notificación </label>
                                                    <input type="date" name="f_notificacion" value="<?=( !empty($san->f_notificacion) ) ? $san->f_notificacion : '' ;?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Fecha de resolución </label>
                                                    <input type="date" name="f_resolucion" value="<?=( !empty($san->f_resolucion) ) ? $san->f_resolucion : '' ;?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Sanción</label>
                                                    <select name="castigo" id="castigos" class="form-control">
                                                        <option value="">...</option>
                                                        <option value="1" <?=($san->castigo == 'AMONESTACION') ? 'selected' : '';?>>Amonestación</option>
                                                        <option value="2" <?=($san->castigo == 'INEXISTENCIA') ? 'selected' : '';?>>Inexistencia</option>
                                                        <option value="3" <?=($san->castigo == 'SUSPENSION') ? 'selected' : '';?>>Supensión</option>
                                                        <option value="4" <?=($san->castigo == 'REMOCION') ? 'selected' : '';?>>Remoción del cargo</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Días de suspensión </label>
                                                    <select name="dias_s" id="dias_s" class="form-control">
                                                        <option value="">...</option>
                                                        <option value="1" <?=($san->dias_sus == '1') ? 'selected' : '';?>>1</option>
                                                        <option value="2" <?=($san->dias_sus == '2') ? 'selected' : '';?>>2</option>
                                                        <option value="3" <?=($san->dias_sus == '3') ? 'selected' : '';?>>3</option>
                                                        <option value="4" <?=($san->dias_sus == '4') ? 'selected' : '';?>>4</option>
                                                        <option value="5" <?=($san->dias_sus == '5') ? 'selected' : '';?>>5</option>
                                                        <option value="6" <?=($san->dias_sus == '6') ? 'selected' : '';?>>6</option>
                                                        <option value="7" <?=($san->dias_sus == '7') ? 'selected' : '';?>>7</option>
                                                        <option value="8" <?=($san->dias_sus == '8') ? 'selected' : '';?>>8</option>
                                                        <option value="9" <?=($san->dias_sus == '9') ? 'selected' : '';?>>9</option>
                                                        <option value="10" <?=($san->dias_sus == '10') ? 'selected' : '';?>>10</option>
                                                        <option value="11" <?=($san->dias_sus == '11') ? 'selected' : '';?>>11</option>
                                                        <option value="12" <?=($san->dias_sus == '12') ? 'selected' : '';?>>12</option>
                                                        <option value="13" <?=($san->dias_sus == '13') ? 'selected' : '';?>>13</option>
                                                        <option value="14" <?=($san->dias_sus == '14') ? 'selected' : '';?>>14</option>
                                                        <option value="15" <?=($san->dias_sus == '15') ? 'selected' : '';?>>15</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>¿Se notificó?</label>
                                                    <select name="notificado" id="notificado" class="form-control">
                                                        <option value="">...</option>
                                                        <option value="1" <?=($san->notificado == 'SI') ? 'selected' : '';?>>Si</option>
                                                        <option value="2" <?=($san->notificado == 'NO') ? 'selected' : '';?>>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Número de oficio de resolución</label>
                                                    <input type="text" class="form-control" name="oficio_sa" id="oficio_sa" value="<?=(!empty($san->oficio)) ? $san->oficio : '';?>">
                                                    <input type="hidden" class="form-control" name="oficio_sa_id" id="oficio_sa_id" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Observaciones</label>
                                                    <textarea name="comentario_san" id="" class="form-control" style="max-height: 250px; resize: vertical;"><?=(!empty($san->comentario)) ? $san->comentario : '';?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-success box-solid">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#verificacion">
                                            Editar verificación
                                        </a>
                                    </h4>
                                </div>
                                <div id="verificacion" class="panel-collapse collapse">
                                    <div class="box-body">
                                        <?php if (isset($ver->id) && !empty($ver->id)): ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Fecha de notificación al servidor público</label>
                                                        <input type="date" name="f_notifica_sp" value="<?=( !empty($ver->f_not_sp) ) ? $ver->f_not_sp : '' ;?>" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Fecha de notificación a R.H. </label>
                                                        <input type="date" name="f_notifica_rh" value="<?=( !empty($ver->f_not_rh) ) ? $ver->f_not_rh : '' ;?>" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Fecha de captura RNPSP</label>
                                                        <input type="date" name="capt_rnpsp" value="<?=( !empty($ver->f_cpt_rnpsp) ) ? $ver->f_cpt_rnpsp : '' ;?>" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Fecha de ejecución </label>
                                                        <input type="date" name="f_ejec" value="<?=( !empty($ver->f_ejec) ) ? $ver->f_ejec : '' ;?>" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Observaciones </label>
                                                        <textarea name="comentario_ver" id="comentario" class="form-control" style="max-height: 250px; resize: vertical;"><?=( !empty($ver->comentario) ) ? $ver->comentario : '' ;?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <h1 class="text-center text-red">NO HAY VERFICACIÓN REGISTRADA</h1>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <button class="btn btn-success btn-flat btn-block">
                                    <i class="fa fa-floppy-o"></i> Guardar cambios
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
<?php endif ?>