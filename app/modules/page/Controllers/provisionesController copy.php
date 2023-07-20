<?php

/**
 * Controlador de Localizaciones que permite la  creacion, edicion  y eliminacion de los locaci&oacute;n del Sistema
 */
class Page_provisionesController extends Page_mainController
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
	protected $_csrf_section = "page_provisiones";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */


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
		$this->mainModel = new Page_Model_DbTable_Planilla();
		$this->namefilter = "parametersfilterprovisiones";
		$this->route = "/page/provisiones";
		$this->namepages = "pages_provisiones";
		$this->namepageactual = "page_actual_provisiones";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 50;
		}
		parent::init();
		// Session::getInstance()->set($this->namefilter, '');
	}


	/**
	 * Recibe la informacion y  muestra un listado de  locaci&oacute;n con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Informe de provisiones";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$list_empresa = $this->getEmpresa();
		$filters = (object) Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		/* 		print_r($filters);
		echo "|||||||||||||||||||||||||"; */
		$resultado = $this->getFilter();
		$filtros = $resultado['filtros'];
		$filtros2 = $resultado['filtros2'];


		/* echo($filtros);
	
 */
		/* echo $filtros;
		echo "|||||||||||||||||||||||||";

		echo $filtros2;

 */
		$planillaHorasModel = new Page_Model_DbTable_Planillahoras();


		$planillaParametrosModel = new Page_Model_DbTable_Parametros();
		$planillaModel = new Page_Model_DbTable_Planilla();
		$planillaAsignacionModel = new Page_Model_DbTable_Planillaasignacion();
		$planillaTotalesModel = new Page_Model_DbTable_Planillatotales();



		$cedulas = $planillaHorasModel->getPlanillaHorasProvisiones($filtros, "nombre1 ASC");
		$planillaParametros = $planillaParametrosModel->getById(1);

		$planillas = $planillaModel->getList($filtros2, "");

		$total_neta = [];
		$total_neta2 = [];

		$i = 0;

		foreach ($planillas as $value) {
			$i++;


			$totales = [];
			$total_normal = [];
			$total_extra = [];
			$total_nocturna = [];
			$total_festivo = [];
			$total_dominical = [];
			$total_bruta = [];
			$decimo = [];
			$vacaciones = [];
			$antiguedad = [];
			$total_provisiones = [];

			foreach ($cedulas as $empleado) {
				$planilla = $value->id;
				$fecha1 = $value->fecha1;
				$fecha2 = $value->fecha2;
				$empresa = $value->empresa;
				$cedula = $empleado->cedula;

				$cedulasAsignacion = $planillaAsignacionModel->getList(" planilla = $planilla AND cedula = '" . $cedula . "' ", "cedula ASC")[0];
				$horas = $planillaHorasModel->getSumPlanillaHorasSalarioNew($planilla, $cedula, $fecha1, $fecha2);

				$aumento = 1;
				$valor_hora = round($cedulasAsignacion->valor_hora * $aumento, 2);
				$total_normal[$cedula] += $horas[0]->total * $valor_hora * 1;
				$totales['normal'] += $horas[0]->total * $valor_hora * 1;

				$aumento = 1 + ($planillaParametros->horas_extra / 100);

				$valor_hora = round($cedulasAsignacion->valor_hora * $aumento, 2);
				$total_extra[$cedula] += $horas[1]->total * $valor_hora * 1;
				$totales['extra'] += $horas[1]->total * $valor_hora * 1;

				$aumento = 1 + ($planillaParametros->horas_nocturnas / 100);

				$valor_hora = round($cedulasAsignacion->valor_hora * $aumento, 2);
				$total_nocturna[$cedula] += $horas[2]->total * $valor_hora * 1;
				$totales['nocturna'] += $horas[2]->total * $valor_hora * 1;

				$aumento = 1 + ($planillaParametros->festivos / 100);

				$valor_hora = round($cedulasAsignacion->valor_hora * $aumento, 2);
				$total_festivo[$cedula] += $horas[3]->total * $valor_hora * 1;
				$totales['festivo'] += $horas[3]->total * $valor_hora * 1;

				$aumento = 1 + ($planillaParametros->horas_dominicales / 100);

				$valor_hora = round($cedulasAsignacion->valor_hora * $aumento, 2);
				$total_dominical[$cedula] += $horas[4]->total * $valor_hora * 1;
				$totales['dominical'] += $horas[4]->total * $valor_hora * 1;




				$total_bruta[$cedula] += $total_normal[$cedula] + $total_extra[$cedula] + $total_nocturna[$cedula] + $total_festivo[$cedula] + $total_dominical[$cedula];
				$totales['bruta'] += $total_bruta[$cedula];



				$seguridad_social = round($total_bruta[$cedula] * $planillaParametros->seguridad_social / 100, 2);

				$seguro_educativo = round($total_bruta[$cedula] * $planillaParametros->seguro_educativo / 100, 2);

				$seguridad_social2 = round($total_bruta[$cedula] * $planillaParametros->seguridad_social2 / 100, 2);

				$seguro_educativo2 = round($total_bruta[$cedula] * $planillaParametros->seguro_educativo2 / 100, 2);

				$riesgos = round($total_bruta[$cedula] * $planillaParametros->riesgos_profesionales / 100, 2);

				$total_seguro = $seguridad_social + $seguro_educativo + $seguridad_social2 + $seguro_educativo2 + $riesgos;

				$totales['seguridad_social'] += $seguridad_social;
				$totales['seguro_educativo'] += $seguro_educativo;
				$totales['seguridad_social2'] += $seguridad_social2;
				$totales['seguro_educativo2'] += $seguro_educativo2;
				$totales['riesgos'] += $riesgos;
				$totales['total_seguro'] += $total_seguro;

				/* --------------------------------------------
					PROVISION
					-------------------------------------------- */
				$planillaTotales = $planillaTotalesModel->getList(" planilla = $planilla AND cedula = '" . $cedula . "' ", "")[0];

				$decimo[$cedula] += round($total_bruta[$cedula] * $planillaParametros->decimo / 100, 2);
				$vacaciones[$cedula] += round($total_bruta[$cedula] * $planillaParametros->vacaciones / 100, 2);
				$antiguedad[$cedula] += round($total_bruta[$cedula] * $planillaParametros->antiguedad / 100, 2);
				$total_provisiones[$cedula] += $decimo[$cedula] + $vacaciones[$cedula] + $antiguedad[$cedula];

				$totales['decimo'] += $decimo[$cedula];
				$totales['vacaciones'] += $vacaciones[$cedula];
				$totales['antiguedad'] += $antiguedad[$cedula];
				$totales['total_provisiones'] += $total_provisiones[$cedula];


				/* $total_neta[$cedula] = $total_bruta[$cedula] - $seguridad_social - $seguro_educativo + $planillaTotales->viaticos - $planillaTotales->prestamos - $planillaTotales->prestamos_financiera;
				$totales['neta'] += $total_neta[$cedula]; 

				$total_neta2[$empresa] += $total_neta[$cedula]; */
				$this->_view->tabla  .= '
					<tr>
					<td> ' . $i  . '</td>
					<td> ' . $cedula . '</td>
					<td> ' . $empleado->nombres1 . '</td>
					<td> ' . $this->formato_numero($decimo[$cedula]) . '</td>
					<td> ' . $this->formato_numero($vacaciones[$cedula]) . '</td>
					<td> ' . $this->formato_numero($antiguedad[$cedula]) . '</td>
					<td> ' . $this->formato_numero($total_provisiones[$cedula]) . '</td>
					

					</tr>';
			}
		}
		// $TOTAL += $total_neta2[$empresa];

		/* $TOTAL += $total_neta2[$empresa];
					$this->_view->tabla  .= '
					<tr>
					<td> ' . $i  . '</td>
					<td> ' . $list_empresa[$empresa] . '</td>
					<td> ' . $this->formato_numero2($total_neta2[$empresa]) . '</td>
					</tr>';
			$this->_view->tabla2  .= '
			<tr>
			<td></td>
			<td class="text-end"><strong>TOTAL</strong> </td>
			<td> ' . $this->formato_numero2($TOTAL) . '</td>
			</tr>'; */

		// $this->_view->cedulas = $cedulas;
		//$this->_view->total_neta2 = $total_neta2;
	}







	function obtenerIds($array)
	{
		$ids = array(); // Array para almacenar los valores de los ids

		foreach ($array as $obj) {
			$ids[] = $obj->id; // Agregar el valor del id al array
		}

		$ids_str = implode(',', $ids); // Convertir el array en una cadena separada por comas

		return $ids_str;
	}

	public function formato_numero2($n)
	{
		return number_format($n, 2, '.', ',');
	}
	public function formato_numero($n)
	{
		return number_format($n,2,',','');
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
		} else if (Session::getInstance()->get($this->namefilter) == "" || !(Session::getInstance()->get($this->namefilter))) {
			$filters = (object) Session::getInstance()->get($this->namefilter);

			if ($filters->fecha_inicio == '' && $filters->fecha_fin == '') {
				$currentDate = date('Y-m-d'); // Obtener la fecha actual en formato Y-m-d

				if (date('d') <= 15) {

					// Si estamos antes o en el día 15 del mes actual
					$this->_view->fecha_inicio = $filters->fecha_inicio  = date('Y-m-15', strtotime('previous month')); // Fecha del día 15 del mes anterior
					$this->_view->fecha_fin = $filters->fecha_fin = date('Y-m-t', strtotime('previous month')); // Fecha del último día del mes anterior
					/* 	echo "Fecha 1: " . $previousMonth15 . "<br>";
								   echo "Fecha 2: " . $previousMonth30 . "<br>"; */
				} else {


					// Si estamos después del día 15 del mes actual
					$this->_view->fecha_inicio = 	$filters->fecha_inicio = date('Y-m-01'); // Fecha del primer día del mes actual
					$this->_view->fecha_fin =	$filters->fecha_fin = date('Y-m-15'); // Fecha del día 15 del mes actual



				}
				$filtros = $filtros . " AND  planilla_horas.fecha >= '" . $filters->fecha_inicio . "' AND planilla_horas.fecha<='" . $filters->fecha_fin . "' ";

				$filtros2 = $filtros2 . " AND fecha1>='" . $filters->fecha_inicio . "' AND fecha1 <='" . $filters->fecha_fin . "' AND fecha2>='" . $filters->fecha_inicio . "' AND fecha2 <='" . $filters->fecha_fin . "'  ";
			}
		}
		return array('filtros' => $filtros, 'filtros2' => $filtros2);
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
