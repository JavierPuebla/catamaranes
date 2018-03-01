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

  // ***** obtiene la data para el dropdown de la tabla asignada
  function mk_dpdown($tbl,$fields,$modif,$init_msg){
    $d = $this ->CMF ->app_model -> get_dpdown_data($tbl,$fields,$modif);
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
    return $clid->id;
  }


  function create_servicio($fecha,$horarios_id,$servicios_id){
    $hs_id = $this ->CMF->app_model->check_hs_exist($fecha,$horarios_id,$servicios_id);
    if(empty($hs_id)){
      $hs_id = $this ->CMF->app_model->insert('cat_historial_servicios',array('fecha_servicio'=>$fecha,'horarios_id'=>$horarios_id,'servicios_id'=>$servicios_id));
      return $hs_id;  
    }
    return $hs_id->id;
  }



  function create_dia_servicios_regulares($fecha){
    foreach ($this->CMF->app_model->get_horarios("AND id > '0'") as $h) {
        $hrtoserv = explode(',', $h['disp_servicios_id']);
      foreach ($hrtoserv as $serv_id) {
        $stru['fecha_servicio']= $fecha;
        $stru['hora_salida'] = $h['id']; 
        $stru['servicios_id']=$serv_id;
        if($serv_id == '1' || $serv_id == '2')
          $this ->create_servicio($fecha,$h['id'],$serv_id);
      }
      

    } 
  }



  function fixdate_ymd($dt){
    if(strpos($dt,'/') >0)
      return substr($dt,strrpos($dt,'/')+1).'-'.substr($dt,strpos($dt,'/')+1,2).'-'.substr($dt,0,strpos($dt,'/'));
    return $dt;
  }

  function set_fields_and_headers($d){
    $tbl = $this->CMF->app_model->get_table('id','cat_tables',"WHERE table_name = '{$d}'");
    $fandth = $this->CMF->app_model->get_table('field_name,header','cat_field_to_th',"WHERE tables_id  = {$tbl[0]['id']}");
    $hdrs = $flds = array();
    foreach ($fandth as $v) {
      $flds[]= $v['field_name'];
      $hdrs[]= $v['header'];
    }
    return ['flds'=>$flds,'hdrs'=>$hdrs];
  }
  
  function get_fields_sin_id($t){
    $db = $this->CMF->app_model->get_table('*',$t);
    $b =  array_shift($db[0]);
    $f = array_keys($db[0]);
    return $f;
  }

  function set_daos($fh){
    $flds = array_keys($fh[0]);
    $d = $x = $y = [];
    foreach ($fh as $rec) {
      foreach ($flds as $key => $f) {
        if($key == 0){
          $x['field']= $f;
          // $x['type']='int';
          // $x['length']=11;
          $x['value']=$rec[$f];
          $x['title'] = $f; 
        }else{
          $x['field']= $f;
          // $x['type']=substr($f,strpos($f,'_')+1,3);
          // $x['length']=substr($f,strrpos($f,'_')+1);
          $x['value']=$rec[$f];  
          // title del field
          $title_exist = $this->CMF->app_model->get_table('title','fields_to_titles',"WHERE field = '{$x['field']}'");
          if(empty($title_exist[0])){
            $t = ucwords(str_replace('_', ' ', $x['field']));
            $x['title'] = $t;
            $this->CMF->app_model->insert('fields_to_titles',['field'=>$x['field'],'title'=>$t]);
          }else{
            $x['title']=$title_exist[0]['title'];
          }
        }
      $d[]=$x;
      $x=[];  
      }
      $y[]=$d;
      $d=[];
    }
    return $y;
  } 



  
}






 