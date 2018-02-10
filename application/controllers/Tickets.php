<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tickets extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this ->load->model('app_model');
    $this->load->helper('array');
    $this->load->library('cmn_functs');
    // Establecer la zona horaria predeterminada a usar
    date_default_timezone_set('America/Argentina/Buenos_Aires');
  }

  public function index() {
    $user = $this -> session -> userdata('logged_in');
    $user_data = $this -> app_model -> get_user_data($user['userId']);
    if (is_array($user)) {
      $userActs = $this -> app_model -> get_activities($user['userId']);
      $acts = explode(',',$userActs['acciones_id']);
      // ****** GET SERVICIOS DISPONIBLES put it into var
      //$fechaHora = date('H:i');
      $fecha = Date('Y-m-d');
      $this->cmn_functs->create_dia_servicios_regulares($fecha);
      $servicios = $this->app_model->get_servicios_boleteria($fecha);
        $var=array('data'=>$servicios,'user'=>array('id'=>$user_data['id_usuario'],'tipo'=>$user_data['tipo_usuario']));
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
        $this -> load -> view('tickets_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }

  
  public function make_tkt(){
    $data = $this->input->post();
    $result = $this -> app_model -> insert_tikets($data); 
    echo json_encode(array('result'=>$result));
  }

  function get_servs_by_usertype($fecha,$utp){
      $horarios = $this -> app_model -> get_horarios('');
      $tipos = $this -> app_model -> get_tipos_servicios();
      $subtipos = $this -> app_model -> get_subtipos_servicios();
      
      switch ($utp) {
        case 'boleteria':
          $subtipos = array(array('subtipo'=>"REGULAR"),array('subtipo'=>"JUBILADO"));
          break;
        
        default:
          $subtipos = $this -> app_model -> get_subtipos_servicios();
          break;
      }
     
      $servicios=[];
      foreach ($horarios as $h) {
        $tipo=[];
        foreach ($tipos as $t) {
          $st=[];
          foreach ($subtipos as $subt) {
            //$stest[] = 
            //if($stest[count($stest)] != null)
            $xt = $this->app_model ->get_servicios_disponibles($fecha,$h['id'],$t['tipo'],$subt['subtipo']);
            
            if(is_object($xt))
              $st[] = $xt;
          }
          if($st)
            $tipo[]= $st;  
        }
        //print_r($servicios);//exit();
       //if($tipo[0])
       $servicios[] = $tipo;
      }
    return $servicios;
  }


  function anular_tkt(){
    $ids = $this->input->post('tkts');
    $r = $this->app_model->anular_tkt($ids);
    echo json_encode($r);
  }


  function reg_vnta_onl(){
    $data = $this->input->post();
    // define tarifa segun tipo ****
    $clid=$this->cmn_functs->check_cliente($data['nombre'],$data['tel'],$data['mail']);
    $fecha = date('Y-m-d');
    switch ($data['tipo_servicio']) {
          case 'Paseo 1 hora':
            $tarifa_gen = $this->app_model->get_tarifa_by_srvsid(1);
            $tarifa= intval($tarifa_gen) - 20;
            $srvs_id = 1;
            $hs_id = $this->cmn_functs->create_servicio($fecha,'-1',$srvs_id);
          break;
          case 'Paseo 2 horas':
            $tarifa_gen = $this->app_model->get_tarifa_by_srvsid(2);
            $tarifa= intval($tarifa_gen) - 20;
            $srvs_id = 2;
            $hs_id = $this->cmn_functs->create_servicio($fecha,'-1',$srvs_id);
          break;
          case 'Delta Night':
            $tarifa = $this->app_model->get_tarifa_by_srvsid(14);
            $srvs_id = 14;
            $hs_id = 146;
          break;
    }    
    
    
 
    $tkts = [
        'cantTickets'=>$data['cant'],
        'fecha'=> $fecha,
        'tipos_comprobantes_id' => 7,
        'tarifa' => $tarifa,
        'chk_sel' =>'1',
        'servicios_id'=>$srvs_id,
        'hist_servicio_id' => $hs_id,
        'forma_pago'=>'MP',
        'status'=> $data['status'],
        'id_transaccion' => $data['id_transaccion'],
        'puntodeventa_id' =>'999',
        'clientes_id'=>$clid
      ]; 
    $result = $this -> app_model -> insert_tikets($tkts); 
    echo json_encode(array('result'=>$result));
  }

}
