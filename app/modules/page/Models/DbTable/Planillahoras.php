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
	public function insert2($data)
	{
		$planilla = $data['planilla'];
		$cedula = $data['cedula'];
		$fecha = $data['fecha'];
		$horas = $data['horas'];

		$general = $data['general'];
		$tipo = $data['tipo'];
		$query = "INSERT INTO planilla_horas( planilla, cedula, fecha, horas, general,tipo) VALUES ( '$planilla', '$cedula', '$fecha', '$horas', '$general', '$tipo')";
		$res = $this->_conn->query($query);
		return mysqli_insert_id($this->_conn->getConnection());
	}
	public function insertpendientes($data)
	{
		$planilla = $data['planilla'];
		$cedula = $data['cedula'];
		$fecha = $data['fecha'];
		$horas = $data['horas'];
		$loc = $data['loc'];
		$tipo = $data['tipo'];
		$general = $data['general'];
		$pendiente = $data['pendiente'];

	
		$query = "INSERT INTO planilla_horas( planilla, cedula, fecha, horas, loc, tipo, general, pendiente) VALUES ( '$planilla', '$cedula', '$fecha', '$horas', '$loc', '$tipo', '$general', '$pendiente')";
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
	public function getPlanillaHorasSalarioOLD($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT planilla_horas.* FROM planilla_horas LEFT JOIN planilla ON planilla_horas.planilla = planilla.id   ' . $filter . ' GROUP BY planilla_horas.cedula ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
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
		$select = 'SELECT * FROM ' . $this->_name . ' ' . $filter . ' GROUP BY cedula ' . $orders;
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
	public function getSumPlanillaHorasSalarioNew($planilla, $cedula, $fecha1, $fecha2)
	{


		$select = "SELECT t.tipo, COALESCE(SUM(p.horas), 0) AS total
		FROM (
		  SELECT '1' AS tipo UNION ALL
		  SELECT '2' AS tipo UNION ALL
		  SELECT '3' AS tipo UNION ALL
		  SELECT '4' AS tipo UNION ALL
		  SELECT '5' AS tipo
		) AS t
		LEFT JOIN planilla_horas p ON p.planilla = $planilla
								  AND p.cedula = '$cedula'
								  AND p.tipo = t.tipo
								  AND ((p.fecha >= '$fecha1' AND p.fecha <= '$fecha2') OR p.fecha = '0000-00-00')
								  AND p.loc NOT IN ('DESCANSO', 'VACACIONES', 'PERMISO', 'FALTA')
		GROUP BY t.tipo; ";
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	/* 	

SELECT t.tipo, COALESCE(SUM(p.horas), 0) AS total
	FROM (
	  SELECT '1' AS tipo UNION ALL
	  SELECT '2' AS tipo UNION ALL
	  SELECT '3' AS tipo UNION ALL
	  SELECT '4' AS tipo UNION ALL
	  SELECT '5' AS tipo
	) AS t
	LEFT JOIN planilla_horas p ON p.planilla = 2316
							  AND p.cedula = 'AT909186'
							  AND p.tipo = t.tipo
							  AND ((p.fecha >= '2023-04-01' AND p.fecha <= '2023-04-15') OR p.fecha = '0000-00-00')
							  AND p.loc NOT IN ('DESCANSO', 'VACACIONES', 'PERMISO', 'FALTA')
	GROUP BY t.tipo;
	 */
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
		$select = 'SELECT planilla_horas.*, hoja_vida.viaticos, CONCAT(hoja_vida.apellidos," ",hoja_vida.nombres) AS nombre1 FROM (planilla_horas LEFT JOIN planilla ON planilla_horas.planilla = planilla.id) LEFT JOIN hoja_vida ON hoja_vida.documento = planilla_horas.cedula  ' . $filter . ' GROUP BY cedula ' . $orders;
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
	public function getPlanillaHorasProvisiones($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT planilla_horas.*, hoja_vida.tipo_contrato,  CONCAT(hoja_vida.apellidos," ",hoja_vida.nombres) AS nombre1 FROM (planilla_horas LEFT JOIN planilla ON planilla_horas.planilla = planilla.id) LEFT JOIN hoja_vida ON hoja_vida.documento = planilla_horas.cedula  ' . $filter . ' GROUP BY cedula ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getPlanillaHorasProvisionesPages($filters = '', $order = '', $page, $amount)
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT planilla_horas.*, hoja_vida.tipo_contrato,  CONCAT(hoja_vida.apellidos," ",hoja_vida.nombres) AS nombre1 FROM (planilla_horas LEFT JOIN planilla ON planilla_horas.planilla = planilla.id) LEFT JOIN hoja_vida ON hoja_vida.documento = planilla_horas.cedula  ' . $filter . ' GROUP BY cedula ' . $orders . ' LIMIT ' . $page . ' , ' . $amount;;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getPlanillaHorasFacturacion($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT planilla_horas.*,  CONCAT(hoja_vida.nombres," ",hoja_vida.apellidos) AS nombre1 FROM (planilla_horas LEFT JOIN planilla ON planilla_horas.planilla = planilla.id) LEFT JOIN hoja_vida ON hoja_vida.documento = planilla_horas.cedula  ' . $filter . ' GROUP BY cedula ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getSumHorasLocalizacion($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT SUM(horas) AS total FROM planilla_horas LEFT JOIN planilla ON planilla.id = planilla_horas.planilla   ' . $filter  . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getSumHorasLocalizacionNew($localizacion, $fecha1, $fecha2)
	{

		$select = "SELECT tipos.tipo, COALESCE(SUM(total_horas.horas), 0) AS total
		 FROM (
		   SELECT 1 AS tipo UNION ALL
		   SELECT 2 AS tipo UNION ALL
		   SELECT 3 AS tipo UNION ALL
		   SELECT 4 AS tipo UNION ALL
		   SELECT 5 AS tipo
		 ) AS tipos
		 LEFT JOIN (
		   SELECT planilla_horas.tipo, SUM(horas) AS horas
		   FROM planilla_horas
		   LEFT JOIN planilla ON planilla.id = planilla_horas.planilla
		   WHERE loc = '$localizacion'
			 AND (
			   (planilla_horas.fecha >= '$fecha1' AND planilla_horas.fecha <= '$fecha2')
			   OR planilla_horas.fecha = '0000-00-00'
			 )
			 AND (
			   (planilla.fecha1 >= '$fecha1' AND planilla.fecha2 <= '$fecha2' AND planilla_horas.fecha = '0000-00-00')
			   OR planilla_horas.fecha != '0000-00-00'
			 )
		   GROUP BY planilla_horas.tipo
		 ) AS total_horas ON tipos.tipo = total_horas.tipo
		 GROUP BY tipos.tipo;";
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getSumHorasLocalizacionNew2($fecha1, $fecha2)
	{

		$select = "SELECT 
		loc.nombre AS localizacion,
		SUM(CASE WHEN tipos.tipo = 1 THEN COALESCE(total_horas.horas, 0) ELSE 0 END) AS total_tipo_1,
		SUM(CASE WHEN tipos.tipo = 2 THEN COALESCE(total_horas.horas, 0) ELSE 0 END) AS total_tipo_2,
		SUM(CASE WHEN tipos.tipo = 3 THEN COALESCE(total_horas.horas, 0) ELSE 0 END) AS total_tipo_3,
		SUM(CASE WHEN tipos.tipo = 4 THEN COALESCE(total_horas.horas, 0) ELSE 0 END) AS total_tipo_4,
		SUM(CASE WHEN tipos.tipo = 5 THEN COALESCE(total_horas.horas, 0) ELSE 0 END) AS total_tipo_5
	  FROM (
		  SELECT 1 AS tipo UNION ALL
		  SELECT 2 AS tipo UNION ALL
		  SELECT 3 AS tipo UNION ALL
		  SELECT 4 AS tipo UNION ALL
		  SELECT 5 AS tipo
	  ) AS tipos
	  CROSS JOIN localizacion AS loc
	  LEFT JOIN (
		  SELECT planilla_horas.loc, planilla_horas.tipo, SUM(horas) AS horas
		  FROM planilla_horas
		  LEFT JOIN planilla ON planilla.id = planilla_horas.planilla
		  WHERE ((planilla_horas.fecha >= '$fecha1' AND planilla_horas.fecha<='$fecha2') OR planilla_horas.fecha='0000-00-00')
		  AND ((planilla.fecha1 >= '$fecha1' AND planilla.fecha2<='$fecha2' AND planilla_horas.fecha='0000-00-00') OR planilla_horas.fecha!='0000-00-00') 
		  GROUP BY planilla_horas.loc, planilla_horas.tipo
	  ) AS total_horas ON loc.nombre = total_horas.loc AND tipos.tipo = total_horas.tipo
	  WHERE loc.nombre NOT IN ('DESCANSO', 'PERMISO', 'FALTA', 'VACACIONES')
	  GROUP BY loc.nombre
	  ";
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getSumHorasLocalizacionInfo($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT SUM(horas) AS total, planilla.* FROM planilla_horas LEFT JOIN planilla ON planilla.id = planilla_horas.planilla  ' . $filter  . ' GROUP BY planilla ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getSumHorasConsolidado($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		 $select = 'SELECT SUM(horas) AS total FROM planilla_horas ' . $filter  . ' ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}

	public function getPlanillaHorasFacturadas($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT SUM(horas) AS total FROM planilla_horas LEFT JOIN planilla ON planilla_horas.planilla = planilla.id   ' . $filter . ' ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getPlanillaHorasFacturadasNew($loc, $fecha1, $fecha2)
	{

		$select = 'SELECT tipos.tipo, COALESCE(SUM(horas), 0) AS total
		 FROM (
		   SELECT 1 AS tipo
		   UNION SELECT 2 AS tipo
		   UNION SELECT 3 AS tipo
		   UNION SELECT 4 AS tipo
		   UNION SELECT 5 AS tipo
		 ) tipos
		 LEFT JOIN planilla_horas ON tipos.tipo = planilla_horas.tipo
		 LEFT JOIN planilla ON planilla_horas.planilla = planilla.id
		 WHERE planilla_horas.loc = ' . "'$loc'" . '
		   AND planilla_horas.tipo IN (1, 2, 3, 4, 5)
		   AND (
			 (planilla_horas.fecha BETWEEN ' . "'$fecha1'" . ' AND ' . "'$fecha2'" . ')
			 OR (planilla_horas.fecha = ' . "'0000-00-00'" . ')
		   )
		   AND (
			 (planilla.fecha1 BETWEEN ' . "'$fecha1'" . ' AND ' . "'$fecha2'" . ' AND planilla_horas.fecha = ' . "'0000-00-00'" . ')
			 OR (planilla_horas.fecha != ' . "'0000-00-00'" . ')
		   )
		 GROUP BY tipos.tipo;
		   ';
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getPlanillaHorasFacturadasNewNew($fecha1, $fecha2)
	{

		$select = 'SELECT loc.nombre AS localizacion, 
		 COALESCE(SUM(total_horas.horas), 0) AS total_horas,
		 SUM(CASE WHEN total_horas.tipo = 1 THEN total_horas.horas ELSE 0 END) AS tipo1,
		 SUM(CASE WHEN total_horas.tipo = 2 THEN total_horas.horas ELSE 0 END) AS tipo2,
		 SUM(CASE WHEN total_horas.tipo = 3 THEN total_horas.horas ELSE 0 END) AS tipo3,
		 SUM(CASE WHEN total_horas.tipo = 4 THEN total_horas.horas ELSE 0 END) AS tipo4,
		 SUM(CASE WHEN total_horas.tipo = 5 THEN total_horas.horas ELSE 0 END) AS tipo5
  FROM localizacion loc
  LEFT JOIN (
	  SELECT planilla_horas.loc, planilla_horas.tipo, COALESCE(SUM(planilla_horas.horas), 0) AS horas
	  FROM planilla_horas
	  LEFT JOIN planilla ON planilla_horas.planilla = planilla.id
	  WHERE planilla_horas.loc != "DESCANSO"
	  AND planilla_horas.loc != "PERMISO"
	  AND planilla_horas.loc != "FALTA"
	  AND planilla_horas.loc != "VACACIONES"
	  AND planilla_horas.tipo IN (1, 2, 3, 4, 5)
	  AND (
		  (planilla_horas.fecha BETWEEN ' . "'$fecha1'" . ' AND ' . "'$fecha2'" . ')
		  OR (planilla_horas.fecha = "0000-00-00")
	  )
	  AND (
		  (planilla.fecha1 BETWEEN ' . "'$fecha1'" . ' AND ' . "'$fecha2'" . ' AND planilla_horas.fecha = "0000-00-00")
		  OR (planilla_horas.fecha != "0000-00-00")
	  )
	  GROUP BY planilla_horas.loc, planilla_horas.tipo
  ) AS total_horas ON loc.nombre = total_horas.loc
  WHERE loc.nombre NOT IN ("DESCANSO", "PERMISO", "FALTA", "VACACIONES")
  GROUP BY loc.nombre
  ORDER BY loc.nombre ASC;';
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
}

/* "SELECT planilla_horas.*,  CONCAT(hoja_vida.apellidos,' ',hoja_vida.nombres) AS nombre1 FROM (planilla_horas LEFT JOIN planilla ON planilla_horas.planilla = planilla.id) LEFT JOIN hoja_vida ON hoja_vida.documento = planilla_horas.cedula */




/* 
SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2316 AND cedula = 'AT909186' AND tipo = '1' AND ( (fecha >= '2023-04-01' AND fecha<='2023-04-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') 


SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2316 AND cedula = 'AT909186' AND tipo = '2' AND ( (fecha >= '2023-04-01' AND fecha<='2023-04-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') 


SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2316 AND cedula = 'AT909186' AND tipo = '3' AND ( (fecha >= '2023-04-01' AND fecha<='2023-04-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') 


SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2316 AND cedula = 'AT909186' AND tipo = '4' AND ( (fecha >= '2023-04-01' AND fecha<='2023-04-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') 


SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2316 AND cedula = 'AT909186' AND tipo = '5' AND ( (fecha >= '2023-04-01' AND fecha<='2023-04-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA')



 SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2197 AND cedula = 'AN47180' AND tipo = '1 ' AND ( (fecha >= '2023-01-01' AND fecha<='2023-01-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 
 

 SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2197 AND cedula = 'AN47180' AND tipo = '2 ' AND ( (fecha >= '2023-01-01' AND fecha<='2023-01-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula
 
 
 SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2197 AND cedula = 'AN47180' AND 
 tipo = '3 ' AND ( (fecha >= '2023-01-01' AND fecha<='2023-01-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 
 

 SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2197 AND cedula = 'AN47180' AND tipo = '4 ' AND ( (fecha >= '2023-01-01' AND fecha<='2023-01-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 
 
 
 SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = 2197 AND cedula = 'AN47180' AND tipo = '5 ' AND ( (fecha >= '2023-01-01' AND fecha<='2023-01-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula


*/

/* 
SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-919-1420' AND tipo = '1 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 


SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-919-1420' AND tipo = '2 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 


SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-919-1420' AND tipo = '3 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 


SELECT SUM(horas) AS total FROM planilla_horas WHERE planilla = '2337' AND cedula = '8-919-1420' AND tipo = '4 ' AND ( (fecha >= '2023-05-01' AND fecha<='2023-05-15') OR fecha='0000-00-00' ) AND (loc != 'DESCANSO' AND loc != 'VACACIONES' AND loc != 'PERMISO' AND loc != 'FALTA') GROUP BY cedula 


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