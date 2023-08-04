<?php
////////////////////////////////////////////////////
// Clase Model
///////////////////////////////////////////////////

class Model
{
    private $host = DB_HOST; // Nombre del host
    private $user = DB_USER; // Usuario
    private $pass = DB_PASS; // Password
    private $dbname = DB_NAME; // Nombre de la BBDD
    private $dbh; // manejador de la Base de datos. Referencia devuelta por la instanciacion del objeto PDO
    public $error; // Para poner en el el ultimo error si hay alguno
    private $stmt; // Objeto Sentencia para leer

    function __construct()
    {
        // crear DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Poner opciones
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        );
        // Probar conexion 
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        // Capturar cualquier error del tipo PDOException
        catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
    // metodo leer al que pasaremos un parametro que será la sentencia SQL que queramos.
    public function consultar($query)
    {
        // prepare es un metodo del objeto PDO
        $this->stmt = $this->dbh->prepare($query);
    }

    public function enlazar($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }


    public function ejecutar()
    {
        // devuelve un objeto resultado con todas las filas seleccionadas, por ejemplo, si es un select
        return $this->stmt->execute();
    }
    public function resultado()
    {
        // llamar al metodo anterior para ejecutar la consulta
        $this->ejecutar();
        // Devolver el resultado en un array asociativo
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function fila()
    {
        // llamar al metodo anterior para ejecutar la consulta
        $this->ejecutar();
        // Devolver el resultado en un array asociativo
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function cuenta()
    {
        return $this->stmt->rowCount();
    }
    public function ultimoId()
    {
        return $this->dbh->lastInsertId();
    }
    public function inicioTransaccion()
    {
        return $this->dbh->beginTransaction();
    }
    public function finTransaccion()
    {
        return $this->dbh->commit();
    }
    public function cancelarTransaccion()
    {
        return $this->dbh->rollBack();
    }
    public function depurarParametros()
    {
        return $this->stmt->debugDumpParams();
    }
}
