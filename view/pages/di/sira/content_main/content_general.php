<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';
if ( isset($_GET['acta']) ) {
    $acta = $_GET['acta'];
    $a = new SiraModel;
    $r = $a->getOnlyActa($acta);
    #echo "<pre>";print_r($r);echo "</pre>";
    $n_area = $r['actas']->n_area;
    $area_h = $r['actas']->area_id;
    $f_acta = $r['actas']->fecha;    
    $t_actuacion = $r['actas']->t_actuacion;
    $procedencia = $r['actas']->procedencia;
    $municipio = $r['actas']->municipio_id;
    $lugar = $r['actas']->lugar;
    $accion = $r['actas']->comentarios;
    #datos de la orden de inspeccion
    if ( empty($r['oin']) ) {
        $oin_id = NULL;
        $oin_clave = NULL;
    }else{
        $oin_id = $r['oin']->id;
        $oin_clave = $r['oin']->clave;
    }
    #Cambiar el nombre del ID de municipios
    $input_id_mun = 'id="municipios"';
    $input_id_frm = 'id="frm_edit_acta"';
    #recupear los municipios
    $municipios = json_decode($a->getMunicipios());
    
}else{
    $n_area = NULL;
    $area_h = NULL;
    $f_acta = NULL;
    $t_actuacion = NULL;
    $procedencia = NULL;
    $municipio = NULL;
    $lugar = NULL;
    $accion = NULL;
    #datos de la orden de inspeccion
    $oin_id = NULL;
    $oin_clave = NULL;
    $input_id_mun = 'id="municipio"';
    $input_id_frm = 'id="frm_add_acta"';
}


?>
<!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <?php if ( isset($_GET['acta']) ): ?>
                                Formulario de edición de acta: <u><?=$r['actas']->clave?></u>
                            <?php else: ?>
                                Formulario de registro de actas
                            <?php endif ?>
                            (<label>NOTA: </label>Los campos obligatorios se encuentran marcados con un asterisco "<i class="fa fa-asterisk"></i>" )
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div_alert"></div>
                    	<form <?=$input_id_frm?> method="post" action="#">
                    		<?php if ( isset($_GET['acta']) ): ?>
                                <input type="hidden" id="option" name="option" value="32">
                                <input type="hidden" id="acta_id" name="acta_id" value="<?=$r['actas']->id?>">
                            <?php else: ?>
                                <input type="hidden" id="option" name="option" value="30">
                            <?php endif ?>
                    		<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Área que genera <i class="fa fa-asterisk"></i></label>
                                        <input type="text" id="area" name="area" value="<?=$n_area?>" placeholder="Escriba un indicio del área y seleccione alguna de las coincidencias" class="form-control" required="">
                                        <input type="hidden" id="area_h" name="area_h" value="<?=$area_h?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha del acta <i class="fa fa-asterisk"></i></label>
                                        <input type="date" id="f_acta" name="f_acta" value="<?=$f_acta?>" class="form-control" required="">
                                    </div>
                                </div>
                                <input type="hidden" name="t_actuacion" value="<?=$t_actuacion?>">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Procedencia <i class="fa fa-asterisk"></i></label>
                                        <select id="procedencia" name="procedencia" class="form-control" required>
                                            <option value="">...</option>
                                            <option value="1"<?=( $procedencia == 'SECRETARIA DE SEGURIDAD' ) ? 'selected' : '' ;?>>Secretaría de Seguridad</option>
                                            <option value="2"<?=( $procedencia == 'CPRS' ) ? 'selected' : '' ;?>>CPRS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Municipio <i class="fa fa-asterisk"></i></label>
                                        <select <?=$input_id_mun?> name="municipio" class="form-control" required>
                                            <option value="">...</option>
                                        <?php if ( isset($_GET['acta']) ): ?>
                                            <?php foreach ($municipios as $key => $mun): ?>
                                                <?php if ( $municipio == $mun->id ): ?>
                                                    <option value="<?=$mun->id?>" selected><?=$mun->nombre?></option>
                                                <?php else: ?>
                                                    <option value="<?=$mun->id?>"><?=$mun->nombre?></option>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        <?php endif ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Escriba el nombre del lugar</label>
                                        <input type="text" id="lugar" name="lugar" maxlength="255" value="<?=$lugar?>" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Accion(es) realizadas <i class="fa fa-asterisk"></i></label>
                                        <textarea name="accion" id="accion" class="form-control" required style="resize: vertical;max-height: 300px;"><?=$accion?></textarea>
                                    </div>
                                </div>
                            </div>
                            <fieldset>
                                <legend>PERSONAL DE INVESTIGACIÓN</legend>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Buscar al personal de investigación</label>
                                            <input type="text" id="personal" name="personal" value="" placeholder="Escriba y seleccione una persona de la lista" autocomplete="off" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>INVESTIGADORES</label>
                                        <ol id="investigadores" title="INVESTIGADOR">
                                        <?php
                                        if ( isset($_GET['acta']) ) {
                                            for ($i=0; $i < count( $r['inv'] ) ; $i++) { 
                                                if ( $r['inv'][$i]->rol == 'INVESTIGADOR' ) {
                                                    echo '<li id="'.$r['inv'][$i]->id_persona.'">';
                                                    echo '<input type="hidden" name="investigadores[]" value="'.$r['inv'][$i]->id_persona.'">';
                                                    echo $r['inv'][$i]->full_name;
                                                    echo '<button type="button" class="btn btn-flat btn-danger btn-sm" onclick="remover_persona('.$r['inv'][$i]->id.',\'investigadores\')">'.
                                                            '<i class="fa fa-minus"></i>'.
                                                        '</button> ';
                                                    echo '</li>';
                                                }
                                            }
                                        }
                                        ?>
                                        </ol>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Buscar al personal de apoyo</label>
                                            <input type="text" id="personal_a" name="personal_a" value="" placeholder="Escriba y seleccione una persona de la lista" autocomplete="off" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>PERSONAL DE APOYO</label>
                                        <ol id="apoyo" title="PERSONAL DE APOYO">
                                            <?php
                                            if ( isset($_GET['acta']) ){
                                                for ($i=0; $i < count( $r['inv'] ) ; $i++) { 
                                                    if ( $r['inv'][$i]->rol == 'APOYO' ) {
                                                        echo '<li id="'.$r['inv'][$i]->id_persona.'">';
                                                        echo '<input type="hidden" name="apoyo[]" value="'.$r['inv'][$i]->id_persona.'">';
                                                        echo $r['inv'][$i]->full_name;
                                                        echo '<button type="button" class="btn btn-flat btn-danger btn-sm" onclick="remover_persona('.$r['inv'][$i]->id.',\'apoyo\')">'.
                                                                '<i class="fa fa-minus"></i>'.
                                                            '</button> ';
                                                        echo '</li>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </ol>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="pull-right">¿El acta esta relacionada a una Orden de Inspección/ Verificación/ Supervisión?</label>
                                </div>
                                <div class="col-md-2">
                                    <select id="question" name="question" class="form-control">
                                        <option value="">...</option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                    </select>
                                </div>
                            </div>
                            <div id="orden" class="hidden">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="">Buscar la orden: </label>
                                        <input type="text" id="orden_i" name="orden" value="<?=$oin_id?>" placeholder="Escriba una parte de la clave de la orden de trabajo." autocomplete="off" class="form-control">
                                        <input type="hidden" id="orden_h" name="orden_h" value="<?=$oin_clave?>">
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
    
    