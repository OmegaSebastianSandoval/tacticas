<?php 
/**
* clase que genera la insercion y edicion  de planilla totales en la base de datos
*/
class Page_Model_DbTable_Planillatotales extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'planilla_totales';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un planilla totales y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$planilla = $data['planilla'];
		$cedula = $data['cedula'];
		$viaticos = $data['viaticos'];
		$prestamos = $data['prestamos'];
		$prestamos_financiera = $data['prestamos_financiera'];
		$decimo = $data['decimo'];
		$neta = $data['neta'];
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
		$query = "INSERT INTO planilla_totales( planilla, cedula, viaticos, prestamos, prestamos_financiera, decimo, neta, normal1, normal2, normal3, extra1, extra2, extra3, nocturna1, nocturna2, nocturna3, festivo1, festivo2, festivo3, dominical1, dominical2, dominical3) VALUES ( '$planilla', '$cedula', '$viaticos', '$prestamos', '$prestamos_financiera', '$decimo', '$neta', '$normal1', '$normal2', '$normal3', '$extra1', '$extra2', '$extra3', '$nocturna1', '$nocturna2', '$nocturna3', '$festivo1', '$festivo2', '$festivo3', '$dominical1', '$dominical2', '$dominical3')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un planilla totales  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$planilla = $data['planilla'];
		$cedula = $data['cedula'];
		$viaticos = $data['viaticos'];
		$prestamos = $data['prestamos'];
		$prestamos_financiera = $data['prestamos_financiera'];
		$decimo = $data['decimo'];
		$neta = $data['neta'];
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
		$query = "UPDATE planilla_totales SET  planilla = '$planilla', cedula = '$cedula', viaticos = '$viaticos', prestamos = '$prestamos', prestamos_financiera = '$prestamos_financiera', decimo = '$decimo', neta = '$neta', normal1 = '$normal1', normal2 = '$normal2', normal3 = '$normal3', extra1 = '$extra1', extra2 = '$extra2', extra3 = '$extra3', nocturna1 = '$nocturna1', nocturna2 = '$nocturna2', nocturna3 = '$nocturna3', festivo1 = '$festivo1', festivo2 = '$festivo2', festivo3 = '$festivo3', dominical1 = '$dominical1', dominical2 = '$dominical2', dominical3 = '$dominical3' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}

}