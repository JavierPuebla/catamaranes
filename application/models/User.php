<?php
Class User extends CI_Model
{
	function login($usuario, $clave)
	{
		//echo "usr:".$nombreusuario;
		//echo "pass:".$clave;

		$this -> db -> select('*');
		$this -> db -> from('Usuarios');
		$this -> db -> where("usuario = " . "'" . $usuario . "'");
		$this -> db -> where("clave = " . "'" . $clave . "'");
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
		$this -> db -> where("idusuario = " . "'" . $id. "'");
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
