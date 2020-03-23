<!--Consultar datos de la queja seleccionada -->
<?php
require_once 'model/Connection.php';
require_once 'model/QDModel.php';
$queja_id = $_GET['queja'];
$q = new QDModel;
$r = $q->getQDOnly($queja_id);
$colores = json_decode( $q->getColores() );
$municipios = json_decode( $q->getMunicipios() );
#print_r( $r[0]['quejosos'] );
?>



<section class="content container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                      <li class="active">
                        <a href="#tab_1" data-toggle="tab">
                            Seguimiento de presunto(s) responsable(s) 
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="row">
                            <div class="col-md-3">
                                <button type="button" class="btn btn-success btn-flat btn-block" data-toggle="modal" data-target="#modal_add_presunto">
                                    <i class="fa fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-resposive">
                                    <table class="table table-bordered table-hover table-condesed">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>NOMBRE COMPLETO</th>
                                                <th>SEXO </th>
                                                <th>PROCEDENCIA</th>
                                                <th>MUNICIPIO</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($r[0]['presuntos'] as $key => $presunto): ?>
                                                <tr id="tr_pres_<?=$presunto->id?>">
                                                    <td><?=$presunto->id?></td>
                                                    <td><?=$presunto->nombre?></td>
                                                    <td><?=$presunto->genero?></td>
                                                    <td><?=$presunto->procedencia?></td>
                                                    <td><?=$presunto->n_municipio?></td>
                                                    <td class="text-center">
                                                        <button type="button" onclick="delete_presunto(<?=$presunto->id?>,<?=$r[0]['id']?>);" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
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
        <div class="col-md-6">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                      <li class="active">
                        <a href="#tab_1" data-toggle="tab">
                            Seguimiento de unidad(es) implicada(s) 
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="row">
                            <div class="col-md-3">
                                <button type="button" class="btn btn-success btn-flat btn-block" data-toggle="modal" data-target="#modal_add_unidad">
                                    <i class="fa fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-resposive">
                                    <table class="table table-bordered table-hover table-condesed">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>PROCEDENCIA</th>
                                                <th>TIPO DE VEHÍCULO </th>
                                                <th>No. ECONOMICO</th>
                                                <th>PLACAS</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($r[0]['unidades'] as $key => $unidad): ?>
                                                <tr id="tr_uni_<?=$unidad->id?>">
                                                    <td><?=$unidad->id?></td>
                                                    <td><?=$unidad->procedencia?></td>
                                                    <td><?=$unidad->t_vehiculo?></td>
                                                    <td><?=$unidad->n_eco?></td>
                                                    <td><?=$unidad->placas?></td>
                                                    <td class="text-center">
                                                        <button type="button" onclick="delete_unidad(<?=$unidad->id?>,<?=$r[0]['id']?>);" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
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
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                      <li class="active">
                        <a href="#" data-toggle="tab">
                            Seguimiento de quejosos
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="">
                        <div class="row">
                            <div class="col-md-3">
                                <button type="button" class="btn btn-success btn-flat btn-block" data-toggle="modal" data-target="#modal_add_quejoso">
                                    <i class="fa fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-resposive">
                                    <table class="table table-bordered table-hover table-condesed">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>NOMBRE</th>
                                                <th width="30%">TELÉFONO/CORREO </th>
                                                <th>GENERO</th>
                                                <th>MUNICIPIO</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($r[0]['quejosos'] as $key => $quejoso): ?>
                                                <tr id="tr_que_<?=$quejoso->id?>">
                                                    <td><?=$quejoso->id?></td>
                                                    <td><?=$quejoso->nombre?></td>
                                                    <td>
                                                        <ol>
                                                            <li><?=$quejoso->telefono?></li>
                                                            <li><?=$quejoso->email?></li>
                                                        </ol>
                                                    </td>
                                                    <td><?=$quejoso->genero?></td>
                                                    <td><?=mb_strtoupper($quejoso->n_municipio,'utf-8')?></td>
                                                    <td class="text-center">
                                                        <button type="button" onclick="delete_quejoso(<?=$quejoso->id?>,<?=$r[0]['id']?>);" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
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
    </div>
</section>

<SCRIPT>
    window.addEventListener("beforeunload", function (e) {
      var confirmationMessage = "\o/";

  (e || window.event).returnValue = confirmationMessage; //Gecko + IE
  return confirmationMessage;                            //Webkit, Safari, Chrome
});
</SCRIPT>

