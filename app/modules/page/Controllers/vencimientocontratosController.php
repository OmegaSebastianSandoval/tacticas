<?php

/**
 * Controlador de Hojadevida que permite la  creacion, edicion  y eliminacion de los hoja de vida del Sistema
 */
class Page_vencimientocontratosController extends Page_mainController
{
	public $botonpanel = 4;

	/**
	 * $mainModel  instancia del modelo de  base de datos usuarios
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
	protected $_csrf_section = "page_vencimientocontratos";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;

	/**
	 * Inicializa las variables principales del controlador usuarios .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Hojadevida();
		$this->namefilter = "parametersfiltervencimientocontratos";
		$this->route = "/page/vencimientocontratos";
		$this->namepages = "pages_vencimientocontratos";
		$this->namepageactual = "page_actual_vencimientocontratos";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 50;
		}
		parent::init();
	}

	/**
	 * Recibe la informacion y  muestra un listado de  hoja de vida con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		
		$title = "Vencimiento de contratos";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		 $this->_view->list_empresa = $this->getEmpresa();
		 $this->_view->list_tipo = $this->getTipocontrato();

		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];

		$filters = (object)Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$filters = $this->getFilter();

		
		$order = " fecha_ingreso ASC";
		$list = $this->mainModel->getContratos($filters, $order);
		

		$amount = $this->pages;


		$page = $this->_getSanitizedParam("page");
		if (!$page && Session::getInstance()->get($this->namepageactual)) {
			$page = Session::getInstance()->get($this->namepageactual);
			$start = ($page - 1) * $amount;
		} else if (!$page) {
			$start = 0;
			$page = 1;
			Session::getInstance()->set($this->namepageactual, $page);
		} else {
			Session::getInstance()->set($this->namepageactual, $page);
			$start = ($page - 1) * $amount;
		}
		$this->_view->register_number = count($list);
		$this->_view->pages = $this->pages;
		$this->_view->totalpages = ceil(count($list) / $amount);
		$this->_view->page = $page;
		$this->_view->lists = $this->mainModel->getListPagesContratos($filters, $order, $start, $amount);
		$this->_view->csrf_section = $this->_csrf_section;

	 
			/*  echo '<pre>';
		 print_r($list);
		echo '</pre>'; */



		
	}

	/**
	 * Genera los valores del campo Tipo.
	 *
	 * @return array cadena con los valores del campo Tipo.
	 */
	private function getTipocontrato()
	{
		$array = array();
		$array['1'] = 'Permanente';
		$array['2'] = 'Definido por seis meses';
		$array['3'] = 'Definido por un año';
		$array['4'] = 'Por servicios';
		$array['5'] = 'Definido';
		return $array;
	}

/**
	 * Genera los valores del campo Empresa.
	 *
	 * @return array cadena con los valores del campo Empresa.
	 */

	 private function getEmpresa()
	 {
		 $modelData = new Page_Model_DbTable_Dependempresa();
		 if(Session::getInstance()->get("kt_login_level") == 3){
			 $asignacion = Session::getInstance()->get("kt_login_asignacion");
			 // echo $asignacion;
			 $data = $modelData->getListAsignacion(" FIND_IN_SET(id, '$asignacion') ");
		 }else if(Session::getInstance()->get("kt_login_level") == 2){
			 $empresa = Session::getInstance()->get("kt_login_empresa");
			 $data = $modelData->getList("id = '$empresa'","");
		 }else{
			 $data = $modelData->getList();
		 }
		 
		 $array = array();
		 foreach ($data as $key => $value) {
			 $array[$value->id] = $value->nombre;
		 }
		 return $array;
	 }
	 protected function getFilter()
	 {
		 $filtros = "  (retirado=0 OR retirado IS NULL) AND (tipo_contrato=2 OR tipo_contrato=3 OR tipo_contrato=5)  ";
	 
		 if(Session::getInstance()->get("kt_login_level") == 2){
			 $empresa = Session::getInstance()->get("kt_login_empresa");
			 $filtros = 	$filtros . " AND hoja_vida.empresa = '$empresa' ";
		 }
		 if(Session::getInstance()->get("kt_login_level") == 3){
			 $asignacion = Session::getInstance()->get("kt_login_asignacion");
			 $filtros = 	$filtros . " AND FIND_IN_SET(hoja_vida.empresa, '$asignacion') ";
		 }
		 Session::getInstance()->get("kt_login_empresa");
	 
		 if (Session::getInstance()->get($this->namefilter) != "") {
			 $filters = (object)Session::getInstance()->get($this->namefilter);
 
			 if ($filters->empresa != '') {
				 $filtros = $filtros . " AND hoja_vida.empresa ='" . $filters->empresa . "'";
			 }
			 if ($filters->nombre != '') {
				 $filtros = $filtros . " AND nombres LIKE '%" . $filters->nombre . "%'";
			 }
			 if ($filters->documento != '') {
				 $filtros = $filtros . " AND documento LIKE '%" . $filters->documento . "%'";
			 }
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
 
			 $parramsfilter['empresa'] =  $this->_getSanitizedParam("empresa");
			 $parramsfilter['nombre'] =  $this->_getSanitizedParam("nombre");
			 $parramsfilter['documento'] =  $this->_getSanitizedParam("documento");
 
			 Session::getInstance()->set($this->namefilter, $parramsfilter);
		 }
		 if ($this->_getSanitizedParam("cleanfilter") == 1) {
			 Session::getInstance()->set($this->namefilter, '');
			 Session::getInstance()->set($this->namepageactual, 1);
		 }
	 }
 }
 