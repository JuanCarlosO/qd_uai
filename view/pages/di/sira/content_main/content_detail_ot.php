<?php 
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';
$ot = $_GET['ot'];
$a = new SiraModel;
$cedula = $a->getCedulaOT($ot);
#echo "<PRE>";
#print_r( var_dump($cedula[0]['queja']) );exit;
?>
<section class="content container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div id="detalle" class="row">
        <div class="col-md-12">
          <div class="panel-group" id="accordion">
            
            <div class="panel panel-primary">
            	<div class="panel-heading">
            		<h4 class="panel-title">
            			<a data-toggle="collapse" data-parent="#accordion" href="#d_oin">
            				Detalles del oficio de la Orden de Trabajo 
                    <b><?=$cedula[0]['clave'];?></b></a>
            		</h4>
            	</div>
            				
              <div id="d_oin" class="panel-collapse collapse in">
            		<div class="panel-body">
                  <div class="row">
                    <div class="col-md-2">
                      <label>ID</label>
                        <input type="text" value="<?=$cedula[0]['id'];?>" placeholder="" readonly="" class="form-control" >
                      </div>
                    <div class="col-md-4">
                      <label>Clave de OT</label>
                        <input type="text" name="" value="<?=$cedula[0]['clave'];?>" readonly="" class="form-control">
                    </div>
                    <div class="col-md-3">
                      <label>Estatus</label>
                        <input type="text" name="" value="<?=$cedula[0]['estatus'];?>" readonly="" class="form-control">
                    </div>
                  </div>

            			<br>

            			<div class="row">
            				<div class="col-md-12">
            					<label>Participantes</label>
            						<ol>
                        <?php
                          if (count($cedula[0]['participantes']) > 0) {
                            foreach ($cedula[0]['participantes'] as $key => $p) {
                              echo '<li> '.$p->nom_completo.'</li>';
                            }
                          }else{
                            echo "SIN PARTICIPANTES";
                          }
                        ?>
            						</ol>
            				</div>
            			</div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Descripción de la Orden de Trabajo </label>
                          <p class="text-justify">
                            <?php if (empty($cedula[0]['comentario'])): ?>
                              SIN COMENTARIOS
                            <?php else: ?>
                              <?=$cedula[0]['comentario'];?>
                            <?php endif ?>
                          </p>
                      </div>
                    </div>
                  </div>
                  
                  <?php if ($cedula[0]['estatus'] == 'Cancelada'): ?>
                    <div class="row bg-red">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Fecha y hora de la cancelación: </label>
                            <p><?=$cedula[0]['f_cancela']?></p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12  bg-red">
                        <div class="form-group">
                          <label>Motivo de la cancelación: </label>
                            <p><?=$cedula[0]['motivo']?></p>
                        </div>
                      </div>
                    </div>                  
                  <?php endif ?>
            						
                  <h4> <center>INSTRUCCIONES DE LA ORDEN DE TRABAJO: </center> </h4>
            			
                  <div class="row">
            				<div class="col-md-4">
                      <label>ID oficio:</label>
                        <?php
                          foreach ($cedula[0]['oficios'] as $key => $s) {
                            echo ' '.$s->of_id.'<br>';
                          }
                        ?>
            				</div>
            			  <div class="col-md-4">
                      <label>Fecha oficio:</label>
                        <?php
                          foreach ($cedula[0]['oficios'] as $key => $s) {
                            echo ' '.$s->fecha_oficio.'<br>';
                          }
                        ?>
            				</div>
            				<div class="col-md-4">
            					<label>No. oficio:</label>
                        <?php
                          foreach ($cedula[0]['oficios'] as $key => $s) {
                            echo ' '.$s->no_oficio.'<br>';
                          }
                        ?>
            				</div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-4">
                      <label>Asunto:</label>
                        <?php
                          foreach ($cedula[0]['oficios'] as $key => $s) {
                            echo ' '.$s->asunto.'<br>';
                          }
                          ?>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <label>Solicitud: </label>
                        <ol>
                        <?php
                          if (count($cedula[0]['tipo_sol']) > 0) {
                            foreach ($cedula[0]['tipo_sol'] as $key => $p) {
                              echo '<li> '.$p->descripcion_solicitud.'</li>';
                            }
                          }else{
                            echo "SIN INFORMACIÓN";
                          }
                        ?>                       
                        </ol>                     
                    </div>
                  </div>
                  
                  <h4> <center>DOCUMENTOS DE LA ORDEN DE TRABAJO: </center> </h4>
                  <br>
                    
                    <div class="row">
                      <div class="col-md-4">
                        <div class="table-responsive">
                          <table class="table table-hover table-condensed table-bordered">
                            <thead>
                              <tr class="bg-gray">
                                <th width="30%">Documento</th>
                                <th width="30%">Fecha de acuse de recepción</th>
                                <th width="60%">Comentarios</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($cedula[0]['archivo_oin'] as $file):?>
                                <tr id="file_<?=$file->id?>" class="info">               
                                  <td>
                                    <a href="controller/puente.php?option=23&file=<?=$file->id?>" target="__blank">
                                      <?=$file->nombre?>  
                                    </a>
                                  </td>
                                  <td><?=$file->fecha?></td>
                                  <td><?=$file->comentarios?></td>
                                </tr>
                              <?php endforeach ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <br>
                    <h4> <center>EXPEDIENTE QUE DA ORIGEN A LA ORDEN DE TRABAJO</center></h4>
                    <br>

                    <!--<div class="row">
                      <div class="col-md-12">
                        <label>Número del expediente:</label>
                          <?php
                            foreach ($cedula[0]['expediente_ot'] as $key => $ex) {
                              echo ' '.$ex->exp.'<br>';
                            }
                          ?>
                      </div>
                      <div class="col-md-12">
                        <label>Comentarios:</label>
                          <?php
                            foreach ($cedula[0]['expediente_ot'] as $key => $ex) {
                              echo ' '.$ex->comentarios.'<br>';
                            }
                          ?>
                      </div>
                    </div>-->

            		</div>
            	</div>
            </div>
            
            <div class="panel panel-success">
            	<div class="panel-heading">
            		<h4 class="panel-title">
            			<a data-toggle="collapse" data-parent="#accordion" href="#d_sira">
            				Acciones de cumplimiento 
            			</a>
            		</h4>
            	</div>
            	
              <div id="d_sira" class="panel-collapse collapse">
            		<ol id="sira_actas">
            			<?php if (count($cedula[0]['actas']) > 0): ?>
                    <?php foreach ($cedula[0]['actas'] as $key => $acta): ?>
                      <li> <a href="index.php?menu=cedula&acta=<?=$acta->id?>"> <?=$acta->clave?> </a>  </li>
                    <?php endforeach ?>
                  <?php elseif (count($cedula[0]['acciones']) > 0): ?>
                    <?php foreach ($cedula[0]['acciones'] as $key => $accion): ?>
                      <a href="index.php?menu=cedula_acciones&accion=<?=$accion->id?>"> Mostrar cédula </a>
                    <?php endforeach ?> 
                  <?php else: ?>    
                    NO EXISTEN ACCIONES RELACIONADAS A ESTA ORDEN DE TRABAJO
                  <?php endif ?>
            		</ol>
            	</div>
            </div>
            			
            <!--<div class="panel panel-info">
            	<div class="panel-heading">
            		<h4 class="panel-title">
            			<a data-toggle="collapse" data-parent="#accordion" href="#d_quejas">
            			  Quejas y Denuncias
            			</a>
            		</h4>
            	</div>
            	
              <div id="d_quejas" class="panel-collapse collapse">
            		<div class="row">
            			<div class="col-md-12">
                    <?php if ( $cedula[0]['queja'] ): ?>
                      <?php if ( count($cedula[0]['queja']) > 0): ?>
                        <a href="index.php?menu=cedula_inv&exp=<?=$cedula[0]['queja']->id?>"></a>
                      <?php endif ?>
                    <?php else: ?>
                      SIN EXPEDIENTE REGISTRADO
                    <?php endif ?>
                  </div>
            		</div>
            	</div>
            </div>-->
            
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#d_irreg">
                    Irregularidades y recomendaciones
                  </a>
                </h4>
              </div>
              
              <div id="d_irreg" class="panel-collapse collapse">
                <?php if ( $cedula[0]['solicitud'] ): ?>
                  <?php if ( count($cedula[0]['solicitud']) > 0): ?>
                    <div class="panel-body">      
                    
                    <h4>INFORMACIÓN DEL ENVÍO DE RECOMENDACIONES </h4>
                    <br>

                    <div class="row">
                      <div class="col-md-12">
                        <label>Fecha del oficio:</label>
                          <?php
                            foreach ($cedula[0]['solicitud'] as $key => $s) {
                              echo ' '.$s->f_envio.'<br>';
                            }
                          ?>
                        <label>Fecha de acuse de recepción:</label>
                          <?php
                            foreach ($cedula[0]['solicitud'] as $key => $s) {
                              echo ' '.$s->f_acuse.'<br>';
                            }
                          ?>
                        <label>Número de oficio:</label>
                          <?php
                            foreach ($cedula[0]['ofi_sol'] as $key => $s) {
                              echo ' '.$s->no_oficio.'<br>';
                            }
                          ?>
                        <label>Destinatario:</label>
                          <?php
                            foreach ($cedula[0]['solicitud'] as $key => $s) {
                              echo ' '.$s->destinatario.'<br>';
                            }
                          ?>
                        <label>Área:</label>
                          <?php
                            foreach ($cedula[0]['nom_area_r'] as $key => $a) {
                              echo ' '.$a->nombre.'<br>';
                            }
                          ?>
                        <label>Asunto:</label>
                          <?php
                            foreach ($cedula[0]['solicitud'] as $key => $s) {
                              echo ' '.$s->asunto.'<br>';
                            }
                          ?>
                        <label>Comentarios:</label>
                          <?php
                            foreach ($cedula[0]['solicitud'] as $key => $s) {
                              echo ' '.$s->comentario.'<br>';
                            }
                          ?>
                      </div>
                    </div>

                    <br>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="table-responsive">
                          <table class="table table-hover table-condensed table-bordered">
                            <caption class="bg-gray">
                              <center><b>ACUSE DE RECEPCIÓN</b></center> 
                            </caption>
                            <thead>
                              <tr class="bg-gray">
                                <th width="30%">Documento</th>
                                <th width="30%">Fecha de acuse de recepción</th>
                                <th width="30%">Descripción</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($cedula[0]['archivos'] as $file): ?>
                                <tr id="file_<?=$file->id?>" class="info">  
                                  <td>
                                    <a href="controller/puente.php?option=23&file=<?=$file->id?>" target="__blank">
                                      <?=$file->nombre?>  
                                    </a>
                                  </td>
                                  <td><?=$file->fecha?></td>
                                  <td><?=$file->comentario?></td>
                                </tr>
                              <?php endforeach ?>
                            </tbody>
                          </table>
                        </div>
                        <ol></ol>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <table class="table table-hover table-bordered">
                          <caption class="text-center bg-gray"> <b>RECORDATORIOS</b> </caption>
                          <thead>
                            <tr class="bg-gray">
                              <th class="text-center">Fecha de oficio</th>
                              <th class="text-center">Fecha de acuse</th>
                              <th class="text-center">Número de oficio</th>
                              <th class="text-center">Destinatario</th>
                              <th class="text-center">Asunto</th>
                              <th class="text-center">Observaciones</th>
                              <th class="text-center">Fecha límite de respuesta</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($cedula[0]['recordatorios'] as  $recordatorio): ?>
                              <tr id="recordatorio<?=$recordatorio->id?>" class="info">
                                <td> <?=$recordatorio->f_recordatorio?> </td>
                                <td> <?=$recordatorio->f_acuse?> </td>
                                <td> <?=$recordatorio->no_oficio?>  </td>
                                <td> <?=$recordatorio->destinatario?> </td>
                                <td> <?=$recordatorio->asunto?> </td>
                                <td> <?=$recordatorio->observaciones?>  </td>
                                <td> <?=$recordatorio->f_limite?>  </td>
                              </tr>
                            <?php endforeach ?>
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <h4>INFORMACIÓN DEL SEGUIMIENTO DE RESPUESTAS </h4>
                    <br>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <label>Fecha de recepción:</label>
                          <?php
                            foreach ($cedula[0]['seguimiento'] as $key => $se) {
                              echo ' '.$se->fecha.'<br>';
                            }
                          ?>
                        <label>Número de  oficio:</label>
                          <?php
                            foreach ($cedula[0]['ofi_seguimiento'] as $key => $seg) {
                              echo ' '.$seg->ofi_resp.'<br>';
                            }
                          ?>
                        <label>Remitente:</label>
                          <?php
                            foreach ($cedula[0]['seguimiento'] as $key => $se) {
                              echo ' '.$se->remitente.'<br>';
                            }
                          ?>
                        <label>Cargo:</label>
                          <?php
                              foreach ($cedula[0]['seguimiento'] as $key => $se) {
                                echo ' '.$se->cargo.'<br>';
                              }
                          ?>
                        <label>Asunto:</label>
                          <?php
                            foreach ($cedula[0]['seguimiento'] as $key => $se) {
                              echo ' '.$se->asunto.'<br>';
                            }
                          ?>
                        <label>Personal de la UAI que recibe:</label>
                          <?php
                            foreach ($cedula[0]['recibe_seguimiento'] as $key => $rs) {
                              echo ' '.$rs->nom_completo.'<br>';
                            }
                        ?>
                        <label>Estatus general de las recomendaciones:</label>
                          <?php
                            foreach ($cedula[0]['seguimiento'] as $key => $rs) {
                              echo ' '.$rs->estatus.'<br>';
                            }
                          ?>
                        <label>Observaciones de las recomendaciones:</label>
                          <?php
                            foreach ($cedula[0]['seguimiento'] as $key => $rs) {
                              echo ' '.$rs->observaciones.'<br>';
                            }
                        ?>
                      </div>
                    </div>

                    <br>                                                                      
                    <div class="row">
                      <div class="col-md-6">
                        <table class="table table-hover table-bordered">
                          <caption class="text-center bg-gray"> <b>IRREGULARIDADES Y RECOMENDACIONES</b> </caption>
                          <thead>
                            <tr class="bg-gray">
                              <th class="text-center">Irregularidades</th>
                              <th class="text-center">Recomendaciones</th>
                              <th class="text-center">Estatus</th>
                              <th class="text-center">Observaciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($cedula[0]['irregularidades'] as  $irregularidades): ?>
                              <tr id="irregularidades<?=$irregularidades->id?>" class="info">
                                <td> <?=$irregularidades->observacion?> </td>
                                <td> <?=$irregularidades->recomendacion?> </td>
                                <td> <?=$irregularidades->atendido?></td>
                                <td> <?=$irregularidades->observaciones?></td>
                              </tr>
                            <?php endforeach ?>
                          </tbody>
                        </table>
                      </div>
                    </div>

                  <?php endif ?>
                  <?php else: ?>
                    SIN INFORMACIÓN DE IRREGULARIDADES Y RECOMENDACIONES
                  <?php endif ?>
                </div>
              </div>
            </div>

            <div class="panel panel-danger">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#d_adm">
                  Acta administrativa
                  </a>
                </h4>
              </div>
              
              <div id="d_adm" class="panel-collapse collapse">
                <?php if ( $cedula[0]['acta_admin'] ): ?>
                  <?php if ( count($cedula[0]['acta_admin']) > 0): ?>
                    <div class="panel-body">

                      <h4>INFORMACIÓN DEL ACTA GENERADA </h4>
                      <br>

                      <div class="row">
                        <div class="col-md-12">
                          <label>Fecha:</label>
                            <?php
                              foreach ($cedula[0]['acta_admin'] as $key => $ad) {
                                echo ' '.$ad->fecha.'<br>';
                              }
                            ?>
                          <label>Hora:</label>
                            <?php
                              foreach ($cedula[0]['acta_admin'] as $key => $ad) {
                                echo ' '.$ad->hora.'<br>';
                              }
                            ?>
                          <label>Motivo:</label>
                            <?php
                              foreach ($cedula[0]['acta_admin'] as $key => $ad) {
                                echo ' '.$ad->motivo.'<br>';
                              }
                            ?>
                          <label>Presunto:</label>
                            <?php
                              foreach ($cedula[0]['acta_admin'] as $key => $ad) {
                                echo ' '.$ad->presunto.'<br>';
                              }
                            ?>
                          <label>Área:</label>
                            <?php
                              foreach ($cedula[0]['nom_area'] as $key => $ad) {
                                echo ' '.$ad->nombre.'<br>';
                              }
                            ?>
                        </div>
                      </div> 

                      <div class="row">
                        <div class="col-md-12">
                          <dl class="dl-horizontal">
                            <dt>Presuntas conductas</dt>
                              <dd>
                                <?php
                                  if ( $cedula[0]['conductas'] == "SIN LEY" ){
                                    echo "<li>CONDUCTA NO ESPECIFICADA EN LA LEY DE SEGURIDAD</li>";
                                  }else{
                                    echo "<ol>";
                                      foreach ($cedula[0]['conductas'] as $key => $conducta) {
                                        echo '<li>'.mb_strtoupper($conducta->nombre).'</li>';
                                      }
                                        echo "</ol>";
                                  }
                                ?>  
                              </dd>
                            </dl>
                          </div>
                        </div>
                      </div>

                      <div class="panel-body">

                      <h4>INFORMACIÓN DEL OFICIO ENVIADO </h4>
                      <br>

                        <div class="row">
                          <div class="col-md-12">
                            <label>Fecha del oficio:</label>
                              <?php
                                foreach ($cedula[0]['acta_admin'] as $key => $ad) {
                                  echo ' '.$ad->f_oficio.'<br>';
                                }
                              ?>
                            <label>Fecha de recepción:</label>
                              <?php
                                foreach ($cedula[0]['acta_admin'] as $key => $ad) {
                                  echo ' '.$ad->f_recepcion.'<br>';
                                }
                              ?>
                            <label>Oficio:</label>
                              <?php
                                foreach ($cedula[0]['ofi_acta'] as $key => $ad) {
                                  echo ' '.$ad->no_oficio.'<br>';
                                }
                              ?>
                            <label>Destinatario:</label>
                              <?php
                                foreach ($cedula[0]['acta_admin'] as $key => $ad) {
                                  echo ' '.$ad->destinatario.'<br>';
                                }
                              ?>
                            <label>Cargo:</label>
                              <?php
                                foreach ($cedula[0]['acta_admin'] as $key => $ad) {
                                  echo ' '.$ad->cargo.'<br>';
                                }
                              ?>
                            <label>Asunto:</label>
                              <?php
                                foreach ($cedula[0]['acta_admin'] as $key => $ad) {
                                  echo ' '.$ad->asunto.'';
                                }
                              ?>
                          </div>
                        </div>
                      </div>

                      <div class="panel-body">
                        
                        <h4>ESTATUS DEL ACTA ADMINISTRATIVA</h4>
                        <br>

                        <div class="row">
                          <div class="col-md-12">
                            <?php
                              foreach ($cedula[0]['acta_admin'] as $key => $ad) {
                                echo ' '.$ad->estatus.'<br>';
                              }
                            ?>
                          </div>
                        </div>
                      </div>

                      <div class="panel-body">

                          <h4>INFORMACIÓN DEL EXPEDIENTE GENERADO</h4>
                          <br>

                          <div class="row">
                            <div class="col-md-12">
                              <label>Número del expediente:</label>
                                <?php
                                  foreach ($cedula[0]['expedientes'] as $key => $ex) {
                                    echo ' '.$ex->exp.'<br>';
                                  }
                                ?>
                            </div>
                          </div>
                      </div>

                      <br>

                      <div class="panel-body">
                        <div class="row">
                          <div class="col-md-3">
                            <div class="table-responsive">
                              <table class="table table-hover table-condensed table-bordered">
                                <caption class="bg-gray">
                                  <center><b>ACUSE DE ACTA ADMINISTRATIVA</b></center> 
                                </caption>
                                  <thead>
                                    <tr class="bg-gray">
                                      <th width="30%">Documento</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php foreach ($cedula[0]['archivos_ac'] as $file): ?>
                                      <tr id="file_<?=$file->id?>" class="info">
                                        <td>
                                          <a href="controller/puente.php?option=26&file=<?=$file->id?>" target="__blank">
                                            <?=$file->formato?>  
                                          </a>
                                        </td>
                                      </tr>
                                    <?php endforeach ?>
                                  </tbody>
                              </table>
                            </div>
                            <ol></ol>
                          </div>
                        </div>
                      </div>
                                                      
                  <?php endif ?>
                  <?php else: ?>
                    SIN ACTA ADMINISTRATIVA GENERADA
                  <?php endif ?>
              </div>
            </div>

            <div class="panel panel-info">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#d_exp">
                    Expediente generado a partir de la Orden de Trabajo
                  </a>
                </h4>
              </div>
                      
              <div id="d_exp" class="panel-collapse collapse">
                <?php if ( $cedula[0]['expediente_ot'] ): ?>
                  <?php if ( count($cedula[0]['expediente_ot']) > 0): ?>
                    <div class="panel-body">

                      <h4>DATOS DEL EXPEDIENTE</h4>
                      <br>

                      <div class="row">
                        <div class="col-md-12">
                          <label>Número de oficio:</label>
                            <?php
                              foreach ($cedula[0]['ofi_expediente'] as $key => $ex) {
                                echo ' '.$ex->no_oficio.'<br>';
                              }
                            ?>
                          <label>Fecha de solicitud:</label>
                            <?php
                              foreach ($cedula[0]['ofi_expediente'] as $key => $ex) {
                                echo ' '.$ex->fecha_oficio.'<br>';
                              }
                            ?>
                          <label>Número de expediente:</label>
                            <?php
                              foreach ($cedula[0]['expediente_ot'] as $key => $ex) {
                                echo ' '.$ex->exp.'<br>';
                              }
                            ?>                                  
                          <label>Tipo de asunto:</label>
                            <?php
                              foreach ($cedula[0]['expediente_ot'] as $key => $ex) {
                                echo ' '.$ex->t_asunto.'<br>';
                              }
                            ?>
                          <label>Comentario:</label>
                            <?php
                              foreach ($cedula[0]['expediente_ot'] as $key => $ex) {
                                echo ' '.$ex->comentarios.'<br>';
                              }
                            ?>                                   
                        </div>
                      </div>
                                    
                    </div>                                         
                  <?php endif ?>
                  <?php else: ?>
                    SIN EXPEDIENTE RELACIONADO
                  <?php endif ?>
              </div>
            </div>
            

          </div>
        </div>
      </div>
    </div>
  </div>
</section>


