<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulario de migración</h3>
                </div>
                <div class="box-body">
                    <div id="alerta"></div>
                    <form action="#" id="frm_migracion" method="post">
                        <input type="hidden" name="option" value="83">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">
                                        Tipo de Actualización
                                    </label>
                                    <select name="modo" id="modo" required="" class="form-control">
                                        <option value="">...</option>
                                        <option value="1">
                                            Migrar de Persona a Persona
                                        </option>
                                        <option value="2">
                                            Actualizar estados de expedientes 
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8 hidden" id="div_1">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Servidor público actual</label>
                                        <input type="text" name="sp" id="sp" value="" placeholder="Ej: juan carlos..." class="form-control ">
                                        <input type="hidden" name="sp_id" id="sp_id" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Servidor público destino</label>
                                        <input type="text" name="destino" id="destino" value="" placeholder="Ej: juan carlos..." class="form-control ">
                                        <input type="hidden" name="destino_id" id="destino_id" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 hidden" id="div_2">
                                <div class="form-group">
                                    <label>Selección de búsqueda</label>
                                    <select name="c_buscar" id="c_buscar" class="form-control">
                                        <option value="">...</option>
                                        <option value="1">Por rango de fechas</option>
                                    </select>
                                </div>
                            </div>
                            <div id="div_2_1" class="hidden">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha de: </label>
                                        <input type="date" name="f_ini" value="" class="form-control" min='2019-07-01' max="<?=date('Y-m-d')?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha hasta: </label>
                                        <input type="date" name="f_fin" value="" class="form-control" min='2019-07-01' max="<?=date('Y-m-d')?>">
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success btn-flat btn-block">
                                    <i class="fa fa-search"></i> Buscar expedientes
                                </button>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </form>  
                    <div id="btn_migrate" class="row hidden">
                        <div class="col-md-6">
                            <button type="button" onclick="strarTransfer();" class="btn btn-app bg-green">
                                <i class="fa fa-play"></i> Iniciar transferencia
                            </button>
                        </div>
                        <div class="col-md-6">
                            <h3 class="pull-right">Elementos seleccionados: <u id="cuenta">0</u> </h3>
                        </div>
                    </div>
                    <div id="btn_change_edo" class="row hidden">
                        <div class="col-md-6">
                            <button type="button" onclick="generateFormsMigrate();" class="btn btn-app bg-green">
                                <i class="fa fa-contao"></i> Generar los formularios de migración
                            </button>
                        </div>
                    </div>
                    <div class="row hidden" id="buscador_tbl">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Buscador:</label>
                                <input type="search" id="buscador" name="buscador" value="" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                        
                    </div>
                    <div id="div_tbl" class="row hidden">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tbl_migrate" class="table table-bordered ">
                                    <thead>
                                        <tr class="bg-black">
                                            <th class="text-center">
                                                <label>
                                                    <input type="checkbox" class="minimal" name="check_master" id="check_master" value="all">
                                                </label>
                                            </th>
                                            <th class="text-center">ID</th>
                                            <th class="text-center" width="35%">CLAVE </th>
                                            <th class="text-center" width="35%">ABOGADO ASIGNADO</th>
                                            <th class="text-center">EDO. DEL EXPEDIENTE</th>
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