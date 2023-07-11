<?php

use Dompdf\FrameDecorator\Page;

/**
 * Controlador de Localizaciones que permite la  creacion, edicion  y eliminacion de los locaci&oacute;n del Sistema
 */
class Page_segurosocialController extends Page_mainController
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
	protected $_csrf_section = "page_segurosocial";

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
		$this->namefilter = "parametersfiltersegurosocial";
		$this->route = "/page/segurosocial";
		$this->namepages = "pages_segurosocial";
		$this->namepageactual = "page_actual_segurosocial";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 100;
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
		$title = "Informe de seguro social";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->_view->list_empresa = $this->getEmpresa();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];


		$planillaParametrosModel = new Page_Model_DbTable_Parametros();
		$planillaHorasModel = new Page_Model_DbTable_Planillahoras();
		$planillaModel = new Page_Model_DbTable_Planilla();
		$planillaTotalesModel = new Page_Model_DbTable_Planillatotales();
		$planillaAsignacionModel = new Page_Model_DbTable_Planillaasignacion();




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

		//FILTROS

		Session::getInstance()->set($this->namepageactual, 1);
		$parramsfilter = array();
		$parramsfilter['fecha_inicio'] =  $this->_getSanitizedParam("fecha_inicio");
		$parramsfilter['empresa'] =  $this->_getSanitizedParam("empresa");
		$parramsfilter['fecha_fin'] =  $this->_getSanitizedParam("fecha_fin");
		Session::getInstance()->set($this->namefilter, $parramsfilter);

		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters1 = (object)Session::getInstance()->get($this->namefilter);
		}
		print_r($filters1);

