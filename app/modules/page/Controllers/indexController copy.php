<?php

/**
 *
 */

class Page_indexController extends Controllers_Abstract
{


  public function indexAction()
  {
    $title = "Tácticas Panama";
    $this->getLayout()->setTitle($title);
    $this->_view->titlesection = $title;
   
    $empresasModel = new Page_Model_DbTable_Empresas();
    $this->_view->empresas = $empresasModel->getList();
  }
  public function homeAction()
  {
    $title = "Tácticas Panama";
    $this->getLayout()->setTitle($title);
    $this->_view->titlesection = $title;
   
    $empresasModel = new Page_Model_DbTable_Empresas();
    $this->_view->empresas = $empresasModel->getList();
  }
}
