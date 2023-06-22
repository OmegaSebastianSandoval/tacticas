<?php

/**
 *
 */

class Page_loginController extends Controllers_Abstract
{
  protected $_csrf_section = "login_admin";
  public $csrf;
  public function init()
  {


    $this->setLayout('page_page');

    $this->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
    parent::init();
  }

  public function indexAction()
  {
    if ((Session::getInstance()->get("kt_login_id") != '' || Session::getInstance()->get("kt_login_id", "") != '')) {
      header('Location: /page/panel');
    }
    $title = "Login del sistema";
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
    $user = $this->_getSanitizedParam("user");
    $password = $this->_getSanitizedParam("password");
    $csrf = $this->_getSanitizedParam("csrf");
    $isError = false;
    $busco = "no";
    $error = 0;


    if ($isPost == true && $user && $password && $this->csrf == $csrf) {
      $usuariosModel = new Core_Model_DbTable_Usuarios();
      $resUser = $usuariosModel->searchUserByUser($user);
      if ($resUser->activo == 1) {
        if ($resUser->clave_act == "" && $resUser->clave_principal == "") {
          header("Location: /page/login/actualizar?id=" . $resUser->id . "&img=" . $img);
          exit();
        } else {
          if ($usuariosModel->autenticateUser($user, $password) == true) {




            Session::getInstance()->set("kt_login_id", $resUser->id);
            Session::getInstance()->set("kt_login_level", $resUser->nivel);
            Session::getInstance()->set("kt_login_user", $resUser->usuario);
            Session::getInstance()->set("kt_login_name", $resUser->nombre);

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
            echo $user;
            echo $password;
            $isError = true;
            $error = 2;
            //  $errorText = "El Usuario o Contraseña son incorrectos.";
          }
        }
      } else {
        $isError = true;
        $error = 3;
        //   $errorText = "El usuario se encuentra inactivo.";
      }
    } else {
      $isError = true;
      $error = 1;
      //$errorText = "Lo sentimos ocurrio un error intente de nuevo.";
    }

    if ($isError == false) {
      header("Location: /page/panel");
    } else {
       header('Location: /page/login?img=' . $img . '&error=' . $error);
    }
  }
  public function actualizarAction()
  {
    $csrf2 = Session::getInstance()->get('csrf')['login_admin'];

    $this->_view->csrf2 = $csrf2;
    $this->getLayout()->setTitle('Actualizar contraseña');
    $this->_view->img = $img = $this->_getSanitizedParam("img");



    $password_new = $this->_getSanitizedParam("password_new");

    $csrf = $this->_getSanitizedParam("csrf");
    $isPost = $this->getRequest()->isPost();

    $this->_view->id = $id = $this->_getSanitizedParam("id");
    /*   $usuariosModel = new Core_Model_DbTable_Usuarios();
    $user = $usuariosModel->getById($id);
    print_r($user);
    echo $id; */


    if ($isPost == true && $password_new && $this->csrf == $csrf) {
      $id = $this->_getSanitizedParam("id");
      $usuariosModel = new Core_Model_DbTable_Usuarios();
      $usuariosModel->changePassword($id, $password_new);
      $resUser = $usuariosModel->getById($id);
      $usuariosModel->editField($id, "clave_act", 1);

      Session::getInstance()->set("kt_login_id", $resUser->id);
      Session::getInstance()->set("kt_login_level", $resUser->nivel);
      Session::getInstance()->set("kt_login_user", $resUser->usuario);
      Session::getInstance()->set("kt_login_name", $resUser->nombre);
      header("Location: /page/panel");
    } else {
      // header('Location: /page/login?img=' . $img . '&error=' . $error);

      $this->_view->id = $this->_getSanitizedParam("id");
      $this->_view->csrf2 = $csrf2;
    }
  }

