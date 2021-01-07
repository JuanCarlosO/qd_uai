
<form id="frm_add_respuesta" action="#" method="POST">
	<input type="hidden" id="option" name="option" value="118">
	<input type="hidden" id="pre_id" name="pre_id" value="118">
	<div class="modal fade " id="modal_add_respuesta">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">×</span>
		        </button>
	        	<h4 class="modal-title"> <center>REGISTRO DE RESPUESTA</center> </h4>
	      	</div>
	      	<div class="modal-body">
		      	<div  class="row">
		      		<div class="col-md-12">
		      			<div id="alerta" class="alert alert-dismissible hidden">
			                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			                <h4><i class="icon fa fa-check"></i> <label id="estado"></label> </h4>
			            	<p id="message"></p>
			            </div>
		      		</div>
		      	</div>
		      	<div class="row">
		      		<div class="col-md-3">
		      			<div class="form-group">
		      				<label>Respuesta</label>
		      				<input type="text" name="respuesta" value="" class="form-control" >
		      			</div>
		      		</div>	
		      	</div>
		      	
	      	</div>
	      	<div class="modal-footer">
	      		<button type="button" class="btn btn-flat btn-danger pull-left" data-dismiss="modal">Cerrar</button>
	      		<button type="submit" class="btn btn-success btn-flat"><i class="fa fa-floppy-o"></i> Guardar</button>
	      	</div>
	    </div>
	  </div>
	</div>
</form>