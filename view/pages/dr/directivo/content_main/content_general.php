<input type="hidden" id="nivel" name="nivel" value="<?=$_SESSION['nivel']?>">
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table id="tbl_sapa" class="table table-hover table-bordered">
                                    <caption class="bg-gray text-center">
                                        SUBDIRECCIÓN DE ANÁLISIS Y PROCEDIMIENTOS ADMINISTRATIVOS.
                                    </caption>
                                    <thead>
                                        <tr class="bg-gray">
                                            <th class="text-center"> ESTADO </th>
                                            <th class="text-center"> CANTIDAD </th>
                                            <th class="text-center"> MOSTRAR </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table id="tbl_sc" class="table table-hover table-bordered">
                                    <caption class="bg-gray text-center">
                                        SUBDIRECCIÓN DE LO CONTENCIOSO.
                                    </caption>
                                    <thead>
                                        <tr class="bg-gray">
                                            <th class="text-center"> ESTADO </th>
                                            <th class="text-center"> CANTIDAD </th>
                                            <th class="text-center"> MOSTRAR </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tbl_general" class="table table-hover table-bordered">
                                    <caption class="bg-gray"></caption>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>CLAVE EXP.</th>
                                            <th>OFICIO</th>
                                            <th>PROCEDENCIA</th>
                                            <th>EDO. PROCESAL</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>                   	
                </div>
            </div>
        </div>
    </div>
</section>
