<?php

/**
 * Controlador de Localizaciones que permite la  creacion, edicion  y eliminacion de los locaci&oacute;n del Sistema
 */
class Page_infolocalizacionController extends Page_mainController
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
	protected $_csrf_section = "page_infolocalizacion";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador infolocalizacion .
	 *
	 * @return void.
	 */
	public function init()
	{
		/* if ((Session::getInstance()->get("kt_login_level") == '2' )) {
			header('Location: /page/panel');
		} */
		$this->mainModel = new Page_Model_DbTable_Localizaciones();
		$this->namefilter = "parametersfilterinfolocalizacion";
		$this->route = "/page/infolocalizacion";
		$this->namepages = "pages_infolocalizacion";
		$this->namepageactual = "page_actual_infolocalizacion";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
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
		$title = "Informe de localizaciones";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->_view->list_facturadas = $this->getFacuradas();
		//print_r($this->getFacuradas());
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		// $this->_view->list_empresa = $this->getEmpresa();

		$filters = (object)Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$resultado = $this->getFilter();
		$filtros = $resultado['filtros'];
		$fecha_inicio = $resultado['fecha_inicio'];
		$fecha_fin = $resultado['fecha_fin'];
		//echo $filtros;

		if ($filtros == " ") {
			$this->_view->noContent = 1;
			return;
		}

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

		$this->_view->pages = $this->pages;

		$this->_view->page = $page;

		$localizacionModel = new Page_Model_DbTable_Localizaciones();
		$planillaHorasModel = new Page_Model_DbTable_PlanillaHoras();
		$facturadasModel = new Page_Model_DbTable_Facturadas();

		/* $localizacionesCantidad = $localizacionModel->getList("1  AND nombre!='DESCANSO' AND nombre!='PERMISO' AND nombre!='FALTA' AND nombre!='VACACIONES' ", "nombre ASC");
		$this->_view->totalpages = ceil(count($localizacionesCantidad) / $amount);

		$localizaciones = $localizacionModel->getListPages("1  AND nombre!='DESCANSO' AND nombre!='PERMISO' AND nombre!='FALTA' AND nombre!='VACACIONES' ", "nombre ASC", $start, $amount); */
		$planillaHoras = $planillaHorasModel->getSumHorasLocalizacionNew2($fecha_inicio, $fecha_fin);

		/* echo '<pre>';
		print_r($planillaHoras);
		echo '</pre>'; */
		$tabla = '';
		$TOTALES = array();
		$i = 0;
		foreach ($planillaHoras as $value) {
			$i++;
			$localizacion = $value->localizacion;
			$cedula = $localizacion;


			$horasFacturadas = $facturadasModel->getList(" localizacion = '$localizacion'AND fecha1 = '" . $fecha_inicio . "'  AND fecha2 = '" . $fecha_fin . "' ", "")[0];
			$tabla .= '
			<tr>
            <td>' . $localizacion . '</td>
            <td> <a href="/page/infolocalizacion/detalle?loc=' . $localizacion . '&fecha1=' . $fecha_inicio . '&fecha2=' . $fecha_fin . '&tipo=1" data-fancybox data-type="iframe" class="enlace_archivo2">' . $value->total_tipo_1 . '</a> </td>
			
			<td>

			<div class="d-flex gap-2 justify-content-between">

            <input type="text" 
			name="normal1_' . $i . '" 
			id="normal1_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->normal1 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ',' . "'normal1'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ',' . "'normal1'" . ',' . "'$i'" . ');" >

			<input type="text" 
			name="normal2_' . $i . '" 
			id="normal2_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->normal2 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ',' . "'normal2'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ',' . "'normal2'" . ',' . "'$i'" . ');" >

			<input type="text" 
			name="normal3_' . $i . '" 
			id="normal3_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->normal3 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ',' . "'normal3'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ',' . "'normal3'" . ',' . "'$i'" . ');" >

          	</div>
			</td>


            <td> <a href="/page/infolocalizacion/detalle?loc=' . $localizacion . '&fecha1=' . $fecha_inicio . '&fecha2=' . $fecha_fin . '&tipo=2" data-fancybox data-type="iframe" class="enlace_archivo2">' . $value->total_tipo_2 . '</a> </td>

            <td>
			<div class="d-flex gap-2 justify-content-between">
			
            <input type="text" 
			name="extra1_' . $i . '" 
			id="extra1_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->extra1 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ', ' . "'extra1'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ', ' . "'extra1'" . ',' . "'$i'" . ');" >

			<input type="text" 
			name="extra2_' . $i . '" 
			id="extra2_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->extra2 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ', ' . "'extra2'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ', ' . "'extra2'" . ',' . "'$i'" . ');" >

			<input type="text" 
			name="extra3_' . $i . '" 
			id="extra3_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->extra3 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ', ' . "'extra3'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ', ' . "'extra3'" . ',' . "'$i'" . ');" >

          	</div>
			
			</td>
            <td> <a href="/page/infolocalizacion/detalle?loc=' . $localizacion . '&fecha1=' . $fecha_inicio . '&fecha2=' . $fecha_fin . '&tipo=3" data-fancybox data-type="iframe" class="enlace_archivo2">' . $value->total_tipo_3 . '</a> </td>

            <td>
			
			<div class="d-flex gap-2 justify-content-between">
			
            <input type="text" 
			name="nocturna1_' . $i . '" 
			id="nocturna1_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->nocturna1 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ',  ' . "'nocturna1'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ',  ' . "'nocturna1'" . ',' . "'$i'" . ');" >

			<input type="text" 
			name="nocturna2_' . $i . '" 
			id="nocturna2_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->nocturna2 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ',  ' . "'nocturna2'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ',  ' . "'nocturna2'" . ',' . "'$i'" . ');" >

			<input type="text" 
			name="nocturna3_' . $i . '" 
			id="nocturna3_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->nocturna3 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ', ' . "'nocturna3'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ',  ' . "'nocturna3'" . ',' . "'$i'" . ');" >

          	</div>

			</td>

            <td> <a href="/page/infolocalizacion/detalle?loc=' . $localizacion . '&fecha1=' . $fecha_inicio . '&fecha2=' . $fecha_fin . '&tipo=4" data-fancybox data-type="iframe" class="enlace_archivo2">' . $value->total_tipo_4 . '</a> </td>

            <td>
			<div class="d-flex gap-2 justify-content-between">
			
            <input type="text" 
			name="festivo1_' . $i . '" 
			id="festivo1_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->festivo1 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ', ' . "'festivo1'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ', ' . "'festivo1'" . ',' . "'$i'" . ');" >

			<input type="text" 
			name="festivo2_' . $i . '" 
			id="festivo2_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->festivo2 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ', ' . "'festivo2'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ', ' . "'festivo2'" . ',' . "'$i'" . ');" >

			<input type="text" 
			name="festivo3_' . $i . '" 
			id="festivo3_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->festivo3 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ', ' . "'festivo3'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ', ' . "'festivo3'" . ',' . "'$i'" . ');" >

          	</div>
			</td>

            <td> <a href="/page/infolocalizacion/detalle?loc=' . $localizacion . '&fecha1=' . $fecha_inicio . '&fecha2=' . $fecha_fin . '&tipo=5" data-fancybox data-type="iframe" class="enlace_archivo2">' . $value->total_tipo_5 . '</a> </td>
            <td>
			<div class="d-flex gap-2 justify-content-between">
			
            <input type="text" 
			name="dominical1_' . $i . '" 
			id="dominical1_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->dominical1 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ', ' . "'dominical1'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ', ' . "'dominical1'" . ',' . "'$i'" . ');" >

			<input type="text" 
			name="dominical2_' . $i . '" 
			id="dominical2_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->dominical2 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ', ' . "'dominical2'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ', ' . "'dominical2'" . ',' . "'$i'" . ');" >

			<input type="text" 
			name="dominical3_' . $i . '" 
			id="dominical3_' . $i . '" 
			class="form-control v" 
			value="' . $horasFacturadas->dominical3 . '" 
			onkeyup="guardar_facturadas(' . "'$cedula'" . ', ' . "'dominical3'" . ', ' . "'$i'" . ');"
			onchange="guardar_facturadas(' . "'$cedula'" . ', ' . "'dominical3'" . ',' . "'$i'" . ');" >

          	</div>
			</td>
			</tr>
			';
			$TOTALES['normal'] += $value->total_tipo_1 * 1;
			$TOTALES['extra'] += $value->total_tipo_2 * 1;
			$TOTALES['nocturna'] += $value->total_tipo_3 * 1;
			$TOTALES['festivo'] += $value->total_tipo_4 * 1;
			$TOTALES['dominical'] +=  $value->total_tipo_5 * 1;
		}
		$tabla .= '
		<tr>
		<td></td>
		<td><strong>' . $TOTALES['normal'] . '</strong></td>
		<td></td>
		<td><strong>' . $TOTALES['extra'] . '</strong></td>
		<td></td>
		<td><strong>' . $TOTALES['nocturna'] . '</strong></td>
		<td></td>
		<td><strong>' . $TOTALES['festivo'] . '</strong></td>
		<td></td>
		<td><strong>' . $TOTALES['dominical'] . '</strong></td>
		<td></td>
		</tr>
		';

		/* 
		$TOTALES = array();
		$tabla2 = '';
		foreach ($localizacionesCantidad as $value2) {	
			$localizacion = $value2->nombre;
			$planillaHoras = $planillaHorasModel->getSumHorasLocalizacionNew($localizacion, $fecha_inicio, $fecha_fin);
			$TOTALES['normal'] += $planillaHoras[0]->total;
			$TOTALES['extra'] += $planillaHoras[1]->total;
			$TOTALES['nocturna'] += $planillaHoras[2]->total;
			$TOTALES['festivo'] += $planillaHoras[3]->total;
			$TOTALES['dominical'] += $planillaHoras[4]->total;
		}
		$tabla2 .= '
			<tr>
            <td></td>
            <td><strong>' . $TOTALES['normal'] . '</strong></td>
            <td></td>
			<td><strong>' . $TOTALES['extra'] . '</strong></td>
            <td></td>
			<td><strong>' . $TOTALES['nocturna'] . '</strong></td>
            <td></td>
			<td><strong>' . $TOTALES['festivo'] . '</strong></td>
            <td></td>
			<td><strong>' . $TOTALES['dominical'] . '</strong></td>
            <td></td>
			</tr>
			'; */

		/* foreach ($localizaciones as $value) {
			$total = array(
				'normal' => 0,
				'extra' => 0,
				'nocturna' => 0,
				'festivo' => 0,
				'dominical' => 0
			);
			$localizacion = $value->nombre;
			$localizacionId = $value->id;

			$planillaHoras = $planillaHorasModel->getSumHorasLocalizacionNew($localizacion, $fecha_inicio, $fecha_fin);

			$total['normal'] = $planillaHoras[0]->total;
			// $TOTALES['normal'] += $planillaHoras[0]->total;


			$total['extra'] = $planillaHoras[1]->total;
			// $TOTALES['extra'] += $planillaHoras[1]->total;


			$total['nocturna'] = $planillaHoras[2]->total;
			// $TOTALES['nocturna'] += $planillaHoras[2]->total;


			$total['festivo'] = $planillaHoras[3]->total;
			// $TOTALES['festivo'] += $planillaHoras[3]->total;


			$total['dominical'] = $planillaHoras[4]->total; */
		// $TOTALES['dominical'] += $planillaHoras[4]->total;

		/* 		$planillaHoras = $planillaHorasModel->getSumHorasLocalizacion(" loc ='$localizacion'  AND tipo=1 $filtros")[0];

			$total['normal'] = $planillaHoras->total;
			// $TOTALES['normal'] += $planillaHoras->total;

		$planillaHoras = $planillaHorasModel->getSumHorasLocalizacion(" loc ='$localizacion'  AND tipo=2 $filtros")[0];

			$total['extra'] = $planillaHoras->total * 1;
			$TOTALES['extra'] += $planillaHoras->total;


			$planillaHoras = $planillaHorasModel->getSumHorasLocalizacion(" loc ='$localizacion'  AND tipo=3 $filtros")[0];

			$total['nocturna'] = $planillaHoras->total * 1;
			$TOTALES['nocturna'] += $planillaHoras->total;

			$planillaHoras = $planillaHorasModel->getSumHorasLocalizacion(" loc ='$localizacion'  AND tipo=4 $filtros")[0];

			$total['festivo'] = $planillaHoras->total * 1;
			$TOTALES['festivo'] += $planillaHoras->total;

			$planillaHoras = $planillaHorasModel->getSumHorasLocalizacion(" loc ='$localizacion'  AND tipo=5 $filtros")[0];

			$total['dominical'] = $planillaHoras->total * 1;
			$TOTALES['dominical'] += $planillaHoras->total; */
		/* "'$cedula'" = $localizacion;

			$horasFacturadas = $facturadasModel->getList(" localizacion = '$localizacion'AND fecha1 = '" . $fecha_inicio . "'  AND fecha2 = '" . $fecha_fin . "' ", ""); */
		/* $tabla .= '
			<tr>
            <td style="text-align: start;">' . $localizacion . '</td>
            <td> <a href="/page/infolocalizacion/detalle?loc=' . $localizacion . '&fecha1=' . $fecha_inicio . '&fecha2=' . $fecha_fin . '&tipo=1" data-fancybox data-type="iframe" class="enlace_archivo2">' . $total['normal'] . '</a> </td>

			
			<td></td>
            <td> <a href="/page/infolocalizacion/detalle?loc=' . $localizacion . '&fecha1=' . $fecha_inicio . '&fecha2=' . $fecha_fin . '&tipo=2" data-fancybox data-type="iframe" class="enlace_archivo2">' . $total['extra'] . '</a> </td>
            <td></td>
            <td> <a href="/page/infolocalizacion/detalle?loc=' . $localizacion . '&fecha1=' . $fecha_inicio . '&fecha2=' . $fecha_fin . '&tipo=3" data-fancybox data-type="iframe" class="enlace_archivo2">' . $total['nocturna'] . '</a> </td>
            <td></td>
            <td> <a href="/page/infolocalizacion/detalle?loc=' . $localizacion . '&fecha1=' . $fecha_inicio . '&fecha2=' . $fecha_fin . '&tipo=4" data-fancybox data-type="iframe" class="enlace_archivo2">' . $total['festivo'] . '</a> </td>
            <td></td>
            <td> <a href="/page/infolocalizacion/detalle?loc=' . $localizacion . '&fecha1=' . $fecha_inicio . '&fecha2=' . $fecha_fin . '&tipo=5" data-fancybox data-type="iframe" class="enlace_archivo2">' . $total['dominical'] . '</a> </td>
            <td></td>
			</tr>
			';
		} */
		/* $tabla .= '
			<tr>
            <td></td>
            <td><strong>' . $TOTALES['normal'] . '</strong></td>
            <td></td>
			<td><strong>' . $TOTALES['extra'] . '</strong></td>
            <td></td>
			<td><strong>' . $TOTALES['nocturna'] . '</strong></td>
            <td></td>
			<td><strong>' . $TOTALES['festivo'] . '</strong></td>
            <td></td>
			<td><strong>' . $TOTALES['dominical'] . '</strong></td>
            <td></td>
			</tr>
			'; */
		$this->_view->tabla = $tabla;
		// $this->_view->tabla2 = $tabla2;
		/* $this->_view->TOTALES = $TOTALES;
		$this->_view->localizaciones = $localizaciones;
		$this->_view->horasFacturadas = $horasFacturadas; */
	}
	public function detalleAction()
	{
		$list_empresa = $this->getEmpresa();
		$localizacion = $this->_getSanitizedParam("loc");
		$fecha1 = $this->_getSanitizedParam("fecha1");
		$fecha2 = $this->_getSanitizedParam("fecha2");
		$tipo = $this->_getSanitizedParam("tipo");

		$filtro = "";
		if ($fecha1 != "" and $fecha2  != "") {

			$filtro .= " AND ((planilla_horas.fecha >= '$fecha1' AND planilla_horas.fecha<='$fecha2') OR planilla_horas.fecha='0000-00-00') ";
			$filtro .= " AND ((planilla.fecha1 >= '$fecha1' AND planilla.fecha2<='$fecha2' AND planilla_horas.fecha='0000-00-00') OR planilla_horas.fecha!='0000-00-00') ";
		}
		/* WHERE planilla_horas.fecha >= '$fecha1' AND planilla_horas.fecha <= '$fecha2'
		AND (
			(planilla.fecha1 >= '$fecha1' AND planilla.fecha2 <= '$fecha2' AND planilla_horas.fecha = '0000-00-00')
			OR planilla_horas.fecha != '0000-00-00'
		) */
		$planillaHorasModel = new Page_Model_DbTable_Planillahoras();
		$horas = $planillaHorasModel->getSumHorasLocalizacionInfo(" loc = '$localizacion' AND tipo=$tipo $filtro", "empresa, fecha1");
		$tabla = '';
		$total = 0;
		foreach ($horas as  $value) {

			$tabla .= '
			<tr>
            <td>' . $localizacion . '</td>
            <td>' . $value->total . '</td>
            <td>' . $list_empresa[$value->empresa] . '</td>
            <td>' . $value->fecha1 . '</td>
            <td>' . $value->fecha2 . '</td>
			</tr>
			';
			$total += $value->total;
		}
		$tabla .= '
			<tr>
            <td></td>
            <td><strong>' . $total . '</strong></td>
            <td></td>
            <td></td>
            <td></td>
			</tr>
			';
		$this->_view->tabla = $tabla;
	}

	public function guardarfacturadasAction()
	{
		ini_set("display_errors", 0);
		header('Content-Type:application/json');
		$this->setLayout('blanco');
		$loc = $this->_getSanitizedParam("loc");
		$fecha1 = $this->_getSanitizedParam("fecha1");
		$fecha2 = $this->_getSanitizedParam("fecha2");
		$campo = $this->_getSanitizedParam("campo");
		$valor = $this->_getSanitizedParam("valor");

		$data = [];
		$data['localizacion'] = $this->_getSanitizedParam("loc");
		$data['fecha1'] = $this->_getSanitizedParam("fecha1");
		$data['fecha2'] = $this->_getSanitizedParam("fecha2");


		$facturadasModel = new Page_Model_DbTable_Facturadas();
		$horasFacturadas = $facturadasModel->getList(" localizacion = '$loc' AND fecha1 = '" . $fecha1 . "' AND fecha2 = '" . $fecha2 . "' ", "");
 
		if (count($horasFacturadas) == 0) {
			$id = $facturadasModel->insert2($data);
			/* $horasFacturadas = $facturadasModel->getList(" localizacion = '$loc' AND fecha1 = '" . $fecha1 . "' AND fecha2 = '" . $fecha2 . "' ", ""); */
		} else {
			$id = $horasFacturadas[0]->id;
			$facturadasModel->editField($id, $campo, $valor);
		} 
		  $respuesta['id'] = $id;
		/*$respuesta['loc'] = $fecha1;
		$respuesta['fecha1'] = $fecha2;
		$respuesta['fecha2'] = $loc;
		$respuesta['fecha2'] = $horasFacturadas; */


		 echo json_encode($respuesta);
	}
	private function getFacuradas()
	{
		$modelData = new Page_Model_DbTable_Facturadas();
		$data = $modelData->getFacturadas();
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->id] = $value->fecha1 . " - " . $value->fecha2;
		}
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

	protected function getFilter()
	{
		// $filtros = " 1 = 1 ";
		$filtros = " ";
		/* 
		if(Session::getInstance()->get("kt_login_level") == 2){
			$empresa = Session::getInstance()->get("kt_login_empresa");
			$filtros = 	$filtros . " AND empresa = '$empresa' ";
		}
		if(Session::getInstance()->get("kt_login_level") == 3){
			$asignacion = Session::getInstance()->get("kt_login_asignacion");
			$filtros = 	$filtros . " AND FIND_IN_SET(empresa, '$asignacion') ";
		} */

		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object)Session::getInstance()->get($this->namefilter);

			if ($filters->fecha_inicio != '' && $filters->fecha_fin != '') {
				// $filtros = $filtros . " fecha1>='" . $filters->fecha_inicio . "' AND fecha1 <='" . $filters->fecha_fin . "' AND fecha2>='" . $filters->fecha_inicio . "' AND fecha2 <='" . $filters->fecha_fin . "' ";
				$filtros = $filtros . " AND ((planilla_horas.fecha >= '" . $filters->fecha_inicio . "' AND planilla_horas.fecha<='" . $filters->fecha_fin . "') OR planilla_horas.fecha='0000-00-00') ";
				$filtros = $filtros . " AND ((planilla.fecha1 >= '" . $filters->fecha_inicio . "' AND planilla.fecha2<='" . $filters->fecha_fin . "' AND planilla_horas.fecha='0000-00-00') OR planilla_horas.fecha!='0000-00-00') ";
			}
			/* if ($filters->fecha_completa != '') {

				$filtros = $filtros . " AND planilla.empresa ='" . $filters->fecha_completa . "'";
			} */
			// $query_rsPlanillas = "SELECT * FROM planilla WHERE fecha1>='$fecha_inicio' AND fecha1 <='$fecha_fin' AND fecha2>='$fecha_inicio' AND fecha2 <='$fecha_fin' $filtro_empresa ";

			// " AND tipo_documento ='" . $filters->tipo_documento . "'"


		}
		return array('filtros' => $filtros, 'fecha_inicio' =>  $filters->fecha_inicio, 'fecha_fin' => $filters->fecha_fin);
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
			$parramsfilter['fecha_completa'] = $this->_getSanitizedParam("fecha_completa");

			if ($parramsfilter['fecha_completa'] != '') {
				// Separar las fechas en un array utilizando el guion como delimitador
				$fechasSeparadas = explode(" - ", $parramsfilter['fecha_completa']);

				// Obtener las fechas en variables individuales
				$parramsfilter['fecha_inicio'] = $fechasSeparadas[0];
				$parramsfilter['fecha_fin'] = $fechasSeparadas[1];
			} else {
				$parramsfilter['fecha_inicio'] = $this->_getSanitizedParam("fecha_inicio");
				$parramsfilter['fecha_fin'] = $this->_getSanitizedParam("fecha_fin");
			}

			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}

		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}
