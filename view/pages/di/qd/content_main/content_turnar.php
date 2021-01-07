<?php
require_once 'model/Connection.php';
require_once 'model/QDModel.php';
$error = false;
if (isset($_GET['queja'])) {
    $queja_id = $_GET['queja'];
    $q = new QDModel;
    $r = (object)$q->getQDOnly($queja_id)[0];
}
?>
<?php if ( !$error ): ?>
    <form id="frm_add_turno" action="#" method="post"  enctype="multipart/form-data">
        <input type="hidden" name="option" value="76">
        <input type="hidden" name="queja_id" value="<?=$_GET['queja']?>">
        <input type="hidden" name="origen" value="<?=( isset($_GET['origen']) ) ? $_GET['origen'] : 0 ;?>">
        <input type="hidden" name="estado" value="7">
        <input type="hidden" name="sp_id" value="61"><!-- Directora de Respo -->
        <section class="content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Turnar <b><?=$r->cve_exp?></b> a la Dirección de Responsabilidades en Asuntos Internos.</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="div_turno"></div>
                            <div class="row">
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fecha de turnado</label>
                                        <input type="date" name="f_turnado" value="<?=date('Y-m-d')?>" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Número de oficio</label>
                                        <input type="text" id="oficio_envio" name="oficio_envio" value=""  class="form-control">
                                        <input type="hidden" id="oficio_e_id" name="oficio_e_id" value=""  class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Buscar documento</label>
                                        <input type="file" id="file" name="file" value="" class="form-control" accept=".pdf">
                                    </div>
                                </div>
                            </div>      
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="checkbox text-justify">
                                        <label for="acepto">
                                            <input type="checkbox" class="" id="acepto" name="acepto" value="1">
                                            <b>Acepto que:</b> la información que esta por turnarse a la Dirección de Responsabilidades en Asuntos Internos se encuentra actualizada y el procedimiento de investigación a sido concluido
                                        </label> 
                                        <p>
                                            <div class="col-md-6">
                                                <a href="index.php?menu=cedula&queja=<?=$_GET['queja'];?>" target="_blank">Deseo revisar</a>
                                            </div>
                                            <div class="col-md-6 ">
                                                <a href="index.php?menu=m_queja&queja=<?=$_GET['queja'];?>" class="pull-right" target="_blank">Modificar</a>
                                            </div>
                                        </p>                                   
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Observaciones</label>
                                    <textarea name="comentario" class="form-control" style="resize: vertical;max-height: 200px;"></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <button id="btn_turnar" type="submit" class="btn btn-success btn-flat btn-block">
                                        <i class="fa fa-floppy-o"></i> Guardar turno
                                    </button>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>

<?php endif ?>
