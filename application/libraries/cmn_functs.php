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

  


  function check_cliente($nombre,$tel,$email,$tipo_cliente='web'){
    $clid = $this->CMF->app_model->get_cliente_by_email($email);
    if(empty($clid)){
      
      $clid = $this->CMF->app_model->insert('cat_clientes',array("nombre_contacto_cliente"=>$nombre,'telefono_contacto_cliente'=>$tel,'email_cliente'=>$email,'tipo_cliente'=>$tipo_cliente,'fecha_alta_cliente'=>date("Y-m-d")));
      return $clid;
    }
    return $clid->id_cliente;
  }


  function check_historial_servicios($fecha,$horarios_id,$servicios_id){
    $hs_id = $this ->CMF->app_model->check_hs_exist($fecha,$horarios_id,$servicios_id);
    if(empty($hs_id)){
      $hs_id = $this ->CMF->app_model->insert('cat_historial_servicios',array('fecha_servicio'=>$fecha,'horarios_id'=>$horarios_id,'servicios_id'=>$servicios_id,'estado'=>'D'));
      return $hs_id;  
    }
    return $hs_id->id;

  }

  function fixdate_ymd($dt){
    if(strpos($dt,'/') >0)
      return substr($dt,strrpos($dt,'/')+1).'-'.substr($dt,strpos($dt,'/')+1,2).'-'.substr($dt,0,strpos($dt,'/'));
    return $dt;
  }
      
}






 