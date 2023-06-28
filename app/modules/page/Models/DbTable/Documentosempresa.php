<?php 
/**
* clase que genera la insercion y edicion  de documento en la base de datos
*/
class Page_Model_DbTable_Documentosempresa extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'documentos_empresa';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'documento_empresa_id';

	/**
	 * insert recibe la informacion de un documento y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$documento_empresa_nombre = $data['documento_empresa_nombre'];
		$documento_empresa_archivo = $data['documento_empresa_archivo'];
		$documento_empresa_fecha_creacion = $data['documento_empresa_fecha_creacion'];
		$documento_empresa_empresa_id = $data['documento_empresa_empresa_id'];
		$query = "INSERT INTO documentos_empresa( documento_empresa_nombre, documento_empresa_archivo, documento_empresa_fecha_creacion, documento_empresa_empresa_id) VALUES ( '$documento_empresa_nombre', '$documento_empresa_archivo', '$documento_empresa_fecha_creacion', '$documento_empresa_empresa_id')";
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
		
		$documento_empresa_nombre = $data['documento_empresa_nombre'];
		$documento_empresa_archivo = $data['documento_empresa_archivo'];
		$documento_empresa_fecha_creacion = $data['documento_empresa_fecha_creacion'];
		$documento_empresa_empresa_id = $data['documento_empresa_empresa_id'];
		$query = "UPDATE documentos_empresa SET  documento_empresa_nombre = '$documento_empresa_nombre', documento_empresa_archivo = '$documento_empresa_archivo', documento_empresa_fecha_creacion = '$documento_empresa_fecha_creacion', documento_empresa_empresa_id = '$documento_empresa_empresa_id' WHERE documento_empresa_id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}