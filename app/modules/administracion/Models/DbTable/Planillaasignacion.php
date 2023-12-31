<?php 
/**
* clase que genera la insercion y edicion  de planilla asignaci&oacute;n en la base de datos
*/
class Administracion_Model_DbTable_Planillaasignacion extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'planilla_asignacion';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un planilla asignaci&oacute;n y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$planilla = $data['planilla'];
		$cedula = $data['cedula'];
		$valor_hora = $data['valor_hora'];
		$sin_seguridad = $data['sin_seguridad'];
		$query = "INSERT INTO planilla_asignacion( planilla, cedula, valor_hora, sin_seguridad) VALUES ( '$planilla', '$cedula', '$valor_hora', '$sin_seguridad')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un planilla asignaci&oacute;n  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$planilla = $data['planilla'];
		$cedula = $data['cedula'];
		$valor_hora = $data['valor_hora'];
		$sin_seguridad = $data['sin_seguridad'];
		$query = "UPDATE planilla_asignacion SET  planilla = '$planilla', cedula = '$cedula', valor_hora = '$valor_hora', sin_seguridad = '$sin_seguridad' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}