

function emitirTks(){
	
	data = {
		'cantTickets':$('#cantidadTks').val(),
	 	'servicios_id': window.tcx.selectedService.servicios_id,
	 	'hora_salida':window.tcx.selectedService.hora,
	 	'tarifa':window.tcx.selectedService.tarifa,
	 	'formaDePago':$("#formaDePago option:selected").val(),
	 	'nroTransacTarjeta':$("#nroTransacTarjeta").val()
	}
	 	
	 	console.log('sending data',data);
	 //loader('show');
			return $.ajax({
				type : "POST",
				url : "tickets/make_tkt",
				data : data,
				dataType : "json",
				success : function(r) {
					//loader('hide');
					console.log('recieved',r)
					//window[r.action](r);
				},
				error : function(xhr, ajaxOptions, thrownError) {
					console.log('err:',xhr)
					//myAlertLoader('failed',thrownError);
				}
			});

	// respuesta ok de ajax
	$('#modalFooterMsg').removeClass('hidden')

	setTimeout(function() {$('#myModal').modal('hide')},2500);
}

// select_servicio('10:00','1-Hora','REGULAR','180.00','Río-Jet-1')
function select_servicio(h,tp,sbtp,brco,srvid,trf,hsId,srvDate) {
  	window.tcx.selectedService = {'hora':h,'servicios_id':srvid,'tarifa':trf,'hsId':hsId,'fecha_servicio':srvDate};
  	var titSbtp = (sbtp !='ESTUDIANTIL')?sbtp:'';
  	$('#descripServicio').html('Salida:&nbsp;'+h+"Hs&nbsp;&nbsp;&nbsp;Paseo: "+tp+' '+titSbtp+'&nbsp;&nbsp;Barco: '+brco );
  	$("#totImporte").html("Total a Cobrar $: "+ (parseInt(window.tcx.selectedService.tarifa) * $("#cantidadTks").val() ) );
  	$('#modalFooterMsg').addClass('hidden');
	$("#formaDePago option[value='EFECTIVO']").prop('selected', true);
	$("#nroTransacTarjeta").val('');
	$("#fgNroTransacTarjeta").addClass("hidden");
	$("#cantidadTks").val('1');
	$('#myModal').modal('show'); 
}

function checkIdTransacTarjeta(){
	if($("#nroTransacTarjeta").val() != ''){
		$("#btnEmitirTks").removeClass("disabled");
		$("#fgNroTransacTarjeta").removeClass("has-error");
	}
}

function checkFormaDePago(e){
	if($("#formaDePago option:selected").val() === "TARJETA"){
		$("#nroTransacTarjeta").val('')
		$("#fgNroTransacTarjeta").removeClass("hidden");
		$("#fgNroTransacTarjeta").addClass("has-error");
		$("#nroTransacTarjeta").focus();
		$("#btnEmitirTks").addClass("disabled");
	}else{
		$("#fgNroTransacTarjeta").addClass("hidden");
		$("#btnEmitirTks").removeClass("disabled");
	}
}


function checkCantidad(e){
	console.log($("#"+e.id).val());
	var cd = parseInt($("#cantdiponible").val())
	console.log(' disp:',(parseInt($("#"+e.id).val()) > cd))
	if(parseInt($("#"+e.id).val()) > cd){
		$("#fgCantTks").addClass("has-error");
		$("#lblCantTks").html("Error - Cantidad no disponible");
		$("#btnEmitirTks").addClass("disabled");
	}else{
		$("#fgCantTks").removeClass("has-error");
		$("#lblCantTks").html("Cantidad Seleccionada");
		$("#btnEmitirTks").removeClass("disabled");
		$("#totImporte").html("Total a Cobrar $: "+ (parseInt(window.tcx.selectedService.tarifa) * $("#"+e.id).val() ) );
	}
}

function nueva_func(){
	//testing push al repo
	//testing push to repo 2
	//algo nuevo
	// un nuevo agregado para nueva func
}

/**************
///TODO VIEJO
/************************************
/************************************
/************************************
* DEFINICIONES ESTRUCTURAS Y OBJETOS
************************************/
//GLOBAL WRAPPER
	var TOP = {};
