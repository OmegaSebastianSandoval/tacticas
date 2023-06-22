<?php 
/**
* clase que genera la insercion y edicion  de renovaciones en la base de datos
*/
class Page_Model_DbTable_Renovaciones extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'renovaciones';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'renovacion_id';

	/**
	 * insert recibe la informacion de un renovaciones y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$renovacion_fechainicio = $data['renovacion_fechainicio'];
		$renovacion_fechafin = $data['renovacion_fechafin'];
		$renovacion_usuario = $data['renovacion_usuario'];
		$query = "INSERT INTO renovaciones( renovacion_fechainicio, renovacion_fechafin, renovacion_usuario) VALUES ( '$renovacion_fechainicio', '$renovacion_fechafin', '$renovacion_usuario')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un renovaciones  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$renovacion_fechainicio = $data['renovacion_fechainicio'];
		$renovacion_fechafin = $data['renovacion_fechafin'];
		$renovacion_usuario = $data['renovacion_usuario'];
		$query = "UPDATE renovaciones SET  renovacion_fechainicio = '$renovacion_fechainicio', renovacion_fechafin = '$renovacion_fechafin', renovacion_usuario = '$renovacion_usuario' WHERE renovacion_id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}