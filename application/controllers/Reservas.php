<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reservas extends CI_Controller {

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
      $hoy = Date("d/m/Y");
      $var=array('data'=>'','fecha'=> $hoy,'user'=>$user['userId']);
        
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
        $this -> load -> view('reservas_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }

  

  public function list_reservas_dia(){
    $data = $this->input->post();
    $datefix_ymd = substr($data['fecha'],strrpos($data['fecha'],'/')+1).'/'.substr($data['fecha'],strpos($data['fecha'],'/')+1,2).'/'.substr($data['fecha'],0,strpos($data['fecha'],'/'));
    
    
    $result = $this -> app_model -> get_reservas_bydate($datefix_ymd,$data['scope_all']); 
    $header = ['fecha', 'Hora Salida','Tipo','Subtipo','Cant Pax','Seña','Saldo','Barco','Operador','Cliente','Observac','Acc.'];
    echo json_encode(array('header'=>$header,'result'=>$result));

  }
  
  public function get_tarifas(){
    $result= $this->app_model->get_tarifas();
    echo json_encode(array('tarifas'=>$result));

  }

  public function autocomplete_clientes(){
    parse_str($_SERVER['QUERY_STRING'], $_GET); 
    $r = $this->app_model->atcp_cli($_GET['term']);
    //$res = Array('label'=>$r['razon_social_cliente'],'value'=> $r);
    echo json_encode($r);
  }
}
