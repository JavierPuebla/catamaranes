<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Operaciones extends CI_Controller {

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
    // ****** NAVBAR DATA ********
      $userActs = $this -> app_model -> get_activities($user['userId']);
      $acts = explode(',',$userActs['acciones_id']);
    
    // ****** END NAVBAR DATA ********
    
      $user_data = $this -> app_model -> get_user_data($user['userId']);
    // ****** DROPDOWN DATA ******  
      $hora = $this ->cmn_functs->mk_dpdown('cat_horarios',['id','hora_salida'],'WHERE disponible = \'S\' AND id >= 0 ORDER BY id ASC');  
      $tps = $this ->cmn_functs->mk_dpdown('cat_servicios',['id','tipo','subtipo'],'GROUP BY cod_tipo_subtipo ASC');  
      $bco = $this ->cmn_functs->mk_dpdown('cat_barcos',['id','nombre_barco'],'WHERE estado_barco = \'D\'');  
      $trpl = $this ->cmn_functs->mk_dpdown('cat_personal',['id','nombre','apellido','actividad'],'');  
      $trpl_keys =array_keys($trpl);
      $var=array('data'=>'',
                'dpdown_hora'=>$hora,
                'tiposerv_dpdown_data'=>$tps,
                'dpdown_barco'=>$bco,
                'trpl'=>$trpl,
                'trpl_keys'=>$trpl_keys,
                'user'=>array('id'=>$user_data['id'],
                'tipo'=>$user_data['tipo_usuario'])
              );
    // ****** END DROPDOWN DATA ******
    // ****** SET FIELDS HEADERS AND FIELD PROCESS


    // ****** LOADING VIEWS ******  
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
        $this -> load -> view('operaciones_view',$var);
    

    } else {
      redirect('login', 'refresh');
    }
  }
  // ****** END INDEX  ******


  function create(){
    $data = $this->input->post();
    $data['fecha_servicio'] = $this->cmn_functs->fixdate_ymd($data['fecha_servicio']);
    $check_if_exists = $this->app_model->check_if_service_exists($data);
    if(empty($check_if_exists)){
      $result = $this -> app_model -> insert('cat_historial_servicios',$data); 
    }else{
      $result = $this -> app_model -> update('cat_historial_servicios',$data,'id',$check_if_exists->id);
    }
    echo json_encode(array('result'=>$result));
  }
  
  function update(){
    $data = $this->input->post();
    $data['fecha_servicio'] = $this->cmn_functs->fixdate_ymd($data['fecha_servicio']);
    $check_if_exists = $this->app_model->check_if_service_exists($data);
    
    if(!empty($check_if_exists)){
      $result = $this -> app_model -> update('cat_historial_servicios',$data,'id',$check_if_exists->id);  
    }
    // else{
    //   $this->app_model->delete('cat_historial_servicios','id',$data['id']);
    //   $result = 'record deleted';
    // }
    echo json_encode(array('result'=>$data));
  }

  
  function listado_servicios_dia(){
    $data = $this->input->post();
    $f = $this->cmn_functs->fixdate_ymd($data['fecha']);
    $result = $this -> app_model -> get_servicios($f); 
    // $header = ['Fecha','Hora Salida','Tipo','Subtipo','Pax Ticket','Pax Reservas','Barco','Tripul.','Acc.'];
    $header = ['Fecha','Hora Salida','Tipo','Subtipo','Pax Ticket','Pax Reservas','Barco','Tripul.'];
    echo json_encode(array('header'=>$header, 'result'=>$result));

  }

 


}
