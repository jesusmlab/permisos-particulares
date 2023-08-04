<?php
class Inicio_c extends Controller
{
    private $config_m; // Para acceder al modelo de configuracion

    public function __construct()
    {
        $this->config_m = $this->load_model("Config_m");
    }
    public function index()
    {
        // Visualizar la pagina de aterrizaje
        $contenido = "login_v";
        $this->load_view("plantilla/cabecera");
        $this->load_view($contenido);
        $this->load_view("plantilla/pie");
    }
    public function actconfig()
    {
        // Actualizar configuración

        $this->config_m->modificar($_REQUEST);

        header("location:" . BASE_URL . 'Permisos_c/index');
    }

    public function config()
    {
        // Metodo que presenta la vista de la configuración

        $datos['config'] = $this->config_m->leerConfig();
        if (CONGUARDIAS) {
            $datos['causas'] = $this->config_m->leerCausas();
        }
        $contenido = "config_v";
        $this->load_view("plantilla/cabecera");
        $this->load_view($contenido, $datos);
        $this->load_view("plantilla/pie");
    }
}
