<?php

/*********************************************
 * Clase para paginar registros
 * 
 * parametros
 * total_registros = numero de registros totales de la fuente de datos
 * comienzo = registro de comienzo para clausula limit
 * limite_por_pagina = Numero de registros a visualizar en cada pagina, "todas" no pagina
 * url = controlador y metodo para poner en cada link del paginador
 * 
 *  */
class Paginador
{

    private $_limite_por_pagina;
    private $_comienzo;
    private $_total_registros;
    private $_url;

    public function __construct($total_registros, $comienzo, $limite_por_pagina, $url)
    {


        $this->_total_registros = $total_registros;
        $this->_comienzo = $comienzo;
        $this->_limite_por_pagina = $limite_por_pagina;
        $this->_url = $url;
    }

    public function crearLinks($links = 10, $lista_clases = "")
    {
        if ($this->_limite_por_pagina == 'todas') {
            return '';
        }

        $url = $this->_url;

        $ultima      = ceil($this->_total_registros / $this->_limite_por_pagina);

        if ($ultima == 1) {
            return '';  // Si solo hay una pagina pues no sacar paginador
        }
        $inicial      = ((($this->_comienzo / $this->_limite_por_pagina) - $links) > 0) ? ($this->_comienzo / $this->_limite_por_pagina) - $links : 1;
        $final        = ((($this->_comienzo / $this->_limite_por_pagina) + $links) < $ultima) ? ($this->_comienzo / $this->_limite_por_pagina) + $links : $ultima;

        $html       = '<ul class="' . $lista_clases . '">';

        $clase      = ($this->_comienzo == 0) ? "disabled" : "";
        $html       .= '<li class="page-item ' . $clase . '"><a class="page-link" href="' . $url . ($this->_comienzo - $this->_limite_por_pagina) . '">&laquo;</a></li>';

        if ($inicial > 1) {
            $html   .= '<li><a class="page-link" href="' . $url . '0">1</a></li>';
            $html   .= '<li class="page-item disabled"><span>...</span></li>';
        }

        for ($i = $inicial; $i <= $final; $i++) {
            $clase  = ($this->_comienzo == (($i - 1) * $this->_limite_por_pagina)) ? "active" : "";
            $html   .= '<li class="page-item ' . $clase . '"><a class="page-link" href="' . $url . (($i - 1) * $this->_limite_por_pagina) . '">' . $i . '</a></li>';
        }

        if ($final < $ultima) {
            $html   .= '<li class="page-item disabled"><span>...</span></li>';
            $html   .= '<li><a class="page-link" href="' . $url . $ultima . '">' . $ultima . '</a></li>';
        }

        $clase      = ($this->_comienzo == ($ultima - 1) * $this->_limite_por_pagina) ? "disabled" : "";
        $html       .= '<li class="page-item ' . $clase . '"><a class="page-link" href="' . $url . ($this->_comienzo + $this->_limite_por_pagina) . '">&raquo;</a></li>';

        $html       .= '</ul>';

        return $html;
    }
}
