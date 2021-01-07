<?php
#Consultar los expedientes 
require_once 'model/Connection.php';
require_once 'model/QDModel.php';

$q = new QDModel;
$r = $q->getExpAbogados();
#print_r($r);
?>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-condesed">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Nombre del abogado</th>
                                            <th class="text-center">
                                            Cantidad y Estado de Expedientes
                                            </th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach ($r as $key => $abogado): ?>
                                            <tr class="text-center">
                                                <td> <?=($i++)?> </td>
                                                <td><?=$abogado['nombre']?></td>
                                                <td>
                                                    <ol>
                                                        <?php foreach ($abogado['segmento'] as $key => $seg): ?>
                                                            <?php if ( $seg->total == 0 ): ?>
                                                                <li>
                                                                    <a href="javascript:cero();" >
                                                                        <label> <?=$seg->estado?>: </label> <?=$seg->total?> 
                                                                    </a>
                                                                </li>
                                                            <?php else: ?>
                                                                <li>
                                                                    <a href="index.php?menu=expedientes&person=<?=$abogado['person_id']?>&estado=<?=$seg->id?>">
                                                                        <label> <?=$seg->estado?>: </label><?=$seg->total?>
                                                                    </a>
                                                                </li>
                                                            <?php endif ?>                                      
                                                        <?php endforeach ?>
                                                    </ol>
                                                </td>
                                                <td>
                                                    <a href="index.php?menu=expedientes&person=<?=$abogado['person_id']?>">
                                                        <?=$abogado['total']?>
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