<?php
class View
{
    protected $template;
    protected $vista;
    protected $params;

    public function __construct($vista, $params = array())
    {
        $this->vista = $vista;
        $this->params = $params;
        $this->render();
    }

    protected function render()
    {
        $this->template = $this->getContentTemplate($this->vista);
        echo $this->template;
    }
    protected function getContentTemplate($file)
    {
        $file_path = ROOT . PATH_VIEWS . $file . ".php";
        // Si existe el fichero
        if (is_file($file_path)) {
            // Extraer los parametros del array de variables
            extract($this->params);
            // Bufer
            ob_start();
            require $file_path; // Mete la vista en el buffer
            $plantilla = ob_get_contents(); // Obtiene el buffer
            ob_end_clean(); // Finaliza y limpia el buffer
            return $plantilla;
        } else {
            throw new Exception("No existe la vista" . $file_path);
        }
    }
}
