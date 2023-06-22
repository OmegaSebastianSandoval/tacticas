<?php 

/**
*
*/

class generator_indexController extends Controllers_Abstract
{

	protected $_csrf_section = "login_admin";

	public $arrayfondo = [];

	public function init(){
		$this->_view->namedatabase = Config_Config::getInstance()->getValue('db/name');
	}

	public function indexAction()
	{
		$this->setLayout('generator');
		$modelTables = new Generator_Model_DbTable_Tables();
		$this->_view->tablas = $modelTables->getTables();
		$table = $this->_getSanitizedParam("table");
		$this->_view->table = $table;
		$this->_view->campos = $modelTables->getCampos($table);
		$this->_view->tipos = $this->getTipos();
	}
	
	public function getTipos(){
		$data = [];
		$data[1] = 'Input';
		$data[2] = 'Textarea';
		$data[8] = 'Textarea - Sin Editor';
		$data[3] = 'Select';
		$data[9] = 'Select - Dependiente';
		$data[4] = 'Check Box (Si,No)';
		$data[5] = 'Upload Image';
		$data[6] = 'Upload File';
		$data[7] = 'Oculto';
		return $data;
	}

	public function crearAction()
	{
		$modelTables = new Generator_Model_DbTable_Tables();
		$table = $this->_getSanitizedParam("table");
		$campos = $modelTables->getCampos($table);
		$data = $this->getDatacolum($campos);
		$info = $this->_getSanitizedParam("controlador");
		$this->crearControlador($data,$campos);
		$this->crearModelo($data,$campos);
		$this->crearVistaindex($data,$campos);
		$this->crearVistamanage($data,$campos);
		header('Location: /generator');
	}

	public function getDatacolum($campos){
		$data = [];
		$orden = [];
		foreach ($campos as $key => $campo) {
			if($campo->Key != 'PRI'){
				$data[$key] = [];
				$data[$key]['nombre'] = $campo->Field;
				$data[$key]['tipo_dato'] = $campo->Type;
				$data[$key]['requerido'] = $this->_getSanitizedParam("requerido_".$campo->Field);
				$data[$key]['titulo'] = $this->_getSanitizedParam("titulo_".$campo->Field);
				$data[$key]['tipo'] = $this->_getSanitizedParam("tipo_".$campo->Field);
				$data[$key]['tabla_dependiente'] = $this->_getSanitizedParam("tabla_dependiente_".$campo->Field);
				$data[$key]['value_dependiente'] = $this->_getSanitizedParam("value_dependiente_".$campo->Field);
				$data[$key]['label_dependiente'] = $this->_getSanitizedParam("label_dependiente_".$campo->Field);
				$data[$key]['oculto'] = $this->_getSanitizedParam("oculto_".$campo->Field);
				$data[$key]['oculto_tipo'] = $this->_getSanitizedParam("oculto_tipo_".$campo->Field);
				$data[$key]['oculto_filtro'] = $this->_getSanitizedParam("oculto_filtro_".$campo->Field);
				if($data[$key]['oculto_filtro']!=1) {
					$data[$key]['oculto_filtro'] = "0";
				}
				$data[$key]['en_listado'] = $this->_getSanitizedParam("en_listado_".$campo->Field);
				$data[$key]['ancho'] = $this->_getSanitizedParam("ancho_".$campo->Field);
				$data[$key]['orden'] = $this->_getSanitizedParam("orden_".$campo->Field);
				$orden[$key] = $data[$key]['orden'];
				if($campo->Field == 'orden' ){
					$data[$key]['ordenar'] = 1; 
				}
			} 
		}
		array_multisort($orden, SORT_ASC, $data);
		return $data;
	}

