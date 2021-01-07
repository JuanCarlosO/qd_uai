
<section class="content container-fluid  connectedSortable">
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs pull-right ">
            <li class="active"><a href="#sc" data-toggle="tab">
            Subdirección de lo Contencioso
            </a></li>
            <li><a href="#sapa" data-toggle="tab">Subdirección de Análisis y Procedimientos Administrativos</a></li>
        </ul>
        <div class="tab-content no-padding">
            <div class="chart tab-pane active" id="sc">
                <div class="">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-3 col-xs-6 col-md-2">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3 id="cuenta_chyj">0</h3>
                                        <p>Enviados a la CHyJ</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="javascript:cargarTablas();" class="small-box-footer">Cargar en tablas<i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3 id="cuenta_apersona">0</h3>
                                        <p>Expedientes en CHyJ con apersonamiento</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table id="tbl_res_chyj" class="table table-hover table-bordered">
                                    <caption class="bg-gray text-center ">
                                        <b>RESULTADOS DE EXPEDIENTES ENVIADOS A LA CHyJ</b>
                                    </caption>
                                    <thead>
                                        <tr class="bg-gray">
                                            <th width="40%" class="text-center">TIPO</th>
                                            <th width="30%" class="text-center">CANTIDAD</th>
                                            <th width="30%" class="text-center">MOSTRAR INFORMACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table id="por_demanda" class="table table-hover table-bordered">
                                    <caption class="bg-gray text-center ">
                                        <b>CONTADORES DE EXPEDIENTES POR TIPO DE DEMANDA</b>
                                    </caption>
                                    <thead>
                                        <tr class="bg-gray">
                                            <th width="40%" class="text-center">TIPO</th>
                                            <th width="30%" class="text-center">CANTIDAD</th>
                                            <th width="30%" class="text-center">MOSTRAR INFORMACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table id="res_prim_dem" class="table table-hover table-bordered">
                                    <caption class="bg-gray text-center ">
                                        <b>RESOLUCIONES DE IMPUGNACIÓN SALA REGIONAL</b>
                                    </caption>
                                    <thead>
                                        <tr class="bg-gray">
                                            <th width="40%" class="text-center">TIPO</th>
                                            <th width="30%" class="text-center">CANTIDAD</th>
                                            <th width="30%" class="text-center">MOSTRAR INFORMACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table id="res_rr_dem" class="table table-hover table-bordered">
                                    <caption class="bg-gray text-center ">
                                        <b>RESOLUCIONES DEL RECURSO DE REVISIÓN</b>
                                    </caption>
                                    <thead>
                                        <tr class="bg-gray">
                                            <th width="40%" class="text-center">TIPO</th>
                                            <th width="30%" class="text-center">CANTIDAD</th>
                                            <th width="30%" class="text-center">MOSTRAR INFORMACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tbl_global" class="table table-bordered table-hover">
                                        <caption class=" bg-gray text-center"> LISTADO DE EXPEDIENTES (SUBDIRECCIÓN DE LO CONTENCIOSO)</caption>
                                        <thead>
                                            <tr class="bg-gray">
                                                <th class="text-center">#</th>
                                                <th class="text-center">Número_expediente</th>
                                                <th class="text-center">Fecha resolución CHyJ</th>
                                                <th class="text-center">Resolución TRIJAEM</th>
                                                <th class="text-center">Jefe_Departamento</th>
                                                <th class="text-center">Abogado_responsable</th>
                                                <th class="text-center">Número_oficio</th>
                                                <th class="text-center">Fecha_conclusión</th>
                                                <th class="text-center">Asunto_conclusión</th>
                                                <th class="text-center">Detalle_apersonamientos</th>
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
            <div class="chart tab-pane" id="sapa">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table id="tbl_sc" class="table table-hover table-condesed table-bordered">
                                    <caption class="bg-gray text-center">
                                        CONTADOR DE EXPEDIENTES POR ESTADO
                                    </caption>
                                    <thead>
                                        <tr class="bg-gray">
                                            <th width="50%">Estado del expediente</th>
                                            <th width="25%">Total</th>
                                            <th width="25%">Mostrar contador(es)</th>
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
                                <table id="tbl_sapa_res" class="table table-bordered table-hover">
                                    <caption class=" bg-gray text-center"> LISTADO DE EXPEDIENTES (SUBDIRECCIÓN DE ANÁLISIS Y PROCEDIMIENTOS ADMINISTRATIVOS)</caption>
                                    <thead>
                                        <tr class="bg-gray">
                                            <th class="text-center">#</th>
                                            <th class="text-center">Número_expediente</th>
                                            <th class="text-center">Jefe_Departamento</th>
                                            <th class="text-center">Abogado_responsable</th>
                                            <th class="text-center">Número_oficio</th>
                                            <th class="text-center">Fecha_conclusión</th>
                                            <th class="text-center">Asunto_conclusión</th>
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

