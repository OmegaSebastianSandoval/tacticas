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
		if ($amount != 'Todos') {;


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
		}
		//FILTROS
		if ($this->getRequest()->isPost() == true) {
			Session::getInstance()->set($this->namepageactual, 1);
			$parramsfilter = array();
			$parramsfilter['fecha_inicio'] = $this->_getSanitizedParam("fecha_inicio");
			$parramsfilter['empresa'] = $this->_getSanitizedParam("empresa");
			$parramsfilter['fecha_fin'] = $this->_getSanitizedParam("fecha_fin");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filtros = " 1 ";
			$filtros2 = "";
			$filters1 = (object) Session::getInstance()->get($this->namefilter);
			if ($filters1->fecha_inicio != '' && $filters1->fecha_fin) {
				$filtros = $filtros . " AND planilla_horas.fecha >='" . $filters1->fecha_inicio . "' AND planilla_horas.fecha <='" . $filters1->fecha_fin . "' ";

				$filtros2 = $filtros2 . " fecha1 >= '" . $filters1->fecha_inicio . "' AND fecha1 <= '" . $filters1->fecha_fin . "' AND fecha2 >= ' " . $filters1->fecha_inicio . "' AND fecha2 <= '" . $filters1->fecha_fin . "' ";
			}

			if ($filters1->empresa != '') {
				$filtros .= " AND planilla.empresa ='" . $filters1->empresa . "'";
				$filtros2 .= " AND planilla.empresa = '$filters1->empresa' ";
			}
		}

		$filters = (object) Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;


		if ($filtros == "" && $filtros2 == "" || !$filtros && !$filtros2) {

			$currentDate = date('Y-m-d');  // Obtener la fecha actual en formato Y-m-d

			if (date('d') <= 15) {
				// Si estamos antes o en el día 15 del mes actual
				$this->_view->fecha_inicio = $fecha_inicio = date('Y-m-15', strtotime('previous month'));  // Fecha del día 15 del mes anterior
				$this->_view->fecha_fin = $fecha_fin = date('Y-m-t', strtotime('previous month'));   // Fecha del último día del mes anterior		
			} else {
				// Si estamos después del día 15 del mes actual
				$this->_view->fecha_inicio = $fecha_inicio = date('Y-m-01');   // Fecha del primer día del mes actual
				$this->_view->fecha_fin = $fecha_fin = date('Y-m-15');  // Fecha del día 15 del mes actual				
			}

			$filtros .= " 1 AND planilla_horas.fecha >='" . $fecha_inicio . "' AND planilla_horas.fecha <='" . $fecha_fin . "' ";
			$filtros2 = $filtros2 . " fecha1 >= '" . $fecha_inicio . "' AND fecha1 <= '" . $fecha_fin . "' AND fecha2 >= ' " . $fecha_inicio . "' AND fecha2 <= '" . $fecha_fin . "' ";
		}

		$order = "nombre1 ASC";
		if ($amount == 'Todos') {
			$cedulas = $planillaHorasModel->getPlanillaHoras($filtros, $order);
		} else {
			$cedulas2 = $planillaHorasModel->getPlanillaHoras($filtros, $order);
			$cedulas = $planillaHorasModel->getPlanillaHorasPages($filtros, $order, $start, $amount);
		}


		$planillaParametros = $planillaParametrosModel->getById(1);
		$planillas = $planillaModel->getList($filtros2, "");


		$aux = explode("-", $this->_getSanitizedParam("fecha_inicio"));
		$aux2 = explode("-", $this->_getSanitizedParam("fecha_fin"));
		$this->_view->mes = $mes = $aux[1] * 1;
		$this->_view->mes2 = $mes2 = $aux2[1] * 1;


		$TOTAL = 0;
		$TOTAL_DECIMO = 0;




		foreach ($planillas as $value) {
			$planilla = $value->id;
			$fecha1 = $value->fecha1;
			$fecha2 = $value->fecha2;
			$total_bruta = [];
			$totales = [];
	
			$total_normal = [];
			$total_extra = [];
			$total_nocturna = [];
			$total_festivo = [];
			$total_dominical = [];
			$tipo = '0';
			$aumento = 0;
	
			$horas = 0;
			// Filtrar los empleados que tienen la misma planilla
			/* $empleadosPlanilla = array_filter($cedulas, function ($empleado) use ($planilla) {
				return $empleado->planilla === $planilla;
			}); */

			foreach ($cedulas as $empleado) {
				$cedula = $empleado->cedula;
				// Aquí puedes realizar el código correspondiente para cada empleado de la planilla actual
				// Acceder a los datos del empleado: $empleado->cedula, $empleado->planilla, etc.

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

			/* 	if ($cedulasAsignacion->sin_seguridad == '1') {
					$seguridad_social = 0;
					$seguridad_social2 = 0;
					$seguro_educativo = 0;
					$seguro_educativo2 = 0;
					$riesgos = 0;
				} */

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


		$this->_view->pages = $this->pages;
		if ($amount != 'Todos') {
			$this->_view->totalpages = ceil(count($cedulas2) / $amount);
			$this->_view->page = $page;
			$this->_view->register_number = count($cedulas2);
		} else {
			$this->_view->totalpages = 1;
			$this->_view->register_number = count($cedulas);
		}
	}

	public function exportarAction()
	{
		$this->setLayout('blanco');


		$planillaParametrosModel = new Page_Model_DbTable_Parametros();
		$planillaHorasModel = new Page_Model_DbTable_Planillahoras();
		$planillaModel = new Page_Model_DbTable_Planilla();
		$planillaTotalesModel = new Page_Model_DbTable_Planillatotales();
		$planillaAsignacionModel = new Page_Model_DbTable_Planillaasignacion();



		//FILTROS
		if ($this->getRequest()->isPost() == true) {
			Session::getInstance()->set($this->namepageactual, 1);
			$parramsfilter = array();
			$parramsfilter['fecha_inicio'] = $this->_getSanitizedParam("fecha_inicio");
			$parramsfilter['empresa'] = $this->_getSanitizedParam("empresa");
			$parramsfilter['fecha_fin'] = $this->_getSanitizedParam("fecha_fin");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filtros = " 1 ";
			$filtros2 = "";
			$filters1 = (object) Session::getInstance()->get($this->namefilter);
			if ($filters1->fecha_inicio != '' && $filters1->fecha_fin) {
				$filtros = $filtros . " AND planilla_horas.fecha >='" . $filters1->fecha_inicio . "' AND planilla_horas.fecha <='" . $filters1->fecha_fin . "' ";

				$filtros2 = $filtros2 . " fecha1 >= '" . $filters1->fecha_inicio . "' AND fecha1 <= '" . $filters1->fecha_fin . "' AND fecha2 >= ' " . $filters1->fecha_inicio . "' AND fecha2 <= '" . $filters1->fecha_fin . "' ";
			}

			if ($filters1->empresa != '') {
				$filtros .= " AND planilla.empresa ='" . $filters1->empresa . "'";
				$filtros2 .= " AND planilla.empresa = '$filters1->empresa' ";
			}
		}

		$filters = (object) Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;


		if ($filtros == "" && $filtros2 == "" || !$filtros && !$filtros2) {
			$currentDate = date('Y-m-d');  // Obtener la fecha actual en formato Y-m-d

			if (date('d') <= 15) {

				// Si estamos antes o en el día 15 del mes actual
				$this->_view->fecha_inicio = $fecha_inicio = date('Y-m-15', strtotime('previous month'));  // Fecha del día 15 del mes anterior
				$this->_view->fecha_fin = $fecha_fin = date('Y-m-t', strtotime('previous month'));   // Fecha del último día del mes anterior
				/* 	echo "Fecha 1: " . $previousMonth15 . "<br>";
				echo "Fecha 2: " . $previousMonth30 . "<br>"; */
			} else {

				// Si estamos después del día 15 del mes actual
				$this->_view->fecha_inicio = $fecha_inicio = date('Y-m-01');   // Fecha del primer día del mes actual
				$this->_view->fecha_fin = $fecha_fin = date('Y-m-15');  // Fecha del día 15 del mes actual
				/* echo "Fecha 1: " . $currentMonth1 . "<br>";
				echo "Fecha 2: " . $currentMonth15 . "<br>"; */
			}

			$filtros .= " 1 AND planilla_horas.fecha >='" . $fecha_inicio . "' AND planilla_horas.fecha <='" . $fecha_fin . "' ";
			$filtros2 = $filtros2 . " fecha1 >= '" . $fecha_inicio . "' AND fecha1 <= '" . $fecha_fin . "' AND fecha2 >= ' " . $fecha_inicio . "' AND fecha2 <= '" . $fecha_fin . "' ";
		}

		$order = "nombre1 ASC";
		$cedulas = $planillaHorasModel->getPlanillaHoras($filtros, $order);
		$planillaParametros = $planillaParametrosModel->getById(1);
		$planillas = $planillaModel->getList($filtros2, "");


		$aux = explode("-", $this->_getSanitizedParam("fecha_inicio"));
		$aux2 = explode("-", $this->_getSanitizedParam("fecha_fin"));
		$mes = $aux[1] * 1;
		$mes2 = $aux2[1] * 1;

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
			$empleadosPlanilla = array_filter($cedulas, function ($empleado) use ($planilla) {
				return $empleado->planilla === $planilla;
			});

			foreach ($cedulas as $empleado) {
				$cedula = $empleado->cedula;
				// Aquí puedes realizar el código correspondiente para cada empleado de la planilla actual
				// Acceder a los datos del empleado: $empleado->cedula, $empleado->planilla, etc.


				// 223206
				$cedulasAsignacion = $planillaAsignacionModel->getList(" planilla = $planilla AND cedula = '" . $cedula . "' ", "cedula ASC")[0];
				//SEGURIDAD SOCIAL

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

				$decimo[$cedula] = round($total_bruta[$cedula] * $planillaParametros->decimo / 100, 2);

				$TOTAL += $total_bruta[$cedula];
				$TOTAL_DECIMO += $decimo[$cedula];
			}
		}
		$key = 1;
		$list_empresa = $this->getEmpresa();
		if ($filters1->empresa == "") {
			$output = '<div align="center" style="font-size:20px">Informe seguro social</div>';
		} else {
			$output = '<div align="center">Informe seguro social de la empresa <strong>' . $list_empresa[$filters1->empresa] . '</strong></div>';
		}
		if ($filters1->fecha_inicio == "" && $filters1->fecha_fin == "") {
			$output .= '<div align="center">Desde: ' . $fecha_inicio . ' - Hasta: ' . $fecha_fin . '</div>';
		} else {
			$output .= '<div align="center">Desde: ' . $filters1->fecha_inicio . ' - Hasta: ' . $filters1->fecha_fin . '</div>';
		}






		$output .= '<table border="1" cellspacing="0" cellpadding="5">';
		$output .= '
		<tr>
		<th>Item</th>
		<th>Documento</th>
		<th>Nombre</th>
		<th>Salario bruto</th>
		<th>Decimo</th>
		</tr>';


		if ($mes == 4 or $mes == 8 or $mes == 12 or $mes2 == 4 or $mes2 == 8 or $mes2 == 12) {
		}


		foreach ($cedulas as $key => $row) {
			$key++;

			$output .= '
	<tr>
	  <td>' . $key . '</td>
	  <td>' . $row->cedula . '</td>
	  <td>' . $row->nombre1 . '</td>
	  <td>' . $this->formato_numero($total_bruta[$row->cedula]) . '</td>
	  ';
			if ($mes == 4 or $mes == 8 or $mes == 12 or $mes2 == 4 or $mes2 == 8 or $mes2 == 12) {
				$output .= '
	 	<td>' . $this->formato_numero($decimo[$row->cedula]) . '</td>';
			}

			$output .= '</tr>';
		}
		$output .= '
		<tr>
		<td></td>
		<td></td>
		<td><div align="right"><strong>TOTAL</strong></div></td>
		<td><strong>' . $this->formato_numero($TOTAL) . '</strong></td>	';
		if ($mes == 4 or $mes == 8 or $mes == 12 or $mes2 == 4 or $mes2 == 8 or $mes2 == 12) {

			$output .= ' <td><strong>' . $this->formato_numero($TOTAL_DECIMO) . '</strong></td>';
		}

		$output .= '</tr>';

		$output .= '</table>';
		$hoy = date('Ym-d h:m:s');
		header('Content-Type: application/xls');
		header('Content-Disposition: attachment; filename=Informe_seguro' . $hoy . '.xls');
		echo $output;
	}


	public function formato_numero($n)
	{
		return number_format($n, 2, ',', '');
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
