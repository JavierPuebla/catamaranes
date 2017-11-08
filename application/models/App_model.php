<?php
class app_model extends CI_Model {
	public function __construct() {
		$this -> load -> database();
	}

function get_servicios_disponibles($hora,$tipo){
	$q= "SELECT hs.hora_salida,hs.servicios_id, s.tipo,s.subtipo,s.tarifa,b.nombre,b.capacidad FROM `cat_historial_servicios` hs LEFT OUTER JOIN cat_servicios s on hs.servicios_id = s.id LEFT OUTER JOIN cat_barcos b on hs.barcos_id = b.id WHERE hs.hora_salida = '{$hora}' AND s.tipo = '{$tipo}' AND hs.estado LIKE 'D' ORDER BY s.tipo ASC";
	$x = $this->db->query($q);
	return ($x)?$x -> result_array() : false;
}

function get_hora_servicios_disponibles(){
	$q="SELECT DISTINCT hora_salida FROM `cat_historial_servicios` ORDER BY hora_salida";
	$x = $this->db->query($q);
	return ($x)?$x -> result_array() : false;
}

function get_tipos_servicios(){
	$q="SELECT DISTINCT tipo FROM `cat_servicios`";
	$x = $this->db->query($q);
	return ($x)?$x -> result_array() : false;
}

function get_subtipos_servicios(){
	$q="SELECT DISTINCT subtipo  FROM `cat_servicios`";
	$x = $this->db->query($q);
	return ($x)?$x -> result_array() : false;
}

function get_activities($user_id){
	$q="SELECT acciones_id from `actividades` WHERE usuarios_id = {$user_id}";
	$x = $this->db->query($q);
	return ($x)?$x -> row_array() : false;
}

function get_acciones($id){
	$q="SELECT * from `acciones` WHERE acciones_id = {$id}";
	$x = $this->db->query($q);
	return ($x)?$x -> row_array() : false;
}


function update($table,$data,$ikey,$id){

	$this->db->where($ikey, $id);
	return $this->db->update($table, $data);
}

function insert($table,$data){

		return ($this->db->insert($table,$data)>0) ? $this->db->insert_id() :false;

}








//*************** funciones viejas  ********************

// OBTIENE UN NUEVO NUMERO DE ORDEN DE LA TABLA PEDIDOS

function get_new_order_number(){
		//  OBTENGO EL NUEVO NUMERO DE ORDEN DEL ULTIMO REGISTRO EN LA TABLA +1
		$mqy= "SELECT order_number FROM parts_pedidos ORDER BY pedido_id DESC LIMIT 1";
		$p2=$this->db->query($mqy);
		$res = $p2->row();
		if ($res) {
			$ord_num =  $res->order_number +1;
		  	return $ord_num;
		}else{
			return false;
		}
	}


	// encuentra un item guardado previamente en la tabla pedidos para ponerle numero de orden cuando se envia el pedido de compra
	function find_saved_item($pedido_id){
		$q= "SELECT `pedido_id`,`order_number`,`item_id`,`item_name`,`qty`,`price` FROM `parts_pedidos` WHERE `pedido_id` LIKE '{$pedido_id}';";
		$query = $this -> db ->query($q);
		return ($query)?$query -> result_array():false;
	}

	function clean_saved_cart($user_id){
		$q = "DELETE FROM `parts_pedidos` WHERE `client_id` LIKE '{$user_id}' AND `state` = 0 ;";
		$query = $this -> db ->query($q);
	}

	function delete_item_incart($pedido_id){
		$q = "DELETE FROM `parts_pedidos` WHERE `pedido_id` LIKE '{$pedido_id}';";
		$query = $this -> db ->query($q);
	}
	// encuentra los items guardados para sumarlos al carro de compras
	function get_saved_cart($user_id){
		$q = "SELECT `pedido_id`,`PTO_id`,`name`,`qty`,`unit_price` FROM `parts_pedidos` WHERE `client_id` LIKE '{$user_id}' AND `state` = 0 ";
		$query = $this -> db ->query($q);
		return ($query)?$query -> result_array():false;
	}

	function get_item_to_guest_cart($pto_id){
		$q = "SELECT * FROM  `A_bsobj` WHERE `PTO_id` = '{$pto_id}'";
		$query = $this -> db ->query($q);
		if($query->num_rows() > 0){
			return $query -> row_array();
		}else{return false;}
	}

