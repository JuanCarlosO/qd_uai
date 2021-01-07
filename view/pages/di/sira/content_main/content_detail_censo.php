<?php 
require_once 'model/Connection.php';
require_once 'model/SiraModel.php';
$censo = $_GET['censo'];
$a = new SiraModel;
$cedula = $a->getCedulaCenso($censo);
?>

        <section class="content container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div id="detalle" class="row">
                <div class="col-md-12">
                  <div class="panel-group" id="accordion">
                    <div class="panel panel-warning">
                      <div class="panel-heading">
                        <h4 class="panel-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#d_irreg">
                          Censos
                          </a>
                        </h4>
                      </div>

                      <div id="d_irreg" class="panel-collapse collapse">
                        <div class="panel-body">
                          <h4>INFORMACIÓN DE ENVÍO DEL CENSO </h4>
                          <br>

                          <div class="row">
                            <div class="col-md-12">
                              <label>Tipo de envío del censo:</label>
                              <?php
                                foreach ($cedula[0]['censo'] as $key => $c) {
                                  echo ' '.$c->tipo_envio.'<br>';
                                }
                              ?>
                              <label>Fecha del oficio/correo:</label>
                              <?php
                                foreach ($cedula[0]['censo'] as $key => $c) {
                                  echo ' '.$c->f_envio.'<br>';
                                }
                              ?>
                              <label>Fecha de acuse de recepción:</label>
                              <?php
                                foreach ($cedula[0]['censo'] as $key => $c) {
                                  echo ' '.$c->f_acuse.'<br>';
                                }
                              ?>
                              <label>Número de oficio:</label>
                              <?php
                                if ( $cedula[0]['sol_censo'] == NULL ){
                                  echo "NO APLICA".'<br>';
                                } else{
                                  foreach ($cedula[0]['sol_censo'] as $key => $sc) {
                                    echo ' '.$sc->no_oficio.'<br>';
                                  } }
                              ?>
                              <label>Destinatario:</label>
                              <?php
                                foreach ($cedula[0]['censo'] as $key => $s) {
                                  echo ' '.$c->destinatario.'<br>';
                                }
                              ?>
                              <label>Área:</label>
                              <?php
                                foreach ($cedula[0]['nom_area_c'] as $key => $c) {
                                  echo ' '.$c->nombre.'<br>';
                                }
                              ?>
                              <label>Asunto:</label>
                              <?php
                                foreach ($cedula[0]['censo'] as $key => $c) {
                                  echo ' '.$c->asunto.'<br>';
                                }
                              ?>
                              <label>Observaciones:</label>
                              <?php
                                foreach ($cedula[0]['censo'] as $key => $c) {
                                  echo ' '.$c->observaciones.'<br>';
                                }
                              ?>
                              <label>Fecha límite de respuesta:</label>
                                <?php
                                  foreach ($cedula[0]['censo'] as $key => $c) {
                                    echo ' '.$c->f_limite.'<br>';
                                  }
                                ?>
                            </div>
                          </div>
                          <br>

                          <div class="row">
                            <div class="col-md-6">
                              <table class="table table-hover table-bordered">
                                <caption class="text-center bg-gray"> <b>RECORDATORIOS</b> </caption>
                                <thead>
                                  <tr class="bg-gray">
                                    <th class="text-center">Fecha de oficio</th>
                                    <th class="text-center">Fecha de acuse</th>
                                    <th class="text-center">Tipo de envío</th>
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
                                    <td> <?=$recordatorio->tipo_envio?> </td>
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

                          <br>
                          <h4>INFORMACIÓN DE LA RESPUESTA AL CENSO </h4>
                          <br>
                          
                          <div class="row">
                            <div class="col-md-12">
                              <label>Tipo de recepción de las respuestas del censo:</label>
                                <?php
                                  foreach ($cedula[0]['seguimiento'] as $key => $s) {
                                    echo ' '.$s->tipo_envio.'<br>';
                                  }
                                ?>
                              <label>Fecha de recepción:</label>
                                <?php
                                  foreach ($cedula[0]['seguimiento'] as $key => $s) {
                                    echo ' '.$s->f_oficio.'<br>';
                                  }
                                ?>
                              <label>Número de  oficio:</label>
                                <?php
                                  if ( $cedula[0]['ofi_seguimiento'] == NULL ){
                                    echo "NO APLICA".'<br>';
                                  } else{
                                    foreach ($cedula[0]['ofi_seguimiento'] as $key => $os) {
                                      echo ' '.$os->ofi_resp.'<br>';
                                    } }
                                ?>
                              <label>Remitente:</label>
                                <?php
                                  foreach ($cedula[0]['seguimiento'] as $key => $s) {
                                    echo ' '.$s->remitente.'<br>';
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
                                  foreach ($cedula[0]['seguimiento'] as $key => $s) {
                                    echo ' '.$s->asunto.'<br>';
                                  }
                                ?>
                              <label>Periodo del censo:</label>
                                <?php
                                  foreach ($cedula[0]['seguimiento'] as $key => $s) {
                                    echo ' '.$s->periodo.'<br>';
                                  }
                                ?>
                              <label>Personal de la UAI que recibe:</label>
                                <?php
                                  foreach ($cedula[0]['recibe_seguimiento'] as $key => $rs) {
                                    echo ' '.$rs->nom_completo.'<br>';
                                  }
                                ?>
                            </div>
                          </div>

                          <br>
                          <h4>INFORMACIÓN DEL EXPEDIENTE RELACIONADO</h4>
                          <br>

                          <div class="row">
                            <div class="col-md-12">
                              <label>Número del expediente:</label>
                                <?php
                                  foreach ($cedula[0]['expediente_censo'] as $key => $ex) {
                                    echo ' '.$ex->exp.'<br>';
                                  }
                                ?>
                            </div>
                          </div>

                          <br>
                          <h4>LISTADO DE PREGUNTAS Y RESPUESTAS </h4>
                          <br>

                          <div class="row">
                            <div class="col-md-12">
                              <?php 
                                echo "<a href='index.php?menu=resumen&censo=$censo'>Ver listado</a>"; 
                              ?>
                            </div>
                          </div>

                          <br><br>
                        </div>
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
                                      foreach ($cedula[0]['nom_area'] as $key => $ar) {
                                        echo ' '.$ar->nombre.'<br>';
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

                              <br>
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
                                          
                              <br>
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
                                         
                              <br>
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
                                        
                              <br>

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
                          Expediente generado a partir del Censo
                          </a>
                        </h4>
                      </div>
                      
                      <div id="d_exp" class="panel-collapse collapse">
                        <?php if ( $cedula[0]['expediente_censo'] ): ?>
                          <?php if ( count($cedula[0]['expediente_censo']) > 0): ?>
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
                                      foreach ($cedula[0]['expediente_censo'] as $key => $ex) {
                                        echo ' '.$ex->exp.'<br>';
                                      }
                                    ?>                                  
                                  <label>Tipo de asunto:</label>
                                    <?php
                                      foreach ($cedula[0]['expediente_censo'] as $key => $ex) {
                                        echo ' '.$ex->t_asunto.'<br>';
                                      }
                                    ?>
                                  <label>Comentario:</label>
                                    <?php
                                      foreach ($cedula[0]['expediente_censo'] as $key => $ex) {
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
      </section>


