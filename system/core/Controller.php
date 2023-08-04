<?php
abstract class  Controller
{
    // private $view; //// Vista

    public function __construct()
    {
    }

    abstract function index();

    protected function load_view($vista, $params = array())
    {
        return new View($vista, $params);
    }
    protected function load_model($modelo = "")
    {
        if (is_file(ROOT . PATH_MODELS . $modelo . ".php")) {
            include ROOT . PATH_MODELS . $modelo . ".php";
            return new $modelo();
        } else {
            throw new Exception("Error. Modelo no existe");
        }
    }
}
