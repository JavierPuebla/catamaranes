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
    $cls_name = 'reservas';
    // ****** TABLA DE PERSISTENCIA DE DATOS DE LA CLASE 
    $table = 'cat_reservas';
    // ****** RUTA DE ACCESO DEL CONTROLLER
    $route = 'reservas/';
    // ****** ******************  

    $user = $this -> session -> userdata('logged_in');
    if (is_array($user)) {

      // ****** NAVBAR ********
      $userActs = $this -> app_model -> get_activities($user['userId']);
      $acts = explode(',',$userActs['acciones_id']);

      // ************  INIT DATA PARA EL VIEW *********** 
      $user_data = $this -> app_model -> get_user_data($user['userId']);
      $hoy = Date("d/m/Y");
      $hora = $this ->cmn_functs->mk_dpdown('cat_horarios',['id','hora_salida'],'WHERE disponible = \'S\' AND id > -1 ORDER BY id ASC','selecciona un horario');
      $tpserv = $this ->cmn_functs->mk_dpdown('cat_servicios',['id','tipo','subtipo'],'ORDER BY id ASC','selecciona un paseo');
      $servicios_abordo_reserva = $this ->cmn_functs->mk_dpdown('cat_servicios_abordo_reserva',['id','nombre'],'ORDER BY id ASC','selecciona un servicio');

      $var=array(
        'data'=> '',
        'dpdown_hora'=> $hora,
        'tpserv'=> $tpserv,
        'servicios_abordo_reserva'=>$servicios_abordo_reserva,
        'fecha'=> $hoy,
        'user'=> $user,
        'route'=>$route
      );

      $this -> load -> view('header-responsive');
      $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
      $this -> load -> view($cls_name.'_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }

  public function create(){
    $data = $this->input->post('data');
    $data['fecha_reserva'] = $this->cmn_functs->fixdate_ymd($data['fecha_reserva']);
    $hs_id = $this->cmn_functs->create_servicio($data['fecha_reserva'],$data['horarios_id'],$data['servicios_id']);
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
        // 'servicios_abordo_reserva'=>$data['servicios_abordo_reserva']
    );
    $result = $this -> app_model -> insert('cat_reservas',$tsave); 
    echo json_encode(array(
                        'callback'=>'getReservas',
                        'clbkparam'=> array(
                          'fecha'=>$data['fecha_reserva'],   
                          'scope'=>'day'
                        )
                      )
                    );
    
  }
  
  public function update(){
    $d = $this->input->post('data');
    $this->app_model->delete('cat_reservas','id',$d['id']);
    if($this->input->post('eliminar_reserva') != 'true'){
      $this->create();
    }else{
      echo json_encode(array(
                        'callback'=>'getReservas',
                        'clbkparam'=> array(
                          'fecha'=>$d['fecha_reserva'],   
                          'scope'=>'day'
                        )
                      )
                    );
    }
  }
  
  function upd(){
    $p = $this->input->post();
    if($p['deleterec'] == 'true'){
      $this->app_model -> delete($p['info']['msg'],'id',$p['id']);  
    }else{
      $result = $this -> app_model -> update($p['info']['msg'],$p['data'],'id',$p['id']);    
    }
    echo json_encode(array(
                        'callback'=>'call',
                        'param'=>$p['info']

                      )
                    );
  }

  function meet(){
    $p = $this->input->post('msg');
    $date = $this->cmn_functs->fixdate_ymd($p['fecha']);
    $recs = $this ->app_model->get_reservas_bydate($date,$p['scope']);
    $ld = $this->cmn_functs->set_daos($recs);
    echo json_encode(array(
                      'info'=>$this->input->post(),
                      'callback'=>'dao_mk_list',
                      'param'=>array('list_data'=>$ld)
                      )
                    );
  }
  
  public function update_drop_down(){
    $h = $this->app_model->get_horarios();
    $res = array();
    foreach ($h as $hr) {
      $hrtoserv = explode(',', $hr['disp_servicios_id']);
      foreach ($hrtoserv as $d) {
        if($d == $this->input->post('serv_id') && $hr['id']>0)
          $res[]=$hr['id'];    
      }
    } 
    echo json_encode($res);
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
        'id'=> $value['id']
      );
    }
    echo json_encode($res);
  }
// END *************************************************
}
