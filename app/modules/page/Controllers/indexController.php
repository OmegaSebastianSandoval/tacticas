<?php

/**
 *
 */

class Page_indexController extends Controllers_Abstract
{




	public function init()
	{

		$this->setLayout('page_page');
  }
  public function indexAction()
  {


    if ((Session::getInstance()->get("kt_login_id") != '' || Session::getInstance()->get("kt_login_id", "") != '')) {
			header('Location: /page/panel');
		}

    $title = "Tácticas Panama";
    $this->getLayout()->setTitle($title);
    $this->_view->titlesection = $title;

		$footer = $this->_view->getRoutPHP('modules/page/Views/partials/footer.php');
		$this->getLayout()->setData("footer", $footer);
    $empresasModel = new Page_Model_DbTable_Empresas();
    $this->_view->empresas = $empresasModel->getList();
  }
}
