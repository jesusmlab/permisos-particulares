<?php
class Router
{
    // Propiedades

    private $uri; //// Array que contendrá nuestra uri
    private $controller; /// Controlador
    private $method; // Metodo
    private $params; //Array de parametros

    public function __construct()
    {
        // Obtener los valores de la URI con los metodos setters
        $this->setUri();
        $this->setController();
        $this->setMethod();
        $this->setParams();
    }
    /////// Metodos Setters

    public function setUri()
    {
        // Usamos la funcion explode de php para separar las diferentes partes
        $this->uri = explode("/", URI);
    }
    public function setController()
    {
        // Coger la parte con indice 2 del array uri
        $this->controller = empty($this->uri[2]) ? DEFAULT_CONTROLLER : $this->uri[2];
    }
    public function setMethod()
    {
        // En este metodo lo haremos con isset por si no existe el elemento del array
        $this->method = empty($this->uri[3]) ? DEFAULT_METHOD : $this->uri[3];
    }
    public function setParams()
    {
        for ($ind = 4; $ind < count($this->uri); $ind++) {
            $this->params[] = !isset($this->uri[$ind]) ? "" : $this->uri[$ind];
        }
    }

    /////////Metodos Getters

    public function getUri()
    {
        return $this->uri;
    }
    public function getController()
    {
        return $this->controller;
    }
    public function getMethod()
    {
        return $this->method;
    }
    public function getParams()
    {
        return $this->params;
    }
}
