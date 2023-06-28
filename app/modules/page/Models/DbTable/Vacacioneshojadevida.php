<?php

/**
 * clase que genera la insercion y edicion  de vacaciones en la base de datos
 */
class Page_Model_DbTable_Vacacioneshojadevida extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'vacaciones';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un vacaciones y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data)
	{
		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$cedula = $data['cedula'];
		$query = "INSERT INTO vacaciones( fecha1, fecha2, cedula) VALUES ( '$fecha1', '$fecha2', '$cedula')";
		$res = $this->_conn->query($query);
		return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un vacaciones  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data, $id)
	{

		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$cedula = $data['cedula'];
		$query = "UPDATE vacaciones SET  fecha1 = '$fecha1', fecha2 = '$fecha2', cedula = '$cedula' WHERE id = '" . $id . "'";
		$res = $this->_conn->query($query);
	}
	public function getVacaciones($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		//   $select = 'SELECT * FROM ' . $this->_name . ' ' . $filter . ' ' . $orders;
		 $select = 'SELECT vacaciones.*, hoja_vida.documento, hoja_vida.empresa, hoja_vida.nombres, hoja_vida.apellidos FROM vacaciones NATURAL JOIN (
		SELECT   cedula, MAX(fecha2) AS fecha2
		FROM     vacaciones
		GROUP BY cedula ) t1 LEFT JOIN hoja_vida ON hoja_vida.id = vacaciones.cedula' .  $filter . ' ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getListPagesVacaciones($filters = '', $order = '', $page, $amount)
	{
	  $filter = '';
	  if ($filters != '') {
		$filter = ' WHERE ' . $filters;
	  }
	  $orders = "";
	  if ($order != '') {
		$orders = ' ORDER BY ' . $order;
	  }
	  $select = 'SELECT vacaciones.*, hoja_vida.documento, hoja_vida.empresa, hoja_vida.nombres, hoja_vida.apellidos FROM vacaciones NATURAL JOIN (
		SELECT   cedula, MAX(fecha2) AS fecha2
		FROM     vacaciones
		GROUP BY cedula ) t1 LEFT JOIN hoja_vida ON hoja_vida.id = vacaciones.cedula ' . $filter . ' ' . $orders . ' LIMIT ' . $page . ' , ' . $amount;
	  $res = $this->_conn->query($select)->fetchAsObject();
	  return $res;
	}
	
  
}
