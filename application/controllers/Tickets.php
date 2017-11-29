<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tickets extends CI_Controller {

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
      $userActs = $this -> app_model -> get_activities($user['userId']);
      $acts = explode(',',$userActs['acciones_id']);
    // ****** GET SERVICIOS DISPONIBLES put it into var
      //$fechaHora = date('H:i');
      $fecha = '10/11/2017';
      $servicios = [];
      $horarios = $this -> app_model -> get_hora_servicios_disponibles($fecha);
      $tipos = $this -> app_model -> get_tipos_servicios();
      foreach ($horarios as $h) {
        foreach ($tipos as $t) {
          $s = $this->app_model ->get_servicios_disponibles($fecha,$h['hora_salida'],$t['tipo']);
          if($s)
            $servicios[]= $s;
        }
      }
      
      $user_data = $this -> app_model -> get_user_data($user['userId']);
        $var=array('data'=>$servicios,'user'=>$user['userId'],'horarios'=>$horarios,'tipos'=>$tipos);
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
        $this -> load -> view('tickets_view',$var);
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
  public function make_tkt(){
    $data = $this->input->post();
    $result = $this -> app_model -> insert_tikets($data); 
    echo json_encode(array('result'=>$result));
  }

}