	// es llamado por una funcion de javascript para construir un select de destribuidores con pais
	// no esta la tabla hay que redefinir el rol de los importadores de forma mas amplia para todos los modulos
	public function get_importadores(){
		$q="SELECT * FROM `importadores` JOIN pais where pais.idpais = importadores.id_pais";
		$query = $this -> db ->query($q);
		return $query -> result_array();

	}

	public function parcial_part_number($n){
		$query = "SELECT DISTINCT `B_engines`.sector, `A_bsobj`.`PTO_id`,`A_bsobj`.`name`,`B_engines`.potencia, `B_engines`.`id` FROM `A_bsobj` INNER JOIN `B_engines` ON `A_bsobj`.`id`= `B_engines`.`bsobj_id`  WHERE `A_bsobj`.`PTO_id` LIKE '%{$n}%'";
		$res = $this -> db -> query($query);
		return $res -> result_array();
	}
	/*
	public function get_model_by_partnumber($pn){
		$q = "SELECT DISTINCT `part_id`,`model` FROM  `partes_V2` WHERE `part_number` LIKE '".$pn."'";
		$query = $this -> db ->query($q);
		return $query -> result_array();
	}
	*/

	function get_sector_id(){
		$q = " SELECT `potencia`,`sector`,`refnum`,`image_pos_x`,`image_pos_y`, `bsobj_id`, `A_bsobj`.PTO_id, `A_bsobj`.name,  `A_pcles`.`value`  FROM `B_engines` INNER JOIN `A_bsobj` ON  `A_bsobj`.`id` = `B_engines`.`bsobj_id` INNER JOIN `A_pcles` ON `A_bsobj`.`id`= `A_pcles`.`id_bsobj` WHERE `B_engines`.`id` LIKE '{$engine_id}' AND `A_pcles`.label LIKE 'unit_price'";
		$query = $this -> db ->query($q);
		return $query -> result_array();

	}
	public function get_unit_cost_by_pto_id($i){
		//$q = "SELECT * FROM  `A_bsobj` WHERE `id` = '{$bsobj_id}'";
		//$q = " SELECT `A_bsobj`.`id`, `A_bsobj`.`PTO_id`, `A_bsobj`.`name`, `A_pcles`.`label`, `A_pcles`.`value`  FROM `A_bsobj` RIGHT JOIN `A_pcles` ON  `A_bsobj`.`id` = `A_pcles`.`id_bsobj`  WHERE `A_bsobj`.`PTO_id` LIKE {$i} AND `A_pcles`.`label` LIKE 'unit_price'";
		$query = $this -> db ->query($q);
		return $query -> row_array();
	}

	public function get_unit_price_by_pto_id($i){
		//$q = "SELECT * FROM  `A_bsobj` WHERE `id` = '{$bsobj_id}'";
	//	$q = " SELECT `A_bsobj`.`id`, `A_bsobj`.`PTO_id`, `A_bsobj`.`name`, `A_pcles`.`label`, `A_pcles`.`value`  FROM `A_bsobj` RIGHT JOIN `A_pcles` ON  `A_bsobj`.`id` = `A_pcles`.`id_bsobj`  WHERE `A_bsobj`.`PTO_id` LIKE '{$i}' AND `A_pcles`.`label` LIKE 'unit_price'";
		$query = $this -> db ->query($q);
		if($query->num_rows() > 0){
			return $query -> row_array();
		}else{return false;}

	}

	///NOT WORKING
	public function get_part_by_engine_id($engine_id,$rqtor){
		//$q = "SELECT * FROM  `A_bsobj` WHERE `id` = '{$bsobj_id}'";
		if($rqtor < 4){
			$q = "SELECT `B_potencias`.`strokes`,`B_engines`.`potencia`,`B_engines`.`sector`,`B_engines`.`refnum`,`B_engines`.`image_pos_x`,`B_engines`.`image_pos_y`, `B_engines`.`bsobj_id`, `A_bsobj`.PTO_id, `A_bsobj`.name,  `A_pcles`.`value`  FROM `B_engines` INNER JOIN `A_bsobj` ON  `A_bsobj`.`id` = `B_engines`.`bsobj_id` LEFT JOIN `B_potencias` ON `B_engines`.`id_potencia_marca` = `B_potencias`.`id_potencia_marca` INNER JOIN `A_pcles` ON `A_bsobj`.`id`= `A_pcles`.`id_bsobj` WHERE `B_engines`.`id` LIKE '{$engine_id}' AND `A_pcles`.label LIKE 'unit_price' GROUP BY `B_engines`.`refnum` ";
		}else{
			$q = "SELECT `B_potencias`.`strokes`,`B_engines`.`potencia`,`B_engines`.`sector`,`B_engines`.`refnum`,`B_engines`.`image_pos_x`,`B_engines`.`image_pos_y`, `B_engines`.`bsobj_id`, `A_bsobj`.PTO_id, `A_bsobj`.name  FROM `B_engines` INNER JOIN `A_bsobj` ON  `A_bsobj`.`id` = `B_engines`.`bsobj_id` LEFT JOIN `B_potencias` ON `B_engines`.`id_potencia_marca` = `B_potencias`.`id_potencia_marca` WHERE `B_engines`.`id` LIKE '{$engine_id}' GROUP BY `B_engines`.`refnum` ";
		}
		$query = $this -> db ->query($q);
		return $query -> result_array();
	}

