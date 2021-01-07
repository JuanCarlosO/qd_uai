<form id="frm_estatus" method="post" action="#">
	<input type="hidden" name="option" value="158">
	<input type="hidden" id="id" name="id" value="<?=$_GET['ot']?>">	
	<div class="modal fade" id="modal_estatus">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					<h4 class="modal-title">Recomendación</h4>
				</div>
				<div class="modal-body">
					<div  class="row">
						<div class="col-md-12">
							<div id="alert_avisos" class="alert alert-dismissible hidden">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h4><i class="icon fa fa-check"></i> <label id="estado_avisos"></label> </h4>
								<p id="message_avisos"></p>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Estatus de las recomendaciones</label>
								<select id="atendido" name="atendido" class="form-control" required>
									<option value="">...</option>
									<option value="1">CUMPLIDAS</option>
									<option value="2">PARCIAL</option>
									<option value="3">PENDIENTE</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Observaciones</label>
								<textarea name="desc" class="form-control" rows="4" style="resize: vertical;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger pull-left btn-flat" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-success btn-flat pull-right" > 
						<i class="fa fa-floppy-o"></i> 
						Resgistrar
					</button>
				</div>
			</div>
		</div>
	</div>
</form>