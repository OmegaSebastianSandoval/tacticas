<?php

/**
 * Controlador de Localizaciones que permite la  creacion, edicion  y eliminacion de los locaci&oacute;n del Sistema
 */
class Page_viaticosController extends Page_mainController
{
	public $botonpanel = 6;
	/**
	 * $mainModel  instancia del modelo de  base de datos locaci&oacute;n
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
	protected $_csrf_section = "page_viaticos";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador localizaciones .
	 *
	 * @return void.
	 */
	public function init()
	{
		/* if ((Session::getInstance()->get("kt_login_level") == '2' )) {
			header('Location: /page/panel');
		} */
		$this->mainModel = new Page_Model_DbTable_Localizaciones();
		$this->namefilter = "parametersfilterviaticos";
		$this->route = "/page/viaticos";
		$this->namepages = "pages_viaticos";
		$this->namepageactual = "page_actual_viaticos";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 50;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  locaci&oacute;n con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Informe de viaticos";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->list_empresa = $this->getEmpresa();
		$filters = (object) Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		// print_r($filters);
		$resultado = $this->getFilter();
		$filtros = $resultado['filtros'];
		$filtros2 = $resultado['filtros2'];
		/* 	echo $filtros;
		echo '<br>';
		echo $filtros2; */
		if ($filtros == ' 1 ' && $filtros2 == ' 1 ') {
			$this->_view->noContent = 1;
			return;
		}

		$this->_view->empresas = $this->mainModel->getList("", "nombre");
		$planillaHorasModel = new Page_Model_DbTable_Planillahoras();

		$planillaParametrosModel = new Page_Model_DbTable_Parametros();
		$planillaModel = new Page_Model_DbTable_Planilla();
		$planillaAsignacionModel = new Page_Model_DbTable_Planillaasignacion();
		$planillaTotalesModel = new Page_Model_DbTable_Planillatotales();



		$cedulas = $planillaHorasModel->getPlanillaHorasViaticos($filtros, "nombre1 ASC");
		$planillaParametros = $planillaParametrosModel->getById(1);
		$planillas = $planillaModel->getList($filtros2, "");
		/* 	echo '<pre>';
		print_r($planillas);
		print_r($cedulas);

		echo '</pre>'; */


		$totales = [];
		$total_normal = [];
		$total_extra = [];
		$total_nocturna = [];
		$total_festivo = [];
		$total_bruta = [];
		$total_dominical = [];
		$decimo = [];
		$vacaciones = [];
		$antiguedad = [];
		$viaticos = [];

		$total_provisiones = [];

		$TOTAL = 0;

		foreach ($planillas as $value) {
			$planilla = $value->id;
			/* 	// Filtrar los empleados que tienen la misma planilla
			$empleadosPlanilla = array_filter($cedulas, function ($empleado) use ($planilla) {
				return $empleado->planilla === $planilla;
			}); */
			foreach ($cedulas as $empleado) {
				$cedula = $empleado->cedula;



				$planillaTotales = $planillaTotalesModel->getList(" planilla = $planilla AND cedula = '" . $cedula . "' ", "")[0];
				$viaticos[$cedula] += $planillaTotales->viaticos;
				$TOTAL += $viaticos[$cedula];
			}
		}
		$this->_view->viaticos = $viaticos;
		$this->_view->TOTAL = $TOTAL;
		$this->_view->cedulas = $cedulas;
	}




	public function exportarAction()
	{
		$this->setLayout('blanco');
		header("Content-Type: text/html;charset=utf-8");
		$this->filters();
		$list_empresa = $this->getEmpresa();
		$filters = (object) Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		// print_r($filters);
		$resultado = $this->getFilter();
		$filtros = $resultado['filtros'];
		$filtros2 = $resultado['filtros2'];
		$empresa = $resultado['empresa'];
		$fecha_inicio = $resultado['fecha_inicio'];
		$fecha_fin = $resultado['fecha_fin'];
		/* 	echo $filtros;
	echo '<br>';
	echo $filtros2; */
		$this->_view->empresas = $this->mainModel->getList("", "nombre");
		$planillaHorasModel = new Page_Model_DbTable_Planillahoras();

		$planillaModel = new Page_Model_DbTable_Planilla();
		$planillaTotalesModel = new Page_Model_DbTable_Planillatotales();



		$cedulas = $planillaHorasModel->getPlanillaHorasViaticos($filtros, "nombre1 ASC");
		$planillas = $planillaModel->getList($filtros2, "");

		$viaticos = [];


		$TOTAL = 0;
		if ($empresa == '') {
			$output = '<div align="center" style="font-size:18px;color:#0158A8;font-weight:700;">Informe de vi&aacute;ticos</div>';
		} else {
			$output = '<div align="center" style="font-size:18px;color:#0158A8;font-weight:700;">Informe de vi&aacute;ticos de la empresa ' . $list_empresa[$empresa] . '</div>';
		}
		$output .= '<div align="center">Desde: <strong>' . $fecha_inicio . '</strong> - Hasta: <strong>' . $fecha_fin . '</strong></div>';

		$output .= '<table border="1" cellpadding="3" cellspacing="0">';
		$output .= '
	<tr>
	<td>Item</td>
	<td>Documento</td>
	<td>Nombre</td>
	<td>Vi&aacute;ticos</td>
	</tr>';
		$i = 0;
		foreach ($planillas as $value) {
			$planilla = $value->id;
			/* 	// Filtrar los empleados que tienen la misma planilla
		$empleadosPlanilla = array_filter($cedulas, function ($empleado) use ($planilla) {
			return $empleado->planilla === $planilla;
		}); */
			foreach ($cedulas as $empleado) {
				$i++;
				$cedula = $empleado->cedula;

				$planillaTotales = $planillaTotalesModel->getList(" planilla = $planilla AND cedula = '" . $cedula . "' ", "")[0];
				$viaticos[$cedula] += $planillaTotales->viaticos;
				$TOTAL += $viaticos[$cedula];

				$output .= '
	<tr>
	<td>' . $i . '</td>
	<td>' . $cedula . '</td>
	<td>' . $empleado->nombre1 . '</td>
	<td>' . $this->formato_numero($viaticos[$cedula]) . '</td>
	</tr>';
			}
		}
		$output .= '
	<tr>
	<td></td>
	<td></td>
	<td style="text-align:right;"><strong>TOTAL</strong></td>
	<td style="text-align:right;"><strong>' . $this->formato_numero2($TOTAL) . '</strong></td>
	</tr>';
		$output .= '</table>';
		$hoy = date('Ym-d h:m:s');


		header('Content-Type: application/xls');
		header('Content-Disposition: attachment; filename=Informe_de_viaticos' . $hoy . '.xls');
		echo $output;
	}

