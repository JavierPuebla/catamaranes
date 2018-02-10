<?php
class app_model extends CI_Model {
	
	public function __construct() {
		$this -> load -> database();
	}

	function get_venta_ws_hs_id($fecha){
		$q = "SELECT * FROM cat_historial_servicios WHERE fecha_servicio = '{$fecha}' AND horarios_id = '-1' ";
		$x = $this->db->query($q);
		return $x -> row();

	}

	function get_tkts_by_date($fin,$fout,$filter){
		
		$q = "SELECT c.*,horas.hora_salida, s.tipo,s.subtipo ,(SELECT SUM(c.importe_neto))as total , COUNT(*) as cantkts FROM cat_comprobantes c LEFT OUTER JOIN cat_servicios s on s.id = c.servicios_id  LEFT OUTER JOIN cat_historial_servicios hs on hs.id = c.hist_servicio_id LEFT OUTER JOIN cat_horarios horas on horas.id = hs.horarios_id WHERE c.fecha >= '{$fin}' AND c.fecha <= '{$fout}'  {$filter} GROUP BY c.hist_servicio_id ";
		$x = $this->db->query($q);
		return $x -> result_array();
	}

	function get_comprobantes_by_date($fin,$fout,$f){
		$q = "SELECT c.*, cmps.nombre, u.nombre_usuario, u.apellido_usuario FROM cat_comprobantes c LEFT OUTER JOIN cat_tipos_comprobantes cmps on cmps.id = c.tipos_comprobantes_id LEFT OUTER JOIN usuarios u ON u.id_usuario = c.personal_id WHERE c.fecha >= '{$fin}' AND c.fecha <= '{$fout}'  $f ORDER BY c.fecha,c.personal_id,c.tipos_comprobantes_id";
		$x = $this->db->query($q);
		return $x -> result_array();
	}

	
	function get_dpdown_data($tbl,$fields,$modif){
		$q = "SELECT $fields FROM $tbl $modif ";
		$x = $this->db->query($q);
		return $x -> result_array();
	}

	function get_hist_servicios_ids($fechin,$fechout){
		$q = "SELECT hs.id,hs.fecha_servicio,hs.cod_tipo_subtipo_servicios,hr.hora_salida hora_salida FROM `cat_historial_servicios` hs LEFT OUTER JOIN cat_horarios hr on hr.id = hs.horarios_id WHERE hs.fecha_servicio >= '{$fechin}' AND hs.fecha_servicio <= '{$fechout}' ORDER BY `id` ASC";
		$x = $this->db->query($q);
		return ($x)?$x -> result_array() : false;
	}

	// estas dos son iguales *****
	function check_if_service_exists($data){
		$q = "SELECT * FROM cat_historial_servicios WHERE fecha_servicio = '{$data['fecha_servicio']}' AND horarios_id = '{$data['horarios_id']}' AND servicios_id = '{$data['servicios_id']}'";
		$x = $this->db->query($q);
		return $x -> row();	
	}
	function check_hs_exist($fecha, $horarios_id, $servicios_id){
		$q= "SELECT * FROM `cat_historial_servicios` WHERE fecha_servicio = '{$fecha}' AND horarios_id = '{$horarios_id}' AND servicios_id = '{$servicios_id}' ";
		$x = $this->db->query($q);
		return $x -> row();
	}
	// ************ end 

	function get_servicios($fecha){
			$res = [];
			//$q= "SELECT hs.id,hs.fecha_servicio,hora.id as salida_id,hs.cod_tipo_subtipo_servicios, hora.hora_salida as hora_salida, s.subtipo, hs.estado, hs.cant_pasajeros,hs.tripulacion, s.tipo, s.id id_servicios, b.nombre_barco as barco, b.id_barco FROM `cat_historial_servicios` hs  LEFT OUTER JOIN cat_horarios hora on hora.id = hs.horarios_id LEFT OUTER JOIN cat_servicios s on hs.servicios_id = s.id LEFT OUTER JOIN cat_barcos b on hs.barcos_id = b.id_barco WHERE hs.fecha_servicio = '{$fecha}' GROUP BY hs.id ORDER BY hora_salida ASC";
			$q="SELECT hs.id,hs.fecha_servicio,hora.id as salida_id,hs.cod_tipo_subtipo_servicios, hora.hora_salida as hora_salida, s.subtipo, hs.estado, hs.cant_pasajeros,hs.tripulacion, s.tipo, s.id id_servicios, b.nombre_barco as barco, b.id_barco,(SELECT COUNT(*) FROM cat_comprobantes WHERE hist_servicio_id = hs.id) comprobantes_cantpax, (SELECT SUM(cant_pasajeros_reserva) FROM cat_reservas WHERE historial_servicios_id = hs.id) reservas_cantpax FROM `cat_historial_servicios` hs  LEFT OUTER JOIN cat_horarios hora on hora.id = hs.horarios_id LEFT OUTER JOIN cat_servicios s on hs.servicios_id = s.id LEFT OUTER JOIN cat_barcos b on hs.barcos_id = b.id_barco  WHERE hs.fecha_servicio = '{$fecha}' GROUP BY hs.id ORDER BY hora_salida ASC";
			$x = $this->db->query($q);
			$hserv = $x -> result_array();
			$trp = [];
			$staff = [];	
			foreach ($hserv as $hs) {
				if(!empty($hs['tripulacion'])){
					$trp = explode(",", $hs['tripulacion']);
					$staff = [];
					foreach ($trp as $t) {
						$staff[]=$this->get_tripulacion($t);
					}
				}
				$res[]=['servicio'=>$hs,'tripulacion'=>$staff];	
			}
			return $res;
	}

