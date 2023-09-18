<?php

class Usuarios_c extends Controller
{
    private $usuarios_m; // Propiedad para instanciar el modelo de Usuarios

    public function __construct()
    {

        // Instanciar modelo Usuarios
        $this->usuarios_m = $this->load_model("Usuarios_m");
    }
    public function index()
    {
    }
    public function login()
    {

        if (isset($_SESSION['sesion'])) {
            header("location:" . BASE_URL . "Permisos_c/index");
        } else {
            // Metodo que presenta la vista del Login
            $contenido = "login_v";
            $this->load_view("plantilla/cabecera");
            $this->load_view($contenido);
            $this->load_view("plantilla/pie");
        }
    }
    public function logout()
    {
        // Destruimos la sesion
        unset($_SESSION['sesion']);
        // Volver al inicio
        header("location:" . BASE_URL . "Inicio_c/index");
    }
    public function autenticar()
    {
        // recibimos usuario y password y lo enviamos al metodo de AUTENTICACION
        if (AUTENTICACION == "LDAP") {
            $usuario = $this->autenticar_ldap($_REQUEST['usuario'], $_REQUEST["password"]);
        } else {
            $usuario = $this->autenticar_self($_REQUEST['usuario'], $_REQUEST["password"]);
        }

        //$usuario = $this->autenticar_ldap($_REQUEST['usuario'], $_REQUEST["password"]);
        //$usuario = "   Jesus M,22,OU=Profesores";
        if ($usuario == "0" || $usuario == '' || $usuario == null) {
            // Si el usuario no existe, retornar al login y dar mensaje de error
            $_SESSION['mensajeError'] = "No existe el usuario o password";
            header("location:" . BASE_URL . "Usuarios_c/login");
        } else {

            if (AUTENTICACION == "LDAP") {
                // Extraer lo campos que devuelve LDAP
                $campos = explode(",", $usuario);
                // Crear variable sesion para guardar info usuario
                $_SESSION['sesion'] = [
                    'usuario' => $_REQUEST['usuario'],
                    'password' => password_hash($_REQUEST["password"], PASSWORD_DEFAULT),
                    'rol' => substr($campos[2], 3, 1),
                    'apenom' => substr($campos[0], 3),
                    'horario' => "D"
                ];
            }
            // Leer usuario a ver si está en tabla de usuarios_gesprof
            $fila = $this->usuarios_m->leer($_REQUEST['usuario']);
            // SI no está, insertarlo y redirigir a perfil
            if (empty($fila)) {
                $this->usuarios_m->insertar($_SESSION['sesion']);
                header("location:" . BASE_URL . "Usuarios_c/perfil");
            } else {
                // Si ya existe recoger las propiedades del usuario en esa tabla
                $_SESSION['sesion']['usuario'] = $fila['usuario'];
                $_SESSION['sesion']['apenom'] = $fila['apenom'];
                $_SESSION['sesion']['rol'] = $fila['rol'];
                $_SESSION['sesion']['email'] = $fila['email'];
                $_SESSION['sesion']['dpto'] = $fila['departamento'];
                $_SESSION['sesion']['horario'] = $fila['horario'];
                // Si falta algun campo, redirigir al perfil
                if (empty($fila['email']) or empty($fila['departamento'])) {
                    header("location:" . BASE_URL . "Usuarios_c/perfil");
                } else {
                    header("location:" . BASE_URL . "Permisos_c/index");
                }
            }
        }
    }
    public function autenticar_ldap($user, $pass)
    {
        $ldaprdn = trim($user) . '@' . DOMINIO;
        $ldappass = trim($pass);
        $ds = SRVLDAP;
        $dn = DN;
        $puertoldap = PUERTO_LDAP;
        $ldapconn = ldap_connect($ds, $puertoldap);
        if ($ldapconn) {
            error_log("conectado a servidor LDAP");
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
            $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
            if ($ldapbind) {
                $filter = "(|(SAMAccountName=" . trim($user) . "))";
                $fields = array("SAMAccountName");
                $sr = @ldap_search($ldapconn, $dn, $filter, $fields);
                $info = @ldap_get_entries($ldapconn, $sr);
                $array = $info[0]["dn"];
            } else {
                $array = 0;
            }
            ldap_close($ldapconn);
        } else {
            $array = 0;
        }
        return $array;
    }

    public function autenticar_self($user, $pass)
    {
        return $fila = $this->usuarios_m->autenticar($user, $pass);
    }

