<?php 
/**
* clase que genera la insercion y edicion  de par&aacute;metros en la base de datos
*/
class Page_Model_DbTable_Parametros extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'planilla_parametros';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un par&aacute;metros y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$horas_extra = $data['horas_extra'];
		$horas_dominicales = $data['horas_dominicales'];
		$horas_nocturnas = $data['horas_nocturnas'];
		$festivos = $data['festivos'];
		$decimo = $data['decimo'];
		$vacaciones = $data['vacaciones'];
		$antiguedad = $data['antiguedad'];
		$seguridad_social = $data['seguridad_social'];
		$seguro_educativo = $data['seguro_educativo'];
		$seguridad_social2 = $data['seguridad_social2'];
		$seguro_educativo2 = $data['seguro_educativo2'];
		$riesgos_profesionales = $data['riesgos_profesionales'];
		$query = "INSERT INTO planilla_parametros( horas_extra, horas_dominicales, horas_nocturnas, festivos, decimo, vacaciones, antiguedad, seguridad_social, seguro_educativo, seguridad_social2, seguro_educativo2, riesgos_profesionales) VALUES ( '$horas_extra', '$horas_dominicales', '$horas_nocturnas', '$festivos', '$decimo', '$vacaciones', '$antiguedad', '$seguridad_social', '$seguro_educativo', '$seguridad_social2', '$seguro_educativo2', '$riesgos_profesionales')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un par&aacute;metros  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$horas_extra = $data['horas_extra'];
		$horas_dominicales = $data['horas_dominicales'];
		$horas_nocturnas = $data['horas_nocturnas'];
		$festivos = $data['festivos'];
		$decimo = $data['decimo'];
		$vacaciones = $data['vacaciones'];
		$antiguedad = $data['antiguedad'];
		$seguridad_social = $data['seguridad_social'];
		$seguro_educativo = $data['seguro_educativo'];
		$seguridad_social2 = $data['seguridad_social2'];
		$seguro_educativo2 = $data['seguro_educativo2'];
		$riesgos_profesionales = $data['riesgos_profesionales'];
		$query = "UPDATE planilla_parametros SET  horas_extra = '$horas_extra', horas_dominicales = '$horas_dominicales', horas_nocturnas = '$horas_nocturnas', festivos = '$festivos', decimo = '$decimo', vacaciones = '$vacaciones', antiguedad = '$antiguedad', seguridad_social = '$seguridad_social', seguro_educativo = '$seguro_educativo', seguridad_social2 = '$seguridad_social2', seguro_educativo2 = '$seguro_educativo2', riesgos_profesionales = '$riesgos_profesionales' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}