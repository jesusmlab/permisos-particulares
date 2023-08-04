<?php
// Abrir session si no esta abierta
if (!session_id()) session_start();
// Incluir la configuración de la aplicacion
require "system/config.php";
// Cargar todas las clases del core
require "system/core/autoload.php";
// Instanciamos Router
$router = new Router();

// Obtenemos datos necesarios de la URI
$controlador = $router->getController();
$metodo = $router->getMethod();
$parametros = $router->getParams();

// Comprobacion de que el controlador exista
if (!is_file(PATH_CONTROLLERS . "{$controlador}.php")) $controlador = "ErrorPage";

// Incluir el controlador que está en la URI
include PATH_CONTROLLERS . "{$controlador}.php";

// Instanciamos el controlador
$micontrolador = new $controlador();

// Verificar que el metodo de la URI exista
if (!method_exists($micontrolador, $metodo)) $metodo = "index";

// lamar al controlador con los parametros
if (empty($parametros)) {
    $micontrolador->$metodo();
} else {
    $micontrolador->$metodo($parametros);
}
