<?php

/**
 * Controlador de Planillaasignacion que permite la  creacion, edicion  y eliminacion de los planilla asignaci&oacute;n del Sistema
 */
class Page_planillaasignacionController extends Page_mainController
{
	/**
	 * $mainModel  instancia del modelo de  base de datos planilla asignaci&oacute;n
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
	protected $_csrf_section = "page_planillaasignacion";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador planillaasignacion .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Planillaasignacion();
		$this->namefilter = "parametersfilterplanillaasignacion";
		$this->route = "/page/planillaasignacion";
		$this->namepages = "pages_planillaasignacion";
		$this->namepageactual = "page_actual_planillaasignacion";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 50;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  planilla asignaci&oacute;n con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Asignaci&oacute;n de colaboradores";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters = (object)Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$filters = $this->getFilter();
		$order = "nombre1 ASC";
		$list = $this->mainModel->getListWithNames($filters, $order);
		// print_r($list);
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
		$this->_view->lists = $this->mainModel->getListWithNamesPages($filters, $order, $start, $amount);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->planilla = $this->_getSanitizedParam("planilla");
	}

	/**
	 * Genera la Informacion necesaria para editar o crear un  planilla asignaci&oacute;n  y muestra su formulario
	 *
	 * @return void.
	 */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_planillaasignacion_" . date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$this->_view->planilla = $this->_getSanitizedParam("planilla");
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if ($content->id) {
				$this->_view->content = $content;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar planilla asignaci&oacute;n";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear planilla asignaci&oacute;n";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear planilla asignaci&oacute;n";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
	 * Inserta la informacion de un planilla asignaci&oacute;n  y redirecciona al listado de planilla asignaci&oacute;n.
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
			$data['log_tipo'] = 'CREAR PLANILLA ASIGNACI&OACUTE;N';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		$planilla = $this->_getSanitizedParam("planilla");
		header('Location: ' . $this->route . '?planilla=' . $planilla . '');
	}


	public function buscarcedulasAction()
	{

		$filtros = " 1 ";
		if (Session::getInstance()->get("kt_login_level") == 2) {
			$empresa = Session::getInstance()->get("kt_login_empresa");
			$filtros = 	$filtros . " AND empresa = '$empresa' ";
		}
		if (Session::getInstance()->get("kt_login_level") == 3) {
			$asignacion = Session::getInstance()->get("kt_login_asignacion");
			$filtros = 	$filtros . " AND FIND_IN_SET(empresa, '$asignacion') ";
		}
		$f2 = "";
		if ($this->_getSanitizedParam("buscar") != "") {
			$this->_view->buscar = $buscar = $this->_getSanitizedParam("buscar");
			$f2 = $f2 . " AND (hoja_vida.documento LIKE '%$buscar%' OR hoja_vida.nombres LIKE '%$buscar%' OR hoja_vida.apellidos LIKE '%$buscar%' ) ";
		}
		if ($this->_getSanitizedParam("cedula") != "") {
			$this->_view->cedula = $cedula = $this->_getSanitizedParam("cedula");
			$f2 = $f2 . " AND (hoja_vida.documento LIKE '%$cedula%'   ) ";
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			$filtros = " 1 ";
			$f2 = "";
		}
		$this->_view->cedulas = $this->mainModel->getListHojaVida(" $filtros $f2", " fecha_c DESC ");
	}

	public function cargarAction()
	{
		$title = "Importar colaboradores";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->_view->planilla = $planilla = $this->_getSanitizedParam('planilla');
		
	}

	public function importarAction()
	{
				$this->setLayout('blanco');

		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 600000000);
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters = (object)Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$filters = $this->getFilter();
		$order = "nombre1 ASC";
		$planilla = $this->_getSanitizedParam('planilla');
		$uploadDocument =  new Core_Model_Upload_Document();
		// $this->_view->planilla = $planilla = $this->_getSanitizedParam('planilla');
		if ($_FILES['colaboradores']['name'] != '') {
			$archivo = $uploadDocument->upload("colaboradores");
		}
		$inputFileName = FILE_PATH . $archivo;
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
		$infoexel = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
		$list = $this->mainModel->getListWithNames($filters, $order);

