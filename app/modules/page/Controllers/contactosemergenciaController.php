<?php

/**
 * Controlador de Contactosemergencia que permite la  creacion, edicion  y eliminacion de los contactos de emergencia del Sistema
 */
class Page_contactosemergenciaController extends Page_mainController
{
	public $botonpanel = 3;

	/**
	 * $mainModel  instancia del modelo de  base de datos contactos de emergencia
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
	 * Inicializa las variables principales del controlador contactosemergencia .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Contactosemergencia();
		$this->namefilter = "parametersfiltercontactosemergencia";
		$this->route = "/page/contactosemergencia";
		$this->namepages = "pages_contactosemergencia";
		$this->namepageactual = "page_actual_contactosemergencia";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  contactos de emergencia con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Administración de contactos de emergencia";
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
	 * Genera la Informacion necesaria para editar o crear un  contactos de emergencia  y muestra su formulario
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
		$empleado = $this->_getSanitizedParam("empleado");
		$this->_view->empleado = $empleado;



		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if ($content->contacto_emergencia_id) {
				$this->_view->content = $content;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar contactos de emergencia";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear contactos de emergencia";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear contactos de emergencia";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
	 * Inserta la informacion de un contactos de emergencia  y redirecciona al listado de contactos de emergencia.
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

			$data['contacto_emergencia_id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'CREAR CONTACTOS DE EMERGENCIA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location:/page/hojadevida/manage?id=' . $data['contacto_emergencia_empleado'] . ' #pills-profile');
	}

	/**
	 * Recibe un identificador  y Actualiza la informacion de un contactos de emergencia  y redirecciona al listado de contactos de emergencia.
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
			if ($content->contacto_emergencia_id) {
				$data = $this->getData();
				$this->mainModel->update($data, $id);
			}
			$data['contacto_emergencia_id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'EDITAR CONTACTOS DE EMERGENCIA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location:/page/hojadevida/manage?id=' . $data['contacto_emergencia_empleado'] . ' #pills-profile');
	}

	/**
	 * Recibe un identificador  y elimina un contactos de emergencia  y redirecciona al listado de contactos de emergencia.
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
					$data['log_tipo'] = 'BORRAR CONTACTOS DE EMERGENCIA';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}


		$empleado =  $this->_getSanitizedParam("empleado");

		header('Location:/page/hojadevida/manage?id=' . $empleado . ' #pills-profile');
	}

	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Contactosemergencia.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		$data['contacto_emergencia_nombre'] = $this->_getSanitizedParam("contacto_emergencia_nombre");
		$data['contacto_emergencia_telefono'] = $this->_getSanitizedParam("contacto_emergencia_telefono");
		$data['contacto_emergencia_parentesco'] = $this->_getSanitizedParam("contacto_emergencia_parentesco");
		$data['contacto_emergencia_empleado'] = $this->_getSanitizedParam("contacto_emergencia_empleado");;
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
			if ($filters->contacto_emergencia_nombre != '') {
				$filtros = $filtros . " AND contacto_emergencia_nombre LIKE '%" . $filters->contacto_emergencia_nombre . "%'";
			}
			if ($filters->contacto_emergencia_telefono != '') {
				$filtros = $filtros . " AND contacto_emergencia_telefono LIKE '%" . $filters->contacto_emergencia_telefono . "%'";
			}
			if ($filters->contacto_emergencia_parentesco != '') {
				$filtros = $filtros . " AND contacto_emergencia_parentesco LIKE '%" . $filters->contacto_emergencia_parentesco . "%'";
			}
			if ($filters->contacto_emergencia_empleado != '') {
				$filtros = $filtros . " AND contacto_emergencia_empleado LIKE '%" . $filters->contacto_emergencia_empleado . "%'";
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
			$parramsfilter['contacto_emergencia_nombre'] =  $this->_getSanitizedParam("contacto_emergencia_nombre");
			$parramsfilter['contacto_emergencia_telefono'] =  $this->_getSanitizedParam("contacto_emergencia_telefono");
			$parramsfilter['contacto_emergencia_parentesco'] =  $this->_getSanitizedParam("contacto_emergencia_parentesco");
			$parramsfilter['contacto_emergencia_empleado'] =  $this->_getSanitizedParam("contacto_emergencia_empleado");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}
