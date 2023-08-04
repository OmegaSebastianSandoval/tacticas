<?php

/**
 * Controlador de Facturadas que permite la  creacion, edicion  y eliminacion de los facturadas del Sistema
 */
class Page_facturadasController extends Page_mainController
{
	/**
	 * $mainModel  instancia del modelo de  base de datos facturadas
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
	protected $_csrf_section = "page_facturadas";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador facturadas .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Facturadas();
		$this->namefilter = "parametersfilterfacturadas";
		$this->route = "/page/facturadas";
		$this->namepages = "pages_facturadas";
		$this->namepageactual = "page_actual_facturadas";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  facturadas con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Informe reportads vs facturadas";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		/* $filters = (object)Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$filters = $this->getFilter();
		echo $filters; */

		$this->_view->fecha1 = $fecha1 = $this->_getSanitizedParam("fecha_inicio");
		$this->_view->fecha2 = $fecha2 = $this->_getSanitizedParam("fecha_fin");
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			$fecha1 == '';
			$fecha2 == '';
		}
		if ($fecha1 == '' && $fecha2 == '') {
			$this->_view->noContent = 1;
			return;
		}

		$filtro = "";
		if ($fecha1 != '' && $fecha2 != '') {
			$filtro .= " AND ((planilla_horas.fecha >= '$fecha1' AND planilla_horas.fecha<='$fecha2') OR planilla_horas.fecha='0000-00-00') ";
			$filtro .= " AND ((planilla.fecha1 >= '$fecha1' AND planilla.fecha2<='$fecha2' AND planilla_horas.fecha='0000-00-00') OR planilla_horas.fecha!='0000-00-00') ";
		}


		$localizacionesModel = new Page_Model_DbTable_Localizaciones();
		$planillaHorasModel = new Page_Model_DbTable_PlanillaHoras();



		$localicaciones = $planillaHorasModel->getPlanillaHorasFacturadasNewNew($fecha1, $fecha2);
		/* echo '<pre>';
		print_r($localicaciones);
		echo '</pre>'; */

		$TOTALES = [];
		$facturadas = [];


		$tabla='';
		foreach ($localicaciones as $localizacion) {

			
			$nombre = $localizacion->localizacion;
			$TOTALES["normal"] += $localizacion->tipo1;
			$TOTALES["extra"] += $localizacion->tipo2;
			$TOTALES["nocturna"] += $localizacion->tipo3;
			$TOTALES["festivo"] += $localizacion->tipo4;
			$TOTALES["dominical"] += $localizacion->tipo5;


			$horas = $this->mainModel->getList("localizacion = '$nombre' AND fecha1 = '$fecha1' AND fecha2 = '$fecha2'", "")[0];

			$total1 = $horas->normal1+$horas->normal2+$horas->normal3;
			$facturadas['normal']+=$total1;

			$total2 = $horas->extra1+$horas->extra2+$horas->extra3;
			$facturadas['extra']+=$total2;

			$total2 = $horas->extra1+$horas->extra2+$horas->extra3;
			$facturadas['extra']+=$total2;

			$total3 = $horas->nocturna1+$horas->nocturna2+$horas->nocturna3;
			$facturadas['nocturna']+=$total3;

			$total4 = $horas->festivo1+$horas->festivo2+$horas->festivo3;
			$facturadas['festivo']+=$total4;

			$total5 = $horas->dominical1+$horas->dominical2+$horas->dominical3;
			$facturadas['dominical']+=$total5;

			$tabla.='
			<tr>
			<td>
			<div align="center">'.$nombre.'</div></td>
			<td><a href="/page/infolocalizacion/detalle?loc=' . $nombre . '&fecha1=' . $fecha1 . '&fecha2=' . $fecha2 . '&tipo=1" data-fancybox data-type="iframe" class="enlace_archivo2">'. $localizacion->tipo1.'</a></td>
			<td><div align="center">'. $total1.'</div></td>
			<td><a href="/page/infolocalizacion/detalle?loc=' . $nombre . '&fecha1=' . $fecha1 . '&fecha2=' . $fecha2 . '&tipo=2" data-fancybox data-type="iframe" class="enlace_archivo2">'.$localizacion->tipo2.'</a></td>


    		<td><div align="center">'.$total2.'</div></td>

      		<td><a href="/page/infolocalizacion/detalle?loc=' . $nombre . '&fecha1=' . $fecha1 . '&fecha2=' . $fecha2 . '&tipo=3" data-fancybox data-type="iframe" class="enlace_archivo2">'. $localizacion->tipo3.'</a></td>
			<td><div align="center">'.$total3.'</div></td>

			<td><a href="/page/infolocalizacion/detalle?loc=' . $nombre . '&fecha1=' . $fecha1 . '&fecha2=' . $fecha2 . '&tipo=4" data-fancybox data-type="iframe" class="enlace_archivo2">'. $localizacion->tipo4.'</a></td>
			<td><div align="center">'.$total4.'</div></td>1

			<td><a href="/page/infolocalizacion/detalle?loc=' . $nombre . '&fecha1=' . $fecha1 . '&fecha2=' . $fecha2 . '&tipo=5" data-fancybox data-type="iframe" class="enlace_archivo2">'. $localizacion->tipo5.'</a></td>
			<td><div align="center">'.$total5.'</div></td>

			</tr>';
		}
		
		$tabla.='
		<tr>
		<td></td>

		<td><div align="center"><strong>'. $TOTALES["normal"].'</strong></div></td>
		<td><div align="center"><strong>'. $facturadas['normal'].'</strong></div></td>
		<td><div align="center"><strong>'. $TOTALES["extra"].'</strong></div></td>
		<td><div align="center"><strong>'. $facturadas['extra'].'</strong></div></td>
		<td><div align="center"><strong>'. $TOTALES["nocturna"].'</strong></div></td>
		<td><div align="center"><strong>'. $facturadas['nocturna'].'</strong></div></td>
		<td><div align="center"><strong>'. $TOTALES["festivo"].'</strong></div></td>
		<td><div align="center"><strong>'. $facturadas['festivo'].'</strong></div></td>
		<td><div align="center"><strong>'. $TOTALES["dominical"].'</strong></div></td>
	
		<td><div align="center"><strong>'. $facturadas['dominical'].'</strong></div></td>
		</tr>';
		$this->_view->tabla = $tabla;
	}

	/**
	 * Genera la Informacion necesaria para editar o crear un  facturadas  y muestra su formulario
	 *
	 * @return void.
	 */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_facturadas_" . date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if ($content->id) {
				$this->_view->content = $content;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar facturadas";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear facturadas";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear facturadas";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
	 * Inserta la informacion de un facturadas  y redirecciona al listado de facturadas.
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
			$data['log_tipo'] = 'CREAR FACTURADAS';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe un identificador  y Actualiza la informacion de un facturadas  y redirecciona al listado de facturadas.
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
			$data['log_tipo'] = 'EDITAR FACTURADAS';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe un identificador  y elimina un facturadas  y redirecciona al listado de facturadas.
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
					$data['log_tipo'] = 'BORRAR FACTURADAS';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Facturadas.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		$data['fecha1'] = $this->_getSanitizedParam("fecha1");
		$data['fecha2'] = $this->_getSanitizedParam("fecha2");
		$data['localizacion'] = $this->_getSanitizedParam("localizacion");
		if ($this->_getSanitizedParam("normal1") == '') {
			$data['normal1'] = '0';
		} else {
			$data['normal1'] = $this->_getSanitizedParam("normal1");
		}
		if ($this->_getSanitizedParam("normal2") == '') {
			$data['normal2'] = '0';
		} else {
			$data['normal2'] = $this->_getSanitizedParam("normal2");
		}
		if ($this->_getSanitizedParam("normal3") == '') {
			$data['normal3'] = '0';
		} else {
			$data['normal3'] = $this->_getSanitizedParam("normal3");
		}
		if ($this->_getSanitizedParam("extra1") == '') {
			$data['extra1'] = '0';
		} else {
			$data['extra1'] = $this->_getSanitizedParam("extra1");
		}
		if ($this->_getSanitizedParam("extra2") == '') {
			$data['extra2'] = '0';
		} else {
			$data['extra2'] = $this->_getSanitizedParam("extra2");
		}
		if ($this->_getSanitizedParam("extra3") == '') {
			$data['extra3'] = '0';
		} else {
			$data['extra3'] = $this->_getSanitizedParam("extra3");
		}
		if ($this->_getSanitizedParam("nocturna1") == '') {
			$data['nocturna1'] = '0';
		} else {
			$data['nocturna1'] = $this->_getSanitizedParam("nocturna1");
		}
		if ($this->_getSanitizedParam("nocturna2") == '') {
			$data['nocturna2'] = '0';
		} else {
			$data['nocturna2'] = $this->_getSanitizedParam("nocturna2");
		}
		if ($this->_getSanitizedParam("nocturna3") == '') {
			$data['nocturna3'] = '0';
		} else {
			$data['nocturna3'] = $this->_getSanitizedParam("nocturna3");
		}
		if ($this->_getSanitizedParam("festivo1") == '') {
			$data['festivo1'] = '0';
		} else {
			$data['festivo1'] = $this->_getSanitizedParam("festivo1");
		}
		if ($this->_getSanitizedParam("festivo2") == '') {
			$data['festivo2'] = '0';
		} else {
			$data['festivo2'] = $this->_getSanitizedParam("festivo2");
		}
		if ($this->_getSanitizedParam("festivo3") == '') {
			$data['festivo3'] = '0';
		} else {
			$data['festivo3'] = $this->_getSanitizedParam("festivo3");
		}
		if ($this->_getSanitizedParam("dominical1") == '') {
			$data['dominical1'] = '0';
		} else {
			$data['dominical1'] = $this->_getSanitizedParam("dominical1");
		}
		if ($this->_getSanitizedParam("dominical2") == '') {
			$data['dominical2'] = '0';
		} else {
			$data['dominical2'] = $this->_getSanitizedParam("dominical2");
		}
		if ($this->_getSanitizedParam("dominical3") == '') {
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
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object)Session::getInstance()->get($this->namefilter);
			if ($filters->fecha_inicio != '' && $filters->fecha_fin != '') {
				// $filtros = $filtros . " fecha1>='" . $filters->fecha_inicio . "' AND fecha1 <='" . $filters->fecha_fin . "' AND fecha2>='" . $filters->fecha_inicio . "' AND fecha2 <='" . $filters->fecha_fin . "' ";
				$filtros = $filtros . " AND ((planilla_horas.fecha >= '" . $filters->fecha_inicio . "' AND planilla_horas.fecha<='" . $filters->fecha_fin . "') OR planilla_horas.fecha='0000-00-00') ";
				$filtros = $filtros . " AND ((planilla.fecha1 >= '" . $filters->fecha_inicio . "' AND planilla.fecha2<='" . $filters->fecha_fin . "' AND planilla_horas.fecha='0000-00-00') OR planilla_horas.fecha!='0000-00-00') ";
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
			$parramsfilter['fecha_inicio'] = $this->_getSanitizedParam("fecha_inicio");
			$parramsfilter['fecha_fin'] = $this->_getSanitizedParam("fecha_fin");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}
