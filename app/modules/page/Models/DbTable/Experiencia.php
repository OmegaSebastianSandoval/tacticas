<?php 
/**
* clase que genera la insercion y edicion  de experiencia laboral en la base de datos
*/
class Page_Model_DbTable_Experiencia extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'hoja_experiencia';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un experiencia laboral y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$empresa = $data['empresa'];
		$cargo = $data['cargo'];
		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$detalles = $data['detalles'];
		$cedula = $data['cedula'];
		$query = "INSERT INTO hoja_experiencia( empresa, cargo, fecha1, fecha2, detalles, cedula) VALUES ( '$empresa', '$cargo', '$fecha1', '$fecha2', '$detalles', '$cedula')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un experiencia laboral  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$empresa = $data['empresa'];
		$cargo = $data['cargo'];
		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$detalles = $data['detalles'];
		$cedula = $data['cedula'];
		$query = "UPDATE hoja_experiencia SET  empresa = '$empresa', cargo = '$cargo', fecha1 = '$fecha1', fecha2 = '$fecha2', detalles = '$detalles', cedula = '$cedula' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}