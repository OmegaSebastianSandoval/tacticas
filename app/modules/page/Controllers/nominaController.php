<?php
/**
* Controlador de Historial que permite la  creacion, edicion  y eliminacion de los historial del Sistema
*/
class Page_nominaController extends Page_mainController
{
    public $botonpanel = 6;
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
		if ((Session::getInstance()->get("kt_login_level") == '2' )) {
			header('Location: /page/panel');
		}
		$title = "Administración de nómina";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		
	}
  

}