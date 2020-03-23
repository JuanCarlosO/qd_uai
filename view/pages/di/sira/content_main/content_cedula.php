<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';
if ( isset($_GET['acta']) ) {
    if ( (int)$_GET['acta'] > 0 ) {
        $acta = $_GET['acta'];
        $a = new SiraModel;
        $r = $a->getOnlyActa($acta);
        echo "<pre>";print_r($r);echo "</pre>";
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
        echo '<script type="text/javascript"> document.location.href = "login.php"; </script>';
    }
}else{
    echo '<script type="text/javascript"> document.location.href = "login.php"; </script>';
}
?>

<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Detalle de la cédula</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Acta con número: <u><?=$r['actas']->clave?></u></center> </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>¿Quién genera?</dt>
                                <dd> <?=$r['actas']->n_area?> </dd>
                                <dt>Tipo de actuación</dt>
                                <dd><?=$r['actas']->t_actuacion?></dd>
                                <dt>Número de acta</dt>
                                <dd><?=$r['actas']->clave?></dd>
                                <dt>Fecha del acta</dt>
                                <dd> <?=$r['actas']->fecha?> </dd>
                                <dt>Procedencia</dt>
                                <dd> <?=$r['actas']->procedencia?> </dd>
                            </dl>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Datos de la dirección</center> </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>Municipio</dt>
                                <dd> <?=$r['actas']->n_municipio?> </dd>
                                <!-- <dt>Zona</dt>
                                <dd><?=$r['actas']->zona?></dd> -->
                                <dt>Lugar</dt>
                                <dd><?=$r['actas']->lugar?></dd>
                                <dt>Descripción Acta</dt>
                                <dd> <p class="text-justify"><?=$r['actas']->comentarios?></p> </dd>
                                <dt>Investigadores</dt>
                                <?php foreach ($r['inv'] as $key => $inv): ?>
                                    <dd> <?=$inv->full_name?> </dd>
                                <?php endforeach ?>
                                
                            </dl>
                        </div>
                    </div>      
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Datos del quejoso</center> </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>Nombre completo</dt>
                                <dd> Juan Carlos Diaz Bernal </dd>
                                <dt>Teléfono</dt>
                                <dd>1234567890</dd>
                                <dt>Medios de Localización</dt>
                                <dd><a href="mailto:webmaster@example.com">micorreo@mail.com</a></dd>
                                <dd><a href="mailto:webmaster@example.com">facebook.com/miperfil/</a></dd>
                                <dt>Dirección</dt>
                                <dd> <p >Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> </dd>
                                
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Datos del presunto infractor</center> </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>Nombre completo</dt>
                                <dd> Juan Carlos Diaz Bernal </dd>
                                <dt>Género</dt>
                                <dd>Masculino</dd>
                                <dt>Procedencia</dt>
                                <dd>Unidad de Asuntos Internos</dd>  
                                <dt>Adscripcion</dt>
                                <dd>Dirección de Investigacion</dd>   
                                <dt>Subdirección/Fiscalía</dt>
                                <dd>Unidad de Quejas y Denuncias</dd>   
                                <dt>Región/Mesa </dt>
                                <dd>Region II</dd>   
                                <dt>Agrupamiento/Turno </dt>
                                <dd>Region II</dd>   
                                <dt>Cargo </dt>
                                <dd>Policia RIII</dd>   
                                <dt>Media Filiación </dt>
                                <dd>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa, enim tempore quo aspernatur eligendi voluptatem earum consequuntur veniam sequi soluta, in culpa distinctio! Rem optio repellendus, ratione voluptas illum! Blanditiis?</dd>   
                                                 
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Datos de las unidades</center> </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>Marca</dt>
                                <dd> Dodge </dd>
                                <dt>Submarca</dt>
                                <dd> Stratus </dd>
                                <dt>Tipo</dt>
                                <dd> Sedan  </dd>
                                <dt>Año</dt>
                                <dd> 2010 </dd>
                                <dt>Color</dt>
                                <dd>Rojo volcanico </dd>
                                <dt>Placa</dt>
                                <dd> lxp8690 </dd>                            
                                <dt>Serie</dt>
                                <dd> 123456789l </dd>
                                <dt>Inventario</dt>
                                <dd> 123456789 </dd>
                                <dt>Corporación</dt>
                                <dd> Secretaria de Seguridad </dd>          
                            </dl>
                        </div>
                    </div>   
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Datos de los animales</center> </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>Tipo de animal</dt>
                                <dd> Perro </dd>
                                <dt>Raza del animal</dt>
                                <dd> Pastor Malinoins Belga </dd>
                                <dt>Nombre del animal</dt>
                                <dd> Rambo </dd>
                                <dt>Edad del animal</dt>
                                <dd> 3 años </dd>
                                <dt>Color del animal</dt>
                                <dd> Café </dd>
                                <dt>Número de Inventario</dt>
                                <dd> rambo3ss </dd>                            
                                         
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h1> <center>Datos de las armas</center> </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>Arma</dt>
                                <dd> Fuego </dd>
                                <dt>Tipo</dt>
                                <dd> Pistola </dd>
                                <dt>Subtipo</dt>
                                <dd> Revólver </dd>
                                <dt>Marca</dt>
                                <dd> EcuRed </dd>
                                <dt>Calibre</dt>
                                <dd> .16 </dd>
                                <dt>Color</dt>
                                <dd> Negro </dd>                            
                                <dt>Inventario</dt>
                                <dd> 12l22 </dd>
                                <dt>Matrícula</dt>
                                <dd> 1314INI069 </dd>       
                            </dl>
                        </div>
                    </div>           	
                </div>
            </div>
        </div>
    </div>
</section>
    