/* 
protected function getFilter()
  {
    $filtros = " 1 = 1 ";
    $padre = $this->_getSanitizedParam('padre');
    $filtros = $filtros . " AND contenido_padre = '$padre' ";
    if (Session::getInstance()->get($this->namefilter) != "") {
      $filters = (object) Session::getInstance()->get($this->namefilter);
      if ($filters->contenido_seccion != '') {
        $filtros = $filtros . " AND contenido_seccion ='" . $filters->contenido_seccion . "'";
      }
      if ($filters->contenido_titulo != '') {
        $filtros = $filtros . " AND contenido_titulo LIKE '%" . $filters->contenido_titulo . "%'";
      }
      if ($filters->contenido_fecha != '') {
        $filtros = $filtros . " AND contenido_fecha LIKE '%" . $filters->contenido_fecha . "%'";
      }
    }
    return $filtros;
  }


  protected function filters()
  {
    if ($this->getRequest()->isPost() == true) {
      Session::getInstance()->set($this->namepageactual, 1);
      $parramsfilter = array();
      $parramsfilter['contenido_seccion'] =  $this->_getSanitizedParam("contenido_seccion");
      $parramsfilter['contenido_titulo'] =  $this->_getSanitizedParam("contenido_titulo");
      $parramsfilter['contenido_fecha'] =  $this->_getSanitizedParam("contenido_fecha");
      Session::getInstance()->set($this->namefilter, $parramsfilter);
    }
    if ($this->_getSanitizedParam("cleanfilter") == 1) {
      Session::getInstance()->set($this->namefilter, '');
      Session::getInstance()->set($this->namepageactual, 1);
    }
  } */