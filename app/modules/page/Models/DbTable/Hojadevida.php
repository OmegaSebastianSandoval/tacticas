<?php

/**
 * clase que genera la insercion y edicion  de hoja de vida en la base de datos
 */
class Page_Model_DbTable_Hojadevida extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'hoja_vida';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un hoja de vida y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data)
	{
		$foto = $data['foto'];
		$nombres = $data['nombres'];
		$apellidos = $data['apellidos'];
		$tipo_documento = $data['tipo_documento'];
		$documento = $data['documento'];
		$fecha_nacimiento = $data['fecha_nacimiento'];
		$ciudad_nacimiento = $data['ciudad_nacimiento'];
		$email = $data['email'];
		$direccion = $data['direccion'];
		$telefono = $data['telefono'];
		$celular = $data['celular'];
		$ciudad = $data['ciudad'];
		$estado_civil = $data['estado_civil'];
		$fecha_m = $data['fecha_m'];
		$fecha_ingreso = $data['fecha_ingreso'];
		$numero_seguro = $data['numero_seguro'];
		$retirado = $data['retirado'];
		$tipo_contrato = $data['tipo_contrato'];
		$fecha_salida = $data['fecha_salida'];
		$inicio = $data['inicio'];
		$fin = $data['fin'];
		$empresa = $data['empresa'];
		$fecha_c = $data['fecha_c'];
		$perfil_profesional = $data['perfil_profesional'];
		$metodo_pago = $data['metodo_pago'];
		$numero_cuenta = $data['numero_cuenta'];
		$cargo = $data['cargo'];
		$viaticos = $data['viaticos'];


		$query = "INSERT INTO hoja_vida( foto, nombres, apellidos, tipo_documento, documento, fecha_nacimiento, ciudad_nacimiento, email, direccion, telefono, celular, ciudad, estado_civil, fecha_m, fecha_ingreso, numero_seguro, retirado, tipo_contrato, fecha_salida, inicio, fin, empresa, fecha_c, perfil_profesional, metodo_pago, numero_cuenta, cargo, viaticos) VALUES ( '$foto', '$nombres', '$apellidos', '$tipo_documento', '$documento', '$fecha_nacimiento', '$ciudad_nacimiento', '$email', '$direccion', '$telefono', '$celular', '$ciudad', '$estado_civil', '$fecha_m', '$fecha_ingreso', '$numero_seguro', '$retirado', '$tipo_contrato', '$fecha_salida', '$inicio', '$fin', '$empresa', '$fecha_c', '$perfil_profesional', '$metodo_pago', '$numero_cuenta', '$cargo', $viaticos)";
		$res = $this->_conn->query($query);
		return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un hoja de vida  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data, $id)
	{

		$foto = $data['foto'];
		$nombres = $data['nombres'];
		$apellidos = $data['apellidos'];
		$tipo_documento = $data['tipo_documento'];
		$documento = $data['documento'];
		$fecha_nacimiento = $data['fecha_nacimiento'];
		$ciudad_nacimiento = $data['ciudad_nacimiento'];
		$email = $data['email'];
		$direccion = $data['direccion'];
		$telefono = $data['telefono'];
		$celular = $data['celular'];
		$ciudad = $data['ciudad'];
		$estado_civil = $data['estado_civil'];
		$fecha_m = $data['fecha_m'];
		$fecha_ingreso = $data['fecha_ingreso'];
		$numero_seguro = $data['numero_seguro'];
		$retirado = $data['retirado'];
		$tipo_contrato = $data['tipo_contrato'];
		$fecha_salida = $data['fecha_salida'];
		$inicio = $data['inicio'];
		$fin = $data['fin'];
		$empresa = $data['empresa'];
		$fecha_c = $data['fecha_c'];
		$metodo_pago = $data['metodo_pago'];
		$numero_cuenta = $data['numero_cuenta'];
		$perfil_profesional = $data['perfil_profesional'];
		$cargo = $data['cargo'];
		$viaticos = $data['viaticos'];



		$query = "UPDATE hoja_vida SET  foto = '$foto', nombres = '$nombres', apellidos = '$apellidos', tipo_documento = '$tipo_documento', documento = '$documento', fecha_nacimiento = '$fecha_nacimiento', ciudad_nacimiento = '$ciudad_nacimiento', email = '$email', direccion = '$direccion', telefono = '$telefono', celular = '$celular', ciudad = '$ciudad', estado_civil = '$estado_civil', fecha_m = '$fecha_m', fecha_ingreso = '$fecha_ingreso', numero_seguro = '$numero_seguro', retirado = '$retirado', tipo_contrato = '$tipo_contrato', fecha_salida = '$fecha_salida', inicio = '$inicio', fin = '$fin', empresa = '$empresa', fecha_c = '$fecha_c', perfil_profesional = '$perfil_profesional', metodo_pago = '$metodo_pago', numero_cuenta = '$numero_cuenta', cargo = '$cargo' , viaticos = '$viaticos' WHERE id = '" . $id . "'";
		$res = $this->_conn->query($query);
	}
	public function totalPersonas($filtro)
	{
		$select =  "SELECT  COUNT(*) AS total FROM hoja_vida LEFT JOIN ciudad ON ciudad.codigo = hoja_vida.ciudad WHERE  $filtro ORDER BY nombres, apellidos ";
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getByCedula($cc)
	{
		$res = $this->_conn->query('SELECT * FROM ' . $this->_name . ' WHERE  documento = "' . $cc . '"')->fetchAsObject();
		if (isset($res[0])) {
			return $res[0];
		}
		return false;
	}

	public function getDocumentos($filters = '', $order = '')
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
		$select = 'SELECT documentos.*, hoja_vida.documento, hoja_vida.empresa, hoja_vida.nombres, hoja_vida.apellidos FROM documentos LEFT JOIN hoja_vida ON hoja_vida.id = documentos.cedula' .  $filter . ' ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getListPagesDocumentos($filters = '', $order = '', $page, $amount)
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT documentos.*, hoja_vida.documento, hoja_vida.empresa, hoja_vida.nombres, hoja_vida.apellidos FROM documentos LEFT JOIN hoja_vida ON hoja_vida.id = documentos.cedula ' . $filter . ' ' . $orders . ' LIMIT ' . $page . ' , ' . $amount;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}

	public function getContratos($filters = '', $order = '')
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
		$select = 'SELECT historial.*, hoja_vida.documento, hoja_vida.empresa, hoja_vida.nombres, hoja_vida.apellidos, hoja_vida.tipo_contrato, hoja_vida.inicio, hoja_vida.fin FROM historial NATURAL JOIN (
		SELECT   cedula, MAX(fecha_ingreso) AS fecha_ingreso
		FROM     historial
		GROUP BY cedula ) t1 LEFT JOIN hoja_vida ON hoja_vida.id = historial.cedula' .  $filter . ' ' . $orders;

		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getListPagesContratos($filters = '', $order = '', $page, $amount)
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT historial.*, hoja_vida.documento, hoja_vida.empresa, hoja_vida.nombres, hoja_vida.apellidos, hoja_vida.tipo_contrato, hoja_vida.inicio, hoja_vida.fin FROM historial NATURAL JOIN (
		SELECT   cedula, MAX(fecha_ingreso) AS fecha_ingreso
		FROM     historial
		GROUP BY cedula ) t1 LEFT JOIN hoja_vida ON hoja_vida.id = historial.cedula ' . $filter . ' ' . $orders . ' LIMIT ' . $page . ' , ' . $amount;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
	public function getRotacion($filters = '', $order = '')
	{
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT * FROM historial ' . $filter . ' ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}

}
/* 
public function getContratos($filters = '', $order = '')
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
	 $select = 'SELECT historial.*, hoja_vida.documento, hoja_vida.empresa, hoja_vida.nombres, hoja_vida.apellidos, hoja_vida.tipo_contrato, hoja_vida.inicio, hoja_vida.fin FROM historial NATURAL JOIN (
		SELECT   cedula, MAX(fecha_ingreso) AS fecha_ingreso
		FROM     historial
		GROUP BY cedula ) t1 LEFT JOIN hoja_vida ON hoja_vida.id = historial.cedula' .  $filter . ' ' . $orders;
	
	 $res = $this->_conn->query($select)->fetchAsObject();
	return $res;
}
public function getListPagesContratos($filters = '', $order = '', $page, $amount)
{
	$filter = '';
	if ($filters != '') {
		$filter = ' WHERE ' . $filters;
	}
	$orders = "";
	if ($order != '') {
		$orders = ' ORDER BY ' . $order;
	}
	$select = 'SELECT historial.*, hoja_vida.documento, hoja_vida.empresa, hoja_vida.nombres, hoja_vida.apellidos, hoja_vida.tipo_contrato, hoja_vida.inicio, hoja_vida.fin FROM historial NATURAL JOIN (
		SELECT   cedula, MAX(fecha_ingreso) AS fecha_ingreso
		FROM     historial
		GROUP BY cedula ) t1 LEFT JOIN hoja_vida ON hoja_vida.id = historial.cedula ' . $filter . ' ' . $orders . ' LIMIT ' . $page . ' , ' . $amount;
	$res = $this->_conn->query($select)->fetchAsObject();
	return $res;
} */