<?php

/**
 *
 */

class Page_loginempleadosController extends Controllers_Abstract
{
    protected $_csrf_section = "login_empleados";
    public $csrf;
    public function init()
    {


        $this->setLayout('page_page');

        $this->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
        parent::init();
    }

    public function indexAction()
    {
        $title = "Login del sistema para empleados";
        $this->getLayout()->setTitle($title);
        $this->_view->titlesection = $title;
        $this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
        $footer = $this->_view->getRoutPHP('modules/page/Views/partials/footer.php');
        $this->getLayout()->setData("footer", $footer);
        $this->_view->img = $img = $this->_getSanitizedParam("img");
        $this->_view->error = $error = $this->_getSanitizedParam("error");
    }
    public function validarAction()
    {
        Session::getInstance()->set("error_login", "");
        $this->setLayout('blanco');


        $isPost = $this->getRequest()->isPost();
        $this->_view->img = $img = $this->_getSanitizedParam("img");
        $cedula = $this->_getSanitizedParam("cedula");

        $csrf = $this->_getSanitizedParam("csrf");
        $isError = false;
        $busco = "no";
        $error = 0;


        if ($isPost == true && $cedula && $this->csrf == $csrf) {
            $hojadevidaModel = new Page_Model_DbTable_Hojadevida();
            $resUser = $hojadevidaModel->getList(" documento='$cedula'")[0];
            if ($resUser) {
                if ($resUser->retirado == 0) {






                    Session::getInstance()->set("kt_login_id", $resUser->id);
                    Session::getInstance()->set("kt_login_level", 4);
                    Session::getInstance()->set("kt_login_user", $resUser->nombres . " " . $resUser->apellidos);
                    Session::getInstance()->set("kt_login_name", $resUser->nombres . " " . $resUser->apellidos);
                    Session::getInstance()->set("kt_login_empresa", $resUser->empresa);
                    Session::getInstance()->set("kt_login_cedula", $resUser->documento);


                    Session::getInstance()->set("kt_login_asignacion", 0);
                    /*  // start a session 
            session_start();
            // initialize session variables 
            $_SESSION['kt_login_id'] = $resUser->id;
            $_SESSION['kt_login_level'] = $resUser->nivel;
            $_SESSION['kt_login_user'] = $resUser->usuario;
            $_SESSION['kt_login_name'] = $resUser->nombre; */


                    //LOG
                    $data['log_tipo'] = "LOGIN";
                    $data['log_usuario'] = $resUser->usuario;
                    $logModel = new Administracion_Model_DbTable_Log();
                    $logModel->insert($data);
                } else {

                    $isError = true;
                    $error = 2;
                    //  $errorText = "El Usuario o Contraseña son incorrectos.";
                }
            } else {

                $isError = true;
                $error = 1;
                //  $errorText = "El Usuario o Contraseña son incorrectos.";
            }
        } else {
            $isError = true;
            $error = 3;
        }
        if ($isError == false) {
            $planillaAsignacionModel = new Page_Model_DbTable_PlanillaAsignacion();

            $cc = Session::getInstance()->get("kt_login_cedula");
            /* echo $cc; */
            $existe = $planillaAsignacionModel->getListPlanillas(" cedula = '$cc' AND planilla.cerrada = 1", "id DESC");
            if (count($existe) > 0) {
            header("Location: /page/planilla/reciboempleado");

            } else {
            header("Location: /page/hojadevida/manage?cc=$resUser->id#pills-documentos");

            }
        } else {
            header('Location: /page/loginempleados?&error=' . $error);
        }
    }
    public function logoutAction()
    {
        //LOG
        $data['log_tipo'] = "LOGOUT";
        $logModel = new Administracion_Model_DbTable_Log();
        $logModel->insert($data);

        Session::getInstance()->set("kt_login_id", "");
        Session::getInstance()->set("kt_login_level", "");
        Session::getInstance()->set("kt_login_user", "");
        Session::getInstance()->set("kt_login_name", "");
        Session::getInstance()->set("kt_login_cedula", "");

        header('Location: /page/');
    }
}