	public function crearControlador($data,$campos){
		$modelTables = new Generator_Model_DbTable_Tables();
		$controlador = strtolower($this->_getSanitizedParam('controlador'));
		$modelo = strtolower($this->_getSanitizedParam("ruta"));
		$ruta = APP_PATH."/modules/".$modelo."/Controllers/".$controlador."Controller.php";
		$nombrecontroller = ucwords($modelo)."_".$controlador."Controller";
		$nuevoarchivo = fopen($ruta, "w+");
		$ordenar = $this->getorden($data);
		$titulolistar = $this->_getSanitizedParam('titulo_listado'); 
		$tituloeditar = $this->_getSanitizedParam('titulo_edicion');
		$images = $this->getFilesimages($data);
		$documents = $this->getFilesdocument($data);
		$identificador = $this->getKey($campos)->Field;
		$camposocultos = $this->getampooculto($data);
		$php = "<?php
/**
* Controlador de ".ucwords($controlador)." que permite la  creacion, edicion  y eliminacion de los ".$titulolistar." del Sistema
*/
class ".$nombrecontroller." extends ".ucwords($modelo)."_mainController
{
	/**
	 * \$mainModel  instancia del modelo de  base de datos ".$titulolistar."
	 * @var modeloContenidos
	 */
	public \$mainModel;

	/**
	 * \$route  url del controlador base
	 * @var string
	 */
	protected \$route;

	/**
	 * \$pages cantidad de registros a mostrar por pagina]
	 * @var integer
	 */
	protected \$pages ;

	/**
	 * \$namefilter nombre de la variable a la fual se le van a guardar los filtros
	 * @var string
	 */
	protected \$namefilter;

	/**
	 * \$_csrf_section  nombre de la variable general csrf  que se va a almacenar en la session
	 * @var string
	 */
	protected \$_csrf_section = \"".$modelo."_".$controlador."\";

	/**
	 * \$namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected \$namepages;



	/**
     * Inicializa las variables principales del controlador ".$controlador." .
     *
     * @return void.
     */
	public function init()
	{
		\$this->mainModel = new ".ucwords($modelo)."_Model_DbTable_".ucwords($controlador)."();
		\$this->namefilter = \"parametersfilter".$controlador."\";
		\$this->route = \"/".$modelo."/".$controlador."\";
		\$this->namepages =\"pages_".$controlador."\";
		\$this->namepageactual =\"page_actual_".$controlador."\";
		\$this->_view->route = \$this->route;
		if(Session::getInstance()->get(\$this->namepages)){
			\$this->pages = Session::getInstance()->get(\$this->namepages);
		} else {
			\$this->pages = 20;
		}
		parent::init();
	}


	/**
     * Recibe la informacion y  muestra un listado de  ".$titulolistar." con sus respectivos filtros.
     *
     * @return void.
     */
	public function indexAction()
	{
		\$title = \"Administración de ".$titulolistar."\";
		\$this->getLayout()->setTitle(\$title);
		\$this->_view->titlesection = \$title;
		\$this->filters();
		\$this->_view->csrf = Session::getInstance()->get('csrf')[\$this->_csrf_section];
		\$filters =(object)Session::getInstance()->get(\$this->namefilter);
        \$this->_view->filters = \$filters;
		\$filters = \$this->getFilter();
		\$order = \"".$ordenar."\";
		\$list = \$this->mainModel->getList(\$filters,\$order);
		\$amount = \$this->pages;
		\$page = \$this->_getSanitizedParam(\"page\");
		if (!\$page && Session::getInstance()->get(\$this->namepageactual)) {
		   	\$page = Session::getInstance()->get(\$this->namepageactual);
		   	\$start = (\$page - 1) * \$amount;
		} else if(!\$page){
			\$start = 0;
		   	\$page=1;
			Session::getInstance()->set(\$this->namepageactual,\$page);
		} else {
			Session::getInstance()->set(\$this->namepageactual,\$page);
		   	\$start = (\$page - 1) * \$amount;
		}
		\$this->_view->register_number = count(\$list);
		\$this->_view->pages = \$this->pages;
		\$this->_view->totalpages = ceil(count(\$list)/\$amount);
		\$this->_view->page = \$page;
		\$this->_view->lists = \$this->mainModel->getListPages(\$filters,\$order,\$start,\$amount);
		\$this->_view->csrf_section = \$this->_csrf_section;";
		foreach ($data as $key => $value) {
		if(($value['tipo'] == 3 ||  $value['tipo'] == 9) && $value['en_listado'] == 1){
			$nombreSelect = ucwords(str_replace("_","",$value['nombre']));
			$php = $php."
		\$this->_view->list_".$value['nombre']." = \$this->get".$nombreSelect."();";
			}

			if($value['tipo'] == 7 && $value['oculto_tipo'] == 2 ){
				$php = $php."
		\$this->_view->".$value['oculto']." = \$this->_getSanitizedParam(\"".$value['oculto']."\");";
			}
		}

		$php= $php."
	}

	/**
     * Genera la Informacion necesaria para editar o crear un  ".$tituloeditar."  y muestra su formulario
     *
     * @return void.
     */
	public function manageAction()
	{
		\$this->_view->route = \$this->route;
		\$this->_csrf_section = \"manage_".$controlador."_\".date(\"YmdHis\");
		\$this->_csrf->generateCode(\$this->_csrf_section);
		\$this->_view->csrf_section = \$this->_csrf_section;
		\$this->_view->csrf = Session::getInstance()->get('csrf')[\$this->_csrf_section];";
		foreach ($data as $key => $value) {
		if($value['tipo'] == 3 ||  $value['tipo'] == 9 ){
			$nombreSelect = ucwords(str_replace("_","",$value['nombre']));
			$php = $php."
		\$this->_view->list_".$value['nombre']." = \$this->get".$nombreSelect."();";
			}
			if($value['tipo'] == 7 && $value['oculto_tipo'] == 2 ){
			$php = $php."
		\$this->_view->".$value['oculto']." = \$this->_getSanitizedParam(\"".$value['oculto']."\");";
			}
		}
		$php = $php."
		\$id = \$this->_getSanitizedParam(\"id\");
		if (\$id > 0) {
			\$content = \$this->mainModel->getById(\$id);
			if(\$content->".$identificador."){
				\$this->_view->content = \$content;
				\$this->_view->routeform = \$this->route.\"/update\";
				\$title = \"Actualizar ".$tituloeditar."\";
				\$this->getLayout()->setTitle(\$title);
				\$this->_view->titlesection = \$title;
			}else{
				\$this->_view->routeform = \$this->route.\"/insert\";
				\$title = \"Crear ".$tituloeditar."\";
				\$this->getLayout()->setTitle(\$title);
				\$this->_view->titlesection = \$title;
			}
		} else {
			\$this->_view->routeform = \$this->route.\"/insert\";
			\$title = \"Crear ".$tituloeditar."\";
			\$this->getLayout()->setTitle(\$title);
			\$this->_view->titlesection = \$title;
		}
	}

	/**
     * Inserta la informacion de un ".$tituloeditar."  y redirecciona al listado de ".$titulolistar.".
     *
     * @return void.
     */
	public function insertAction(){
		\$this->setLayout('blanco');
		\$csrf = \$this->_getSanitizedParam(\"csrf\");
		if (Session::getInstance()->get('csrf')[\$this->_getSanitizedParam(\"csrf_section\")] == \$csrf ) {	
			\$data = \$this->getData();";
		if(count($images)>0){
			$php = $php."
			\$uploadImage =  new Core_Model_Upload_Image();";
			foreach ($images as $key => $image) {
				$php = $php."
			if(\$_FILES['".$image."']['name'] != ''){
				\$data['".$image."'] = \$uploadImage->upload(\"".$image."\");
			}";
			}
		}
		if(count($documents)>0){
			$php = $php."
			\$uploadDocument =  new Core_Model_Upload_Document();";
			foreach ($documents as $key => $document) {
				$php = $php."
			if(\$_FILES['".$document."']['name'] != ''){
				\$data['".$document."'] = \$uploadDocument->upload(\"".$document."\");
			}";
			}
		}

			$php = $php."
			\$id = \$this->mainModel->insert(\$data);
			";
			if($ordenar == "orden ASC" ){
			$php = $php."\$this->mainModel->changeOrder(\$id,\$id);";
			}

			$php = $php."
			\$data['".$identificador."']= \$id;
			\$data['log_log'] = print_r(\$data,true);
			\$data['log_tipo'] = 'CREAR ".strtoupper($tituloeditar)."';
			\$logModel = new Administracion_Model_DbTable_Log();
			\$logModel->insert(\$data);";
			$php = $php."
		}";

		$url = "";
		foreach ($camposocultos as $key => $value) {
			if($value['tipo'] == 7 && $value['oculto_tipo'] == 2 ){
				$php = $php."
		\$".$value['oculto']." = \$this->_getSanitizedParam(\"".$value['nombre']."\");";
				if($url==""){
					$url="?".$value['oculto']."='.\$".$value['oculto'];
				} else {
					$url=$url.".'&".$value['oculto']."='.\$".$value['oculto'];
				}
			}
		}
		if($url == ""){
			$url="'";
		}
		$php = $php."
		header('Location: '.\$this->route.'".$url.".'');";
		$php = $php."
	}

	/**
     * Recibe un identificador  y Actualiza la informacion de un ".$tituloeditar."  y redirecciona al listado de ".$titulolistar.".
     *
     * @return void.
     */
	public function updateAction(){
		\$this->setLayout('blanco');
		\$csrf = \$this->_getSanitizedParam(\"csrf\");
		if (Session::getInstance()->get('csrf')[\$this->_getSanitizedParam(\"csrf_section\")] == \$csrf ) {
			\$id = \$this->_getSanitizedParam(\"id\");
			\$content = \$this->mainModel->getById(\$id);
			if (\$content->".$identificador.") {
				\$data = \$this->getData();
				";
			if(count($images)>0){
			$php = $php."\$uploadImage =  new Core_Model_Upload_Image();";
			foreach ($images as $key => $image) {
				$php = $php."
				if(\$_FILES['".$image."']['name'] != ''){
					if(\$content->".$image."){
						\$uploadImage->delete(\$content->".$image.");
					}
					\$data['".$image."'] = \$uploadImage->upload(\"".$image."\");
				} else {
					\$data['".$image."'] = \$content->".$image.";
				}
			";
			}
		}
		if(count($documents)>0){
			$php = $php."	\$uploadDocument =  new Core_Model_Upload_Document();";
			foreach ($documents as $key => $document) {
				$php = $php."
				if(\$_FILES['".$document."']['name'] != ''){
					if(\$content->".$document."){
						\$uploadDocument->delete(\$content->".$document.");
					}
					\$data['".$document."'] = \$uploadDocument->upload(\"".$document."\");
				} else {
					\$data['".$document."'] = \$content->".$document.";
				}
			";
			}
		}
				$php = $php."	\$this->mainModel->update(\$data,\$id);
			}";
			$php = $php."
			\$data['".$identificador."']=\$id;
			\$data['log_log'] = print_r(\$data,true);
			\$data['log_tipo'] = 'EDITAR ".strtoupper($tituloeditar)."';
			\$logModel = new Administracion_Model_DbTable_Log();
			\$logModel->insert(\$data);";
		$php = $php."}";
		$url = "";
		foreach ($camposocultos as $key => $value) {
			if($value['tipo'] == 7 && $value['oculto_tipo'] == 2 ){
				$php = $php."
		\$".$value['oculto']." = \$this->_getSanitizedParam(\"".$value['nombre']."\");";
				if($url==""){
					$url="?".$value['oculto']."='.\$".$value['oculto'];
				} else {
					$url=$url.".'&".$value['oculto']."='.\$".$value['oculto'];
				}
			}
		}
		if($url == ""){
			$url="'";
		}
		$php = $php."
		header('Location: '.\$this->route.'".$url.".'');";
		$php = $php."
	}

	/**
     * Recibe un identificador  y elimina un ".$tituloeditar."  y redirecciona al listado de ".$titulolistar.".
     *
     * @return void.
     */
	public function deleteAction()
	{
		\$this->setLayout('blanco');
		\$csrf = \$this->_getSanitizedParam(\"csrf\");
		if (Session::getInstance()->get('csrf')[\$this->_csrf_section] == \$csrf ) {
			\$id =  \$this->_getSanitizedParam(\"id\");
			if (isset(\$id) && \$id > 0) {
				\$content = \$this->mainModel->getById(\$id);
				if (isset(\$content)) {
					";
				if(count($images)>0){
					$php = $php."\$uploadImage =  new Core_Model_Upload_Image();";
					foreach ($images as $key => $image) {
					$php = $php."
					if (isset(\$content->".$image.") && \$content->".$image." != '') {
						\$uploadImage->delete(\$content->".$image.");
					}
					";
					}
				}
				if(count($documents)>0){
					$php = $php."\$uploadDocument =  new Core_Model_Upload_Document();";
					foreach ($documents as $key => $document) {
					$php = $php."
					if (isset(\$content->".$document.") && \$content->".$document." != '') {
						\$uploadDocument->delete(\$content->".$document.");
					}
					";
					}
				}
					$php = $php."\$this->mainModel->deleteRegister(\$id);";
					$php = $php."\$data = (array)\$content;
					\$data['log_log'] = print_r(\$data,true);
					\$data['log_tipo'] = 'BORRAR ".strtoupper($tituloeditar)."';
					\$logModel = new Administracion_Model_DbTable_Log();
					\$logModel->insert(\$data);";
				$php = $php." }
			}
		}";
		$url = "";
		foreach ($camposocultos as $key => $value) {
			if($value['tipo'] == 7 && $value['oculto_tipo'] == 2 ){
				$php = $php."
		\$".$value['oculto']." = \$this->_getSanitizedParam(\"".$value['oculto']."\");";
				if($url==""){
					$url="?".$value['oculto']."='.\$".$value['oculto'];
				} else {
					$url=$url.".'&".$value['oculto']."='.\$".$value['oculto'];
				}
			}
		}
		if($url == ""){
			$url="'";
		}
		$php = $php."
		header('Location: '.\$this->route.'".$url.".'');";
		$php = $php."
	}

	/**
     * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de ".ucwords($controlador).".
     *
     * @return array con toda la informacion recibida del formulario.
     */
	private function getData()
	{
		\$data = array();";
		foreach ($data as $key => $value) {
			if($value['ordenar']!= 1){
				if($value['tipo']== 5 || $value['tipo']== 6){
			$php = $php."
		\$data['".$value['nombre']."'] = \"\";";
				} else if ($value['tipo']== 2 ){
					$php = $php."
		\$data['".$value['nombre']."'] = \$this->_getSanitizedParamHtml(\"".$value['nombre']."\");";
				} else if ($value['tipo']== 7 ){
					if($value['oculto_tipo']==1){
					$php = $php."
		\$data['".$value['nombre']."'] = '".$value['oculto']."' ;";
					} else {
					$php = $php."
		\$data['".$value['nombre']."'] = \$this->_getSanitizedParamHtml(\"".$value['nombre']."\");";
					}
				} else {
					if(strpos($value['tipo_dato'],"int") !== false || strpos($value['tipo_dato'],"float") !== false || strpos($value['tipo_dato'],"double") !== false   ){
						$php = $php."
		if(\$this->_getSanitizedParam(\"".$value['nombre']."\") == '' ) {
			\$data['".$value['nombre']."'] = '0';
		} else {
			\$data['".$value['nombre']."'] = \$this->_getSanitizedParam(\"".$value['nombre']."\");
		}";
					} else {
						$php = $php."
		\$data['".$value['nombre']."'] = \$this->_getSanitizedParam(\"".$value['nombre']."\");";
					}
				}
			}
		}
		$php = $php."
		return \$data;
	}";

		foreach ($data as $key => $value) {
			if($value['tipo'] == 3){
				$nombreSelect = ucwords(str_replace("_","",$value['nombre']));
		$php = $php."

	/**
     * Genera los valores del campo ".$value['titulo'].".
     *
     * @return array cadena con los valores del campo ".$value['titulo'].".
     */
	private function get".$nombreSelect."()
	{
		\$array = array();
		\$array['Data'] = 'Data';
		return \$array;
	}
";
			}
			if($value['tipo'] == 9){

				if($value['tabla_dependiente']){
					$nombreSelect = ucwords(str_replace("_","",$value['nombre']));
					$this->crearModelodependiente($value['tabla_dependiente'],$modelTables->getCampos($value['tabla_dependiente']));
					$nombre_depend = ucwords($modelo)."_Model_DbTable_".ucwords(str_replace("_","","depend_".$value['tabla_dependiente']));
					$label_dependiente = $value['label_dependiente'];
					$value_dependiente = $value['value_dependiente'];
					$php = $php."

	/**
     * Genera los valores del campo ".$value['titulo'].".
     *
     * @return array cadena con los valores del campo ".$value['titulo'].".
     */
	private function get".$nombreSelect."()
	{
		\$modelData = new ".$nombre_depend."();
		\$data = \$modelData->getList();
		\$array = array();
		foreach (\$data as \$key => \$value) {
			\$array[\$value->".$value_dependiente."] = \$value->".$label_dependiente.";
		}
		return \$array;
	}
";
				}else {
				$nombreSelect = ucwords(str_replace("_","",$value['nombre']));
		$php = $php."

	/**
     * Genera los valores del campo ".$value['titulo'].".
     *
     * @return array cadena con los valores del campo ".$value['titulo'].".
     */
	private function get".$nombreSelect."()
	{
		\$array = array();
		\$array['Data'] = 'Data';
		return \$array;
	}
";
				}
			}
		}

		$php = $php."
	/**
     * Genera la consulta con los filtros de este controlador.
     *
     * @return array cadena con los filtros que se van a asignar a la base de datos
     */
    protected function getFilter()
    {";

    $php = $php."
    	\$filtros = \" 1 = 1 \";";
    	foreach ($camposocultos as $key => $value) {
			if($value['tipo'] == 7 &&  $value['oculto_filtro'] == 1){
				if ($value['oculto_tipo'] == 2) {
					$php = $php."
		\$".$value['oculto']."= \$this->_getSanitizedParam(\"".$value['oculto']."\");
		\$filtros = \$filtros.\" AND ".$value['nombre']." = '\$".$value['oculto']."' \";";
				} else {
				$php = $php." 
		\$".$value['oculto']."=".$value['oculto'].";
		\$filtros = \$filtros.\" AND".$value['nombre']." = '\$".$value['oculto']."' \";";
				}
		    }

    }
        $php = $php."
        if (Session::getInstance()->get(\$this->namefilter)!=\"\") {
            \$filters =(object)Session::getInstance()->get(\$this->namefilter);";
			foreach ($data as $key => $value) {
				if($value['en_listado']== 1){
					if( $value['tipo']== 3){
 			$php= $php."
            if (\$filters->".$value['nombre']." != '') {
                \$filtros = \$filtros.\" AND ".$value['nombre']." ='\".\$filters->".$value['nombre'].".\"'\";
            }";
					} else {
            $php= $php."
            if (\$filters->".$value['nombre']." != '') {
                \$filtros = \$filtros.\" AND ".$value['nombre']." LIKE '%\".\$filters->".$value['nombre'].".\"%'\";
            }";
       				}
        		}
        	}
	$php= $php."
		}
        return \$filtros;
    }

    /**
     * Recibe y asigna los filtros de este controlador
     *
     * @return void
     */
    protected function filters()
    {
        if (\$this->getRequest()->isPost()== true) {
        	Session::getInstance()->set(\$this->namepageactual,1);
            \$parramsfilter = array();";

            foreach ($data as $key => $value) {
				if($value['en_listado']== 1){
					$php = $php."
					\$parramsfilter['".$value['nombre']."'] =  \$this->_getSanitizedParam(\"".$value['nombre']."\");";
        		}
        	}
            $php = $php."Session::getInstance()->set(\$this->namefilter, \$parramsfilter);
        }
        if (\$this->_getSanitizedParam(\"cleanfilter\") == 1) {
            Session::getInstance()->set(\$this->namefilter, '');
            Session::getInstance()->set(\$this->namepageactual,1);
        }
    }
}";
		fwrite($nuevoarchivo,$php);
		fclose($nuevoarchivo);
	}


	public function crearModelo($data,$campos){
		$controlador = strtolower($this->_getSanitizedParam('controlador'));
		$modelo = strtolower($this->_getSanitizedParam("ruta"));
		$table = strtolower($this->_getSanitizedParam("table"));
		$identificador = $this->getKey($campos)->Field;
		$ruta = APP_PATH."/modules/".$modelo."/Models/DbTable/".ucwords($controlador).".php";
		$nuevoarchivo = fopen($ruta, "w+");
		$titulolistar = $this->_getSanitizedParam('titulo_listado'); 
		$tituloeditar = $this->_getSanitizedParam('titulo_edicion');
		$php = "<?php 
/**
* clase que genera la insercion y edicion  de ".$titulolistar." en la base de datos
*/
class ".ucwords($modelo)."_Model_DbTable_".ucwords($controlador)." extends Db_Table
{
	/**
	 * [$_name nombre de la tabla actual]
	 * @var string
	 */
	protected \$_name = '".$table."';

	/**
	 * [$_id identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected \$_id = '".$identificador."';

	/**
	 * insert recibe la informacion de un ".$tituloeditar." y la inserta en la base de datos
	 * @param  array $data array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert(\$data){";
		$campos = "";
		$variables = "";
		foreach ($data as $key => $value) {
			if($value['ordenar']!= 1){
				
		$php= $php."
		\$".$value['nombre']." = \$data['".$value['nombre']."'];";
			$campos =  $campos." ".$value['nombre'].",";
			$variables =  $variables." "."'\$".$value['nombre']."',";
			}
		}
		$campos = substr($campos, 0, -1);
		$variables = substr($variables, 0, -1);
		$php= $php."
		\$query = \"INSERT INTO ".$table."(".$campos.") VALUES (".$variables.")\";
		\$res = \$this->_conn->query(\$query);
        return mysqli_insert_id(\$this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un ".$tituloeditar."  y actualiza la informacion en la base de datos
	 * @param  array $data Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer $id   identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update(\$data,\$id){
		";
		$campos = "";
		foreach ($data as $key => $value) {
			if($value['ordenar']!= 1){
		$php= $php."
		\$".$value['nombre']." = \$data['".$value['nombre']."'];";
			$campos = $campos." ".$value['nombre']." = '\$".$value['nombre']."',";
			}
		}
		$campos = substr($campos, 0, -1);
	$php= $php."
		\$query = \"UPDATE ".$table." SET ".$campos." WHERE ".$identificador." = '\".\$id.\"'\";
		\$res = \$this->_conn->query(\$query);
	}
}";
fwrite($nuevoarchivo,$php);
		fclose($nuevoarchivo);
	}

	public function crearModelodependiente($name,$campos){
		$controlador = str_replace("_","","depend_".$name);
		$modelo = strtolower($this->_getSanitizedParam("ruta"));
		if(!file_exists(APP_PATH."/modules/".$modelo."/Models/DbTable/".ucwords($controlador).".php")){
		$identificador = $this->getKey($campos)->Field;
		$ruta = APP_PATH."/modules/".$modelo."/Models/DbTable/".ucwords($controlador).".php";
		$nuevoarchivo = fopen($ruta, "w+");
		$titulolistar = $this->_getSanitizedParam('titulo_listado'); 
		$tituloeditar = $this->_getSanitizedParam('titulo_edicion');
		$php = "<?php 
/**
* clase que genera la clase dependiente  de ".$titulolistar." en la base de datos
*/
class ".ucwords($modelo)."_Model_DbTable_".ucwords($controlador)." extends Db_Table
{
	/**
	 * [$_name nombre de la tabla actual]
	 * @var string
	 */
	protected \$_name = '".$name."';

	/**
	 * [$_id identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected \$_id = '".$identificador."';

}";
fwrite($nuevoarchivo,$php);
		fclose($nuevoarchivo);

}

}

public function coloresfondo(){
	if(count($this->arrayfondo)==0){
		$this->arrayfondo [0] ="fondo-azul";
		$this->arrayfondo [1] ="fondo-azul-claro";
		$this->arrayfondo [2] ="fondo-verde";
		$this->arrayfondo [3] ="fondo-verde-claro";
		$this->arrayfondo [4] ="fondo-rojo";
		$this->arrayfondo [4] ="fondo-rojo-claro";
		$this->arrayfondo [5] ="fondo-morado";
		$this->arrayfondo [6] ="fondo-rosado";
		$this->arrayfondo [7] ="fondo-cafe";
	}
	$posicion = array_rand($this->arrayfondo);
	$fondo = $this->arrayfondo[$posicion];
	unset($this->arrayfondo[$posicion]);
	return $fondo;
}


	public function crearVistaindex($data,$campos){
		$controlador = strtolower($this->_getSanitizedParam('controlador'));
		$modelo = strtolower($this->_getSanitizedParam("ruta"));
		$table = strtolower($this->_getSanitizedParam("table"));
		$identificador = $this->getKey($campos)->Field;
		$carpeta = APP_PATH."/modules/".$modelo."/Views/".$controlador."/";
		$ruta = APP_PATH."/modules/".$modelo."/Views/".$controlador."/index.php";
		if (!file_exists($carpeta)) {
		    mkdir($carpeta, 0777, true);
		}
		$nuevoarchivo = fopen($ruta, "w+");
		$titulolistar = $this->_getSanitizedParam('titulo_listado');
		$tituloeditar = $this->_getSanitizedParam('titulo_edicion');
		$camposocultos = $this->getampooculto($data);
		$filtro='';
		$filtro2="";
		foreach ($data as $key => $value) {
			if ($value['tipo'] == 7 && $value['oculto_tipo']==2 ) {
				if($filtro==''){
					$filtro=$filtro.".\"?".$value['oculto']."=\".\$this->".$value['oculto'].".\"\"";
				} else {
					$filtro=$filtro.".\"&".$value['oculto']."=\".\$this->".$value['oculto'].".\"\"";
				}

				$filtro2=$filtro2.".'&".$value['oculto']."='.\$this->".$value['oculto']."";
			}
		}
			$php ="<h1 class=\"titulo-principal\"><i class=\"fas fa-cogs\"></i> <?php echo \$this->titlesection; ?></h1>
<div class=\"container-fluid\">";
	$php =$php."
	<form action=\"<?php echo \$this->route".$filtro."; ?>\" method=\"post\">
        <div class=\"content-dashboard\">
            <div class=\"row\">";
            foreach ($data as $key => $value) {
				if($value['en_listado'] == 1){
				if($value['tipo'] == 3 || $value['tipo'] == 9 ){
				$php = $php."
				<div class=\"col-3\">
					<label>".$value['titulo']."</label>
	                <label class=\"input-group\">
						<div class=\"input-group-prepend\">
							<span class=\"input-group-text input-icono ".$this->coloresfondo()." \" ><i class=\"far fa-list-alt\"></i></span>
						</div>
	                    <select class=\"form-control\" name=\"".$value['nombre']."\">
	                        <option value=\"\">Todas</option>
	                        <?php foreach (\$this->list_".$value['nombre']." as \$key => \$value) : ?>
	                            <option value=\"<?= \$key; ?>\" <?php if (\$this->getObjectVariable(\$this->filters, '".$value['nombre']."') ==  \$key) { echo \"selected\";} ?>><?= \$value; ?></option>
	                        <?php endforeach ?>
	                    </select>
	               </label>
	            </div>";
					}  else if($value['tipo_dato'] == "date"){
						$php =$php."
				<div class=\"col-3\">
	                <label>".$value['titulo']."</label>
	                <label class=\"input-group\">
						<div class=\"input-group-prepend\">
							<span class=\"input-group-text input-icono  ".$this->coloresfondo()." \" ><i class=\"fas fa-calendar-alt\"></i></span>
						</div>
	                <input type=\"text\" class=\"form-control\" name=\"".$value['nombre']."\" value=\"<?php echo \$this->getObjectVariable(\$this->filters, '".$value['nombre']."') ?>\"></input>
	                    </label>
	            </div>";
					} else {
						$php =$php."
				<div class=\"col-3\">
		            <label>".$value['titulo']."</label>
		            <label class=\"input-group\">
							<div class=\"input-group-prepend\">
								<span class=\"input-group-text input-icono ".$this->coloresfondo()." \" ><i class=\"fas fa-pencil-alt\"></i></span>
							</div>
		            <input type=\"text\" class=\"form-control\" name=\"".$value['nombre']."\" value=\"<?php echo \$this->getObjectVariable(\$this->filters, '".$value['nombre']."') ?>\"></input>
		            </label>
		        </div>";
					}
            	}
            }
                $php =$php."
                <div class=\"col-3  d-flex align-items-end \">
                    <label>&nbsp;</label>
                    <button type=\"submit\" class=\"btn btn-block btn-azul\"> <i class=\"fas fa-filter\"></i> Filtrar</button>
                </div>
                <div class=\"col-3  d-flex align-items-end \">
                    <label>&nbsp;</label>
                    <a class=\"btn btn-block btn-azul-claro \" href=\"<?php echo \$this->route; ?>?cleanfilter=1\" > <i class=\"fas fa-eraser\"></i> Limpiar Filtro</a>
                </div>
            </div>
        </div>
    </form>";

    $php = $php."
    <div align=\"center\">
		<ul class=\"pagination justify-content-center\">
	    <?php
	    	\$url = \$this->route;
	        if (\$this->totalpages > 1) {
	            if (\$this->page != 1)
	                echo '<li class=\"page-item\" ><a class=\"page-link\"  href=\"'.\$url.'?page='.(\$this->page-1)".$filtro2.".'\"> &laquo; Anterior </a></li>';
	            for (\$i=1;\$i<=\$this->totalpages;\$i++) {
	                if (\$this->page == \$i)
	                    echo '<li class=\"active page-item\"><a class=\"page-link\">'.\$this->page.'</a></li>';
	                else
	                    echo '<li class=\"page-item\"><a class=\"page-link\" href=\"'.\$url.'?page='.\$i".$filtro2.".'\">'.\$i.'</a></li>  ';
	            }
	            if (\$this->page != \$this->totalpages)
	                echo '<li class=\"page-item\"><a class=\"page-link\" href=\"'.\$url.'?page='.(\$this->page+1)".$filtro2.".'\">Siguiente &raquo;</a></li>';
	        }
	  	?>
	  	</ul>
	</div>
	<div class=\"content-dashboard\">
	    <div class=\"franja-paginas\">
		    <div class=\"row\">
		    	<div class=\"col-5\">
		    		<div class=\"titulo-registro\">Se encontraron <?php echo \$this->register_number; ?> Registros</div>
		    	</div>
		    	<div class=\"col-3 text-right\">
		    		<div class=\"texto-paginas\">Registros por pagina:</div>
		    	</div>
		    	<div class=\"col-1\">
		    		<select class=\"form-control form-control-sm selectpagination\">
		    			<option value=\"20\" <?php if(\$this->pages == 20){ echo 'selected'; } ?>>20</option>
		    			<option value=\"30\" <?php if(\$this->pages == 30){ echo 'selected'; } ?>>30</option>
		    			<option value=\"50\" <?php if(\$this->pages == 50){ echo 'selected'; } ?>>50</option>
		    			<option value=\"100\" <?php if(\$this->pages == 100){ echo 'selected'; } ?>>100</option>
		    		</select>
		    	</div>
		    	<div class=\"col-3\">
		    		<div class=\"text-right\"><a class=\"btn btn-sm btn-success\" href=\"<?php echo \$this->route.\"\manage\"".$filtro."; ?>\"> <i class=\"fas fa-plus-square\"></i> Crear Nuevo</a></div>
		    	</div>
		    </div>
	    </div>
		<div class=\"content-table table-responsive\">
		<table class=\" table table-striped  table-hover table-administrator text-left\">
			<thead>
				<tr>";
				foreach ($data as $key => $value) {
					if($value['en_listado'] == 1){
						$php = $php."
					<td>".$value['titulo']."</td>";
					}
				}
				if($this->getorden($data) == "orden ASC"){
					$php = $php."
					<td width=\"100\">Orden</td>";
				}
				$php = $php."
					<td width=\"100\"></td>
				</tr>
			</thead>
			<tbody>
				<?php foreach(\$this->lists as \$content){ ?>
				<?php \$id =  \$content->".$identificador."; ?>
					<tr>";
						foreach ($data as $key => $value) {
							if($value['en_listado'] == 1){
								if($value['tipo'] == 3 || $value['tipo'] == 9){
									$php = $php."
						<td><?= \$this->list_".$value['nombre']."[\$content->".$value['nombre']."];?>";
								} else if($value['tipo'] == 5 ){
									$php = $php."
						<td>
							<?php if(\$content->".$value['nombre'].") { ?>
								<img src=\"/images/<?= \$content->".$value['nombre']."; ?>\"  class=\"img-thumbnail thumbnail-administrator\" />
							<?php } ?>
							<div><?= \$content->".$value['nombre']."; ?></div>
						</td>";
								} else {
									$php = $php."
						<td><?=\$content->".$value['nombre'].";?></td>";
								}
								
							}
						}
						if($this->getorden($data) == "orden ASC"){
							$php = $php."
						<td>
							<input type=\"hidden\" id=\"<?= \$id; ?>\" value=\"<?= \$content->orden; ?>\"></input>
							<button class=\"up_table btn btn-primary btn-sm\"><i class=\"fas fa-angle-up\"></i></button>
							<button class=\"down_table btn btn-primary btn-sm\"><i class=\"fas fa-angle-down\"></i></button>
						</td>";
						}

						$php = $php."
						<td class=\"text-right\">
							<div>
								<a class=\"btn btn-azul btn-sm\" href=\"<?php echo \$this->route;?>/manage?id=<?= \$id ?>\"  data-bs-toggle=\"tooltip\" data-placement=\"top\" title=\"Editar\"><i class=\"fas fa-pen-alt\"></i></a>
								<span  data-bs-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\"><a class=\"btn btn-rojo btn-sm\"  data-bs-toggle=\"modal\" data-bs-target=\"#modal<?= \$id ?>\"  ><i class=\"fas fa-trash-alt\" ></i></a></span>
							</div>
							<!-- Modal -->
							<div class=\"modal fade text-left\" id=\"modal<?= \$id ?>\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\">
							  	<div class=\"modal-dialog modal-dialog-centered\" role=\"document\">
							    	<div class=\"modal-content\">
							      		<div class=\"modal-header\">
							        		<h4 class=\"modal-title\" id=\"myModalLabel\">Eliminar Registro</h4>
							        		<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
							      	</div>
							      	<div class=\"modal-body\">
							        	<div class=\"\">¿Esta seguro de eliminar este registro?</div>
							      	</div>
								      <div class=\"modal-footer\">
								        	<button type=\"button\" class=\"btn btn-default\" data-bs-dismiss=\"modal\">Cancelar</button>
								        	<a class=\"btn btn-danger\" href=\"<?php echo \$this->route;?>/delete?id=<?= \$id ?>&csrf=<?= \$this->csrf;?><?php echo ''".$filtro2."; ?>\" >Eliminar</a>
								      </div>
							    	</div>
							  	</div>
							</div>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<input type=\"hidden\" id=\"csrf\" value=\"<?php echo \$this->csrf ?>\">";
	if($this->getorden($data) == "orden ASC"){
	$php = $php."<input type=\"hidden\" id=\"order-route\" value=\"<?php echo \$this->route; ?>/order\">";
	}
	$php = $php."<input type=\"hidden\" id=\"page-route\" value=\"<?php echo \$this->route; ?>/changepage\">
	</div>
	 <div align=\"center\">
		<ul class=\"pagination justify-content-center\">
	    <?php
	    	\$url = \$this->route;
	        if (\$this->totalpages > 1) {
	            if (\$this->page != 1)
	                echo '<li class=\"page-item\"><a class=\"page-link\" href=\"'.\$url.'?page='.(\$this->page-1)".$filtro2.".'\"> &laquo; Anterior </a></li>';
	            for (\$i=1;\$i<=\$this->totalpages;\$i++) {
	                if (\$this->page == \$i)
	                    echo '<li class=\"active page-item\"><a class=\"page-link\">'.\$this->page.'</a></li>';
	                else
	                    echo '<li class=\"page-item\"><a class=\"page-link\" href=\"'.\$url.'?page='.\$i".$filtro2.".'\">'.\$i.'</a></li>  ';
	            }
	            if (\$this->page != \$this->totalpages)
	                echo '<li class=\"page-item\"><a class=\"page-link\" href=\"'.\$url.'?page='.(\$this->page+1)".$filtro2.".'\">Siguiente &raquo;</a></li>';
	        }
	  	?>
	  	</ul>
	</div>
</div>";
		fwrite($nuevoarchivo,$php);
		fclose($nuevoarchivo);
	}

	public function crearVistamanage($data,$campos){
		$this->arrayfondo = [];
		$controlador = strtolower($this->_getSanitizedParam('controlador'));
		$modelo = strtolower($this->_getSanitizedParam("ruta"));
		$table = strtolower($this->_getSanitizedParam("table"));
		$identificador = $this->getKey($campos)->Field;
		$carpeta = APP_PATH."/modules/".$modelo."/Views/".$controlador."/";
		$ruta = APP_PATH."/modules/".$modelo."/Views/".$controlador."/manage.php";
		if (!file_exists($carpeta)) {
		    mkdir($carpeta, 0777, true);
		}
		$nuevoarchivo = fopen($ruta, "w+");
		$titulolistar = $this->_getSanitizedParam('titulo_listado');
		$tituloeditar = $this->_getSanitizedParam('titulo_edicion');

		$urlcancel = "";

		$php = "<h1 class=\"titulo-principal\"><i class=\"fas fa-cogs\"></i> <?php echo \$this->titlesection; ?></h1>
<div class=\"container-fluid\">
	<form class=\"text-left\" enctype=\"multipart/form-data\" method=\"post\" action=\"<?php echo \$this->routeform;?>\"  data-bs-toggle=\"validator\">
		<div class=\"content-dashboard\">
			<input type=\"hidden\" name=\"csrf\" id=\"csrf\" value=\"<?php echo \$this->csrf ?>\">
			<input type=\"hidden\" name=\"csrf_section\" id=\"csrf_section\" value=\"<?php echo \$this->csrf_section ?>\">
			<?php if (\$this->content->".$identificador.") { ?>
				<input type=\"hidden\" name=\"id\" id=\"id\" value=\"<?= \$this->content->".$identificador."; ?>\" />
			<?php }?>
			<div class=\"row\">";
	foreach ($data as $key => $value) {
		if($value['ordenar']!= 1){
			if($value['requerido'] == 1){
				$requerido = "required";
			} else {
				$requerido = "";
			}
		if($value['tipo'] == 7){
			if($value['oculto_tipo']==2){
					$php = $php."
				<input type=\"hidden\" name=\"".$value['nombre']."\"  value=\"<?php if(\$this->content->".$value['nombre']."){ echo \$this->content->".$value['nombre']."; } else { echo \$this->".$value['oculto']."; } ?>\">";
		if($urlcancel==''){
			$urlcancel="?";
		} else {
			$urlcancel=$urlcancel."&";
		}
		$urlcancel=$urlcancel.$value['oculto']."=<?php if(\$this->content->".$value['nombre']."){ echo \$this->content->".$value['nombre']."; } else { echo \$this->".$value['oculto']."; } ?>";

			} else{
					$php = $php."
				<input type=\"hidden\" name=\"".$value['nombre']."\"  value=\"<?php echo \$this->content->".$value['nombre']." ?>\">";
			}
		
		} else if($value['tipo'] == 6 ){
			if($requerido == 'required'){
				$requireddocument = "if(!\$this->content->".$identificador."){ echo 'required'; }";
			}
			$php = $php."
				<div class=\"".$value['ancho']." form-group\">
					<label for=\"".$value['nombre']."\" >".$value['titulo']."</label>
					<input type=\"file\" name=\"".$value['nombre']."\" id=\"".$value['nombre']."\" class=\"form-control  file-document\" data-buttonName=\"btn-primary\" onchange=\"validardocumento('".$value['nombre']."');\" accept=\"application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf\" ".$requireddocument.">
					<div class=\"help-block with-errors\"></div>
				</div>";
		} else if($value['tipo'] == 5 ){

			if($requerido == 'required'){
				$requiredimage = "<?php if(!\$this->content->".$identificador."){ echo 'required'; } ?>";
			}
			$php = $php."
				<div class=\"".$value['ancho']." form-group\">
					<label for=\"".$value['nombre']."\" >".$value['titulo']."</label>
					<input type=\"file\" name=\"".$value['nombre']."\" id=\"".$value['nombre']."\" class=\"form-control  file-image\" data-buttonName=\"btn-primary\" accept=\"image/gif, image/jpg, image/jpeg, image/png\"  ".$requiredimage.">
					<div class=\"help-block with-errors\"></div>
					<?php if(\$this->content->".$value['nombre'].") { ?>
						<div id=\"imagen_".$value['nombre']."\">
							<img src=\"/images/<?= \$this->content->".$value['nombre']."; ?>\"  class=\"img-thumbnail thumbnail-administrator\" />
							<div><button class=\"btn btn-danger btn-sm\" type=\"button\" onclick=\"eliminarImagen('".$value['nombre']."','<?php echo \$this->route.\"/deleteimage\"; ?>')\"><i class=\"glyphicon glyphicon-remove\" ></i> Eliminar Imagen</button></div>
						</div>
					<?php } ?>
				</div>";
		} else if($value['tipo'] == 4 ){
			$php = $php."
		<div class=\"".$value['ancho']." form-group\">
			<label   class=\"control-label\">".$value['titulo']."</label>
				<input type=\"checkbox\" name=\"".$value['nombre']."\" value=\"1\" class=\"form-control switch-form \" <?php if (\$this->getObjectVariable(\$this->content, '".$value['nombre']."') == 1) { echo \"checked\";} ?>  ".$requerido." ></input>
				<div class=\"help-block with-errors\"></div>
		</div>";
		}  else if($value['tipo'] == 3 ||  $value['tipo'] == 9 ){
			$php = $php."
				<div class=\"".$value['ancho']." form-group\">
					<label class=\"control-label\">".$value['titulo']."</label>
					<label class=\"input-group\">
						<div class=\"input-group-prepend\">
							<span class=\"input-group-text input-icono  ".$this->coloresfondo()." \" ><i class=\"far fa-list-alt\"></i></span>
						</div>
						<select class=\"form-control\" name=\"".$value['nombre']."\"  ".$requerido." >
							<option value=\"\">Seleccione...</option>
							<?php foreach (\$this->list_".$value['nombre']." AS \$key => \$value ){?>
								<option <?php if(\$this->getObjectVariable(\$this->content,\"".$value['nombre']."\") == \$key ){ echo \"selected\"; }?> value=\"<?php echo \$key; ?>\" /> <?= \$value; ?></option>
							<?php } ?>
						</select>
					</label>
					<div class=\"help-block with-errors\"></div>
				</div>";
		}  else if($value['tipo'] == 8){
			$php= $php."
				<div class=\"".$value['ancho']." form-group\">
					<label for=\"".$value['nombre']."\" class=\"form-label\" >".$value['titulo']."</label>
					<textarea name=\"".$value['nombre']."\" id=\"".$value['nombre']."\"   class=\"form-control\" rows=\"10\"  ".$requerido." ><?= \$this->content->".$value['nombre']."; ?></textarea>
					<div class=\"help-block with-errors\"></div>
				</div>";
		} else if($value['tipo'] == 2 ){
			$php= $php."
				<div class=\"".$value['ancho']." form-group\">
					<label for=\"".$value['nombre']."\" class=\"form-label\" >".$value['titulo']."</label>
					<textarea name=\"".$value['nombre']."\" id=\"".$value['nombre']."\"   class=\"form-control tinyeditor\" rows=\"10\"  ".$requerido." ><?= \$this->content->".$value['nombre']."; ?></textarea>
					<div class=\"help-block with-errors\"></div>
				</div>";
		} else {
			if($value['tipo_dato'] == "date"){
				$php = $php."
				<div class=\"".$value['ancho']." form-group\">
					<label for=\"".$value['nombre']."\"  class=\"control-label\">".$value['titulo']."</label>
					<label class=\"input-group\">
						<div class=\"input-group-prepend\">
							<span class=\"input-group-text input-icono  ".$this->coloresfondo()." \" ><i class=\"fas fa-calendar-alt\"></i></span>
						</div>
					<input type=\"text\" value=\"<?php if(\$this->content->".$value['nombre']."){ echo \$this->content->".$value['nombre']."; } else { echo date('Y-m-d'); } ?>\" name=\"".$value['nombre']."\" id=\"".$value['nombre']."\" class=\"form-control\"  ".$requerido." data-provide=\"datepicker\" data-date-format=\"yyyy-mm-dd\" data-date-language=\"es\"  >
					</label>
					<div class=\"help-block with-errors\"></div>
				</div>";
			} else {
				$php = $php."
				<div class=\"".$value['ancho']." form-group\">
					<label for=\"".$value['nombre']."\"  class=\"control-label\">".$value['titulo']."</label>
					<label class=\"input-group\">
						<div class=\"input-group-prepend\">
							<span class=\"input-group-text input-icono  ".$this->coloresfondo()." \" ><i class=\"fas fa-pencil-alt\"></i></span>
						</div>
						<input type=\"text\" value=\"<?= \$this->content->".$value['nombre']."; ?>\" name=\"".$value['nombre']."\" id=\"".$value['nombre']."\" class=\"form-control\"  ".$requerido." >
					</label>
					<div class=\"help-block with-errors\"></div>
				</div>";
			}
			
		}
		}
	}

	$php = $php."
			</div>
		</div>
		<div class=\"botones-acciones\">
			<button class=\"btn btn-guardar\" type=\"submit\">Guardar</button>
			<a href=\"<?php echo \$this->route; ?>".$urlcancel."\" class=\"btn btn-cancelar\">Cancelar</a>
		</div>
	</form>
</div>";
		fwrite($nuevoarchivo,$php);
		fclose($nuevoarchivo);

	}


	public function getorden($data){
		foreach ($data as $key => $value) {
			if($value['ordenar']== 1){
				return $value['nombre']." ASC";
			}
		}
		return "";
	}

	public function getKey($campos){
		foreach ($campos as $key => $campo) {
			if($campo->Key == 'PRI'){
				return $campo;
			} 
		}
		return false;
	}

	public function getFilesimages($data){
		$images = [];
		foreach ($data as $key => $value) {
			if($value['tipo'] == 5){
				array_push($images,$value['nombre']);
			}
		}
		return $images;
	}
	public function getampooculto($data){
		$camposocultos = [];
		foreach ($data as $key => $value) {
			if($value['tipo'] == 7){
				array_push($camposocultos,$value);
			}
		}
		return $camposocultos;
	}

	public function getFilesdocument($data){
		$document = [];
		foreach ($data as $key => $value) {
			if($value['tipo'] == 6){
				array_push($document,$value['nombre']);
			}
		}
		return $document;
	}


	public function getdatatableAction(){
		header('Content-Type:application/json');
		$modelTables = new Generator_Model_DbTable_Tables();
		$table = $this->_getSanitizedParam("table");
		$data = $modelTables->getCampos($table);
		$campos = [];
		$campos['campo'] = $this->_getSanitizedParam("campo");
		$campos['data'] = [];
		foreach ($data as $key => $value) {
			array_push($campos['data'],$value->Field);
		}
		echo json_encode($campos);
	}
}