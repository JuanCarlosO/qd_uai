<form action="#" method="post" id="frm_aviso">
	<input type="hidden" name="option" value="16">
	<input type="hidden" id="conducta_id" name="conducta_id" value="">
	<div class="modal modal-danger fade" id="modal_aviso">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title text-center">¿Está seguro que desea eliminar la conducta principal?</h4>
				</div>
				<div class="modal-body">
					<div id=""></div>
					<div class="row">
						<div class="col-md-12">
							<p class="text-justify">
								<b>Políticas internas para la reasignación de conductas: </b> 
								<ol>
									<li>Eliminar esta conducta principal provocará que el expediente no sea contado estadísticamente, sino con la que se establezca a partir de este momento.</li>
									
									<li>
										El expediente no podrá ser turnado a la Dirección de Responsabilidades en Asuntos Internos, si no se cuenta con una conducta principal.
									</li>
								</ol>							</p>
						</div>
					</div>	
					<div class="checkbox">
						<label for="acepta">
							<input type="checkbox" id="acepta" name="acepta" value="1"> <b>Acepto los términos</b>
						</label>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-default btn-flat pull-right">Confirmar</button>
				</div>
			</div>
		</div>
	</div>
</form>