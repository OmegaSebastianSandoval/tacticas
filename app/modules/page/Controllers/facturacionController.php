<?php

/**
 * Controlador de Localizaciones que permite la  creacion, edicion  y eliminacion de los locaci&oacute;n del Sistema
 */
class Page_facturacionController extends Page_mainController
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
	protected $_csrf_section = "page_facturacion";

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
		$this->namefilter = "parametersfilterfacturacion";
		$this->route = "/page/facturacion";
		$this->namepages = "pages_facturacion";
		$this->namepageactual = "page_actual_facturacion";
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

		$title = "Informe de facturación";
		$this->getLayout()->setTitle($title);
		(object) Session::getInstance()->set($this->namefilter, '');


		$this->_view->titlesection = $title;
		$this->_view->list_empresa = $this->getEmpresa();
		$this->_view->list_tipo = $this->getTipo();
		$this->_view->list_localizacion = $this->getLocalizacion();
		$this->filters();
		$filters = (object) Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		/* 		print_r($filters);
		echo "|||||||||||||||||||||||||"; */
		$resultado = $this->getFilter();
		$filtros = $resultado['filtros'];
		$filtros2 = $resultado['filtros2'];
		$this->_view->fecha_inicio = $fecha_inicio = $resultado['fecha_inicio'];
		$this->_view->fecha_fin = $fecha_fin = $resultado['fecha_fin'];

		$localizacion = $resultado['localizacion'];
		$empresa = $resultado['empresa'];

		//echo $filtros;
		//echo "ok";
		//echo $filtros2;
		if ($filtros == '1' && $filtros2 == '1') {
			$this->_view->noContent = 1;
			return;
		}

		$planillaHorasModel = new Page_Model_DbTable_Planillahoras();
		$planillaParametrosModel = new Page_Model_DbTable_Parametros();
		$planillaModel = new Page_Model_DbTable_Planilla();
		$planillaAsignacionModel = new Page_Model_DbTable_Planillaasignacion();
		$planillaTotalesModel = new Page_Model_DbTable_Planillatotales();

		$cedulas = $planillaHorasModel->getPlanillaHorasFacturacion($filtros, "nombre1 ASC");


		//echo $fecha_inicio;
		//echo "ok";

		//echo $fecha_fin;
		//echo '<pre>';

		//print_r($resultado);
		//	print_r($empresa);

	//	echo '</pre>';
		$filtro_loc = "";
		if ($localizacion != '') {
			$filtro_loc = " AND (loc = '$localizacion' OR loc = 'DESCANSO' AND loc = 'VACACIONES' AND loc = 'INCAPACIDAD' AND loc='FALTA' AND loc='PERMISO' )  ";
		}
		$total1 = 0;

		$k = 0;
		$existe = 0;
		$tabla = '';
		$tabla2 = '';

		foreach ($cedulas as $empleado) {
			$planillas = $planillaModel->getList("fecha1 >= '$fecha_inicio' AND fecha2 <= '$fecha_fin'", "");
			$fecha = "0000-00-00";
			$cedula = $empleado->cedula;
			$tipo = $resultado['tipo'];
			$horas_pendientes = [];
			$cantidades = [];
			$incapacidades = [];
			// Calcular horas pendientes
			foreach ($planillas as $planilla) {

				$planillaId = $planilla->id;
				$planillaHorasPendientes = $planillaHorasModel->getList("planilla = '$planillaId'  AND fecha = '$fecha' AND cedula = '$cedula' AND tipo = '$tipo' $filtro_loc ", "")[0];
				$horas_pendientes[$cedula][$tipo] += $planillaHorasPendientes->horas * 1;
				/* 	echo '<pre>';
				print_r($planillaId);
				echo '</pre>'; */
			}

			// Verificar si existe
			$existe = 0;
			for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
				$tipo = 1;
				$fecha = $j;
				$row_rsHoras = $planillaHorasModel->getList("fecha = '$fecha' AND cedula = '$cedula' AND tipo = '$tipo' $filtro_loc ", "")[0];
				$horasTotal = $row_rsHoras->horas * 1;

				if ($row_rsHoras->loc != "DESCANSO" && $row_rsHoras->loc != "VACACIONES" && $row_rsHoras->loc != "PERMISO" && $row_rsHoras->loc != "FALTA") {
					if ($horasTotal > 0) {
						$existe = 1;
					}
				}
			}

			if ($horas_pendientes[$cedula][$tipo] > 0) {
				$existe = 1;
			}


			if ($existe == 1) {
				if ($resultado['tipo'] == 1) {
					$k++;
				}

				if ($resultado['tipo'] == 1) {


					// $k++;

					$tabla .= '
						<tr id="fila1_' . $k . '">
							<td  width="200">' . $k . '</td>
							<td width="200">' . $cedula . '</td>
							<td width="200">' . $empleado->nombre1 . '</td>
							<td><div align="center">' . $this->evaluar($horas_pendientes[$cedula][$tipo]) . '</div></td>
					';

					for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
						$tipo = 1; // Extra
						$fecha = $j;
						$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo = '$tipo' $filtro_loc ", "");
						$horas1 = 0;

						foreach ($row_rsHoras as $hora) {
							$horas1 += $hora->horas;
						}

						$horas1 = $horas1 * 1;

						if ($row_rsHoras->loc != "DESCANSO" && $row_rsHoras->loc != "VACACIONES" && $row_rsHoras->loc != "PERMISO" && $row_rsHoras->loc != "FALTA") {
							$cantidades['normal'] += $horas1;
						}

						if ($row_rsHoras->loc == "DESCANSO") {
							$horas1 = "D";
						} elseif ($row_rsHoras->loc == "VACACIONES") {
							$horas1 = "V";
						} elseif ($row_rsHoras->loc == "PERMISO") {
							$horas1 = "P";
						} elseif ($row_rsHoras->loc == "FALTA") {
							$horas1 = "F";
						}

						if ($row_rsHoras->loc == "INCAPACIDAD") {
							$incapacidades['normal'] += $horas1;
							$horas1 = "I";
						}

						$incapacidades['normal'] = $incapacidades['normal'] * 1;

						$tabla .= '<td><div align="center">' . $this->evaluar($horas1) . '</div></td>';
					}

					$tabla .= '
						<td><div align="center">' . $this->evaluar($incapacidades['normal']) . '</div></td>
						';

					$cantidades['normal'] += $horas_pendientes[$cedula][$tipo];

					$tabla .= '
						<td><div align="center">' . $this->evaluar($cantidades['normal']);
					$total1 += $cantidades['normal'] . '</div></td>
						</tr>
					';
				}
			}

			//VERIFICAR SI EXISTE  
			$existe = 0;
			for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {

				$tipo = 2;
				$fecha = $j;
				$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "")[0];

				$horasTotal = $row_rsHoras->horas  * 1;
				//$row_rsHoras->horas = $row_rsHoras->horas * 1;
				if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
					if ($horasTotal > 0) {
						$existe = 1;
					}
				}
			}
			if ($horas_pendientes[$cedula][$tipo] > 0) {
				$existe = 1;
			}
			// $k = 0;
			if ($existe == 1) {
				if ($resultado['tipo'] == 2) {
					$k++;
				}

				if ($resultado['tipo'] == 2) {
					// echo 'tipo 2';

					// $k++;
					$tabla .= '
					<tr id="fila2_' . $k . '">
					<td> ' . $k  . '</td>
					<td> ' . $cedula . '</td>
					<td> ' .  $empleado->nombre1 . '</td>
					<td>
					<div align="center">' .  $this->evaluar($horas_pendientes[$cedula][$tipo]) . '</div>
					</td>';
					for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
						$tipo = 2; //extra
						$fecha = $j;
						$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "");
						$horas1 = 0;
						foreach ($row_rsHoras as $hora) {
							$horas1 += $hora->horas;
						}
						//  	$row_rsHoras->horas = $horas1;


						// $row_rsHoras->horas = $row_rsHoras->horas * 1;
						$horas1 = $horas1 * 1;


						if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
							$cantidades['extra'] += $horas1;
						}

						if ($row_rsHoras->loc == "DESCANSO") {
							$horas1 = "D";
						}
						if ($row_rsHoras->loc == "VACACIONES") {
							$horas1 = "V";
						}
						if ($row_rsHoras->loc == "PERMISO") {
							$horas1 = "P";
						}
						if ($row_rsHoras->loc == "FALTA") {
							$horas1 = "F";
						}

						if ($row_rsHoras->loc == "INCAPACIDAD") {
							$incapacidades['extra'] += $horas1;
							$horas1 = "I";
						}
						$incapacidades['extra'] = $incapacidades['extra'] * 1;
						$tabla .= '
						<td>
						<div align="center">' .  $this->evaluar($horas1) . '</div>
						</td> ';
					}
					$tabla .= '
						<td>
						<div align="center">' . $this->evaluar($incapacidades['extra']) . ' </div>
						</td>';

					$cantidades['extra'] += $horas_pendientes[$cedula][$tipo];

					$tabla .= '
						<td>
						<div align="center"> ' . $this->evaluar($cantidades['extra']);
					$total1 += $cantidades['extra'] . ' </div>
						</td>
						</tr>';


					/* print_r($tabla);
					echo ($tabla); */
				}
			}




			//VERIFICAR SI EXISTE  
			$existe = 0;
			for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {

				$tipo = 3;
				$fecha = $j;
				$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "")[0];

				$horasTotal = $row_rsHoras->horas  * 1;
				//$row_rsHoras->horas = $row_rsHoras->horas * 1;
				if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
					if ($horasTotal > 0) {
						$existe = 1;
					}
				}
			}
			if ($horas_pendientes[$cedula][$tipo] > 0) {
				$existe = 1;
			}
			// $k = 0;
			if ($existe == 1) {
				if ($resultado['tipo'] == 3) {
					$k++;
				}
				if ($resultado['tipo'] == 3) {
					// $k++;
					$tabla .= '
					<tr id="fila3_' . $k . '">
					<td > ' . $k  . '</td>
					<td > ' . $cedula . '</td>
					<td > ' .  $empleado->nombre1 . '</td>
					<td>
					<div align="center">' .  $this->evaluar($horas_pendientes[$cedula][$tipo]) . '</div>
					</td>';
					for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
						$tipo = 3; //extra
						$fecha = $j;
						$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "");
						$horas1 = 0;
						foreach ($row_rsHoras as $hora) {
							$horas1 += $hora->horas;
						}



						$horas1 = $horas1 * 1;

						if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
							$cantidades['nocturna'] += $horas1;
						}

						if ($row_rsHoras->loc == "DESCANSO") {
							$horas1 = "D";
						}
						if ($row_rsHoras->loc == "VACACIONES") {
							$horas1 = "V";
						}
						if ($row_rsHoras->loc == "PERMISO") {
							$horas1 = "P";
						}
						if ($row_rsHoras->loc == "FALTA") {
							$horas1 = "F";
						}

						if ($row_rsHoras->loc == "INCAPACIDAD") {
							$incapacidades['nocturna'] += $horas1;
							$horas1 = "I";
						}
						$incapacidades['nocturna'] = $incapacidades['nocturna'] * 1;
						$tabla .= '
						<td>
						<div align="center">' .  $this->evaluar($horas1) . '</div>
						</td> ';
					}
					$tabla .= '
						<td>
						<div align="center">' . $this->evaluar($incapacidades['nocturna']) . ' </div>
						</td>';

					$cantidades['nocturna'] += $horas_pendientes[$cedula][$tipo];

					$tabla .= '
						<td>
						<div align="center"> ' . $this->evaluar($cantidades['nocturna']);
					$total1 += $cantidades['nocturna'] . ' </div>
						</td>
						</tr>';
				}
			}
			//VERIFICAR SI EXISTE  
			$existe = 0;
			for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {

				$tipo = 4;
				$fecha = $j;
				$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "")[0];

				$horasTotal = $row_rsHoras->horas  * 1;
				//$row_rsHoras->horas = $row_rsHoras->horas * 1;
				if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
					if ($horasTotal > 0) {
						$existe = 1;
					}
				}
			}
			if ($horas_pendientes[$cedula][$tipo] > 0) {
				$existe = 1;
			}
			// $k = 0;
			if ($existe == 1) {
				if ($resultado['tipo'] == 4) {
					$k++;
				}
				if ($resultado['tipo']  == 4) {
					// $k++;
					$tabla .= '
					<tr id="fila4_' . $k . '">
					<td > ' . $k  . '</td>
					<td > ' . $cedula . '</td>
					<td > ' .  $empleado->nombre1 . '</td>
					<td>
					<div align="center">' .  $this->evaluar($horas_pendientes[$cedula][$tipo]) . '</div>
					</td>';
					for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
						$tipo = 4; //extra
						$fecha = $j;
						$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "");
						$horas1 = 0;
						foreach ($row_rsHoras as $hora) {
							$horas1 += $hora->horas;
						}
						// $row_rsHoras->horas = $horas1;


						$horas1 = $horas1 * 1;

						if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
							$cantidades['festivo'] += $horas1;
						}

						if ($row_rsHoras->loc == "DESCANSO") {
							$horas1 = "D";
						}
						if ($row_rsHoras->loc == "VACACIONES") {
							$horas1 = "V";
						}
						if ($row_rsHoras->loc == "PERMISO") {
							$horas1 = "P";
						}
						if ($row_rsHoras->loc == "FALTA") {
							$horas1 = "F";
						}

						if ($row_rsHoras->loc == "INCAPACIDAD") {
							$incapacidades['festivo'] += $horas1;
							$horas1 = "I";
						}
						$incapacidades['festivo'] = $incapacidades['festivo'] * 1;
						$tabla .= '
						<td>
						<div align="center">' .  $this->evaluar($horas1) . '</div>
						</td> ';
					}
					$tabla .= '
						<td>
						<div align="center">' . $this->evaluar($incapacidades['festivo']) . ' </div>
						</td>';

					$cantidades['festivo'] += $horas_pendientes[$cedula][$tipo];

					$tabla .= '
						<td>
						<div align="center"> ' . $this->evaluar($cantidades['festivo']);
					$total1 += $cantidades['festivo'] . ' </div>
						</td>
						</tr>';
				}
			}
			//VERIFICAR SI EXISTE  
			$existe = 0;
			for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {

				$tipo = 5;
				$fecha = $j;
				$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "")[0];
				$horasTotal = $row_rsHoras->horas  * 1;
				//$row_rsHoras->horas = $row_rsHoras->horas * 1;

				if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
					if ($horasTotal > 0) {
						$existe = 1;
					}
				}
			}
			if ($horas_pendientes[$cedula][$tipo] > 0) {
				$existe = 1;
			}
			// $k = 0;
			if ($existe == 1) {
				if ($resultado['tipo'] == 5) {
					$k++;
				}
				if ($resultado['tipo'] == 5) {
					// $k++;
					$tabla .= '
					<tr id="fila5_' . $k . '">
					<td > ' . $k  . '</td>
					<td > ' . $cedula . '</td>
					<td > ' .  $empleado->nombre1 . '</td>
					<td>
					<div align="center">' .  $this->evaluar($horas_pendientes[$cedula][$tipo]) . '</div>
					</td>';
					for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
						$tipo = 5; //extra
						$fecha = $j;
						$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "");
						$horas1 = 0;
						foreach ($row_rsHoras as $hora) {
							$horas1 += $hora->horas;
						}
						// $row_rsHoras->horas = $horas1;


						$horas1 = $horas1 * 1;

						if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
							$cantidades['dominical'] += $horas1;
						}

						if ($row_rsHoras->loc == "DESCANSO") {
							$horas1 = "D";
						}
						if ($row_rsHoras->loc == "VACACIONES") {
							$horas1 = "V";
						}
						if ($row_rsHoras->loc == "PERMISO") {
							$horas1 = "P";
						}
						if ($row_rsHoras->loc == "FALTA") {
							$horas1 = "F";
						}

						if ($row_rsHoras->loc == "INCAPACIDAD") {
							$incapacidades['dominical'] += $horas1;
							$horas1 = "I";
						}
						$incapacidades['dominical'] = $incapacidades['dominical'] * 1;
						$tabla .= '
						<td>
						<div align="center">' .  $this->evaluar($horas1) . '</div>
						</td> ';
					}
					$tabla .= '
						<td>
						<div align="center">' . $this->evaluar($incapacidades['dominical']) . ' </div>
						</td>';

					$cantidades['dominical'] += $horas_pendientes[$cedula][$tipo];

					$tabla .= '
						<td>
						<div align="center"> ' . $this->evaluar($cantidades['dominical']);
					$total1 += $cantidades['dominical'] . ' </div>
						</td>
						</tr>';
				}
			}
			$this->_view->tabla = $tabla;

			$cantidades = [];
			$incapacidades = [];
		}


		$tabla2  .= '
		 <tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			';
		for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
			$tabla2  .= '
			<td></td>

			';
		}
		$tabla2  .= '
			<td></td>
			<td align="right">
		    <div align="center"><strong>' . $total1 . '</strong></div>
		  </td>
		  </tr>
			';
		$this->_view->tabla2 = $tabla2;
		$this->_view->register_number = $k;
	}

















	public function exportarAction()
	{
		$this->setLayout('blanco');
		header('Content-Type: text/html; charset=utf-8');
		$list_empresa = $this->getEmpresa();
		$list_tipo = $this->getTipo();

		$this->filters();
		$filters = (object) Session::getInstance()->get($this->namefilter);


		$resultado = $this->getFilter();
		$filtros = $resultado['filtros'];
		$filtros2 = $resultado['filtros2'];
		$fecha_inicio = $resultado['fecha_inicio'];
		$fecha_fin = $resultado['fecha_fin'];
		$empresa = $resultado['empresa'];
		$localizacion = $resultado['localizacion'];
		$tipo = $resultado['tipo'];

		if ($filtros == '1' && $filtros2 == '1') {
			return;
		}

		$planillaHorasModel = new Page_Model_DbTable_Planillahoras();

		$planillaModel = new Page_Model_DbTable_Planilla();


		$cedulas = $planillaHorasModel->getPlanillaHorasFacturacion($filtros, "nombre1 ASC");
		$output = '';


		if ($empresa == "") {
			$output = '<div align="center" style="font-size:17px;color:#0158A8;font-weight:700;">Informe de facturaci&oacute;n</div>';
		} else {
			$output = '<div align="center" style="font-size:17px;color:#0158A8;font-weight:700;">Informe de facturaci&oacute;n de la empresa <strong>' . $list_empresa[$empresa] . '</strong></div>';
		}
		if ($localizacion != "") {
			$output .= '<div align="center" style="font-size:15px">Localizaci&oacute;n: <strong>' . $localizacion . '</strong></div>';
		}
		$output .= '<div align="center" style="font-size:15px"><strong>' . $list_tipo[$tipo] . '</strong></div>';
		if ($fecha_inicio == "" && $fecha_fin == "") {
			$output .= '<div align="center">Desde: ' . $fecha_inicio . ' - Hasta: ' . $fecha_fin . '</div>';
		} else {
			$output .= '<div align="center">Desde: ' . $fecha_inicio . ' - Hasta: ' . $fecha_fin . '</div>';
		}
		$output .= '<table width="100%" border="1" cellpadding="3" cellspacing="0">';
		$output .= '
		<tr>
		<th rowspan="2">Item</th>
		<th rowspan="2">Cliente</th>
		<th rowspan="2">Salario neto</th>
		<th>PEND.</th>';
		for ($j = $fecha_inicio; $j <= $fecha_fin; $j =  $this->sumar_dias($j, 1)) {
			$output .= '  <th><div align="center">' . $j . '</div></th>';
		}
		$output .= '
		<td rowspan="2"><div align="center">INC.</div></td>
		<td rowspan="2"><div align="center">TOT</div></td>';
		$output .= '</tr>';
		$output .= '
		<tr>
		<th></th>';
		for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
			$output .= '  <th><div align="center">' . $this->dia_semana($j) . '</div></th>';
		}
		$output .= '</tr>';

		$filtro_loc = "";
		if ($localizacion != '') {
			$filtro_loc = " AND (loc = '$localizacion' OR loc = 'DESCANSO' AND loc = 'VACACIONES' AND loc = 'INCAPACIDAD' AND loc='FALTA' AND loc='PERMISO' )  ";
		}
		$total1 = 0;

		$k = 0;
		$existe = 0;
		$tabla = '';
		$tabla2 = '';

		foreach ($cedulas as $empleado) {
			$planillas = $planillaModel->getList("fecha1 >= '$fecha_inicio' AND fecha2 <= '$fecha_fin'", "");
			$fecha = "0000-00-00";
			$cedula = $empleado->cedula;
			$tipo = $resultado['tipo'];
			$horas_pendientes = [];
			$cantidades = [];
			$incapacidades = [];
			// Calcular horas pendientes
			foreach ($planillas as $planilla) {

				$planillaId = $planilla->id;
				$planillaHorasPendientes = $planillaHorasModel->getList("planilla = '$planillaId'  AND fecha = '$fecha' AND cedula = '$cedula' AND tipo = '$tipo' $filtro_loc ", "")[0];
				$horas_pendientes[$cedula][$tipo] += $planillaHorasPendientes->horas * 1;
			}

			// Verificar si existe
			$existe = 0;
			for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
				$tipo = 1;
				$fecha = $j;
				$row_rsHoras = $planillaHorasModel->getList("fecha = '$fecha' AND cedula = '$cedula' AND tipo = '$tipo' $filtro_loc ", "")[0];
				$horasTotal = $row_rsHoras->horas * 1;

				if ($row_rsHoras->loc != "DESCANSO" && $row_rsHoras->loc != "VACACIONES" && $row_rsHoras->loc != "PERMISO" && $row_rsHoras->loc != "FALTA") {
					if ($horasTotal > 0) {
						$existe = 1;
					}
				}
			}

			if ($horas_pendientes[$cedula][$tipo] > 0) {
				$existe = 1;
			}


			if ($existe == 1) {


				if ($resultado['tipo'] == 1) {

					$k++;
					// $k++;

					$output .= '
						<tr id="fila1_' . $k . '">
							<td  width="200">' . $k . '</td>
							<td width="200">' . $cedula . '</td>
							<td width="200">' . $empleado->nombre1 . '</td>
							<td><div align="center">' . $this->evaluar($horas_pendientes[$cedula][$tipo]) . '</div></td>
					';

					for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
						$tipo = 1; // Extra
						$fecha = $j;
						$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo = '$tipo' $filtro_loc ", "");
						$horas1 = 0;

						foreach ($row_rsHoras as $hora) {
							$horas1 += $hora->horas;
						}

						$horas1 = $horas1 * 1;

						if ($row_rsHoras->loc != "DESCANSO" && $row_rsHoras->loc != "VACACIONES" && $row_rsHoras->loc != "PERMISO" && $row_rsHoras->loc != "FALTA") {
							$cantidades['normal'] += $horas1;
						}

						if ($row_rsHoras->loc == "DESCANSO") {
							$horas1 = "D";
						} elseif ($row_rsHoras->loc == "VACACIONES") {
							$horas1 = "V";
						} elseif ($row_rsHoras->loc == "PERMISO") {
							$horas1 = "P";
						} elseif ($row_rsHoras->loc == "FALTA") {
							$horas1 = "F";
						}

						if ($row_rsHoras->loc == "INCAPACIDAD") {
							$incapacidades['normal'] += $horas1;
							$horas1 = "I";
						}

						$incapacidades['normal'] = $incapacidades['normal'] * 1;

						$output .= '<td><div align="center">' . $this->evaluar($horas1) . '</div></td>';
					}

					$output .= '
						<td><div align="center">' . $this->evaluar($incapacidades['normal']) . '</div></td>
						';

					$cantidades['normal'] += $horas_pendientes[$cedula][$tipo];

					$output .= '
						<td><div align="center">' . $this->evaluar($cantidades['normal']);
					$total1 += $cantidades['normal'] . '</div></td>
						</tr>
					';
				}
			}

			//VERIFICAR SI EXISTE  
			$existe = 0;
			for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {

				$tipo = 2;
				$fecha = $j;
				$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "")[0];

				$horasTotal = $row_rsHoras->horas  * 1;
				//$row_rsHoras->horas = $row_rsHoras->horas * 1;
				if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
					if ($horasTotal > 0) {
						$existe = 1;
					}
				}
			}
			if ($horas_pendientes[$cedula][$tipo] > 0) {
				$existe = 1;
			}
			// $k = 0;
			if ($existe == 1) {


				if ($resultado['tipo'] == 2) {

					$k++;

					$output .= '
					<tr id="fila2_' . $k . '">
					<td> ' . $k  . '</td>
					<td> ' . $cedula . '</td>
					<td> ' .  $empleado->nombre1 . '</td>
					<td>
					<div align="center">' .  $this->evaluar($horas_pendientes[$cedula][$tipo]) . '</div>
					</td>';
					for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
						$tipo = 2; //extra
						$fecha = $j;
						$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "");
						$horas1 = 0;
						foreach ($row_rsHoras as $hora) {
							$horas1 += $hora->horas;
						}
						//  	$row_rsHoras->horas = $horas1;


						// $row_rsHoras->horas = $row_rsHoras->horas * 1;
						$horas1 = $horas1 * 1;


						if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
							$cantidades['extra'] += $horas1;
						}

						if ($row_rsHoras->loc == "DESCANSO") {
							$horas1 = "D";
						}
						if ($row_rsHoras->loc == "VACACIONES") {
							$horas1 = "V";
						}
						if ($row_rsHoras->loc == "PERMISO") {
							$horas1 = "P";
						}
						if ($row_rsHoras->loc == "FALTA") {
							$horas1 = "F";
						}

						if ($row_rsHoras->loc == "INCAPACIDAD") {
							$incapacidades['extra'] += $horas1;
							$horas1 = "I";
						}
						$incapacidades['extra'] = $incapacidades['extra'] * 1;
						$output .= '
						<td>
						<div align="center">' .  $this->evaluar($horas1) . '</div>
						</td> ';
					}
					$output .= '
						<td>
						<div align="center">' . $this->evaluar($incapacidades['extra']) . ' </div>
						</td>';

					$cantidades['extra'] += $horas_pendientes[$cedula][$tipo];

					$output .= '
						<td>
						<div align="center"> ' . $this->evaluar($cantidades['extra']);
					$total1 += $cantidades['extra'] . ' </div>
						</td>
						</tr>';
				}
			}




			//VERIFICAR SI EXISTE  
			$existe = 0;
			for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {

				$tipo = 3;
				$fecha = $j;
				$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "")[0];

				$horasTotal = $row_rsHoras->horas  * 1;
				//$row_rsHoras->horas = $row_rsHoras->horas * 1;
				if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
					if ($horasTotal > 0) {
						$existe = 1;
					}
				}
			}
			if ($horas_pendientes[$cedula][$tipo] > 0) {
				$existe = 1;
			}
			// $k = 0;
			if ($existe == 1) {

				if ($resultado['tipo'] == 3) {
					// $k++;
					$k++;
					$output .= '
					<tr id="fila3_' . $k . '">
					<td > ' . $k  . '</td>
					<td > ' . $cedula . '</td>
					<td > ' .  $empleado->nombre1 . '</td>
					<td>
					<div align="center">' .  $this->evaluar($horas_pendientes[$cedula][$tipo]) . '</div>
					</td>';
					for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
						$tipo = 3; //extra
						$fecha = $j;
						$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "");
						$horas1 = 0;
						foreach ($row_rsHoras as $hora) {
							$horas1 += $hora->horas;
						}



						$horas1 = $horas1 * 1;

						if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
							$cantidades['nocturna'] += $horas1;
						}

						if ($row_rsHoras->loc == "DESCANSO") {
							$horas1 = "D";
						}
						if ($row_rsHoras->loc == "VACACIONES") {
							$horas1 = "V";
						}
						if ($row_rsHoras->loc == "PERMISO") {
							$horas1 = "P";
						}
						if ($row_rsHoras->loc == "FALTA") {
							$horas1 = "F";
						}

						if ($row_rsHoras->loc == "INCAPACIDAD") {
							$incapacidades['nocturna'] += $horas1;
							$horas1 = "I";
						}
						$incapacidades['nocturna'] = $incapacidades['nocturna'] * 1;
						$output .= '
						<td>
						<div align="center">' .  $this->evaluar($horas1) . '</div>
						</td> ';
					}
					$output .= '
						<td>
						<div align="center">' . $this->evaluar($incapacidades['nocturna']) . ' </div>
						</td>';

					$cantidades['nocturna'] += $horas_pendientes[$cedula][$tipo];

					$output .= '
						<td>
						<div align="center"> ' . $this->evaluar($cantidades['nocturna']);
					$total1 += $cantidades['nocturna'] . ' </div>
						</td>
						</tr>';
				}
			}
			//VERIFICAR SI EXISTE  
			$existe = 0;
			for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {

				$tipo = 4;
				$fecha = $j;
				$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "")[0];

				$horasTotal = $row_rsHoras->horas  * 1;
				//$row_rsHoras->horas = $row_rsHoras->horas * 1;
				if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
					if ($horasTotal > 0) {
						$existe = 1;
					}
				}
			}
			if ($horas_pendientes[$cedula][$tipo] > 0) {
				$existe = 1;
			}
			// $k = 0;
			if ($existe == 1) {

				if ($resultado['tipo']  == 4) {
					// $k++;
					$k++;
					$output .= '
					<tr id="fila4_' . $k . '">
					<td > ' . $k  . '</td>
					<td > ' . $cedula . '</td>
					<td > ' .  $empleado->nombre1 . '</td>
					<td>
					<div align="center">' .  $this->evaluar($horas_pendientes[$cedula][$tipo]) . '</div>
					</td>';
					for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
						$tipo = 4; //extra
						$fecha = $j;
						$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "");
						$horas1 = 0;
						foreach ($row_rsHoras as $hora) {
							$horas1 += $hora->horas;
						}
						// $row_rsHoras->horas = $horas1;


						$horas1 = $horas1 * 1;

						if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
							$cantidades['festivo'] += $horas1;
						}

						if ($row_rsHoras->loc == "DESCANSO") {
							$horas1 = "D";
						}
						if ($row_rsHoras->loc == "VACACIONES") {
							$horas1 = "V";
						}
						if ($row_rsHoras->loc == "PERMISO") {
							$horas1 = "P";
						}
						if ($row_rsHoras->loc == "FALTA") {
							$horas1 = "F";
						}

						if ($row_rsHoras->loc == "INCAPACIDAD") {
							$incapacidades['festivo'] += $horas1;
							$horas1 = "I";
						}
						$incapacidades['festivo'] = $incapacidades['festivo'] * 1;
						$output .= '
						<td>
						<div align="center">' .  $this->evaluar($horas1) . '</div>
						</td> ';
					}
					$output .= '
						<td>
						<div align="center">' . $this->evaluar($incapacidades['festivo']) . ' </div>
						</td>';

					$cantidades['festivo'] += $horas_pendientes[$cedula][$tipo];

					$output .= '
						<td>
						<div align="center"> ' . $this->evaluar($cantidades['festivo']);
					$total1 += $cantidades['festivo'] . ' </div>
						</td>
						</tr>';
				}
			}
			//VERIFICAR SI EXISTE  
			$existe = 0;
			for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {

				$tipo = 5;
				$fecha = $j;
				$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "")[0];
				$horasTotal = $row_rsHoras->horas  * 1;
				//$row_rsHoras->horas = $row_rsHoras->horas * 1;

				if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
					if ($horasTotal > 0) {
						$existe = 1;
					}
				}
			}
			if ($horas_pendientes[$cedula][$tipo] > 0) {
				$existe = 1;
			}
			// $k = 0;
			if ($existe == 1) {

				if ($resultado['tipo'] == 5) {
					// $k++;
					$k++;
					$output .= '
					<tr id="fila5_' . $k . '">
					<td > ' . $k  . '</td>
					<td > ' . $cedula . '</td>
					<td > ' .  $empleado->nombre1 . '</td>
					<td>
					<div align="center">' .  $this->evaluar($horas_pendientes[$cedula][$tipo]) . '</div>
					</td>';
					for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
						$tipo = 5; //extra
						$fecha = $j;
						$row_rsHoras = $planillaHorasModel->getList("cedula = '$cedula' AND fecha = '$fecha' AND tipo= '$tipo' $filtro_loc ", "");
						$horas1 = 0;
						foreach ($row_rsHoras as $hora) {
							$horas1 += $hora->horas;
						}
						// $row_rsHoras->horas = $horas1;


						$horas1 = $horas1 * 1;

						if ($row_rsHoras->loc != "DESCANSO" and $row_rsHoras->loc != "VACACIONES" and $row_rsHoras->loc != "PERMISO" and $row_rsHoras->loc != "FALTA") {
							$cantidades['dominical'] += $horas1;
						}

						if ($row_rsHoras->loc == "DESCANSO") {
							$horas1 = "D";
						}
						if ($row_rsHoras->loc == "VACACIONES") {
							$horas1 = "V";
						}
						if ($row_rsHoras->loc == "PERMISO") {
							$horas1 = "P";
						}
						if ($row_rsHoras->loc == "FALTA") {
							$horas1 = "F";
						}

						if ($row_rsHoras->loc == "INCAPACIDAD") {
							$incapacidades['dominical'] += $horas1;
							$horas1 = "I";
						}
						$incapacidades['dominical'] = $incapacidades['dominical'] * 1;
						$output .= '
						<td>
						<div align="center">' .  $this->evaluar($horas1) . '</div>
						</td> ';
					}
					$output .= '
						<td>
						<div align="center">' . $this->evaluar($incapacidades['dominical']) . ' </div>
						</td>';

					$cantidades['dominical'] += $horas_pendientes[$cedula][$tipo];

					$output .= '
						<td>
						<div align="center"> ' . $this->evaluar($cantidades['dominical']);
					$total1 += $cantidades['dominical'] . ' </div>
						</td>
						</tr>';
				}
			}


			$cantidades = [];
			$incapacidades = [];
		}


		$output  .= '
		 <tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			';
		for ($j = $fecha_inicio; $j <= $fecha_fin; $j = $this->sumar_dias($j, 1)) {
			$output  .= '
			<td></td>

			';
		}
		$output  .= '
			<td></td>
			<td align="right">
		    <div align="center"><strong>' . $total1 . '</strong></div>
		  </td>
		  </tr>
			';
		$output .= '</table>';
		$hoy = date('Ym-d h:m:s');
		header('Content-Type: application/xls');
		header('Content-Disposition: attachment; filename=Informe_de_facturacion' . $hoy . '.xls');
		echo $output;
	}
	public function evaluar($x)
	{
		if ($x == "0") {
			return "";
		} else {
			return $x;
		}
	}
	public function dia_semana($f)
	{
		$dia_semana = date("w", strtotime($f));

		if ($dia_semana == 0) {
			$dia_semana = 6; // Assign 6 instead of 7 for Sunday
		} else {
			$dia_semana -= 1; // Subtract 1 from the other days to match the array indexes
		}

		$dias = array('L', 'M', 'X', 'J', 'V', 'S', 'D');
		$letra = $dias[$dia_semana];

		return $letra;
	}
	public function sumar_dias($fecha, $dias)
	{
		$nuevafecha = strtotime('+' . $dias . ' day', strtotime($fecha));
		$nuevafecha = date('Y-m-d', $nuevafecha);
		return $nuevafecha;
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
	private function getLocalizacion()
	{
		$modelData = new Page_Model_DbTable_Localizaciones();
		$data = $modelData->getList("nombre != 'DESCANSO' AND nombre != 'VACACIONES' AND nombre != 'INCAPACIDAD' AND nombre!='FALTA' AND nombre!='PERMISO'", "nombre ASC");
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->nombre] = $value->nombre;
		}
		return $array;
	}
	private function getTipo()
	{
		$array = array();
		$array['1'] = 'Horas normales';
		$array['2'] = 'Horas diurnas';
		$array['3'] = 'Horas nocturna';
		$array['4'] = 'Festivos';
		$array['5'] = 'Dominicales';


		return $array;
	}
	protected function getFilter()
	{
		// $filtros = " 1 = 1 ";
		$filtros = " 1 ";
		$filtros2 = " 1 ";


		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object) Session::getInstance()->get($this->namefilter);
			if ($filters->fecha_inicio != '' && $filters->fecha_fin != '') {
				$filtros = $filtros . "  AND ( ( planilla_horas.fecha >= '" . $filters->fecha_inicio . "' AND planilla_horas.fecha<='" . $filters->fecha_fin . "') OR planilla_horas.fecha='0000-00-00') ";

				/* 	$filtros2 = $filtros2 . " AND fecha1>='" . $filters->fecha_inicio . "' AND fecha1 <='" . $filters->fecha_fin . "' AND fecha2>='" . $filters->fecha_inicio . "' AND fecha2 <='" . $filters->fecha_fin . "'  "; */
				$filtros2 = $filtros2 . " AND fecha1>='" . $filters->fecha_inicio . "' AND fecha2 <='" . $filters->fecha_fin . "'   ";
			}
			if ($filters->empresa != '') {
				$filtros = $filtros . " AND planilla.empresa ='" . $filters->empresa . "'";

				$filtros2 = $filtros2 . " AND planilla.empresa ='" . $filters->empresa . "'";
			}


			if ($filters->tipo != '') {
				$filtros = $filtros . " AND  planilla_horas.tipo='" . $filters->tipo . "'";
				$filtros2 = $filtros2 . " AND tipo = '" . $filters->tipo . "'  ";
			}
			if ($filters->localizacion != '') {
				$filtros = $filtros . " AND  planilla_horas.loc ='" . $filters->localizacion . "'";
			}
		}
		return array('filtros' => $filtros, 'filtros2' => $filtros2, 'fecha_inicio' => $filters->fecha_inicio, 'fecha_fin' => $filters->fecha_fin, 'empresa' => $filters->empresa, 'localizacion' => $filters->localizacion, 'tipo' => $filters->tipo);
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
			$parramsfilter['localizacion'] = $this->_getSanitizedParam("localizacion");
			$parramsfilter['tipo'] = $this->_getSanitizedParam("tipo");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}
