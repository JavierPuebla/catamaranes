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
      $header = ['Fecha','Hora Salida','Tipo','Subtipo','Estado','Cant Pasaj','Barco','Tripul.','Acc.'];
      $var=array('data'=>'','header'=>$header,'user'=>$user['userId']);
        
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usuario']));
        $this -> load -> view('operaciones_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }

  public function listado_servicios_dia(){
    $data = $this->input->post();
    $result = $this -> app_model -> get_servicios($data['fecha']); 
    echo json_encode(array('result'=>$result));

  }
}
