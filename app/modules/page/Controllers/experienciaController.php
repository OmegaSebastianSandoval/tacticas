<?php
/**
* Controlador de Experiencia que permite la  creacion, edicion  y eliminacion de los experiencia laboral del Sistema
*/
class Page_experienciaController extends Page_mainController
{
	public $botonpanel = 3;

	/**
	 * $mainModel  instancia del modelo de  base de datos experiencia laboral
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
     * Inicializa las variables principales del controlador experiencia .
     *
     * @return void.
     */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Experiencia();
		$this->namefilter = "parametersfilterexperiencia";
		$this->route = "/page/experiencia";
		$this->namepages ="pages_experiencia";
		$this->namepageactual ="page_actual_experiencia";
		$this->_view->route = $this->route;
		if(Session::getInstance()->get($this->namepages)){
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
     * Recibe la informacion y  muestra un listado de  experiencia laboral con sus respectivos filtros.
     *
     * @return void.
     */
	public function indexAction()
	{
		$title = "Administración de experiencia laboral";
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
		$this->_view->cc = $this->_getSanitizedParam("cc");
	}

	/**
     * Genera la Informacion necesaria para editar o crear un  experiencia laboral  y muestra su formulario
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
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if($content->id){
				$this->_view->content = $content;
				$this->_view->routeform = $this->route."/update";
				$title = "Actualizar experiencia laboral";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}else{
				$this->_view->routeform = $this->route."/insert";
				$title = "Crear experiencia laboral";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route."/insert";
			$title = "Crear experiencia laboral";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
     * Inserta la informacion de un experiencia laboral  y redirecciona al listado de experiencia laboral.
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
			$data['log_tipo'] = 'CREAR EXPERIENCIA LABORAL';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		$cc = $this->_getSanitizedParam("cedula");
		header('Location:/page/hojadevida/manage?cc=' .$cc. '#pills-experiencia');

	}

	/**
     * Recibe un identificador  y Actualiza la informacion de un experiencia laboral  y redirecciona al listado de experiencia laboral.
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
			$data['log_tipo'] = 'EDITAR EXPERIENCIA LABORAL';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);}
		$cc = $this->_getSanitizedParam("cedula");
		header('Location:/page/hojadevida/manage?cc=' .$cc. '#pills-experiencia');

	}

	/**
     * Recibe un identificador  y elimina un experiencia laboral  y redirecciona al listado de experiencia laboral.
     *
     * @return void.
     */
	public function deleteAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")]  == $csrf ) {
			$id =  $this->_getSanitizedParam("id");
			if (isset($id) && $id > 0) {
				$content = $this->mainModel->getById($id);
				if (isset($content)) {
					$this->mainModel->deleteRegister($id);$data = (array)$content;
					$data['log_log'] = print_r($data,true);
					$data['log_tipo'] = 'BORRAR EXPERIENCIA LABORAL';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data); }
			}
		}
		$cc = $this->_getSanitizedParam("cc");
		header('Location:/page/hojadevida/manage?cc=' .$cc. '#pills-experiencia');

	}

	/**
     * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Experiencia.
     *
     * @return array con toda la informacion recibida del formulario.
     */
	private function getData()
	{
		$data = array();
		$data['empresa'] = $this->_getSanitizedParam("empresa");
		$data['cargo'] = $this->_getSanitizedParam("cargo");
		$data['fecha1'] = $this->_getSanitizedParam("fecha1");
		$data['fecha2'] = $this->_getSanitizedParam("fecha2");
		$data['detalles'] = $this->_getSanitizedParamHtml("detalles");
		$data['cedula'] = $this->_getSanitizedParamHtml("cedula");
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
		$cc= $this->_getSanitizedParam("cc");
		$filtros = $filtros." AND cedula = '$cc' ";
        if (Session::getInstance()->get($this->namefilter)!="") {
            $filters =(object)Session::getInstance()->get($this->namefilter);
            if ($filters->empresa != '') {
                $filtros = $filtros." AND empresa LIKE '%".$filters->empresa."%'";
            }
            if ($filters->cargo != '') {
                $filtros = $filtros." AND cargo LIKE '%".$filters->cargo."%'";
            }
            if ($filters->fecha1 != '') {
                $filtros = $filtros." AND fecha1 LIKE '%".$filters->fecha1."%'";
            }
            if ($filters->fecha2 != '') {
                $filtros = $filtros." AND fecha2 LIKE '%".$filters->fecha2."%'";
            }
            if ($filters->detalles != '') {
                $filtros = $filtros." AND detalles LIKE '%".$filters->detalles."%'";
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
					$parramsfilter['empresa'] =  $this->_getSanitizedParam("empresa");
					$parramsfilter['cargo'] =  $this->_getSanitizedParam("cargo");
					$parramsfilter['fecha1'] =  $this->_getSanitizedParam("fecha1");
					$parramsfilter['fecha2'] =  $this->_getSanitizedParam("fecha2");
					$parramsfilter['detalles'] =  $this->_getSanitizedParam("detalles");Session::getInstance()->set($this->namefilter, $parramsfilter);
        }
        if ($this->_getSanitizedParam("cleanfilter") == 1) {
            Session::getInstance()->set($this->namefilter, '');
            Session::getInstance()->set($this->namepageactual,1);
        }
    }
}