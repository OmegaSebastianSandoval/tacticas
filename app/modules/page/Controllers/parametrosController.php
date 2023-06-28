<?php
/**
* Controlador de Parametros que permite la  creacion, edicion  y eliminacion de los par&aacute;metros del Sistema
*/
class Page_parametrosController extends Page_mainController
{
	/**
	 * $mainModel  instancia del modelo de  base de datos par&aacute;metros
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
	protected $_csrf_section = "page_parametros";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
     * Inicializa las variables principales del controlador parametros .
     *
     * @return void.
     */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Parametros();
		$this->namefilter = "parametersfilterparametros";
		$this->route = "/page/parametros";
		$this->namepages ="pages_parametros";
		$this->namepageactual ="page_actual_parametros";
		$this->_view->route = $this->route;
		if(Session::getInstance()->get($this->namepages)){
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
     * Recibe la informacion y  muestra un listado de  par&aacute;metros con sus respectivos filtros.
     *
     * @return void.
     */
	public function indexAction()
	{
		$title = "Administración de par&aacute;metros";
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
     * Genera la Informacion necesaria para editar o crear un  par&aacute;metros  y muestra su formulario
     *
     * @return void.
     */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_parametros_".date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if($content->id){
				$this->_view->content = $content;
				$this->_view->routeform = $this->route."/update";
				$title = "Actualizar par&aacute;metros";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}else{
				$this->_view->routeform = $this->route."/insert";
				$title = "Crear par&aacute;metros";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route."/insert";
			$title = "Crear par&aacute;metros";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
     * Inserta la informacion de un par&aacute;metros  y redirecciona al listado de par&aacute;metros.
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
			$data['log_tipo'] = 'CREAR PAR&AACUTE;METROS';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe un identificador  y Actualiza la informacion de un par&aacute;metros  y redirecciona al listado de par&aacute;metros.
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
			$data['log_tipo'] = 'EDITAR PAR&AACUTE;METROS';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe un identificador  y elimina un par&aacute;metros  y redirecciona al listado de par&aacute;metros.
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
					$data['log_tipo'] = 'BORRAR PAR&AACUTE;METROS';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data); }
			}
		}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Parametros.
     *
     * @return array con toda la informacion recibida del formulario.
     */
	private function getData()
	{
		$data = array();
		if($this->_getSanitizedParam("horas_extra") == '' ) {
			$data['horas_extra'] = '0';
		} else {
			$data['horas_extra'] = $this->_getSanitizedParam("horas_extra");
		}
		if($this->_getSanitizedParam("horas_dominicales") == '' ) {
			$data['horas_dominicales'] = '0';
		} else {
			$data['horas_dominicales'] = $this->_getSanitizedParam("horas_dominicales");
		}
		if($this->_getSanitizedParam("horas_nocturnas") == '' ) {
			$data['horas_nocturnas'] = '0';
		} else {
			$data['horas_nocturnas'] = $this->_getSanitizedParam("horas_nocturnas");
		}
		if($this->_getSanitizedParam("festivos") == '' ) {
			$data['festivos'] = '0';
		} else {
			$data['festivos'] = $this->_getSanitizedParam("festivos");
		}
		if($this->_getSanitizedParam("decimo") == '' ) {
			$data['decimo'] = '0';
		} else {
			$data['decimo'] = $this->_getSanitizedParam("decimo");
		}
		if($this->_getSanitizedParam("vacaciones") == '' ) {
			$data['vacaciones'] = '0';
		} else {
			$data['vacaciones'] = $this->_getSanitizedParam("vacaciones");
		}
		if($this->_getSanitizedParam("antiguedad") == '' ) {
			$data['antiguedad'] = '0';
		} else {
			$data['antiguedad'] = $this->_getSanitizedParam("antiguedad");
		}
		if($this->_getSanitizedParam("seguridad_social") == '' ) {
			$data['seguridad_social'] = '0';
		} else {
			$data['seguridad_social'] = $this->_getSanitizedParam("seguridad_social");
		}
		if($this->_getSanitizedParam("seguro_educativo") == '' ) {
			$data['seguro_educativo'] = '0';
		} else {
			$data['seguro_educativo'] = $this->_getSanitizedParam("seguro_educativo");
		}
		if($this->_getSanitizedParam("seguridad_social2") == '' ) {
			$data['seguridad_social2'] = '0';
		} else {
			$data['seguridad_social2'] = $this->_getSanitizedParam("seguridad_social2");
		}
		if($this->_getSanitizedParam("seguro_educativo2") == '' ) {
			$data['seguro_educativo2'] = '0';
		} else {
			$data['seguro_educativo2'] = $this->_getSanitizedParam("seguro_educativo2");
		}
		if($this->_getSanitizedParam("riesgos_profesionales") == '' ) {
			$data['riesgos_profesionales'] = '0';
		} else {
			$data['riesgos_profesionales'] = $this->_getSanitizedParam("riesgos_profesionales");
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