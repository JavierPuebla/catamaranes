<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Administracion extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this -> load -> model('app_model');
    $this->load->helper('array');
    $this->load->helper('form');
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
      $user_data = $this -> app_model -> get_user_data($user['userId']);
    // ******** DATA SET UP    
      $hoy = Date("d/m/Y");
      $usuarios = $this ->cmn_functs->mk_dpdown('usuarios',['id','nombre_usuario','apellido_usuario'],"WHERE permisos_usuario = '4' OR permisos_usuario = '0' ORDER BY permisos_usuario ASC");
      $tpcomp = $this ->cmn_functs->mk_dpdown('cat_tipos_comprobantes',['id','nombre'],'where id < 3 OR id > 7');
      $formaDePago = array('NULL'=>'Selecciona forma de pago','EFVO'=>'Efectivo','BCO'=>'Deposito / Transferencia','CHQ'=>'Cheque','MP'=>'Mercado Pago');
      $header = ['Fecha','Tipo','Cantidad','Total $'];
      $var=array(
        'data'=> '',
        'dpdownUsuario'=> $usuarios,
        'dpdownTipoComprob'=> $tpcomp,
        'dpdownFPago'=> $formaDePago,
        'fecha'=> $hoy,
        'header'=>$header,
        'user'=>$user['userId']
      );

      // ****** VIEW VARs 
      
      $this -> load -> view('header-responsive');
      $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
      $this -> load -> view('administracion_view',$var);
    }else{
      redirect('login', 'refresh');
    }
  }

  function get_cmprb(){
    $data = $this->input->post('data');
    $fin=$this->cmn_functs->fixdate_ymd($data['fecIn']);
    $fout=$this->cmn_functs->fixdate_ymd($data['fecOut']);
    $myfilter=$this->input->post('filter');
    $f='';
    //var_dump($this->input->post()); 
    switch ($myfilter) {
      case 'vta_oln':
        $f = "AND c.tipos_comprobantes_id = '7' AND c.estado_comprobantes = '1'";
        break;
      case 'all_vta':
        $f = "AND c.tipos_comprobantes_id <= '6' AND c.estado_comprobantes = '1'";
        break;
      case 'all_cpra':
        $f = "AND c.tipos_comprobantes_id > '7' AND c.estado_comprobantes = '1'";
        break; 
    }

    $header = ['Fecha','Tipo ','Usuario','Monto $'];
    $rdat = $this-> app_model -> get_comprobantes_by_date($fin,$fout,$f);
    $clbkparams=(array('header'=>$header,'filter'=>
      $myfilter,'list_data'=>$rdat,'tpl'=>'compras-ventas'));
    echo json_encode(array('result'=>'ok','callback'=>'mk_list','clbkparam'=>$clbkparams));
  }

  
  function new_cmprb(){
    $data = $this->input->post();
    $data['data']['fecha'] = $this->cmn_functs->fixdate_ymd($data['data']['fecha']);
    $result_id = $this -> app_model -> insert('cat_comprobantes',$data['data']);
    echo json_encode(array('result'=>'ok','callback'=>'getAdmCmpts','clbkparam'=>'all'.$data['filter']));
  }

  function upd_cmprb(){
     $data = $this->input->post();
     $data ['data']['fecha'] = $this->cmn_functs->fixdate_ymd($data['data']['fecha']);
     $res = $this->app_model->update('cat_comprobantes',$data['data'],'id',$data['data']['id']);
    echo json_encode(array('result'=>'ok','callback'=>'getAdmCmpts','clbkparam'=>'all'.$data['filter']));

  }

}
