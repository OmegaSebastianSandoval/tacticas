<?php

/**
 * Controlador de Historial que permite la  creacion, edicion  y eliminacion de los historial del Sistema
 */
class Page_rotacionController extends Page_mainController
{
    public $botonpanel = 5;
    /**
     * $mainModel  instancia del modelo de  base de datos historial
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
    protected $_csrf_section = "page_rotacion";

    /**
     * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
     * @var string
     */
    protected $namepages;



    /**
     * Inicializa las variables principales del controlador rotacion .
     *
     * @return void.
     */
    public function init()
    {
        $this->mainModel = new Page_Model_DbTable_Hojadevida();
        $this->namefilter = "parametersfilterrotacion";
        $this->route = "/page/rotacion";
        $this->namepages = "pages_rotacion";
        $this->namepageactual = "page_actual_rotacion";
        $this->_view->route = $this->route;
        if (Session::getInstance()->get($this->namepages)) {
            $this->pages = Session::getInstance()->get($this->namepages);
        } else {
            $this->pages = 20;
        }
        parent::init();
    }


    /**
     * Recibe la informacion y  muestra un listado de  historial con sus respectivos filtros.
     *
     * @return void.
     */
    public function indexAction()
    {
        $title = "Rotación de personal";
        $this->getLayout()->setTitle($title);
        $this->_view->titlesection = $title;
        $this->filters();
        $this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
        $this->_view->list_empresa = $this->getEmpresa();

        $filters = (object)Session::getInstance()->get($this->namefilter);
        $this->_view->filters = $filters;
        $filters = $this->getFilter();
        $historialModel = new Page_Model_DbTable_Historial();
        // echo $filters . "ok";
        $order = " id ASC ";
        $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $dias = array("", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sabado", "Domingo");

        $dias = array_map("mb_strtoupper", $dias);
        $meses2 = array("", "ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC");
        $dias2 = array("", "L", "M", "M", "J", "V", "S", "D");
        $ingresos = 0;
        $retiros = 0;

        for ($i = 1; $i <= 12; $i++) {
            $fecha1 = date("Y-") . $this->con_cero($i) . "-01";
            $fecha2 = date("Y-") . $this->con_cero($i) . "-31";
            $filtroIngresos = $filters . " AND fecha_ingreso >= '$fecha1' AND fecha_ingreso <= '$fecha2' ";
            $filtroRetiros = $filters .  " AND fecha_salida >= '$fecha1' AND fecha_salida <= '$fecha2' ";

            $totalIngresos  = $historialModel->getList($filtroIngresos, "");
            $totalRetiros = $historialModel->getList($filtroRetiros, "");
            $totalIngresos = count($totalIngresos);
            $totalRetiros = count($totalRetiros);

            $totalTrabajadores1 = $historialModel->getList($filtroIngresos, " id ASC ")[0];
            $totalTrabajadores2 = $historialModel->getList($filtroIngresos, " id DESC ")[0];




            $trabajadores1 =  $totalTrabajadores1->trabajadores_total;
            $trabajadores2 = $totalTrabajadores2->trabajadores_total;
            if (strpos($filters, "empresa") !== false && strpos($filters, "FIND_IN_SET") === false) {
                $trabajadores1 =  $totalTrabajadores1->trabajadores_empresa;
                $trabajadores2 = $totalTrabajadores2->trabajadores_empresa;
            }


            $cantidadRepeticiones = substr_count($filters, "empresa");

            if ($cantidadRepeticiones === 2) {
                $trabajadores1 =  $totalTrabajadores1->trabajadores_empresa;
                $trabajadores2 = $totalTrabajadores2->trabajadores_empresa;
            }
            /*  if (Session::getInstance()->get("kt_login_level")==='3' && strpos($filters, "FIND_IN_SET") === false) {
                $trabajadores1 =  $totalTrabajadores1->trabajadores_empresa;
                $trabajadores2 = $totalTrabajadores2->trabajadores_empresa;
            }  */

            //calcular total de ingresos y retiros
            $ingresos += $totalIngresos;
            $retiros += $totalRetiros;

            $indicador1 = ($totalIngresos + $totalRetiros) * 100;
            $indicador2 = ($trabajadores1 + $trabajadores2) / 2;

            $indicador = ""; // Cadena vacía
            if ($indicador2 > 0) {
                $indicador = $indicador1 / $indicador2; // Asigna un valor numérico a $indicador
            }

            $indicador = floatval($indicador); // Convierte $indicador a un número decimal
            $indicador = round($indicador, 2); // Aplica el redondeo a $indicador

            $this->_view->tabla  .= '
            <tr>
            <td> ' . $meses[$i] . '</td>
            <td> ' . $totalIngresos . '</td>
            <td> ' . $totalRetiros . '</td>
            <td> ' . $indicador . '%</td>
            </tr>';
        }
        $this->_view->tabla2  = '
            <tr style="background: #dbeeff;">
            <td><strong>TOTAL</strong></td>
            <td><strong> ' . $ingresos . '</strong></td>
            <td><strong> ' . $retiros . '</strong></td>
            <td> </td>
            </tr>';
    }

    public function con_cero($x)
    {
        $x = $x * 1;
        if ($x <= 9) {
            $x = "0" . $x;
        }
        return $x;
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
        $filtros = " 1  ";

        if (Session::getInstance()->get("kt_login_level") == 2) {
            $empresa = Session::getInstance()->get("kt_login_empresa");
            $filtros = $filtros . " AND empresa = '$empresa' ";
        }
        if (Session::getInstance()->get("kt_login_level") == 3) {
            $asignacion = Session::getInstance()->get("kt_login_asignacion");
            $filtros =   $filtros . " AND FIND_IN_SET(empresa, '$asignacion') ";
        }
        Session::getInstance()->get("kt_login_empresa");

        if (Session::getInstance()->get($this->namefilter) != "") {
            $filters = (object)Session::getInstance()->get($this->namefilter);

            if ($filters->empresa != '') {
                $filtros = $filtros . " AND empresa ='" . $filters->empresa . "'";
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

            $parramsfilter['empresa'] =  $this->_getSanitizedParam("empresa");


            Session::getInstance()->set($this->namefilter, $parramsfilter);
        }
        if ($this->_getSanitizedParam("cleanfilter") == 1) {
            Session::getInstance()->set($this->namefilter, '');
            Session::getInstance()->set($this->namepageactual, 1);
        }
    }
}
