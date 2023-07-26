<?php 
/**
* clase que genera la insercion y edicion  de planilla en la base de datos
*/
class Page_Model_DbTable_Planilla extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'planilla';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un planilla y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$empresa = $data['empresa'];
		$cerrada = $data['cerrada'];
		$fecha_cerrada = $data['fecha_cerrada'];
		$limite_horas = $data['limite_horas'];
		$limite_dominicales = $data['limite_dominicales'];
		$query = "INSERT INTO planilla( fecha1, fecha2, empresa, cerrada, fecha_cerrada, limite_horas, limite_dominicales) VALUES ( '$fecha1', '$fecha2', '$empresa', '$cerrada', '$fecha_cerrada', '$limite_horas', '$limite_dominicales')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un planilla  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$empresa = $data['empresa'];
		$cerrada = $data['cerrada'];
		$fecha_cerrada = $data['fecha_cerrada'];
		$limite_horas = $data['limite_horas'];
		$limite_dominicales = $data['limite_dominicales'];
		$query = "UPDATE planilla SET  fecha1 = '$fecha1', fecha2 = '$fecha2', empresa = '$empresa', cerrada = '$cerrada', fecha_cerrada = '$fecha_cerrada', limite_horas = '$limite_horas', limite_dominicales = '$limite_dominicales' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
	public function getListPlanillas($filters = '', $order = '')
	{
	  $filter = '';
	  if ($filters != '') {
		$filter = ' WHERE ' . $filters;
	  }
	  $orders = "";
	  if ($order != '') {
		$orders = ' ORDER BY ' . $order;
	  }
		$select = 'SELECT planilla.*, empresa.nombre AS empresa FROM planilla LEFT JOIN empresa ON planilla.empresa = empresa.id ' . $filter . ' ' . $orders;
	  $res = $this->_conn->query($select)->fetchAsObject();
	  return $res;
	}
  
	public function getListPagesPlanillas($filters = '', $order = '', $page, $amount)
	{
	  $filter = '';
	  if ($filters != '') {
		$filter = ' WHERE ' . $filters;
	  }
	  $orders = "";
	  if ($order != '') {
		$orders = ' ORDER BY ' . $order;
	  }
	  $select = 'SELECT planilla.*, empresa.nombre AS empresa FROM planilla LEFT JOIN empresa ON planilla.empresa = empresa.id ' . $filter . ' ' . $orders . ' LIMIT ' . $page . ' , ' . $amount;
	  $res = $this->_conn->query($select)->fetchAsObject();
	  return $res;
	}
  
}
