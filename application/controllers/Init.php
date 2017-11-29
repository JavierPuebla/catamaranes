<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Init extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this -> load -> model('app_model');
  //  $this->load->helper('array');
  }


/***************************************************************************
* ARMADO DE LA PANTALLA INICIAL
***************************************************************************/
  function index() {
      // proceso eligiendo eltipo de accion segun el usuario
      $user= $this->input->post('user');
      $userType=null;//$user['userType'];
      $userId=$user['id_usuario'];
      
      $actividades = $this->app_model->get_activities($userId);

      echo json_encode($actividades);



      // switch ($userType) {
      //   case 'agent':
      //       // SI ES AGENTE DE VENTA BUSCO SUS CAMPAÑAS Y HAGO UN SELECTOR
      //       $layout = $this->app_model->get_layout($userType,1);
      //       $campaigns = $this->app_model->get_campaign($userId);
      //       foreach ($campaigns as $indkey => $camp_value) {
      //         $campXData = $this->app_model->get_extended_data('campaña',$camp_value['camp_id']);
      //         foreach ($campXData as $xvalue) {
      //             $camp_value[$xvalue['label']] = $xvalue['value'];
      //         }
      //         $campaigns[$indkey] = $camp_value;
      //       }
      //       $d = ['action'=>'setup_'.$userType,'layout'=>$layout,'user'=>$user,'campaigns'=> $campaigns];
      //       break;
      //   case 'supervisor':
      //       //LISTADO DE agentes
      //       $layout = $this->app_model->get_layout($userType,1);
      //       $campaigns = $this->app_model->get_campaign('all');
      //       $agents = $this->app_model->get_agents();
      //       //$clients = $this->app_model->get_clients();
      //       $agnts_acts = array();
      //       $agnts_cmpns = array();
      //       $clientsList=array();
      //       $prods=array();

      //       //listado de actividad de agentes
      //       foreach ($agents as $agent) {
      //         $ag_id_acts[$agent['usuarios_id']] = $this->app_model->get_AgntsActivity($agent['usuarios_id']);
      //         $agnts_acts[] = $this->app_model->get_AgntsActivity($agent['usuarios_id']);
      //         $agnts_cmpns[] = $this->app_model->get_campaign($agent['usuarios_id']);
      //       }
      //       $clid =array();
      //       $pid=array();
      //       foreach ($campaigns as $cmpn) {
      //         $cls= $this->app_model->get_clients_by_cmpnid($cmpn['camp_id']);
      //         foreach ($cls as $cl) {
      //           if(!in_array($cl['client_id'],$clid)){
      //             array_push($clid,$cl['client_id']);
      //             array_push($clientsList,$cl);
      //           }
      //         }
      //         $ProdFamIds = explode(',',$cmpn['prod_families_id']);
      //         foreach ($ProdFamIds as $pfid) {
      //           $prds = $this->app_model->get_prod_by_family_id($pfid);
      //           foreach ($prds as $pr) {
      //             if(!in_array($pr['product_id'],$pid)){
      //               array_push($pid,$pr['product_id']);
      //               array_push($prods,$pr);
      //             }
      //           }
      //         }
      //       }
      //       $d = ['action'=>'setup_'.$userType,
      //         'agIdActs'=>$ag_id_acts,
      //         'layout'=>$layout,
      //         'user'=>$user,
      //         'clients'=>$clientsList,
      //         'allCamps'=>$campaigns,
      //         'agents'=> $agents,
      //         'agActs'=>$agnts_acts,
      //         'agCmpn'=>$agnts_cmpns,
      //         'prodsList'=>$prods,
      //         'pedidos'=>$this->app_model->pedidos_report((new DateTime())->format('Y'))
      //       ];
      //       break;
      //   default:
      //     # code...
      //     break;
      // }

      //echo json_encode($d);
  }

/*
* SUPERVISOR ---- ARMADO DE LA CAMPAÑA PARA
*/

  function get_superv_campData(){

    $contactsByYearAndCampign = $this->app_model->contacts_report((new DateTime())->format('Y'),$this->input->post('campId'));

  }

