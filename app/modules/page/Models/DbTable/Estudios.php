<?php 
/**
* clase que genera la insercion y edicion  de estudios y formaci&oacute;n en la base de datos
*/
class Page_Model_DbTable_Estudios extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'hoja_estudios';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un estudios y formaci&oacute;n y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$institucion = $data['institucion'];
		$titulo = $data['titulo'];
		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$cedula = $data['cedula'];
		$descripcion = $data['descripcion'];
		$query = "INSERT INTO hoja_estudios( institucion, titulo, fecha1, fecha2, cedula, descripcion) VALUES ( '$institucion', '$titulo', '$fecha1', '$fecha2', '$cedula', '$descripcion')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un estudios y formaci&oacute;n  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$institucion = $data['institucion'];
		$titulo = $data['titulo'];
		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$cedula = $data['cedula'];
		$descripcion = $data['descripcion'];
		$query = "UPDATE hoja_estudios SET  institucion = '$institucion', titulo = '$titulo', fecha1 = '$fecha1', fecha2 = '$fecha2', cedula = '$cedula', descripcion = '$descripcion' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}