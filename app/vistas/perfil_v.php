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
                            <i class="bi bi-person-circle"></i> Perfil
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-12 mx-auto">
                                <form name="frmPerfil" method="post" id="perfil" action="<?= BASE_URL; ?>Usuarios_c/actperfil">
                                    <input type="hidden" name="usuario" value="<?= $perfil['usuario']; ?>">
                                    <table class="table table-sm">
                                        <tbody>
                                            <tr>
                                                <td>Apellidos y nombre:</td>
                                                <td>
                                                    <input type="text" class="form-control" name="apenom" maxlength="50" value="<?php echo $perfil['apenom']; ?>" required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Correo electrónico:</td>
                                                <td>
                                                    <input type="email" class="form-control input-sm" name="email" maxlength="250" value="<?php echo $perfil['email'] ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Departamento
                                                <td>
                                                    <input type="text" class="form-control input-sm" name="departamento" maxlength="50" value="<?php echo $perfil['departamento'] ?>">
                                                </td>
                                            </tr>
                                            <?php if (AUTENTICACION == "SELF") : ?>
                                                <tr>
                                                    <td>Password
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="password" autocomplete="new-password" class="form-control input-sm" name="password" maxlength="50">
                                                            <button type="button" id="btnVerPass" class="input-group-text"><i class="bi bi-eye"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <td>Horario principal:</td>
                                                <td>
                                                    <select class="form-control" name="horario" value="<?php echo $perfil['horario']; ?>" required>
                                                        <option <?php echo $perfil['horario'] == "D" ? "selected" : ""; ?> value="D">Diurno</option>
                                                        <option <?php echo $perfil['horario'] == "V" ? "selected" : ""; ?> value="V">Vespertino</option>
                                                        <option <?php echo $perfil['horario'] == "P" ? "selected" : ""; ?> value="V">Partido</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <? if (CONGUARDIAS) : ?>
                                                <tr>
                                                    <td>Cod. Profesor Faltas:</td>
                                                    <td>
                                                        <select class="form-control" name="codigoprof" value="<?php echo $perfil['codigoprof']; ?>">
                                                            <? foreach ($profesores as $profe) :
                                                                $sel = $profe['Codigo'] == $perfil['codigoprof'] ? "selected" : "";
                                                            ?>
                                                                <option <?= $sel; ?> value="<?= $profe['Codigo']; ?>"><?= $profe['Nombre'] . " " . $profe['Apellido1'] . " " . $profe['Apellido2']; ?></option>
                                                            <? endforeach; ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            <? endif ?>
                                            <?php if ($perfil['rol'] == "D") : ?>
                                                <tr>
                                                    <td>Tipo de Usuario:</td>
                                                    <td>
                                                        <select class="form-control" name="rol" value="<?php echo $perfil['rol']; ?>" required>
                                                            <option <?php echo $perfil['rol'] == "P" ? "selected" : ""; ?> value="P">Profesor</option>
                                                            <option <?php echo $perfil['rol'] == "J" ? "selected" : ""; ?> value="J">J.Estudios</option>
                                                            <option <?php echo $perfil['rol'] == "D" ? "selected" : ""; ?> value="D">Director</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                            </div>
                            <div class='col-12' id="resultados_ajax"></div>
                            <!-- Carga los datos ajax -->
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-pen-fill"></i> Actualizar datos</button>
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
<script>
    // Ver password
    $("#btnVerPass").on("click", function(evento) {
        if ($(document.frmPerfil.password).attr("type") == "password") {
            $(document.frmPerfil.password).attr("type", "text");
            this.innerHTML = '<i class="bi bi-eye-slash"></i>'
        } else {
            $(document.frmPerfil.password).attr("type", "password");
            this.innerHTML = '<i class="bi bi-eye"></i>'
        }
    })
</script>