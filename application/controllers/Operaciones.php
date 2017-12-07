<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Operaciones extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this -> load -> model('app_model');
    $this->load->helper('array');
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
        
      $var=array('data'=>'','user'=>array('id'=>$user_data['id_usuario'],'tipo'=>$user_data['tipo_usuario']));
        
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
        $this -> load -> view('operaciones_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }

  public function listado_servicios_dia(){
    $data = $this->input->post();
    $datefix_ymd = substr($data['fecha'],strrpos($data['fecha'],'/')+1).'/'.substr($data['fecha'],strpos($data['fecha'],'/')+1,2).'/'.substr($data['fecha'],0,strpos($data['fecha'],'/'));
    
    $result = $this -> app_model -> get_servicios($datefix_ymd); 
    $header = ['Fecha','Hora Salida','Tipo','Subtipo','Estado','Cant Pasaj','Barco','Tripul.','Acc.'];
    echo json_encode(array('header'=>$header, 'result'=>$result));

  }

}
