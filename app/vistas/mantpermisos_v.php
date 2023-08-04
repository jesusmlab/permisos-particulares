<link rel="stylesheet" href="<?= BASE_URL; ?>app/assets/libs/datatables/datatables.min.css" />
<style>
    a[disabled] {
        pointer-events: none;
        background-color: lightgrey;
    }
</style>
<main>
    <div class="row mb-3">
        <div class="col-lg-4">
            <h3>CRUD Permisos</h3>
        </div>
        <div class="col-lg-2">
            <div class="d-grid">
                <button type="button" title="Insertar permiso" id="btnNuevo" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#permisosModal">Nuevo</button>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="d-grid">
                <a title="Volver a mis permisos" href="<?= BASE_URL; ?>Permisos_c/index" class="btn btn-success">Volver</a>
            </div>
        </div>
    </div>
    <?php if (isset($_SESSION['mensajes'])) : ?>
        <div class="row" id="mensajes">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php
                foreach ($_SESSION['mensajes'] as $mensaje) {
                    echo "<p>" . $mensaje . "</p>";
                }
                unset($_SESSION['mensajes']); // Eliminar variable de sesion
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <table id="tblpermisos" class="table table-sm table-striped">
            <thead>
                <th class="text-center">Nombre</th>
                <th class="text-center">Horario</th>
                <th class="text-center">Nº Petición</th>
                <th class="text-center">F. Solicitud</th>
                <th class="text-center">F.Pedida</th>
                <th class="text-center">F.Registro</th>
                <th class="text-end">Nº.Reg.</th>
                <th class="text-center">Lectivo</th>
                <th class="text-center">Documento</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Cmd</th>
            </thead>
            <tbody>
                <?php
                if (count($permisos) > 0) {
                    $estados = ["S" => "Solicitado", "R" => "Registrado", "C" => "Concedido", "D" => "Denegado", "A" => "Anulada"];
                    foreach ($permisos as $permi) {
                        $estado = $estados[$permi['estado']];
                        $fpedida = date("Y/m/d", strtotime($permi['fecha_pedida']));
                ?>
                        <tr data-id="<?= $permi['id']; ?>">
                            <td><a class="infoDiasUsu" data-usu="<?= $permi['usuario']; ?>" href="#"><i class="bi bi-info-circle"></i></a> <?= $permi['apenom']; ?></td>
                            <td class="text-center"><?= $permi['horario']; ?></td>
                            <td class="text-center"><?= $permi['npeticion']; ?></td>
                            <td class="text-center"><?= date("Y/m/d H:i", strtotime($permi['fecha_peticion'])); ?></td>
                            <td class="text-center"> <button title="Ver quien ha pedido esta fecha" data-fechap="<?= $permi['fecha_pedida']; ?>" class="btn btn-outline-secondary btn-fechap"><?= $fpedida; ?></button></td>
                            <td class="text-center"><?= $permi['fecha_registro'] == "0000-00-00 00:00:00" ? "" : date("Y/m/d H:i", strtotime($permi['fecha_registro'])); ?></td>
                            <td class="text-end"><?= $permi['nregistro']; ?></td>
                            <td class="text-center"><?= $permi['dia_lectivo'] ? "S" : "N"; ?></td>
                            <? $fichero = ROOT . 'app/assets/documentos/' . $permi['id'] . '.pdf';
                            if (file_exists($fichero)) {
                                $existedoc = "";
                                $titulo = "Ver documento de solicitud";
                            } else {
                                $existedoc = "disabled";
                                $titulo = "No hay documento de solicitud";
                            }
                            ?>
                            <td class="text-center"><a title="<?= $titulo; ?>" <?= $existedoc; ?> href="<?= BASE_URL . "app/assets/documentos/" . $permi['id'] . ".pdf"; ?>" target="_blank"><img title="Ver solicitud" width="24" src="<?= BASE_URL . "app/assets/img/icono_pdf.png"; ?>"></a></td>
                            <td class="text-center"><?= $estado; ?></td>
                            <td class="text-center">
                                <button class="btn btnModificar"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btnBorrar"><i class="bi bi-trash3-fill"></i></i></button>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Modal para alta y modificación de permisos -->
    <div class="modal fade" id="permisosModal" tabindex="-1" aria-labelledby="Alta y modificacion de permisos" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="titulopermisosModal">Alta Permisos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form name="frmPermisos" action="<?= BASE_URL; ?>Permisos_c/insertar" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id">
                        <input type="hidden" name="falta_id">
                        <div class="row mb-3">
                            <label for="usuario" class="col-sm-2 col-form-label">Usuario</label>
                            <div class="col-sm-10">
                                <select name="usuario" class="form-control" requiered>
                                    <? foreach ($usuarios as $usu) : ?>
                                        <option value="<?= $usu['usuario']; ?>"><?= $usu['apenom']; ?></option>
                                    <? endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="horario" class="col-sm-2 col-form-label">Horario</label>
                            <div class="col-sm-3">
                                <select name="horario" class="form-control" required>
                                    <option value="D">Diurno</option>
                                    <option value="V">Vespertino</option>
                                    <option value="P">Partido</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="npeticion" class="col-sm-2 col-form-label">Nº Petición</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" name="npeticion" min="1" max="4" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fecha_peticion" class="col-sm-2 col-form-label">Fecha Solicitud</label>
                            <div class="col-sm-3">
                                <input type="datetime-local" class="form-control" name="fecha_peticion" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fecha_pedida" class="col-sm-2 col-form-label">Fecha Pedida</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" name="fecha_pedida" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fecha_registro" class="col-sm-2 col-form-label">Fecha Registro</label>
                            <div class="col-sm-3">
                                <input type="datetime-local" class="form-control" name="fecha_registro">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nregistro" class="col-sm-2 col-form-label">Nº Registro</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="nregistro" maxlength="10">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="dia_lectivo" class="col-sm-2 form-check-label">Día lectivo</label>
                            <div class="col-sm-1">
                                <input type="hidden" name="dia_lectivo" value="0">
                                <input type="checkbox" class="form-check-input" name="dia_lectivo" id="dia_lectivo" value="1">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                            <div class="col-sm-3">
                                <select name="estado" class="form-control" required>
                                    <option value="S">Solicitada</option>
                                    <option value="R">Registrada</option>
                                    <option value="C">Concedida</option>
                                    <option value="D">Denegada</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3" id="causad" style="display:none;">
                            <label for="causad" class="col-sm-2 col-form-label">Causa Denegación</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="Denegada por....." class="form-control" name="causad">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="verDiaModal" tabindex="-1" aria-labelledby="verDiaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="verDiaModalLabel">Dia </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cuerpoVerDia"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="verInfoModal" tabindex="-1" aria-labelledby="verInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="verInfoModalLabel">Usuario: </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cuerpoInfoUsu"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="<?= BASE_URL; ?>app/assets/libs/datatables/datatables.min.js"></script>
<script src="<?= BASE_URL; ?>app/vistas/js/mantpermisos.js"></script>