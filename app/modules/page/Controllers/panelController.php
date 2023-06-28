<?php

/**
 *
 */

class Page_panelController extends Page_mainController
{
    public $botonpanel = 0;

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
	protected $_csrf_section = "panel_control";
		/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Hojadevida();
		$this->namefilter = "parametersfilterpanel";
		$this->route = "/page/panel";
		$this->namepages = "pages_panel";
		$this->namepageactual = "page_actual_panel";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 50;
		}
		parent::init();
	}
    public function indexAction()
    {
        $title = "Panel Tácticas Panama";
        $this->getLayout()->setTitle($title);
        $this->_view->titlesection = $title;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];

        $empresasModel = new Page_Model_DbTable_Empresas();
		
		

		$filters = (object)Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$filters = $this->getFilter();
		// echo $filters;

		
		
        $this->_view->empresas = $empresasModel->getList("", "id DESC LIMIT 4");

        $hojasVida = new Page_Model_DbTable_Hojadevida();
        $this->_view->hojaVida = $hojasVida->getList($filters, "id DESC LIMIT 10");
        $this->_view->list_tipo_documento = $this->getTipodocumento();
		$this->_view->list_empresa = $this->getEmpresa();
		$this->_view->list_tipo_contrato = $this->getTipocontrato();
    }
	/**
	 * Recibe un identificador  y elimina un hoja de vida  y redirecciona al listado de hoja de vida.
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
				$hojadevidaModel = new Page_Model_DbTable_Hojadevida();
				$content = $hojadevidaModel->getById($id);
				if (isset($content)) {
					$uploadImage =  new Core_Model_Upload_Image();
					if (isset($content->foto) && $content->foto != '') {
						$uploadImage->delete($content->foto);
					}
					$hojadevidaModel->deleteRegister($id);
					$data = (array)$content;
					$data['log_log'] = print_r($data, true);
					$data['log_tipo'] = 'BORRAR HOJA DE VIDA';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}
		header('Location: /page/panel');
	}

    	/**
	 * Genera los valores del campo Tipo documento.
	 *
	 * @return array cadena con los valores del campo Tipo documento.
	 */
	private function getTipodocumento()
	{
		$array = array();
		$array['CC'] = 'CC';
		$array['CE'] = 'CE';
		$array['NIT'] = 'NIT';
		$array['NUIP'] = 'NUIP';
		$array['PASAPORTE'] = 'PASAPORTE';
		return $array;
	}


	/**
	 * Genera los valores del campo Ciudad de nacimiento.
	 *
	 * @return array cadena con los valores del campo Ciudad de nacimiento.
	 */
	private function getCiudadnacimiento()
	{
		$modelData = new Page_Model_DbTable_Dependciudad();
		$data = $modelData->getList("","codigo DESC");
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->codigo] = $value->nombre;
		}
		return $array;
	}


	/**
	 * Genera los valores del campo Ciudad.
	 *
	 * @return array cadena con los valores del campo Ciudad.
	 */
	private function getCiudad()
	{
		$modelData = new Page_Model_DbTable_Dependciudad();
		$data = $modelData->getList("","codigo DESC");
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->codigo] = $value->nombre;
		}
		return $array;
	}


	/**
	 * Genera los valores del campo Estado civil.
	 *
	 * @return array cadena con los valores del campo Estado civil.
	 */
	private function getEstadocivil()
	{
		$array = array();
		$array['Soltero(a)'] = 'Soltero(a)';
		$array['Casado(a)'] = 'Casado(a)';
		$array['Viudo(a)'] = 'Viudo(a)';

		return $array;
	}


	/**
	 * Genera los valores del campo Tipo de contrato.
	 *
	 * @return array cadena con los valores del campo Tipo de contrato.
	 */
	private function getTipocontrato()
	{
		$array = array();
		$array['1'] = 'Permanente';
		$array['4'] = 'Por servicios';
		$array['5'] = 'Definido';
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
	protected function getFilter()
	{
		$filtros = " 1 ";
	
		if(Session::getInstance()->get("kt_login_level") == 2){
			$empresa = Session::getInstance()->get("kt_login_empresa");
			$filtros = 	$filtros . " AND empresa = '$empresa' ";
		}
		if(Session::getInstance()->get("kt_login_level") == 3){
			$asignacion = Session::getInstance()->get("kt_login_asignacion");
			$filtros = 	$filtros . " AND FIND_IN_SET(empresa, '$asignacion') ";
		}
	
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object)Session::getInstance()->get($this->namefilter);

			if ($filters->empresa != '') {
				$filtros = $filtros . " AND empresa ='" . $filters->empresa . "'";
			}
			if ($filters->nombre != '') {
				$filtros = $filtros . " AND nombres LIKE '%" . $filters->nombre . "%'";
			}
			if ($filters->documento != '') {
				$filtros = $filtros . " AND documento LIKE '%" . $filters->documento . "%'";
			}
		}


		return $filtros;
	}
}
