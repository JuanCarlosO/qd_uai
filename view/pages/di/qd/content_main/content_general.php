<?php
require_once 'model/Connection.php';
require_once 'model/QDModel.php';
$q = new QDModel;
$colores = json_decode( $q->getColores() );
$municipios = json_decode( $q->getMunicipios() );

if ( $_SESSION['perfil'] == 'QDP' ) { $t_as = 1; }
if ( $_SESSION['perfil'] == 'QDNP' ) { $t_as = 2; }
?>
<!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Formulario de Alta de Queja o Denuncia</h3>
                        (<label>NOTA: </label>Los campos obligatorios se encuentran marcados con un asterisco "<i class="fa fa-asterisk text-red"></i>" )
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div_alert"></div>
                    	<form id="frm_add_queja" method="post" action="#">
                    		<input type="hidden" id="option" name="option" value="11">
                            <input type="hidden" name="estado" value="8">
                            <input type="hidden" name="prioridad" value="1">
                            <?php if ( isset($_GET['acta_admin']) ): ?>
                            <input type="hidden" name="acta_admin" value="<?=$_GET['acta_admin']?>">
                            <?php endif ?>
                            
                    		<div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Tipo de trámite <i class="fa fa-asterisk text-red"></i></label>
                                        <select id="t_tra" name="t_tra" class="form-control" required>
                                            <option value="">...</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Clave del expediente</label>
                                        <input type="text" id="cve_exp" name="cve_exp" value="" placeholder="Campo automático" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Área administrativa <i class="fa fa-asterisk text-red"></i> </label>
                                        <select id="t_asunto" name="t_asunto" class="form-control" required autofocus>
                                            <option value="">...</option>
                                            <option value="1" <?=( $t_as == 1 ) ? 'selected=""' : '' ;?> >POLICIAL</option>
                                            <option value="2" <?=( $t_as == 2 ) ? 'selected=""' : '' ;?> >ESPECIAL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <fieldset>
                                <legend class="text-center "> <h2><b>Datos de la queja o denuncia</b></h2> </legend>
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label>Vía de recepción <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="vias_r" name="vias_r[]" class="form-control select2" required multiple>
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="evidencia">Evidencia</label>
                                            <select id="evidencia" name="evidencia" class="form-control">
                                                <option value="">...</option>
                                                <option value="1">CD/DVD</option>
                                                <option value="2">MEMORIA USB</option>
                                                <option value="3">FOTOGRAFÍAS</option>
                                                <option value="4">DOCUMENTOS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="fojas">Número de fojas</label>
                                            <input type="number" name="fojas" value="0" placeholder="" class="form-control" autocomplete="off" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="procedencia">Procedencia <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="procedencia" name="procedencia" class="form-control" required="">
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fecha de hechos <i class="fa fa-asterisk text-red"></i></label>
                                            <input type="date" id="f_hechos" name="f_hechos" value="" class="form-control" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Hora de hechos</label>
                                            <input type="time" id="h_hechos" name="h_hechos" value="" placeholder="Campo automático" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="t_afecta">Tipo de afectada(o) <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="categoria" name="categoria" class="form-control" required>
                                                <option value="">...</option>
                                                <option value="1">CIUDADANO</option>
                                                <option value="2">SERVIDOR PÚBLICO</option>
                                                <option value="3">OTRO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" style="margin-top: 25px;">
                                            <big>
                                                <label style="text-decoration: underline black;" for="d_ano">¿ES DENUNCIA ANÓNIMA?</label>
                                                &emsp;
                                                <big class="">
                                                    <label for="d_ano">SÍ</label> <input type="checkbox" id="d_ano" name="d_ano" value="1" style="font-size: 110%; display: inline;">
                                                </big>
                                            </big>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset id="d_quejoso" class="">
                                <legend class="text-center"> <h2><b>Datos del quejoso</b></h2> </legend>
                                <div id="persona" class="hidden">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Nombre completo</label>
                                                <input type="text" name="name_quejoso" id="name_quejoso" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Teléfono</label>
                                                <input type="text" name="phone" value="" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Correo electrónico</label>
                                                <input type="email" name="mail" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="genero">Género del quejoso o denunciante <i class="fa fa-asterisk text-red"></i></label>
                                                <select id="genero" name="genero" class="form-control">
                                                    <option value="">...</option>
                                                    <option value="1">MASCULINO</option>
                                                    <option value="2">FEMENINO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Municipio</label>
                                                <select id="municipio" name="municipio" class="form-control">
                                                    <option value="">...</option>
                                                    <?php foreach ($municipios as $municipio): ?>
                                                    <option value="<?=$municipio->id?>">
                                                        <?=$municipio->nombre?>
                                                    </option>
                                                    <?php endforeach ?>                            
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Código postal</label>
                                                <input type="text" name="cp" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Núm. Int.</label>
                                                <input type="text" name="n_int" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Núm. Ext.</label>
                                                <input type="text" name="n_ext" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Nombre de la calle</label>
                                                <input type="text" class="form-control" name="n_calle" value="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="descripcion">Descripción de los hechos </label>
                                                <textarea id="descripcion" name="descripcion" class="form-control" style="resize: vertical; max-height: 300px; " rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="servidor_publico">
                                    <?php include_once 'probable_responsable.php'; ?>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend class="text-center"> <h2><b>Datos del lugar de los hechos</b></h2> </legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="municipios">Municipio <i class="fa fa-asterisk text-red"></i></label>
                                            <select id="municipios" name="municipios" class="form-control" required>
                                                <option value="">...</option>
                                                <?php foreach ($municipios as $municipio): ?>
                                                <option value="<?=$municipio->id?>">
                                                    <?=$municipio->nombre?>
                                                </option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="c_principal">Calle principal</label>
                                            <input type="text" id="c_principal" name="c_principal" class="form-control" placeholder="Nombre de la calle principal" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="e_calle">Entre calle </label>
                                            <input type="text" id="e_calle" name="e_calle" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="y_calle">Y calle</label>
                                            <input type="text" id="y_calle" name="y_calle" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="edificacion">Colonia </label>
                                            <input type="text" id="edificacion" name="edificacion" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="n_edificacion">Número</label>
                                            <input type="text" id="n_edificacion" name="n_edificacion" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend class="text-center"> <h2><b>Alta de problable(s) responsable(s)</b></h2> </legend>
                                <!-- <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="comentario">Referencia(s) de adscripción(es) de presunto(s) responsable(s)</label>
                                            <input type="text" id="comentario" name="comentario" value="" class="form-control" placeholder="Ej: Adscrito al tercer agrupamiento ASES. en el municipio de Amecameca." autocomplete="off" maxlength="255">
                                        </div>
                                    </div>
                                </div> -->
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>¿Desea agregar uno o varios probable(s) responsable(s)?</label>
                                            <select name="pregunta" id="pregunta" class="form-control" required>
                                                <option value="">...</option>
                                                <option value="2">SÍ</option>
                                                <option value="1">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="cant_person" class="hidden">
                                        <div class="col-md-4">
                                            <label>¿Cuántas personas desea agregar?</label>
                                            <div class="input-group">
                                                <input type="number" id="cantidad" name="cantidad" value="" class="form-control" min="1" max="30" >
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-success btn-flat" onclick="addPresuntos();" > 
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="mult_presuntos" class="hidden">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="box box-success box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Sección de presuntos responsables</h3>
                                            </div>
                                            <div class="box-body">
                                                <div id="formularios"></div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend class="text-center"> <h2><b>Datos de unidad implicada</b></h2> </legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>¿Desea agregar una unidad?</label>
                                            <select name="question_unidad" id="question_unidad" class="form-control" required="">
                                                <option value="">...</option>
                                                <option value="1">SI</option>
                                                <option value="2">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="data_unidad" class="hidden">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Procedencia</label>
                                                <select id="u_procedencia" name="u_procedencia" class="form-control">
                                                    <option value="">...</option>
                                                    <option value="1">CPRS</option>
                                                    <option value="2">SECRETARIA DE SEGURIDAD</option>
                                                    <option value="3">ADMINISTRATIVO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Tipo de vehículo</label>
                                                <select id="t_vehiculo" name="t_vehiculo" class="form-control">
                                                    <option value="">...</option>
                                                    <!-- VALORES ENUM DE LA BD-->
                                                    <option value="1">MOTOCICLETA</option>
                                                    <option value="2">CAMIONETA</option>
                                                    <option value="3">AUTOMOVIL</option>
                                                    <option value="4">CAMIÓN</option>
                                                    <option value="5">PICKUP</option>
                                                    <option value="6">ACUÁTICO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Color</label>
                                                <select id="colores" name="color" class="form-control">
                                                    <option value="">...</option>
                                                    <?php foreach ($colores as $color): ?>
                                                    <option value="<?=$color->id?>"><?=$color->nombre?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Número económico</label>
                                                <input type="text" id="n_eco" name="n_eco" class="form-control" value="" placeholder="Ej: 123-S">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Placas</label>
                                                <input type="text" id="placas" name="placas" class="form-control" value="" placeholder="123-22">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Número de serie (Opcional)</label>
                                                <input type="text" name="n_ser" class="form-control" placeholder="Ej: 123456789">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Número de inventario (Opcional)</label>
                                                <input type="text" name="n_inv" class="form-control" placeholder="Ej: 987654321">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Características de la unidad </label>
                                                <textarea name="u_comentarios" class="form-control" style="resize: vertical; max-height: 250px;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend class="text-center"> <h2><b>Alta de semoviente</b></h2> </legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>¿Desea agregar un semoviente?</label>
                                            <select id="add_semo" name="add_semo" class="form-control" required="">
                                                <option value="">...</option>
                                                <option value="1">SI</option>
                                                <option value="2">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="info_semo" class="hidden">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Tipo de semoviente</label>
                                                <select id="t_animal" name="t_animal" class="form-control">
                                                    <option value="">...</option>
                                                    <option value="1">Caballo</option>
                                                    <option value="2">Perro</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Raza del semoviente</label>
                                                <input type="text" id="raza" name="raza" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Edad</label>
                                                <input type="text" id="edad" name="edad" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Color</label>
                                                <input type="text" id="color" name="color" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nombre del semoviente</label>
                                                <input type="text" id="n_animal" name="n_animal" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Número de inventario</label>
                                                <input type="text" id="inventario" name="inventario" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend class="text-center"> <h2><b>Probable conducta</b></h2> </legend>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="t_ley"> Normatividad aplicable<i class="fa fa-asterisk text-red"></i></label>
                                            <select id="t_ley" name="t_ley" class="form-control" required>
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Capítulo</label>
                                            <select id="capitulos" name="capitulo" class="form-control">
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
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
                                            <select id="conducta" name="conducta" class="form-control" data-placeholder="Selecciona una conducta" required>
                                                <option value="">...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-flat btn-success btn-block">
                                        <i class="fa fa-floppy-o"></i>
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
    
    