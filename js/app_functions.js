
// TIKETS ********
function getTkts(filter){
	data = {
		'fecIn':$('#dpk_tkts_desde_'+filter).find("input").val(),
		'fecOut':$('#dpk_tkts_hasta_'+filter).find("input").val(),
		'filter':filter
	}
	$.blockUI({ message: null, baseZ: 10000  }); 
	return $.ajax({
		type : "POST",
		url : "reportes/get_tkts",
		data : data,
		dataType : "json",
		success : function(r) {
				//r.result = false;
				$.unblockUI(); 
				// console.log('recieved',r.result)
				if(r.result === false){
					myAlert("#main_container","warning","Error!","No hay Tickets para el dia seleccionado.");
				}else{
					// respuesta ok de ajax
					window.tcx.data = r.result
					 console.log('tikets',r);
					var screen = '<table class=\"table table-responsive table-striped table-hover\"><thead><tr>';
					for (var i = 0; i < r.header.length; i++) {
						screen += "<th>"+r.header[i]+"</th>";
					}
					screen += "</tr></thead><tbody>";
					var totImporte = 0.00;
					var totTikets = 0;
					for (var i = 0; i < r.result.length; i++) {
						screen += "<tr><td>"+moment(r.result[i].fecha).format("DD/MM/YYYY")+"</td><td>"+r.result[i].tipo+" - "+r.result[i].subtipo+"</td><td>"+r.result[i].hora_salida+"</td><td>"+r.result[i].cantkts+"</td><td>"+r.result[i].total+"</td></tr>";
						totImporte += parseFloat(r.result[i].total);
						totTikets +=parseInt(r.result[i].cantkts);
					}
					screen += "<tr class='active bordered'><th class='text-center' colspan='3'>TOTALES</th><th>"+totTikets+"</th><th>"+totImporte.toFixed(2)+"</th></tr>";
					screen +="</tbody></table>";
					$('#main_container_'+filter).html(screen);
				}
			},
			error : function(xhr, ajaxOptions, thrownError) {
				$.unblockUI();
				myAlert("danger","Error","Error de comunicación...");
				console.log('err:',xhr)
			}
		});
}

function anularTicket(conf){
 	if(window.tcx.lastInsertedTkts == null){
 		myAlert("#mesages","warning","No hay ticket para anular");
 	}else if(conf == false){
 		$('#myModalAnularTicket').modal('show');
 	}
 	if(conf){
 		$('#myModalAnularTicket').modal('hide');
 			//console.log('anulando tickets',window.tcx.lastInsertedTkts );
 		data = {'tkts':window.tcx.lastInsertedTkts}
 	
  		$.blockUI({ message: null, baseZ: 10000  }); 
	 	return $.ajax({	
	 		type : "POST",
	 		url : "tickets/anular_tkt",
	  		data : data,
	  		dataType : "json",
	  		success : function(r) {
	  				$.unblockUI();
	  				if(r.status == false){
						myAlert("#mesages","danger","Error!","No se puede anular el ticket solicitado");	
					}else{
						// respuesta ok de ajax
						myAlert("#mesages","success","Ticket anulado");
	 				}
			},
			error : function(xhr, ajaxOptions, thrownError) {
					$.unblockUI();
	 				myAlert("#mesages","danger","Error!","No se puede conectar con el servidor");	
			}
		});
		window.tcx.lastInsertedTkts = null;
	}	
 }
 

