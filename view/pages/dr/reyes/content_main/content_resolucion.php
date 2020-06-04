<?php
require_once 'model/Connection.php';
require_once 'model/DRModel.php';
$queja_id = $_GET['exp'];
$q = new DRModel;
$clave = $q->getClave($queja_id);
?>
<input type="hidden" id="nivel" name="nivel" value="<?=$_SESSION['nivel']?>">
<form action="#" id="frm_add_resolucion">
	<section class="content container-fluid">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="box">
	                <div class="box-header with-border">
	                    <h3 class="box-title">Registro de una resolución <u><?=$clave?></u></h3>
	                </div>
	                <div class="box-body">
	                	<input type="hidden" name="option" value="62">
	                	<input type="hidden" name="queja_id" value="<?=$_GET['exp']?>">
	                	<div id="div_resolucion"></div>
	                    <div class="row">
	                        <div class="col-md-3">
	                        	<label>Tipo de resolución</label>
	                        	<select name="sancion" id="sancion" class="form-control" required>
	                        		<option value="">...</option>
	                        		<option value="1">SANCIONADO</option>
	                        		<option value="2">NO SANCIONADO</option>
	                        	</select>
	                        </div>
	                        <div class="col-md-3">
	                        	<label>No. de Oficio (Interno)</label>
	                        	<input type="text" id="oficio" name="oficio" value="" class="form-control">
	                        	<input type="hidden" id="oficio_id" name="oficio_id" value="">
	                        </div>
	                        <div class="col-md-3">
	                        	<label>Fecha de sanción</label>
	                        	<input type="date" name="f_sancion" value="" class="form-control">
	                        </div>
	                        <div class="col-md-3">
	                        	<div class="form-group">
	                        		<label>Estado de la resolución</label>
	                        		<select name="estado" id="estado" class="form-control" required>
	                        			<option value="">...</option>
	                        			<option value="1">PENDIENTE</option>
	                        			<option value="2">RESUELTO</option>
	                        			<option value="3">CONCLUIDO</option>
	                        		</select>
	                        	</div>
	                        </div>
	                    </div>  
	                    <div class="row">
	                    	<div class="col-md-4">
	                    		<diuv class="form-group">
	                    			<label>No. de oficio (externo)</label>
	                    			<input type="text" name="oficio_e" class="form-control">
	                    		</diuv>
	                    	</div>
	                    	<div class="col-md-4">
	                    		<div class="form-group">
	                    			<label>Fecha del oficio</label>
	                    			<input type="date" name="f_oficio" value="" class="form-control">
	                    		</div>
	                    	</div>
	                    	<div class="col-md-4">
	                    		<div class="form-group">
	                    			<label>Fecha del acuse</label>
	                    			<input type="date" name="f_acuse" value="" class="form-control">
	                    		</div>
	                    	</div>
	                    </div> 
	                    <div class="row">
	                    	<div class="col-md-12">
	                    		<label>Descripción de resolución </label>
	                    		<textarea name="comentario" id="comentario" class="form-control" style="resize: vertical;max-height: 200px;"></textarea>
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
