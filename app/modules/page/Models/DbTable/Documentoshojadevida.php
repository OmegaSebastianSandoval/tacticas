<?php 
/**
* clase que genera la insercion y edicion  de documento en la base de datos
*/
class Page_Model_DbTable_Documentoshojadevida extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'documentos';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un documento y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$nombre = $data['nombre'];
		$archivo = $data['archivo'];
		$fecha = $data['fecha'];
		$cedula = $data['cedula'];
		$tipo = $data['tipo'];
		$fecha_vencimiento = $data['fecha_vencimiento'];
		$query = "INSERT INTO documentos( nombre, archivo, fecha, cedula, tipo, fecha_vencimiento) VALUES ( '$nombre', '$archivo', '$fecha', '$cedula', '$tipo', '$fecha_vencimiento')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un documento  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$nombre = $data['nombre'];
		$archivo = $data['archivo'];
		$fecha = $data['fecha'];
		$cedula = $data['cedula'];
		$tipo = $data['tipo'];
		$fecha_vencimiento = $data['fecha_vencimiento'];
		$query = "UPDATE documentos SET  nombre = '$nombre', archivo = '$archivo', fecha = '$fecha', cedula = '$cedula', tipo = '$tipo', fecha_vencimiento = '$fecha_vencimiento' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}