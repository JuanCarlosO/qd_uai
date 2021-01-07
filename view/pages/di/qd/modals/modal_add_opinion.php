<form action="#" method="post" id="frm_add_opinion">
	<input type="hidden" name="option" value="110">
	<input type="hidden" name="queja_id" id="queja_id" value="">
	<input type="hidden" name="personal_id" value="<?=$_SESSION['id']?>">
	<div class="modal fade" id="modal_add_opinion">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Agregar un comentario al expediente seleccionado </h4>
				</div>
				<div class="modal-body">
					<div id="m_opinion"></div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="comentario">Comentario final del expediente</label>
								<textarea name="comentario" class="form-control" style="resize: vertical;max-height: 250px;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat pull-left">
						<i class="fa  fa-paint-brush"></i>
						Limpiar campos
					</button>
					<button type="submit" class="btn btn-success btn-flat pull-right">
						<i class="fa fa-floppy-o"></i>
						Guardar comentario
					</button>
				</div>
			</div>
		</div>
	</div>
</form>