  public function recuperarAction()
  {

    $title = "¿Olvidaste tu contraseña?";
    $this->getLayout()->setTitle($title);
    $this->_view->titlesection = $title;
    $footer = $this->_view->getRoutPHP('modules/page/Views/partials/footer.php');
    $this->getLayout()->setData("footer", $footer);
    $this->_view->error =  $error = $this->_getSanitizedParam("error");
  }

  public function forgotpasswordAction()
  {
    $this->setLayout('blanco');
    $this->_csrf_section = "login_admin";
    $modelUser = new Core_Model_DbTable_Usuarios();
    $correo = $this->_getSanitizedParam("correo");

    $error = 3;
    $filter = " email = '" . $correo . "' ";

    $user = $modelUser->getList($filter, "")[0];
    $id = $user->id;

    if ($user) {
      $sendingemail = new Core_Model_Sendingemail($this->_view);
      $hash = md5("omega" . $id);
      /*       $code = base64_encode($id);
      $code = str_replace("=", "_", $code);
      echo $code;
      echo $hash;
 */
      $modelUser->editCode($id, $hash);
      $user = $modelUser->getById($user->id);

      if ($sendingemail->forgotpasswordUsuarios($user) == true) {
        $error = false;
        $error = 1;
      } else {
        $error = 2;
      }
    }
    header('Location: /page/login/recuperar?error=' . $error);
  }
  public function changepasswordAction()
  {

    $this->getLayout()->setTitle("Cambiar Contraseña");
    $user = $this->validarCodigo();

    if (isset($user['error'])) {
      if ($user['error'] == 1) {
        $this->_view->error = "Lo sentimos este código ya fue utilizado.";
      } else {
        $this->_view->error = "La información suministrada es inválida.";
      }
    } else {
      $this->_view->usuario = $user['user']->usuario;
      new Core_Model_Csrf('nueva_contrasena');
      $csrf = Session::getInstance()->get('csrf')['nueva_contrasena'];
      $password = $this->_getSanitizedParam("password");
      $repassword = $this->_getSanitizedParam("repassword");

      if ($this->getRequest()->isPost() == true && $password == $repassword) {

        $id_user = $user['user']->id;
        $modelUser = new Core_Model_DbTable_Usuarios();
        $modelUser->changePassword($id_user, $password);
        $modelUser->editCode($id_user, $csrf);
        $this->_view->message = "Sea cambiado su contraseña satisfactoriamente.";
      } else {


        $this->_view->code = $this->_getSanitizedParam("code");
        $this->_view->usuario = $user['user']->usuario;
        $this->_view->csrf = $this->_getSanitizedParam("csrf");
      }
    }
  }
  public function cambiarAction()
  {

    $this->setLayout('blanco');


    $isPost = $this->getRequest()->isPost();
    $this->_view->img = $img = $this->_getSanitizedParam("img");
    $code = $this->_getSanitizedParam("code");
    $user = $this->_getSanitizedParam("user");

    $password = $this->_getSanitizedParam("password");
    $repassword = $this->_getSanitizedParam("repassword");

    $csrf = $this->_getSanitizedParam("csrf");
    $isError = false;
    $busco = "no";
    $error = 0;
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
    header('Location: /page/');
  }
  protected function validarCodigo()
  {
    $res = [];
    $code =  base64_decode($this->_getSanitizedParam("code"));
    if (isset($code) && $this->isJson($code) == true) {
      $code = json_decode($code, true);
      $modelUser = new Core_Model_DbTable_Usuarios();
      if (isset($code['user'])) {
        $user = $modelUser->getById($code['user']);
        if (isset($user->id)) {
          if ($user->code == $code['code']) {
            $res['user'] = $user;
          } else {
            $res['error'] =  1;
            $res['user'] = $user;
          }
        } else {
          $res['error'] =  2;
        }
      } else {
        $res['error'] =  3;
      }
    } else {
      $res['error'] =  4;
    }
    return $res;
  }
  /**
   * verifica si una cadena es de tipo json
   * @param  string  $string cadena a evaluar
   * @return boolean    resultado de la evaluacion
   */
  private function isJson($string)
  {
    return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
  }
}
