<form action="#" id="frm_ot_add_obs" method="post" >
	<input type="hidden" name="option" value="159">
	<input type="hidden" id="ot_id" name="ot_id" value="">
	<div class="modal fade" id="modal_add_obs">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Cambiar estatus de la Orden de Trabajo</h4>
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
						<div class="col-md-12">
							<div class="form-group">
								<label>Colocar un estado a la orden de trabajo</label>
								<select id="estado" name="estado" class="form-control" required>
									<option value="">...</option>
									<option value="1">Cumplida</option>
									<option value="2">Parcial sin resultado</option>
									<option value="3">Parcial con resultado</option>
									<option value="4">Cumplida sin resultado</option>
								</select>
							</div>
						</div>
					</div>	
					<?php
					$txt = "HOY ".date('Y-m-d')." " .$_SESSION['name']." DICE: ".PHP_EOL;
					?>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Quién y Cuándo comenta</label>
								<input type="text" id="txt_complemento" name="txt_complemento" value="<?=$txt?>" class="form-control" placeholder="" readonly="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Observaciones</label>
								<textarea id="observaciones" name="observaciones" class="form-control" rows="5" style="resize: vertical; max-height: 250px;"></textarea>
							</div>
						</div>
					</div>				
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-floppy-o"></i> Guardar datos
					</button>
				</div>
			</div>
		</div>
	</div>
</form>