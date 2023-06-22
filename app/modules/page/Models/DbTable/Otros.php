<?php 
/**
* clase que genera la insercion y edicion  de otros en la base de datos
*/
class Page_Model_DbTable_Otros extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'hoja_otros';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un otros y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$nombre = $data['nombre'];
		$descripcion = $data['descripcion'];
		$cedula = $data['cedula'];
		$query = "INSERT INTO hoja_otros( nombre, descripcion, cedula) VALUES ( '$nombre', '$descripcion', '$cedula')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un otros  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$nombre = $data['nombre'];
		$descripcion = $data['descripcion'];
		$cedula = $data['cedula'];
		$query = "UPDATE hoja_otros SET  nombre = '$nombre', descripcion = '$descripcion', cedula = '$cedula' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}