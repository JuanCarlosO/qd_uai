<!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Formulario de Alta de Actas</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div id="div_alert" class="col-md-12">
                                <div id="alerta" class="alert alert-success alert-dismissible ">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h4><i id="icono" class="icon fa fa-info"></i> <label id="estado"> Mi estado </label> </h4>
                                    <p id="message">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p>
                                </div>
                            </div>
                        </div>
                    	<form id="frm_add_acta" method="post" action="#">
                    		<input type="hidden" id="option" name="option" value="">
                    		<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Área que genera</label>
                                        <input type="text" id="area" name="area" value="" placeholder="Escriba un indicio del área y seleccione alguna de las coincidencias" class="form-control">
                                        <input type="hidden" id="area_h" name="area_h" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha del acta</label>
                                        <input type="date" id="f_acta" name="f_acta" value="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tipo de actuación</label>
                                        <select id="t_actuacion" name="t_actuacion" class="form-control" required>
                                            <option value="">...</option>
                                            <option value="1">INSPECCIÓN</option>
                                            <option value="2">VERIFICACIÓN</option>
                                            <option value="3">SUPERVISIÓN</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Procedencia</label>
                                        <select id="t_actuacion" name="t_actuacion" class="form-control" required>
                                            <option value="">...</option>
                                            <option value="1">Secretaría de Seguridad</option>
                                            <option value="2">CPRS</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Escriba el nombre del lugar</label>
                                        <input type="text" id="lugar" name="lugar" maxlength="255" value="" required class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Escriba el nombre del lugar</label>
                                        <textarea name="accion" id="accion" class="form-control" required style="resize: vertical;max-height: 300px;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <fieldset>
                                <legend>PERSONAL DE INVESTIGACIÓN</legend>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Buscar al personal de investigación</label>
                                            <input type="text" id="personal" name="personal" value="" placeholder="Escriba y seleccione una persona de la lista" autocomplete="off" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>INVESTIGADORES</label>
                                        <ol id="investigadores" title="INVESTIGADOR">
                                            <li id="1">
                                                Uno 
                                                <button type="button" class="btn btn-flat btn-danger btn-sm" onclick="remover_persona(1,'investigadores');">
                                                    <i class="fa fa-minus"></i>
                                                </button> 
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Buscar al personal de apoyo</label>
                                            <input type="text" id="personal_a" name="personal_a" value="" placeholder="Escriba y seleccione una persona de la lista" autocomplete="off" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>PERSONAL DE APOYO</label>
                                        <ol id="apoyo" title="PERSONAL DE APOYO">
                                            <li id="1">
                                                UNO DE APOYO 
                                                <button type="button" class="btn btn-flat btn-danger btn-sm" onclick="remover_persona(1,'apoyo');">
                                                    <i class="fa fa-minus"></i>
                                                </button> 
                                            </li>
                                            <li id="3">
                                                TRES 
                                                <button type="button" class="btn btn-flat btn-danger btn-sm" onclick="remover_persona(3,'apoyo');">
                                                    <i class="fa fa-minus"></i>
                                                </button> 
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="pull-right">¿El acta esta relacionada a una Orden de Inspección/ Verificación/ Supervisión?</label>
                                </div>
                                <div class="col-md-2">
                                    <select id="question" name="question" class="form-control">
                                        <option value="">...</option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                    </select>
                                </div>
                            </div>
                            <div id="orden" class="hidden">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="">Buscar la orden: </label>
                                        <input type="text" id="orden" name="orden" value="" placeholder="Escriba una parte de la clave de la orden de trabajo." autocomplete="off" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-flat btn-success btn-block">
                                        Guardar información
                                    </button>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                    	</form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    