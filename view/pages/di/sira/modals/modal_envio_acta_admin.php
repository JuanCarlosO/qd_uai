<style type="text/css">
  ul.ui-autocomplete {
    z-index: 1100;
  }
</style>
<div class="modal modal-default fade" id="modal_envio_acta_admin">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Datos del oficio</h4>
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
                
        <form action="#" id="frm_add_seguimiento_acta" method="post">
          <input type="hidden" name="option" value="148">
          <input type="hidden" name="acta_id" id="acta_id" value="">
          <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Fecha del oficio <i class="fa fa-asterisk text-red"></i></label>
                        <input type="date" id="f_oficio" name="f_oficio" class="form-control" required="">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Fecha de acuse/recepción <i class="fa fa-asterisk text-red"></i></label>
                        <input type="date" id="f_recepcion" name="f_recepcion" class="form-control" required="">
                    </div>
                </div>
            </div>
          <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Número de oficio <i class="fa fa-asterisk text-red"></i></label>
                        <input type="text" id="oficio" name="oficio" class="form-control" required="" autocomplete="off">
                        <input type="hidden" id="oficio_id" name="oficio_id" value="">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Destinatario</label>
                        <input type="text" class="form-control" id="destinatario_ofi" name="destinatario_ofi" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Cargo del destinatario</label>
                        <input type="text" class="form-control" id="cargo_remi" name="cargo_remi" readonly>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <label>Asunto</label>
                        <input type="text" class="form-control" id="asunto_ofi" name="asunto_ofi" readonly>
                    </div>
                </div>
            </div>

          <div class="row">
            <div class="col-md-6">
              <button type="button" class="btn btn-danger pull-left btn-flat" data-dismiss="modal">Cancelar</button>
            </div>
            <div class="col-md-6">
              <button type="submit" class="btn btn-success btn-flat pull-right"> <i class="fa fa-floppy-o"></i> Enviar </button>
            </div>
          </div>
        </form>
      </div>
        
    </div>
  </div>
</div>