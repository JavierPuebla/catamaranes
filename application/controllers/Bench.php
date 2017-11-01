<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class bench extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this -> load -> model('app_model');
    $this->load->helper('array');
  }

  function index() {
        $this -> load -> view('header-responsive');
        $this -> load -> view('bench');
  }



}
