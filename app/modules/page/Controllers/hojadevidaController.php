<?php

/**
 * Controlador de Hojadevida que permite la  creacion, edicion  y eliminacion de los hoja de vida del Sistema
 */
class Page_hojadevidaController extends Page_mainController
{
	public $botonpanel = 3;

	/**
	 * $mainModel  instancia del modelo de  base de datos hoja de vida
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
	protected $_csrf_section = "page_hojadevida";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador hojadevida .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Hojadevida();
		$this->namefilter = "parametersfilterhojadevida";
		$this->route = "/page/hojadevida";
		$this->namepages = "pages_hojadevida";
		$this->namepageactual = "page_actual_hojadevida";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  hoja de vida con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{

		$title = "Administración de hoja de vida";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters = (object)Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$emp = $this->_getSanitizedParam("id");

		$filters = $this->getFilter($emp);
		// echo	$filters ;
		$order = "id DESC";
		$list = $this->mainModel->getList($filters, $order);
		/* 		echo '<pre>';
		print_r($list);
		echo '</pre>'; */
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
		$this->_view->list_tipo_documento = $this->getTipodocumento();
		$this->_view->list_empresa = $this->getEmpresa();
		$this->_view->list_tipo_contrato = $this->getTipocontrato();

		//CONSULTAR DATOS DEL INICIO DE HOJA DE VIDA
		$this->_view->activas = $this->mainModel->getListCount($filters . " AND retirado = '0' OR retirado IS NULL", $order)[0]->total;
		$this->_view->retiradas = $this->mainModel->getListCount($filters . " AND retirado = 1", $order)[0]->total;
		$this->_view->contratoDefinido = $this->mainModel->getListCount($filters . " AND tipo_contrato != 1", $order)[0]->total;
		$this->_view->contratoIndefinido = $this->mainModel->getListCount($filters . " AND tipo_contrato = 1", $order)[0]->total;
		$this->_view->contratoServicios = $this->mainModel->getListCount($filters . " AND tipo_contrato = 4", $order)[0]->total;
		$this->_view->totalPersonas = $this->mainModel->totalPersonas($filters)[0]->total;

