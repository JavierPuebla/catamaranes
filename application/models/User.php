<?php
Class User extends CI_Model
{
	function login($usuario, $clave)
	{
		//echo "usr:".$nombreusuario;
		//echo "pass:".$clave;

		$this -> db -> select('*');
		$this -> db -> from('usuarios');
		$this -> db -> where("usr_usuario = " . "'" . $usuario . "'");
		$this -> db -> where("clave_usuario = " . "'" . $clave . "'");
		$this -> db -> limit(1);

		$query = $this -> db -> get();
		if($query -> num_rows() == 1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}

	}

	function u_data($id){
		$this -> db -> select('nombre, apellido, email, direccion, ciudad, provincia, idpais');
		$this -> db -> from('usuarios');
		$this -> db -> where("id = " . "'" . $id. "'");
		$this -> db -> limit(1);

		$query = $this -> db -> get();
		if($query -> num_rows() == 1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}


	}
}
?>