// funciones para usar con .sort()
	var by = function (name) {
	return function (o, p) {
		var a, b;
		if (typeof o === 'object' && typeof p === 'object' && o && p) {
			a = o[name];
			b = p[name];
			if (a === b) {
				return 0;
			}
			if (typeof a === typeof b) {
				return a < b ? -1 : 1;
			}
			return typeof a < typeof b ? -1 : 1;
		} else {
			throw {
				name: 'Error',
				message: 'Expected an object when sorting by ' + name
			};
		}
	};
	};
	var by2 = function (name, minor) {
	return function (o, p) {
		var a, b;
		if (o && p && typeof o === 'object' && typeof p === 'object') {
			a = o[name];
			b = p[name];
			if (a === b) {
				return typeof minor === 'function' ? minor(o, p) : 0;
			}
			if (typeof a === typeof b) {
				return a < b ? -1 : 1;
			}
			return typeof a < typeof b ? -1 : 1;
		} else {
			throw {
				name: 'Error',
				message: 'Expected an object when sorting by ' + name
			};
		}
	};
	};
	// find if is an array
	var isArray=function (value) {
  	return value &&
	  typeof value === 'object' &&
	  typeof value.length === 'number' &&
	  typeof value.splice === 'function' &&
	  !(value.propertyIsEnumerable('length'));
  };

	/**************************
	* SEPARADORDE MILES CON .
	***************************/
	function numberWithDots(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}

	/*************************
	* RAMDOM COLOR GENERATOR
	**************************/
	function getRandomColor() {
    //var letters = '0123456789ABCDEF'.split('');
		var letters = 'CA6B6667896C6D8E'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
		return color;
}

