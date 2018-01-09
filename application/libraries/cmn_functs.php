<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cmn_functs {
   protected $CMF;

        // We'll use a constructor, as you can't directly call a function
        // from a property definition.
        public function __construct()
        {
                // Assign the CodeIgniter super-object
                $this->CMF =& get_instance();
                $this->CMF ->load -> model('app_model');
        }


  function mk_dpdown($tbl,$fields,$modif){
    $d = $this ->CMF ->app_model -> get_dpdown_data($tbl,implode(',',$fields),$modif);
    $r=[];
    foreach ($d as $value) {
      $f ='';
      for ($i=1; $i < count($fields); $i++) { 
        $f .= $value[$fields[$i]]." ";  
      }  
      $r[$value[$fields[0]]] = $f; 
    }
    return $r;
  }

  function fixdate_ymd($dt){
    return substr($dt,strrpos($dt,'/')+1).'/'.substr($dt,strpos($dt,'/')+1,2).'/'.substr($dt,0,strpos($dt,'/'));
  }
      
}






 