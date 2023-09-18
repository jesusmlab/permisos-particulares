<main>
    <div class="row mb-3">
        <div class="col-lg-2">
            <h3>CRUD Usuarios</h3>
        </div>
        <div class="col-lg-1">
            <div class="d-grid">
                <button type="button" id="btnNuevo" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#usuariosModal">Nuevo</button>
            </div>
        </div>
        <div class="col-lg-4">
            <form name="frmBuscar" action="<? echo BASE_URL; ?>Usuarios_c/mantusu" method="post">
                <input type="hidden" name="filtro" value="%%">
                <div class="input-group">
                    <input type="search" id="textoBuscado" name="textoBuscado" class="form-control" placeholder="Buscar por...." aria-label="Filtro de busqueda" aria-describedby="btnBuscar" value="<?= isset($buscado) ? $buscado : ""; ?>">
                    <button class="btn btn-outline-secondary" type="submit" id="btnBuscar">Buscar</button>
                </div>
            </form>
        </div>
        <div class="col-lg-2">
            <?php
            $arrayHorarios = ["%%" => "Todos", "D" => "Diurno", "V" => "Vespertino", "P" => "Partido"];
            ?>
            <div class="row">
                <label for="filtro" class="col-sm-4 col-form-label">Horario</label>
                <div class="col-sm-8">
                    <select id="filtro" class="form-control">
                        <?php foreach ($arrayHorarios as $clave => $valor) :
                            if ($clave == $filtro) {
                                $sel = "selected";
                            } else {
                                $sel = "";
                            }
                        ?>
                            <option <?= $sel; ?> value="<?= $clave; ?>"><?= $valor; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div title="Cargar Profesores masivamente" class="d-grid">
                <button type="button" id="btnCargar" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#cargarProfes">Carga Masiva</button>
            </div>
        </div>
        <div class="col-lg-1">
            <div class="d-grid">
                <a href="<?= BASE_URL; ?>Permisos_c/index" class="btn btn-success">Volver</a>
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
        <table class="table table-sm table-striped">
            <thead>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Horario</th>
                <th>Departamento</th>
                <th class="text-center">Rol</th>
                <th>Email</th>
                <th class="text-center">Cod.Profesor</th>
                <th class="text-center">Cmd</th>
            </thead>
            <tbody>
                <?php
                if (count($usuarios) > 0) {
                    $horarios = ["D" => "Diurno", "V" => "Vespertino", "P" => "Partido"];
                    foreach ($usuarios as $usu) {
                        $horario = $horarios[$usu['horario']];
                ?>
                        <tr>
                            <td><?= $usu['usuario']; ?></td>
                            <td><?= $usu['apenom']; ?></td>
                            <td><?= $horario; ?></td>
                            <td><?= $usu['departamento']; ?></td>
                            <td class="text-center"><?= $usu['rol']; ?></td>
                            <td><?= $usu['email']; ?></td>
                            <td class="text-center"><?= $usu['codigoprof']; ?></td>
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
        <div class="col-8 mx-auto d-flex justify-content-center">
            <?= $paginas; ?>
        </div>
    </div>
    <!-- Modal para alta y modificación de permisos -->
    <div class="modal fade" id="usuariosModal" tabindex="-1" aria-labelledby="Alta y modificacion de usuarios" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="titulousuariosModal">Alta Usuarios</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form name="frmUsuarios" action="<?= BASE_URL; ?>Usuarios_c/insertar" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <label for="usuario" class="col-sm-2 col-form-label">Usuario</label>
                            <div class="col-sm-6">
                                <input type="text" autocomplete="new-password" class="form-control" maxlength="20" name="usuario" placeholder="Usuario de Rayuela" required>
                            </div>
                        </div>
                        <?php if (AUTENTICACION == "SELF") : ?>
                            <div class="row mb-3">
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="password" autocomplete="new-password" class="form-control" maxlength="30" name="password" placeholder="Clave del usuario">
                                        <button type="button" id="btnVerPass" class="input-group-text"><i class="bi bi-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="row mb-3">
                            <label for="apenom" class="col-sm-2 col-form-label">Nombre</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" maxlength="35" name="apenom" required>
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
                            <label for="departamento" class="col-sm-2 col-form-label">Departamento</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" maxlength="50" name="departamento" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="rol" class="col-sm-2 col-form-label">Rol</label>
                            <div class="col-sm-3">
                                <select name="rol" class="form-control" required>
                                    <option value="P">Profesor</option>
                                    <option value="J">Jefatura E.</option>
                                    <option value="S">Secretaría</option>
                                    <option value="D">Director</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" maxlength="250" name="email" required>
                            </div>
                        </div>
                        <? if (CONGUARDIAS) : ?>
                            <div class="row mb-3">
                                <label for="codigoprof" class="col-sm-2 col-form-label">Cod.Profesor</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="codigoprof">
                                        <? foreach ($profesores as $profe) : ?>
                                            <option <?= $sel; ?> value="<?= $profe['Codigo']; ?>"><?= $profe['Nombre'] . " " . $profe['Apellido1'] . " " . $profe['Apellido2']; ?></option>
                                        <? endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <? endif ?>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="cargarProfes" tabindex="-1" aria-labelledby="Carga masiva de Profesores" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="titulocargarProfesores">Carga Masiva de Profesores</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form name="frmCargar" action="<?= BASE_URL; ?>Usuarios_c/cargar" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <label for="lista" class="col-sm-2 col-form-label">Lista</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="lista" id="lista" rows="15" placeholder="Login|Apellidos,Nombre|Email(opcional)" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Cargar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="<?= BASE_URL; ?>app/vistas/js/mantusu.js"></script>