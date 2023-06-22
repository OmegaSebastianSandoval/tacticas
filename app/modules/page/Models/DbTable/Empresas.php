<?php 
/**
* clase que genera la insercion y edicion  de empresas en la base de datos
*/
class Page_Model_DbTable_Empresas extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'empresa';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un empresas y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$nombre = $data['nombre'];
		$logo = $data['logo'];
		$direccion = $data['direccion'];
		$telefono = $data['telefono'];
		$email = $data['email'];
		$web = $data['web'];
		$fecha_c = $data['fecha_c'];
		$query = "INSERT INTO empresa( nombre, logo, direccion, telefono, email, web, fecha_c) VALUES ( '$nombre', '$logo', '$direccion', '$telefono', '$email', '$web', '$fecha_c')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un empresas  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$nombre = $data['nombre'];
		$logo = $data['logo'];
		$direccion = $data['direccion'];
		$telefono = $data['telefono'];
		$email = $data['email'];
		$web = $data['web'];
		$fecha_c = $data['fecha_c'];
		$query = "UPDATE empresa SET  nombre = '$nombre', logo = '$logo', direccion = '$direccion', telefono = '$telefono', email = '$email', web = '$web', fecha_c = '$fecha_c' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}