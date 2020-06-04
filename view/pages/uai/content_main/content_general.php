<?php
if ( $_SESSION['perfil'] == 'QDP' ) { $t_as = 1; }
if ( $_SESSION['perfil'] == 'QDNP' ) { $t_as = 2; }
?>
<!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Menú principal</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="div_general"></div>
                        <div class="row">
                            
                            <div class="col-md-9">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-4 text-right">Buscador de expediente por clave: </label>
                                        <div class="col-md-8">
                                            <input type="text" id="clave" name="clave" value="" placeholder="Ej: 210D11000000/001/2020" class="form-control">
                                            <input type="hidden" id="clave_id" name="clave_id" value="">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <form id="frm_coincidencias" action="#" class="form-horizontal">
                                    <input type="hidden" name="option" value="75">
                                    <div class="form-group">
                                        <label class="col-md-4 text-right">Buscador de coincidencias:</label>
                                        <div class="input-group col-md-8">
                                            <input type="text" id="palabra" name="palabra" value="" placeholder="Ejemplo: La patrulla azul" class="form-control" required>
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-success btn-flat">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Buscar por año:</label>
                                    <select id="year" name="year" class="form-control">
                                        <option value="" selected>Todos los años disponibles</option>
                                        <?php
                                        $year = 2017;
                                        $right = date('Y');
                                        $diff = $right - $year;
                                        for ($i=0; $i < $diff; $i++) { 
                                            echo "<option value='".($right-$i)."'>".($right-$i)."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table id="dash" class="table table-hover table-condesed table-bordered">
                                        <caption class="bg-gray text-center">CONTADORES DE D. INVESTIGACIÓN</caption>
                                        <thead>
                                            <tr class="bg-gray">
                                                <th width="50%">Estado del expediente</th>
                                                <th width="25%">Total</th>
                                                <th width="25%">Ver listado</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table id="dash_a" class="table table-hover table-condesed table-bordered">
                                        <caption class="bg-gray text-center ">CONTADORES DE ACTUACIONES</caption>
                                        <thead>
                                            <tr class="bg-gray">
                                                <th width="50%">Tipo de actuación</th>
                                                <th width="25%">Total</th>
                                                <th width="25%">Ver listado</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
                        </div>
                        
                        <div id="qd_estado" class="hidden">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="tbl_ee" class="table table-hover table-bordered">
                                            <caption class="text-center bg-gray">
                                                LISTADO DE EXPEDIENTES (QUEJAS Y DENUNCIAS)
                                            </caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th width="10">#</th>
                                                    <th width="30">Clave del expediente</th>
                                                    <th width="15">Tipo asunto</th>
                                                    <th width="15">Tipo de tramite</th>
                                                    <th width="15">Procedencia</th>
                                                    <th width="15">Fecha/hora de hechos</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="actas" class="hidden">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="tbl_actas" class="table table-hover table-bordered">
                                            <caption class="text-center bg-gray">
                                                LISTADO DE ACTUACIONES (SIRA)
                                            </caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th width="10">#</th>
                                                    <th width="30">Clave del acta</th>
                                                    <th width="15">Quien elaboró</th>
                                                    <th width="15">Fecha del acta</th>
                                                    <th width="15">Procedencia</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="div_oins" class="hidden">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="tbl_oins" class="table table-hover table-bordered">
                                            <caption class="text-center bg-gray">
                                                LISTADO DE ORDENES DE TRABAJO
                                            </caption>
                                            <thead>
                                                <tr class="bg-gray">
                                                    <th>#</th>
                                                    <th>Número de orden</th>
                                                    <th>Número de oficio</th>
                                                    <th>Tipo de orden</th>
                                                    <th>Estado </th>
                                                    <th>Fecha de registro de O.T. </th>
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
        </div>
    </section>
    
    