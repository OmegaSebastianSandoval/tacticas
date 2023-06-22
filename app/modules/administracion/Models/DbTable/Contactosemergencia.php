<?php 
/**
* clase que genera la insercion y edicion  de contactos de emergencia en la base de datos
*/
class Administracion_Model_DbTable_Contactosemergencia extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'contactos_emergencia';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'contacto_emergencia_id';

	/**
	 * insert recibe la informacion de un contactos de emergencia y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$contacto_emergencia_nombre = $data['contacto_emergencia_nombre'];
		$contacto_emergencia_telefono = $data['contacto_emergencia_telefono'];
		$contacto_emergencia_parentesco = $data['contacto_emergencia_parentesco'];
		$contacto_emergencia_empleado = $data['contacto_emergencia_empleado'];
		$query = "INSERT INTO contactos_emergencia( contacto_emergencia_nombre, contacto_emergencia_telefono, contacto_emergencia_parentesco, contacto_emergencia_empleado) VALUES ( '$contacto_emergencia_nombre', '$contacto_emergencia_telefono', '$contacto_emergencia_parentesco', '$contacto_emergencia_empleado')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un contactos de emergencia  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$contacto_emergencia_nombre = $data['contacto_emergencia_nombre'];
		$contacto_emergencia_telefono = $data['contacto_emergencia_telefono'];
		$contacto_emergencia_parentesco = $data['contacto_emergencia_parentesco'];
		$contacto_emergencia_empleado = $data['contacto_emergencia_empleado'];
		$query = "UPDATE contactos_emergencia SET  contacto_emergencia_nombre = '$contacto_emergencia_nombre', contacto_emergencia_telefono = '$contacto_emergencia_telefono', contacto_emergencia_parentesco = '$contacto_emergencia_parentesco', contacto_emergencia_empleado = '$contacto_emergencia_empleado' WHERE contacto_emergencia_id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}