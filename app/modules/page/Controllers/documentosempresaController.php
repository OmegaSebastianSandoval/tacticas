<?php
/**
* Controlador de Documentosempresa que permite la  creacion, edicion  y eliminacion de los documento del Sistema
*/
class Page_documentosempresaController extends Page_mainController
{
	public $botonpanel = 1;
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
	protected $_csrf_section = "page_empresas";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
     * Inicializa las variables principales del controlador documentosempresa .
     *
     * @return void.
     */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Documentosempresa();
		$this->namefilter = "parametersfilterdocumentosempresa";
		$this->route = "/page/documentosempresa";
		$this->namepages ="pages_documentosempresa";
		$this->namepageactual ="page_actual_documentosempresa";
		$this->_view->route = $this->route;
		if(Session::getInstance()->get($this->namepages)){
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
		$this->_view->id = $this->_getSanitizedParam("id");
	}

	/**
     * Genera la Informacion necesaria para editar o crear un  documento  y muestra su formulario
     *
     * @return void.
     */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_empresas_".date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$this->_view->id = $this->_getSanitizedParam("id");
		$this->_view->emp = $this->_getSanitizedParam("emp");


		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if($content->documento_empresa_id){
				$this->_view->content = $content;
				$this->_view->routeform = $this->route."/update";
				$title = "Actualizar documento";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}else{
				$this->_view->routeform = $this->route."/insert";
				$title = "Crear documento";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route."/insert";
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
	public function insertAction(){
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf ) {	
			$data = $this->getData();
			$uploadDocument =  new Core_Model_Upload_Document();
			if($_FILES['documento_empresa_archivo']['name'] != ''){
				$data['documento_empresa_archivo'] = $uploadDocument->upload("documento_empresa_archivo");
			}
			$id = $this->mainModel->insert($data);
			
			$data['documento_empresa_id']= $id;
			$data['log_log'] = print_r($data,true);
			$data['log_tipo'] = 'CREAR DOCUMENTO';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		$id = $this->_getSanitizedParam("documento_empresa_empresa_id");
		header('Location:/page/empresas/manage?id=' . $id . '#pills-documentosEmpresa');

	}

	/**
     * Recibe un identificador  y Actualiza la informacion de un documento  y redirecciona al listado de documento.
     *
     * @return void.
     */
	public function updateAction(){
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf ) {
			$id = $this->_getSanitizedParam("id");
			$content = $this->mainModel->getById($id);
			if ($content->documento_empresa_id) {
				$data = $this->getData();
					$uploadDocument =  new Core_Model_Upload_Document();
				if($_FILES['documento_empresa_archivo']['name'] != ''){
					if($content->documento_empresa_archivo){
						$uploadDocument->delete($content->documento_empresa_archivo);
					}
					$data['documento_empresa_archivo'] = $uploadDocument->upload("documento_empresa_archivo");
				} else {
					$data['documento_empresa_archivo'] = $content->documento_empresa_archivo;
				}
				$this->mainModel->update($data,$id);
			}
			$data['documento_empresa_id']=$id;
			$data['log_log'] = print_r($data,true);
			$data['log_tipo'] = 'EDITAR DOCUMENTO';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);}
		$id = $this->_getSanitizedParam("documento_empresa_empresa_id");
		header('Location:/page/empresas/manage?id=' . $id . '#pills-documentosEmpresa');

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
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf ) {
			$id =  $this->_getSanitizedParam("id");
			if (isset($id) && $id > 0) {
				$content = $this->mainModel->getById($id);
				if (isset($content)) {
					$uploadDocument =  new Core_Model_Upload_Document();
					if (isset($content->documento_empresa_archivo) && $content->documento_empresa_archivo != '') {
						$uploadDocument->delete($content->documento_empresa_archivo);
					}
					$this->mainModel->deleteRegister($id);$data = (array)$content;
					$data['log_log'] = print_r($data,true);
					$data['log_tipo'] = 'BORRAR DOCUMENTO';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data); }
			}
		}
		$emp = $this->_getSanitizedParam("emp");
		header('Location:/page/empresas/manage?id=' . $emp . '#pills-documentosEmpresa');

	}

	/**
     * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Documentosempresa.
     *
     * @return array con toda la informacion recibida del formulario.
     */
	private function getData()
	{
		$data = array();
		$data['documento_empresa_nombre'] = $this->_getSanitizedParam("documento_empresa_nombre");
		$data['documento_empresa_archivo'] = "";
		$data['documento_empresa_fecha_creacion'] =  $this->_getSanitizedParam("documento_empresa_fecha_creacion");
		$data['documento_empresa_empresa_id'] = $this->_getSanitizedParamHtml("documento_empresa_empresa_id");
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
            if ($filters->documento_empresa_nombre != '') {
                $filtros = $filtros." AND documento_empresa_nombre LIKE '%".$filters->documento_empresa_nombre."%'";
            }
            if ($filters->documento_empresa_archivo != '') {
                $filtros = $filtros." AND documento_empresa_archivo LIKE '%".$filters->documento_empresa_archivo."%'";
            }
            if ($filters->documento_empresa_fecha_creacion != '') {
                $filtros = $filtros." AND documento_empresa_fecha_creacion LIKE '%".$filters->documento_empresa_fecha_creacion."%'";
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
					$parramsfilter['documento_empresa_nombre'] =  $this->_getSanitizedParam("documento_empresa_nombre");
					$parramsfilter['documento_empresa_archivo'] =  $this->_getSanitizedParam("documento_empresa_archivo");
					$parramsfilter['documento_empresa_fecha_creacion'] =  $this->_getSanitizedParam("documento_empresa_fecha_creacion");Session::getInstance()->set($this->namefilter, $parramsfilter);
        }
        if ($this->_getSanitizedParam("cleanfilter") == 1) {
            Session::getInstance()->set($this->namefilter, '');
            Session::getInstance()->set($this->namepageactual,1);
        }
    }
}