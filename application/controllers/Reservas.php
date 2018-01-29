<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reservas extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this -> load -> model('app_model');
    $this->load->helper('array');
    $this->load->helper('form');
    $this->load->library('cmn_functs');
    // Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('America/Argentina/Buenos_Aires');

  }

  public function index() {
    $user = $this -> session -> userdata('logged_in');
    if (is_array($user)) {
      
    // ****** NAVBAR ********
      $userActs = $this -> app_model -> get_activities($user['userId']);
      $acts = explode(',',$userActs['acciones_id']);
    
      $user_data = $this -> app_model -> get_user_data($user['userId']);
      $hoy = Date("d/m/Y");
      $hora = $this ->cmn_functs->mk_dpdown('cat_horarios',['id','hora_salida'],'WHERE disponible = \'S\' AND id > 0 ORDER BY id ASC');
      $tpserv = $this ->cmn_functs->mk_dpdown('cat_servicios',['id','tipo','subtipo'],'GROUP BY cod_tipo_subtipo ASC');


      $var=array(
        'data'=> '',
        'dpdown_hora'=> $hora,
        'tpserv'=> $tpserv,
        'fecha'=> $hoy,
        'user'=> $user
      );

        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
        $this -> load -> view('reservas_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }

  public function create(){
    $data = $this->input->post();
    $data['fecha_reserva'] = $this->cmn_functs->fixdate_ymd($data['fecha_reserva']);
    $hs_id = $this->cmn_functs->check_historial_servicios($data['fecha_reserva'],$data['horarios_id'],$data['servicios_id']);
    $clid=$this->cmn_functs->check_cliente($data['nombre_contacto_cliente'],$data['telefono_contacto_cliente'],$data['email_cliente']);
    $tsave = array(
        'clientes_id'=>$clid,
        'historial_servicios_id'=>$hs_id,
        'fecha_reserva'=>$data['fecha_reserva'],
        'cant_pasajeros_reserva'=>$data['cant_pasajeros_reserva'],
        'monto_pagado_reserva'=>$data['monto_pagado_reserva'],
        'monto_total_reserva'=>$data['monto_total_reserva'],
        'observaciones_reserva'=>$data['observaciones_reserva'],
        'usuarios_id'=>$data['usuarios_id'],

    );
    $result = $this -> app_model -> insert('cat_reservas',$tsave); 
    echo json_encode(array('result'=>$result,'fecha'=>$data['fecha_reserva']));
  }
  

  public function update(){
    $this->app_model->delete('cat_reservas','id_reserva',$this->input->post('id'));
    if($this->input->post('eliminar_reserva') == 'false'){
      $this->create();
    }else{
      echo json_encode(array('result'=>'delete OK','fecha'=> $this->input->post('fecha_reserva')));  
    }
    
  }

  public function list_reservas_dia(){
    $data = $this->input->post();
    $date = $this->cmn_functs->fixdate_ymd($data['fecha']);
    $result = $this -> app_model -> get_reservas_bydate($date,$data['scope_all']); 
    $header = ['fecha', 'Hora Salida','Tipo','Subtipo','Cant Pax','Seña','Saldo','Barco','Operador','Observac','Acc.'];
    echo json_encode(array('header'=>$header,'result'=>$result));

  }
  
  public function get_tarifas(){
    $result= $this->app_model->get_tarifas();
    echo json_encode(array('tarifas'=>$result));

  }

  public function autocomplete_clientes(){
    parse_str($_SERVER['QUERY_STRING'], $_GET); 
    $r = $this->app_model->atcp_cli($_GET['term']);
    // echo json_encode(Array('label'=>$r['label'],'value'=> $r));
    //echo json_encode($r);
    foreach($r as $key => $value){
        $res[] = array('label'=>$value['nombre_contacto_cliente'],
                       'value'=>$value['nombre_contacto_cliente'],
                       'email'=>$value['email_cliente'],
                       'tel'=> $value['telefono_contacto_cliente'],
                       'id_cliente'=> $value['id_cliente']
                     );
        }
        echo json_encode($res);
  }
}
