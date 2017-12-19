<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tickets extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this ->load->model('app_model');
    $this->load->helper('array');
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
      $hay_servicios = $this->app_model->check_serv_exists($fecha);
      if(empty($hay_servicios))
        $this -> create_dia_servicios_regulares();
      $servicios = $this->get_servs_by_usertype($fecha,$user_data['tipo_usuario']);
        $var=array('data'=>$servicios,'user'=>array('id'=>$user_data['id_usuario'],'tipo'=>$user_data['tipo_usuario']));
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
        $this -> load -> view('tickets_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }

  function create_dia_servicios_regulares(){
    $stru = array(
              'fecha_servicio'=>'',
              'hora_salida'=>'',
              'codigo_tipo_servicios'=>1, 
              'estado'=> 'D',
              'cant_pasajeros'=>0
            );
    $stru['fecha_servicio']= Date("Y-m-d");
    $horas_disponibles = $this->app_model->get_horarios();
    foreach ($horas_disponibles as $hd) {
      $stru['hora_salida']=$hd['hora_salida'];
      $stru['horarios_id']=$hd['id'];
      $this ->app_model->insert('cat_historial_servicios',$stru);  
    }
  }


  public function make_tkt(){
    $data = $this->input->post();
    $result = $this -> app_model -> insert_tikets($data); 
    echo json_encode(array('result'=>$result));
  }

  function get_servs_by_usertype($fecha,$utp){
      $horarios = $this -> app_model -> get_horarios();
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
     //print_r($subtipos);exit();
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
        if($tipo[0])
        $servicios[] =$tipo;
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
    //$myvars = 'nombre=' . $nombre . '&tel=' . $telefono. '&mail=' . $email . '&cant=' . $cantidad. '&tipo=1h';
    $cli = $this->app_model->get_cliente_by_email($data['mail']);
    if(!is_object($cli)){
      $cl = array('usuarios_id'=>0, "nombre_contacto_cliente"=>$data['nombre'],'telefono_contacto_cliente'=>$data['tel'],'email_cliente'=>$data['mail'],'tipo_cliente'=>'website','fecha_alta_cliente'=>date("Y-m-d"));
      $new_id = $this->app_model->insert('cat_clientes',$cl);
      $cli = $this->app_model->get_cliente_by_id($new_id);
    }
    var_dump($cli);
    // define tarifa segun tipo ****
    switch ($data['tipo']) {
          case '1h':
            $tarifa = $this->app_model->get_tarifa_by_srvsid(1);
            $srvs_id = 1;
          break;
          case '2h':
            $tarifa = $this->app_model->get_tarifa_by_srvsid(11);
            $srvs_id = 11;
          break;
    }    
    $tkts = [
        'cantTickets'=>$data['cant'],
        'fecha'=> date("Y-m-d"),
        'tipo_comp' =>'VTA-WEBSITE',
        'tarifa' => $tarifa,
        'chk_sel' =>'1',
        'servicios_id'=>$srvs_id,
        'histServiciosId' => '',
        'formaDePago'=>'MP',
        'nroTransacTarjeta'=> '',
        'puntodeventa_id' =>'999',
        'user_id'=>$cli->usuarios_id,
        'clientes_id'=>$cli->id_cliente
      ]; 
    $result = $this -> app_model -> insert_tikets($tkts); 
    echo json_encode(array('result'=>$result));
  }

}
