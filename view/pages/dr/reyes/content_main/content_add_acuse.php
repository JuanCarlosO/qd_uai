
<form action="#" id="frm_add_acuse">
    <input type="hidden" name="option" value="111">
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Registro de acuses y documentos</h3>
                    </div>
                    <div class="box-body">
                        <div id="div_acuse"></div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Buscar expediente</label>
                                    <input type="text" id="queja" name="queja" value="" required="" class="form-control">
                                    <input type="hidden" name="queja_id" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Buscar número de oficio</label>
                                    <input type="text" id="oficio" name="oficio" value="" required="" class="form-control">
                                    <input type="hidden" name="oficio_id" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha del oficio</label>
                                    <input type="date" id="f_oficio" name="f_oficio" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha del acuse</label>
                                    <input type="date" id="f_acuse" name="f_acuse" value="" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Documento</label>
                                    <input type="file" name="file" value="" class="form-control" required accept=".pdf">
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Asunto</label>
                                    <input type="text" name="asunto" value="" placeholder="Ej:Asunto del documento" class="form-control" required="" maxlength="255">
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Comentario</label>
                                    <textarea name="comentario" class="form-control" style="resize: vertical; max-height: 200px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success btn-flat btn-block">
                                    <i class="fa fa-floppy-o"></i> Guardar datos
                                </button>
                            </div>
                            <div class="col-md-4"></div>
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