function rgbToHex(r, g, b) {
console.log('rgb',r,g,b);
console.log('hex',"#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1));
		return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
}

function pickHex(color1, color2, weight) {
console.log('colors',color1,color2,weight);
    var p = weight/100;
    var w = p * 2 - 1;
    var w1 = (w/1+1) / 2;
    var w2 = 1 - w1;
    var rgb = [Math.round(color1[0] * w1 + color2[0] * w2),
        Math.round(color1[1] * w1 + color2[1] * w2),
        Math.round(color1[2] * w1 + color2[2] * w2)];
    return rgbToHex(rgb[0],rgb[1],rgb[2]);
}

// ******** asigna un color al porcetaje en el param
function colortemp(percent){
	var p=percent+Math.pow(percent,2)/100
	p=(p>100)?80:p;
	console.log('perce',percent);
	console.log('p',p);
	var c1=[20,230,20]
	var c2=[230,20,20]
	return pickHex(c1,c2,p)
}
	/******************
	* KNOWLEDGE DATA STRUCT
	*******************/
	var knldg= function(d){
  	this.stk = d
	}
	knldg.prototype.search = function (lbl,val) {
		var res = function lp(s,lbl,val,i){
			if(i === s.length){return}
			else{
				if(s[i][lbl] === val){return i}
				else{return lp(s,lbl,val,i+1)}
			}
		}
		return res(this.stk,lbl,val,0);
		};

		knldg.prototype.group = function (lbl,val) {
			var gruped = []
			var res = function lp(s,lbl,val,i){
				if(i === s.length){return gruped}
				else{
					if(s[i][lbl] === val){return
						gruped.push(s[i]);
						s.slice(i,1)
						return lp(s,lbl,val,0)
					}
					else{return lp(s,lbl,val,i+1)}
				}
			}
			return res(this.stk,lbl,val,0);
			};


/**ACTIONS******************************/
/********************************************************
* LLAMA A UN controler O EJECUTA UNA ACTION pasa un objeto con: los params necesarios controller , datos
* recibe un objeto con action y datos
*********************************************************/

//controler,action,user,dat
// function Do(d){
// 	if(d.typeof != 'undefined' ){
// 		if(d.hasOwnProperty('controller')){
// 			myAlertLoader('show');
// 			return $.ajax({
// 				type : "POST",
// 				url : d.controller,
// 				data : d,
// 				dataType : "json",
// 				success : function(r) {
// 					myAlertLoader('hide');
// 					console.log(r)
// 					//window[r.action](r);
// 				},
// 				error : function(xhr, ajaxOptions, thrownError) {
// 					console.log('err:',xhr)
// 					myAlertLoader('failed',thrownError);
// 				}
// 			});

// 		}else{
// 				console.log('action',d)
// 				window[d.action](d);
// 		}
// 	}
// }
// muestra un item de la tabla layout
function show(i){
	//i es el layout item
	// SI ES UNA TABLA
	if(i.element === 'table'){
		if(i.usertype == 'supervisor'){
			i.elem_contentData=TOP[i.data_container].stk[TOP.selectedAgId].map(function(lstline,stki){return{'attrib':i.elem_attribs,'items':i.elem_cont_data_src.map(function(ds,idx){return contentsFilter({'origin':i.data_container,'rowIndex':stki,'colIndex':idx},{'content':lstline[ds.content],'src':ds.content})})}});
		}else if (i.usertype == 'agent'){
			i.elem_contentData=TOP[i.data_container].stk.map(function(lstline,stki){return{'attrib':i.elem_attribs,'items':i.elem_cont_data_src.map(function(ds,idx){return contentsFilter({'origin':i.data_container,'rowIndex':stki,'colIndex':idx},{'content':lstline[ds.content],'src':ds.content})})}});
		}
	}

	// SI ES EL COLLAPSE DE FAMILIAS DE PRODUCTOS U OTRO CON SUBSTACK
	// agrega collapseLines que es un objeto knldg con los contenidos de la tabla
	if(i.element === 'collapse'){
			i.collapseLines = TOP[i.data_container].stk.map(function(prlne,stki){return {'id':prlne.famdat.prod_families_id,'heading':' '+prlne.famdat.description,'contnt':prlne.prdata.map(function(lstline,sbstki){return{'attrib':i.elem_attribs,'items':i.elem_cont_data_src.map(function(ds,idx){return contentsFilter({'origin':i.data_container,'stkIndx':stki,'substackIndx':sbstki,'colIndex':idx},{'content':lstline[ds.content],'src':ds.content})})}})}})
	}
	window['mk_'+i.element](i);
	postElemCreationHook(i)
}
// hook after make
function postElemCreationHook(i){
	// SI EL EL TITULO DEL LISTADO DE PRODUCTOS AGREGO UNA COLUMNA DE TOTAL DEL PEDIDO
	if(i.elem_id == 'prodsPanel'){
		$('#pnlHeading_prodsPanel').append("<div class='text-right'><big><strong>Total pedido $:&nbsp; <span id=cartTotal>0.00</span></big></strong></div>")
	}
	if(i.elem_id == 'cliPanel'){
		$('#col_visita').html("<div class='text'><strong>Selecciona Un Cliente para registrar la visita o guardar un pedido</strong></div>")
	}
}

	/********************************************************
	* CONTROLA Y MODIFICA  LOS DATOS EN dta ANTES DE PONER EL CONTENIDO EN PANTALLA
	PARAM:
	------
	dtOrig Object {
		origin: "prodsList",
		stkIndx: 2,
		substackIndx: 12,
		colIndex: 4
	}
	dta Object {
		content: "10",
		src: "cant_available"
	}
	********************************************************/
	//
function contentsFilter(dtOrig,dta){
	// FILTROS PARA AGENT VIEW ****
	//DEL LISTADO DE CLIENTES
	//pone el nombre del cliente en lugar del id de cliente
	if(dtOrig.origin == 'agIdActs' && dtOrig.colIndex == 1){
		//console.log(dta.content);
		dta.content = (typeof(TOP.clientsList.search('client_id',dta.content)!='undefined'))?TOP.clientsList.stk[TOP.clientsList.search('client_id',dta.content)].RAZON_SOCIAL:[];
	}
	//pone el nombre del cliente en lugar del id de cliente
	if(dtOrig.origin == 'contacts'  && dtOrig.colIndex == 1){
		dta.content = (typeof(TOP.clientsList.search('client_id',dta.content)!='undefined'))?TOP.clientsList.stk[TOP.clientsList.search('client_id',dta.content)].RAZON_SOCIAL:[];
	}
	//FILTROS DEL LISTADO DE PRODUCTOS
	// pone image icon y funcion de llamada a foto en la columna donde esta el nombre del archivo.jpg
	if (dtOrig.origin == 'prodsList' && dtOrig.colIndex == 2 && dtOrig.hasOwnProperty('substackIndx') && typeof(dta.content) != 'undefined') {
		dta.content = "<button onClick=\"show_foto(\'"+dta.content+"\',\'"+TOP[dtOrig.origin].stk[dtOrig.stkIndx].prdata[dtOrig.substackIndx].descript+"')\" type='button' class='btn btn-sm btn-default'><span class='glyphicon glyphicon-camera' aria-hidden='true'></span></button>";
	}
	// cant_available ES EL NUMERO DE ITEMS QUE LE PONGO A MAXITEMS EN EL CAMPO NUMERICO DE SELECCION
	if(dtOrig.origin == 'prodsList' && dta.src == "cant_available"){
		var inpId = 'inpCant_'+dtOrig.stkIndx+'-'+dtOrig.substackIndx
		dta.content = "<input id=\'"+inpId+"\' class='form-control' style='padding:5px;width:55px;display:inline;' type='number' min='0' max='"+dta.content+"' step='1' value=0 onChange=update_cart(\'"+JSON.stringify(dtOrig)+"\')>"
	}
	return dta;
}

	/********************************************************
	* Hook de los cliks en listados  lmId = clicked: clientsList_table_row_3
	********************************************************/
	function clickedHook(lmId){
		//parsing datos del elemento para identificarlo
		var list = lmId.slice(0,lmId.indexOf("_"));
		var element = lmId.slice((lmId.indexOf("_")+1),lmId.lastIndexOf("_"));
		var listAndElement = lmId.slice(0,lmId.lastIndexOf("_"));
 		var elementIndx = lmId.slice(lmId.lastIndexOf("_")+1);
		var clicked = {
			'list': list,
			'element':element,
			'listElemIndx': elementIndx ,
			//'source':TOP.currentLayout.stk[TOP.currentLayout.search('elem_id',list+'_table')],
			//'currElem': TOP.currentLayout.stk[TOP.currentLayout.search('elem_id',list+'_table')],
			'action':'resToClickOn_'+ lmId.slice((lmId.indexOf("_")+1),lmId.lastIndexOf("_"))
		}
		Do(clicked);
	}


	/********************************************************
	* EVENTOS
	********************************************************/

	function resToClickOn_table_row(o){
		switch (o.list) {
			case 'clientsList':
			TOP.selectedCliAddrs = TOP[o.list].stk[o.listElemIndx];
			// filtro el contacts stak
			var fCont = TOP.contacts.stk.filter(function(e){return e.client_id == TOP[o.list].stk[o.listElemIndx].client_id});
			var tgtElm = TOP.currentLayout.stk[TOP.currentLayout.search('elem_id','contacts_table')];
			refresh_element(fCont,tgtElm);
			//muestra el boto de registrar visita
			$('#col_visita').html("<button type='button' id='bot_visita' class='btn btn-success' onCLick=regVisita() >Registrar Visita</button>");
			break;
			default:
			break;
		}
	}
	function resToClickOn_table_headCol(o){
		TOP[o.list].stk.sort(by(TOP.currentLayout.stk[TOP.currentLayout.search('elem_id',o.list+'_table')].elem_cont_data_src[o.listElemIndx ].content));
		refresh_element(TOP[o.list].stk,o.currElem);
	}

	//******************************************************
	// REHACE EL ELEMENTO SELECCIONADO EN target CON newCnt
	//******************************************************
	//PARAMS: TOP
	function refresh_element(newCnt,target){
		target.elem_contentData = newCnt.map(function(nc,stki){return {'attrib': target.elem_attribs,'items':target.elem_cont_data_src.map(function(ds,idx){return contentsFilter({'origin':target.data_container,'rowIndex':stki,'colIndex':idx},{'content':nc[ds.content],'src':ds.content})})}});
		$('#'+target.container_div_id).empty();
		window['mk_'+target.element](target);
	}

	/****************************************************************************
	* LOCAL STORAGE  FALTA IMPLEMENTAR mantiene la persistencia de los objetos
	* convirtiendo a string y parseando a objetos tipo KNOWLEDGE (knldg)
	*****************************************************************************/
	function localSave(o){
		if (typeof(localStorage) == 'undefined' ) {
		 alert('Este browser no soporta localStorage. actualizar el browser para resolver este problema');
	 	}else {
		 	try {
				 //localStorage.setItem('topStore', JSON.stringify(TOP)); //saves to the database, “key”, “value”
				 localStorage.topStore = JSON.stringify(o);
		 	}catch (e) {
			 	if (e == QUOTA_EXCEEDED_ERR) {
				 	alert('no hay mas espacio en localStorage!'); //data wasn’t successfully saved due to quota exceed so throw an error
			 	}
		 	}
		}
	}

	function localRecover(o){
		console.log('dd',JSON.parse(localStorage.getItem('topStore')));

	}
		 //var restoredSession = JSON.parse(localStorage.getItem('topStore'));
	 //console.log('topstore',localStorage.getItem('topStore'));
	//top store recovered
	//var r = new knldg(JSON.parse(localStorage.getItem('topStore')).o)

	//return r;
	 //localStorage.removeItem('name'); //deletes the matching item from the database
	 //localStorage.clear();


	/********************************************************
	* LIMPIA LA PANTALLA ANTES DE PONER EL NUEVO CONTENIDO
	********************************************************/
	function clean(target) {
		switch(target){
			case 'campaign':
				// linpieza del layout de campaña
				$('#clientsList').empty();
				$('#productsList').empty();
				$('#contactsPanel').empty();
				$('#condicPanel').empty();
				$('#col_visita').empty();

			break;
			case 'report':
				$('#content_pnl_1').empty();
				$('#content_pnl_2').empty();
				$('#content_pnl_3').empty();
				$('#content_pnl_4').empty();
				$('#footer').empty();
			break;
			case 'dd':
				//limpieza selectors content
				$('#sel_potencias').empty();
				$('#sel_sectors').empty();
			break;
			case 'gg':
				$('#container').empty();
		}
	}
	/*************************************
	VENTANA DE MENSAJES Y LOADER DEL AJAX
	A REEMPLAZR CON JQUERY BLOCKUI  
	**************************************/
	function myAlertLoader(state, msg){
		var res=true;
		if (typeof(msg)==='undefined') msg = '';
		switch(state){
			case 'ask':
			$.fancybox("<div style='width:250px;margin-top:23px;'><div class='alert alert-success' role='alert'><button type='button' onClick=myAlertLoader('hide') class='close' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Success!&nbsp;</strong>"+msg+"</div></div>", {
					modal : true,
					});
			break;
			case 'show':
			$.fancybox("<div style='margin:20px;'><img src='/ciparts/images/ajax-loader.gif' width='60' height='60' /></div>", {
					modal : true,
					});
			break;
			case 'hide':
			$.fancybox.close();
			break;
			case 'success':
			$.fancybox("<div style='width:250px;margin-top:23px;'><div class='alert alert-success' role='alert'><button type='button' onClick=myAlertLoader('hide') class='close' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Success!&nbsp;</strong>"+msg+"</div></div>", {
					modal : true,
					});
			break;
			case 'warning':
			$.fancybox("<div style='width:250px;margin-top:23px;'><div class='alert alert-warning' role='alert'><button type='button' onClick=myAlertLoader('hide') class='close' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Warning!&nbsp;</strong>"+msg+"</div></div>", {
					modal : true,
					});
			break;
			case 'failed':
			$.fancybox("<div style='width:250px;margin-top:23px;'><div class='alert alert-danger' role='alert'><button type='button' onClick=myAlertLoader('hide') class='close' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Failed!&nbsp;</strong>"+msg+"</div></div>", {
					modal : true,
					});
			break;

		}
		return res;
	}