/***************************************************************************
* AGENT --- ARMADO DE LA CAMPAÑA SELECCIONADA  CLIENTES, PRODUCTOS, CONTACTOS
***************************************************************************/
  function get_camp_data(){
    // LISTADO DE CLIENTES Y SUS DATOS
    $clients = $this->app_model->get_clients_by_cmpnid($this->input->post('cmpn_id'));
    foreach ($clients as $indkey => $cli) {
      $cliXData = $this->app_model->get_extended_data('clients',$cli['client_id']);
      foreach ($cliXData as $xvalue) {
          $cli[$xvalue['label']] = $xvalue['value'];
      }
      $clients[$indkey] = $cli;
    }
    //PRODUCTOS Y FAMILIAS DE PRODS
    $prodList = array();
    $prodFam = array();
    $fm_id = explode(',',$this->input->post('ProdFamIds'));
    foreach ($fm_id as $prntId) {
      $prodFam['famdat']= $this->app_model->get_family_by_id($prntId);
      $pr_dta = $this->app_model->get_prod_by_family_id(intval($prntId));
      foreach ($pr_dta as $k =>$prv) {
        $prodXData = $this->app_model->get_extended_data('products',$prv['product_id']);
        foreach ($prodXData as $xval) {
          $prv[$xval['label']] = $xval['value'];
        }
        $pr_dta[$k]=$prv;
        $prodFam['prdata']=$pr_dta;
      }
      $prodList[] = $prodFam;
    }
    // LISTADO DE ACTIVIDADES PARA AGENTES
    $contacts = $this->app_model->get_contacts_by_usrcamp($this->input->post('user_id'),$this->input->post('cmpn_id'));

    // DEVUELVE LA LLAMADA A agentPanel(los listados en un objeto)
    echo json_encode(array('action'=>'agentPanel',
                          'clientsList'=>$clients,
                          'prodsList'=>$prodList,
                          'contacts'=>$contacts
                          )
                    );
  }

  function save_pedido(){
    		//$r = $this->partes_model->checkout_order('478');
		// para implementar mas tarde link a la orden  -- <p> <a href='".base_url()."control_panel/detalle_pedido/".$order_n ." '>Clickear aqui para ver el detalle de la orden</a></p>
		//send mail to admins
		/*  $msg= "<h4>Nuevo pedido de partes,</h4> <h4>Orden Nro.: ". $order_number ." </h4>";
			$this->email->from('ciparts@outboardsgroup.com', 'CIPARTS');
			$this->email->to('javier@powertecoutboards.com');
			//$this->email->cc('mayra@powertecoutboards.com');
			//$this->email->bcc('javier@powertecoutboards.com');
			$this->email->subject('nuevo pedido de partes');
		 	$this->email->message($msg);
		 	$this->email->send();
    */
			echo json_encode(Array('action'=>'response','responder'=>'save_pedido','resp'=>$order_number));
	}

  function save_activity(){

    $p = array('user_id'=>$this->input->post('agent_id'),
          'client_id'=>$this->input->post('client_id'),
          'date'=>$this->input->post('date')
    );
    // guardo el pedio y obtengo el numero
    $pitems = (is_array($this->input->post('pedido')))?$this->input->post('pedido'):0;
    $order_number = (is_array($pitems))?$this->app_model->checkout_order($p,$pitems):0;
    if($this->input->post('revisita') != ''){
      $dt = str_replace('/','-',$this->input->post('revisita'));
      $day=substr($dt,0,strpos($dt,'-'));
      $mon=substr($dt,strpos($dt,'-')+1,strlen($day));
      $yr=substr($dt,strrpos($dt,'-')+1);
      $date=$yr.'-'.$mon.'-'.$day;
    }else{
      $date=NULL;
    }
    $d = array('agent_id'=>$this->input->post('agent_id'),
          'campaign_id'=>$this->input->post('campaign_id'),
          'client_id'=>$this->input->post('client_id'),
          'date'=>$this->input->post('date'),
          'order_id'=>$order_number,
          'result'=>$this->input->post('result'),
          'coments'=>$this->input->post('coments'),
          'revisit'=>$date
    );
    $contacts_id = $this->app_model->insert('contacts',$d);
    echo json_encode(Array('action'=>'act_response','orderNumber'=>$order_number,'contactsId'=>$contacts_id));
  }


}