		/* 	$array['1'] = 'Permanente';
		$array['4'] = 'Por servicios';
		$array['5'] = 'Definido'; */
	}

	/**
	 * Genera la Informacion necesaria para editar o crear un  hoja de vida  y muestra su formulario
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
		$this->_view->list_tipo_documento = $this->getTipodocumento();
		$this->_view->list_ciudad_nacimiento = $this->getCiudadnacimiento();
		$this->_view->list_ciudad = $this->getCiudad();
		$this->_view->list_estado_civil = $this->getEstadocivil();
		$this->_view->list_tipo_contrato = $this->getTipocontrato();
		$this->_view->list_empresa = $this->getEmpresa();
		$this->_view->list_cargo = $this->getCargo();
		$this->_view->list_metodo_pago = $this->getMetodoPago();
		$this->_view->list_tipo = $this->getTipo();
		$this->_view->list_tipoDotacion = $this->getTipoDotacion();
		$this->_view->list_tipodocumentohojadevida = $this->getTipoDocumentoHojadeVida();


		$id = $this->_getSanitizedParam("id");
		$cc = $this->_getSanitizedParam("cc");


		if ($id > 0) {
			$content = $this->mainModel->getById($id);

			//CONTACTOS DE EMERGENCIA
			$contactosEmergenciaModel = new Page_Model_DbTable_Contactosemergencia();
			$this->_view->listaContactos = $listaContactos = $contactosEmergenciaModel->getList("contacto_emergencia_empleado = '$id'", "");
			$this->_view->cantidadContactosEmergencia = $cantidadContactosEmergencia = count($listaContactos);

			//EDUCAION Y FORMACION
			$educacionModel = new Page_Model_DbTable_Estudios();
			$this->_view->listaEstudios = $listaEstudios = $educacionModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadEstudios = $cantidadEstudios = count($listaEstudios);

			//EXPERIENCIA LABORAL
			$experienciaModel = new Page_Model_DbTable_Experiencia();
			$this->_view->listaExperiencia = $listaExperiencia = $experienciaModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadExperiencia = $cantidadExperiencia = count($listaExperiencia);


			//REFERENCIAS PERSONALES
			$referenciaModel = new Page_Model_DbTable_Referencias();
			$this->_view->listaReferencia = $listaReferencia = $referenciaModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadReferencia = $cantidadReferencia = count($listaReferencia);

			//OTROS DATOS
			$otrosModel = new Page_Model_DbTable_Otros();
			$this->_view->listaOtros = $listaOtros = $otrosModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadOtros = $cantidadOtros = count($listaOtros);

			//VACACIONES 
			$vacacionModel = new Page_Model_DbTable_Vacacioneshojadevida();
			$this->_view->listaVacaciones = $listaVacaciones = $vacacionModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadVacaciones = $cantidadVacaciones = count($listaVacaciones);

			//DOTACION
			$dotacionModel = new Page_Model_DbTable_Dotacioneshojadevida();
			$this->_view->listaDotacion = $listaDotacion = $dotacionModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadDotacion = $cantidadDotacion = count($listaDotacion);

			//DOCUMENTOS
			$documentosModel = new Page_Model_DbTable_Documentoshojadevida();
			$this->_view->listaDocumentos = $listaDocumentos = $documentosModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadDocumentos = $cantidadDocumentos = count($listaDocumentos);

			if ($content->id) {
				$this->_view->content = $content;
				$fechaActual = date('Y-m-d');
				//calcular
				$this->_view->edad = $edad = date_diff(date_create($content->fecha_nacimiento), date_create($fechaActual))->y;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar hoja de vida";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear hoja de vida";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear hoja de vida";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
		if ($cc > 0) {
			$content = $this->mainModel->getByCedula($cc);

			//CONTACTOS DE EMERGENCIA
			$contactosEmergenciaModel = new Page_Model_DbTable_Contactosemergencia();
			$this->_view->listaContactos = $listaContactos = $contactosEmergenciaModel->getList("contacto_emergencia_empleado = '$id'", "");
			$this->_view->cantidadContactosEmergencia = $cantidadContactosEmergencia = count($listaContactos);

			//EDUCAION Y FORMACION
			$educacionModel = new Page_Model_DbTable_Estudios();
			$this->_view->listaEstudios = $listaEstudios = $educacionModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadEstudios = $cantidadEstudios = count($listaEstudios);

			//EXPERIENCIA LABORAL
			$experienciaModel = new Page_Model_DbTable_Experiencia();
			$this->_view->listaExperiencia = $listaExperiencia = $experienciaModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadExperiencia = $cantidadExperiencia = count($listaExperiencia);

			//REFERENCIAS PERSONALES
			$referenciaModel = new Page_Model_DbTable_Referencias();
			$this->_view->listaReferencia = $listaReferencia = $referenciaModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadReferencia = $cantidadReferencia = count($listaReferencia);

			//OTROS DATOS 
			$otrosModel = new Page_Model_DbTable_Otros();
			$this->_view->listaOtros = $listaOtros = $otrosModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadOtros = $cantidadOtros = count($listaOtros);


			//VACACIONES 
			$vacacionModel = new Page_Model_DbTable_Vacacioneshojadevida();
			$this->_view->listaVacaciones = $listaVacaciones = $vacacionModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadVacaciones = $cantidadVacaciones = count($listaVacaciones);

			//DOTACION
			$dotacionModel = new Page_Model_DbTable_Dotacioneshojadevida();
			$this->_view->listaDotacion = $listaDotacion = $dotacionModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadDotacion = $cantidadDotacion = count($listaDotacion);
			//DOCUMENTOS
			$documentosModel = new Page_Model_DbTable_Documentoshojadevida();
			$this->_view->listaDocumentos = $listaDocumentos = $documentosModel->getList("cedula = '$content->documento'", "");
			$this->_view->cantidadDocumentos = $cantidadDocumentos = count($listaDocumentos);
			if ($content->id) {
				$this->_view->content = $content;
				$fechaActual = date('Y-m-d');
				//calcular
				$this->_view->edad = $edad = date_diff(date_create($content->fecha_nacimiento), date_create($fechaActual))->y;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar hoja de vida";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear hoja de vida";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear hoja de vida";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
	 * Inserta la informacion de un hoja de vida  y redirecciona al listado de hoja de vida.
	 *
	 * @return void.
	 */
	public function insertAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf) {
			$data = $this->getData();
			$uploadImage =  new Core_Model_Upload_Image();
			if ($_FILES['foto']['name'] != '') {
				$data['foto'] = $uploadImage->upload("foto");
			}
			$id = $this->mainModel->insert($data);

			$data['id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'CREAR HOJA DE VIDA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '/manage?id=' . $id . '');
	}

	/**
	 * Recibe un identificador  y Actualiza la informacion de un hoja de vida  y redirecciona al listado de hoja de vida.
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
				$uploadImage =  new Core_Model_Upload_Image();
				if ($_FILES['foto']['name'] != '') {
					if ($content->foto) {
						$uploadImage->delete($content->foto);
					}
					$data['foto'] = $uploadImage->upload("foto");
				} else {
					$data['foto'] = $content->foto;
				}
				$this->mainModel->update($data, $id);
			}
			$data['id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'EDITAR HOJA DE VIDA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '/manage?id=' . $id . '');
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
				$content = $this->mainModel->getById($id);
				if (isset($content)) {
					$uploadImage =  new Core_Model_Upload_Image();
					if (isset($content->foto) && $content->foto != '') {
						$uploadImage->delete($content->foto);
					}
					$this->mainModel->deleteRegister($id);
					$data = (array)$content;
					$data['log_log'] = print_r($data, true);
					$data['log_tipo'] = 'BORRAR HOJA DE VIDA';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}
		header('Location: ' . $this->route . '' . '');
	}

	public function exportarAction()
	{
		$this->setLayout('blanco');
		$this->filters();
		$filters = (object)Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$emp = $this->_getSanitizedParam("id");
		$filters = $this->getFilter($emp);
		// echo	$filters ;
		$order = "id DESC";
		$list = $this->mainModel->getList($filters, $order);


		$output = '';

		$output .= '<table border="1" cellspacing="0" cellpadding="5">';
		$output .= '
  <tr>
	<th>nombres</th>
	<th>apellidos</th>
	<th>tipo de documento</th>
	<th>documento</th>
	<th>fecha de nacimiento</th>
	<th>ciudad de nacimiento</th>
	<th>email</th>
	<th>direccion</th>
	<th>telefono</th>
	<th>celular</th>
	<th>ciudad</th>
	<th>estado civil</th>
	<th>perfil profesional</th>
	<th>fecha ingreso</th>
	<th>numero de seguro</th>
	<th>retirado</th>
	<th>tipo de contrato</th>
	<th>fecha de salida</th>
	<th>inicio</th>
	<th>fin</th>
	<th>empresa</th>
	<th>fecha de creacion</th>
	<th>fecha de modificacion</th>

  </tr>';





		foreach ($list as $row) {
			$output .= '
	<tr>
	  <td>' . $row->nombres . '</td>
	  <td>' . $row->apellidos . '</td>
	  <td>' . $row->tipo_documento . '</td>
	  <td>' . $row->documento . '</td>	
	 <td>' . $row->fecha_nacimiento . '</td>
	  <td>' . $row->ciudad_nacimiento . '</td>
	  <td>' . $row->email . '</td>
	  <td>' . $row->direccion . '</td>
	  <td>' . $row->telefono . '</td>
	  <td>' . $row->celular . '</td>
	  <td>' . $row->ciudad . '</td>
	  <td>' . $row->estado_civil . '</td>
	  <td>' . $row->perfil_profesional . '</td>
	  <td>' . $row->fecha_ingreso . '</td>
	  <td>' . $row->numero_seguro . '</td>
	  <td>' . $row->retirado . '</td>
	  <td>' . $row->tipo_contrato . '</td>
	  <td>' . $row->fecha_salida . '</td>
	  <td>' . $row->inicio . '</td>
	  <td>' . $row->fin . '</td>
	  <td>' . $row->empresa . '</td>
	  <td>' . $row->fecha_c . '</td>
	  <td>' . $row->fecha_m . '</td>
	</tr>
	';
		}

		$output .= '</table>';
		$hoy = date('Ym-d h:m:s');
		header('Content-Type: application/xls');
		header('Content-Disposition: attachment; filename=hojasdevida' . $hoy . '.xls');
		echo $output;
	}
	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Hojadevida.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		$data['foto'] = "";
		$data['nombres'] = $this->_getSanitizedParam("nombres");
		$data['apellidos'] = $this->_getSanitizedParam("apellidos");
		$data['tipo_documento'] = $this->_getSanitizedParam("tipo_documento");
		$data['documento'] = $this->_getSanitizedParam("documento");
		$data['fecha_nacimiento'] = $this->_getSanitizedParam("fecha_nacimiento");
		if ($this->_getSanitizedParam("ciudad_nacimiento") == '') {
			$data['ciudad_nacimiento'] = '0';
		} else {
			$data['ciudad_nacimiento'] = $this->_getSanitizedParam("ciudad_nacimiento");
		}
		$data['email'] = $this->_getSanitizedParam("email");
		$data['direccion'] = $this->_getSanitizedParam("direccion");
		$data['telefono'] = $this->_getSanitizedParam("telefono");
		$data['celular'] = $this->_getSanitizedParam("celular");
		if ($this->_getSanitizedParam("ciudad") == '') {
			$data['ciudad'] = '0';
		} else {
			$data['ciudad'] = $this->_getSanitizedParam("ciudad");
		}
		$data['estado_civil'] = $this->_getSanitizedParam("estado_civil");
		$data['fecha_m'] = '';
		$data['fecha_ingreso'] = $this->_getSanitizedParam("fecha_ingreso");
		$data['numero_seguro'] = $this->_getSanitizedParam("numero_seguro");
		if ($this->_getSanitizedParam("retirado") == '') {
			$data['retirado'] = '0';
		} else {
			$data['retirado'] = $this->_getSanitizedParam("retirado");
		}
		if ($this->_getSanitizedParam("tipo_contrato") == '') {
			$data['tipo_contrato'] = '0';
		} else {
			$data['tipo_contrato'] = $this->_getSanitizedParam("tipo_contrato");
		}
		$data['fecha_salida'] = $this->_getSanitizedParam("fecha_salida");
		$data['inicio'] = $this->_getSanitizedParam("inicio");
		$data['fin'] = $this->_getSanitizedParam("fin");
		if ($this->_getSanitizedParam("empresa") == '') {
			$data['empresa'] = '0';
		} else {
			$data['empresa'] = $this->_getSanitizedParam("empresa");
		}
		if ($this->_getSanitizedParam("cargo") == '') {
			$data['cargo'] = '0';
		} else {
			$data['cargo'] = $this->_getSanitizedParam("cargo");
		}
		if ($this->_getSanitizedParam("metodo_pago") == '') {
			$data['metodo_pago'] = '0';
		} else {
			$data['metodo_pago'] = $this->_getSanitizedParam("metodo_pago");
		}
		$data['fecha_c'] = '';
		$data['perfil_profesional'] = $this->_getSanitizedParamHtml("perfil_profesional");
		$data['numero_cuenta'] = $this->_getSanitizedParamHtml("numero_cuenta");

		return $data;
	}

	/**
	 * Genera los valores del campo Tipo de documento.
	 *
	 * @return array cadena con los valores del campo Tipo de documento.
	 */
	private function getTipoDocumentoHojadeVida()
	{
		$modelData = new Page_Model_DbTable_Dependdocumentostipo();
		$data = $modelData->getList();
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->id] = $value->nombre;
		}
		return $array;
	}
	/**
	 * Genera los valores del campo Tipo.
	 *
	 * @return array cadena con los valores del campo Tipo.
	 */
	private function getTipoDotacion()
	{
		$modelData = new Page_Model_DbTable_Dependdotacionestipo();
		$data = $modelData->getList();
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->nombre] = $value->nombre;
		}
		return $array;
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
		$data = $modelData->getList("", "codigo DESC");
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
		$data = $modelData->getList("", "codigo DESC");
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
	 * Genera los valores del campo Tipo.
	 *
	 * @return array cadena con los valores del campo Tipo.
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
	 * Genera los valores del campo MetodoPago.
	 *
	 * @return array cadena con los valores del campo MetodoPago.
	 */
	private function getMetodoPago()
	{
		$array = array();
		$array['1'] = 'Cheque';
		$array['2'] = 'Transferencia';

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
	protected function getFilter($emp)
	{
		$filtros = " 1 = 1 ";
		if ($emp  != '') {
			Session::getInstance()->set($this->namefilter, '');

			$filtros = $filtros . " AND empresa ='" . $emp . "'";
		}
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object)Session::getInstance()->get($this->namefilter);
			if ($filters->foto != '') {
				$filtros = $filtros . " AND foto LIKE '%" . $filters->foto . "%'";
			}
			if ($filters->nombres != '') {
				$filtros = $filtros . " AND nombres LIKE '%" . $filters->nombres . "%'";
			}
			if ($filters->apellidos != '') {
				$filtros = $filtros . " AND apellidos LIKE '%" . $filters->apellidos . "%'";
			}
			if ($filters->tipo_documento != '') {
				$filtros = $filtros . " AND tipo_documento ='" . $filters->tipo_documento . "'";
			}
			if ($filters->documento != '') {
				$filtros = $filtros . " AND documento LIKE '%" . $filters->documento . "%'";
			}
			if ($filters->empresa != '') {
				$filtros = $filtros . " AND empresa ='" . $filters->empresa . "'";
			}
			if ($filters->tipo_contrato != '') {
				$filtros = $filtros . " AND tipo_contrato ='" . $filters->tipo_contrato . "'";
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
			$parramsfilter['foto'] =  $this->_getSanitizedParam("foto");
			$parramsfilter['nombres'] =  $this->_getSanitizedParam("nombres");
			$parramsfilter['apellidos'] =  $this->_getSanitizedParam("apellidos");
			$parramsfilter['tipo_documento'] =  $this->_getSanitizedParam("tipo_documento");
			$parramsfilter['documento'] =  $this->_getSanitizedParam("documento");
			$parramsfilter['empresa'] =  $this->_getSanitizedParam("empresa");
			$parramsfilter['tipo_contrato'] =  $this->_getSanitizedParam("tipo_contrato");

			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}
