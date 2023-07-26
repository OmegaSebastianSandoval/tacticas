<?php
/**
* Controlador de Planilla que permite la  creacion, edicion  y eliminacion de los planilla del Sistema
*/
class Page_planillaController extends Page_mainController
{
	/**
	 * $mainModel  instancia del modelo de  base de datos planilla
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
	protected $_csrf_section = "page_planilla";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
     * Inicializa las variables principales del controlador planilla .
     *
     * @return void.
     */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Planilla();
		$this->namefilter = "parametersfilterplanilla";
		$this->route = "/page/planilla";
		$this->namepages ="pages_planilla";
		$this->namepageactual ="page_actual_planilla";
		$this->_view->route = $this->route;
		if(Session::getInstance()->get($this->namepages)){
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
     * Recibe la informacion y  muestra un listado de  planilla con sus respectivos filtros.
     *
     * @return void.
     */
	public function indexAction()
	{
		$title = "Administración de planilla";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters =(object)Session::getInstance()->get($this->namefilter);
        $this->_view->filters = $filters;
		$filters = $this->getFilter();
		$order = "fecha1 DESC, empresa.nombre ASC";
		$list = $this->mainModel->getListPlanillas($filters,$order);
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
		$this->_view->lists = $this->mainModel->getListPagesPlanillas($filters,$order,$start,$amount);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->list_empresa = $this->getEmpresa();
		$this->_view->list_meses = $this->getMeses();
		$this->_view->list_quincena = $this->getQuincenas();


	}

	/**
     * Genera la Informacion necesaria para editar o crear un  planilla  y muestra su formulario
     *
     * @return void.
     */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_planilla_".date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$this->_view->list_empresa = $this->getEmpresa();
		$this->_view->list_meses = $this->getMeses();
		$this->_view->list_quincena = $this->getQuincenas();


		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if($content->id){
				$this->_view->content = $content;
				$this->_view->routeform = $this->route."/update";
				$title = "Actualizar planilla";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}else{
				$this->_view->routeform = $this->route."/insert";
				$title = "Crear planilla";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route."/insert";
			$title = "Crear planilla";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
     * Inserta la informacion de un planilla  y redirecciona al listado de planilla.
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
			$data['log_tipo'] = 'CREAR PLANILLA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe un identificador  y Actualiza la informacion de un planilla  y redirecciona al listado de planilla.
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
			$data['log_tipo'] = 'EDITAR PLANILLA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe un identificador  y elimina un planilla  y redirecciona al listado de planilla.
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
					$data['log_tipo'] = 'BORRAR PLANILLA';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data); }
			}
		}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Planilla.
     *
     * @return array con toda la informacion recibida del formulario.
     */
	private function getData()
	{
		$data = array();
		$data['fecha1'] = $this->_getSanitizedParam("fecha1");
		$data['fecha2'] = $this->_getSanitizedParam("fecha2");
		if($this->_getSanitizedParam("empresa") == '' ) {
			$data['empresa'] = '0';
		} else {
			$data['empresa'] = $this->_getSanitizedParam("empresa");
		}
		if($this->_getSanitizedParam("cerrada") == '' ) {
			$data['cerrada'] = '0';
		} else {
			$data['cerrada'] = $this->_getSanitizedParam("cerrada");
		}
		$data['fecha_cerrada'] = $this->_getSanitizedParam("fecha_cerrada");
		if($this->_getSanitizedParam("limite_horas") == '' ) {
			$data['limite_horas'] = '0';
		} else {
			$data['limite_horas'] = $this->_getSanitizedParam("limite_horas");
		}
		if($this->_getSanitizedParam("limite_dominicales") == '' ) {
			$data['limite_dominicales'] = '0';
		} else {
			$data['limite_dominicales'] = $this->_getSanitizedParam("limite_dominicales");
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
		$data = $modelData->getList();
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->id] = $value->nombre;
		}
		return $array;
	}
	private function getMeses()
	{
		$array = array(
			1 => 'Enero',
			2 => 'Febrero',
			3 => 'Marzo',
			4 => 'Abril',
			5 => 'Mayo',
			6 => 'Junio',
			7 => 'Julio',
			8 => 'Agosto',
			9 => 'Septiembre',
			10 => 'Octubre',
			11 => 'Noviembre',
			12 => 'Diciembre'
		);
	
		return $array;
	}
	private function getQuincenas()
	{
		$array = array(
			1 => '1',
			2 => '2',

		);
	
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
        if (Session::getInstance()->get($this->namefilter)!="") {
            $filters =(object)Session::getInstance()->get($this->namefilter);
            if ($filters->fecha1 != '') {
                $filtros = $filtros." AND fecha1 LIKE '%".$filters->fecha1."%'";
            }
            if ($filters->fecha2 != '') {
                $filtros = $filtros." AND fecha2 LIKE '%".$filters->fecha2."%'";
            }
            if ($filters->empresa != '') {
                $filtros = $filtros." AND empresa LIKE '%".$filters->empresa."%'";
            }
            if ($filters->cerrada != '') {
                $filtros = $filtros." AND cerrada LIKE '%".$filters->cerrada."%'";
            }
            if ($filters->fecha_cerrada != '') {
                $filtros = $filtros." AND fecha_cerrada LIKE '%".$filters->fecha_cerrada."%'";
            }
            if ($filters->limite_horas != '') {
                $filtros = $filtros." AND limite_horas LIKE '%".$filters->limite_horas."%'";
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
					$parramsfilter['fecha1'] =  $this->_getSanitizedParam("fecha1");
					$parramsfilter['fecha2'] =  $this->_getSanitizedParam("fecha2");
					$parramsfilter['empresa'] =  $this->_getSanitizedParam("empresa");
					$parramsfilter['cerrada'] =  $this->_getSanitizedParam("cerrada");
					$parramsfilter['fecha_cerrada'] =  $this->_getSanitizedParam("fecha_cerrada");
					$parramsfilter['limite_horas'] =  $this->_getSanitizedParam("limite_horas");Session::getInstance()->set($this->namefilter, $parramsfilter);
        }
        if ($this->_getSanitizedParam("cleanfilter") == 1) {
            Session::getInstance()->set($this->namefilter, '');
            Session::getInstance()->set($this->namepageactual,1);
        }
    }
}