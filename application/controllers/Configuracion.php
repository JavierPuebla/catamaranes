<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Configuracion extends CI_Controller {

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
    // ****** DATA PARA CUSTOMIZAR LA CLASE 
     $cls_name = 'configuracion';
    // ****** TABLA DE PERSISTENCIA DE DATOS DE LA CLASE 
    $table = 'tables';
    // ****** RUTA DE ACCESO DEL CONTROLLER
    $route = 'configuracion/';
    // ****** ******************
  

    $user = $this -> session -> userdata('logged_in');
    if (is_array($user)) {
    // ****** NAVBAR DATA ********
      $userActs = $this -> app_model -> get_activities($user['userId']);
      $acts = explode(',',$userActs['acciones_id']);
    // ****** END NAVBAR DATA ********
  
    // ************ VAR INYECTA INIT DATA EN LA INDEX VIEW *********** 
    $var=array(
        // PREPARO LOS DATOS DEL VIEW
        'items'=>$this->cmn_functs->mk_dpdown($table,$this->cmn_functs->get_fields_sin_id($table),' WHERE id < 99 ORDER BY id ASC','selecciona un item'),
        'attr'=> "class='form-control' onchange=call({'method':'meet','msg':this.value})",
        'title'=>'Modificando datos de:',
        'route'=>$route
        );
    // // ****** LOAD VIEW ******  
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$this -> app_model -> get_user_data($user['userId'])['usr_usuario']));
        $this -> load -> view($cls_name.'_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }
  // ****** END INDEX  ******


  function add(){
    $p = $this->input->post();
    $result = $this -> app_model -> insert($p['info']['msg'],$p['data']); 
    echo json_encode(array(
                        'callback'=>'call',
                        'param'=>$p['info']
                      )
                    );
  }
  
  function upd(){
    $p = $this->input->post();
    if($p['deleterec'] == 'true'){
      $this->app_model -> delete($p['info']['msg'],'id',$p['id']);  
    }else{
      $result = $this -> app_model -> update($p['info']['msg'],$p['data'],'id',$p['id']);    
    }
    echo json_encode(array(
                        'callback'=>'call',
                        'param'=>$p['info']

                      )
                    );
  }

  function meet(){
    
    // echo json_encode($this->input->post('msg'));
    // EN ESTE CASO DE CONFIG MEET DEVUELVE TODA LA DATA EN UN LISTADO PARA HACER UNA TABLA Y CARGAR EN TCX.DATA
    $t=$this->input->post('msg'); 
    
    $fh = $this->app_model->get_table('*',$t);
    
    $r = $this->cmn_functs->set_daos($fh);
    // echo json_encode($r);
    echo json_encode(array(
                      'info'=>$this->input->post(),
                      'callback'=>'dao_mk_list',
                      'param'=>array('list_data'=>$r)
                      )
                    );
  }



/*
public function index() {
    $subject = 'configuracion/';
    $user = $this -> session -> userdata('logged_in');
    if (is_array($user)) {
    // ****** NAVBAR DATA ********
      $userActs = $this -> app_model -> get_activities($user['userId']);
      $acts = explode(',',$userActs['acciones_id']);
    // ****** END NAVBAR DATA ********
    // ************ VAR INYECTA INIT DATA EN LA INDEX VIEW *********** 
    $var=array(
        'items'=>$this->cmn_functs->mk_dpdown('cat_tables',['table_name','title'],' WHERE id < 99 ORDER BY id ASC','selecciona un item'),
        'attr'=> "class='form-control' onchange=call({'method':'meet','msg':{'table_name':this.value}})",
        'title'=>'Modificando datos de:',
        'subject'=>$subject
        );
    // ****** LOAD VIEW ******  
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$this -> app_model -> get_user_data($user['userId'])['usr_usuario']));
        $this -> load -> view('config_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }
  // ****** END INDEX  ******


  function add(){
    $p = $this->input->post();
    $result = $this -> app_model -> insert($p['info']['msg']['table_name'],$p['data']); 
    echo json_encode(array(
                        'callback'=>'call',
                        'param'=>$p['info']
                      )
                    );
  }
  
  function upd(){
    $p = $this->input->post();
    if($p['deleterec'] == 'true'){
      $this->app_model -> delete($p['info']['msg']['table_name'],'id',$p['id']);  
    }else{
      $result = $this -> app_model -> update($p['info']['msg']['table_name'],$p['data'],'id',$p['id']);    
    }
    echo json_encode(array(
                        'callback'=>'call',
                        'param'=>$p['info']

                      )
                    );
  }

  function meet(){
    // EN ESTE CASO DE CONFIG MEET DEVUELVE TODA LA DATA EN UN LISTADO PARA HACER UNA TABLA Y CARGAR EN TCX.DATA
    $tbl = $this->input->post('msg')['table_name'];
    $r = $this->cmn_functs->set_fields_and_headers($tbl);
    echo json_encode(array(
                      'info'=>$this->input->post(),
                      'callback'=>'mk_list',
                      'param'=>array(
                          'header'=>$r['hdrs'],
                          'flds'=>$r['flds'],
                          'list_data'=>$this->app_model->get_table("*",$tbl,'WHERE id > 0 ORDER BY id ASC'),
                          'tpl'=>'config'
                        )
                      )
                    );
  }

*/




}
