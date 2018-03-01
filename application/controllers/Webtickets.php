<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Webtickets extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this ->load->model('app_model');
    $this->load->helper('array');
    // Establecer la zona horaria predeterminada a usar
    date_default_timezone_set('America/Argentina/Buenos_Aires');
  }

  public function index() {
    $user = $this -> session -> userdata('logged_in');
    $user_data = array('id'=>999,'tipo_usuario'=>'web');
    if ($user_data['id'] == 999) {
      // ****** GET SERVICIOS DISPONIBLES put it into var
     $fecha = Date('Y-m-d');
       $servicios = $this->get_serv_disponibles($fecha);
        $var=array('data'=>$servicios,'user'=>array('id'=>$user_data['id'],'tipo'=>$user_data['tipo_usuario']));
        $this -> load -> view('header-responsive');
        $this -> load -> view('webtickets_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }
  
  public function make_tkt(){
    $data = $this->input->post();
    $result = $this -> app_model -> insert_tikets($data); 
    echo json_encode(array('result'=>$result));
  }

  function get_serv_disponibles($fecha){
      $horarios = $this -> app_model -> get_hora_servicios_disponibles($fecha);
      $tipos = $this -> app_model -> get_tipos_servicios();
      $subtipos = array(array('subtipo'=>"REGULAR"));
      $servicios=[];
      foreach ($horarios as $h) {
        $tipo=[];
        foreach ($tipos as $t) {
          $st=[];
          foreach ($subtipos as $subt) {
            //$stest[] = 
            //if($stest[count($stest)] != null)
            $xt = $this->app_model ->get_servicios_disponibles($fecha,$h['hora_salida'],$t['tipo'],$subt['subtipo']);
            if(is_object($xt))
              $st[] = $xt;
          }
          if($st)
            $tipo[]= $st;  
        }
        if($tipo[0])
        $servicios[] =$tipo;
      } 
    return $servicios;
  }
}
