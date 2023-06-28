<?php

/**
 * Controlador de Hojadevida que permite la  creacion, edicion  y eliminacion de los hoja de vida del Sistema
 */
class Page_vencimientosController extends Page_mainController
{
	public $botonpanel = 4;





	public function indexAction()
	{

		$title = "Vencimientos";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
    }
	
	

}