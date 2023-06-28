<?php 
/**
* clase que genera la clase dependiente  de usuarios en la base de datos
*/
class Page_Model_DbTable_Dependempresa extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'empresa';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	
	public function getListAsignacion($filters = '', $order = '')
	{
	  $filter = '';
	  if ($filters != '') {
		$filter = ' WHERE ' . $filters;
	  }
	  $orders = "";
	  if ($order != '') {
		$orders = ' ORDER BY ' . $order;
	  }
	  $select = 'SELECT * FROM ' . $this->_name . ' ' . $filter . ' ' . $orders;
	  $res = $this->_conn->query($select)->fetchAsObject();
	  return $res;
	}
}