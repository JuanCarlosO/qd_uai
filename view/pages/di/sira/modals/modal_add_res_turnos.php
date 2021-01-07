<form id="frm_add_res_turnos" action="#" method="POST">
	<input type="hidden" id="option" name="option" value="120">
	<input type="hidden" id="pre_id_t" name="pre_id_t" value="120">
	<div class="modal fade " id="modal_add_res_turnos">
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
		      				<label>Total de Servidores Públicos</label>
		      				<input type="text" name="total" value="" class="form-control" >
		      			</div>
		      		</div>	
		      	</div>
		      	<div class="row">
		      		<div class="col-md-3">
		      			<div class="form-group">
		      				<label>Turno Matutino:</label>
		      				<input type="text" name="matutino" value="" class="form-control" >
		      			</div>
		      		</div>	
		      	</div>
		      	<div class="row">
		      		<div class="col-md-3">
		      			<div class="form-group">
		      				<label>Turno Vespertino:</label>
		      				<input type="text" name="vespertino" value="" class="form-control" >
		      			</div>
		      		</div>	
		      	</div>
		      	<div class="row">
		      		<div class="col-md-3">
		      			<div class="form-group">
		      				<label>Turno Nocturno:</label>
		      				<input type="text" name="nocturno" value="" class="form-control" >
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