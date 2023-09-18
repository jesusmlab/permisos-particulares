<?php
class Usuarios_m extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function autenticar($usu, $pass)
    {
        $cadSQL = "SELECT * FROM usuarios_gesprof WHERE usuario=:usu";
        $this->consultar($cadSQL);
        $this->enlazar(":usu", $usu);
        $fila = $this->fila();
        if ($fila) {
            // Comprobar el password
            if (!password_verify($pass, $fila['password'])) {
                return 0;
            }
        }
        return $fila;
    }
    public function insertar($datos)
    {
        // Recibimos los datos del formulario en un array
        // Obtenemos cadena con las columnas desde las claves del array asociativo
        $columnas = implode(",", array_keys($datos));
        // Campos de columnas
        $campos = array_map(
            function ($col) {
                return ":" . $col;
            },
            array_keys($datos)
        );
        $parametros = implode(",", $campos); // Parametros para enlazar
        $cadSQL = "INSERT INTO usuarios_gesprof ($columnas) VALUES ($parametros)";
        $this->consultar($cadSQL);   // Preparar sentencia
        for ($ind = 0; $ind < count($campos); $ind++) {    // Enlace de parametros
            $this->enlazar($campos[$ind], $datos[array_keys($datos)[$ind]]);
        }
        return $this->ejecutar();
    }
    public function actualizarPerfil($datos)
    {
        // Recibimos los datos del formulario en un array
        // Obtenemos cadena con las columnas desde las claves del array asociativo
        $columnas = implode(",", array_keys($datos));
        // Campos de columnas
        $campos = array_map(
            function ($col) {
                return ":" . $col;
            },
            array_keys($datos)
        );
        $cadSQL = "UPDATE usuarios_gesprof SET ";
        // Poner todos los campos y parametros
        for ($ind = 0; $ind < count($campos); $ind++) {
            $cadSQL .= array_keys($datos)[$ind] . "=" . $campos[$ind] . ",";
        }
        $cadSQL = substr($cadSQL, 0, strlen($cadSQL) - 1); // quitar la ultima coma
        $cadSQL .= " WHERE usuario='$datos[usuario]'"; // Añadir el WHERE
        $this->consultar($cadSQL);   // Preparar sentencia
        for ($ind = 0; $ind < count($campos); $ind++) {    // Enlace de parametros
            $this->enlazar($campos[$ind], $datos[array_keys($datos)[$ind]]);
        }
        return $this->ejecutar();
    }
    public function activarCuenta($token)
    {
        // Activar la cuenta cuyo token sea el recibido como parametro
        $cadSQL = "UPDATE usuarios_gesprof SET activo=1 WHERE token=:token";
        $this->consultar($cadSQL);
        $this->enlazar(":token", $token);
        return $this->ejecutar();
    }
    public function existeUsuario($datos)
    {
        $clave = array_keys($datos)[0];
        $valor = array_values($datos)[0];
        $cadSQL = "SELECT count(*) as existe FROM usuarios_gesprof WHERE $clave  = '$valor'";
        //error_log($cadSQL);
        $this->consultar($cadSQL);
        return $this->fila()['existe'];
    }

    public function leer($usu)
    {
        $cadSQL = "SELECT * FROM usuarios_gesprof WHERE usuario='$usu'";
        $this->consultar($cadSQL);
        return $this->fila();
    }
    public function leerTodos()
    {
        $cadSQL = "SELECT * FROM usuarios_gesprof ORDER BY 3";
        $this->consultar($cadSQL);
        return $this->resultado();
    }
    public function leerProfesores()
    {
        $cadSQL = "SELECT * FROM profesores ORDER BY 6,4,5";
        $this->consultar($cadSQL);
        return $this->resultado();
    }
    public function totalRegistros($busq, $hor)
    {
        $cadSQL = "SELECT count(*) as totalR FROM usuarios_gesprof WHERE (apenom like :busq or departamento like :busq) and horario like :hor ORDER BY 1";
        $this->consultar($cadSQL);
        // Enlazar parametros
        $this->enlazar(":busq", "%$busq%");
        $this->enlazar(":hor", "$hor");
        // Retornar el numero total de registros
        return $this->fila()['totalR'];
    }
    public function leerConFiltro($comienzo, $registros, $busq, $hor)
    {
        $cadSQL = "SELECT * FROM usuarios_gesprof WHERE (apenom like :busq or departamento like :busq) and horario like :hor ORDER BY 1 LIMIT $comienzo,$registros";

        $this->consultar($cadSQL);
        // Enlazar parametros
        $this->enlazar(":busq", "%$busq%");
        $this->enlazar(":hor", "$hor");
        return $this->resultado();
    }
    public function borrar($usu)
    {
        $cadSQL = "DELETE FROM usuarios_gesprof WHERE usuario='$usu'";
        $this->consultar($cadSQL);
        return $this->ejecutar();
    }
    public function modificar($datos)
    {
        // Recibimos los datos del formulario en un array
        // Obtenemos cadena con las columnas desde las claves del array asociativo
        $columnas = implode(",", array_keys($datos));
        // Campos de columnas
        $campos = array_map(
            function ($col) {
                return ":" . $col;
            },
            array_keys($datos)
        );
        $cadSQL = "UPDATE usuarios_gesprof SET ";
        // Poner todos los campos y parametros
        for ($ind = 0; $ind < count($campos); $ind++) {
            $cadSQL .= array_keys($datos)[$ind] . "=" . $campos[$ind] . ",";
        }
        $cadSQL = substr($cadSQL, 0, strlen($cadSQL) - 1); // quitar la ultima coma
        $cadSQL .= " WHERE usuario='$datos[usuario]'"; // Añadir el WHERE
        $this->consultar($cadSQL);   // Preparar sentencia
        for ($ind = 0; $ind < count($campos); $ind++) {    // Enlace de parametros
            $this->enlazar($campos[$ind], $datos[array_keys($datos)[$ind]]);
        }
        return $this->ejecutar();
    }
}
