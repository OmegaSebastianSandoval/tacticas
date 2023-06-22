<?php

/**
 * Controlador de Documentoshojadevida que permite la  creacion, edicion  y eliminacion de los documento del Sistema
 */
class Page_documentoshojadevidaController extends Page_mainController
{
	public $botonpanel  = 3;

	/**
	 * $mainModel  instancia del modelo de  base de datos documento
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
	protected $_csrf_section = "papage_hojadevida";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador documentoshojadevida .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Documentoshojadevida();
		$this->namefilter = "parametersfilterdocumentoshojadevida";
		$this->route = "/page/documentoshojadevida";
		$this->namepages = "pages_documentoshojadevida";
		$this->namepageactual = "page_actual_documentoshojadevida";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  documento con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Administración de documento";
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
		$this->_view->cc = $this->_getSanitizedParam("cc");
		$this->_view->list_tipo = $this->getTipo();
	}

	/**
	 * Genera la Informacion necesaria para editar o crear un  documento  y muestra su formulario
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
		$this->_view->cc = $this->_getSanitizedParam("cc");
		$this->_view->list_tipo = $this->getTipo();
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if ($content->id) {
				$this->_view->content = $content;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar documento";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear documento";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear documento";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
	 * Inserta la informacion de un documento  y redirecciona al listado de documento.
	 *
	 * @return void.
	 */
	public function insertAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf) {
			$data = $this->getData();
			$uploadDocument =  new Core_Model_Upload_Document();
			if ($_FILES['archivo']['name'] != '') {
				$data['archivo'] = $uploadDocument->upload("archivo");
			}
			$id = $this->mainModel->insert($data);

			$data['id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'CREAR DOCUMENTO';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		$cc = $this->_getSanitizedParam("cedula");
		header('Location:/page/hojadevida/manage?cc=' . $cc . '#pills-documentos');
	}

	/**
	 * Recibe un identificador  y Actualiza la informacion de un documento  y redirecciona al listado de documento.
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
				$uploadDocument =  new Core_Model_Upload_Document();
				if ($_FILES['archivo']['name'] != '') {
					if ($content->archivo) {
						$uploadDocument->delete($content->archivo);
					}
					$data['archivo'] = $uploadDocument->upload("archivo");
				} else {
					$data['archivo'] = $content->archivo;
				}
				$this->mainModel->update($data, $id);
			}
			$data['id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'EDITAR DOCUMENTO';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		$cc = $this->_getSanitizedParam("cedula");
		header('Location:/page/hojadevida/manage?cc=' . $cc . '#pills-documentos');
	}

	/**
	 * Recibe un identificador  y elimina un documento  y redirecciona al listado de documento.
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
					$uploadDocument =  new Core_Model_Upload_Document();
					if (isset($content->archivo) && $content->archivo != '') {
						$uploadDocument->delete($content->archivo);
					}
					$this->mainModel->deleteRegister($id);
					$data = (array)$content;
					$data['log_log'] = print_r($data, true);
					$data['log_tipo'] = 'BORRAR DOCUMENTO';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}
		$cc = $this->_getSanitizedParam("cc");
		header('Location:/page/hojadevida/manage?cc=' . $cc . '#pills-documentos');
	}

	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Documentoshojadevida.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		$data['nombre'] = $this->_getSanitizedParam("nombre");
		$data['archivo'] = "";
		$data['fecha'] = $this->_getSanitizedParam("fecha");
		$data['cedula'] = $this->_getSanitizedParamHtml("cedula");
		if ($this->_getSanitizedParam("tipo") == '') {
			$data['tipo'] = '0';
		} else {
			$data['tipo'] = $this->_getSanitizedParam("tipo");
		}
		$data['fecha_vencimiento'] = $this->_getSanitizedParam("fecha_vencimiento");
		return $data;
	}

	/**
	 * Genera los valores del campo Tipo de documento.
	 *
	 * @return array cadena con los valores del campo Tipo de documento.
	 */
	private function getTipo()
	{
		$modelData = new Page_Model_DbTable_Dependdocumentostipo();
		$data = $modelData->getList();
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->id] = $value->nombre;
		}
		return $array;
	}

	/**
	 * Genera la consulta con los filtros de este controlador.
	 *
	 * @return array cadena con los filtros que se van a asignar a la base de datos
	 */
	protected function getFilter()
	{
		$filtros = " 1 = 1 ";
		$cc = $this->_getSanitizedParam("cc");
		$filtros = $filtros . " AND cedula = '$cc' ";
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object)Session::getInstance()->get($this->namefilter);
			if ($filters->nombre != '') {
				$filtros = $filtros . " AND nombre LIKE '%" . $filters->nombre . "%'";
			}
			if ($filters->fecha != '') {
				$filtros = $filtros . " AND fecha LIKE '%" . $filters->fecha . "%'";
			}
			if ($filters->tipo != '') {
				$filtros = $filtros . " AND tipo LIKE '%" . $filters->tipo . "%'";
			}
			if ($filters->fecha_vencimiento != '') {
				$filtros = $filtros . " AND fecha_vencimiento LIKE '%" . $filters->fecha_vencimiento . "%'";
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
			$parramsfilter['nombre'] =  $this->_getSanitizedParam("nombre");
			$parramsfilter['fecha'] =  $this->_getSanitizedParam("fecha");
			$parramsfilter['tipo'] =  $this->_getSanitizedParam("tipo");
			$parramsfilter['fecha_vencimiento'] =  $this->_getSanitizedParam("fecha_vencimiento");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}