		$i = 1;
		foreach ($infoexel as $fila) {
			$i++;
			if ($i > 2) {
				$planilla = $data['planilla'] = $planilla;
				$cedula = $data['cedula'] = $fila['A'];
				$valor_hora = $data['valor_hora'] = $fila['B'];
				$sin_seguridad = $data['sin_seguridad'] = $fila['C'];
				
				if ($data['cedula'] != "") {
					$existe = $this->mainModel->getListWithNames("$filters AND cedula = '$cedula'", $order);
					if (count($existe) == 0) {
						$this->mainModel->insert($data);
					} else {
						$id = $existe[0]->id;





						if ($cedula != "" && $cedula != $existe[0]->cedula) {
							($this->mainModel->editField($id, "cedula", $cedula));
						}

						if ($valor_hora != "" && $valor_hora != $existe[0]->valor_hora) {
							$this->mainModel->editField($id, "valor_hora", $valor_hora);
						}

						if ($sin_seguridad != "" && $sin_seguridad != $existe[0]->sin_seguridad) {
							$this->mainModel->editField($id, "sin_seguridad", $sin_seguridad);
						}
						
					
					}
				}
			}

		}
		header("Location:/page/planillaasignacion?planilla=".$planilla);

	
	}



	/**
	 * Recibe un identificador  y Actualiza la informacion de un planilla asignaci&oacute;n  y redirecciona al listado de planilla asignaci&oacute;n.
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
			$data['log_tipo'] = 'EDITAR PLANILLA ASIGNACI&OACUTE;N';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		$planilla = $this->_getSanitizedParam("planilla");
		header('Location: ' . $this->route . '?planilla=' . $planilla . '');
	}

	/**
	 * Recibe un identificador  y elimina un planilla asignaci&oacute;n  y redirecciona al listado de planilla asignaci&oacute;n.
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
					$data['log_tipo'] = 'BORRAR PLANILLA ASIGNACI&OACUTE;N';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}
		$planilla = $this->_getSanitizedParam("planilla");
		header('Location: ' . $this->route . '?planilla=' . $planilla . '');
	}






















	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Planillaasignacion.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		$data['planilla'] = $this->_getSanitizedParamHtml("planilla");
		$data['cedula'] = $this->_getSanitizedParam("cedula");
		if ($this->_getSanitizedParam("valor_hora") == '') {
			$data['valor_hora'] = '0';
		} else {
			$data['valor_hora'] = $this->_getSanitizedParam("valor_hora");
		}
		if ($this->_getSanitizedParam("sin_seguridad") == '') {
			$data['sin_seguridad'] = '0';
		} else {
			$data['sin_seguridad'] = $this->_getSanitizedParam("sin_seguridad");
		}
		return $data;
	}
	/**
	 * Genera los valores del campo Empresa.
	 *
	 * @return array cadena con los valores del campo Empresa.
	 */
	private function getEmpresa()
	{
		$modelData = new Page_Model_DbTable_Dependempresa();
		if (Session::getInstance()->get("kt_login_level") == 3) {
			$asignacion = Session::getInstance()->get("kt_login_asignacion");
			// echo $asignacion;
			$data = $modelData->getListAsignacion(" FIND_IN_SET(id, '$asignacion') ");
		} else if (Session::getInstance()->get("kt_login_level") == 2) {
			$empresa = Session::getInstance()->get("kt_login_empresa");
			$data = $modelData->getList("id = '$empresa'", "");
		} else {
			$data = $modelData->getList();
		}

		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->id] = $value->nombre;
		}
		return $array;
	}
	/**
	 * Genera los valores del campo Cargo.
	 *
	 * @return array cadena con los valores del campo Cargo.
	 */
	private function getCargo()
	{
		$modelData = new Page_Model_DbTable_Cargos();
		$data = $modelData->getList("cargo_estado = 1", "");
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->cargo_id] = $value->cargo_nombre;
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
		// $filtros = " 1 = 1 ";
		$filtros = " 1 ";

		if (Session::getInstance()->get("kt_login_level") == 2) {
			$empresa = Session::getInstance()->get("kt_login_empresa");
			$filtros = 	$filtros . " AND empresa = '$empresa' ";
		}
		if (Session::getInstance()->get("kt_login_level") == 3) {
			$asignacion = Session::getInstance()->get("kt_login_asignacion");
			$filtros = 	$filtros . " AND FIND_IN_SET(empresa, '$asignacion') ";
		}


		$planilla = $this->_getSanitizedParam("planilla");
		$filtros = $filtros . " AND planilla = '$planilla' ";
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object)Session::getInstance()->get($this->namefilter);
			if ($filters->cedula != '') {
				$filtros = $filtros . " AND cedula LIKE '%" . $filters->cedula . "%'";
			}
			if ($filters->valor_hora != '') {
				$filtros = $filtros . " AND valor_hora LIKE '%" . $filters->valor_hora . "%'";
			}
			if ($filters->sin_seguridad != '') {
				$filtros = $filtros . " AND sin_seguridad LIKE '%" . $filters->sin_seguridad . "%'";
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
			$parramsfilter['cedula'] =  $this->_getSanitizedParam("cedula");
			$parramsfilter['valor_hora'] =  $this->_getSanitizedParam("valor_hora");
			$parramsfilter['sin_seguridad'] =  $this->_getSanitizedParam("sin_seguridad");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}
