

/********************************************************
* SETUP DE DATA Y PANTALLAS PARA EL USUARIO AGENTE
*********************************************************/
function setup_agent(obj){
	// CARGO LA DATA DE LAS CAMPAÑAS EN STRUC knldg
		TOP.camp = new knldg(obj.campaigns);
	 	TOP.userdata = {'user':obj.user,'selectedCampId':'','selectedFarma':'','selectedContact':'','selectedProd':''};
	 	TOP.currentLayout = new knldg(obj.layout);
		TOP.responses = new knldg([])
	 	console.log('raw',TOP);
		// SELECTOR DE CAMPAÑAS
		var o = {
		'id':'campaignSelector',
		'settings':{'selectionContainer':'mselect','action':'show_cmpn'},
		'values':TOP.camp.stk.map(function(itm){return itm.camp_id}),
		'labels':TOP.camp.stk.map(function(itm){return itm.name+" "}),
		'placeholder':'Selecciona una Campaña ',
		'parent': 'campaign_selector'
		}
		mk_select(o);
}



/********************************************************
* CAMPAÑA SELECCIONADA
*********************************************************/
function show_cmpn(campId){
	if(TOP.userdata.selectedCampId !=campId){
		clean('campaign');
		Do({'controller':'init/get_camp_data','user_id':TOP.userdata.user.userId,'cmpn_id':campId,'ProdFamIds':TOP.camp.stk[TOP.camp.search('camp_id',campId)].prod_families_id});
	}
	TOP.userdata.selectedCampId=campId;
}

/********************************************************
* PANEL INICIAL DE CAMPAÑA SELECCIONADA
*********************************************************/
function agentPanel(o){
	// armo listados locales
	TOP.clientsList = new knldg(o.clientsList);
	TOP.prodsList = new knldg(o.prodsList);
	TOP.contacts = new knldg(o.contacts);
	TOP.cart = new knldg([]);
	TOP.visits = [];
	// muestra todos los elementos del layout del user
	TOP.currentLayout.stk.map(function(i){show(i)});
}

//------------------  END AGENT SETUP -------------------



/********************************************************
* VISITA
********************************************************/
// ABRE EL LISTADO DE PRODUCTOS PARA ESE CLIENTE Y CAMPAÑA
// LISTADO PERMITE SELECCIONAR PRODS PARA ARMAR Y ASIGNAR UN NUMERO DE PEDIDO.
// LISTADO DE PRODS TIENE UN SUBPANEL DE CONDICIONES DE PROD (DESCUENTOS, PROMOS, ETC)
// queda habierta la visita hasta concluir con campos completados
// para cerrar la visita hay que completar el campo visit_result
function regVisita(){
	// ***********  todo esta en : TOP.selectedCliAddrs ************
	$('#myModalLabel').html('Registrando visita a '+TOP.selectedCliAddrs.RAZON_SOCIAL);
	$('#modal_content').html("<div id='visitContainer'><p id='vselect-row'></p></div>");
	$('#myModal').modal({keyboard: false});
	//o=(btnlbl:'bot label',values:['v1','v2','v3'],labels:['lbl1','lbl2','lbl3'],placehlder:'select an item',parent:'containerdiv')
	var visitSelect = {'id':'vselect',btnlbl:'Resultado ','values':['Venta Registrada','Visitado sin venta','Visitado con Promocion','No encontrado'],'labels':['Venta Registrada','Visitado sin venta','Visitado con Promocion','No encontrado'],'placeholder':'selecciona un resultado','parent':'vselect-row'}
	mk_inputgroup(visitSelect);
	if(parseInt($('#cartTotal').html()) > 0){
		$('#input_vselect').val('Venta Registrada')
	}
	$('#visitContainer').append("<p><div class='input-group'><span class='input-group-addon' id='igLabel'>Comentarios</span><input type='text' class='form-control' id='coments' aria-describedby='igLabel'></p>")
	$('#visitContainer').append("<p><div class='input-group date'><input type='text'id='dp-revisita' class='form-control' placeholder='Agendar Revisita'><span class='input-group-addon'><i class='glyphicon glyphicon-th'></i></span></div></p>")
	$('#visitContainer').append("<div class='modal-footer'><button type='button' class='btn btn-default' data-dismiss='modal'>Close</button><button class='btn btn-primary' onCLick=saveRegVisita() type='submit'>OK</button></div>")
	$('.input-group.date').datepicker({
		language:'es',
		format: "dd/mm/yyyy",
		weekStart: 1,
		daysOfWeekHighlighted: "0,6",
		calendarWeeks: true,
		todayHighlight: true,
		autoclose:true
	});
}

