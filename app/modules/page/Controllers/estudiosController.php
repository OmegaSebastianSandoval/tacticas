<?php

/**
 * Controlador de Estudios que permite la  creacion, edicion  y eliminacion de los estudios y formaci&oacute;n del Sistema
 */
class Page_estudiosController extends Page_mainController
{
	public $botonpanel = 3;

	/**
	 * $mainModel  instancia del modelo de  base de datos estudios y formaci&oacute;n
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

	 protected $_csrf_section = "page_hojadevida";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador estudios .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Estudios();
		$this->namefilter = "parametersfilterestudios";
		$this->route = "/page/estudios";
		$this->namepages = "pages_estudios";
		$this->namepageactual = "page_actual_estudios";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  estudios y formaci&oacute;n con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Administración de estudios y formaci&oacute;n";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters = (object)Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$filters = $this->getFilter();
		$order = "";
		$list = $this->mainModel->getList($filters, $order);
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
		$this->_view->lists = $this->mainModel->getListPages($filters, $order, $start, $amount);
		$this->_view->csrf_section = $this->_csrf_section;
	}

	/**
	 * Genera la Informacion necesaria para editar o crear un  estudios y formaci&oacute;n  y muestra su formulario
	 *
	 * @return void.
	 */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_hojadevida_" . date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$id = $this->_getSanitizedParam("id");
		$this->_view->cedula = $cedula = $this->_getSanitizedParam("cc");

		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if ($content->id) {
				$this->_view->content = $content;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar estudios y formaci&oacute;n";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear estudios y formaci&oacute;n";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear estudios y formaci&oacute;n";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
	 * Inserta la informacion de un estudios y formaci&oacute;n  y redirecciona al listado de estudios y formaci&oacute;n.
	 *
	 * @return void.
	 */
	public function insertAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf) {
			$data = $this->getData();
			$id = $this->mainModel->insert($data);

			$data['id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'CREAR ESTUDIOS Y FORMACI&OACUTE;N';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location:/page/hojadevida/manage?cc=' .$data['cedula']. '#pills-contact');

	}

	/**
	 * Recibe un identificador  y Actualiza la informacion de un estudios y formaci&oacute;n  y redirecciona al listado de estudios y formaci&oacute;n.
	 *
	 * @return void.
	 */
	public function updateAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf) {
			$id = $this->_getSanitizedParam("id");
			$content = $this->mainModel->getById($id);
			if ($content->id) {
				$data = $this->getData();
				$this->mainModel->update($data, $id);
			}
			$data['id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'EDITAR ESTUDIOS Y FORMACI&OACUTE;N';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}

		header('Location:/page/hojadevida/manage?cc=' .$data['cedula']. '#pills-contact');
	}

	/**
	 * Recibe un identificador  y elimina un estudios y formaci&oacute;n  y redirecciona al listado de estudios y formaci&oacute;n.
	 *
	 * @return void.
	 */
	public function deleteAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf) {
			$id =  $this->_getSanitizedParam("id");
			if (isset($id) && $id > 0) {
				$content = $this->mainModel->getById($id);
				if (isset($content)) {
					$this->mainModel->deleteRegister($id);
					$data = (array)$content;
					$data['log_log'] = print_r($data, true);
					$data['log_tipo'] = 'BORRAR ESTUDIOS Y FORMACI&OACUTE;N';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}
	
		$empleado =  $this->_getSanitizedParam("empleado");

		header('Location:/page/hojadevida/manage?id=' . $empleado . ' #pills-contact');
	}

	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Estudios.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		$data['institucion'] = $this->_getSanitizedParam("institucion");
		$data['titulo'] = $this->_getSanitizedParam("titulo");
		$data['fecha1'] = $this->_getSanitizedParam("fecha1");
		$data['fecha2'] = $this->_getSanitizedParam("fecha2");
		$data['cedula'] = $this->_getSanitizedParam("cedula");
		$data['descripcion'] = $this->_getSanitizedParamHtml("descripcion");
		return $data;
	}
	/**
	 * Genera la consulta con los filtros de este controlador.
	 *
	 * @return array cadena con los filtros que se van a asignar a la base de datos
	 */
	protected function getFilter()
	{
		$filtros = " 1 = 1 ";
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object)Session::getInstance()->get($this->namefilter);
			if ($filters->institucion != '') {
				$filtros = $filtros . " AND institucion LIKE '%" . $filters->institucion . "%'";
			}
			if ($filters->titulo != '') {
				$filtros = $filtros . " AND titulo LIKE '%" . $filters->titulo . "%'";
			}
			if ($filters->fecha1 != '') {
				$filtros = $filtros . " AND fecha1 LIKE '%" . $filters->fecha1 . "%'";
			}
			if ($filters->fecha2 != '') {
				$filtros = $filtros . " AND fecha2 LIKE '%" . $filters->fecha2 . "%'";
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
			$parramsfilter['institucion'] =  $this->_getSanitizedParam("institucion");
			$parramsfilter['titulo'] =  $this->_getSanitizedParam("titulo");
			$parramsfilter['fecha1'] =  $this->_getSanitizedParam("fecha1");
			$parramsfilter['fecha2'] =  $this->_getSanitizedParam("fecha2");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}
