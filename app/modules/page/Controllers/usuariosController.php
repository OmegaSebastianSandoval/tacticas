<?php

/**
 * Controlador de Usuarios que permite la  creacion, edicion  y eliminacion de los usuarios del Sistema
 */
class Page_usuariosController extends Page_mainController
{
	public $botonpanel = 2;
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
	protected $_csrf_section = "page_usuarios";

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
		if ((Session::getInstance()->get("kt_login_level") != '1' )) {
			header('Location: /page/panel');
		}
		$this->mainModel = new Page_Model_DbTable_Usuarios();
		$this->namefilter = "parametersfilterusuarios";
		$this->route = "/page/usuarios";
		$this->namepages = "pages_usuarios";
		$this->namepageactual = "page_actual_usuarios";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  usuarios con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		
		$title = "Administración de usuarios";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters = (object)Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$emp = $this->_getSanitizedParam("emp");

		$filters = $this->getFilter($emp);
		// echo $filters;
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
		$this->_view->list_nivel = $this->getNivel();
		$this->_view->list_empresa = $this->getEmpresa();
		$this->_view->list_estado = $this->getEstado();
	}

	/**
	 * Genera la Informacion necesaria para editar o crear un  usuarios  y muestra su formulario
	 *
	 * @return void.
	 */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_usuarios_" . date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$this->_view->list_nivel = $this->getNivel();
		$this->_view->list_empresa = $this->getEmpresa();
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if ($content->id) {
				$this->_view->content = $content;
				$asignacionString = $content->asignacion;
				$asignacionString = rtrim($asignacionString, ','); 
				$asignacionArray = explode(",", $asignacionString);
				$numerosArray = array_map("intval", $asignacionArray);
				$this->_view->asignacionArray = $numerosArray;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar usuarios";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear usuarios";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear usuarios";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
	 * Inserta la informacion de un usuarios  y redirecciona al listado de usuarios.
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
			$data['log_tipo'] = 'CREAR USUARIOS';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe un identificador  y Actualiza la informacion de un usuarios  y redirecciona al listado de usuarios.
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
			$data['log_tipo'] = 'EDITAR USUARIOS';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe un identificador  y elimina un usuarios  y redirecciona al listado de usuarios.
	 *
	 * @return void.
	 */
	public function deleteAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_csrf_section] == $csrf) {
			$id =  $this->_getSanitizedParam("id");
			if (isset($id) && $id > 0) {
				$content = $this->mainModel->getById($id);
				if (isset($content)) {
					$this->mainModel->deleteRegister($id);
					$data = (array)$content;
					$data['log_log'] = print_r($data, true);
					$data['log_tipo'] = 'BORRAR USUARIOS';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Usuarios.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		$data['nombre'] = $this->_getSanitizedParam("nombre");
		$data['usuario'] = $this->_getSanitizedParam("usuario");
		// $data['clave_principal'] = $this->_getSanitizedParam("clave_principal");
		$data['email'] = $this->_getSanitizedParam("email");
		if ($this->_getSanitizedParam("nivel") == '') {
			$data['nivel'] = '0';
		} else {
			$data['nivel'] = $this->_getSanitizedParam("nivel");
		}
		if ($this->_getSanitizedParam("activo") == '') {
			$data['activo'] = '0';
		} else {
			$data['activo'] = $this->_getSanitizedParam("activo");
		}
		if ($this->_getSanitizedParam("empresa") == '') {
			$data['empresa'] = '0';
		} else {
			$data['empresa'] = $this->_getSanitizedParam("empresa");
		}
		$data['asignacion'] = $this->_getSanitizedParam("asignacion");
		return $data;
	}

	/**
	 * Genera los valores del campo Nivel.
	 *
	 * @return array cadena con los valores del campo Nivel.
	 */
	private function getNivel()
	{
		$array = array();
		$array['1'] = 'Administrador';
		$array['2'] = 'Empresa';
		$array['3'] = 'Coordinador';

		return $array;
	}

	/**
	 * Genera los valores del campo Nivel.
	 *
	 * @return array cadena con los valores del campo Nivel.
	 */
	private function getEstado()
	{
		$array = array();
		$array['1'] = 'Activo';
		$array['0'] = 'Inactivo';


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
	protected function getFilter($emp = "")
	{
		$filtros = " 1 = 1 ";
		if ($emp  != ''  ) {
			Session::getInstance()->set($this->namefilter, '');

			$filtros = $filtros . " AND empresa ='" . $emp . "'";
		}
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object)Session::getInstance()->get($this->namefilter);
			if ($filters->nombre != '') {
				$filtros = $filtros . " AND nombre LIKE '%" . $filters->nombre . "%'";
			}
			if ($filters->usuario != '') {
				$filtros = $filtros . " AND usuario LIKE '%" . $filters->usuario . "%'";
			}
			if ($filters->clave != '') {
				$filtros = $filtros . " AND clave LIKE '%" . $filters->clave . "%'";
			}
			if ($filters->email != '') {
				$filtros = $filtros . " AND email LIKE '%" . $filters->email . "%'";
			}
			if ($filters->nivel != '') {
				$filtros = $filtros . " AND nivel ='" . $filters->nivel . "'";
			}
			if ($filters->activo != '') {
				$filtros = $filtros . " AND activo LIKE '%" . $filters->activo . "%'";
			}
			if ($filters->empresa != ''  ) {
				$filtros = $filtros . " AND empresa ='" . $filters->empresa . "'";
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
			$parramsfilter['usuario'] =  $this->_getSanitizedParam("usuario");
			$parramsfilter['clave'] =  $this->_getSanitizedParam("clave");
			$parramsfilter['email'] =  $this->_getSanitizedParam("email");
			$parramsfilter['nivel'] =  $this->_getSanitizedParam("nivel");
			$parramsfilter['activo'] =  $this->_getSanitizedParam("activo");
			$parramsfilter['empresa'] =  $this->_getSanitizedParam("empresa");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
		
	}
}