	function get_tripulacion($id){
		$q="SELECT id,nombre,apellido,actividad FROM `cat_personal` WHERE id= '{$id}' ";
		$x = $this->db->query($q);
		return $x -> result_array();
	}	

	
	function get_horarios($modif){
		$q="SELECT * FROM `cat_horarios` WHERE disponible = 'S'".$modif;
		$x = $this->db->query($q);
		return $x -> result_array();
	}

	// no sirve
	// function get_hrs_disp_by_servid($serv_id){
	// 	$q="SELECT * FROM `cat_horarios` WHERE disp_servicios_id LIKE '%{$serv_id}%'";
	// 	$x = $this->db->query($q);
	// 	return $x -> result_array();
	// }

	// to deprecate ************
	function check_serv_exists($f){
		$q= "SELECT * FROM `cat_historial_servicios` WHERE fecha_servicio = '{$f}' ";
			$x = $this->db->query($q);
			return ($x)?$x -> row_array() : false;
	}

	function get_servicios_disponibles($fecha,$horarios_id,$tipo,$subtipo){
			$q= "SELECT hs.id,hs.fecha_servicio,hora.hora_salida,hs.horarios_id, s.tipo,s.subtipo,s.tarifa,s.id servicios_id,b.nombre_barco,b.capacidad_barco FROM `cat_historial_servicios` hs LEFT OUTER JOIN cat_horarios hora on hora.id = hs.horarios_id LEFT OUTER JOIN cat_servicios s on hs.servicios_id = s.id LEFT OUTER JOIN cat_barcos b on hs.barcos_id = b.id_barco WHERE hs.fecha_servicio = '{$fecha}' AND hs.horarios_id = '{$horarios_id}' AND s.tipo = '{$tipo}' AND s.subtipo = '{$subtipo}' AND hs.estado LIKE 'D' AND hora.id > 0 ORDER BY s.tipo ASC";
			$x = $this->db->query($q);
			return ($x)?$x -> row() : false;
	}

