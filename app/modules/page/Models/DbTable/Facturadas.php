<?php 
/**
* clase que genera la insercion y edicion  de facturadas en la base de datos
*/
class Page_Model_DbTable_Facturadas extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'facturadas';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un facturadas y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$localizacion = $data['localizacion'];
		$normal1 = $data['normal1'];
		$normal2 = $data['normal2'];
		$normal3 = $data['normal3'];
		$extra1 = $data['extra1'];
		$extra2 = $data['extra2'];
		$extra3 = $data['extra3'];
		$nocturna1 = $data['nocturna1'];
		$nocturna2 = $data['nocturna2'];
		$nocturna3 = $data['nocturna3'];
		$festivo1 = $data['festivo1'];
		$festivo2 = $data['festivo2'];
		$festivo3 = $data['festivo3'];
		$dominical1 = $data['dominical1'];
		$dominical2 = $data['dominical2'];
		$dominical3 = $data['dominical3'];
		$query = "INSERT INTO facturadas( fecha1, fecha2, localizacion, normal1, normal2, normal3, extra1, extra2, extra3, nocturna1, nocturna2, nocturna3, festivo1, festivo2, festivo3, dominical1, dominical2, dominical3) VALUES ( '$fecha1', '$fecha2', '$localizacion', '$normal1', '$normal2', '$normal3', '$extra1', '$extra2', '$extra3', '$nocturna1', '$nocturna2', '$nocturna3', '$festivo1', '$festivo2', '$festivo3', '$dominical1', '$dominical2', '$dominical3')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	public function insert2($data){
		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$localizacion = $data['localizacion'];
		
		$query = "INSERT INTO facturadas( fecha1, fecha2, localizacion) VALUES ( '$fecha1', '$fecha2', '$localizacion')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}
	/**
	 * update Recibe la informacion de un facturadas  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$fecha1 = $data['fecha1'];
		$fecha2 = $data['fecha2'];
		$localizacion = $data['localizacion'];
		$normal1 = $data['normal1'];
		$normal2 = $data['normal2'];
		$normal3 = $data['normal3'];
		$extra1 = $data['extra1'];
		$extra2 = $data['extra2'];
		$extra3 = $data['extra3'];
		$nocturna1 = $data['nocturna1'];
		$nocturna2 = $data['nocturna2'];
		$nocturna3 = $data['nocturna3'];
		$festivo1 = $data['festivo1'];
		$festivo2 = $data['festivo2'];
		$festivo3 = $data['festivo3'];
		$dominical1 = $data['dominical1'];
		$dominical2 = $data['dominical2'];
		$dominical3 = $data['dominical3'];
		$query = "UPDATE facturadas SET  fecha1 = '$fecha1', fecha2 = '$fecha2', localizacion = '$localizacion', normal1 = '$normal1', normal2 = '$normal2', normal3 = '$normal3', extra1 = '$extra1', extra2 = '$extra2', extra3 = '$extra3', nocturna1 = '$nocturna1', nocturna2 = '$nocturna2', nocturna3 = '$nocturna3', festivo1 = '$festivo1', festivo2 = '$festivo2', festivo3 = '$festivo3', dominical1 = '$dominical1', dominical2 = '$dominical2', dominical3 = '$dominical3' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
	public function getFacturadas(){

		 $select = 'SELECT * FROM facturadas GROUP BY fecha1,fecha2 ORDER BY fecha2 DESC';
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}

}