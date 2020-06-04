<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                    <input type="hidden" name="nivel" id="nivel" value="<?=$_SESSION['nivel']?>">
                    <div id="alerta"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="70px">Oficios nuevos:</th>
                                    <td class="bg-green" width="100px"></td>
                                    <td></td>
                                    <th width="70px">Oficios enviados:</th>
                                    <td class="bg-aqua" width="100px"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <div id="correspondencia"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