	public function get_partes_by_pot_sec($id_pot,$sec,$rqtor){
		if($rqtor < 4){
			$q = "SELECT `B_potencias`.`strokes`,`B_engines`.`potencia`,`B_engines`.`sector`,`B_engines`.`refnum`,`B_engines`.`image_pos_x`,`B_engines`.`image_pos_y`, `B_engines`.`bsobj_id`, `A_bsobj`.PTO_id, `A_bsobj`.name,  `A_pcles`.`value`  FROM `B_engines` INNER JOIN `A_bsobj` ON  `A_bsobj`.`id` = `B_engines`.`bsobj_id` INNER JOIN `B_potencias` ON  `B_engines`.`id_potencia_marca` = `B_potencias`.`id_potencia_marca`   INNER JOIN `A_pcles` ON `A_bsobj`.`id`= `A_pcles`.`id_bsobj` WHERE `B_engines`.`id_potencia_marca` LIKE '{$id_pot}' AND  `B_engines`.`sector` LIKE '{$sec}' AND `A_pcles`.`label` = 'unit_price' GROUP BY `B_engines`.`bsobj_id`  ORDER BY CONVERT(`refnum`,UNSIGNED INTEGER)";
		}else{

				$q = "SELECT `B_potencias`.`strokes`,`B_engines`.`potencia`,`B_engines`.`sector`,`B_engines`.`refnum`,`B_engines`.`image_pos_x`,`B_engines`.`image_pos_y`, `B_engines`.`bsobj_id`, `A_bsobj`.PTO_id, `A_bsobj`.name  FROM `B_engines` INNER JOIN `A_bsobj` ON  `A_bsobj`.`id` = `B_engines`.`bsobj_id` INNER JOIN `B_potencias` ON  `B_engines`.`id_potencia_marca` = `B_potencias`.`id_potencia_marca` WHERE `B_engines`.`id_potencia_marca` LIKE '{$id_pot}' AND  `B_engines`.`sector` LIKE '{$sec}' GROUP BY `B_engines`.`bsobj_id`  ORDER BY CONVERT(`refnum`,UNSIGNED INTEGER)";
		}
		$query = $this -> db ->query($q);
		return $query -> result_array();
	}


	public function get_engine_refnum($sector,$refnum){
		$q = " SELECT `potencia`,`sector`,`refnum`,`image_pos_x`,`image_pos_y`, `bsobj_id`, `A_bsobj`.PTO_id, `A_bsobj`.name,  `A_pcles`.`value`  FROM `B_engines` INNER JOIN `A_bsobj` ON  `A_bsobj`.`id` = `B_engines`.`bsobj_id` INNER JOIN `A_pcles` ON `A_bsobj`.`id`= `A_pcles`.`id_bsobj` WHERE `B_engines`.`id_potencia_marca` LIKE '{$id_pot}' AND  `B_engines`.`sector` LIKE '{$sec}' AND `A_pcles`.label LIKE 'unit_price'";
		$query = $this -> db ->query($q);
		return $query -> result_array();

	}

	function get_engine_psr($potencia,$sector,$refnum){
		$sql = "SELECT id FROM `B_engines` WHERE `potencia` = '{$potencia}' AND `sector` = '{$sector}' AND `refnum` = '{$refnum}'";
		$q = $this -> db -> query($sql);
		return $q -> row_array();
	}

