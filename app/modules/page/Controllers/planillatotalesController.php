<?php
/**
* Controlador de Planillatotales que permite la  creacion, edicion  y eliminacion de los planilla totales del Sistema
*/
class Page_planillatotalesController extends Page_mainController
{
	/**
	 * $mainModel  instancia del modelo de  base de datos planilla totales
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
	protected $_csrf_section = "page_planillatotales";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
     * Inicializa las variables principales del controlador planillatotales .
     *
     * @return void.
     */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Planillatotales();
		$this->namefilter = "parametersfilterplanillatotales";
		$this->route = "/page/planillatotales";
		$this->namepages ="pages_planillatotales";
		$this->namepageactual ="page_actual_planillatotales";
		$this->_view->route = $this->route;
		if(Session::getInstance()->get($this->namepages)){
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
     * Recibe la informacion y  muestra un listado de  planilla totales con sus respectivos filtros.
     *
     * @return void.
     */
	public function indexAction()
	{
		$title = "Administración de planilla totales";
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
	}

	/**
     * Genera la Informacion necesaria para editar o crear un  planilla totales  y muestra su formulario
     *
     * @return void.
     */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_planillatotales_".date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if($content->id){
				$this->_view->content = $content;
				$this->_view->routeform = $this->route."/update";
				$title = "Actualizar planilla totales";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}else{
				$this->_view->routeform = $this->route."/insert";
				$title = "Crear planilla totales";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route."/insert";
			$title = "Crear planilla totales";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
     * Inserta la informacion de un planilla totales  y redirecciona al listado de planilla totales.
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
			$data['log_tipo'] = 'CREAR PLANILLA TOTALES';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe un identificador  y Actualiza la informacion de un planilla totales  y redirecciona al listado de planilla totales.
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
			$data['log_tipo'] = 'EDITAR PLANILLA TOTALES';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe un identificador  y elimina un planilla totales  y redirecciona al listado de planilla totales.
     *
     * @return void.
     */
	public function deleteAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_csrf_section] == $csrf ) {
			$id =  $this->_getSanitizedParam("id");
			if (isset($id) && $id > 0) {
				$content = $this->mainModel->getById($id);
				if (isset($content)) {
					$this->mainModel->deleteRegister($id);$data = (array)$content;
					$data['log_log'] = print_r($data,true);
					$data['log_tipo'] = 'BORRAR PLANILLA TOTALES';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data); }
			}
		}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Planillatotales.
     *
     * @return array con toda la informacion recibida del formulario.
     */
	private function getData()
	{
		$data = array();
		if($this->_getSanitizedParam("planilla") == '' ) {
			$data['planilla'] = '0';
		} else {
			$data['planilla'] = $this->_getSanitizedParam("planilla");
		}
		$data['cedula'] = $this->_getSanitizedParam("cedula");
		if($this->_getSanitizedParam("viaticos") == '' ) {
			$data['viaticos'] = '0';
		} else {
			$data['viaticos'] = $this->_getSanitizedParam("viaticos");
		}
		if($this->_getSanitizedParam("prestamos") == '' ) {
			$data['prestamos'] = '0';
		} else {
			$data['prestamos'] = $this->_getSanitizedParam("prestamos");
		}
		if($this->_getSanitizedParam("prestamos_financiera") == '' ) {
			$data['prestamos_financiera'] = '0';
		} else {
			$data['prestamos_financiera'] = $this->_getSanitizedParam("prestamos_financiera");
		}
		if($this->_getSanitizedParam("decimo") == '' ) {
			$data['decimo'] = '0';
		} else {
			$data['decimo'] = $this->_getSanitizedParam("decimo");
		}
		if($this->_getSanitizedParam("neta") == '' ) {
			$data['neta'] = '0';
		} else {
			$data['neta'] = $this->_getSanitizedParam("neta");
		}
		if($this->_getSanitizedParam("normal1") == '' ) {
			$data['normal1'] = '0';
		} else {
			$data['normal1'] = $this->_getSanitizedParam("normal1");
		}
		if($this->_getSanitizedParam("normal2") == '' ) {
			$data['normal2'] = '0';
		} else {
			$data['normal2'] = $this->_getSanitizedParam("normal2");
		}
		if($this->_getSanitizedParam("normal3") == '' ) {
			$data['normal3'] = '0';
		} else {
			$data['normal3'] = $this->_getSanitizedParam("normal3");
		}
		if($this->_getSanitizedParam("extra1") == '' ) {
			$data['extra1'] = '0';
		} else {
			$data['extra1'] = $this->_getSanitizedParam("extra1");
		}
		if($this->_getSanitizedParam("extra2") == '' ) {
			$data['extra2'] = '0';
		} else {
			$data['extra2'] = $this->_getSanitizedParam("extra2");
		}
		if($this->_getSanitizedParam("extra3") == '' ) {
			$data['extra3'] = '0';
		} else {
			$data['extra3'] = $this->_getSanitizedParam("extra3");
		}
		if($this->_getSanitizedParam("nocturna1") == '' ) {
			$data['nocturna1'] = '0';
		} else {
			$data['nocturna1'] = $this->_getSanitizedParam("nocturna1");
		}
		if($this->_getSanitizedParam("nocturna2") == '' ) {
			$data['nocturna2'] = '0';
		} else {
			$data['nocturna2'] = $this->_getSanitizedParam("nocturna2");
		}
		if($this->_getSanitizedParam("nocturna3") == '' ) {
			$data['nocturna3'] = '0';
		} else {
			$data['nocturna3'] = $this->_getSanitizedParam("nocturna3");
		}
		if($this->_getSanitizedParam("festivo1") == '' ) {
			$data['festivo1'] = '0';
		} else {
			$data['festivo1'] = $this->_getSanitizedParam("festivo1");
		}
		if($this->_getSanitizedParam("festivo2") == '' ) {
			$data['festivo2'] = '0';
		} else {
			$data['festivo2'] = $this->_getSanitizedParam("festivo2");
		}
		if($this->_getSanitizedParam("festivo3") == '' ) {
			$data['festivo3'] = '0';
		} else {
			$data['festivo3'] = $this->_getSanitizedParam("festivo3");
		}
		if($this->_getSanitizedParam("dominical1") == '' ) {
			$data['dominical1'] = '0';
		} else {
			$data['dominical1'] = $this->_getSanitizedParam("dominical1");
		}
		if($this->_getSanitizedParam("dominical2") == '' ) {
			$data['dominical2'] = '0';
		} else {
			$data['dominical2'] = $this->_getSanitizedParam("dominical2");
		}
		if($this->_getSanitizedParam("dominical3") == '' ) {
			$data['dominical3'] = '0';
		} else {
			$data['dominical3'] = $this->_getSanitizedParam("dominical3");
		}
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
        if (Session::getInstance()->get($this->namefilter)!="") {
            $filters =(object)Session::getInstance()->get($this->namefilter);
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
            $parramsfilter = array();Session::getInstance()->set($this->namefilter, $parramsfilter);
        }
        if ($this->_getSanitizedParam("cleanfilter") == 1) {
            Session::getInstance()->set($this->namefilter, '');
            Session::getInstance()->set($this->namepageactual,1);
        }
    }
}