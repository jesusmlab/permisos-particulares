<?php
//////////////////////// CONFIGURACIONES MVC ///////////////////////
define('URI', $_SERVER['REQUEST_URI']);
const DEFAULT_CONTROLLER = 'Inicio_c';
const DEFAULT_METHOD = 'index';
const CORE = "system/core/";
const PATH_CONTROLLERS = "app/controladores/";
const PATH_VIEWS = "app/vistas/";
const PATH_MODELS = "app/modelos/";

define('ROOT', $_SERVER['DOCUMENT_ROOT'] . "/permisos/");
define('PATH_LIBS', "app/assets/libs/");
define("BASE_URL", 'http://' . $_SERVER["SERVER_NAME"] . '/permisos/');

////////////NOMBRE DEL CENTRO//////////////
define("CENTRO", 'NOMBRE DEL CENTRO');

////////////BBDD/////////////////
const DB_HOST = "localhost";
const DB_USER = "root";
const DB_PASS = "";
const DB_NAME = "permisos";

////////////CONECTADO A APLICACION DE GUARDIAS//////////////
define("CONGUARDIAS", false);

////////////AUTENTICACION DE USUARIOS//////////////
//define("AUTENTICACION", "LDAP");  // utiliza LDAP
define("AUTENTICACION", "SELF");    // Propia

///////////DATOS LDAP///////////////
define("SRVLDAP", "192.168.1.1");
define('DOMINIO', 'centro.local');
define('DN', 'OU=Profesores,dc=centro,dc=local');
define("PUERTO_LDAP", 389);

///////////DATOS PARA ENVIO DE CORREO/////////////////
define("EMAIL_HOST", 'smtp.office365.com');        //SMTP server para enviar
define("EMAIL_USER", 'centro@centro.es');          //SMTP Usuario
define("EMAIL_PASS", 'password');                  //SMTP password
