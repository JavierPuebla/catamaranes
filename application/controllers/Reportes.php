<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reportes extends CI_Controller {

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
    

    // ****** GET REPORTES DATA SEGUN USUARIO 
     
      
      $fechin = "2017-11-01";
      $fechout = "2017-12-30";
      $tikets = $this->get_tikets_report($fechin, $fechout);
      
    // ****** PUT REPORTES DATA EN VAR   
        $user_data = $this -> app_model -> get_user_data($user['userId']);
        $header = ['Fecha','Servicio',' Hora','Cantidad Tickets','Total $'];
        $var=array('data'=>$tikets,'header'=>$header,'user'=>$user['userId']);
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
        $this -> load -> view('reportes_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }


/*
cantTickets:"3"
formaDePago:"EFECTIVO"
hora_salida:"10:00"
nroTransacTarjeta:""
servicios_id:"1"
tarifa:"180.00"
histServiciosId:
fecha_servicio:

*/
  public function get_tikets_report($fin,$fout){
    
    $arr_tktsdia = [];
    $arr_ids = $this -> app_model -> get_hist_servicios_ids($fin,$fout);
    foreach ($arr_ids as $hsid) {
      $t = ['hora'=>$hsid['hora_salida'],'servicio'=>$this -> app_model -> get_tkts_by_hsid($hsid['id'])]; 
      if($t['servicio']['id']!= null)
        $arr_tktsdia[] = $t;
    }
    return $arr_tktsdia;
  }

}
