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
	public function insert($data){
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
	public function update($data,$id){
		
		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$cedula = $data['cedula'];
		$query = "UPDATE vacaciones SET  fecha1 = '$fecha1', fecha2 = '$fecha2', cedula = '$cedula' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}