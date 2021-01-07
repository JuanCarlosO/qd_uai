<?php
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
$queja_id = $_GET['exp'];
$q = new DRModel;
$clave = $q->getClave($queja_id);
?>
<input type="hidden" id="nivel" name="nivel" value="<?=$_SESSION['nivel']?>">
<form action="#" id="frm_add_demanda">
	<input type="hidden" name="option" value="63">
	<input type="hidden" name="queja_id" value="<?=$_GET['exp']?>">
	<section class="content container-fluid">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="box">
	                <div class="box-header with-border">
	                    <h3 class="box-title">Demanda <u><?=$clave?></u></h3>
	                </div>
	                <!-- /.box-header -->
	                <div class="box-body">
						<div id="div_demanda"></div>
	                    <div class="row">
	                        <div class="col-md-3">
	                        	<label>Tipo de demanda</label>
	                        	<select name="t_demanda" id="" class="form-control">
	                        		<option value="">...</option>
	                        		<option value="1">Impugnación sala regional</option>
	                        		<option value="2">Impugnación sala superior</option>
	                        	</select>
	                        </div>
	                        <div class="col-md-5">
	                        	<label>Oficio</label>
	                        	<input type="text" name="oficio" id="oficio" value="" class="form-control">
	                        	<input type="hidden" name="oficio_id" id="oficio_id" value="" class="form-control">
	                        </div>
	                        <div class="col-md-4">
	                        	<label>Fecha del Oficio</label>
	                        	<input type="date" name="f_oficio" value="" class="form-control">
	                        </div>
	                    </div>
	                    <div class="row">
	                    	<div class="col-md-4">
	                    		<label>Fecha del acuse</label>
	                    		<input type="date" name="f_acuse" value="" class="form-control">
	                    	</div>
	                    	<div class="col-md-4">
	                    		<label>Nombre de la Autoridad</label>
	                    		<input type="text" id="dep" name="dep" value="" class="form-control">
	                    	</div>
	                    </div>
	                    <div class="row">
	                    	<div class="col-md-12">
	                    		<label>Comentarios</label>
	                    		<textarea name="comentario" id="comentario" class="form-control" style="resize: vertical;max-height: 200px; min-height: 50px;"></textarea>
	                    	</div>
	                    </div>
	                    <br> 
	                    <div class="row">
	                    	<div class="col-md-4"></div>
	                    	<div class="col-md-4">
	                    		<button type="submit" class="btn btn-success btn-flat btn-block">
	                    			<i class="fa fa-floppy-o"></i> Guardar datos
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