	 function formato_numero($n)
	{
		return number_format($n, 2, ',', '');
	}


	public	function formato_numero2($n)
	{
		return number_format($n, 2, '.', ',');
	}
	private function getEmpresa()
	{
		$modelData = new Page_Model_DbTable_Dependempresa();
		if (Session::getInstance()->get("kt_login_level") == 3) {
			$asignacion = Session::getInstance()->get("kt_login_asignacion");
			// echo $asignacion;
			$data = $modelData->getListAsignacion(" FIND_IN_SET(id, '$asignacion') ");
		} else if (Session::getInstance()->get("kt_login_level") == 2) {
			$empresa = Session::getInstance()->get("kt_login_empresa");
			$data = $modelData->getList("id = '$empresa'", "");
		} else {
			$data = $modelData->getList();
		}

		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->id] = $value->nombre;
		}
		return $array;
	}

	/**
	 * Genera la consulta con los filtros de este controlador.
	 *
	 * @return array cadena con los filtros que se van a asignar a la base de datos
	 */
	protected function getFilter()
	{
		// $filtros = " 1 = 1 ";
		$filtros = " 1 ";
		$filtros2 = " 1 ";


		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object) Session::getInstance()->get($this->namefilter);

			if ($filters->empresa != '') {
				$filtros = $filtros . " AND planilla.empresa ='" . $filters->empresa . "'";
				$filtros2 = $filtros2 . " AND planilla.empresa ='" . $filters->empresa . "'";
			}

			if ($filters->fecha_inicio != '' && $filters->fecha_fin != '') {
				$filtros = $filtros . "  AND planilla_horas.fecha >= '" . $filters->fecha_inicio . "' AND planilla_horas.fecha<='" . $filters->fecha_fin . "' ";

				$filtros2 = $filtros2 . " AND fecha1>='" . $filters->fecha_inicio . "' AND fecha1 <='" . $filters->fecha_fin . "' AND fecha2>='" . $filters->fecha_inicio . "' AND fecha2 <='" . $filters->fecha_fin . "'  ";
			}
		} /* else {
			$filters = (object) Session::getInstance()->get($this->namefilter);
			if ($filters->fecha_inicio == '' && $filters->fecha_fin == '') {
				$currentDate = date('Y-m-d'); // Obtener la fecha actual en formato Y-m-d

				if (date('d') <= 15) {

					// Si estamos antes o en el día 15 del mes actual
					$this->_view->fecha_inicio = $filters->fecha_inicio  = date('Y-m-15', strtotime('previous month')); // Fecha del día 15 del mes anterior
					$this->_view->fecha_fin = $filters->fecha_fin = date('Y-m-t', strtotime('previous month')); // Fecha del último día del mes anterior
				
				} else {

					// Si estamos después del día 15 del mes actual
					$this->_view->fecha_inicio = 	$filters->fecha_inicio = date('Y-m-01'); // Fecha del primer día del mes actual
					$this->_view->fecha_fin =	$filters->fecha_fin = date('Y-m-15'); // Fecha del día 15 del mes actual

				}

				$filtros = $filtros . "  AND planilla_horas.fecha >= '" . $filters->fecha_inicio . "' AND planilla_horas.fecha<='" . $filters->fecha_fin . "' ";

				$filtros2 = $filtros2 . " AND fecha1>='" . $filters->fecha_inicio . "' AND fecha1 <='" . $filters->fecha_fin . "' AND fecha2>='" . $filters->fecha_inicio . "' AND fecha2 <='" . $filters->fecha_fin . "'  ";
			}
		} */
		return array('filtros' => $filtros, 'filtros2' => $filtros2, 'fecha_inicio' => $filters->fecha_inicio, 'fecha_fin' => $filters->fecha_fin, 'empresa' => $filters->empresa);
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
			$parramsfilter['empresa'] = $this->_getSanitizedParam("empresa");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}
