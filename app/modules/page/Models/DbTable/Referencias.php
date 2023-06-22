<?php 
/**
* clase que genera la insercion y edicion  de referencias en la base de datos
*/
class Page_Model_DbTable_Referencias extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'hoja_referencias';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un referencias y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$tipo = $data['tipo'];
		$nombre = $data['nombre'];
		$cargo = $data['cargo'];
		$empresa = $data['empresa'];
		$telefono = $data['telefono'];
		$cedula = $data['cedula'];
		$se_llamo = $data['se_llamo'];
		$se_confirmo = $data['se_confirmo'];
		$descripcion = $data['descripcion'];
		$query = "INSERT INTO hoja_referencias( tipo, nombre, cargo, empresa, telefono, cedula, se_llamo, se_confirmo, descripcion) VALUES ( '$tipo', '$nombre', '$cargo', '$empresa', '$telefono', '$cedula', '$se_llamo', '$se_confirmo', '$descripcion')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un referencias  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$tipo = $data['tipo'];
		$nombre = $data['nombre'];
		$cargo = $data['cargo'];
		$empresa = $data['empresa'];
		$telefono = $data['telefono'];
		$cedula = $data['cedula'];
		$se_llamo = $data['se_llamo'];
		$se_confirmo = $data['se_confirmo'];
		$descripcion = $data['descripcion'];
		$query = "UPDATE hoja_referencias SET  tipo = '$tipo', nombre = '$nombre', cargo = '$cargo', empresa = '$empresa', telefono = '$telefono', cedula = '$cedula', se_llamo = '$se_llamo', se_confirmo = '$se_confirmo', descripcion = '$descripcion' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}