<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';
if ( isset($_GET['acta']) ) {
    if ( (int)$_GET['acta'] > 0 ) {
        $acta = $_GET['acta'];
        $a = new SiraModel;
        $r = $a->getOnlyActa($acta);
        #echo "<pre>";print_r($r['pr']);echo "</pre>";
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
##tipo de actuacion con asentos
$t_actuacion = "";
switch ($r['actas']->t_actuacion) {
    case 'INSPECCION':
        $t_actuacion = "INSPECCIÓN";
        break;
    case 'VERIFICACION':
        $t_actuacion = "VERIFICACIÓN";
        break;
    case 'SUPERVISION':
        $t_actuacion = "SUPERVISIÓN";
        break;
    case 'INVESTIGACION':
        $t_actuacion = "INVESTIGACIÓN";
        break;
    
    default:
        # code...
        break;
}

?>

<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Detalle de la cédula del acta </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h2> <center>Acta con número: <u><?=$r['actas']->clave?></u></center> </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>¿Quién genera?</dt>
                                <dd> <?=$r['actas']->n_area?> </dd>
                                <dt>Tipo de actuación</dt>
                                <dd><?=$t_actuacion?></dd>
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
                            <h2> <center>Datos de la dirección</center> </h2>
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
                            <h2> <center>Datos del quejoso(s)</center> </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condesed">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre completo</th>
                                            <th>Género</th>
                                            <th>Teléfono</th>
                                            <th>Correo</th>
                                            <th>Dirección</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($r['quejosos'] as $key => $quejoso): ?>
                                        <tr class="text-center">
                                            <td><?=(++$key)?></td>
                                            <td><?=$quejoso->nombre;?> <?=$quejoso->ap_pat;?> <?=$quejoso->ap_mat;?></td>
                                            <td><?=$quejoso->genero?></td>
                                            <td><?=$quejoso->phone?></td>
                                            <td><?=$quejoso->email?></td>
                                            <td><?=$quejoso->direccion?></td>
                                        </tr>
                                    <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h2> <center>Datos del presunto(s) infractor(es)</center> </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condesed">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre completo</th>
                                            <th>Género</th>
                                            <th>Cargo</th>
                                            <th>Procedencia</th>
                                            <th>Media filiación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($r['pr'] as $key => $quejoso): ?>
                                        <tr class="text-center">
                                            <td><?=(++$key)?></td>
                                            <td><?=$quejoso->nombre;?> <?=$quejoso->ap_pat;?> <?=$quejoso->ap_mat;?></td>
                                            <td><?=$quejoso->genero?></td>
                                            <td><?=$quejoso->n_cargo?></td>
                                            <td><?=$quejoso->procedencia?></td>
                                            <td><?=$quejoso->media_f?></td>
                                        </tr>
                                    <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h2> <center>Datos de las unidades</center> </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condesed">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Características</th>
                                            <th>Información</th>
                                            <th>Corporación</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($r['unidades'] as $key => $u): ?>
                                        <tr class="text-center">
                                            <td><?=(++$key)?></td>
                                            <td>
                                                <ol>
                                                    <li>MARCA: <?=$u->marca?></li>
                                                    <li>SUBMARCA: <?=$u->submarca?></li>
                                                    <li>TIPO DE AUTO: <?=$u->t_auto?></li>
                                                    <li>COLOR: <?=$u->color?></li>
                                                    <li>MODELO: <?=$u->modelo?></li>
                                                </ol>
                                            </td>
                                            <td>
                                                <ol>
                                                    <li>PLACAS: <?=$u->placa?></li>
                                                    <li>NIV: <?=$u->niv?></li>
                                                    <li>INVENTARIO: <?=$u->n_inventario?></li>
                                                </ol>
                                            </td>
                                            <td><?=$u->corporacion?></td>
                                        </tr>
                                    <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>   
                    <div class="row">
                        <div class="col-md-12">
                            <h2> <center>Datos de los semovientes</center> </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condesed">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tipo</th>
                                            <th>Raza</th>
                                            <th>Nombre</th>
                                            <th>Edad</th>
                                            <th>Color</th>
                                            <th>Inventario</th>
                                            <th>Corporación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($r['animales'] as $key => $a): ?>
                                        <tr class="text-center">
                                            <td><?=(++$key)?></td>
                                            <td><?=$a->tipo?></td>
                                            <td><?=$a->raza?></td>
                                            <td><?=$a->nombre?></td>
                                            <td><?=$a->edad?></td>
                                            <td><?=$a->color?></td>
                                            <td><?=$a->inv?></td>
                                            <td><?=$a->corporacion?></td>
                                        </tr>
                                    <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h2> <center>Datos de las armas</center> </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condesed">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tipo</th>
                                            <th>Matrícula</th>
                                            <th>Inventario</th>
                                            <th>Corporación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($r['armas'] as $key => $a): ?>
                                        <tr class="text-center">
                                            <td><?=(++$key)?></td>
                                            <td><?=$a->tipo?></td>
                                            <td><?=$a->matricula?></td>
                                            <td><?=$a->inv?></td>
                                            <td><?=$a->corporacion?></td>
                                        </tr>
                                    <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>              
                </div>
            </div>
        </div>
    </div>
</section>