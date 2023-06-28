<?php

/**
 * clase que genera la insercion y edicion  de dotaci&oacute;n en la base de datos
 */
class Page_Model_DbTable_Dotacioneshojadevida extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'dotaciones';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un dotaci&oacute;n y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data)
	{
		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$tipo = $data['tipo'];
		$cantidad = $data['cantidad'];
		$cedula = $data['cedula'];
		$observacion = $data['observacion'];
		$query = "INSERT INTO dotaciones( fecha1, fecha2, tipo, cantidad, cedula, observacion) VALUES ( '$fecha1', '$fecha2', '$tipo', '$cantidad', '$cedula', '$observacion')";
		$res = $this->_conn->query($query);
		return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un dotaci&oacute;n  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data, $id)
	{

		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$tipo = $data['tipo'];
		$cantidad = $data['cantidad'];
		$cedula = $data['cedula'];
		$observacion = $data['observacion'];
		$query = "UPDATE dotaciones SET  fecha1 = '$fecha1', fecha2 = '$fecha2', tipo = '$tipo', cantidad = '$cantidad', cedula = '$cedula', observacion = '$observacion' WHERE id = '" . $id . "'";
		$res = $this->_conn->query($query);
	}

	public function getDotacion($filters = '', $order = '')
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
		$select = 'SELECT dotaciones.*, hoja_vida.documento, hoja_vida.nombres,hoja_vida.empresa, hoja_vida.apellidos FROM dotaciones NATURAL JOIN (
			SELECT   cedula, MAX(fecha2) AS fecha2
			FROM     dotaciones
			GROUP BY cedula ) t1 LEFT JOIN hoja_vida ON hoja_vida.id = dotaciones.cedula ' .  $filter . ' ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getListPagesDotacion($filters = '', $order = '', $page, $amount)
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT dotaciones.*, hoja_vida.documento, hoja_vida.nombres,hoja_vida.empresa, hoja_vida.apellidos FROM dotaciones NATURAL JOIN (
			SELECT   cedula, MAX(fecha2) AS fecha2
			FROM     dotaciones
			GROUP BY cedula ) t1 LEFT JOIN hoja_vida ON hoja_vida.id = dotaciones.cedula ' . $filter . ' ' . $orders . ' LIMIT ' . $page . ' , ' . $amount;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
}
