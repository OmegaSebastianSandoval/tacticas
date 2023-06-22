<?php 
/**
* clase que genera la insercion y edicion  de usuarios en la base de datos
*/
class Page_Model_DbTable_Usuarios extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'usuarios';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un usuarios y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$nombre = $data['nombre'];
		$usuario = $data['usuario'];
		$clave = $data['clave'];
		$clave_principal = password_hash($data['clave_principal'], PASSWORD_DEFAULT);

		$email = $data['email'];
		$nivel = $data['nivel'];
		$activo = $data['activo'];
		$empresa = $data['empresa'];
		$asignacion = $data['asignacion'];
		$query = "INSERT INTO usuarios( nombre, usuario, clave, clave_principal, email, nivel, activo, empresa, asignacion) VALUES ( '$nombre', '$usuario', '$clave', '$clave_principal', '$email', '$nivel', '$activo', '$empresa', '$asignacion')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un usuarios  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$nombre = $data['nombre'];
		$usuario = $data['usuario'];
		$clave = $data['clave'];
		$changepasword = '';
        if($data['clave_principal']!=''){
            $clave_principal = password_hash($data['clave_principal'], PASSWORD_DEFAULT);
            $changepasword = " , clave_principal = '$clave_principal'";
        }
		$email = $data['email'];
		$nivel = $data['nivel'];
		$activo = $data['activo'];
		$empresa = $data['empresa'];
		$asignacion = $data['asignacion'];
		$query = "UPDATE usuarios SET  nombre = '$nombre', usuario = '$usuario', clave = '$clave', email = '$email', nivel = '$nivel', activo = '$activo', empresa = '$empresa', asignacion = '$asignacion'$changepasword WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}

}