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
      //$hora = date('H:i');
      
      $servicios = [];
      $horarios = $this -> app_model -> get_hora_servicios_disponibles();
      $tipos = $this -> app_model -> get_tipos_servicios();
      foreach ($horarios as $h) {
        foreach ($tipos as $t) {
          $s = $this->app_model ->get_servicios_disponibles($h['hora_salida'],$t['tipo']);
          if($s)
            $servicios[]= $s;
        }
      }
      
      
      $user_data = $this -> app_model -> get_user_data($user['userId']);
        $var=array('data'=>$servicios,'user'=>$user_data);
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usuario']));
        $this -> load -> view('tickets_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }


  public function make_tkt(){
    $data = $this->input->post();
    echo json_encode($data);

  }

}
