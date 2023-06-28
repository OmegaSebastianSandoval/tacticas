<?php 
/**
* clase que genera la insercion y edicion  de historial contratos en la base de datos
*/
class Administracion_Model_DbTable_Historialcontratos extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'historial_contratos';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'historial_contratos_id';

	/**
	 * insert recibe la informacion de un historial contratos y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$historial_contratos_fecha_inicio = $data['historial_contratos_fecha_inicio'];
		$historial_contratos_fecha_fin = $data['historial_contratos_fecha_fin'];
		$historial_contratos_cedula = $data['historial_contratos_cedula'];
		$query = "INSERT INTO historial_contratos( historial_contratos_fecha_inicio, historial_contratos_fecha_fin, historial_contratos_cedula) VALUES ( '$historial_contratos_fecha_inicio', '$historial_contratos_fecha_fin', '$historial_contratos_cedula')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un historial contratos  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$historial_contratos_fecha_inicio = $data['historial_contratos_fecha_inicio'];
		$historial_contratos_fecha_fin = $data['historial_contratos_fecha_fin'];
		$historial_contratos_cedula = $data['historial_contratos_cedula'];
		$query = "UPDATE historial_contratos SET  historial_contratos_fecha_inicio = '$historial_contratos_fecha_inicio', historial_contratos_fecha_fin = '$historial_contratos_fecha_fin', historial_contratos_cedula = '$historial_contratos_cedula' WHERE historial_contratos_id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}