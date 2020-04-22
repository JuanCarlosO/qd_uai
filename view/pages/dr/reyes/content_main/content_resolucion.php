<input type="hidden" id="nivel" name="nivel" value="<?=$_SESSION['nivel']?>">
<form action="#" id="frm_add_resolucion">
	<section class="content container-fluid">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="box">
	                <div class="box-header with-border">
	                    <h3 class="box-title">Registro de una resolución</h3>
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
	                        <div class="col-md-5">
	                        	<label>No. de Oficio</label>
	                        	<input type="text" id="oficio" name="oficio" value="" class="form-control">
	                        	<input type="hidden" id="oficio_id" name="oficio_id" value="">
	                        </div>
	                        <div class="col-md-3">
	                        	<label>Fecha de sanción</label>
	                        	<input type="date" name="f_sancion" value="" class="form-control">
	                        </div>
	                        <div class="col-md-1"></div>
	                    </div>
	                    <div class="row">
	                    	<div class="col-md-4">
	                    		<div class="form-group">
	                    			<label> Fecha de apersonamiento</label>
	                    		</div>
	                    	</div>
	                    </div>   
	                    <div class="row">
	                    	<div class="col-md-12">
	                    		<label>Comentario </label>
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
