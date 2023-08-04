<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="stylesheet" href="<?= BASE_URL; ?>app/assets/libs/bootstrap/css/bootstrap.min.css" />
    <script src="<?= BASE_URL; ?>app/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL; ?>app/assets/libs/jquery-3.6.3.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.2/moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.2/locale/es.js"></script>
    <title>Permisos Particulares</title>
</head>

<body>
    <script>
        const base_url = '<?= BASE_URL; ?>'
    </script>
    <?php if (isset($_SESSION['sesion'])) {
        $textoBoton = "Logout";
        $metodoBoton = "logout";
        $nombre = "Bienvenido " . $_SESSION['sesion']['apenom'];
    } else {
        $textoBoton = "Login";
        $metodoBoton = "login";
        $nombre = "";
    }
    ?>
    <div id="wrapper" class="container">
        <header class="row align-items-center text-center">
            <div id="logo" class="col-lg-3">
                <a href="<?= BASE_URL; ?>Inicio_c/index"><img class="img-fluid" width="100" src="<?= BASE_URL; ?>app/assets/img/logocentro.png" alt="" /></a>
            </div>
            <div id="brand" class="col-lg-6">
                <h3 class="display-5"><?= CENTRO; ?><h4 class="text-muted">Gestión de permisos</h4>
                </h3>
                <p><?= $nombre; ?></p>
            </div>
            <div id="interaccion" class="col-lg-3">
                <div class="row align-items-center">
                    <div class="col d-grid">
                        <a title="Entrar/Salir" href="<?= BASE_URL; ?>Usuarios_c/<?= $metodoBoton; ?>" class="btn btn-primary"><?= $textoBoton; ?></a>
                    </div>
                    <?php if (isset($_SESSION['sesion'])) : ?>
                        <div class="col d-grid">

                            <a title="Modificar mi perfil" href="<?= BASE_URL; ?>Usuarios_c/perfil" class="btn btn-primary">Perfil</a>

                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['sesion'])) {
                        if (($_SESSION['sesion']['rol'] == "D")) {
                    ?>
                            <div class="col d-grid">
                                <div class="btn-group" role="group">
                                    <button title="Mantenimiento Tablas" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Tablas
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>Inicio_c/config">Configuración</a></li>
                                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>Permisos_c/mantpermisos">Permisos</a></li>
                                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>Usuarios_c/mantusu">Usuarios</a></li>
                                    </ul>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>
        </header>