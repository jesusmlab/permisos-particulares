<style>
    #signatureArea {
        border: 2px solid #444;
        border-radius: 15px;
        background-color: #fafafa;
    }
</style>
<section class="row d-flex justify-content-center">
    <h3 class="text-center">Firmar solicitud de permiso</h3>
    <!-- <div class="col-10"></div> -->
    <div class="row mb-2">
        <form action="<?php echo BASE_URL; ?>Permisos_c/guardarFirma" name="frmFirma" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $permiso['id']; ?>">
            <input type="hidden" name="fecha_pedida" value="<?= $permiso['fecha_pedida']; ?>">
            <input type="hidden" name="fecha_peticion" value="<?= $permiso['fecha_peticion']; ?>">
            <input type="hidden" name="apenom" value="<?= $permiso['apenom']; ?>">
            <input type="hidden" name="departamento" value="<?= $permiso['departamento']; ?>">
            <input type="hidden" name="estado" value="<?= $permiso['estado']; ?>">
            <input type="hidden" name="usuario" value="<?= $permiso['usuario']; ?>">
            <input type="hidden" name="npeticion" value="<?= $permiso['npeticion']; ?>">
            <input type="hidden" name="dia_lectivo" value="<?= $permiso['dia_lectivo']; ?>">

            <div class="form-group row mb-1">
                <label for="usuario" class="col-sm-2 col-form-label">Profesor</label>
                <div class="col-6">
                    <input readonly type="text" class="form-control" name="usuario" value="<?= $permiso['apenom']; ?>">
                </div>
            </div>

            <div class="form-group row mb-1">
                <label for="fecha_pedida" class="col-sm-2 col-form-label">Fecha pedida</label>
                <div class="col-3">
                    <input readonly type="date" class="form-control" name="fecha_pedida" value="<?= $permiso['fecha_pedida']; ?>">
                </div>
            </div>
            <input type="hidden" name="firmaBase64">
        </form>
    </div>
    <hr>
    <div class="row mb-2">
        <div class="col-6 mx-auto">
            <?php
            $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
            ?>
            <p>En Cáceres a <?php echo date("d"); ?> de <?php echo $meses[date("n") - 1]; ?> de <?php echo date("Y"); ?></p>
            <p>Firmado</p>
        </div>
    </div>
    <div class="row">
        <div class="col-8 col-md-6 col-lg-4">
            <div id="signatureArea" width="400" height="150">
                <div style="height:auto;">
                    <canvas id="signaturePad" width="400" height="150"></canvas>
                </div>
            </div>

        </div>
        <div class="col-4 col-md-2 ">
            <div class="d-grid gap-2 col-2 m-auto">
                <button title="Pulse aqui para solicitar el día" type="button" id="btnGenerarDocumento" class="btn btn-primary">Solicitar</button>
                <button title="Si ha firmado mal puede limpiar la firma y volver a intentarlo" type="button" id="btnLimpiar" class="btn btn-secondary">Limpiar Firma</button>
                <button title="Si desea cancelar su solicitud, pulse aqui. " type="button" id="btnCancelar" class="btn btn-warning">Cancelar Solicitud</button>
            </div>
        </div>
    </div>
</section>
<!-- Javascript sign -->
<script src="<?php echo BASE_URL; ?>app/assets/libs/firma/numeric.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/libs/firma/bezier.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/libs/firma/jquery.signaturepad.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/libs/firma/html2canvas.js"></script>
<script>
    let salir = 1;
    $(document).ready(function() {
        $('#signatureArea').signaturePad({
            drawOnly: true,
            drawBezierCurves: true,
            lineTop: 140
        });
        $("#btnCancelar").on("click", function(evento) {
            salir = 0;
            $.post(base_url + "Permisos_c/borrarLinea", {
                id: document.frmFirma.id.value
            }, function(nada) {
                // Ir a permisos
                window.location.href = base_url + "Permisos_c/index";
            });
        });
    });

    window.addEventListener("beforeunload", (evento) => {

        if (salir) {
            evento.preventDefault();
            evento.returnValue = "";
            return "";
        }
    });

    $("#btnGenerarDocumento").on("click", function(evento) {
        salir = 0;
        // Quitar Border a la firma
        html2canvas([document.getElementById('signaturePad')], {
            onrendered: function(canvas) {
                var canvas_img_data = canvas.toDataURL('image/jpeg');
                var img_data = canvas_img_data.replace(/^data:image\/(png|jpeg);base64,/, "");
                document.frmFirma.firmaBase64.value = "data:image/jpeg;base64," + img_data;
                frmFirma.submit();
            }
        });

    });
    $("#btnLimpiar").on("click", function(evento) {
        $('#signatureArea').signaturePad().clearCanvas();
    });
</script>