function emitirTks(){
	
	data = {
		'cantTickets':$('#cantidadTks').val(),
		'tipo_comp':"TIKET",
		'servicios_id': window.tcx.selectedService.servicios_id,
		'hora_salida':window.tcx.selectedService.hora,
		'tarifa':window.tcx.selectedService.tarifa,
		'chk_sel':($("#formaDePago option:selected").val() == "TARJETA" ||	 $("#chk_selected").prop('checked')?1:0),
		'formaDePago':$("#formaDePago option:selected").val(),
		'nroTransacTarjeta':$("#nroTransacTarjeta").val(),
		'histServiciosId':window.tcx.selectedService.hsId,
		'fecha':window.tcx.selectedService.fecha_servicio,
		'user_id':window.tcx.user.id,
		'clientes_id':''
	}

	//console.log('sending data',data);
	// show loading ....
	$.blockUI({ message: null, baseZ: 10000  }); 

	return $.ajax({
	 	type : "POST",
	 	url : "tickets/make_tkt",
	 	data : data,
	 	dataType : "json",
	 	success : function(r) {
	 		$.unblockUI(); 
	 		console.log('recieved',r)

	 		if(r.result === false){
	 			$('#totImporte').html('');
	 			$('#modalFooterMsg').removeClass("label label-success glyphicon glyphicon-ok hidden")
	 			$('#modalFooterMsg').addClass("label label-warning glyphicon glyphicon-remove hidden")
	 			$('#modalFooterMsgtxt').html('<big>Fallo la emision del ticket!</big>');					
	 			$('#modalFooterMsg').removeClass('hidden')
	 			setTimeout(function() {
	 				$('#modalFooterMsg').addClass('hidden');
	 				$("#cantidadTks").val('1');
	 				$("#totImporte").html("Total a Cobrar $: "+ (parseInt(window.tcx.selectedService.tarifa) * $("#cantidadTks").val() ) );
	 			},2500);
	 		}else{
					// respuesta ok de ajax
					window.tcx.lastInsertedTkts = r.result;
					$('#totImporte').html('');
					$('#modalFooterMsg').removeClass("label label-warning glyphicon glyphicon-remove hidden")
					$('#modalFooterMsg').addClass("label label-success glyphicon glyphicon-ok hidden")
					$('#modalFooterMsgtxt').html('<big>Imprimiendo Ticket...</big>');					
					$('#modalFooterMsg').removeClass('hidden')
					setTimeout(function() {$('#myModal').modal('hide')},2000);
				}
		},
		error : function(xhr, ajaxOptions, thrownError) {
				$.unblockUI();
				console.log('err:',xhr)
				$('#totImporte').html('');
				$('#modalFooterMsg').removeClass("label label-success glyphicon glyphicon-ok hidden")
				$('#modalFooterMsg').addClass("label label-warning glyphicon glyphicon-remove hidden")
				$('#modalFooterMsgtxt').html(xhr);					
				$('#modalFooterMsg').removeClass('hidden')
				setTimeout(function() {
					$('#modalFooterMsg').addClass('hidden');
					$("#cantidadTks").val('1');
					$("#totImporte").html("Total a Cobrar $: "+ (parseInt(window.tcx.selectedService.tarifa) * $("#cantidadTks").val() ) );
				},2500);
		}
	});
}


function select_servicio(h,tp,sbtp,brco,trf,hsId,srvDate,srvid) {
  	
  	window.tcx.selectedService = {'hora':h,'servicios_id':srvid,'tarifa':trf,'hsId':hsId,'fecha_servicio':srvDate};
  	// fix tipo y subtipo estudiantil que se repite en descripcion del servicio.
  	var titSbtp = (sbtp !='ESTUDIANTIL')?sbtp:'';
  	$('#descripServicio').html('Salida:&nbsp;'+h+"Hs&nbsp;&nbsp;&nbsp;Paseo: "+tp+' '+titSbtp+'&nbsp;&nbsp;Barco: '+brco );
  	$('#modalFooterMsg').addClass('hidden');
	$("#chk_selected").prop('checked', false);
	
	$("#formaDePago option[value='EFECTIVO']").prop('selected', true);
	$("#nroTransacTarjeta").val('');
	$("#fgNroTransacTarjeta").addClass("hidden");
	$("#cantidadTks").val('1');
	$("#totImporte").html("Total a Cobrar $: "+ (parseInt(window.tcx.selectedService.tarifa) * $("#cantidadTks").val() ) );
	$('#myModal').modal('show'); 
}

