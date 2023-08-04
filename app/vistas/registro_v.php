<style>
    table {
        table-layout: fixed;
    }
</style>
<main>
    <?php if (isset($_SESSION['mensaje'])) : ?>
        <div class="row" id="mensajes">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php
                echo "<p>" . $_SESSION['mensajes'] . "</p>";
                unset($_SESSION['mensaje']); // Eliminar variable de sesion
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <?php if ($permisos) : ?>
            <div class="col-lg-10 mx-auto">
                <table class="table table-sm table-striped caption-top">
                    <thead>
                        <? $columnas = ['Nombre' => [25, "text-start"], 'Fecha de petición' => [15, "text-center"], 'Fecha de registro' => [15, "text-center"], 'Dia Pedido' => [15, "text-center"], 'Dia lectivo' => [10, "text-center"], 'Estado' => [10, "text-center"], 'Documento' => [10, "text-center"], 'Cmd' => [10, "text-center"]];
                        foreach ($columnas as $col => $ancho) : ?>
                            <th width="<? echo $ancho[0]; ?>%" class="<? echo $ancho[1]; ?>"><? echo $col; ?></th>
                        <? endforeach; ?>
                    </thead>
                    <tbody style="vertical-align:middle;">
                        <?php
                        $estados = ["S" => "Solicitado", "R" => "Registrado", "C" => "Concedido", "D" => "Denegado"];
                        $diaslectivos = 0;
                        foreach ($permisos as $item) :
                            $estado = $estados[$item['estado']];
                        ?>
                            <tr data-id="<?= $item['id']; ?>">
                                <td class="text-start"><?= $item['apenom']; ?></td>
                                <td class="text-center"><?= date("d/m/Y H:i", strtotime($item['fecha_peticion'])); ?></td>
                                <td class="text-center"><?= $item['fecha_registro'] == "0000-00-00 00:00:00" ? "" : date("d/m/Y H:i", strtotime($item['fecha_registro'])); ?></td>
                                <td class="text-center"><?= date("d/m/Y", strtotime($item['fecha_pedida'])); ?></td>
                                <td class="text-center"><?= $item['dia_lectivo'] ? "S" : "N"; ?></td>
                                <td class="text-center"><?= $estado; ?></td>
                                <td class="text-center"><a href="<?= BASE_URL . "app/assets/documentos/" . $item['id'] . ".pdf"; ?>" target="_blank"><img title="Ver solicitud" width="24" src="<?= BASE_URL . "app/assets/img/icono_pdf.png"; ?>"></a></td>
                                <td class="text-center"><button class="btnRegistrar"><i title="Registrar solicitud" class="bi bi-r-circle"></i></button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="col-lg-8 mx-auto text-center my-5">
                <h3>Nada que registrar</h3>
            </div>
        <?php endif; ?>
    </div>
    <!-- Modal para enviar documento registrado -->
    <div class="modal fade" id="registrosModal" tabindex="-1" aria-labelledby="Registro de permisos" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tituloregistrosModal">Registro de permisos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form name="frmRegistros" action="<?= BASE_URL; ?>Permisos_c/registrar" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id">
                        <input type="hidden" name="estado" value="R">
                        <div class="row mb-3">
                            <label for="nregistro" class="col-sm-2 col-form-label">Nº Registro</label>
                            <div class="col-sm-2">
                                <input type="text" maxlength="10" class="form-control" name="nregistro" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fecha_registro" class="col-sm-2 col-form-label">Fecha Registro</label>
                            <div class="col-sm-3">
                                <input type="datetime-local" class="form-control" name="fecha_registro" value="<?= date("Y-m-d H:i"); ?>" required>
                            </div>
                        </div>
                        <!-- <div class="row mb-3">
                            <label for="documento" class="col-sm-2 col-form-label">Documento</label>
                            <div class="col-sm-10">
                                <input type="file" multiple class="form-control" name="documento" accept=".pdf" required>
                            </div>
                        </div> -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="<?= BASE_URL; ?>app/vistas/js/registro.js"></script>