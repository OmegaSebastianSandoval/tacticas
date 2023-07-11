<?php

/**
 * clase que genera la insercion y edicion  de planilla horas en la base de datos
 */
class Page_Model_DbTable_Planillahoras extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'planilla_horas';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un planilla horas y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data)
	{
		$planilla = $data['planilla'];
		$cedula = $data['cedula'];
		$fecha = $data['fecha'];
		$horas = $data['horas'];
		$loc = $data['loc'];
		$tipo = $data['tipo'];
		$general = $data['general'];
		$query = "INSERT INTO planilla_horas( planilla, cedula, fecha, horas, loc, tipo, general) VALUES ( '$planilla', '$cedula', '$fecha', '$horas', '$loc', '$tipo', '$general')";
		$res = $this->_conn->query($query);
		return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un planilla horas  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data, $id)
	{

		$planilla = $data['planilla'];
		$cedula = $data['cedula'];
		$fecha = $data['fecha'];
		$horas = $data['horas'];
		$loc = $data['loc'];
		$tipo = $data['tipo'];
		$general = $data['general'];
		$query = "UPDATE planilla_horas SET  planilla = '$planilla', cedula = '$cedula', fecha = '$fecha', horas = '$horas', loc = '$loc', tipo = '$tipo', general = '$general' WHERE id = '" . $id . "'";
		$res = $this->_conn->query($query);
	}
	public function getPlanillaHoras($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT planilla_horas.*,  hoja_vida.empresa, CONCAT(hoja_vida.apellidos," ",hoja_vida.nombres) AS nombre1 FROM (planilla_horas LEFT JOIN planilla ON planilla_horas.planilla = planilla.id) LEFT JOIN hoja_vida ON hoja_vida.documento = planilla_horas.cedula  ' . $filter . ' GROUP BY cedula ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getPlanillaHorasPages($filters = '', $order = '', $page, $amount)
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT planilla_horas.*, hoja_vida.empresa,  CONCAT(hoja_vida.apellidos," ",hoja_vida.nombres) AS nombre1 FROM (planilla_horas LEFT JOIN planilla ON planilla_horas.planilla = planilla.id) LEFT JOIN hoja_vida ON hoja_vida.documento = planilla_horas.cedula  ' . $filter . ' GROUP BY cedula ' . $orders . ' LIMIT ' . $page . ' , ' . $amount;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getSumPlanillaHoras($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = ' SELECT SUM(horas) AS total FROM planilla_horas  ' . $filter . ' GROUP BY cedula ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}

	/* --------------------------------------------
   IFORME SALARIO
   -------------------------------------------- */
	public function getPlanillaHorasSalario($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		 $select = 'SELECT planilla_horas.* FROM planilla_horas LEFT JOIN planilla ON planilla_horas.planilla = planilla.id   ' . $filter . ' GROUP BY cedula ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getSumPlanillaHorasSalario($filters = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}

			$select = ' SELECT SUM(horas) AS total FROM planilla_horas  ' . $filter;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getPlanillaHorasViaticos($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT planilla_horas.*,  CONCAT(hoja_vida.apellidos," ",hoja_vida.nombres) AS nombre1 FROM (planilla_horas LEFT JOIN planilla ON planilla_horas.planilla = planilla.id) LEFT JOIN hoja_vida ON hoja_vida.documento = planilla_horas.cedula  ' . $filter . ' GROUP BY cedula ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getSumPlanillaHorasViaticos($filters = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}

			$select = ' SELECT SUM(horas) AS total FROM planilla_horas  ' . $filter;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
}






/* 

 SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2197 AND cedula = 'AN47180' AND tipo = '1 ' AND ( (fecha >= '2023-01-01' AND fecha<='2023-01-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 
 

 SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2197 AND cedula = 'AN47180' AND tipo = '2 ' AND ( (fecha >= '2023-01-01' AND fecha<='2023-01-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula
 
 
 SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2197 AND cedula = 'AN47180' AND 
 tipo = '3 ' AND ( (fecha >= '2023-01-01' AND fecha<='2023-01-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 
 

 SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2197 AND cedula = 'AN47180' AND tipo = '4 ' AND ( (fecha >= '2023-01-01' AND fecha<='2023-01-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 
 
 
 SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2197 AND cedula = 'AN47180' AND tipo = '5 ' AND ( (fecha >= '2023-01-01' AND fecha<='2023-01-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula


*/

/* 
SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-919-1420' AND tipo = '1 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 
104.0 104.0

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-919-1420' AND tipo = '2 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 
0 0

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-919-1420' AND tipo = '3 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 
00

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-919-1420' AND tipo = '4 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 
00

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-919-1420' AND tipo = '5 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 

*/

/* 

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-745-1137' AND tipo = '1 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-745-1137' AND tipo = '2 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-745-1137' AND tipo = '3 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-745-1137' AND tipo = '4 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-745-1137' AND tipo = '5 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 


 */


/* 



SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2342' AND cedula = '8-747-281' AND tipo = '1 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2342' AND cedula = '8-747-281' AND tipo = '2 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 
1
SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2342' AND cedula = '8-747-281' AND tipo = '3 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2342' AND cedula = '8-747-281' AND tipo = '4 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2342' AND cedula = '8-747-281' AND tipo = '5 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula

104
2 2
*/


/* 

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2349 AND cedula = '4-808-1674' AND tipo = '1 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2349 AND cedula = '4-808-1674' AND tipo = '2 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2349 AND cedula = '4-808-1674' AND tipo = '3 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2349 AND cedula = '4-808-1674' AND tipo = '4 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 

SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2349 AND cedula = '4-808-1674' AND tipo = '5 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 


*/