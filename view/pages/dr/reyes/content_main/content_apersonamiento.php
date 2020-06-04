<?php
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
$queja_id = $_GET['queja_id'];
$q = new DRModel;
$clave = $q->getClave($queja_id);
?>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulario de apersonamiento para <?=$clave?> </h3>
                </div>
                <div class="box-body">
                	<div id="apersonamiento"></div>
                	<form id="frm_apersonamiento" action="#" >
                		<input type="hidden" name="option" value="97">
                		<div class="row">
                			<div class="col-md-3">
                				<div class="form-group">
                					<label>Oficio</label>
                					<input type="text" id="oficio" name="oficio" value="" class="form-control">
                					<input type="hidden" id="oficio_id" name="oficio_id" value="" class="form-control">
                				</div>
                			</div>
                			<div class="col-md-3">
                				<div class="form-group">
                					<label>Fecha del oficio</label>
                					<input type="date" name="f_oficio" value="" class="form-control" required="">
                				</div>
                			</div>
                			<div class="col-md-3">
                				<div class="form-group">
                					<label>Fecha del acuse</label>
                					<input type="date" name="f_acuse" value="" class="form-control">
                				</div>
                			</div>
                			<div class="col-md-3">
                				<div class="form-group">
                					<label>Fecha del apersonamiento</label>
                					<input type="date" name="f_apersonamiento" value="" class="form-control">
                				</div>
                			</div>
                		</div>
                		<div class="row">
                			<div class="col-md-12">
                				<div class="form-group">
                					<label>Observaciones</label>
                					<textarea name="comentario" class="form-control" style="resize: vertical;max-height: 200px;"></textarea>
                				</div>
                			</div>
                		</div>
                		<div class="row">
                			<div class="col-md-4"></div>
                			<div class="col-md-4">
                				<button class="btn btn-success btn-flat btn-block">
                					Guardar <i class="fa fa-floppy-o"></i>
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
