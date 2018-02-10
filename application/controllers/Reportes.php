<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reportes extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this -> load -> model('app_model');
    $this->load->helper('array');
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
    

    // ****** GET REPORTES DATA SEGUN USUARIO 
     
      
      $fechin = date("Y-m-d");
      $fechout =date("Y-m-d");
      $filter = "AND c.tipo = 'VTA-TIKET' AND c.estado_comprobantes = '1'";
      $tikets = $this-> app_model -> get_tkts_by_date($fechin,$fechout,$filter);
     
      
      //$data = 
    
    // ****** REPORTES DATA EN VAR   
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
  
  
  function get_tkts(){
    $data = $this->input->post('data');
    $fin=$this->cmn_functs->fixdate_ymd($data['fecIn']);
    $fout=$this->cmn_functs->fixdate_ymd($data['fecOut']);
    $myfilter=$this->input->post('filter');
    $f='';
     switch ($myfilter) {
      case 'vta_oln':
        $f = "AND c.tipos_comprobantes_id = '7' AND c.estado_comprobantes = '1'";
        break;
      case '_all':
        $f = "AND c.tipos_comprobantes_id <= '6' AND c.estado_comprobantes = '1'";
        break;
      
      default:
        $f = "AND c.tipos_comprobantes_id <= '6' AND c.estado_comprobantes = '1'";
        break;
    }
    $header = ['Fecha','Servicio',' Hora','Cantidad Tickets','Total $'];
    $tkts = $this-> app_model -> get_tkts_by_date($fin,$fout,$f);
    $clbkparams=(array('header'=>$header,'filter'=>
    $myfilter,'list_data'=>$tkts,'tpl'=>'tkts'));
    echo json_encode(array('result'=>'ok','callback'=>'mk_list','clbkparam'=>$clbkparams));
  }
}
