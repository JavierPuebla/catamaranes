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
    // ****** NAVBAR ********
      $userActs = $this -> app_model -> get_activities($user['userId']);
      $acts = explode(',',$userActs['acciones_id']);
    
      $user_data = $this -> app_model -> get_user_data($user['userId']);
      
      $hora = $this ->cmn_functs->mk_dpdown('cat_horarios',['id','hora_salida'],'WHERE disponible = \'S\' ORDER BY id ASC');  
      $tps = $this ->cmn_functs->mk_dpdown('cat_servicios',['codigo_tipo','tipo','subtipo'],'GROUP BY codigo_tipo ASC');  
      $bco = $this ->cmn_functs->mk_dpdown('cat_barcos',['id_barco','nombre_barco'],'WHERE estado_barco = \'D\'');  
      $trpl = $this ->cmn_functs->mk_dpdown('cat_personal',['id','nombre','apellido','actividad'],'');  
      $trpl_keys =array_keys($trpl);
      $var=array('data'=>'',
                'dpdown_hora'=>$hora,
                'tiposerv_dpdown_data'=>$tps,
                'dpdown_barco'=>$bco,
                'trpl'=>$trpl,
                'trpl_keys'=>$trpl_keys,
                'user'=>array('id'=>$user_data['id_usuario'],
                'tipo'=>$user_data['tipo_usuario'])
              );
        
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
        $this -> load -> view('operaciones_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }
  function create(){
    $data = $this->input->post();
    $data['fecha_servicio'] = $this->fixdate_ymd($data['fecha_servicio']);
    $result = $this -> app_model -> insert('cat_historial_servicios',$data); 
    echo json_encode(array('result'=>$result));
  }
  
  function update(){
    $data = $this->input->post();
    $result = $this -> app_model -> update('cat_historial_servicios',$data,'id',$data['id']); 
    echo json_encode(array('result'=>$result));
  }

  function listado_servicios_dia(){
    $data = $this->input->post();
    $datefix_ymd = substr($data['fecha'],strrpos($data['fecha'],'/')+1).'/'.substr($data['fecha'],strpos($data['fecha'],'/')+1,2).'/'.substr($data['fecha'],0,strpos($data['fecha'],'/'));
    
    $result = $this -> app_model -> get_servicios($datefix_ymd); 
    $header = ['Fecha','Hora Salida','Tipo','Subtipo','Estado','Cant Pasaj','Barco','Tripul.','Acc.'];
    echo json_encode(array('header'=>$header, 'result'=>$result));

  }

 


}
