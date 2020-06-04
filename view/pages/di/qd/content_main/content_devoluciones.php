<?php
require_once 'model/Connection.php';
require_once 'model/QDModel.php';
$q = new QDModel;
$devoluciones = $q->getDevoluciones();
?>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                    	<div class="col-md-12">
                    		<div class="table-responsive">
                                <table id="tbl_devoluciones" class="table table-hover table-bordered">
                                    <thead class="bg-gray">
                                        <tr>
                                            <th>ID</th>
                                            <th>CLAVE DEL EXPEDIENTE</th>
                                            <th>FECHA DE DEVOLUCIÓN</th>
                                            <th>FECHA DEL OFICIO</th>
                                            <th>OFICIO</th>
                                            <th>MOTIVO</th>
                                            <th>TURNAR A </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($devoluciones as $key => $d): ?>
                                        <tr>
                                            <td><?=$d->id?></td>
                                            <td><?=$d->cve_exp?></td>
                                            <td><?=$d->f_devolucion?></td>
                                            <td><?=$d->f_oficio?></td>
                                            <td><?=$d->oficio?></td>
                                            <td><?=$d->motivo?></td>
                                            <td>
                                                <a href="index.php?menu=turnar&queja=<?=$d->queja_id?>&origen=<?=$d->id?>" class="btn btn-success btn-flat">
                                                    <i class="fa fa-mail-reply-all"></i>
                                                </a>
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
</section>
