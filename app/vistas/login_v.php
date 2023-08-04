<section class="container mt-5">
    <div class="col-lg-6 mx-auto bm-5">
        <form name="frmLogin" action="<? echo BASE_URL; ?>Usuarios_c/autenticar" method="post" novalidate>
            <fieldset class="border p-3">
                <div class="row mb-3">
                    <label for="usuario" class="col-lg-3 col-form-label">Usuario</label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" autocomplete="new-password" name="usuario" maxlength="15" required autofocus />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="password" class="col-lg-3 col-form-label">Password</label>
                    <div class="col-lg-5">
                        <input type="password" autocomplete="new-password" name="password" class="form-control" maxlength="20" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="recuerdame" class="col-lg-3 col-form-label">Recuerdame</label>
                    <div class="col-lg-5">
                        <input type="checkbox" id="recuerdame" class="form-check-input" />
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </fieldset>
        </form>
    </div>
    <?php if (isset($_SESSION['mensajeError'])) : ?>
        <div class="row" id="mensajeError">
            <div class="col-lg-6 mx-auto">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <? echo $_SESSION['mensajeError']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php unset($_SESSION['mensajeError']);
    endif;
    ?>
</section>
<script src="<? echo BASE_URL; ?>app/assets/libs/cookies.js"></script>
<script>
    "use strict";
    // Cuando la aplicacion arranque ver si tenemos cookies y si es asi
    window.addEventListener("load", function(evento) {
        let usuario = localStorage["usuario"];
        //let password = localStorage["password"];
        if (usuario) {
            document.frmLogin.usuario.value = usuario;
            document.frmLogin.recuerdame.checked = true;
            //document.frmLogin.password.value = password;
        }
    });
    document.frmLogin.addEventListener("submit", function(evento) {
        // Prevenir envio del formulario
        evento.preventDefault();

        if (document.frmLogin.recuerdame.checked == true) {
            // Guardar una cookie con el usuario
            localStorage.usuario = document.frmLogin.usuario.value;
            //localStorage.password = document.frmLogin.password.value;
        } else {
            localStorage.removeItem("usuario");
            //localStorage.removeItem("password");
        }
        // Validacion
        if (
            document.frmLogin.usuario.value.length > 0 &&
            document.frmLogin.password.value.length > 0
        ) {
            document.frmLogin.submit();
        } else {
            //alert("Falta el usuario o el password");
            // Poner clase was-validated al formulario
            document.frmLogin.classList.add("was-validated");
        }
    });
</script>