	function delete_pcle($id){
		$q = "DELETE FROM `A_pcles` WHERE `id_pcle` LIKE '{$id}';";
		$query = $this -> db ->query($q);
	}

	function find_duplicated_pcle_label($pcle){

		$idbsobj = intval($pcle['id_bsobj']);
		$idpcle = intval($pcle['id_pcle']) ;

		$sql = "SELECT * FROM `A_pcles` WHERE `id_bsobj` = {$idbsobj} AND `label` = '{$pcle['label']}' AND `id_pcle` != {$idpcle} ";
		$q = $this -> db -> query($sql);
		return $q -> result_array();
	}


	function get_idbsobj_from_pto_id($pto_id){
		$q = " SELECT `id` FROM `A_bsobj` WHERE `PTO_id` = '{$pto_id}'";
		$query = $this -> db ->query($q);
		$res = $query -> row_array();

		return ($res >0)?$res:'false';
	}

	function get_pcles_from_bsobj_id($bsobj_id){
		$q = " SELECT * FROM `A_pcles` where `id_bsobj` = '{$bsobj_id}'";
		$query = $this -> db ->query($q);
		return $query -> result_array();
	}

	function get_pcle_val_by_id($id){
		$q = " SELECT `value` FROM `A_pcles` WHERE `id_pcle` = '{$id}'";
		$query = $this -> db ->query($q);
		return $query -> row_array();
	}

	function get_bsobj_byId($bsobj_id){
		$q = " SELECT `A_bsobj`.`PTO_id`,`A_bsobj`.`name`, `A_pcles`.`label`, `A_pcles`.`value` FROM `A_pcles` INNER JOIN `A_bsobj` ON  `A_bsobj`.`id` = `A_pcles`.`id_bsobj` WHERE `A_bsobj`.`id` LIKE '{$bsobj_id}'";
		$query = $this -> db ->query($q);
		return $query -> result_array();
	}

	function get_pcle($part_id,$plce_lbl){
		$q = "SELECT `id_pcle` FROM `A_pcles` WHERE `id_bsobj` = '{$part_id}' AND `label` = '{$plce_lbl}'";
		$query = $this -> db ->query($q);
		return $query -> row_array();
	}
	/*
	public function get_by_partnum_and_model($dta){

		$sql = "SELECT * FROM `partes_V2` WHERE `part_number` LIKE '". $dta[0] ."' AND `model` LIKE '".$dta[1]."'";
		//$myq = "SELECT * FROM `partes_V2` WHERE  `model` LIKE '"  "' AND `part_number` LIKE '"  "'";
		$query = $this -> db -> query($sql);
		return $query -> result_array();
	}
	public function get_parte_by_partnumber($pn){
		$query = $this -> db -> get_where('partes_V2', array('part_number' => $pn));
		return $query -> result_array();
	}



	public function get_by_serialnumber($sn) {

		$query = $this -> db -> get_where('serie', array('nserie' => $sn));
		return $query -> result_array();

	}

	public function get_productos($marca){
		$q="SELECT DISTINCT `tipo_producto_code`,`tipo_producto_name` FROM `productos_N` WHERE `marca` LIKE '" . $marca."'";
		$query = $this -> db ->query($q);
		return $query -> result_array();
	}


	public function get_by_prod_id($pid){
		$query = $this -> db -> get_where('productos_N', array('idproducto' => $pid));
		return $query -> result_array();

	}

*/
	// no estoy usando esto?? ****
public function get_tipodeusuario($userid) {
		$query = $this -> db -> get_where('B_usuarios', array('idusuario' => $userid));
		return $query -> row_array();
}


	public function get_user_data($userid){
		$query = $this -> db -> get_where('Usuarios', array('usuarios_id' => $userid));
		return $query -> row_array();

	}


	public function get_pedidos() {

		$this -> db -> select('*');
		$this -> db -> from('parts_pedidos');
		$this -> db -> order_by("order_number", "desc");
		$this -> db -> join('B_usuarios', 'B_usuarios.idusuario = parts_pedidos.client_id');
		$q = $this -> db -> get();
		return $q -> result_array();

	}

	public function get_order_number($order) {

		$this -> db -> where('order_number', $order);
		$this -> db -> from('parts_pedidos');
		$this -> db -> join('B_usuarios', 'B_usuarios.idusuario = parts_pedidos.client_id');
		$q = $this -> db -> get();
		return $q -> result_array();

	}

