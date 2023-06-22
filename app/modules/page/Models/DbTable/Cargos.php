<?php 
/**
* clase que genera la insercion y edicion  de cargos en la base de datos
*/
class Page_Model_DbTable_Cargos extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'cargos';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'cargo_id';

	/**
	 * insert recibe la informacion de un cargos y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$cargo_nombre = $data['cargo_nombre'];
		$cargo_estado = $data['cargo_estado'];
		$query = "INSERT INTO cargos( cargo_nombre, cargo_estado) VALUES ( '$cargo_nombre', '$cargo_estado')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un cargos  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$cargo_nombre = $data['cargo_nombre'];
		$cargo_estado = $data['cargo_estado'];
		$query = "UPDATE cargos SET  cargo_nombre = '$cargo_nombre', cargo_estado = '$cargo_estado' WHERE cargo_id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}