    public function insertar()
    {
        // Este metodo inserta un registro de usuario si no existe

        // Encriptar password si se ha escrito algo, si no se deja como está
        if (isset($_REQUEST['password'])) {
            if (!empty($_REQUEST['password'])) {
                $_REQUEST['password'] = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
            } else {
                unset($_REQUEST['password']);
            }
        }

        // Enviamos los datos al metodo insertar del modelo
        $this->usuarios_m->insertar($_REQUEST);
        header("location:" . $_SERVER['HTTP_REFERER']);
    }
    public function modificar()
    {
        // Encriptar password si se ha escrito algo, si no se deja como está
        if (isset($_REQUEST['password'])) {
            if (!empty($_REQUEST['password'])) {
                $_REQUEST['password'] = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
            } else {
                unset($_REQUEST['password']);
            }
        }

        // Modificamos campos del usuario
        $this->usuarios_m->modificar($_REQUEST);
        header("location:" . $_SERVER['HTTP_REFERER']);
    }
    public function actperfil()
    {
        // Actualización del perfil de usuario

        // Encriptar password si se ha escrito algo, si no se deja como está
        if (isset($_REQUEST['password'])) {
            if (!empty($_REQUEST['password'])) {
                $_REQUEST['password'] = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
            } else {
                unset($_REQUEST['password']);
            }
        }

        $this->usuarios_m->actualizarPerfil($_REQUEST);
        // Refrescar variable sesion por si ha cambiado algo en el perfil
        $_SESSION['sesion']['rol'] = $_REQUEST['rol'];
        $_SESSION['sesion']['email'] = $_REQUEST['email'];
        $_SESSION['sesion']['dpto'] = $_REQUEST['departamento'];
        $_SESSION['sesion']['horario'] = $_REQUEST['horario'];
        $_SESSION['sesion']['apenom'] = $_REQUEST['apenom'];
        header("location:" . BASE_URL . "Permisos_c/index");
    }

    public function perfil()
    {
        // Metodo que presenta la vista de Perfil de usuario

        // Leer perfil
        $datos['perfil'] = $this->usuarios_m->leer($_SESSION['sesion']['usuario']);
        if (CONGUARDIAS) {
            // Leer datos de profesores de aplicacion de Guardias y Horarios
            $datos['profesores'] = $this->usuarios_m->leerProfesores();
        }
        $contenido = "perfil_v";
        $this->load_view("plantilla/cabecera");
        $this->load_view($contenido, $datos);
        $this->load_view("plantilla/pie");
    }
    public function mantusu($par = "")
    {
        // Mantenimiento de usuarios
        if (!isset($_SESSION['sesion'])) header("location:" . BASE_URL . "Usuarios_c/login");
        if ($_SESSION['sesion']['rol'] != "D") header("location:" . BASE_URL . "Permisos_c/index");

        // Si recibo parametro lo devuelvo a la vista nuevamente

        if (isset($_REQUEST['textoBuscado'])) {
            $buscado = urldecode($_REQUEST['textoBuscado']);
        } else {
            $buscado = "";
        }
        if (isset($_REQUEST['filtro'])) {
            $filtro = urldecode($_REQUEST['filtro']);
        } else {
            $filtro = "%%";
        }
        $datos['buscado'] = $buscado;
        $datos['filtro'] = $filtro;

        $totalRegistros = $this->usuarios_m->totalRegistros($buscado, $filtro);
        $limite_reg_por_pag = 9;
        $url = BASE_URL . "Usuarios_c/mantusu/";
        $comienzo = isset($par[0]) ? $par[0] : 0;

        $paginador = new Paginador($totalRegistros, $comienzo, $limite_reg_por_pag, $url);
        // renderizar las paginas
        $datos['paginas'] = $paginador->crearLinks(10, "pagination");

        $datos['usuarios'] = $this->usuarios_m->leerConFiltro($comienzo, $limite_reg_por_pag, $buscado, $filtro);
        if (CONGUARDIAS) {
            $datos['profesores'] = $this->usuarios_m->leerProfesores();
        }
        $contenido = "mantusu_v";
        $this->load_view("plantilla/cabecera");
        $this->load_view($contenido, $datos);
        $this->load_view("plantilla/pie");
    }
    public function borrarLinea()
    {
        // Borrar Usuario
        echo $this->usuarios_m->borrar($_REQUEST['usuario']);
    }
    public function leerUsuarioAjax()
    {
        // Este metodo recibe un parametro con el usuario y devuelve un objeto JSON con los datos del usuario
        echo json_encode($this->usuarios_m->leer($_REQUEST['usuario']));
    }
    public function cargar()
    {
        $lista = explode("\n", str_replace("\r", "", $_REQUEST['lista']));
        $profesores = [];
        foreach ($lista as $clave => $valor) {

            $profesores[] = array_map('trim', explode('|', $valor));
        }
        foreach ($profesores as &$elemento) {
            $elemento["usuario"] = $elemento[0];
            unset($elemento[0]);
            $elemento["apenom"] = $elemento[1];
            unset($elemento[1]);
            if (isset($elemento[2])) {
                $elemento["email"] = $elemento[2];
                unset($elemento[2]);
            } else {
                $elemento["email"] = $elemento["usuario"] . "@educarex.es";
            }
            $elemento['departamento'] = "";
            $elemento['rol'] = "P";
            $elemento['horario'] = "D";
            $elemento['codigoprof'] = "";
            $elemento['password'] = password_hash("profpas123*", PASSWORD_DEFAULT);
        }
        $errores = [];
        foreach ($profesores as $prof) {
            $resultado = $this->usuarios_m->leer($prof['usuario']);
            if ($resultado === false) {
                $this->usuarios_m->insertar($prof);
            }
        }
        if (count($errores) > 0) $_SESSION['mensajes'] = $errores;
        header("location:" . $_SERVER['HTTP_REFERER']);
    }
}