	public function get_partes($part = FALSE) {
		if ($part === FALSE) {
			$query = $this -> db -> get('partes');
			return $query -> result_array();
		}

		$query = $this -> db -> get_where('partes', array('model' => $part));
		return $query -> result_array();
	}



	// busca $searh en la columna $key en $tabla -- $result true devuelve result_array false row_array
	// si defino $key tengo que pasar $search sino falla
	public function get_filas($tabla, $query) {
		if ($tabla) {
			$this -> db -> from($tabla);
			if ($query) {
				$this -> db -> where($query);
				$result = $this -> db -> get();
				if ($result) {
					return $result -> result_array();
				} else {
					exit('falló el query en get_filas');
				}
			}
			$res = $this -> db -> get();
			return $res -> result_array();
		}
		exit('fallo llamada a get_filas tabla:' . $tabla);
	}

	/*
	 $array = array('nombre' => $nombre, 'titulo' => $titulo, 'estado' =>
	 $status);
	 $this->db->where($array);

	 */


	public function get_sector_by_potencia_id($pot){

		$sl_query = "SELECT DISTINCT `sector` FROM `B_engines` WHERE  `id_potencia_marca` =  '{$pot}' ";
		$q = $this -> db -> query($sl_query);
		return $q -> result_array();

	}

	public function get_potencias($strokes){

		//select distinct name,id_potencia_marca from B_potencias where marca like 'POWERTEC' or marca like 'TITAN' order by order_number asc
		$query = "SELECT `name`,`id_potencia_marca` FROM `B_potencias` WHERE  `marca` NOT LIKE 'YAMAHA' AND `strokes` = '{$strokes}' GROUP BY `name` ORDER BY `order_number` ASC ";
		$q = $this -> db -> query($query);
		return $q -> result_array();

	}


	public function get_sectores(){
		$query = "SELECT DISTINCT `sector` FROM `B_engines`";
		$q = $this -> db -> query($query);
		return $q -> result_array();
	}


	public function get_importers(){
		$query = "SELECT * FROM `B_usuarios` WHERE `tipousuario` LIKE '%importador%' ORDER BY `idpais` ASC ";
		$q = $this -> db -> query($query);
		return $q -> result_array();
	}

	public function get_orders($owner,$state,$mode){

		$query = array('all'=>"SELECT * FROM `parts_pedidos` WHERE `client_id` LIKE '{$owner}' AND `state` LIKE {$state} ORDER BY `order_number` ASC ",
						'group'=>"SELECT `order_number` FROM `parts_pedidos` WHERE `client_id` LIKE '{$owner}' AND `state` LIKE {$state} GROUP BY `order_number` ASC ");
		$q = $this -> db -> query($query[$mode]);
		return $q -> result_array();
	}

	// ******************
	// busca DISTINCT en columna para completar selectores de partes, $selected es la seleccion actual y $sl_number es el numero de orden de selector
	// con el cual defino que seleccion y campos estoy haciendo.
	//
	/*
	public function get_nuevo_selector($selected, $sl_number, $prev_selected,$marca) {
		//echo $sl_number;

		if ($sl_number == 0) {

			$sl_query = "SELECT DISTINCT `anio` FROM `productos_N` WHERE `tipo_producto_code` ='" . $selected . "' LIMIT 1";

		}
		if ($sl_number == 1) {

			$sl_query = "SELECT  `nombre`,`codigo` FROM `productos_N` WHERE `tipo_producto_code` ='" . $prev_selected['s0'] . "'AND `anio` <= '". $prev_selected['s1'] ."' AND `marca` = '". strtolower($marca) ." ' ORDER BY `order_number` ";
		}
		if ($sl_number == 2) {
			// *-*-*-*-*
			$sl_query = "SELECT DISTINCT `sector` ,`model` FROM `partes_V2` WHERE  `model` =  '" . $selected . "' AND `product_ID` = '" . $prev_selected['s0'] . "'AND `año_discontinuado` = '0'";
		}
		if ($sl_number == 3) {

			$sl_query = "SELECT * FROM `partes_V2` WHERE  `model` = '{$prev_selected['s2']}' AND `product_ID` = '{$prev_selected['s0']}'  AND `año_discontinuado` = '0' AND `sector` = '{$prev_selected['s3']}' AND image_refnum > '0' ORDER BY image_refnum";
		}
		if ($sl_number == 4) {

		}
		$query = $this -> db -> query($sl_query);
		return $query -> result_array();

	}
	*/



}
