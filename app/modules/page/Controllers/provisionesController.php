<?php

/**
 * Controlador de Localizaciones que permite la  creacion, edicion  y eliminacion de los locaci&oacute;n del Sistema
 */
// Import the core class of PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Import the Xlsx writer class
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;

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
			$this->pages = 100;
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

		$this->_view->list_empresa = $this->getEmpresa();

		$filters = (object) Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		/* rint_r($filters); */
		$resultado = $this->getFilter();
		$filtros = $resultado['filtros'];
		$filtros2 = $resultado['filtros2'];
		$this->_view->empresa = $resultado['empresa'];

		if ($filtros == ' 1 ' && $filtros2 == ' 1 ') {
			$this->_view->noContent = 1;
			return;
		}
		$this->_view->amount = $amount = $this->pages;
		$page = $this->_getSanitizedParam("page");
		if ($amount != 'Todos') {
			if (!$page) {
				$page = 1;

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
		if ($amount != 'Todos') {
			$cedulas2 = $planillaHorasModel->getPlanillaHorasProvisiones($filtros, "nombre1 ASC");
			$cedulas = $planillaHorasModel->getPlanillaHorasProvisionesPages($filtros, "nombre1 ASC", $start, $amount);
			$this->_view->totalpages = ceil(count($cedulas2) / $amount);
			$this->_view->register_number = count($cedulas2);
		} else {
			$cedulas = $planillaHorasModel->getPlanillaHorasProvisiones($filtros, "nombre1 ASC");
			$this->_view->register_number = count($cedulas);
		}
		$this->_view->pages = $this->pages;
		$this->_view->page = $page;



		$planillaParametros = $planillaParametrosModel->getById(1);

		$planillas = $planillaModel->getList($filtros2, "");
		/* 	echo '<pre>';
print_r($cedulas);
		echo '</pre>'; */





		$totales = [];
		$decimo = [];
		$vacaciones = [];
		$antiguedad = [];
		$total_provisiones = [];

		foreach ($planillas as $value) {


			$planilla = $value->id;
			$fecha1 = $value->fecha1;
			$fecha2 = $value->fecha2;

			$total_normal = [];
			$total_extra = [];
			$total_nocturna = [];
			$total_festivo = [];
			$total_dominical = [];
			$total_bruta = [];


			foreach ($cedulas as $i => $empleado) {




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

				/* --------------------------------------------
					PROVISION
					-------------------------------------------- */

				$decimo[$cedula] += round($total_bruta[$cedula] * $planillaParametros->decimo / 100, 2);
				$vacaciones[$cedula] += round($total_bruta[$cedula] * $planillaParametros->vacaciones / 100, 2);
				$antiguedad[$cedula] += round($total_bruta[$cedula] * $planillaParametros->antiguedad / 100, 2);
				$total_provisiones[$cedula] = ($decimo[$cedula] + $vacaciones[$cedula] + $antiguedad[$cedula]);

				if($empleado->tipo_contrato != '1'){
					$antiguedad[$cedula] = 0;
				}

				$totales['decimo'] += $decimo[$cedula];
				$totales['vacaciones'] += $vacaciones[$cedula];
				$totales['antiguedad'] += $antiguedad[$cedula];
				$totales['total_provisiones'] += $total_provisiones[$cedula];
			}


			$this->_view->cedulas = $cedulas;
			$this->_view->decimo = $decimo;
			$this->_view->vacaciones = $vacaciones;
			$this->_view->antiguedad = $antiguedad;
			$this->_view->total_provisiones = $total_provisiones;
		}
	}












	public function exportarAction()
	{
		$this->setLayout('blanco');
		header("Content-Type: text/html;charset=utf-8");
		$output = '';


		$this->filters();

		$list_empresa = $this->getEmpresa();

		$filters = (object) Session::getInstance()->get($this->namefilter);

		$resultado = $this->getFilter();
		$filtros = $resultado['filtros'];
		$filtros2 = $resultado['filtros2'];
		$empresa = $resultado['empresa'];
		$fecha_inicio = $resultado['fecha_inicio'];
		$fecha_fin = $resultado['fecha_fin'];


		$planillaHorasModel = new Page_Model_DbTable_Planillahoras();
		$planillaParametrosModel = new Page_Model_DbTable_Parametros();
		$planillaModel = new Page_Model_DbTable_Planilla();
		$planillaAsignacionModel = new Page_Model_DbTable_Planillaasignacion();
		$cedulas = $planillaHorasModel->getPlanillaHorasProvisiones($filtros, "nombre1 ASC");
		$planillaParametros = $planillaParametrosModel->getById(1);
		$planillas = $planillaModel->getList($filtros2, "");


		if ($empresa == '') {
			$output = '<div align="center" style="font-size:18px;color:#0158A8;font-weight:700;">Informe de provisiones</div>';
		} else {
			$output = '<div align="center" style="font-size:18px;color:#0158A8;font-weight:700;">Informe de provisiones de la empresa ' . $list_empresa[$empresa] . '</div>';
		}
		$output .= '<div align="center">Desde: <strong>' . $fecha_inicio . '</strong> - Hasta: <strong>' . $fecha_fin . '</strong></div>';

		$output .= '<table border="1" cellpadding="3" cellspacing="0" width="100%">';
		$output .= '
	<tr>
	<td>Item</td>
	<td>Documento</td>
	<td>Nombre</td>
	<td>D&Eacute;CIMO</td>
	<td>VACACIONES</td>
	<td>P. ANTIGUEDAD</td>
	<td>TOTAL PROVISIONES</td>
	</tr>';


		$totales = [];
		$decimo = [];
		$vacaciones = [];
		$antiguedad = [];
		$total_provisiones = [];
		$i = 0;
		$totales_decimo = 0;
		$totales_antiguedad = 0;
		$totales_vacaciones = 0;
		$totales_provisiones = 0;
		foreach ($planillas as $value) {


			$planilla = $value->id;
			$fecha1 = $value->fecha1;
			$fecha2 = $value->fecha2;

			$total_normal = [];
			$total_extra = [];
			$total_nocturna = [];
			$total_festivo = [];
			$total_dominical = [];
			$total_bruta = [];


			foreach ($cedulas as $i => $empleado) {

				$i++;


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

				/* --------------------------------------------
					PROVISION
					-------------------------------------------- */

				$decimo[$cedula] += round($total_bruta[$cedula] * $planillaParametros->decimo / 100, 2);
				$vacaciones[$cedula] += round($total_bruta[$cedula] * $planillaParametros->vacaciones / 100, 2);
				$antiguedad[$cedula] += round($total_bruta[$cedula] * $planillaParametros->antiguedad / 100, 2);
				$total_provisiones[$cedula] = ($decimo[$cedula] + $vacaciones[$cedula] + $antiguedad[$cedula]);
			}
		}
		$key = 1;

		foreach ($cedulas as $key => $content) {

			$key++;
			$output .= '
				<tr>
				<td>' . $key . '</td>
				<td>' . $content->cedula . '</td>
				<td>' . $content->nombre1 . '</td>
				<td>' . $decimo[$content->cedula] . '</td>';
			$totales_decimo += $decimo[$content->cedula];
			$output .= '<td>' . $vacaciones[$content->cedula] . '</td>';
			$totales_vacaciones += $vacaciones[$content->cedula];
			$output .= '<td>' . $antiguedad[$content->cedula] . '</td>';
			$totales_antiguedad += $antiguedad[$content->cedula];
			$output .= '<td>' . $total_provisiones[$content->cedula] . '</td>';
			$totales_provisiones += $total_provisiones[$content->cedula];
			$output .= '</tr>';
		}
		$output .= '
		<tr>
		<td></td>
		<td></td>
		<td style=text-align:right"><strong>TOTAL</strong></td>
		<td><strong>' . $totales_decimo . '</strong></td>
		<td><strong>' . $totales_vacaciones . '</strong></td>
		<td><strong>' . $totales_antiguedad . '</strong></td>
		<td><strong>' . $totales_provisiones . '</strong></td>
		</tr>
		';

		$output .= '</table>';
		$hoy = date('Ym-d h:m:s');
		header('Content-Type: application/xls');
		header('Content-Disposition: attachment; filename=Informe_de_provisiones' . $hoy . '.xls');
		echo $output;
	}
	public function exportarxlsxAction()
	{
		// Crear un nuevo libro de Excel
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// Establecer el título y la fecha
		$list_empresa = $this->getEmpresa();
		$filters = (object) Session::getInstance()->get($this->namefilter);
		$resultado = $this->getFilter();
		$empresa = $resultado['empresa'];
		$fecha_inicio = $resultado['fecha_inicio'];
		$fecha_fin = $resultado['fecha_fin'];
		$filtros = $resultado['filtros'];
		$filtros2 = $resultado['filtros2'];
		if ($empresa == '') {
			$title = 'Informe de provisiones';
		} else {
			$title = 'Informe de provisiones de la empresa ' . $list_empresa[$empresa];
		}

		// Establecer el título del documento en negrita y centrado
		$sheet->mergeCells('A1:G1'); // Combinar celdas para el título
		$sheet->setCellValue('A1', $title);
		$sheet->getStyle('A1')->getFont()->setBold(true); // Establecer el texto en negrita

		// Establecer la fecha en la siguiente fila y centrado
		$sheet->mergeCells('A2:G2'); // Combinar celdas para la fecha
		$sheet->setCellValue('A2', 'Desde: ' . $fecha_inicio . ' - Hasta: ' . $fecha_fin);
		$sheet->getStyle('A2')->getFont()->setBold(true); // Establecer el texto en negrita



		// Obtener los datos para exportar
		$this->filters();
		$planillaHorasModel = new Page_Model_DbTable_Planillahoras();
		$planillaParametrosModel = new Page_Model_DbTable_Parametros();
		$planillaModel = new Page_Model_DbTable_Planilla();
		$planillaAsignacionModel = new Page_Model_DbTable_Planillaasignacion();
		$cedulas = $planillaHorasModel->getPlanillaHorasProvisiones($filtros, "nombre1 ASC");
		$planillaParametros = $planillaParametrosModel->getById(1);
		$planillas = $planillaModel->getList($filtros2, "");

		// Cabecera de la tabla
		$sheet->setCellValue('A5', 'Item');
		$sheet->setCellValue('B5', 'Documento');
		$sheet->setCellValue('C5', 'Nombre');
		$sheet->setCellValue('D5', 'DÉCIMO');
		$sheet->setCellValue('E5', 'VACACIONES');
		$sheet->setCellValue('F5', 'P. ANTIGUEDAD');
		$sheet->setCellValue('G5', 'TOTAL PROVISIONES');


		$totales = [];
		$decimo = [];
		$vacaciones = [];
		$antiguedad = [];
		$total_provisiones = [];
		$i = 0;
		$totales_decimo = 0;
		$totales_antiguedad = 0;
		$totales_vacaciones = 0;
		$totales_provisiones = 0;
		foreach ($planillas as $value) {


			$planilla = $value->id;
			$fecha1 = $value->fecha1;
			$fecha2 = $value->fecha2;

			$total_normal = [];
			$total_extra = [];
			$total_nocturna = [];
			$total_festivo = [];
			$total_dominical = [];
			$total_bruta = [];


			foreach ($cedulas as $i => $empleado) {

				$i++;


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


				$decimo[$cedula] += round($total_bruta[$cedula] * $planillaParametros->decimo / 100, 2);
				$vacaciones[$cedula] += round($total_bruta[$cedula] * $planillaParametros->vacaciones / 100, 2);
				$antiguedad[$cedula] += round($total_bruta[$cedula] * $planillaParametros->antiguedad / 100, 2);
				$total_provisiones[$cedula] = ($decimo[$cedula] + $vacaciones[$cedula] + $antiguedad[$cedula]);
			}
		}
		$key = 3;
		foreach ($cedulas as $content) {
			$key++;
			$sheet->setCellValue('A' . $key, $key - 3);
			$sheet->setCellValue('B' . $key, $content->cedula);
			$sheet->setCellValue('C' . $key, $content->nombre1);
			$sheet->setCellValue('D' . $key, $decimo[$content->cedula]);
			$sheet->setCellValue('E' . $key, $vacaciones[$content->cedula]);
			$sheet->setCellValue('F' . $key, $antiguedad[$content->cedula]);
			$sheet->setCellValue('G' . $key, $total_provisiones[$content->cedula]);
			$totales_decimo += $decimo[$content->cedula];
			$totales_vacaciones += $vacaciones[$content->cedula];
			$totales_antiguedad += $antiguedad[$content->cedula];
			$totales_provisiones += $total_provisiones[$content->cedula];
		}
		// Agregar la fila de totales
		$key++;
		$sheet->setCellValue('A' . $key, '');
		$sheet->setCellValue('B' . $key, '');
		$sheet->setCellValue('C' . $key, 'TOTAL');
		$sheet->setCellValue('D' . $key, $totales_decimo);
		$sheet->setCellValue('E' . $key, $totales_vacaciones);
		$sheet->setCellValue('F' . $key, $totales_antiguedad);
		$sheet->setCellValue('G' . $key, $totales_provisiones);



	    // Establecer anchos de columnas automáticamente
		foreach (range('A', 'G') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}

		// Crear el objeto Writer para guardar el archivo en XLSX
		$writer = new Xlsx($spreadsheet);

		// Definir el nombre del archivo
		$filename = 'Informe_de_provisiones' . date('Ymd_His') . '.xlsx';

		// Definir el tipo de contenido y el encabezado para la descarga
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		// Enviar el archivo al navegador
		$writer->save('php://output');
		/* 	$hoy = date('Ym-d h:m:s');
		header('Content-Type: application/xlsx');
		header('Content-Disposition: attachment; filename=Informe_de_provisiones' . $hoy . '.xlsx');
		echo $output; */
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
		}/*  else if (Session::getInstance()->get($this->namefilter) == "" || !(Session::getInstance()->get($this->namefilter))) {
			$filters = (object) Session::getInstance()->get($this->namefilter);

			if ($filters->fecha_inicio == '' && $filters->fecha_fin == '') {
				$currentDate = date('Y-m-d'); // Obtener la fecha actual en formato Y-m-d

				if (date('d') <= 15) {

					// Si estamos antes o en el día 15 del mes actual
					$this->_view->fecha_inicio = $filters->fecha_inicio  = date('Y-m-15', strtotime('previous month')); // Fecha del día 15 del mes anterior
					$this->_view->fecha_fin = $filters->fecha_fin = date('Y-m-t', strtotime('previous month')); // Fecha del último día del mes anterior
					/
				} else {


					// Si estamos después del día 15 del mes actual
					$this->_view->fecha_inicio = 	$filters->fecha_inicio = date('Y-m-01'); // Fecha del primer día del mes actual
					$this->_view->fecha_fin =	$filters->fecha_fin = date('Y-m-15'); // Fecha del día 15 del mes actual



				}
				$filtros = $filtros . " AND  planilla_horas.fecha >= '" . $filters->fecha_inicio . "' AND planilla_horas.fecha<='" . $filters->fecha_fin . "' ";

				$filtros2 = $filtros2 . " AND fecha1>='" . $filters->fecha_inicio . "' AND fecha1 <='" . $filters->fecha_fin . "' AND fecha2>='" . $filters->fecha_inicio . "' AND fecha2 <='" . $filters->fecha_fin . "'  ";
			}
		} */
		return array('filtros' => $filtros, 'filtros2' => $filtros2, 'empresa' => $filters->empresa, 'fecha_inicio' => $filters->fecha_inicio, 'fecha_fin' => $filters->fecha_fin);
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
