<?php

use Symfony\Component\Yaml\Tests\A;

/**
 * Controlador de Planilla que permite la  creacion, edicion  y eliminacion de los planilla del Sistema
 */
// Import the core class of PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Import the Xlsx writer class
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;

class Page_planillaController extends Page_mainController
{
	/**
	 * $mainModel  instancia del modelo de  base de datos planilla
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
	protected $_csrf_section = "page_planilla";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador planilla .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Page_Model_DbTable_Planilla();
		$this->namefilter = "parametersfilterplanilla";
		$this->route = "/page/planilla";
		$this->namepages = "pages_planilla";
		$this->namepageactual = "page_actual_planilla";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 50;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  planilla con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Administración de planilla";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters = (object)Session::getInstance()->get($this->namefilter);
		/* 		echo '<pre>';

		print_r($filters);
		echo '</pre>'; */

		$this->_view->filters = $filters;
		$filters = $this->getFilter();
		// echo $filters;
		$order = "fecha1 DESC, empresa.nombre ASC";
		$list = $this->mainModel->getListPlanillas($filters, $order);
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
		$this->_view->lists = $this->mainModel->getListPagesPlanillas($filters, $order, $start, $amount);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->list_empresa = $this->getEmpresa();
		$this->_view->list_meses = $this->getMeses();
		$this->_view->list_quincena = $this->getQuincenas();
	}

	/**
	 * Genera la Informacion necesaria para editar o crear un  planilla  y muestra su formulario
	 *
	 * @return void.
	 */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_planilla_" . date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$this->_view->list_empresa = $this->getEmpresa();
		$this->_view->list_meses = $this->getMeses();
		$this->_view->list_quincena = $this->getQuincenas();


		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if ($content->id) {
				$this->_view->content = $content;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar planilla";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear planilla";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear planilla";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
	 * Inserta la informacion de un planilla  y redirecciona al listado de planilla.
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
			$data['log_tipo'] = 'CREAR PLANILLA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe un identificador  y Actualiza la informacion de un planilla  y redirecciona al listado de planilla.
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
			$data['log_tipo'] = 'EDITAR PLANILLA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe un identificador  y elimina un planilla  y redirecciona al listado de planilla.
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
					$data['log_tipo'] = 'BORRAR PLANILLA';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}
		header('Location: ' . $this->route . '' . '');
	}

	public function consolidadoAction()
	{
		$this->_view->planila = $id = $this->_getSanitizedParam("planilla");
		$empresaModel = new Page_Model_DbTable_Empresas();
		$planillaAsignacionModel = new Page_Model_DbTable_PlanillaAsignacion();
		$parametrosModel = new Page_Model_DbTable_Parametros();
		$planillaHorasModel = new Page_Model_DbTable_PlanillaHoras();
		$this->_view->list_meses = $this->getMeses();
		$list_meses = $this->getMeses();

		$planilla = $this->mainModel->getById($id);
		$empresaId = $planilla->empresa;
		$empresa = $empresaModel->getById($empresaId);
		$cedulas = $planillaAsignacionModel->getListCedulas(" planilla= '$id'", "nombre1 ASC");
		$parametros = $parametrosModel->getById(1);

		$aux = explode("-", $planilla->fecha1);
		$aux2 = explode("-", $planilla->fecha2);
		$dia1 = $aux[2];
		$dia2 = $aux2[2];
		$mes = $aux[1] * 1;
		$anio = $aux[0];
		$title = "Planilla de pago del $dia1 al $dia2 de " . $list_meses[$mes] . " del $anio - $empresa->nombre ";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$fecha1 = $planilla->fecha1;
		$fecha2 = $planilla->fecha2;
		$f1 = " AND ( (fecha >= '$fecha1' AND fecha<='$fecha2') OR fecha='0000-00-00' ) ";
		$f2 = " AND (loc!='DESCANSO' AND loc!='VACACIONES' AND loc!='PERMISO' AND loc!='FALTA') ";
		$tabla = '';
		$i = 0;
		$totales = [];

		foreach ($cedulas as $value) {

			$i++;
			$cedula = $value->cedula;
			$valorHora = $value->valor_hora;

			//HORA NORMAL
			$aumento = 1;
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 1 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_normal = $horas->total * $valor_hora;
			$totales['normal'] += $total_normal;

			//HORA EXTRA
			$aumento = 1 + ($parametros->horas_extra / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 2 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_extra = $horas->total * $valor_hora;
			$totales['extra'] += $total_extra;

			//HORA NOCTURNA
			$aumento = 1 + ($parametros->horas_nocturnas / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 3 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_nocturna = $horas->total * $valor_hora;
			$totales['nocturna'] += $total_nocturna;


			//HORA FESTIVO
			$aumento = 1 + ($parametros->festivos / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 4 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_festivo = $horas->total * $valor_hora;
			$totales['festivo'] += $total_festivo;


			//HORA DOMINICAL
			$aumento = 1 + ($parametros->horas_dominicales / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 5 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_dominical = $horas->total * $valor_hora;
			$totales['dominical'] += $total_dominical;

			$total_bruta = $total_normal + $total_extra + $total_nocturna + $total_festivo + $total_dominical;
			$totales['bruta'] += $total_bruta;

			$seguridad_social = round($total_bruta * $parametros->seguridad_social / 100, 2);
			$seguro_educativo = round($total_bruta * $parametros->seguro_educativo / 100, 2);
			$seguridad_social2 = round($total_bruta * $parametros->seguridad_social2 / 100, 2);
			$seguro_educativo2 = round($total_bruta * $parametros->seguro_educativo2 / 100, 2);
			$riesgos = round($total_bruta * $parametros->riesgos_profesionales / 100, 2);

			if ($value->sin_seguridad == 1) {
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

			$decimo = round($total_bruta * $parametros->decimo / 100, 2);
			$vacaciones = round($total_bruta * $parametros->vacaciones / 100, 2);
			$antiguedad = round($total_bruta * $parametros->antiguedad / 100, 2);
			$total_provisiones = $decimo + $vacaciones + $antiguedad;


			$totales['decimo'] += $decimo;
			$totales['vacaciones'] += $vacaciones;
			$totales['antiguedad'] += $antiguedad;
			$totales['total_provisiones'] += $total_provisiones;

			$total_gastos = $total_bruta + $total_provisiones + $total_seguro - $seguridad_social - $seguro_educativo;
			$totales['total_gastos'] += $total_gastos;

			$tabla .= '
			<tr>
			<td><div align="center">' . $i . '</div></td>
			<td>
			<div align="left">' . $cedula  . '</div>
			<input id="cedula' . $i . '" name="cedula' . $i . '" type="hidden" value="' . $cedula  . '" />
			</td>
			<td><div align="left">' . $value->nombre1  . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_bruta) . '</div></td>
			<td><div align="center">' . $this->formato_numero($decimo) . '</div></td>
			<td><div align="center">' . $this->formato_numero($vacaciones) . '</div></td>
			<td><div align="center">' . $this->formato_numero($antiguedad) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_provisiones) . '</div></td>
			<td><div align="center">' . $this->formato_numero($seguridad_social) . '</div></td>
			<td><div align="center">' . $this->formato_numero($seguro_educativo) . '</div></td>
			<td><div align="center">' . $this->formato_numero($seguridad_social2) . '</div></td>
			<td><div align="center">' . $this->formato_numero($seguro_educativo2) . '</div></td>
			<td><div align="center">' . $this->formato_numero($riesgos) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_seguro) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_gastos) . '</div></td>
			</tr>';
		}
		$tabla .= '
		<tr>
		<td></td>
		<td></td>
		<td><div align="right"><strong>TOTAL</strong></div></td>
		
		<td><div align="center"><strong>' . $this->formato_numero($totales['bruta']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['decimo']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['vacaciones']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['antiguedad']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['total_provisiones']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguridad_social']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguro_educativo']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguridad_social2']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguro_educativo2']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['riesgos']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['total_seguro']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['total_gastos']) . '</strong></div></td>
		</tr>';

		$this->_view->tabla = $tabla;
		$this->_view->register_number = count($cedulas);
	}








	public function exportarconsolidadoAction()
	{
		$this->setLayout('blanco');

		$id = $this->_getSanitizedParam("planilla");
		$empresaModel = new Page_Model_DbTable_Empresas();
		$planillaAsignacionModel = new Page_Model_DbTable_PlanillaAsignacion();
		$parametrosModel = new Page_Model_DbTable_Parametros();
		$planillaHorasModel = new Page_Model_DbTable_PlanillaHoras();
		$this->_view->list_meses = $this->getMeses();
		$list_meses = $this->getMeses();

		$planilla = $this->mainModel->getById($id);
		$empresaId = $planilla->empresa;
		$empresa = $empresaModel->getById($empresaId);
		$cedulas = $planillaAsignacionModel->getListCedulas(" planilla= '$id'", "nombre1 ASC");
		$parametros = $parametrosModel->getById(1);

		$aux = explode("-", $planilla->fecha1);
		$aux2 = explode("-", $planilla->fecha2);
		$dia1 = $aux[2];
		$dia2 = $aux2[2];
		$mes = $aux[1] * 1;
		$anio = $aux[0];
		/* $title = "Planilla de pago del $dia1 al $dia2 de " . $list_meses[$mes] . " del $anio - $empresa->nombre ";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title; */
		$fecha1 = $planilla->fecha1;
		$fecha2 = $planilla->fecha2;
		$f1 = " AND ( (fecha >= '$fecha1' AND fecha<='$fecha2') OR fecha='0000-00-00' ) ";
		$f2 = " AND (loc!='DESCANSO' AND loc!='VACACIONES' AND loc!='PERMISO' AND loc!='FALTA') ";

		$output = '<div align="center" style="font-size:20px">Consolidado planilla de pago del ' . $dia1 . ' al ' . $dia2 . ' de ' . $list_meses[$mes] . ' del ' . $anio . ' - ' . $empresa->nombre . ' </div>';

		$output .= '<div align="center" style="font-size:17px">Desde: ' . $fecha1 . ' - Hasta: ' . $fecha2 . '</div>';
		$output .= '
		<table width="100%" border="1" cellpadding="3" cellspacing="0" class="tabla2">
		<tr>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>PAGOS</th>
			<th colspan="3">PROVISIONES</th>
			<th rowspan="2" valign="bottom">TOTAL PROVISIONES</th>
			<th colspan="5">SEGURIDAD SOCIAL</th>
			<th rowspan="2" valign="bottom">TOTAL SEGURO SOCIAL</th>
			<th rowspan="2" valign="bottom">TOTAL GASTOS PERSONAL</th>
		</tr>
		<tr>
			<th valign="bottom"><div align="left">Item</div></th>
			<th valign="bottom"><div align="left">Cedula</div></th>
			<th valign="bottom"><div align="left">Nombre</div></th>
			<th valign="bottom">NOMINA BRUTA</th>
			<th valign="bottom">DECIMO</th>
			<th valign="bottom">VACACIONES</th>
			<th valign="bottom">P. ANTIGUEDAD</th>
			<th valign="bottom">CUOTA EMPLEADO SEGURO SOCIAL</th>
			<th valign="bottom">CUOTA EMPLEADO SEGURO EDUCATIVO</th>
			<th valign="bottom">CUOTA EMPLEADOR SEGURO SOCIAL</th>
			<th valign="bottom">CUOTA EMPLEADOR SEGURO EDUCATIVO</th>
			<th valign="bottom">RIESGOS PROFESIONALES</th>
		</tr>
		
		';

		$i = 0;
		$totales = [];


		foreach ($cedulas as $value) {

			$i++;
			$cedula = $value->cedula;
			$valorHora = $value->valor_hora;

			//HORA NORMAL
			$aumento = 1;
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 1 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_normal = $horas->total * $valor_hora;
			$totales['normal'] += $total_normal;

			//HORA EXTRA
			$aumento = 1 + ($parametros->horas_extra / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 2 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_extra = $horas->total * $valor_hora;
			$totales['extra'] += $total_extra;

			//HORA NOCTURNA
			$aumento = 1 + ($parametros->horas_nocturnas / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 3 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_nocturna = $horas->total * $valor_hora;
			$totales['nocturna'] += $total_nocturna;


			//HORA FESTIVO
			$aumento = 1 + ($parametros->festivos / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 4 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_festivo = $horas->total * $valor_hora;
			$totales['festivo'] += $total_festivo;


			//HORA DOMINICAL
			$aumento = 1 + ($parametros->horas_dominicales / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 5 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_dominical = $horas->total * $valor_hora;
			$totales['dominical'] += $total_dominical;

			$total_bruta = $total_normal + $total_extra + $total_nocturna + $total_festivo + $total_dominical;
			$totales['bruta'] += $total_bruta;

			$seguridad_social = round($total_bruta * $parametros->seguridad_social / 100, 2);
			$seguro_educativo = round($total_bruta * $parametros->seguro_educativo / 100, 2);
			$seguridad_social2 = round($total_bruta * $parametros->seguridad_social2 / 100, 2);
			$seguro_educativo2 = round($total_bruta * $parametros->seguro_educativo2 / 100, 2);
			$riesgos = round($total_bruta * $parametros->riesgos_profesionales / 100, 2);

			if ($value->sin_seguridad == 1) {
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

			$decimo = round($total_bruta * $parametros->decimo / 100, 2);
			$vacaciones = round($total_bruta * $parametros->vacaciones / 100, 2);
			$antiguedad = round($total_bruta * $parametros->antiguedad / 100, 2);
			$total_provisiones = $decimo + $vacaciones + $antiguedad;


			$totales['decimo'] += $decimo;
			$totales['vacaciones'] += $vacaciones;
			$totales['antiguedad'] += $antiguedad;
			$totales['total_provisiones'] += $total_provisiones;

			$total_gastos = $total_bruta + $total_provisiones + $total_seguro - $seguridad_social - $seguro_educativo;
			$totales['total_gastos'] += $total_gastos;

			$output .= '
			<tr>
			<td><div align="center">' . $i . '</div></td>
			<td>
			<div align="left">' . $cedula  . '</div>
			<input id="cedula' . $i . '" name="cedula' . $i . '" type="hidden" value="' . $cedula  . '" />
			</td>
			<td><div align="left">' . $value->nombre1  . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_bruta) . '</div></td>
			<td><div align="center">' . $this->formato_numero($decimo) . '</div></td>
			<td><div align="center">' . $this->formato_numero($vacaciones) . '</div></td>
			<td><div align="center">' . $this->formato_numero($antiguedad) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_provisiones) . '</div></td>
			<td><div align="center">' . $this->formato_numero($seguridad_social) . '</div></td>
			<td><div align="center">' . $this->formato_numero($seguro_educativo) . '</div></td>
			<td><div align="center">' . $this->formato_numero($seguridad_social2) . '</div></td>
			<td><div align="center">' . $this->formato_numero($seguro_educativo2) . '</div></td>
			<td><div align="center">' . $this->formato_numero($riesgos) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_seguro) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_gastos) . '</div></td>
			</tr>';
		}
		$output .= '
		<tr>
		<td></td>
		<td></td>
		<td><div align="right"><strong>TOTAL</strong></div></td>
		
		<td><div align="center"><strong>' . $this->formato_numero($totales['bruta']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['decimo']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['vacaciones']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['antiguedad']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['total_provisiones']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguridad_social']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguro_educativo']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguridad_social2']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguro_educativo2']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['riesgos']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['total_seguro']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['total_gastos']) . '</strong></div></td>
		</tr>';

		$output .= '</table>';
		$hoy = date('Ym-d h:m:s');
		header('Content-Type: application/xls');
		header('Content-Disposition: attachment; filename=consolidado' . $hoy . '.xls');
		echo $output;
	}







	//TODO TOTAL NOMINA
	public function totalnominaAction()
	{

		$this->_view->planila = $id = $this->_getSanitizedParam("planilla");
		$empresaModel = new Page_Model_DbTable_Empresas();
		$planillaAsignacionModel = new Page_Model_DbTable_PlanillaAsignacion();
		$parametrosModel = new Page_Model_DbTable_Parametros();
		$planillaHorasModel = new Page_Model_DbTable_PlanillaHoras();
		$planillaTotales = new Page_Model_DbTable_PlanillaTotales();
		$this->_view->list_meses = $this->getMeses();
		$list_meses = $this->getMeses();

		$planilla = $this->mainModel->getById($id);
		$this->_view->planillaCerrada = $planilla->cerrada;
		$empresaId = $planilla->empresa;
		$empresa = $empresaModel->getById($empresaId);
		$cedulas = $planillaAsignacionModel->getListCedulas(" planilla= '$id'", "nombre1 ASC");
		$parametros = $parametrosModel->getById(1);

		$aux = explode("-", $planilla->fecha1);
		$aux2 = explode("-", $planilla->fecha2);
		$dia1 = $aux[2];
		$dia2 = $aux2[2];
		$mes = $aux[1] * 1;
		$anio = $aux[0];
		$title = " Total nómina bruta y neta <br> Planilla de pago del $dia1 al $dia2 de " . $list_meses[$mes] . " del $anio - $empresa->nombre ";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$fecha1 = $planilla->fecha1;
		$fecha2 = $planilla->fecha2;
		$f1 = " AND ( (fecha >= '$fecha1' AND fecha<='$fecha2') OR fecha='0000-00-00' ) ";
		$f2 = " AND (loc!='DESCANSO' AND loc!='VACACIONES' AND loc!='PERMISO' AND loc!='FALTA') ";
		$tabla = '';

		$i = 0;
		$totales = [];

		foreach ($cedulas as $value) {

			$i++;
			$cedula = $value->cedula;
			$valorHora = $value->valor_hora;

			//HORA NORMAL
			$aumento = 1;
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 1 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_normal = $horas->total * $valor_hora;
			$totales['normal'] += $total_normal;

			//HORA EXTRA
			$aumento = 1 + ($parametros->horas_extra / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 2 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_extra = $horas->total * $valor_hora;
			$totales['extra'] += $total_extra;

			//HORA NOCTURNA
			$aumento = 1 + ($parametros->horas_nocturnas / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 3 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_nocturna = $horas->total * $valor_hora;
			$totales['nocturna'] += $total_nocturna;


			//HORA FESTIVO
			$aumento = 1 + ($parametros->festivos / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 4 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_festivo = $horas->total * $valor_hora;
			$totales['festivo'] += $total_festivo;


			//HORA DOMINICAL
			$aumento = 1 + ($parametros->horas_dominicales / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 5 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_dominical = $horas->total * $valor_hora;
			$totales['dominical'] += $total_dominical;

			$total_bruta = $total_normal + $total_extra + $total_nocturna + $total_festivo + $total_dominical;
			$totales['bruta'] += $total_bruta;

			$seguridad_social = round($total_bruta * $parametros->seguridad_social / 100, 2);
			$seguro_educativo = round($total_bruta * $parametros->seguro_educativo / 100, 2);
			$seguridad_social2 = round($total_bruta * $parametros->seguridad_social2 / 100, 2);
			$seguro_educativo2 = round($total_bruta * $parametros->seguro_educativo2 / 100, 2);
			$riesgos = round($total_bruta * $parametros->riesgos_profesionales / 100, 2);

			if ($value->sin_seguridad == 1) {
				$seguridad_social = 0;
				$seguridad_social2 = 0;
				$seguro_educativo = 0;
				$seguro_educativo2 = 0;
				$riesgos = 0;
			}

			/* 	$total_seguro = $seguridad_social + $seguro_educativo + $seguridad_social2 + $seguro_educativo2 + $riesgos; */

			$totales['seguridad_social'] += $seguridad_social;
			$totales['seguro_educativo'] += $seguro_educativo;
			$totales['seguridad_social2'] += $seguridad_social2;
			$totales['seguro_educativo2'] += $seguro_educativo2;
			/* $totales['riesgos'] += $riesgos;
			$totales['total_seguro'] += $total_seguro; */

			/* $decimo = round($total_bruta * $parametros->decimo / 100, 2);
			$vacaciones = round($total_bruta * $parametros->vacaciones / 100, 2);
			$antiguedad = round($total_bruta * $parametros->antiguedad / 100, 2);
			$total_provisiones = $decimo + $vacaciones + $antiguedad;


			$totales['decimo'] += $decimo;
			$totales['vacaciones'] += $vacaciones;
			$totales['antiguedad'] += $antiguedad;
			$totales['total_provisiones'] += $total_provisiones;

			$total_gastos = $total_bruta + $total_provisiones + $total_seguro - $seguridad_social - $seguro_educativo;
			$totales['total_gastos'] += $total_gastos;
 */

			$planillaTotal = $planillaTotales->getList(" planilla = '$id' AND cedula = '$cedula'", "")[0];

			$total_neta = $total_bruta - $seguridad_social - $seguro_educativo + $planillaTotal->viaticos - $planillaTotal->prestamos - $planillaTotal->prestamos_financiera;


			$totales['neta'] += $total_neta;
			$totales['prestamos'] += $planillaTotal->prestamos * 1;
			$totales['prestamos2'] += $planillaTotal->prestamos_financiera * 1;
			$totales['decimo'] += $planillaTotal->decimo * 1;
			$totales['viaticos'] += $planillaTotal->viaticos * 1;


			$tabla .= '
			<tr>
			<td><div align="center">' . $i . '</div></td>
			<td>
			<div align="left">' . $cedula  . '</div>
			<input id="cedula' . $i . '" name="cedula' . $i . '" type="hidden" value="' . $cedula  . '" />
			</td>
			<td><div align="left">' . $value->nombre1  . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_normal) . '</div></td>
			
			<td><div align="center">' . $this->formato_numero($total_extra) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_nocturna) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_festivo) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_dominical) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_bruta) . '
			<input 
			id="total_bruta' . $i . '" 
			name="total_bruta' . $i . '" 
			type="hidden" 
			value="' . $total_bruta . '"/>
			</div>
			</td>
			<td><div align="center">' . $this->formato_numero($seguridad_social) . '
			<input 
			id="seguridad_social' . $i . '" 
			name="seguridad_social' . $i . '" 
			type="hidden" 
			value="' . $seguridad_social . '"/>
			</div>
			</td>
			<td><div align="center">' . $this->formato_numero($seguro_educativo) . '
			<input 
			id="seguro_educativo' . $i . '" 
			name="seguro_educativo' . $i . '" 
			type="hidden" 
			value="' . $seguro_educativo . '"/>
			</div>
			</td>

			<td>
			
            <div align="center"> 
                <input type="text" 
				name="viaticos' . $i . '"
				id="viaticos' . $i . '"
				value="' . $planillaTotal->viaticos . '"
							  class="form-control v" 

				onkeyup="total_neta();  guardar_neta(' . $i . ');" 
				onchange="total_neta(); guardar_neta(' . $i . ');" />  
            </div>
			
			</td>

			<td>
			
		  	<div align="center">
		   
			  <input 
			  type="text" 
			  name="prestamos' . $i . '" 
			  id="prestamos' . $i . '" 
			  value="' . $planillaTotal->prestamos . '" 
			  class="form-control v" 
			  onkeyup="total_neta(); guardar_neta(' . $i . ');" 
			  onchange="total_neta(); guardar_neta(' . $i . ');"
			   ' . ($_SESSION["kt_login_level"] == 3 ? 'readonly' : '') . ' />
			</div>
			
			
			</td>

			<td>
			
		  	<div align="center">
		   
			<input 
			type="text" 
			name="prestamos_financiera' . $i . '" 
			id="prestamos_financiera' . $i . '" 
			value="' . $planillaTotal->prestamos_financiera . '" 
			class="form-control v" 
			onkeyup="total_neta(); guardar_neta(' . $i . ');" 
			onchange="total_neta(); guardar_neta(' . $i . ');"
				 ' . ($_SESSION["kt_login_level"] == 3 ? 'readonly' : '') . ' />
			</div>
			
		
			</td>
			
			<td>
			
		  	<div align="center">
		   
			<input type="text" name="decimo' . $i . '" 
			id="decimo' . $i . '" 
			value="' . $planillaTotal->decimo . '" 			  
			class="form-control v" 
			onkeyup="total_neta(); guardar_neta(' . $i . ');" 
			onchange="total_neta(); guardar_neta(' . $i . ');"
			' . ($_SESSION["kt_login_level"] == 3 ? 'readonly' : '') . ' />
			</div>
			
			</td>
			<td>
          	<div align="center" id="neta' . $i . '">' . $this->formato_numero($total_neta) . '</div>
        </td>
    
			
			</tr>';
		}
		$tabla .= '
		<tr>
		<td></td>
		<td></td>
		<td><div align="right"><strong>TOTAL</strong></div></td>
		
		<td><div align="center"><strong>' . $this->formato_numero($totales['normal']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['extra']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['nocturna']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['festivo']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['dominical']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['bruta']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguridad_social']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguro_educativo']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['viaticos']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['prestamos']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['prestamos2']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['decimo']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['neta']) . '</strong></div></td>
		</tr>';

		$this->_view->tabla = $tabla;
		$this->_view->register_number = count($cedulas);
	}




	//TODO EXPORTAR TOTAL NOMINA 


	public function exportartotalnominaAction()
	{
		$this->setLayout('blanco');


		$this->_view->planila = $id = $this->_getSanitizedParam("planilla");
		$empresaModel = new Page_Model_DbTable_Empresas();
		$planillaAsignacionModel = new Page_Model_DbTable_PlanillaAsignacion();
		$parametrosModel = new Page_Model_DbTable_Parametros();
		$planillaHorasModel = new Page_Model_DbTable_PlanillaHoras();
		$planillaTotales = new Page_Model_DbTable_PlanillaTotales();
		$this->_view->list_meses = $this->getMeses();
		$list_meses = $this->getMeses();

		$planilla = $this->mainModel->getById($id);
		$this->_view->planillaCerrada = $planilla->cerrada;
		$empresaId = $planilla->empresa;
		$empresa = $empresaModel->getById($empresaId);
		$cedulas = $planillaAsignacionModel->getListCedulas(" planilla= '$id'", "nombre1 ASC");
		$parametros = $parametrosModel->getById(1);

		$aux = explode("-", $planilla->fecha1);
		$aux2 = explode("-", $planilla->fecha2);
		$dia1 = $aux[2];
		$dia2 = $aux2[2];
		$mes = $aux[1] * 1;
		$anio = $aux[0];

		$fecha1 = $planilla->fecha1;
		$fecha2 = $planilla->fecha2;
		$f1 = " AND ( (fecha >= '$fecha1' AND fecha<='$fecha2') OR fecha='0000-00-00' ) ";
		$f2 = " AND (loc!='DESCANSO' AND loc!='VACACIONES' AND loc!='PERMISO' AND loc!='FALTA') ";

		$output = '<div align="center" style="font-size:20px">Reporte total nómina  del ' . $dia1 . ' al ' . $dia2 . ' de ' . $list_meses[$mes] . ' del ' . $anio . ' - ' . $empresa->nombre . ' </div>';

		$output .= '<div align="center" style="font-size:17px">Desde: ' . $fecha1 . ' - Hasta: ' . $fecha2 . '</div>';
		$output .= '
		<table width="100%" border="1" cellpadding="3" cellspacing="0" class="tabla2">
		<tr class="text-center">

		<th valign="">ITEM</th>
		<th valign="">CÉDULA</th>
		<th valign=""> NOMBRE</th>
		<th valign="">TOTAL HORA NORMAL</th>
		<th valign="">TOTAL HORA EXTRA </th>
		<th valign="">TOTAL HORA NOCTURNA </th>
		<th valign="">TOTAL HORA FESTIVO </th>
		<th valign="">TOTAL HORA DOMINICAL </th>
		<th valign="">TOTAL NOMINA BRUTA</th>
		<th valign="">CUOTA EMPLEADO SEGURO SOCIAL </th>
		<th valign="">CUOTA EMPLEADO SEGURO EDUCATIVO</th>
		<th valign="">VIATICOS</th>
		<th valign="">PRESTAMOS EMPRESA</th>
		<th valign="">PRESTAMOS FINANCIERA</th>
		<th valign="">PAGO DECIMO</th>
		<th valign="">TOTAL NOMINA NETA</th>
	</tr>
		
		';
		$i = 0;
		$totales = [];

		foreach ($cedulas as $value) {

			$i++;
			$cedula = $value->cedula;
			$valorHora = $value->valor_hora;

			//HORA NORMAL
			$aumento = 1;
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 1 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_normal = $horas->total * $valor_hora;
			$totales['normal'] += $total_normal;

			//HORA EXTRA
			$aumento = 1 + ($parametros->horas_extra / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 2 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_extra = $horas->total * $valor_hora;
			$totales['extra'] += $total_extra;

			//HORA NOCTURNA
			$aumento = 1 + ($parametros->horas_nocturnas / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 3 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_nocturna = $horas->total * $valor_hora;
			$totales['nocturna'] += $total_nocturna;


			//HORA FESTIVO
			$aumento = 1 + ($parametros->festivos / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 4 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_festivo = $horas->total * $valor_hora;
			$totales['festivo'] += $total_festivo;


			//HORA DOMINICAL
			$aumento = 1 + ($parametros->horas_dominicales / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 5 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_dominical = $horas->total * $valor_hora;
			$totales['dominical'] += $total_dominical;

			$total_bruta = $total_normal + $total_extra + $total_nocturna + $total_festivo + $total_dominical;
			$totales['bruta'] += $total_bruta;

			$seguridad_social = round($total_bruta * $parametros->seguridad_social / 100, 2);
			$seguro_educativo = round($total_bruta * $parametros->seguro_educativo / 100, 2);
			$seguridad_social2 = round($total_bruta * $parametros->seguridad_social2 / 100, 2);
			$seguro_educativo2 = round($total_bruta * $parametros->seguro_educativo2 / 100, 2);
			$riesgos = round($total_bruta * $parametros->riesgos_profesionales / 100, 2);

			if ($value->sin_seguridad == 1) {
				$seguridad_social = 0;
				$seguridad_social2 = 0;
				$seguro_educativo = 0;
				$seguro_educativo2 = 0;
				$riesgos = 0;
			}

			/* 	$total_seguro = $seguridad_social + $seguro_educativo + $seguridad_social2 + $seguro_educativo2 + $riesgos; */

			$totales['seguridad_social'] += $seguridad_social;
			$totales['seguro_educativo'] += $seguro_educativo;
			$totales['seguridad_social2'] += $seguridad_social2;
			$totales['seguro_educativo2'] += $seguro_educativo2;
			/* $totales['riesgos'] += $riesgos;
			$totales['total_seguro'] += $total_seguro; */

			/* $decimo = round($total_bruta * $parametros->decimo / 100, 2);
			$vacaciones = round($total_bruta * $parametros->vacaciones / 100, 2);
			$antiguedad = round($total_bruta * $parametros->antiguedad / 100, 2);
			$total_provisiones = $decimo + $vacaciones + $antiguedad;


			$totales['decimo'] += $decimo;
			$totales['vacaciones'] += $vacaciones;
			$totales['antiguedad'] += $antiguedad;
			$totales['total_provisiones'] += $total_provisiones;

			$total_gastos = $total_bruta + $total_provisiones + $total_seguro - $seguridad_social - $seguro_educativo;
			$totales['total_gastos'] += $total_gastos;
 */

			$planillaTotal = $planillaTotales->getList(" planilla = '$id' AND cedula = '$cedula'", "")[0];

			$total_neta = $total_bruta - $seguridad_social - $seguro_educativo + $planillaTotal->viaticos - $planillaTotal->prestamos - $planillaTotal->prestamos_financiera;


			$totales['neta'] += $total_neta;
			$totales['prestamos'] += $planillaTotal->prestamos * 1;
			$totales['prestamos2'] += $planillaTotal->prestamos_financiera * 1;
			$totales['decimo'] += $planillaTotal->decimo * 1;
			$totales['viaticos'] += $planillaTotal->viaticos * 1;


			$output .= '
			<tr>
			<td><div align="center">' . $i . '</div></td>
			<td>
			<div align="left">' . $cedula  . '</div>
			<input id="cedula' . $i . '" name="cedula' . $i . '" type="hidden" value="' . $cedula  . '" />
			</td>
			<td><div align="left">' . $value->nombre1  . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_normal) . '</div></td>
			
			<td><div align="center">' . $this->formato_numero($total_extra) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_nocturna) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_festivo) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_dominical) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_bruta) . '
			<input 
			id="total_bruta' . $i . '" 
			name="total_bruta' . $i . '" 
			type="hidden" 
			value="' . $total_bruta . '"/>
			</div>
			</td>
			<td><div align="center">' . $this->formato_numero($seguridad_social) . '
			<input 
			id="seguridad_social' . $i . '" 
			name="seguridad_social' . $i . '" 
			type="hidden" 
			value="' . $seguridad_social . '"/>
			</div>
			</td>
			<td><div align="center">' . $this->formato_numero($seguro_educativo) . '
			<input 
			id="seguro_educativo' . $i . '" 
			name="seguro_educativo' . $i . '" 
			type="hidden" 
			value="' . $seguro_educativo . '"/>
			</div>
			</td>

			<td>
			<div align="center">' . $this->formato_numero($planillaTotal->viaticos) . '</div>		
			</td>

			<td>
			<div align="center">' . $this->formato_numero($planillaTotal->prestamos) . '</div>		
			</td>

			<td>
			<div align="center">' . $this->formato_numero($planillaTotal->prestamos_financiera) . '</div>
		  	
			
		
			</td>
			
			<td>
			<div align="center">' . $this->formato_numero($planillaTotal->decimo) . '</div>
		  	
			</td>
			<td>
          	<div align="center" id="neta' . $i . '">' . $this->formato_numero($total_neta) . '</div>
        </td>
    
			
			</tr>';
		}
		$output .= '
		<tr>
		<td></td>
		<td></td>
		<td><div align="right"><strong>TOTAL</strong></div></td>
		
		<td><div align="center"><strong>' . $this->formato_numero($totales['normal']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['extra']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['nocturna']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['festivo']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['dominical']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['bruta']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguridad_social']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguro_educativo']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['viaticos']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['prestamos']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['prestamos2']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['decimo']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['neta']) . '</strong></div></td>
		</tr>';

		$output .= '</table>';
		$hoy = date('Ym-d h:m:s');
		header('Content-Type: application/xls');
		header('Content-Disposition: attachment; filename=total_nomina' . $hoy . '.xls');
		echo $output;
	}



	public function limiteAction()
	{
		$this->_view->planila = $id = $this->_getSanitizedParam("planilla");
		$empresaModel = new Page_Model_DbTable_Empresas();
		$planillaAsignacionModel = new Page_Model_DbTable_PlanillaAsignacion();
		$parametrosModel = new Page_Model_DbTable_Parametros();
		$planillaHorasModel = new Page_Model_DbTable_PlanillaHoras();
		$this->_view->list_meses = $this->getMeses();
		$list_meses = $this->getMeses();

		$planilla = $this->mainModel->getById($id);
		$empresaId = $planilla->empresa;
		$empresa = $empresaModel->getById($empresaId);
		$cedulas = $planillaAsignacionModel->getListCedulas(" planilla= '$id'", "nombre1 ASC");
		$parametros = $parametrosModel->getById(1);

		$aux = explode("-", $planilla->fecha1);
		$aux2 = explode("-", $planilla->fecha2);
		$dia1 = $aux[2];
		$dia2 = $aux2[2];
		$mes = $aux[1] * 1;
		$anio = $aux[0];
		$title = " Reporte límite de horas <br> planilla de pago del $dia1 al $dia2 de " . $list_meses[$mes] . " del $anio - $empresa->nombre ";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$fecha1 = $planilla->fecha1;
		$fecha2 = $planilla->fecha2;
		$f1 = " AND ( (fecha >= '$fecha1' AND fecha<='$fecha2') OR fecha='0000-00-00' ) ";
		$f2 = " AND (loc!='DESCANSO' AND loc!='VACACIONES' AND loc!='PERMISO' AND loc!='FALTA') ";
		$tabla = '';
		$i = 0;
		$totales = [];

		foreach ($cedulas as $value) {

			$i++;
			$cedula = $value->cedula;
			$valorHora = $value->valor_hora;

			//HORA NORMAL
			$aumento = 1;
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 1 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_extra = $horas->total;
			$total_normal = $horas->total * $valor_hora;
			$totales['normal'] += $total_normal;

			//HORA EXTRA
			$aumento = 1 + ($parametros->horas_extra / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 2 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_extra = $horas->total * $valor_hora;
			$totales['extra'] += $total_extra;

			//HORA NOCTURNA
			$aumento = 1 + ($parametros->horas_nocturnas / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 3 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_nocturna = $horas->total * $valor_hora;
			$totales['nocturna'] += $total_nocturna;


			//HORA FESTIVO
			$aumento = 1 + ($parametros->festivos / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 4 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_festivo = $horas->total * $valor_hora;
			$totales['festivo'] += $total_festivo;


			//HORA DOMINICAL
			$aumento = 1 + ($parametros->horas_dominicales / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 5 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_dominical = $horas->total * $valor_hora;
			$total_dominical = $horas->total;
			$totales['dominical'] += $total_dominical;

			$total_bruta = $total_normal + $total_extra + $total_nocturna + $total_festivo + $total_dominical;
			$totales['bruta'] += $total_bruta;

			$seguridad_social = round($total_bruta * $parametros->seguridad_social / 100, 2);
			$seguro_educativo = round($total_bruta * $parametros->seguro_educativo / 100, 2);
			$seguridad_social2 = round($total_bruta * $parametros->seguridad_social2 / 100, 2);
			$seguro_educativo2 = round($total_bruta * $parametros->seguro_educativo2 / 100, 2);


			if ($value->sin_seguridad == 1) {
				$seguridad_social = 0;
				$seguridad_social2 = 0;
				$seguro_educativo = 0;
				$seguro_educativo2 = 0;
			}

			$totales['seguridad_social'] += $seguridad_social;
			$totales['seguro_educativo'] += $seguro_educativo;
			$totales['seguridad_social2'] += $seguridad_social2;
			$totales['seguro_educativo2'] += $seguro_educativo2;

			/* $total_seguro = $seguridad_social + $seguro_educativo + $seguridad_social2 + $seguro_educativo2 + $riesgos;

			$totales['seguridad_social'] += $seguridad_social;
			$totales['seguro_educativo'] += $seguro_educativo;
			$totales['seguridad_social2'] += $seguridad_social2;
			$totales['seguro_educativo2'] += $seguro_educativo2;
			$totales['riesgos'] += $riesgos;
			$totales['total_seguro'] += $total_seguro;

			$decimo = round($total_bruta * $parametros->decimo / 100, 2);
			$vacaciones = round($total_bruta * $parametros->vacaciones / 100, 2);
			$antiguedad = round($total_bruta * $parametros->antiguedad / 100, 2);
			$total_provisiones = $decimo + $vacaciones + $antiguedad;


			$totales['decimo'] += $decimo;
			$totales['vacaciones'] += $vacaciones;
			$totales['antiguedad'] += $antiguedad;
			$totales['total_provisiones'] += $total_provisiones;

			$total_gastos = $total_bruta + $total_provisiones + $total_seguro - $seguridad_social - $seguro_educativo;
			$totales['total_gastos'] += $total_gastos;
 */
			$tabla .= '
			<tr>
			<td><div align="center">' . $i . '</div></td>
			<td>
			<div align="left">' . $cedula  . '</div>
			<input id="cedula' . $i . '" name="cedula' . $i . '" type="hidden" value="' . $cedula  . '" />
			</td>
			<td><div align="left">' . $value->nombre1  . '</div></td>
			<td><div align="center">' . $planilla->limite_horas . '</div></td>
			<td ' . (($total_normal > $planilla->limite_horas) ? 'style="background:#FFCCCC;"' : '') . '> <div align="center">' . $this->formato_numero($total_normal) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_extra) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_nocturna) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_festivo) . '</div></td>
			<td><div align="center">' . $planilla->limite_dominicales . '</div></td>

			<td ' . (($total_dominical > $planilla->limite_dominicales) ? 'style="background:#FFCCCC;"' : '') . '> <div align="center">' . $this->formato_numero($total_dominical) . '</div></td>

			</tr>';
		}
		$tabla .= '
		<tr>
		<td></td>
		<td></td>
		<td><div align="right"><strong>TOTAL</strong></div></td>
		<td></td>
		
		<td><div align="center"><strong>' . $this->formato_numero($totales['normal']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['extra']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['nocturna']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['festivo']) . '</strong></div></td>
		<td></td>

		<td><div align="center"><strong>' . $this->formato_numero($totales['dominical']) . '</strong></div></td>
		</tr>';

		$this->_view->tabla = $tabla;
		$this->_view->register_number = count($cedulas);
	}

	public function exportarlimiteAction()
	{
		$this->setLayout('blanco');

		$this->_view->planila = $id = $this->_getSanitizedParam("planilla");
		$empresaModel = new Page_Model_DbTable_Empresas();
		$planillaAsignacionModel = new Page_Model_DbTable_PlanillaAsignacion();
		$parametrosModel = new Page_Model_DbTable_Parametros();
		$planillaHorasModel = new Page_Model_DbTable_PlanillaHoras();
		$this->_view->list_meses = $this->getMeses();
		$list_meses = $this->getMeses();

		$planilla = $this->mainModel->getById($id);
		$empresaId = $planilla->empresa;
		$empresa = $empresaModel->getById($empresaId);
		$cedulas = $planillaAsignacionModel->getListCedulas(" planilla= '$id'", "nombre1 ASC");
		$parametros = $parametrosModel->getById(1);

		$aux = explode("-", $planilla->fecha1);
		$aux2 = explode("-", $planilla->fecha2);
		$dia1 = $aux[2];
		$dia2 = $aux2[2];
		$mes = $aux[1] * 1;
		$anio = $aux[0];
		$title = " Reporte límite de horas <br> planilla de pago del $dia1 al $dia2 de " . $list_meses[$mes] . " del $anio - $empresa->nombre ";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$fecha1 = $planilla->fecha1;
		$fecha2 = $planilla->fecha2;
		$f1 = " AND ( (fecha >= '$fecha1' AND fecha<='$fecha2') OR fecha='0000-00-00' ) ";
		$f2 = " AND (loc!='DESCANSO' AND loc!='VACACIONES' AND loc!='PERMISO' AND loc!='FALTA') ";
		$tabla = '';
		$i = 0;
		$output = '<div align="center" style="font-size:20px">Reporte límite de horas  del ' . $dia1 . ' al ' . $dia2 . ' de ' . $list_meses[$mes] . ' del ' . $anio . ' - ' . $empresa->nombre . ' </div>';

		$output .= '<div align="center" style="font-size:17px">Desde: ' . $fecha1 . ' - Hasta: ' . $fecha2 . '</div>';
		$output .= '
		<table width="100%" border="1" cellpadding="3" cellspacing="0" class="tabla2">
		<tr class="text-center">
		<th>ITEM</th>
		<th>CÉDULA</th>
		<th> NOMBRE</th>
		<th>LIMITE HORAS NORMALES</th>
		<th> HORAS NORMALES</th>
		<th> HORAS EXTRA</th>
		<th>HORAS NOCTURNAS</th>
		<th>FESTIVOS</th>
		<th>LIMITE HORA DOMINICAL</th>
		<th> HORAS DOMINICALES</th>
	
	</tr>';
		$totales = [];

		foreach ($cedulas as $value) {

			$i++;
			$cedula = $value->cedula;
			$valorHora = $value->valor_hora;

			//HORA NORMAL
			$aumento = 1;
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 1 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_extra = $horas->total;
			$total_normal = $horas->total * $valor_hora;
			$totales['normal'] += $total_normal;

			//HORA EXTRA
			$aumento = 1 + ($parametros->horas_extra / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 2 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_extra = $horas->total * $valor_hora;
			$totales['extra'] += $total_extra;

			//HORA NOCTURNA
			$aumento = 1 + ($parametros->horas_nocturnas / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 3 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_nocturna = $horas->total * $valor_hora;
			$totales['nocturna'] += $total_nocturna;


			//HORA FESTIVO
			$aumento = 1 + ($parametros->festivos / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 4 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_festivo = $horas->total * $valor_hora;
			$totales['festivo'] += $total_festivo;


			//HORA DOMINICAL
			$aumento = 1 + ($parametros->horas_dominicales / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 5 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_dominical = $horas->total * $valor_hora;
			$total_dominical = $horas->total;
			$totales['dominical'] += $total_dominical;

			$total_bruta = $total_normal + $total_extra + $total_nocturna + $total_festivo + $total_dominical;
			$totales['bruta'] += $total_bruta;

			$seguridad_social = round($total_bruta * $parametros->seguridad_social / 100, 2);
			$seguro_educativo = round($total_bruta * $parametros->seguro_educativo / 100, 2);
			$seguridad_social2 = round($total_bruta * $parametros->seguridad_social2 / 100, 2);
			$seguro_educativo2 = round($total_bruta * $parametros->seguro_educativo2 / 100, 2);


			if ($value->sin_seguridad == 1) {
				$seguridad_social = 0;
				$seguridad_social2 = 0;
				$seguro_educativo = 0;
				$seguro_educativo2 = 0;
			}

			$totales['seguridad_social'] += $seguridad_social;
			$totales['seguro_educativo'] += $seguro_educativo;
			$totales['seguridad_social2'] += $seguridad_social2;
			$totales['seguro_educativo2'] += $seguro_educativo2;

			/* $total_seguro = $seguridad_social + $seguro_educativo + $seguridad_social2 + $seguro_educativo2 + $riesgos;

			$totales['seguridad_social'] += $seguridad_social;
			$totales['seguro_educativo'] += $seguro_educativo;
			$totales['seguridad_social2'] += $seguridad_social2;
			$totales['seguro_educativo2'] += $seguro_educativo2;
			$totales['riesgos'] += $riesgos;
			$totales['total_seguro'] += $total_seguro;

			$decimo = round($total_bruta * $parametros->decimo / 100, 2);
			$vacaciones = round($total_bruta * $parametros->vacaciones / 100, 2);
			$antiguedad = round($total_bruta * $parametros->antiguedad / 100, 2);
			$total_provisiones = $decimo + $vacaciones + $antiguedad;


			$totales['decimo'] += $decimo;
			$totales['vacaciones'] += $vacaciones;
			$totales['antiguedad'] += $antiguedad;
			$totales['total_provisiones'] += $total_provisiones;

			$total_gastos = $total_bruta + $total_provisiones + $total_seguro - $seguridad_social - $seguro_educativo;
			$totales['total_gastos'] += $total_gastos;
 */
			$output .= '
			<tr>
			<td><div align="center">' . $i . '</div></td>
			<td>
			<div align="left">' . $cedula  . '</div>
			<input id="cedula' . $i . '" name="cedula' . $i . '" type="hidden" value="' . $cedula  . '" />
			</td>
			<td><div align="left">' . $value->nombre1  . '</div></td>
			<td><div align="center">' . $planilla->limite_horas . '</div></td>
			<td ' . (($total_normal > $planilla->limite_horas) ? 'style="background:#FFCCCC;"' : '') . '> <div align="center">' . $this->formato_numero($total_normal) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_extra) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_nocturna) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_festivo) . '</div></td>
			<td><div align="center">' . $planilla->limite_dominicales . '</div></td>

			<td ' . (($total_dominical > $planilla->limite_dominicales) ? 'style="background:#FFCCCC;"' : '') . '> <div align="center">' . $this->formato_numero($total_dominical) . '</div></td>

			</tr>';
		}
		$output .= '
		<tr>
		<td></td>
		<td></td>
		<td><div align="right"><strong>TOTAL</strong></div></td>
		<td></td>
		
		<td><div align="center"><strong>' . $this->formato_numero($totales['normal']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['extra']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['nocturna']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['festivo']) . '</strong></div></td>
		<td></td>

		<td><div align="center"><strong>' . $this->formato_numero($totales['dominical']) . '</strong></div></td>
		</tr>';

		$output .= '</table>';
		$hoy = date('Ym-d h:m:s');
		header('Content-Type: application/xls');
		header('Content-Disposition: attachment; filename=total_nomina' . $hoy . '.xls');
		echo $output;
	}

	public function exportarlimitexlsxAction()
	{
		$this->setLayout('blanco');
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();


		$this->_view->planila = $id = $this->_getSanitizedParam("planilla");
		$empresaModel = new Page_Model_DbTable_Empresas();
		$planillaAsignacionModel = new Page_Model_DbTable_PlanillaAsignacion();
		$parametrosModel = new Page_Model_DbTable_Parametros();
		$planillaHorasModel = new Page_Model_DbTable_PlanillaHoras();
		$this->_view->list_meses = $this->getMeses();
		$list_meses = $this->getMeses();

		$planilla = $this->mainModel->getById($id);
		$empresaId = $planilla->empresa;
		$empresa = $empresaModel->getById($empresaId);
		$cedulas = $planillaAsignacionModel->getListCedulas(" planilla= '$id'", "nombre1 ASC");
		$parametros = $parametrosModel->getById(1);

		$aux = explode("-", $planilla->fecha1);
		$aux2 = explode("-", $planilla->fecha2);
		$dia1 = $aux[2];
		$dia2 = $aux2[2];
		$mes = $aux[1] * 1;
		$anio = $aux[0];
		$title = " Reporte límite de horas <br> planilla de pago del $dia1 al $dia2 de " . $list_meses[$mes] . " del $anio - $empresa->nombre ";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$fecha1 = $planilla->fecha1;
		$fecha2 = $planilla->fecha2;
		$f1 = " AND ( (fecha >= '$fecha1' AND fecha<='$fecha2') OR fecha='0000-00-00' ) ";
		$f2 = " AND (loc!='DESCANSO' AND loc!='VACACIONES' AND loc!='PERMISO' AND loc!='FALTA') ";
		$tabla = '';
		$i = 2;

		$output = '<div align="center" style="font-size:20px">Reporte límite de horas  del ' . $dia1 . ' al ' . $dia2 . ' de ' . $list_meses[$mes] . ' del ' . $anio . ' - ' . $empresa->nombre . ' </div>';




		$title = 'Reporte límite de horas  del ' . $dia1 . ' al ' . $dia2 . ' de ' . $list_meses[$mes] . ' del ' . $anio . ' - ' . $empresa->nombre . ' ';


		// Establecer el título del documento en negrita y centrado
		$sheet->mergeCells('A1:J1'); // Combinar celdas para el título
		$sheet->setCellValue('A1', $title);
		$sheet->getStyle('A1')->getFont()->setBold(true); // Establecer el texto en negrita


		$sheet->setCellValue('A2', 'ITEM');
		$sheet->setCellValue('B2', 'CÉDULA');
		$sheet->setCellValue('C2', 'NOMBRE');
		$sheet->setCellValue('D2', 'LÍMITE HORAS NORMALES');
		$sheet->setCellValue('E2', 'HORAS NORMALES');
		$sheet->setCellValue('F2', 'HORAS EXTRA');
		$sheet->setCellValue('G2', 'HORAS NOCTURNAS');
		$sheet->setCellValue('H2', 'FESTIVOS');
		$sheet->setCellValue('I2', 'LIMITE HORA DOMINICAL');
		$sheet->setCellValue('J2', 'HORAS DOMINICALES');




		$totales = [];

		foreach ($cedulas as $value) {

			$i++;
			$cedula = $value->cedula;
			$valorHora = $value->valor_hora;

			//HORA NORMAL
			$aumento = 1;
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 1 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_extra = $horas->total;
			$total_normal = $horas->total * $valor_hora;
			$totales['normal'] += $total_normal;

			//HORA EXTRA
			$aumento = 1 + ($parametros->horas_extra / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 2 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_extra = $horas->total * $valor_hora;
			$totales['extra'] += $total_extra;

			//HORA NOCTURNA
			$aumento = 1 + ($parametros->horas_nocturnas / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 3 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_nocturna = $horas->total * $valor_hora;
			$totales['nocturna'] += $total_nocturna;


			//HORA FESTIVO
			$aumento = 1 + ($parametros->festivos / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 4 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_festivo = $horas->total * $valor_hora;
			$totales['festivo'] += $total_festivo;


			//HORA DOMINICAL
			$aumento = 1 + ($parametros->horas_dominicales / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 5 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$valor_hora = 1;
			$total_dominical = $horas->total * $valor_hora;
			$total_dominical = $horas->total;
			$totales['dominical'] += $total_dominical;

			$total_bruta = $total_normal + $total_extra + $total_nocturna + $total_festivo + $total_dominical;
			$totales['bruta'] += $total_bruta;

			$seguridad_social = round($total_bruta * $parametros->seguridad_social / 100, 2);
			$seguro_educativo = round($total_bruta * $parametros->seguro_educativo / 100, 2);
			$seguridad_social2 = round($total_bruta * $parametros->seguridad_social2 / 100, 2);
			$seguro_educativo2 = round($total_bruta * $parametros->seguro_educativo2 / 100, 2);


			if ($value->sin_seguridad == 1) {
				$seguridad_social = 0;
				$seguridad_social2 = 0;
				$seguro_educativo = 0;
				$seguro_educativo2 = 0;
			}

			$totales['seguridad_social'] += $seguridad_social;
			$totales['seguro_educativo'] += $seguro_educativo;
			$totales['seguridad_social2'] += $seguridad_social2;
			$totales['seguro_educativo2'] += $seguro_educativo2;

			/* $total_seguro = $seguridad_social + $seguro_educativo + $seguridad_social2 + $seguro_educativo2 + $riesgos;

			$totales['seguridad_social'] += $seguridad_social;
			$totales['seguro_educativo'] += $seguro_educativo;
			$totales['seguridad_social2'] += $seguridad_social2;
			$totales['seguro_educativo2'] += $seguro_educativo2;
			$totales['riesgos'] += $riesgos;
			$totales['total_seguro'] += $total_seguro;

			$decimo = round($total_bruta * $parametros->decimo / 100, 2);
			$vacaciones = round($total_bruta * $parametros->vacaciones / 100, 2);
			$antiguedad = round($total_bruta * $parametros->antiguedad / 100, 2);
			$total_provisiones = $decimo + $vacaciones + $antiguedad;


			$totales['decimo'] += $decimo;
			$totales['vacaciones'] += $vacaciones;
			$totales['antiguedad'] += $antiguedad;
			$totales['total_provisiones'] += $total_provisiones;

			$total_gastos = $total_bruta + $total_provisiones + $total_seguro - $seguridad_social - $seguro_educativo;
			$totales['total_gastos'] += $total_gastos;
 */
			// Formato número para las celdas E a J
			$sheet->getStyle('E' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('J' . $i)->getNumberFormat()->setFormatCode('0.00');


			$sheet->setCellValue('A' . $i, $i - 2);
			$sheet->setCellValue('B' . $i, $cedula);
			$sheet->setCellValue('C' . $i, $value->nombre1);
			$sheet->setCellValue('D' . $i, $planilla->limite_horas);
			$sheet->setCellValue('E' . $i, $this->formato_numero($total_normal));
			$sheet->setCellValue('F' . $i, $this->formato_numero($total_extra));
			$sheet->setCellValue('G' . $i, $this->formato_numero($total_nocturna));
			$sheet->setCellValue('H' . $i, $this->formato_numero($total_festivo));
			$sheet->setCellValue('I' . $i, $planilla->limite_dominicales);
			$sheet->setCellValue('J' . $i, $this->formato_numero($total_dominical));
		}
		$i++;
		$sheet->setCellValue('A' . $i, '');
		$sheet->setCellValue('B' . $i, '');
		$sheet->setCellValue('C' . $i, 'TOTAL');
		$sheet->setCellValue('D' . $i, '');
		$sheet->setCellValue('E' . $i, $this->formato_numero($totales['normal']));
		$sheet->setCellValue('F' . $i,  $this->formato_numero($totales['extra']));
		$sheet->setCellValue('G' . $i, $this->formato_numero($totales['nocturna']));
		$sheet->setCellValue('H' . $i, $this->formato_numero($totales['festivo']));
		$sheet->setCellValue('I' . $i, '');
		$sheet->setCellValue('J' . $i, $this->formato_numero($totales['dominical']));


		// Establecer anchos de columnas automáticamente
		foreach (range('A', 'J') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}

		// Crear el objeto Writer para guardar el archivo en XLSX
		$writer = new Xlsx($spreadsheet);

		// Definir el nombre del archivo
		$filename = 'Informe_limite' . date('Ymd_His') . '.xlsx';

		// Definir el tipo de contenido y el encabezado para la descarga
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		// Enviar el archivo al navegador
		$writer->save('php://output');
	}



	public function horasnormalesAction()
	{
		$this->_view->planila = $id = $this->_getSanitizedParam("planilla");

		$empresaModel = new Page_Model_DbTable_Empresas();
		$planillaAsignacionModel = new Page_Model_DbTable_PlanillaAsignacion();
		$parametrosModel = new Page_Model_DbTable_Parametros();
		$planillaHorasModel = new Page_Model_DbTable_PlanillaHoras();
		$this->_view->list_meses = $this->getMeses();
		$list_meses = $this->getMeses();

		$planilla = $this->mainModel->getById($id);
		$empresaId = $planilla->empresa;
		$empresa = $empresaModel->getById($empresaId);
		$cedulas = $planillaAsignacionModel->getListCedulas(" planilla= '$id'", "nombre1 ASC");
		$parametros = $parametrosModel->getById(1);

		if ($this->_getSanitizedParam("tipo") == 1) { //normal
			$aumento = 1;
		}
		if ($this->_getSanitizedParam("tipo") == 2) { //extra
			$aumento = 1 + ($parametros->horas_extra / 100);
		}
		if ($this->_getSanitizedParam("tipo") == 3) { //nocturna
			$aumento = 1 + ($parametros->horas_nocturnas / 100);
		}
		if ($this->_getSanitizedParam("tipo") == 4) { //festivos
			$aumento = 1 + ($parametros->festivos / 100);
		}
		if ($this->_getSanitizedParam("tipo") == 5) { //dominicales
			$aumento = 1 + ($parametros->horas_dominicales / 100);
		}
		$this->_view->dias = $dias = array("","Lunes","Martes","Miércoles","Jueves","Viernes","Sabado","Domingo");

		$this->_view->dias = $dias = array_map("mb_strtoupper", $dias);

		$aux = explode("-", $planilla->fecha1);
		$aux2 = explode("-", $planilla->fecha2);
		$this->_view->dia1 = $dia1 = $aux[2];
		$this->_view->dia2 = $dia2 = $aux2[2];
		$this->_view->mes = $mes = $aux[1] * 1;
		$this->_view->anio = $anio = $aux[0];
		$title = "Planilla de pago del $dia1 al $dia2 de " . $list_meses[$mes] . " del $anio - $empresa->nombre ";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$fecha1 = $planilla->fecha1;
		$fecha2 = $planilla->fecha2;
		$f1 = " AND ( (fecha >= '$fecha1' AND fecha<='$fecha2') OR fecha='0000-00-00' ) ";
		$f2 = " AND (loc!='DESCANSO' AND loc!='VACACIONES' AND loc!='PERMISO' AND loc!='FALTA') ";
		$tabla = '';
		$i = 0;
		$totales = [];

		foreach ($cedulas as $value) {

			$i++;
			$cedula = $value->cedula;
			$valorHora = $value->valor_hora;

			//HORA NORMAL
			$aumento = 1;
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 1 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_normal = $horas->total * $valor_hora;
			$totales['normal'] += $total_normal;

			//HORA EXTRA
			$aumento = 1 + ($parametros->horas_extra / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 2 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_extra = $horas->total * $valor_hora;
			$totales['extra'] += $total_extra;

			//HORA NOCTURNA
			$aumento = 1 + ($parametros->horas_nocturnas / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 3 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_nocturna = $horas->total * $valor_hora;
			$totales['nocturna'] += $total_nocturna;


			//HORA FESTIVO
			$aumento = 1 + ($parametros->festivos / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 4 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_festivo = $horas->total * $valor_hora;
			$totales['festivo'] += $total_festivo;


			//HORA DOMINICAL
			$aumento = 1 + ($parametros->horas_dominicales / 100);
			$horas = $planillaHorasModel->getSumHorasConsolidado(" planilla = '$id' AND cedula ='$cedula' AND tipo = 5 $f1 $f2", "")[0];

			$valor_hora = round($valorHora * $aumento, 2);
			$total_dominical = $horas->total * $valor_hora;
			$totales['dominical'] += $total_dominical;

			$total_bruta = $total_normal + $total_extra + $total_nocturna + $total_festivo + $total_dominical;
			$totales['bruta'] += $total_bruta;

			$seguridad_social = round($total_bruta * $parametros->seguridad_social / 100, 2);
			$seguro_educativo = round($total_bruta * $parametros->seguro_educativo / 100, 2);
			$seguridad_social2 = round($total_bruta * $parametros->seguridad_social2 / 100, 2);
			$seguro_educativo2 = round($total_bruta * $parametros->seguro_educativo2 / 100, 2);
			$riesgos = round($total_bruta * $parametros->riesgos_profesionales / 100, 2);

			if ($value->sin_seguridad == 1) {
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

			$decimo = round($total_bruta * $parametros->decimo / 100, 2);
			$vacaciones = round($total_bruta * $parametros->vacaciones / 100, 2);
			$antiguedad = round($total_bruta * $parametros->antiguedad / 100, 2);
			$total_provisiones = $decimo + $vacaciones + $antiguedad;


			$totales['decimo'] += $decimo;
			$totales['vacaciones'] += $vacaciones;
			$totales['antiguedad'] += $antiguedad;
			$totales['total_provisiones'] += $total_provisiones;

			$total_gastos = $total_bruta + $total_provisiones + $total_seguro - $seguridad_social - $seguro_educativo;
			$totales['total_gastos'] += $total_gastos;

			$tabla .= '
			<tr>
			<td><div align="center">' . $i . '</div></td>
			<td>
			<div align="left">' . $cedula  . '</div>
			<input id="cedula' . $i . '" name="cedula' . $i . '" type="hidden" value="' . $cedula  . '" />
			</td>
			<td><div align="left">' . $value->nombre1  . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_bruta) . '</div></td>
			<td><div align="center">' . $this->formato_numero($decimo) . '</div></td>
			<td><div align="center">' . $this->formato_numero($vacaciones) . '</div></td>
			<td><div align="center">' . $this->formato_numero($antiguedad) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_provisiones) . '</div></td>
			<td><div align="center">' . $this->formato_numero($seguridad_social) . '</div></td>
			<td><div align="center">' . $this->formato_numero($seguro_educativo) . '</div></td>
			<td><div align="center">' . $this->formato_numero($seguridad_social2) . '</div></td>
			<td><div align="center">' . $this->formato_numero($seguro_educativo2) . '</div></td>
			<td><div align="center">' . $this->formato_numero($riesgos) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_seguro) . '</div></td>
			<td><div align="center">' . $this->formato_numero($total_gastos) . '</div></td>
			</tr>';
		}
		$tabla .= '
		<tr>
		<td></td>
		<td></td>
		<td><div align="right"><strong>TOTAL</strong></div></td>
		
		<td><div align="center"><strong>' . $this->formato_numero($totales['bruta']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['decimo']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['vacaciones']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['antiguedad']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['total_provisiones']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguridad_social']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguro_educativo']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguridad_social2']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['seguro_educativo2']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['riesgos']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['total_seguro']) . '</strong></div></td>
		<td><div align="center"><strong>' . $this->formato_numero($totales['total_gastos']) . '</strong></div></td>
		</tr>';

		$this->_view->tabla = $tabla;
		$this->_view->register_number = count($cedulas);
	}


	public function formato_numero($n)
	{
		return number_format($n, 2, ',', '');
	}

	public function con_cero($mes)
	{
		$mes1 = $mes;
		if ($mes1 < 10) {
			$mes1 = "0" . $mes;
		}
		return $mes1;
	}

	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Planilla.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		$data['fecha1'] = $this->_getSanitizedParam("fecha1");
		$data['fecha2'] = $this->_getSanitizedParam("fecha2");
		if ($this->_getSanitizedParam("empresa") == '') {
			$data['empresa'] = '0';
		} else {
			$data['empresa'] = $this->_getSanitizedParam("empresa");
		}
		if ($this->_getSanitizedParam("cerrada") == '') {
			$data['cerrada'] = '0';
		} else {
			$data['cerrada'] = $this->_getSanitizedParam("cerrada");
		}
		$data['fecha_cerrada'] = $this->_getSanitizedParam("fecha_cerrada");
		if ($this->_getSanitizedParam("limite_horas") == '') {
			$data['limite_horas'] = '0';
		} else {
			$data['limite_horas'] = $this->_getSanitizedParam("limite_horas");
		}
		if ($this->_getSanitizedParam("limite_dominicales") == '') {
			$data['limite_dominicales'] = '0';
		} else {
			$data['limite_dominicales'] = $this->_getSanitizedParam("limite_dominicales");
		}
		return $data;
	}

	/**
	 * Genera los valores del campo Empresa.
	 *
	 * @return array cadena con los valores del campo Empresa.
	 */
	/* 	private function getEmpresa()
	{
		$modelData = new Page_Model_DbTable_Dependempresa();
		$data = $modelData->getList();
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->id] = $value->nombre;
		}
		return $array;
	} */
	private function getMeses()
	{
		$array = array(
			1 => 'Enero',
			2 => 'Febrero',
			3 => 'Marzo',
			4 => 'Abril',
			5 => 'Mayo',
			6 => 'Junio',
			7 => 'Julio',
			8 => 'Agosto',
			9 => 'Septiembre',
			10 => 'Octubre',
			11 => 'Noviembre',
			12 => 'Diciembre'
		);

		return $array;
	}
	private function getQuincenas()
	{
		$array = array(
			1 => '1',
			2 => '2',

		);

		return $array;
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
	protected function getFilter()
	{
		// $filtros = " 1 = 1 ";
		$filtros = " 1 ";

		if (Session::getInstance()->get("kt_login_level") == 2) {
			$empresa = Session::getInstance()->get("kt_login_empresa");
			$filtros = 	$filtros . " AND empresa = '$empresa' ";
		}
		if (Session::getInstance()->get("kt_login_level") == 3) {
			$asignacion = Session::getInstance()->get("kt_login_asignacion");
			$filtros = 	$filtros . " AND FIND_IN_SET(empresa, '$asignacion') ";
		}


		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object)Session::getInstance()->get($this->namefilter);
			if ($filters->meses > 0 && $filters->quincena = '') {
				$fec1 = date("Y-") . $this->con_cero($filters->meses) . "-01";
				$fec2 = date("Y-") .  $this->con_cero($filters->meses) . "-31";
				$filtros = $filtros . "  AND fecha1 >= '$fec1' AND fecha2<='$fec2'";
			}
			if ($filters->meses > 0 && $filters->quincena == 1) {
				$fec1 = date("Y-") . $this->con_cero($filters->meses) . "-01";
				$fec2 = date("Y-") .  $this->con_cero($filters->meses) . "-15";
				$filtros = $filtros . "  AND fecha1 >= '$fec1' AND fecha2<='$fec2'";
			}
			if ($filters->meses > 0 && $filters->quincena == 2) {
				$fec1 = date("Y-") . $this->con_cero($filters->meses) . "-15";
				$fec2 = date("Y-") .  $this->con_cero($filters->meses) . "-31";
				$filtros = $filtros . "  AND fecha1 >= '$fec1' AND fecha2<='$fec2'";
			}
			if ($filters->empresa != '') {
				$filtros = $filtros . " AND empresa = '" . $filters->empresa . "'";
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
			$parramsfilter['quincena'] =  $this->_getSanitizedParam("quincena");
			$parramsfilter['meses'] =  $this->_getSanitizedParam("meses");
			$parramsfilter['empresa'] =  $this->_getSanitizedParam("empresa");

			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}
