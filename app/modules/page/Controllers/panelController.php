<?php

/**
 *
 */

class Page_panelController extends Page_mainController
{
    public $botonpanel = 0;

		/**
	 * $_csrf_section  nombre de la variable general csrf  que se va a almacenar en la session
	 * @var string
	 */
	protected $_csrf_section = "panel_control";

    public function indexAction()
    {
        $title = "Panel Tácticas Panama";
        $this->getLayout()->setTitle($title);
        $this->_view->titlesection = $title;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];

        $empresasModel = new Page_Model_DbTable_Empresas();
        $this->_view->empresas = $empresasModel->getList("", "id DESC LIMIT 4");

        $hojasVida = new Page_Model_DbTable_Hojadevida();
        $this->_view->hojaVida = $hojasVida->getList("", "id DESC LIMIT 10");
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
}
