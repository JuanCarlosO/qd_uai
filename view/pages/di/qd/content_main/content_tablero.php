<input type="hidden" id="nivel" name="nivel" value="<?=$_SESSION['nivel']?>">
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                    <?php if ( ($_SESSION['perfil'] == 'DI' AND $_SESSION['nivel'] == 'DIRECTOR') || ($_SESSION['perfil'] == 'QDP' AND $_SESSION['nivel'] == 'SUBDIRECTOR') ): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table id="tbl_edos" class="table table-hover table-bordered">
                                        <caption class="bg-gray text-center">
                                            ESTADOS DE LOS EXPEDIENTE
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
                                    <table id="tbl_abogados" class="table table-hover table-bordered">
                                        <caption class="bg-gray text-center">
                                            EXPEDIENTES ASIGNADOS A LOS ABOGADOS
                                        </caption>
                                        <thead>
                                            <tr class="bg-gray">
                                                <th class="text-center"> ABOGADO </th>
                                                <th class="text-center"> CANTIDAD </th>
                                                <th class="text-center"> MOSTRAR </th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                    <?php if (($_SESSION['perfil'] == 'DI' AND $_SESSION['nivel'] == 'DIRECTOR') || ($_SESSION['perfil'] == 'QDNP' AND $_SESSION['nivel'] == 'SUBDIRECTOR')): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table id="tbl_ordenes" class="table table-hover table-bordered">
                                        <caption class="bg-gray text-center">CONTADOR DE ORDENES DE TRABAJO</caption>
                                        <thead>
                                            <tr class="bg-gray">
                                                <th class="text-center">TIPO DE ORDEN</th>
                                                <th class="text-center">TOTAL</th>
                                                <th class="text-center">MOSTRAR</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table id="tbl_actas" class="table table-hover table-bordered">
                                        <caption class="bg-gray text-center">CONTADOR DE ACTUACIONES</caption>
                                        <thead>
                                            <tr class="bg-gray">
                                                <th class="text-center">TIPO ACTUACIÓN</th>
                                                <th class="text-center">TOTAL</th>
                                                <th class="text-center">MOSTRAR</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                     
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tbl_general" class="table table-hover table-bordered">
                                    <caption class="bg-gray"></caption>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>CLAVE EXP.</th>
                                            <th>ESTADO</th>
                                            <th>PROCEDENCIA</th>
                                            <th>TIPO DE ASUNTO </th>
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
