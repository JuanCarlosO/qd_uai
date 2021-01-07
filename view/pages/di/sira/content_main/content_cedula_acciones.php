<?php
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';
if ( isset($_GET['accion']) ) {
    if ( (int)$_GET['accion'] > 0 ) {
        $accion = $_GET['accion'];
        $a = new SiraModel;
        $r = $a->getOnlyAcciones($accion);

        
    }else{
        echo '<script type="text/javascript"> document.location.href = "login.php"; </script>';
    }
}/*else{
    echo '<script type="text/javascript"> document.location.href = "login.php"; </script>';
}*/
##tipo de actuacion con asentos
?>

<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Detalle de la cédula de la Orden de Trabajo <b><?=$r[0]['clave']?></b></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h2> <center>Datos generales de las acciones de cumplimiento</center> </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>Fecha </dt>
                                <dd> <?=$r[0]['fecha']?> </dd>
                                <br>
                                <dt>Investigadores</dt>
                                <br>
                                <?php foreach ($r[0]['investigadores'] as $key => $inv): ?>
                                    <dd> <?=$inv->full_name?> </dd>
                                <?php endforeach ?>
                                <dt>Personal de Apoyo</dt>
                                <br>
                                <?php foreach ($r[0]['apoyo'] as $key => $apoyo): ?>
                                    <dd> <?=$apoyo->full_name?> </dd>
                                <?php endforeach ?>
                                <br>
                                <dt>Descripción </dt>
                                <dd> <p class="text-justify"><?=$r[0]['acciones']?></p> </dd>
                                
                                <?php if ( $r[0]['area'] == 'Operativos Secretaría de Seguridad'): ?>
                                    <?php foreach ($r[0]['n_area'] as $key => $ar): ?>
                                        <dt>Procedencia</dt>
                                        <dd><?=$ar->procedencia?></dd>
                                        <dt>Área</dt>
                                        <dd><?=$ar->area?></dd>
                                        <dd>Coordinación: <?=$ar->coordinacion?></dd>
                                        <dd>Subdirección: <?=$ar->subdireccion?></dd>
                                        <dd>Región: <?=$ar->region?></dd>
                                        <dd>Agrupamiento: <?=$ar->agrupamiento?></dd>      
                                    <?php endforeach ?>

                                <?php elseif ( $r[0]['area'] == 'Dirección de Policía de Tránsito'): ?>
                                    <?php foreach ($r[0]['n_area'] as $key => $ar): ?>
                                        <dt>Procedencia</dt>
                                        <dd><?=$ar->procedencia?></dd>
                                        <dt>Área</dt>
                                        <dd><?=$ar->area?></dd>
                                        <dd>Coordinación: <?=$ar->coordinacion?></dd>
                                        <dd>Agrupamiento: <?=$ar->agrupamiento?></dd>
                                    <?php endforeach ?>

                                <?php elseif ( $r[0]['area'] == 'Dirección General de Prevención y Reinserción Social'): ?>
                                    <?php foreach ($r[0]['n_area'] as $key => $ar): ?>
                                        <dt>Procedencia</dt>
                                        <dd><?=$ar->procedencia?></dd>
                                        <dt>Área</dt>
                                        <dd><?=$ar->area?></dd>
                                        <dt>CPRS</dt>
                                        <dd><?=$ar->agrupamiento?></dd>
                                    <?php endforeach ?>

                                <?php elseif ( $r[0]['area'] == 'Personal Administrativo'): ?>
                                    <?php foreach ($r[0]['n_area'] as $key => $ar): ?>
                                        <dt>Procedencia</dt>
                                        <dd><?=$ar->procedencia?></dd>
                                        <dt>Área</dt>
                                        <dd><?=$ar->area?></dd>
                                        <dd>Dirección: <?=$ar->direccion?></dd>
                                        <dd>Coordinación: <?=$ar->coordinacion?></dd>
                                        <dd>Subdirección: <?=$ar->subdireccion?></dd>
                                        <dd>Región: <?=$ar->region?></dd>
                                        <dd>Agrupamiento: <?=$ar->agrupamiento?></dd>
                                    <?php endforeach ?>
                                <?php endif ?>

                            </dl>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <h2> <center>Datos de la ubicación</center> </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="dl-horizontal">
                                <dt>Municipio</dt>
                                <dd> <?=$r[0]['n_municipio']?> </dd>
                                
                                <?php if ( $r[0]['t_orden'] == 'SUPERVISION'): ?>
                                    <dt>Red vial</dt>
                                    <dd><?=$r[0]['red_vial']?></dd>
                                    <dt>Referencia del lugar</dt>
                                    <dd><?=$r[0]['referencia_red']?></dd>
                                  <?php else: ?>
                                    <dt>Calle</dt>
                                    <dd><?=$r[0]['calle']?></dd>
                                    <dt>Número</dt>
                                    <dd><?=$r[0]['numero']?></dd>
                                    <dt>Colonia</dt>
                                    <dd><?=$r[0]['colonia']?></dd>
                                    <dt>Código Postal</dt>
                                    <dd><?=$r[0]['cp']?></dd>
                                <?php endif ?>

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
                                    <?php foreach ($r[0]['quejosos'] as $key => $quejoso): ?>
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
                                    <?php foreach ($r[0]['pr']  as $key => $quejoso): ?>
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
                                    <?php foreach ($r[0]['unidades']  as $key => $u): ?>
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
                                    <?php foreach ($r[0]['animales']  as $key => $a): ?>
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
                                    <?php foreach ($r[0]['armas']  as $key => $a): ?>
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