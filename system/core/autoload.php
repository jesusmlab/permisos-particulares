<?php
////////// Este codigo autocarga todas las clases que esten en el Core
spl_autoload_register(function ($nombre_clase) {
    if (is_file(CORE . $nombre_clase . ".php")) {
        include CORE . $nombre_clase . '.php';
    }
});
