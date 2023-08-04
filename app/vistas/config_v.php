  <style>
      .form-control {
          font-weight: 700;
      }
  </style>
  <div class="content-wrapper">
      <!-- Main content -->
      <div class="content">
          <div class="container">
              <div class="col-6 mx-auto">
                  <div class="panel panel-info">
                      <div class="panel-heading">
                          <h3 class="panel-title text-center">
                              <i class="bi bi-gear"></i> Configuración
                          </h3>
                      </div>
                      <div class="panel-body">
                          <div class="row">
                              <div class="col-12 mx-auto">
                                  <form name="frmConfig" method="post" id="perfil" action="<?= BASE_URL; ?>Inicio_c/actconfig">
                                      <input type="hidden" name="id" value="<?= $config['id']; ?>">
                                      <table class="table table-sm">
                                          <tbody>
                                              <tr>
                                                  <td>Curso:</td>
                                                  <td>
                                                      <input type="text" class="form-control" name="curso" maxlength="5" value="<?php echo $config['curso']; ?>" required>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td>Fecha de Inicio:</td>
                                                  <td>
                                                      <input type="date" class="form-control" name="fecha_inicio" value="<?php echo $config['fecha_inicio'] ?>">
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td>Fecha de Final:</td>
                                                  <td>
                                                      <input type="date" class="form-control" name="fecha_fin" value="<?php echo $config['fecha_fin'] ?>">
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td>Tope Concesiones Diurno:</td>
                                                  <td>
                                                      <input min="0" max="20" step="1" type="number" class="form-control" name="topeConcesionesD" value="<?php echo $config['topeConcesionesD'] ?>">
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td>Tope Concesiones Vespertino:</td>
                                                  <td>
                                                      <input min="0" max="20" step="1" type="number" class="form-control" name="topeConcesionesV" value="<?php echo $config['topeConcesionesV'] ?>">
                                                  </td>
                                              </tr>
                                              <? if (CONGUARDIAS) : ?>
                                                  <tr>
                                                      <td>Causa Falta Guardias:</td>
                                                      <td>
                                                          <select class="form-control" name="codCausa">
                                                              <? foreach ($causas as $causa) :
                                                                    $sel = $causa['codigo'] == $config['codCausa'] ? "selected" : "";
                                                                ?>
                                                                  <option <?= $sel; ?> value="<?= $causa['codigo']; ?>"><?= $causa['descripcion']; ?></option>
                                                              <? endforeach; ?>
                                                          </select>
                                                      </td>
                                                  </tr>
                                              <? endif; ?>
                                              <tr>
                                                  <td>Email Secretaria:</td>
                                                  <td>
                                                      <input type="email" class="form-control" name="email_secretaria" maxlength="255" value="<?php echo $config['email_secretaria']; ?>" required>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td>Email Dirección:</td>
                                                  <td>
                                                      <input type="email" class="form-control" name="email_direccion" maxlength="255" value="<?php echo $config['email_direccion']; ?>" required>
                                                  </td>
                                              </tr>
                                          </tbody>
                                      </table>
                              </div>
                              <div class='col-12' id="resultados_ajax"></div>
                              <!-- Carga los datos ajax -->
                          </div>
                      </div>
                      <div class="panel-footer text-center">
                          <button type="submit" class="btn btn-sm btn-success">
                              <i class="bi bi-pen-fill"></i></i> Actualizar datos</button>
                          <a href="<?= BASE_URL; ?>Permisos_c/index" class="btn btn-sm btn-secondary">
                              <i class="bi bi-x-square-fill"></i> Cerrar</a>
                      </div>
                  </div>
              </div>
              </form>
          </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->