<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';

$orden = $_GET['ot'];
$a = new SiraModel;
$r = $a->getOnlyOrden($orden);

$t_orden = $r['orden']->t_orden;
$clave = $r['orden']->clave;    


$input_id_mun = 'id="municipio"';
$input_id_frm = 'id="frm_add_acciones"';

?>
<!-- Main content -->
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">                            
                        Formulario de registro de acciones para la OT: <b><?=$clave;?></b>                            
                        <br>
                        <small>(<label>NOTA: </label>Campos obligatorios "<i class="fa fa-asterisk text-red"></i>" )</small>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="div_alert"></div>
                    <form <?=$input_id_frm?> method="post" action="#">

                        <input type="hidden" id="option" name="option" value="161">
                        <input type="hidden" name="ot_id" value="<?=$_GET['ot']?>">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha de realización <i class="fa fa-asterisk text-red"></i></label>
                                    <input type="date" id="f_acta" name="f_acta" class="form-control" required="">
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


                        



                    <?php if ( $t_orden == 'SUPERVISION' ): ?>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Red vial</label>
                                    <input type="text" id="red" name="red" maxlength="255"  class="form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Referencia del lugar</label>
                                    <input type="text" id="referencia_red" name="referencia_red" maxlength="255"  class="form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        

                        <?php else: ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Calle</label>
                                        <input type="text" id="calle" name="calle" maxlength="255"  class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Número</label>
                                        <input type="text" id="numero" name="numero" maxlength="255"  class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Colonia</label>
                                        <input type="text" id="colonia" name="colonia" maxlength="255"  class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Código Postal</label>
                                        <input type="text" id="cp" name="cp" maxlength="255"  class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>                          

                        <?php endif ?>                            



                        <div class="row">                                
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Municipio <i class="fa fa-asterisk text-red"></i></label>
                                    <select <?=$input_id_mun?> name="municipio" class="form-control" required>
                                        <option value="">...</option>
                                        <?php foreach ($municipios as $key => $mun): ?>
                                            <option value="<?=$mun->id?>"><?=$mun->nombre?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Acción(es) realizadas <i class="fa fa-asterisk text-red"></i></label>
                                    <textarea name="acciones" id="acciones" class="form-control" required style="resize: vertical;max-height: 300px;"></textarea>
                                </div>
                            </div>
                        </div>

                        <fieldset>
                                <legend>Personal de Investigación</legend>
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

                        <br>

                        <div class="row">                                
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Estatus de la Orden de Trabajo <i class="fa fa-asterisk text-red"></i></label>
                                    <select id="estado" name="estado" class="form-control" required>
                                        <option value="">...</option>
                                        <option value="1">Cumplida</option>
                                        <option value="2">Parcial sin resultado</option>
                                        <option value="3">Parcial con resultado</option>
                                        <option value="4">Cumplida sin resultado</option>
                                </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observaciones de la Orden de Trabajo</label>
                                    <textarea id="observaciones" name="observaciones" class="form-control" rows="5" style="resize: vertical; max-height: 250px;"></textarea>
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