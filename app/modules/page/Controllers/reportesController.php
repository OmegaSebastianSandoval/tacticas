<?php
/**
* Controlador de Historial que permite la  creacion, edicion  y eliminacion de los historial del Sistema
*/
class Page_reportesController extends Page_mainController
{
    public $botonpanel = 5;
	/**
	 * $mainModel  instancia del modelo de  base de datos historial
	 * @var modeloContenidos
	 */
	


	/**
     * Recibe la informacion y  muestra un listado de  historial con sus respectivos filtros.
     *
     * @return void.
     */
	public function indexAction()
	{
		$title = "Administración de reportes";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		
	}
  

}