function saveRegVisita(){
	// TOP.selectedCliAddrs
	var date = new Date();
	var isodate = date.toISOString().slice(0,date.toISOString().indexOf('T'))
	var save_ok=true;
	//check de input_vselect
	if ($('#input_vselect').val()=='Venta Registrada' && TOP.cart.stk.length == 0) {
			save_ok=false
			myAlertLoader('failed','debe completar un pedido para Registrar Venta');
	}
	if($('#input_vselect').val() == ''){
		save_ok=false
		myAlertLoader('failed','debe seleccionar un resultado para registrar la vista');
	}
	// si hay un pedido
	var pedido=(TOP.cart.stk.length > 0)?JSON.parse(JSON.stringify(TOP.cart)).stk.map(function(x){return x.content}):0;
	// guardala activity
	var activity = {controller:'init/save_activity',agent_id: TOP.userdata.user.userId,campaign_id: TOP.selectedCliAddrs.campaign_id,client_id: TOP.selectedCliAddrs.client_id,contacts_id: null,date: isodate,pedido:pedido,result: $('#input_vselect').val(),coments:$('#coments').val(),revisita:$('#dp-revisita').val()};
	// actualiza la pantalla y el listado interno
	if(save_ok){Do(activity)}
}

function act_response(o){
	var date = new Date();
	var isodate = date.toISOString().slice(0,date.toISOString().indexOf('T'))
	// agrega a la lista "'"regsitro de contactos"
	TOP.visits.push({client_id:TOP.selectedCliAddrs.client_id,result:$('#input_vselect').val(),fecha: isodate,coments:$('#coments').val(),revisita:$('#dp-revisita').val()});
	// registro de nuevo contacto al cliente en stk contacts
	TOP.contacts.stk.reverse();
	TOP.contacts.stk.push({agent_id: TOP.userdata.user.userId,campaign_id: TOP.selectedCliAddrs.campaign_id,client_id: TOP.selectedCliAddrs.client_id,contacts_id: o.contactsId,date:isodate,order_id:o.orderNumber,result: $('#input_vselect').val(),coments:$('#coments').val(),revisita:$('#dp-revisita').val()});
	TOP.contacts.stk.reverse();
	// refresca la tabla registro de contactos de pantalla
	var fCont = TOP.contacts.stk.filter(function(e){return e.client_id == TOP.selectedCliAddrs.client_id});
	var tgtElm = TOP.currentLayout.stk[TOP.currentLayout.search('elem_id','contacts_table')];
	refresh_element(fCont,tgtElm);
	cartReset();
	$('#myModal').modal('hide')

}
//** CART FUNCTIONS*********************************
	function update_cart(dtOrig){
		var r = JSON.parse(dtOrig)
		var elem = 'inpCant_'+r.stkIndx+'-'+r.substackIndx
		var i =TOP[r.origin].stk[r.stkIndx].prdata[r.substackIndx];
		var itm = {elemId:elem,addrs:i,content:{price:i.price,prodId:i.product_id,descript:i.descript,qtty:$('#'+elem).val(),tot:(parseFloat(parseFloat(i.price)*parseInt($('#'+elem).val())))}};
		//carga o actualiza en el cart el item que cambió
		TOP.cart.stk[(typeof(TOP.cart.search('elemId',elem))!='undefined')?TOP.cart.search('elemId',elem):TOP.cart.stk.length] = itm
		//Grand total del cart
		var tot_cart = TOP.cart.stk.reduce(function(ant,act,i){return ant +((isNaN(act.content.tot))?0:act.content.tot)},0);
		$('#cartTotal').html(tot_cart.toFixed(2));
	}
	//si clikea enter en el campo de qty blurea el campo para detonar el update del cantidad seleccionada
	function check_enter(elem){
		if(event.keyCode == 13){
			$('#'+elem).blur();
		}
	}
	function cartReset(){
		$('#pnlBody_prodsPanel :input').val(0);
		$('#cartTotal').html('0.00')
		TOP.prodsList.stk.map(function(pr){$('#'+pr.famdat.prod_families_id).collapse('hide')});
		TOP.cart.stk.splice(0,TOP.cart.stk.length+1);
		console.log('cart',TOP.cart,'contacts',TOP.contacts);
	}


//---------------------------------------------------------------
//---------------------------------------------------------------


/**************************
* MUESTA LA FOTO DEL PROD
***************************/

function hide_foto(){
	$('#pop-up').hide();
}
// muestro foto de la parte
function show_foto(imgpath ,tit){
	$.ajax({
			url:"/basic_app/prod_images/"+imgpath,
			type:'HEAD',
			error: function()
			{
					return false;
			},
		 success: function(output, status, xhr) {
								$('#myModalLabel').html(tit);
								$('#modal_content').html("<div><img class=\"img-responsive\" style=\"margin:auto;\" src=\"/basic_app/prod_images/"+ imgpath +"\"/></div>");
								$('#myModal').modal({keyboard: false});
				},

	});
}
