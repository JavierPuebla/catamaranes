<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Main extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this -> load -> model('app_model');
    $this->load->helper('array');
  }

  function index() {
    $user = $this -> session -> userdata('logged_in');
    if (is_array($user)) {
      $user_data = $this -> app_model -> get_user_data($user['userId']);
      $userActs = $this -> app_model -> get_activities($user['userId']);
      $acts = explode(',',$userActs['acciones_id']);
      //if($quien['usuario'] == "algun tipo seleccionado de user"){
        // CONTROLLER Y ACTION son settings en la DB
        //$this->app_model->getUserSetup()
        $var=array('controller'=>'init','user'=>$user_data);
        $this -> load -> view('header-responsive');
        $this -> load -> view('navbar',array('acts'=>$acts,'username'=>$user_data['usr_usuario']));
        $this -> load -> view('main_view',$var);
        //$this -> load -> view($user['userType'].'_view',$var);
      //}else{
        //redirect('catalogs');
      //}
    } else {
      redirect('login', 'refresh');
    }
  }



}
