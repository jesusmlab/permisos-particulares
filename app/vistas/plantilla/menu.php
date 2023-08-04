<?php
$menu = "2";
if (isset($_SESSION['menuActivo'])) {
    $menu = $_SESSION['menuActivo'];
}
?>
<nav class="row justify-content-center">
    <ul class="nav nav-pills col-lg-8 justify-content-center">
        <li data-menu="1" class="nav-item">
            <a class="nav-link" href="<?= BASE_URL; ?>/Inicio_c/index">Inicio</a>
        </li>
        <li data-menu="2" class="nav-item">
            <a class="nav-link" href="<?= BASE_URL; ?>Articulos_c/catalogo">Catálogo</a>
        </li>
        <li data-menu="3" class="nav-item">
            <a class="nav-link" href="#">Quienes Somos</a>
        </li>
        <li data-menu="4" class="nav-item">
            <a class="nav-link" href="#">Soporte</a>
        </li>
    </ul>
</nav>
<script>
    // Obtener la variable del menu activo
    let menuActivo = <?= $menu; ?>;

    // Poner el menu activo
    $("nav li").eq(menuActivo - 1).children("a").addClass("active");

    // Activar la opcion corriente y desactivar las demas
    $("nav ul").on("click", "li", function(evento) {
        //Desactivar el activo
        $("nav li a.active").removeClass("active");
        // Activar el activo
        $(this).children("a").addClass("active");
        // Enviar el numero del menu activo a una variable de sesion por AJAX
        $.post(base_url + "inicio_c/menuActivo_Ajax", {
            "menuActivo": this.dataset.menu
        });
    })
</script>