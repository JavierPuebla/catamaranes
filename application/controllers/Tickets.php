<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tickets extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this -> load -> model('app_model');
    $this->load->helper('array');
  }

  function index() {
    $user = $this -> session -> userdata('logged_in');
    if (is_array($user)) {
      $userActs = $this -> app_model -> get_activities($user['userId']);
      $acts = explode(',',$userActs['acciones_id']);


      // ****** GET SERVICIOS DISPONIBLES

      $user_data = $this -> app_model -> get_user_data($user['userId']);
        $var=array('data'=>'','user'=>$user_data);
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usuario']));
        $this -> load -> view('tickets_view',$var);
    } else {
      redirect('login', 'refresh');
    }
  }

}
