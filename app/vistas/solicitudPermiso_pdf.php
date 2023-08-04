<style type="text/css">
    table {
        vertical-align: top;
    }

    tr {
        vertical-align: top;
    }

    td {
        vertical-align: top;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    p,
    ul {
        margin-top: 8px;
        margin-bottom: 8px;
    }

    .titulo {
        border: 1px solid black;
    }
</style>
<page backtop="10mm" backbottom="10mm" backleft="25mm" backright="15mm" style="font-size: 12pt; font-family: arial">
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 12pt;">
        <tr>
            <td style="width: 25%; color: #34495e;font-size:12px;text-align:center">
                <h4>NOMBRE DEL CENTRO"</h4>
                <span>Direccion, numero</span>
                <br>
                <span>Codigo Postal y Provincia</span>
                <br>
                <span>Teléfono: 123 121234</span>
                <br>
                <span>Email:centro@centro.es</span>
            </td>
            <td style="width: 50%; color: #444444;text-align:center;">
                <img style="width: 70%;" src="<?php echo BASE_URL; ?>app/assets/img/otroslogosedu.png" alt="Logo">
            </td>
            <td style="width: 25%; color: #444444;text-align: right;">
                <img style="width: 70%;" src="<?php echo BASE_URL; ?>app/assets/img/logocentro.png" alt="Logo">
            </td>
        </tr>
    </table>
    <br>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 12pt;">
        <tr>
            <td style="text-align:center; width:100%;">
                <h3>CONCESION DE PERMISOS</h3>
            </td>
        </tr>
    </table>
    <br>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left" ;>
        <tr>
            <td colspan="2" style="text-align:center">
                <h4>Solicitante</h4>
                <br>
            </td>
        </tr>
        <tr>
            <td style=" width:30%;"><strong>D./Dña.:</strong>
            </td>
            <td style="width:60%">
                <?php echo $_REQUEST['apenom']; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
            </td>
        </tr>
        <tr>
            <td style="width:30%;">
                <strong>Departamento:</strong>
            </td>
            <td style="width:50%;">
                <?php echo $_REQUEST['departamento']; ?>
            </td>
        </tr>
    </table>
    <br>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left" ;>
        <tr>
            <td colspan="2" style="text-align:center">
                <h4>Permiso que solicita</h4>
                <br>
            </td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>Por asuntos particulares para el día:</strong>
            </td>
            <td style="width:30%">
                <?php echo date("d/m/Y", strtotime($_REQUEST['fecha_pedida'])); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
            </td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>Nº de Petición:</strong></td>
            <td style="width:30%">
                <?php echo $_REQUEST['npeticion']; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
            </td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>Día lectivo:</strong></td>
            <td style="width:30%">
                <?php echo $_REQUEST['dia_lectivo'] ? "SI" : "NO"; ?>
            </td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <br>
    <?php
    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    ?>
    <p style="text-align: right;">En Localidad a <?php echo date("d", strtotime($_REQUEST['fecha_peticion'])); ?> de <?php echo $meses[date("n", strtotime($_REQUEST['fecha_peticion'])) - 1]; ?> de <?php echo date("Y", strtotime($_REQUEST['fecha_peticion'])); ?></p>
    <br>
    <div id="firma" style="width: 95%;text-align: right;">
        <img src="<?php echo isset($_REQUEST['firmaBase64']) ? $_REQUEST['firmaBase64'] : ROOT . "app/assets/img/sinFirma.jpg"; ?>" alt="">
    </div>
    <div style="text-align: center;">Firmado</div>
</page>