<?php 
/**
* clase que genera la insercion y edicion  de historial en la base de datos
*/
class Page_Model_DbTable_Historial extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'historial';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un historial y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$empresa = $data['empresa'];
		$fecha_ingreso = $data['fecha_ingreso'];
		$fecha_salida = $data['fecha_salida'];
		$cedula = $data['cedula'];
		$trabajadores_total = $data['trabajadores_total'];
		$trabajadores_empresa = $data['trabajadores_empresa'];
		echo $query = "INSERT IGNORE INTO historial( empresa, fecha_ingreso, fecha_salida, cedula, trabajadores_total, trabajadores_empresa) VALUES ( '$empresa', '$fecha_ingreso', '$fecha_salida', '$cedula', '$trabajadores_total', '$trabajadores_empresa')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un historial  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$empresa = $data['empresa'];
		$fecha_ingreso = $data['fecha_ingreso'];
		$fecha_salida = $data['fecha_salida'];
		$cedula = $data['cedula'];
		$trabajadores_total = $data['trabajadores_total'];
		$trabajadores_empresa = $data['trabajadores_empresa'];
		$query = "UPDATE historial SET  empresa = '$empresa', fecha_ingreso = '$fecha_ingreso', fecha_salida = '$fecha_salida', cedula = '$cedula', trabajadores_total = '$trabajadores_total', trabajadores_empresa = '$trabajadores_empresa' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
	public function update2($data){
		
		$empresa = $data['empresa'];
		$fecha_ingreso = $data['fecha_ingreso'];
		$fecha_salida = $data['fecha_salida'];
		$cedula = $data['cedula'];
		$trabajadores_total = $data['trabajadores_total'];
		$trabajadores_empresa = $data['trabajadores_empresa'];
		$query = "UPDATE historial SET fecha_salida = '$fecha_salida', trabajadores_total='$trabajadores_total', trabajadores_empresa='$trabajadores_empresa' WHERE empresa='$empresa' AND cedula='$cedula' AND fecha_ingreso='$fecha_ingreso'";
		$res = $this->_conn->query($query);
	}

}
/* if($empresa!="" and $id>0 and $fecha_ingreso!="" and $fecha_ingreso!="0000-00-00"){
	$query = " INSERT IGNORE INTO historial (empresa,fecha_ingreso,cedula,trabajadores_total,trabajadores_empresa) VALUES ('$empresa','$fecha_ingreso','$id','$trabajadores_total','$trabajadores_empresa') ";
	mysql_query($query, $panama) or die(mysql_error());
	if($fecha_salida!="" and $fecha_salida!="0000-00-00"){
		$query = " UPDATE historial SET fecha_salida = '$fecha_salida', trabajadores_total='$total_trabajadores', trabajadores_total='$trabajadores_empresa' WHERE empresa='$empresa' AND cedula='$id' AND fecha_ingreso='$fecha_ingreso' ";
		mysql_query($query, $panama) or die(mysql_error());
	}
} */