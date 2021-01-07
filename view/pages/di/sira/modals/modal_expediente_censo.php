<style type="text/css">
  ul.ui-autocomplete {
    z-index: 1100;
  }
</style>
<div class="modal modal-default fade" id="modal_expediente_censo">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Relacionar un expediente al Censo</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-dismissible hidden " id="modal_expediente_alert">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-info"></i> <label id="modal_estado"></label></h4>
              <p id="modal_message"></p>
            </div>
          </div>
        </div>
                
        <form action="#" id="frm_censo_expediente" method="post">
          <input type="hidden" name="option" value="164">
          <input type="hidden" name="censo_id" id="censo_id" value="">
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label for="expediente">Número de expediente</label>
                <input type="text" id="expediente" name="expediente" value="" class="form-control" required autocomplete="off">
                <input type="hidden" name="expediente_id" id="expediente_id" value="">
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label for="oficio">Número de oficio de solicitud</label>
                <input type="text" id="oficio" name="oficio" value="" class="form-control" required autocomplete="off">
                <input type="hidden" name="oficio_id" id="oficio_id" value="">
              </div>
            </div>
            <div class="col-md-12">	
      				<div class="form-group">
      					<label>Comentarios</label>
      					<textarea class="form-control" id="comentario" name="comentario" style="resize: vertical; max-height: 200px;"></textarea>
      				</div>	
      			</div>
          </div>  
          <div class="row">
            <div class="col-md-6">
              <button type="button" class="btn btn-danger pull-left btn-flat" data-dismiss="modal">Cancelar</button>
            </div>
            <div class="col-md-6">
              <button type="submit" class="btn btn-success btn-flat pull-right"> <i class="fa fa-floppy-o"></i> Guardar</button>
            </div>
          </div>
        </form>
      </div>
        
    </div>
  </div>
</div>