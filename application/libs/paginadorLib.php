<?php

/*
 * + --------------------------------------------------------- +
 * |  Software:	Paginador - clase PHP para paginar registros   |
 * |   Version:	1.0											   |
 * |  Licencia:	Distribuido de forma libre					   |
 * |     Autor:	Jaisiel Delance								   |
 * | Sitio Web:	http://www.dlancedu.com						   |
 * + --------------------------------------------------------- +
 *
 */


class Paginador
{
    private $_datos;
    private $_paginacion;
    
    public function __construct() {
        $this->_datos = array();
        $this->_paginacion = array();
    }
    
    public function paginar($query, $pagina = false, $limite = false, $paginacion = false)
    {
        if($limite && is_numeric($limite)){
            $limite = $limite;
        } else {
            $limite = 9;
        }
        
        if($pagina && is_numeric($pagina)){
            $pagina = $pagina;
            $inicio = ($pagina - 1) * $limite;
        } else {
            $pagina = 1;
            $inicio = 0;
        }
        
        if(isset($query)){
            $registros = count($query);
            $total = ceil($registros / $limite);
            $this->_datos = array_slice($query, $inicio, $limite);

            $limitpage = $paginacion;       
            $paginacion = array();
            $paginacion['actual'] = $pagina;
            $paginacion['total'] = $total;
            $paginacion['limite'] = $limite;

            if($pagina > 1){
                $paginacion['primero'] = 1;
                $paginacion['anterior'] = $pagina - 1;
            } else {
                $paginacion['primero'] = '';
                $paginacion['anterior'] = '';
            }

            if($pagina < $total){
                $paginacion['ultimo'] = $total;
                $paginacion['siguiente'] = $pagina + 1;
            } else {
                $paginacion['ultimo'] = '';
                $paginacion['siguiente'] = '';
            }

            $this->_paginacion = $paginacion;
            $this->_rangoPaginacion($limitpage);

            return $this->_datos;
        }else{
            return false;
        }
    }
    
    private function _rangoPaginacion($limite = false)
    {
        if($limite && is_numeric($limite)){
            $limite = $limite;
        } else {
            $limite = 8;
        }
        
        $total_paginas = $this->_paginacion['total'];
        $pagina_seleccionada = $this->_paginacion['actual'];
        $rango = ceil($limite / 2);
        $paginas = array();
        
        $rango_derecho = $total_paginas - $pagina_seleccionada;
        
        if($rango_derecho < $rango){
            $resto = $rango - $rango_derecho;
        } else {
            $resto = 0;
        }
        
        $rango_izquierdo = $pagina_seleccionada - ($rango + $resto);
        
        for($i = $pagina_seleccionada; $i > $rango_izquierdo; $i--){
            if($i == 0){
                break;
            }
            
            $paginas[] = $i;
        }
        
        sort($paginas);
        
        if($pagina_seleccionada < $rango){
            $rango_derecho = $limite;
        } else {
            $rango_derecho = $pagina_seleccionada + $rango;
        }
        
        for($i = $pagina_seleccionada + 1; $i <= $rango_derecho; $i++){
            if($i > $total_paginas){
                break;
            }
            
            $paginas[] = $i;
        }
        
        $this->_paginacion['rango'] = $paginas;
        
        return $this->_paginacion;
        
    }

	public function getView($vista, $idpagination = false, $link = false )
	{
		$rutaView = Config::get('PATH_VIEW') . '_pagination/' . $vista . '.php';
		if($link)
		$link = Config::get('URL') . $link . '/';
		
        if(!$idpagination):
            $classpagination = 'page';
            $datattr = 'data-page';
        else:
            $classpagination = 'page-'.$idpagination;
            $datattr = 'data-' . $idpagination;
        endif; 
            
		if(is_readable($rutaView)){
			ob_start();			
			include $rutaView;			
			$contenido = ob_get_contents();			
			ob_end_clean();			
			return $contenido;
		}
		
		throw new Exception('Error de paginacion');		
	}
}

?>
