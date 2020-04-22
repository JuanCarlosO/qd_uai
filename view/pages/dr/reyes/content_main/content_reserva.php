
<form action="#" id="frm_reserva">
    <input type="hidden" name="option" value="65">
    <input type="hidden" name="queja_id" value="<?=$_GET['exp']?>">
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Registro reserva del expediente</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div_reserva"></div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Oficio</label>
                                    <input type="text" id="oficio" name="oficio" value="" class="form-control">
                                    <input type="hidden" id="oficio_id" name="oficio_id" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha oficio</label>
                                    <input type="date" name="f_oficio" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha de reserva</label>
                                    <input type="date" name="f_reserva" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Duración de la reserva</label>
                                    <select name="duracion" class="form-control" required>
                                        <option value="">...</option>
                                        <option value="1095">3 años</option>
                                        <option value="1825">5 años</option>
                                        <option value="3285">9 años</option>
                                    </select>
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
