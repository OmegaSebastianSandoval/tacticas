<?php 
/**
* clase que genera la insercion y edicion  de tipo de documento en la base de datos
*/
class Page_Model_DbTable_Tipodocumentos extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'documentos_tipo';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un tipo de documento y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$nombre = $data['nombre'];
		$solo_admin = $data['solo_admin'];
		$query = "INSERT INTO documentos_tipo( nombre, solo_admin) VALUES ( '$nombre', '$solo_admin')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un tipo de documento  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$nombre = $data['nombre'];
		$solo_admin = $data['solo_admin'];
		$query = "UPDATE documentos_tipo SET  nombre = '$nombre', solo_admin = '$solo_admin' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}