	function get_servicios_boleteria($fecha){
		$q ="SELECT hs.id,hs.fecha_servicio,hora.hora_salida,hs.horarios_id, s.tipo,s.subtipo,s.tarifa,s.id servicios_id,b.nombre_barco,b.capacidad_barco FROM `cat_historial_servicios` hs LEFT OUTER JOIN cat_horarios hora on hora.id = hs.horarios_id LEFT OUTER JOIN cat_servicios s on hs.servicios_id = s.cod_tipo_subtipo LEFT OUTER JOIN cat_barcos b on hs.barcos_id = b.id_barco WHERE hs.fecha_servicio = '{$fecha}'  AND hora.id > 0 ORDER BY `hora`.`hora_salida` ASC, `s`.`tipo` DESC, `s`.`subtipo` DESC";
		$x = $this->db->query($q);
		return $x -> result_array();
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

	function get_all($table,$id=null){
		$q="SELECT * from {$table}";
		if($id != null)
			$q .="WHERE id = {$id}";
		$x = $this->db->query($q);
		return $x -> row();
	}

	function delete($table,$field_name,$id){
		$this->db->delete($table, array($field_name => $id));
	}

	function update($table,$data,$ikey,$id){

		$this->db->where($ikey, $id);
		return $this->db->update($table, $data);
	}

	function insert($table,$data){
		return ($this->db->insert($table,$data)>0) ? $this->db->insert_id() :false;
	}


	function anular_tkt($ids_arr){
		$tr=array(); 
		foreach ($ids_arr as $id) {
			$this->db->where('id',$id);
			$this->db->update('cat_comprobantes',array(' estado_comprobantes'=>0));
			$t = $this->db->affected_rows();
			if($t == 0){
				$tr[]=$id;
			}
		}
		if(count($tr)>0){return array('status'=>false,'data'=>$tr);}else{return array('status'=>true);};
	}

	function insert_tikets($data){
		$cantTkts = intval($data['cantTickets']);
		$this->db->trans_start();
		for ($i=0; $i < $cantTkts; $i++) { 
			$table ='cat_comprobantes';
			$datos = [
				'fecha'=> $data['fecha'],
				'tipos_comprobantes_id' => $data['tipos_comprobantes_id'],
				'importe_neto' => $data['tarifa'],
				'rnr_id' => $data['chk_sel'],
				'servicios_id'=> $data['servicios_id'],
				'hist_servicio_id' => $data['hist_servicio_id'],
				'forma_pago'=> $data['forma_pago'],
				'usuarios_id' => $data['usuarios_id'],
				'clientes_id' => $data['clientes_id'],
				'status' => $data['status'],
				'id_transaccion' =>$data['id_transaccion']
			];  
			// $hs_data = $this ->get_cant_pasajeros($data['histServiciosId']);
			// $last_cantpax = intval($hs_data['cant_pasajeros'])+1;
			// $test = $this -> update('cat_historial_servicios',array('cant_pasajeros'=>$last_cantpax),'id',$data['histServiciosId']);
			
			$this -> db -> insert($table,$datos);
			$result_arr[]=$this->db->insert_id(); 
		}	
		$this->db->trans_complete();
		if($this->db->trans_status())
			return $result_arr;
		return $this->db->trans_status();
	}


	function get_reservas_bydate($fecha,$scope_all){
			$scp = ($scope_all === 'true')?'>=':"="; 
			$q= "SELECT r.id_reserva,hs.horarios_id,hs.servicios_id,r.historial_servicios_id,r.clientes_id,r.usuarios_id,r.puntodeventa_id,r.fecha_reserva, s.tipo,s.subtipo,r.cant_pasajeros_reserva,r.monto_pagado_reserva,r.monto_total_reserva,s.tarifa,b.nombre_barco,u.usr_usuario,cl.nombre_contacto_cliente,cl.email_cliente,cl.telefono_contacto_cliente,r.observaciones_reserva,r.estado_reserva,r.servicio_bar_reserva,horas.hora_salida FROM cat_reservas r LEFT OUTER JOIN usuarios u ON r.usuarios_id = u.id_usuario LEFT OUTER JOIN cat_historial_servicios hs ON r.historial_servicios_id = hs.id LEFT OUTER JOIN cat_horarios horas on horas.id = hs.horarios_id LEFT OUTER JOIN cat_clientes cl ON r.clientes_id = cl.id_cliente LEFT OUTER JOIN cat_barcos b ON b.id_barco = hs.barcos_id LEFT OUTER JOIN cat_servicios s ON hs.servicios_id = s.id WHERE r.fecha_reserva {$scp} '{$fecha}' ORDER BY r.fecha_reserva, hs.horarios_id ASC";
			$x = $this->db->query($q);
			return $x -> result_array();
	}

	
	function get_tarifas(){
		$q= "SELECT * FROM `cat_servicios`";
			$x = $this->db->query($q);
			return ($x)?$x -> result_array() : false;
	}

	function get_tarifa_by_srvsid($sid){
		$query = $this -> db -> get_where('cat_servicios', array('id' => $sid));
		return ($query)?$query -> row()->tarifa : false;
	}



	// autocomplete de clientes
	function atcp_cli($t){

		$q= "SELECT razon_social_cliente as label ,id_cliente,nombre_contacto_cliente,telefono_contacto_cliente,razon_social_cliente,email_cliente FROM `cat_clientes` WHERE nombre_contacto_cliente LIKE '%{$t}%' OR razon_social_cliente LIKE '%{$t}%'";
		$x = $this->db->query($q);
		return $x -> result_array();
	}

	function get_cliente_by_email($em){
		$query = $this -> db -> get_where('cat_clientes', array('email_cliente' => $em));
		return ($query)?$query -> row() : false;
	}

	function get_cliente_by_id($id){
		$query = $this -> db -> get_where('cat_clientes', array('id_cliente' => $id));
		return ($query)?$query -> row() : false;
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

	public function get_cant_pasajeros($hs_id){
		$query = $this -> db -> get_where('cat_historial_servicios', array('id' => $hs_id));
		return $query -> row_array();

	}

	public function get_user_data($userid){
		$query = $this -> db -> get_where('usuarios', array('id_usuario' => $userid));
		return $query -> row_array();

	}



}