function checkIdTransacTarjeta(){
	if($("#nroTransacTarjeta").val() != ''){
		$("#btnEmitirTks").removeClass("disabled");
		$("#fgNroTransacTarjeta").removeClass("has-error");
	}else{
		$("#btnEmitirTks").addClass("disabled");
		$("#fgNroTransacTarjeta").addClass("has-error");
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
	var cd = parseInt($("#cantdiponible").val())
	if(parseInt($("#"+e.id).val()) > cd){
		$("#fgCantTks").addClass("has-error");
		$("#lblCantTks").html("Error - Cantidad no disponible");		$("#btnEmitirTks").addClass("disabled");
	}else{
		$("#fgCantTks").removeClass("has-error");
		$("#lblCantTks").html("Cantidad Seleccionada");
		$("#btnEmitirTks").removeClass("disabled");
		$("#totImporte").html("Total a Cobrar $: "+ (parseInt(window.tcx.selectedService.tarifa) * $("#"+e.id).val() ) );
	}
}



//  OPERACIONES *******************

function serv_edit(i){
	window.tcx.crtindex = i;
	var d = window.tcx.data[i].servicio;
	// console.log('serv edit',d.id)
	$('#myModalOperTitle').html('Modificando Servicio dia: <strong>'+ $('#dpk_servicios').find("input").val()+'</strong>');
	$("#selectServicioHoraSalida").val(d.salida_id);
	$("#selectTipoPaseo").val(d.codigo_tipo_servicios);
	$("#selectBarco").val(d.id_barco);
	$("#selectServicioEstado").val(d.estado);
	$('#myModalTrpTitle').addClass('hidden');
	$('#myModalTrpBody').addClass('hidden');	
	$('#myModalOperBody').removeClass('hidden');
	$('#myModalOperTitle').removeClass('hidden');
	$('#myModalOper').modal('show'); 
}

function setNewServicios(){
	window.tcx.crtindex = -1;
	// var d = window.tcx.data[i].servicio;
	// console.log('serv edit',d.id)
	$('#myModalOperTitle').html('Crear Servicio');
	// $("#selectServicioHoraSalida").val();
	// $("#selectTipoPaseo").val(d.codigo_tipo_servicios);
	// $("#selectBarco").val(d.id_barco);
	// $("#selectServicioEstado").val(d.estado);
	$('#myModalTrpTitle').addClass('hidden');
	$('#myModalTrpBody').addClass('hidden');	
	$('#myModalOperBody').removeClass('hidden');
	$('#myModalOperTitle').removeClass('hidden');
	$('#myModalOper').modal('show'); 
}

function show_tripl(idx){
	var arrTrp = window.tcx.data[idx].tripulacion;
	var objSrv = window.tcx.data[idx].servicio;
	var header = Object.keys(arrTrp[0][0]);
	var htit = "";
	for (var i = 0; i < header.length; i++) {
		htit += "<th>"+header[i]+"</th>";
	}
	var txtTrp = "";
	for (var y = 0; y < arrTrp.length; y++) {
		txtTrp += "<tr>";
		for (var x = 0; x < header.length; x++) {
			txtTrp += "<td>"+arrTrp[y][0][header[x]]+"</td>";
		}
		txtTrp += "</tr>";
	}
	var srvtit = "<h5> Servicio: "+moment(objSrv.fecha_servicio).format("DD/MM/YYYY")+" - "+objSrv.hora_salida+"Hs - "+objSrv.tipo+" "+objSrv.subtipo+"&nbsp;"+objSrv.barco+"</h5>";
	var scrn = "<h5>Tripulación</h5>";
	scrn += "<table class='table table-bordered table-responsive table-striped table-hover'>\
			<thead><tr>"+htit+"</tr></thead>\
			<tbody>"+txtTrp+"</tbody></table>";
	
	$('#myModalOper').on('hidden.bs.modal', function (e) {
		$('#myModalTrpTitle').html('');
		$('#myModalTrpBody').html('');
					
	})

	$('#myModalOperBody').addClass('hidden');
	$('#myModalOperTitle').addClass('hidden');
	$('#myModalTrpTitle').html(srvtit);
	$('#myModalTrpBody').html(scrn);			
	$('#myModalTrpTitle').removeClass('hidden');
	$('#myModalTrpBody').removeClass('hidden');
	$('#myModalOper').modal('show');
}


function getServicios(){
	data = {
		'fecha':$('#dpk_servicios').find("input").val()
	}
	$.blockUI({ message: null, baseZ: 10000  }); 
	return $.ajax({
		type : "POST",
		url : "operaciones/listado_servicios_dia",
		data : data,
		dataType : "json",
		success : function(r) {
				//r.result = false;
				$.unblockUI(); 
				console.log('recieved',r.result)
				if(r.result === false){
					myAlert("#main_container","warning","Error!","No hay Servicios para el dia seleccionado.");
				}else{
					// respuesta ok de ajax
					window.tcx.data = r.result
					console.log('servicios',window.tcx.data);
					var screen = '<table class=\"table table-responsive table-striped table-hover\"><thead><tr>';
					for (var i = 0; i < r.header.length; i++) {
						screen += "<th class='text-center'>"+r.header[i]+"</th>";
					}
					screen += "</tr></thead><tbody>";
					for (var i = 0; i < r.result.length; i++) {
						screen += "<tr><td>"+moment(r.result[i].servicio.fecha_servicio).format("DD/MM/YYYY")+"</td><td>"+r.result[i].servicio.hora_salida+"</td><td>"+r.result[i].servicio.tipo+"</td><td>"+r.result[i].servicio.subtipo+"</td><td>"+r.result[i].servicio.estado+"</td><td>"+r.result[i].servicio.cant_pasajeros+"</td><td>"+r.result[i].servicio.barco+"</td><td>"+(r.result[i].tripulacion.length>0? "<a href=\"#\" title=\'Ver Tripulación\'><span class=\"glyphicon glyphicon-user\" aria-hidden=\"true\" onClick='show_tripl("+i+")'></span></a>" :'<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>')+"</td><td><a href=\"#\" title=\'Editar Servicio\'><span class=\"glyphicon glyphicon-edit\" aria-hidden=\"true\" onClick='serv_edit("+i+")'></a></span></td></tr>";
					}
					screen +="</tbody></table>";
					$('#main_container').html(screen);
				}
			},
			error : function(xhr, ajaxOptions, thrownError) {
				$.unblockUI();
				myAlert("danger","Error","Error de comunicación...");
				console.log('err:',xhr)
			}
		});
}



function guardaServ(){
	if(window.tcx.crtindex === -1){
		data = {
		'fecha_servicio':$('#dpk_servicios').find("input").val(),
		'horarios_id': $("#selectServicioHoraSalida").val(),
		'codigo_tipo_servicios': $("#selectTipoPaseo").val(),
		'estado': $("#selectServicioEstado").val(),
		'barcos_id': $("#selectBarco").val(),
		'tripulacion':$("#selectTrpl").val().join(", ")
		};
		var method = "create";
	}else{
		data = {
		'id':window.tcx.data[window.tcx.crtindex].servicio.id,
		'horarios_id': $("#selectServicioHoraSalida").val(),
		'codigo_tipo_servicios': $("#selectTipoPaseo").val(),
		'estado': $("#selectServicioEstado").val(),
		'barcos_id': $("#selectBarco").val(),
		'tripulacion':$("#selectTrpl").val().join(", ")
		};
		var method = "update";
	}
	

	//console.log('sending data',data);
	// show loading ....
	$.blockUI({ message: null, baseZ: 10000  }); 

	return $.ajax({
	 	type : "POST",
	 	url : "operaciones/"+method,
	 	data : data,
	 	dataType : "json",
	 	success : function(r) {
	 		$.unblockUI(); 
	 		 console.log('recieved',r)
	 		if(r.result === false){
	 			$('#totImporte').html('');
	 			$('#modalFooterMsg').removeClass("label label-success glyphicon glyphicon-ok hidden")
	 			$('#modalFooterMsg').addClass("label label-warning glyphicon glyphicon-remove hidden")
	 			$('#modalFooterMsgtxt').html('<big>Error de comunicación...</big>');					
	 			$('#modalFooterMsg').removeClass('hidden')
	 			setTimeout(function() {
	 				$('#modalFooterMsg').addClass('hidden');
	 			},2500);
	 		}else{
					// respuesta ok de ajax
					$('#modalFooterMsg').removeClass("label label-warning glyphicon glyphicon-remove hidden")
					$('#modalFooterMsg').addClass("label label-success glyphicon glyphicon-ok hidden")
					$('#modalFooterMsgtxt').html('<big>Guardando...</big>');					
					$('#modalFooterMsg').removeClass('hidden')
					setTimeout(function() {
						$('#modalFooterMsg').addClass('hidden');
						$('#myModalOper').modal('hide')},2000);
						getServicios();
				}
		},
		error : function(xhr, ajaxOptions, thrownError) {
				$.unblockUI();
				console.log('err:',xhr)
				$('#modalFooterMsg').removeClass("label label-success glyphicon glyphicon-ok hidden")
				$('#modalFooterMsg').addClass("label label-warning glyphicon glyphicon-remove hidden")
				$('#modalFooterMsgtxt').html('Error de comunicación');					
				$('#modalFooterMsg').removeClass('hidden')
				setTimeout(function() {
					$('#modalFooterMsg').addClass('hidden');
				},2500);
		}
	});
}


// RESERVAS ************

function getTarifas(){
	//$.blockUI({ message: null, baseZ: 10000  }); 
	return $.ajax({
		type : "POST",
		url : "reservas/get_tarifas",
		// data : data,
		dataType : "json",
		success : function(r) {
				//console.log('rr',r.tarifas);
				if(r.tarifas === false){
					console.log('error en la carga de tarifas:',r.tarifas)
					// myAlert("#main_container","warning","Error!","No hay Servicios para el dia seleccionado.");
				}else{
					// respuesta ok de ajax
					window.tcx.tarifas = r.tarifas
				}
			},
			error : function(xhr, ajaxOptions, thrownError) {
				console.log('error en la carga de tarifas:',xhr)
			}
		});
}



function getReservas(fecha=null,scope_all='false'){
	data = {'fecha':fecha,'scope_all':scope_all};	
	$.blockUI({ message: null, baseZ: 10000  }); 
	return $.ajax({
		type : "POST",
		url : "reservas/list_reservas_dia",
		data : data,
		dataType : "json",
		success : function(r) {
				//r.result = false;
				$.unblockUI(); 
				// console.log('recieved',r.result)
				if(r.result === false){
					myAlert("#main_container","warning","No hay Reservas para el dia seleccionado.","");
				}else{
					// respuesta ok de ajax
					window.tcx.data = r.result
					console.log(' reserva',r.result)
					var screen = '<table class=\"table table-responsive table-striped table-hover\"><thead><tr>';
					for (var i = 0; i < r.header.length; i++) {
						screen += "<th>"+r.header[i]+"</th>";
					}
					screen += "</tr></thead><tbody>";
					for (var i = 0; i < r.result.length; i++) {
						screen += "<tr><td>"+moment(r.result[i].fecha_reserva).format("DD/MM/YYYY")+"</td><td>"+r.result[i].hora_salida+"</td><td>"+r.result[i].tipo+"</td><td>"+r.result[i].subtipo+"</td><td>"+r.result[i].cant_pasajeros_reserva+"</td><td>"+r.result[i].monto_pagado_reserva+"</td><td>"+(parseFloat(r.result[i].monto_total_reserva)-parseFloat(r.result[i].monto_pagado_reserva))+"</td><td>"+r.result[i].nombre_barco+"</td><td>"+r.result[i].usr_usuario+"</td><td>"+r.result[i].razon_social_cliente+"</td><td>"+r.result[i].observaciones_reserva+"</td><td><a href=\"#\" title=\'Suspender Reserva\'><span class=\"glyphicon glyphicon-pause\" aria-hidden=\"true\" onClick='reserva_stop("+i+")'></a></span>&nbsp;&nbsp;&nbsp;<a href=\"#\" title=\'Editar Reserva\'><span class=\"glyphicon glyphicon-edit\" aria-hidden=\"true\" onClick='reservaEdit("+i+")'></a></span></td></tr>";
					}
					screen +="</tbody></table>";
					$('#main_container').html(screen);
				}
			},
			error : function(xhr, ajaxOptions, thrownError) {
				$.unblockUI();
				myAlert("danger","Error","Error de comunicación...");
				console.log('err:',xhr)
			}
		});
}

function setNewReserva(){
	
	$('#dpk_new_reserva').data("DateTimePicker").minDate(new Date());
	$('#dpk_new_reserva').data("DateTimePicker").date(null);
	$("#selectHoraSalida").prop('selectedIndex',0);
	$("#selectTipoPaseo").prop('selectedIndex',0);
	$("#inpCantPax").val(0);
	$("#inpMontoPagado").val(0);
	$("#inpMontoTotal").val(0);
	$("#reservasAutocmpl").val("");
	$("#imptEmailCli").val('');
	$("#imptTelCli").val('');
	$("#imptDetalle").val('');
	$('#myModalReservas').modal('show'); 
}

function reservaEdit(index){
	var d = window.tcx.data[index];
	$('#dpk_new_reserva').data("DateTimePicker").minDate(moment("2000-01-01"));
	$('#dpk_new_reserva').data("DateTimePicker").date(moment(d.fecha_reserva));
	$("#selectHoraSalida").val(d.hora_salida);
	$("#selectTipoPaseo").val(d.servicios_id);
	$("#inpCantPax").val(d.cant_pasajeros_reserva);
	$("#inpMontoPagado").val(d.monto_pagado_reserva);
	$("#inpMontoTotal").val(parseFloat(d.monto_total_reserva)-parseFloat(d.monto_pagado_reserva));
	$("#reservasAutocmpl").val(d.razon_social_cliente);
	$("#imptEmailCli").val(d.email_cliente);
	$("#imptTelCli").val(d.telefono_contacto_cliente);
	$("#imptDetalle").val(d.observaciones_reserva);
	$('#myModalReservas').modal('show'); 


}

function checkCantPaxReservas(){

	 
	var result = $.grep(window.tcx.tarifas, function(e){ console.log(e,$("#selectTipoPaseo").val());return e.id == $("#selectTipoPaseo").val()});
	var tot = (parseInt($("#inpCantPax").val()) * result[0].tarifa)- parseFloat($("#inpMontoPagado").val())
	$("#inpMontoTotal").val(parseFloat(tot).toFixed(2));
	//console.log('result',result[0].tarifa)
	
}

function saveReserva(){
	console.log('reserva save')
}


// GENERALES ************

function myAlert(container,type,tit='',msg=''){
	var scrn = "<div class=\"alert alert-dismissible alert-"+type+"\">\
  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\
  <h4>"+tit+"</h4><p>"+msg+"</p></div>";
  $(container).html(scrn);
  setTimeout(function(){$(container).html('')},3000);

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
