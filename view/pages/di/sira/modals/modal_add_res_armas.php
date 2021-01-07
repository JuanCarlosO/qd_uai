<form id="frm_add_res_armas" action="#" method="POST">
	<input type="hidden" id="option" name="option" value="121">
	<input type="hidden" id="pre_id_a" name="pre_id_a" value="121">
	<div class="modal fade " id="modal_add_res_armas">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">×</span>
		        </button>
	        	<h4 class="modal-title"> <center>REGISTRO DE RESPUESTAS</center> </h4>
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
		      				<label>Total de armas:</label>
		      				<input type="text" name="total" value="" class="form-control" >
		      			</div>
		      		</div>	
		      	</div>
		      	<div class="row">
		      		<div class="col-md-3">
		      			<div class="form-group">
		      				<label>Cortas:</label>
		      				<input type="text" name="cortas" value="" class="form-control" >
		      			</div>
		      		</div>	
		      	</div>
		      	<div class="row">
		      		<div class="col-md-3">
		      			<div class="form-group">
		      				<label>Largas:</label>
		      				<input type="text" name="largas" value="" class="form-control" >
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