/* 		$this->_view->empresa = $empresa =  $this->_getSanitizedParam("empresa");
		$this->_view->fecha_inicio = $fecha_inicio =  $this->_getSanitizedParam("fecha_inicio");
		$this->_view->fecha_fin = $fecha_fin =  $this->_getSanitizedParam("fecha_fin");
 */

		$filters = "  ";
		$filtersPlanillaHoras = " 1 ";


		if ($fecha_inicio == "" && $fecha_fin == "") {
			$fecha_inicio = date("Y") . "-05-01";
			$fecha_fin = date("Y") . "-05-15";
		}
		$filtersPlanillaHoras .= " AND planilla_horas.fecha >='" . $fecha_inicio . "' AND planilla_horas.fecha <='" . $fecha_fin . "' ";






		$filters = $filters . " fecha1 >= '" . $fecha_inicio . "' AND fecha1 <= '" . $fecha_fin . "' AND fecha2 >= ' " . $fecha_inicio . "' AND fecha2 <= '" . $fecha_fin . "' ";

		if ($empresa != "") {
			$filtersPlanillaHoras .= " AND planilla.empresa ='" . $empresa . "'";
			$filters .= " AND planilla.empresa = '$empresa' ";
		}



		// echo $filters;
		// echo $filters;
		$order = "nombre1 ASC";
		$cedulas = $planillaHorasModel->getPlanillaHoras($filtersPlanillaHoras, $order);

		$cedulas2 = $planillaHorasModel->getPlanillaHorasPages($filtersPlanillaHoras, $order, $start, $amount);
		$planillaParametros = $planillaParametrosModel->getById(1);
		$planillas = $planillaModel->getList($filters, "");




		/* 
				echo '<pre>';
		// print_r($planillaParametros);
		print_r($planillas);
		print_r($cedulas2);
		echo '</pre>';
 */

		$aux = explode("-", $this->_getSanitizedParam("fecha_inicio"));
		$aux2 = explode("-", $this->_getSanitizedParam("fecha_fin"));
		$this->_view->mes = $mes = $aux[1] * 1;
		$this->_view->mes2 = $mes2 = $aux2[1] * 1;

		$TOTAL = 0;
		$TOTAL_DECIMO = 0;
		$total_bruta = [];
		$totales = [
			'normal' => 0,
			'extra' => 0,
			'nocturna' => 0,
			'festivo' => 0,
			'dominical' => 0,
			'bruta' => 0,
			'seguridad_social' => 0,
			'seguro_educativo' => 0,
			'seguridad_social2' => 0,
			'seguro_educativo2' => 0,
			'riesgos' => 0,
			'total_seguro' => 0
		];

		$total_normal = [];
		$total_extra = [];
		$total_nocturna = [];
		$total_festivo = [];
		$total_dominical = [];
		$tipo = '0'; //NORMAL
		$aumento = 0;

		$horas = 0;



		foreach ($planillas as $value) {
			$planilla = $value->id;
			$fecha1 = $value->fecha1;
			$fecha2 = $value->fecha2;

			// Filtrar los empleados que tienen la misma planilla
			$empleadosPlanilla = array_filter($cedulas2, function ($empleado) use ($planilla) {
				return $empleado->planilla === $planilla;
			});

			foreach ($empleadosPlanilla as $empleado) {
				$cedula = $empleado->cedula;
				// Aquí puedes realizar el código correspondiente para cada empleado de la planilla actual
				// Acceder a los datos del empleado: $empleado->cedula, $empleado->planilla, etc.


				// 223206
				$cedulasAsignacion = $planillaAsignacionModel->getList(" planilla = $planilla AND cedula = '" . $cedula . "' ", "cedula ASC")[0];
				//SEGURIDAD SOCIAL
				/* echo '<pre>';
				print_r($cedulasAsignacion);
				echo '</pre>'; */
				//PARAMETROS

				$f1 = " AND ( (fecha >= '" . $fecha1 . "' AND fecha<='" . $fecha2 . "') OR fecha='0000-00-00' ) ";
				$f2 = " AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') ";


				/* --------------------------------------------
				INICIO HORA NORMAL
				-------------------------------------------- */
				$tipo = '1'; //NORMAL
				$aumento = 1;

				$horas = $planillaHorasModel->getSumPlanillaHoras(" planilla = $planilla  AND cedula = '" . $cedula . "' AND tipo = '$tipo ' $f1  $f2", "")[0];

				$valor_hora = round($cedulasAsignacion->valor_hora * $aumento, 2);
				$total_normal[$cedula] += $horas->total * $valor_hora * 1;
				$totales['normal'] += $horas->total * $valor_hora * 1;
				/* --------------------------------------------
				FIN HORA NORMAL
				-------------------------------------------- */



				/* --------------------------------------------
				INICIO HORA EXTRA
				-------------------------------------------- */
				$tipo = '2'; //EXTRA
				$aumento = 1 + ($planillaParametros->horas_extra / 100);

				$horas = $planillaHorasModel->getSumPlanillaHoras(" planilla = $planilla  AND cedula = '$cedula' AND tipo = '$tipo ' $f1  $f2", "")[0];

				$valor_hora = round($cedulasAsignacion->valor_hora * $aumento, 2);
				$total_extra[$cedula] += $horas->total * $valor_hora * 1;
				$totales['extra'] += $horas->total * $valor_hora * 1;
				/* --------------------------------------------
				FIN HORA EXTRA 93-22
				-------------------------------------------- */

				/* --------------------------------------------
				INICIO HORA NOCTURNA
				-------------------------------------------- */
				$tipo = '3'; //NOCTURNA
				$aumento = 1 + ($planillaParametros->horas_nocturnas / 100);
				$horas = $planillaHorasModel->getSumPlanillaHoras(" planilla = $planilla  AND cedula = '$cedula' AND tipo = '$tipo ' $f1  $f2", "")[0];

				$valor_hora = round($cedulasAsignacion->valor_hora * $aumento, 2);
				$total_nocturna[$cedula] += $horas->total * $valor_hora * 1;
				$totales['nocturna'] += $horas->total * $valor_hora * 1;
				/* --------------------------------------------
				FIN HORA NOCTURNA
				-------------------------------------------- */

				/* --------------------------------------------
				INICIO HORA FESTIVO
				-------------------------------------------- */
				$tipo = '4'; //FESTIVO
				$aumento = 1 + ($planillaParametros->festivos / 100);
				$horas = $planillaHorasModel->getSumPlanillaHoras(" planilla = $planilla  AND cedula = '$cedula' AND tipo = '$tipo ' $f1  $f2", "")[0];

				$valor_hora = round($cedulasAsignacion->valor_hora * $aumento, 2);
				$total_festivo[$cedula] += $horas->total * $valor_hora * 1;
				$totales['festivo'] += $horas->total * $valor_hora * 1;
				/* --------------------------------------------
				FIN HORA FESTIVO
				-------------------------------------------- */


				/* --------------------------------------------
				INICIO HORA DOMINICAL
				-------------------------------------------- */
				$tipo = '5'; //DOMINICAL
				$aumento = 1 + ($planillaParametros->horas_dominicales / 100);
				$horas = $planillaHorasModel->getSumPlanillaHoras(" planilla = $planilla  AND cedula = '$cedula' AND tipo = '$tipo ' $f1  $f2", "")[0];

				$valor_hora = round($cedulasAsignacion->valor_hora * $aumento, 2);
				$total_dominical[$cedula] += $horas->total * $valor_hora * 1;
				$totales['dominical'] += $horas->total * $valor_hora * 1;
				/* --------------------------------------------
				FIN HORA DOMINICAL
				-------------------------------------------- */

				$total_bruta[$cedula] += $total_normal[$cedula] + $total_extra[$cedula] + $total_nocturna[$cedula] + $total_festivo[$cedula] + $total_dominical[$cedula];
				$totales['bruta'] += $total_bruta[$cedula];



				$seguridad_social = round($total_bruta[$cedula] * $planillaParametros->seguridad_social / 100, 2);

				$seguro_educativo = round($total_bruta[$cedula] * $planillaParametros->seguro_educativo / 100, 2);

				$seguridad_social2 = round($total_bruta[$cedula] * $planillaParametros->seguridad_social2 / 100, 2);

				$seguro_educativo2 = round($total_bruta[$cedula] * $planillaParametros->seguro_educativo2 / 100, 2);

				$riesgos = round($total_bruta[$cedula] * $planillaParametros->riesgos_profesionales / 100, 2);

				if ($cedulasAsignacion->sin_seguridad == '1') {
					$seguridad_social = 0;
					$seguridad_social2 = 0;
					$seguro_educativo = 0;
					$seguro_educativo2 = 0;
					$riesgos = 0;
				}

				$total_seguro = $seguridad_social + $seguro_educativo + $seguridad_social2 + $seguro_educativo2 + $riesgos;

				$totales['seguridad_social'] += $seguridad_social;
				$totales['seguro_educativo'] += $seguro_educativo;
				$totales['seguridad_social2'] += $seguridad_social2;
				$totales['seguro_educativo2'] += $seguro_educativo2;
				$totales['riesgos'] += $riesgos;
				$totales['total_seguro'] += $total_seguro;



				//PROVISION   
				// 8-747-281
				/* 		$horasPlanillaTotales = $planillaTotalesModel->getList(" planilla = '$planilla' AND cedula = '" . $cedula . "'", ""); */

				$decimo[$cedula] = round($total_bruta[$cedula] * $planillaParametros->decimo / 100, 2);

				/* 	$vacaciones[$cedula] = round($total_bruta[$cedula] * $planillaParametros->vacaciones / 100, 2);

				$antiguedad[$cedula] = round($total_bruta[$cedula] * $planillaParametros->antiguedad / 100, 2); */
				/* 
				$total_provisiones[$cedula] = $decimo[$cedula] + $vacaciones[$cedula] + $antiguedad[$cedula]; */

				$TOTAL += $total_bruta[$cedula];
				$TOTAL_DECIMO += $decimo[$cedula];
			}
		}
		$this->_view->TOTAL = $TOTAL;
		$this->_view->TOTAL_DECIMO = $TOTAL_DECIMO;
		$this->_view->total_bruta = $total_bruta;
		$this->_view->decimo = $decimo;
		$this->_view->cedulas = $cedulas;
		$this->_view->planillas = $planillas;


		$this->_view->register_number = count($cedulas);
		$this->_view->pages = $this->pages;
		$this->_view->totalpages = ceil(count($cedulas) / $amount);
		$this->_view->cedulas = $cedulas2;
		$this->_view->page = $page;

		/* $cedulas2 = $planillaHorasModel->getPlanillaHorasPages($filtersPlanillaHoras, $order, $start, $amount);
		$this->_view->page = $page; */
		/* 	$this->_view->register_number = count($cedulas);
		$this->_view->pages = $this->pages;
		$this->_view->totalpages = ceil(count($cedulas) / $amount);
		$this->_view->page = $page; */
		//$this->_view->cedulas = $cedulas2;
		/* 
		echo '<pre>';

		 print_r($cedulas);

		echo '</pre>'; */
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
}
