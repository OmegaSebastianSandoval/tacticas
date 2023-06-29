<?php

/**
 * Controlador de Localizaciones que permite la  creacion, edicion  y eliminacion de los locaci&oacute;n del Sistema
 */
class Page_infolocalizacionController extends Page_mainController
{
	public $botonpanel = 6;
	/**
	 * $mainModel  instancia del modelo de  base de datos locaci&oacute;n
	 * @var modeloContenidos
	 */
	public $mainModel;

	/**
	 * $route  url del controlador base
	 * @var string
	 */
	protected $route;

	/**
	 * $pages cantidad de registros a mostrar por pagina]
	 * @var integer
	 */
	protected $pages;

	/**
	 * $namefilter nombre de la variable a la fual se le van a guardar los filtros
	 * @var string
	 */
	protected $namefilter;

	/**
	 * $_csrf_section  nombre de la variable general csrf  que se va a almacenar en la session
	 * @var string
	 */
	protected $_csrf_section = "page_infolocalizacion";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador infolocalizacion .
	 *
	 * @return void.
	 */
	public function init()
	{
		/* if ((Session::getInstance()->get("kt_login_level") == '2' )) {
			header('Location: /page/panel');
		} */
		$this->mainModel = new Page_Model_DbTable_Localizaciones();
		$this->namefilter = "parametersfilterinfolocalizacion";
		$this->route = "/page/infolocalizacion";
		$this->namepages = "pages_infolocalizacion";
		$this->namepageactual = "page_actual_infolocalizacion";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 50;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  locaci&oacute;n con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Informe de localizaciones";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->_view->list_facturadas = $this->getFacuradas();
		//print_r($this->getFacuradas());
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		// $this->_view->list_empresa = $this->getEmpresa();

		$filters = (object)Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		echo $filters = $this->getFilter();
	}

	private function getFacuradas()
	{
		$modelData = new Page_Model_DbTable_Facturadas();
		$data = $modelData->getFacturadas();
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->id] = $value->fecha1 . " - " . $value->fecha2;
		}
		return $array;
	}

	protected function getFilter()
	{
		// $filtros = " 1 = 1 ";
		$filtros = " ";
		/* 
		if(Session::getInstance()->get("kt_login_level") == 2){
			$empresa = Session::getInstance()->get("kt_login_empresa");
			$filtros = 	$filtros . " AND empresa = '$empresa' ";
		}
		if(Session::getInstance()->get("kt_login_level") == 3){
			$asignacion = Session::getInstance()->get("kt_login_asignacion");
			$filtros = 	$filtros . " AND FIND_IN_SET(empresa, '$asignacion') ";
		} */

		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object)Session::getInstance()->get($this->namefilter);

			if ($filters->fecha_inicio != '' && $filters->fecha_fin != '') {
				$filtros = $filtros . " fecha1>='" . $filters->fecha_inicio . "' AND fecha1 <='" . $filters->fecha_fin . "' AND fecha2>='" . $filters->fecha_inicio . "' AND fecha2 <='" . $filters->fecha_fin . "' ";
			}
			if ($filters->fecha_completa != '') {

				$filtros = $filtros . " AND planilla.empresa ='" . $filters->fecha_completa . "'";
			}
			// $query_rsPlanillas = "SELECT * FROM planilla WHERE fecha1>='$fecha_inicio' AND fecha1 <='$fecha_fin' AND fecha2>='$fecha_inicio' AND fecha2 <='$fecha_fin' $filtro_empresa ";

			// " AND tipo_documento ='" . $filters->tipo_documento . "'"


		}
		return $filtros;
	}

	/**
	 * Recibe y asigna los filtros de este controlador
	 *
	 * @return void
	 */
	protected function filters()
	{
		if ($this->getRequest()->isPost() == true) {
			Session::getInstance()->set($this->namepageactual, 1);
			$parramsfilter = array();
			$parramsfilter['fecha_completa'] = $this->_getSanitizedParam("fecha_completa");
		
			if ($parramsfilter['fecha_completa'] != '') {
				// Separar las fechas en un array utilizando el guion como delimitador
				$fechasSeparadas = explode(" - ", $parramsfilter['fecha_completa']);
		
				// Obtener las fechas en variables individuales
				$parramsfilter['fecha_inicio'] = $fechasSeparadas[0];
				$parramsfilter['fecha_fin'] = $fechasSeparadas[1];
			} else {
				$parramsfilter['fecha_inicio'] = $this->_getSanitizedParam("fecha_inicio");
				$parramsfilter['fecha_fin'] = $this->_getSanitizedParam("fecha_fin");
			}
		
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}
