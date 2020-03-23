<form action="#" id="frm_ot_add_obs" method="post" >
	<div class="modal fade" id="modal_add_obs">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Adjuntar Documento al Acta</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Colocar un estado a la OIN</label>
								<select id="estado" name="estado" class="form-control" required>
									<option value="">...</option>
									<option value="">Cumplida</option>
									<option value="">Parcial sin resultado</option>
									<option value="">Parcial con resultado</option>
									<option value="">Cumplida sin resultado</option>
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
								<label>Quien y Cuando comenta</label>
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
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-primary">
						<i class="fa fa-floppy-o"></i> Guardar datos
					</button>
				</div>
			</div>
		</div>
	</div>
</form>
