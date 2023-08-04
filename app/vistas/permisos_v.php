<style>
    table {
        table-layout: fixed;
    }

    a[disabled] {
        pointer-events: none;
        background-color: lightgrey;
    }
</style>
<main>
    <div class="row">
        <?php if ($permisos) : ?>
            <div class="col-12 col-lg-10 mx-auto table-responsive-md">
                <div class="table-responsive-md">
                    <table class="table table-sm table-striped caption-top">
                        <thead>
                            <? $columnas = ['Nº petición' => [9, "text-center"], 'Fecha de petición' => [15, "text-center"], 'Fecha de registro' => [15, "text-center"], 'Nº registro' => [10, "text-center"], 'Dia Pedido' => [15, "text-center"], 'Dia lectivo' => [8, "text-center"], 'Estado' => [9, "text-center"], 'Solicitud' => [9, "text-center"], 'Cmd' => [10, "text-center"]];
                            foreach ($columnas as $col => $ancho) : ?>
                                <th width="<? echo $ancho[0]; ?>%" class="<? echo $ancho[1]; ?>"><? echo $col; ?></th>
                            <? endforeach; ?>
                        </thead>
                        <tbody style="vertical-align:middle;">
                            <?php
                            $estados = ["S" => "Solicitado", "R" => "Registrado", "C" => "Concedido", "D" => "Denegado", "A" => "Anulado"];
                            $diaslectivos = 0;
                            $numerop = 0;
                            foreach ($permisos as $item) :
                                $estado = $estados[$item['estado']];
                                $fecha_hoy = strtotime(date('Y-m-d'));
                                $fecha_comp = strtotime($item['fecha_pedida']);

                                if ($item['estado'] == "C" and $fecha_hoy > $fecha_comp) {
                                    $estado = "Disfrutado";
                                }
                                if ($item['estado'] != "S") {
                                    $borrar = "disabled";
                                } else {
                                    $borrar = "";
                                }
                                if ($estado == "Concedido" or $estado == "Registrado") {
                                    $anular = "";
                                } else {
                                    $anular = "disabled";
                                }
                                //$dialec=$item['dia_lectivo']?"S":"N";
                            ?>
                                <tr data-id="<?= $item['id']; ?>">
                                    <td class="text-center"><?= $item['npeticion']; ?></td>
                                    <td class="text-center"><?= date("d/m/Y", strtotime($item['fecha_peticion'])); ?></td>
                                    <td class="text-center"><?= $item['fecha_registro'] == "0000-00-00 00:00:00" ? "" : date("d/m/Y", strtotime($item['fecha_registro'])); ?></td>
                                    <td class="text-center"><?= $item['nregistro']; ?></td>
                                    <td class="text-center text-white bg-secondary"><?= date("d/m/Y", strtotime($item['fecha_pedida'])); ?></td>
                                    <td class="text-center"><?= $item['dia_lectivo'] ? "S" : "N"; ?></td>
                                    <td class="text-center"><?= $estado; ?></td>
                                    <? $fichero = ROOT . 'app/assets/documentos/' . $item['id'] . '.pdf';
                                    if (file_exists($fichero)) {
                                        $existedoc = "";
                                    } else {
                                        $existedoc = "disabled";
                                    }
                                    ?>
                                    <td class="text-center"><a <?= $existedoc; ?> href="<?= BASE_URL . "app/assets/documentos/" . $item['id'] . ".pdf"; ?>" target="_blank"><img title="Ver solicitud" width="24" src="<?= BASE_URL . "app/assets/img/icono_pdf.png"; ?>"></a></td>
                                    <td>
                                        <div class="btn-group d-flex justify-content-around">
                                            <button <?= $borrar; ?> class="btnBorrar"><i title="Borrar solicitud" class="bi bi-trash"></i></button>
                                            <button <?= $anular; ?> class="btnAnular"><i title="Anular permiso" class="bi bi-calendar-x"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                if ($item['dia_lectivo'] and $item['npeticion'] > 0) $diaslectivos += 1;
                                if ($item['npeticion'] > 0) $numerop = $item['npeticion'];
                            endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        <?php else : ?>
            <div class="col-12 col-lg-10 mx-auto my-5">
                <h3 class="text-center">Todavia no has solicitado tus dias</h3>
            </div>
        <?php endif; ?>
    </div>
    <?php if (count($permisosValidos) < 4) : ?>
        <div class="row align-items-center justify-content-center mt-2">
            <div class="col-8 col-sm-6 col-lg-4 mx-auto">
                <div class="d-grid my-2">
                    <a href="<?= BASE_URL; ?>Permisos_c/consmes" class="btn btn-secondary">Ver como está el patio</a>
                </div>
                <form name="frmSolicitar" action="<?= BASE_URL; ?>Permisos_c/insertar" method="post">
                    <input type="hidden" name="usuario" value="<?= $_SESSION['sesion']['usuario']; ?>">
                    <input type="hidden" name="horario" value="<?= $_SESSION['sesion']['horario']; ?>">
                    <input type="hidden" name="fecha_peticion" value="<?= date("Y-m-d H:i"); ?>">
                    <input type="hidden" name="estado" value="S">
                    <input type="hidden" name="npeticion" value="<?= $numerop + 1; ?>">
                    <input type="hidden" name="dia_lectivo" value="0">
                    <fieldset class="border p-3">
                        <div class="row mb-3">
                            <label for="fecha_pedida" class="col-lg-4 col-form-label">Fecha</label>
                            <div class="col-lg-8">
                                <input type="date" class="form-control" name="fecha_pedida" required autofocus />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="dia_lectivo" class="col-lg-4 form-check-label">Día lectivo?</label>
                            <div class="col-lg-1">
                                <input type="checkbox" checked class="form-check-input" name="dia_lectivo" value="1" />
                            </div>
                        </div>
                        <div class="d-grid">
                            <button id="btnSolicitar" disabled type="submit" class="btn btn-primary" title="Hasta que no hayas seleccionado fecha y hayas firmado el documento (rúbrica y clic en boton firmar), no te dejaré solicitar">Solicitar</button>
                        </div>
                    </fieldset>
                    <input type="hidden" name="firmaBase64">
                </form>
            </div>
            <div class="col-8 col-sm-6 col-lg-4 mx-auto">
                <h5 class="border-bottom">Firmado</h5>
                <div id="signatureArea" width="400" height="150">
                    <div>
                        <canvas id="signaturePad" width="400" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-2 d-grid gap-2 m-auto">
                <button title="Si has firmado mal puedes limpiar la firma y volver a intentarlo" type="button" id="btnLimpiar" class="btn btn-secondary">Limpiar Firma</button>
                <button title="Firmar la solicitud. Necesitas hacer una rubrica de tu firma el documento antes de hacer la solicitud" type="button" id="btnFirmar" class="btn btn-primary">Firmado</button>
            </div>
        </div>
    <?php else : ?>
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <h2 class="text-center">Ya has dispuesto de todos tus días</h2>
            </div>
        </div>
    <?php endif; ?>
</main>
<script>
    const topeDia = '<?= $config['topeConcesionesD']; ?>'
    const topeVes = '<?= $config['topeConcesionesV']; ?>'
    const fechaMax = '<?= $config['fecha_fin']; ?>'
    const dias_lectivos = '<?= $diaslectivos; ?>'
</script>
<script src="<?php echo BASE_URL; ?>app/assets/libs/firma/numeric.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/libs/firma/bezier.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/libs/firma/jquery.signaturepad.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/libs/firma/html2canvas.js"></script>
<script src="<?= BASE_URL; ?>app/vistas/js/permisos.js"></script>