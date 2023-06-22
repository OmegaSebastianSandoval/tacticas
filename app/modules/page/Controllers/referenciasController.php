<?php
/**
* Controlador de Referencias que permite la  creacion, edicion  y eliminacion de los referencias del Sistema
*/
class Page_referenciasController extends Page_mainController
{
	public $botonpanel = 3;

	/**
	 * $mainModel  instancia del modelo de  base de datos referencias
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
	protected $pages ;

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
     * Inicializa las variables principales del controlador referencias .
     *
     * @return void.
     */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Referencias();
		$this->namefilter = "parametersfilterreferencias";
		$this->route = "/page/referencias";
		$this->namepages ="pages_referencias";
		$this->namepageactual ="page_actual_referencias";
		$this->_view->route = $this->route;
		if(Session::getInstance()->get($this->namepages)){
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
     * Recibe la informacion y  muestra un listado de  referencias con sus respectivos filtros.
     *
     * @return void.
     */
	public function indexAction()
	{
		$title = "Administración de referencias";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters =(object)Session::getInstance()->get($this->namefilter);
        $this->_view->filters = $filters;
		$filters = $this->getFilter();
		$order = "";
		$list = $this->mainModel->getList($filters,$order);
		$amount = $this->pages;
		$page = $this->_getSanitizedParam("page");
		if (!$page && Session::getInstance()->get($this->namepageactual)) {
		   	$page = Session::getInstance()->get($this->namepageactual);
		   	$start = ($page - 1) * $amount;
		} else if(!$page){
			$start = 0;
		   	$page=1;
			Session::getInstance()->set($this->namepageactual,$page);
		} else {
			Session::getInstance()->set($this->namepageactual,$page);
		   	$start = ($page - 1) * $amount;
		}
		$this->_view->register_number = count($list);
		$this->_view->pages = $this->pages;
		$this->_view->totalpages = ceil(count($list)/$amount);
		$this->_view->page = $page;
		$this->_view->lists = $this->mainModel->getListPages($filters,$order,$start,$amount);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->list_tipo = $this->getTipo();
		$this->_view->cc = $this->_getSanitizedParam("cc");
	}

	/**
     * Genera la Informacion necesaria para editar o crear un  referencias  y muestra su formulario
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
		$this->_view->list_tipo = $this->getTipo();
		$this->_view->cc = $this->_getSanitizedParam("cc");
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if($content->id){
				$this->_view->content = $content;
				$this->_view->routeform = $this->route."/update";
				$title = "Actualizar referencias";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}else{
				$this->_view->routeform = $this->route."/insert";
				$title = "Crear referencias";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route."/insert";
			$title = "Crear referencias";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
     * Inserta la informacion de un referencias  y redirecciona al listado de referencias.
     *
     * @return void.
     */
	public function insertAction(){
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf ) {	
			$data = $this->getData();
			$id = $this->mainModel->insert($data);
			
			$data['id']= $id;
			$data['log_log'] = print_r($data,true);
			$data['log_tipo'] = 'CREAR REFERENCIAS';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		$cc = $this->_getSanitizedParam("cedula");
		header('Location:/page/hojadevida/manage?cc=' .$cc. '#pills-referencias');
	}

	/**
     * Recibe un identificador  y Actualiza la informacion de un referencias  y redirecciona al listado de referencias.
     *
     * @return void.
     */
	public function updateAction(){
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf ) {
			$id = $this->_getSanitizedParam("id");
			$content = $this->mainModel->getById($id);
			if ($content->id) {
				$data = $this->getData();
					$this->mainModel->update($data,$id);
			}
			$data['id']=$id;
			$data['log_log'] = print_r($data,true);
			$data['log_tipo'] = 'EDITAR REFERENCIAS';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);}
		$cc = $this->_getSanitizedParam("cedula");
		header('Location:/page/hojadevida/manage?cc=' .$cc. '#pills-referencias');
	}

	/**
     * Recibe un identificador  y elimina un referencias  y redirecciona al listado de referencias.
     *
     * @return void.
     */
	public function deleteAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf ) {
			$id =  $this->_getSanitizedParam("id");
			if (isset($id) && $id > 0) {
				$content = $this->mainModel->getById($id);
				if (isset($content)) {
					$this->mainModel->deleteRegister($id);$data = (array)$content;
					$data['log_log'] = print_r($data,true);
					$data['log_tipo'] = 'BORRAR REFERENCIAS';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data); }
			}
		}
		$cc = $this->_getSanitizedParam("cc");
		header('Location:/page/hojadevida/manage?cc=' .$cc. '#pills-referencias');
	}

	/**
     * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Referencias.
     *
     * @return array con toda la informacion recibida del formulario.
     */
	private function getData()
	{
		$data = array();
		if($this->_getSanitizedParam("tipo") == '' ) {
			$data['tipo'] = '0';
		} else {
			$data['tipo'] = $this->_getSanitizedParam("tipo");
		}
		$data['nombre'] = $this->_getSanitizedParam("nombre");
		$data['cargo'] = $this->_getSanitizedParam("cargo");
		$data['empresa'] = $this->_getSanitizedParam("empresa");
		$data['telefono'] = $this->_getSanitizedParam("telefono");
		$data['cedula'] = $this->_getSanitizedParamHtml("cedula");
		if($this->_getSanitizedParam("se_llamo") == '' ) {
			$data['se_llamo'] = '0';
		} else {
			$data['se_llamo'] = $this->_getSanitizedParam("se_llamo");
		}
		if($this->_getSanitizedParam("se_confirmo") == '' ) {
			$data['se_confirmo'] = '0';
		} else {
			$data['se_confirmo'] = $this->_getSanitizedParam("se_confirmo");
		}
		$data['descripcion'] = $this->_getSanitizedParamHtml("descripcion");
		return $data;
	}

	/**
     * Genera los valores del campo Tipo.
     *
     * @return array cadena con los valores del campo Tipo.
     */
	private function getTipo()
	{
		$array = array();
		$array['1'] = 'Laboral';
		$array['2'] = 'Personal';
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
		$cc= $this->_getSanitizedParam("cc");
		$filtros = $filtros." AND cedula = '$cc' ";
        if (Session::getInstance()->get($this->namefilter)!="") {
            $filters =(object)Session::getInstance()->get($this->namefilter);
            if ($filters->tipo != '') {
                $filtros = $filtros." AND tipo ='".$filters->tipo."'";
            }
            if ($filters->nombre != '') {
                $filtros = $filtros." AND nombre LIKE '%".$filters->nombre."%'";
            }
            if ($filters->cargo != '') {
                $filtros = $filtros." AND cargo LIKE '%".$filters->cargo."%'";
            }
            if ($filters->empresa != '') {
                $filtros = $filtros." AND empresa LIKE '%".$filters->empresa."%'";
            }
            if ($filters->telefono != '') {
                $filtros = $filtros." AND telefono LIKE '%".$filters->telefono."%'";
            }
            if ($filters->se_llamo != '') {
                $filtros = $filtros." AND se_llamo LIKE '%".$filters->se_llamo."%'";
            }
            if ($filters->se_confirmo != '') {
                $filtros = $filtros." AND se_confirmo LIKE '%".$filters->se_confirmo."%'";
            }
            if ($filters->descripcion != '') {
                $filtros = $filtros." AND descripcion LIKE '%".$filters->descripcion."%'";
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
        if ($this->getRequest()->isPost()== true) {
        	Session::getInstance()->set($this->namepageactual,1);
            $parramsfilter = array();
					$parramsfilter['tipo'] =  $this->_getSanitizedParam("tipo");
					$parramsfilter['nombre'] =  $this->_getSanitizedParam("nombre");
					$parramsfilter['cargo'] =  $this->_getSanitizedParam("cargo");
					$parramsfilter['empresa'] =  $this->_getSanitizedParam("empresa");
					$parramsfilter['telefono'] =  $this->_getSanitizedParam("telefono");
					$parramsfilter['se_llamo'] =  $this->_getSanitizedParam("se_llamo");
					$parramsfilter['se_confirmo'] =  $this->_getSanitizedParam("se_confirmo");
					$parramsfilter['descripcion'] =  $this->_getSanitizedParam("descripcion");Session::getInstance()->set($this->namefilter, $parramsfilter);
        }
        if ($this->_getSanitizedParam("cleanfilter") == 1) {
            Session::getInstance()->set($this->namefilter, '');
            Session::getInstance()->set($this->namepageactual,1);
        }
    }
}