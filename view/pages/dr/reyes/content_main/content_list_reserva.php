<?php
#generar la consulta de la info
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
if ( isset($_GET['exp']) ) {
    $title = "Listado de reservas";
    $queja_id = $_GET['exp'];
    $q = new DRModel;
    $data = $q->getReserva($queja_id);
    $x = (int) $data->duracion;
    $y = (int) $data->control;
    $diff = $x - $y;$bg="";
    if( $diff <= 100 ){
        $bg = "bg-red";
    }else{
        $bg = "bg-green";
    }
}else{
    $title = "SIN RESERVAS REGISTRADAS";
}
?>

<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"> <?=$title;?> </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="div_list_reserva"></div>
                    <div class="row">
                        <div class="col-md-3">
                            <i class="fa fa-circle text-red"></i> <label> INDICA PRÓXIMO A EXPIRAR</label>
                        </div>
                        <div class="col-md-3">
                            <i class="fa fa-circle text-green"></i> <label> INDICA SE ENCUENTRA A TIEMPO</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th>Expediente</th>
                                            <th>Oficio</th>
                                            <th>Fechas</th>
                                            <th>Duración</th>
                                            <th>Transcurridos</th>
                                            <th>Descripción</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="<?=$bg?>">
                                            <td><?=$data->clave?></td>
                                            <td><?=$data->oficio?></td>
                                            <td>
                                                <ul>
                                                    <li><label>F. Oficio: </label> <?=$data->f_oficio?></li>
                                                    <li><label>F. Reserva: </label> <?=$data->f_reserva?></li>
                                                </ul>
                                            </td>
                                            <td><?=$data->duracion?></td>
                                            <td><?=$data->control?></td>
                                            <td><?=$data->comentario?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-flat  dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-gear"></i>
                                                    </button>
                                                  <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="#" onclick="open_modal( 'modal_add_improcedencia','<?=$queja_id?>', 'queja_id');">Mandar a improcedencia</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" onclick="open_modal( 'modal_regresar_exp','<?=$queja_id?>', 'queja_id');">
                                                        Regresar a D.I.
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        </tr>
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