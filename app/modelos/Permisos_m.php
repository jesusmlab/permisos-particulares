<?php
class Permisos_m extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insertar($datos)
    {

        if (isset($datos['firmaBase64'])) unset($datos['firmaBase64']); // Quitar la causa del array

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
        $cadSQL = "INSERT INTO permisos_gesprof ($columnas) VALUES ($parametros)";
        $this->consultar($cadSQL);   // Preparar sentencia
        for ($ind = 0; $ind < count($campos); $ind++) {    // Enlace de parametros
            $this->enlazar($campos[$ind], $datos[array_keys($datos)[$ind]]);
        }
        $this->ejecutar();
        return $this->ultimoId();
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
        $cadSQL = "UPDATE permisos_gesprof SET ";
        // Poner todos los campos y parametros
        for ($ind = 0; $ind < count($campos); $ind++) {
            $cadSQL .= array_keys($datos)[$ind] . "=" . $campos[$ind] . ",";
        }
        $cadSQL = substr($cadSQL, 0, strlen($cadSQL) - 1); // quitar la ultima coma
        $cadSQL .= " WHERE id='$datos[id]'"; // Añadir el WHERE
        $this->consultar($cadSQL);   // Preparar sentencia
        for ($ind = 0; $ind < count($campos); $ind++) {    // Enlace de parametros
            $this->enlazar($campos[$ind], $datos[array_keys($datos)[$ind]]);
        }
        return $this->ejecutar();
    }
    public function borrar($id)
    {
        $cadSQL = "DELETE FROM permisos_gesprof WHERE id=$id";
        $this->consultar($cadSQL);
        return $this->ejecutar();
    }
    public function leerPermiso($id)
    {
        $cadSQL = "SELECT * FROM permisos_gesprof WHERE id=$id";
        $this->consultar($cadSQL);
        return $this->fila();
    }
    public function leerPermisos($usu)
    {
        $cadSQL = "SELECT * FROM permisos_gesprof WHERE usuario='$usu' ORDER BY npeticion";
        $this->consultar($cadSQL);
        return $this->resultado();
    }
    public function leerPermisosValidos($usu)
    {
        $cadSQL = "SELECT * FROM permisos_gesprof WHERE usuario='$usu' and estado in ('C','S','R') ORDER BY npeticion";
        $this->consultar($cadSQL);
        return $this->resultado();
    }
    public function leerPermisosRegistro()
    {
        $cadSQL = "SELECT permisos_gesprof.*,apenom FROM permisos_gesprof INNER JOIN usuarios_gesprof ON permisos_gesprof.usuario=usuarios_gesprof.usuario WHERE fecha_registro='0000-00-00' ORDER BY fecha_peticion, id";
        $this->consultar($cadSQL);
        return $this->resultado();
    }
    public function leerPermisosFecha($fecha)
    {
        $cadSQL = "SELECT count(*) as totaldia,SUM(IF(horario='D', 1, 0)) AS diurno, SUM(IF(horario='V', 1, 0)) AS vespertino FROM permisos_gesprof where fecha_pedida='$fecha' and estado='C'";
        $this->consultar($cadSQL);
        return $this->fila();
    }
    public function leerPermisosFechaDet($fecha)
    {
        $cadSQL = "SELECT permisos_gesprof.*,apenom,departamento FROM permisos_gesprof INNER JOIN usuarios_gesprof ON permisos_gesprof.usuario=usuarios_gesprof.usuario where fecha_pedida='$fecha' and estado='C' ORDER BY horario,npeticion,fecha_peticion";
        $this->consultar($cadSQL);
        return $this->resultado();
    }
    public function leerTodosPorFecha($fdesde, $fhasta)
    {
        $cadSQL = "SELECT fecha_pedida as start,count(*) as title,SUM(IF(horario='D', 1, 0)) AS diurno, SUM(IF(horario='V', 1, 0)) AS vespertino FROM permisos_gesprof WHERE fecha_pedida between '$fdesde' and '$fhasta' and estado='C' GROUP BY fecha_pedida";
        $this->consultar($cadSQL);
        return $this->resultado();
    }
    public function leerTodosPermisos()
    {
        $cadSQL = "SELECT permisos_gesprof.*,apenom FROM permisos_gesprof INNER JOIN usuarios_gesprof ON permisos_gesprof.usuario=usuarios_gesprof.usuario ORDER BY usuario,fecha_pedida";
        $this->consultar($cadSQL);
        return $this->resultado();
    }
    public function totalRegistros($busq, $estado)
    {
        $cadSQL = "SELECT count(*) as totalR FROM permisos_gesprof WHERE usuario like :busq and estado like :estado ORDER BY id";
        $this->consultar($cadSQL);
        // Enlazar parametros
        $this->enlazar(":busq", "%$busq%");
        $this->enlazar(":estado", "$estado");
        // Retornar el numero total de registros
        return $this->fila()['totalR'];
    }
    public function leerConFiltro($comienzo, $registros, $busq, $estado)
    {
        $cadSQL = "SELECT permisos_gesprof.*,apenom FROM permisos_gesprof INNER JOIN usuarios_gesprof ON permisos_gesprof.usuario=usuarios_gesprof.usuario WHERE apenom like :busq and estado like :estado ORDER BY id LIMIT $comienzo,$registros";

        $this->consultar($cadSQL);
        // Enlazar parametros
        $this->enlazar(":busq", "%$busq%");
        $this->enlazar(":estado", "$estado");
        return $this->resultado();
    }
    public function leerPermisosEstado($estado, $fecha)
    {
        $cadSQL = "SELECT * FROM permisos_gesprof WHERE estado='$estado' and DATE_ADD(DATE(fecha_registro),INTERVAL 3 DAY)<=DATE('$fecha') ORDER BY npeticion,fecha_registro";
        $this->consultar($cadSQL);
        return $this->resultado();
    }
    public function insertarFalta($datos)
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
        $cadSQL = "INSERT INTO faltas ($columnas) VALUES ($parametros)";
        $this->consultar($cadSQL);   // Preparar sentencia
        for ($ind = 0; $ind < count($campos); $ind++) {    // Enlace de parametros
            $this->enlazar($campos[$ind], $datos[array_keys($datos)[$ind]]);
        }
        $this->ejecutar();
        return $this->ultimoId();
    }
    public function borrarFalta($id)
    {
        $cadSQL = "DELETE FROM faltas WHERE id=$id";
        $this->consultar($cadSQL);
        return $this->ejecutar();
    }
}
