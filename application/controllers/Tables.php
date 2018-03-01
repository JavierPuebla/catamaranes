<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tables extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this -> load -> model('app_model');
    $this->load->helper('array');
    $this->load->helper('form');
    $this->load->library('cmn_functs');
    // Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('America/Argentina/Buenos_Aires');
  }

   // ******  PERMITE EDITAR CUALQUIER TABLA   
  

  public function index() {
    // ****** DATA PARA CUSTOMIZAR LA CLASE 
    // ****** TABLA DE PERSISTENCIA DE DATOS DE LA CLASE 
    $table = 'tables';
    // ****** RUTA DE ACCESO DEL CONTROLLER
    $route = 'tables/';
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
        'items'=>$this->cmn_functs->mk_dpdown($table,$this->cmn_functs->get_fields_sin_id($table),' ORDER BY id ASC','selecciona un item'),
        'attr'=> "class='form-control' onchange=call({'method':'meet','msg':this.value})",
        'title'=>'Modificando datos de:',
        'route'=>$route
        );
    // // ****** LOAD VIEW ******  
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$this -> app_model -> get_user_data($user['userId'])['usr_usuario']));
        $this -> load -> view($table.'_view',$var);
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


/* junk 
  <?php 
        echo "<pre>";
        // print_r($daos);
        foreach ($daos as $d) {
          echo '<br/>datos de cada dao: <br/>';
          foreach ($d as $elm) {
            echo '<br/> field name: '. $elm['field'] .' title: '.$elm['title'] .' val: '. $elm['value'];
            
          }
        }
        //echo 'label: '.$daos[1]['field'].' ||| value: '.$daos[1]['value'] ?>



*/

}
