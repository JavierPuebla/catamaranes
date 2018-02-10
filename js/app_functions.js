// ADMINISTRACION *******

function getAdmCmpts(flt){
	var data = {
		'fecIn':$('#dpk_desde_'+flt).find("input").val(),
		'fecOut':$('#dpk_hasta_'+flt).find("input").val(),
	}
	var d={
			data:data,
			filter:flt,
			url:"administracion/get_cmprb",
			OkMsg: ""
		}
		callToServer(d);

}


function NewAdmCmpts(flt){
	window.tcx.currId=null;
	window.tcx.currAction = 'new'+flt;
	// default values que deben dar false en validate func 
	window.tcx.validateDefVals = {'dpkModalAdm':'','selectUsuario':0,'inpMonto':''};
	$('#dpkModalAdm').data("DateTimePicker").minDate(moment("2000-01-01"));
	$('#dpkModalAdm').data("DateTimePicker").date(new Date());
	$("#modalTitle").html('Nuevo Ingreso');
	$("#selectUsuario").val(0);
	$("#selectTipoComprob").val((flt == '_vta'?2:8));
	$("#selectFormaPago").val('EFVO');	
	$("#inpMonto").val('');
	$("#myModal").modal('show');

}

function updAdmCmpts(index,flt){
	window.tcx.currAction = 'upd'+flt;
	// default values que deben dar false en validate func 
	window.tcx.validateDefVals = {'dpkModalAdm':'','selectUsuario':0,'inpMonto':''};
	var itm = window.tcx.data[index];
	window.tcx.currId = itm['id'];
	console.log('it',itm);	
	$("#modalTitle").html('Modificar Ingreso')
	$('#dpkModalAdm').data("DateTimePicker").minDate(moment("2000-01-01"));
	$('#dpkModalAdm').data("DateTimePicker").date(moment(itm.fecha));
	$("#selectUsuario").val(itm.personal_id);
	$("#selectTipoComprob").val(itm.tipos_comprobantes_id);
	$("#selectFormaPago").val(itm.forma_pago);	
	$("#inpMonto").val(parseFloat(itm.importe_neto));
	$("#myModal").modal('show');	
}


function saveAdm(){
	
	var contrUrl = "administracion/"+window.tcx.currAction.slice(0,(window.tcx.currAction.indexOf("_")))+"_cmprb"; 
	var f = window.tcx.currAction.substring(window.tcx.currAction.indexOf("_"));
	var data = {
			'id':window.tcx.currId,
			'fecha':$('#dpkModalAdm').find("input").val(),
			'personal_id':$('#selectUsuario').val(),
			'tipos_comprobantes_id':$('#selectTipoComprob').val(),
			'forma_pago':$('#selectFormaPago').val(),
			'importe_neto':$('#inpMonto').val()
		}
	var d={
			data:data,
			filter:f,
			url:contrUrl,
			OkMsg: "Guardando..."
		}
	//console.log('d',d);
	let lmts = {'dpkModalAdm':$('#dpkModalAdm').find("input").val(),'selectUsuario':$('#selectUsuario').val(),'inpMonto':$('#inpMonto').val()}
	var errs = validateInputs(lmts);
	if(!errs){callToServer(d)};
}



// END ADMINISTRACION 


// TIKETS ********
function getTkts(flt){
	var data = {
		'fecIn':$('#dpk_tkts_desde_'+flt).find("input").val(),
		'fecOut':$('#dpk_tkts_hasta_'+flt).find("input").val(),
	}
	var d={
			data:data,
			filter:flt,
			url:"reportes/get_tkts",
			OkMsg: ""
		}
		callToServer(d);
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
		'tipo':"VTA-TIKET",
		'servicios_id': window.tcx.selectedService.servicios_id,
		'hora_salida':window.tcx.selectedService.hora,
		'tarifa':window.tcx.selectedService.tarifa,
		'chk_sel':($("#formaDePago option:selected").val() == "TARJETA" ||	 $("#chk_selected").prop('checked')?1:0),
		'forma_pago':$("#formaDePago option:selected").val(),
		'id_transaccion':($("#nroTransacTarjeta").val() == '' ? '0':$("#nroTransacTarjeta").val()),
		'hist_servicio_id':window.tcx.selectedService.hsId,
		'fecha':window.tcx.selectedService.fecha_servicio,
		'usuarios_id':window.tcx.user.id,
		'clientes_id':''
	}

	console.log('sending tkt data',data);
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
	$("#selectTipoPaseo").val(d.id_servicios);
	$barco = (d.id_barco <= 0)?0:d.id_barco;
	$("#selectBarco").val($barco);
	// $("#selectServicioEstado").val(d.estado);
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
	$('#myModalOperTitle').html('Crear Servicio para el dia: <strong>'+ $('#dpk_servicios').find("input").val()+'</strong>');
	$("#selectServicioHoraSalida").val(0);
	$("#selectTipoPaseo").val(0);
	$("#selectBarco").val(0);
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
						// fix cantpax ****
						screen += "<tr><td>"+moment(r.result[i].servicio.fecha_servicio).format("DD/MM/YYYY")+"</td><td class='text-center'>"+r.result[i].servicio.hora_salida+"</td><td>"+r.result[i].servicio.tipo+"</td><td>"+r.result[i].servicio.subtipo+"</td><td class='text-center'>"+r.result[i].servicio.comprobantes_cantpax+"</td><td class='text-center'>"+ (r.result[i].servicio.reservas_cantpax != null ?r.result[i].servicio.reservas_cantpax:'0') +"</td><td>"+(r.result[i].servicio.barco != null ? r.result[i].servicio.barco : '<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>')+"</td><td class='text-center'>"+(r.result[i].tripulacion.length>0? "<a href=\"#\" title=\'Ver Tripulación\'><span class=\"glyphicon glyphicon-user\" aria-hidden=\"true\" onClick='show_tripl("+i+")'></span></a>" :'<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>')+"</td><td class='text-center'><a href=\"#\" title=\'Editar Servicio\'><span class=\"glyphicon glyphicon-edit\" aria-hidden=\"true\" onClick='serv_edit("+i+")'></a></span></td></tr>";
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
	// validate del modal de sericios
	var notvalid = ($("#selectTipoPaseo").val() <= '0' || $("#selectServicioHoraSalida").val() <= '0' );
	if(notvalid){
		($('#selectTipoPaseo').val() <='0')?$('#fgTipoPaseo').addClass("has-error"):$('#fgTipoPaseo').removeClass("has-error");
		($('#selectServicioHoraSalida').val() <='0')?$('#fgHoraSalida').addClass("has-error"):$('#fgHoraSalida').removeClass("has-error");
		$('#modalFooterMsg').removeClass("label label-success glyphicon glyphicon-ok hidden")
	 	$('#modalFooterMsg').addClass("label label-warning glyphicon glyphicon-remove hidden")
		$('#modalFooterMsgtxt').html('<big>Error: Falta completar datos </big>');
		$('#modalFooterMsg').removeClass('hidden')
		console.log('returning sin send')
		return false;
	}
	// clean last red item on modal when validated ok 
	var items = new Array($('#fgHoraSalida'),$('#fgTipoPaseo'));
	for (var i = items.length - 1; i >= 0; i--) {
		items[i].removeClass("has-error");
	}
	$('#modalFooterMsgtxt').html('');
	$('#modalFooterMsg').addClass('hidden');

	if(window.tcx.crtindex === -1){
		data = {
		'fecha_servicio':$('#dpk_servicios').find("input").val(),
		'horarios_id': $("#selectServicioHoraSalida").val(),
		'servicios_id': $("#selectTipoPaseo").val(),
		//'estado': $("#selectServicioEstado").val(),
		'barcos_id': $("#selectBarco").val(),
		'tripulacion':$("#selectTrpl").val().join(", ")
		};
		var method = "create";
	}else{
		data = {
		'id':window.tcx.data[window.tcx.crtindex].servicio.id,
		'fecha_servicio':$('#dpk_servicios').find("input").val(),
		'horarios_id': $("#selectServicioHoraSalida").val(),
		'servicios_id': $("#selectTipoPaseo").val(),
		//'estado': $("#selectServicioEstado").val(),
		'barcos_id': $("#selectBarco").val(),
		'tripulacion':$("#selectTrpl").val().join(", ")
		};
		var method = "update";
	}

	console.log('sending ops data',data);
	// show loading ....
	$.blockUI({ message: null, baseZ: 10000  }); 

	return $.ajax({
	 	type : "POST",
	 	url : "operaciones/"+method,
	 	data : data,
	 	dataType : "json",
	 	success : function(r) {
	 		$.unblockUI(); 
	 		 console.log('recieved from ops',r)
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
				 console.log('recieved',r)
				if(r.result.length == 0 ){
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
						screen += "<tr><td>"+moment(r.result[i].fecha_reserva).format("DD/MM/YYYY")+"</td><td>"+r.result[i].hora_salida+"</td><td>"+r.result[i].tipo+"</td><td>"+r.result[i].subtipo+"</td><td>"+r.result[i].cant_pasajeros_reserva+"</td><td>"+r.result[i].monto_pagado_reserva+"</td><td>"+(parseFloat(r.result[i].monto_total_reserva)-parseFloat(r.result[i].monto_pagado_reserva))+"</td><td>"+r.result[i].nombre_barco+"</td><td>"+r.result[i].usr_usuario+"</td><td>"+r.result[i].observaciones_reserva+"</td><td>&nbsp;&nbsp;&nbsp;<a href=\"#\" title=\'Editar Reserva\'><span class=\"glyphicon glyphicon-edit text-center\" aria-hidden=\"true\" onClick='reservaEdit("+i+")'></a></span></td></tr>";
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
	window.tcx.crtindex = -1;
	$('#dpk_modal_reserva').data("DateTimePicker").minDate(new Date());
	$('#dpk_modal_reserva').data("DateTimePicker").date(null);
	$("#selectHoraSalida").prop('selectedIndex',0);
	$("#selectTipoPaseo").prop('selectedIndex',0);
	$("#inpCantPax").val(0);
	$("#inpMontoPagado").val(0);
	$("#inpMontoTotal").val(0);
	$("#reservasAutocmpl").val("");
	$("#imptEmailCli").val('');
	$("#imptTelCli").val('');
	$("#imptDetalle").val('');
	$("#myModalReservasTitle").html("Crear Nueva Reserva")
	$('#myModalReservas').modal('show'); 
	$( "#reservasAutocmpl" ).autocomplete( "option", "appendTo", ".eventInsForm" );
	$("#checkDeleteReserva").addClass('hidden');
	$("#lblDeleteReserva").addClass('hidden');
	$('#reservas_btn_ok').removeClass('btn btn-danger');
	$('#reservas_btn_ok').addClass('btn btn-primary');
	$('#reservas_btn_ok').html('Guardar');
}

function reservaEdit(index){
	window.tcx.crtindex = index;
	var d = window.tcx.data[index];
	console.log('tcx data',d);
	$('#dpk_modal_reserva').data("DateTimePicker").minDate(moment("2000-01-01"));
	$('#dpk_modal_reserva').data("DateTimePicker").date(moment(d.fecha_reserva));
	$("#selectHoraSalida").val(d.horarios_id);
	$("#selectTipoPaseo").val(d.servicios_id);
	$("#inpCantPax").val(d.cant_pasajeros_reserva);
	$("#inpMontoPagado").val(d.monto_pagado_reserva);
	$("#inpMontoTotal").val(parseFloat(d.monto_total_reserva)-parseFloat(d.monto_pagado_reserva));
	$("#reservasAutocmpl").val(d.nombre_contacto_cliente);
	$("#imptEmailCli").val(d.email_cliente);
	$("#imptTelCli").val(d.telefono_contacto_cliente);
	$("#imptDetalle").val(d.observaciones_reserva);
	$("#myModalReservasTitle").html("Modificar Reserva")
	$('#myModalReservas').modal('show');
	$( "#reservasAutocmpl" ).autocomplete( "option", "appendTo", ".eventInsForm" ); 
	$("#checkDeleteReserva").removeClass('hidden'),
	$("#lblDeleteReserva").removeClass('hidden');
	$("#checkDeleteReserva").prop('checked',false),
	$('#reservas_btn_ok').removeClass('btn btn-danger');
	$('#reservas_btn_ok').addClass('btn btn-primary');
	$('#reservas_btn_ok').html('Guardar');
}

function checkCantPaxReservas(updateDpdn){
	if(updateDpdn){
		updateHorariosDropdown('#selectHoraSalida','#selectTipoPaseo'); 	
	}
	var result = $.grep(window.tcx.tarifas, function(e){return e.id == $("#selectTipoPaseo").val()});
	//console.log('ta',result)
	var tot = (parseInt($("#inpCantPax").val()) * result[0].tarifa)- parseFloat($("#inpMontoPagado").val())
	$("#inpMontoTotal").val(parseFloat(tot).toFixed(2));
	//console.log('result',result[0].tarifa)
	
}

function handlerDeleteReserva(){
	
	if($("#checkDeleteReserva").prop('checked')){
		$('#reservas_btn_ok').removeClass('btn btn-primary');
		$('#reservas_btn_ok').addClass('btn btn-danger');
		$('#reservas_btn_ok').html('Eliminar Reserva');
		return false;
	}
	$('#reservas_btn_ok').removeClass('btn btn-danger');
	$('#reservas_btn_ok').addClass('btn btn-primary');
	$('#reservas_btn_ok').html('Guardar');
	return false;
}

function saveReserva(){
	// validate del modal de reservas
	var notvalid = ($("#selectTipoPaseo").val() <= '0' || $("#selectHoraSalida").val() <= '0' || $("#inpCantPax").val() == '0' || $('#dpk_modal_reserva').find("input").val() == '' || $("#reservasAutocmpl").val().length < 3 || $("#imptEmailCli").val().indexOf("@") <= 0 );
	if(notvalid){
		($('#selectTipoPaseo').val() <='0')?$('#fgTipoPaseo').addClass("has-error"):$('#fgTipoPaseo').removeClass("has-error");
		($('#selectHoraSalida').val() <='0')?$('#fgHoraSalida').addClass("has-error"):$('#fgHoraSalida').removeClass("has-error");
		($('#dpk_modal_reserva').find("input").val() =='')?$('#dpk_modal_reserva').addClass("has-error"):$('#dpk_modal_reserva').removeClass("has-error");
		($("#inpCantPax").val() == '0')?$("#fgCantPax").addClass("has-error"):$("#fgCantPax").removeClass("has-error"); 
		($("#reservasAutocmpl").val().length < 3)?$("#fgCliente").addClass("has-error"):$("#fgCliente").removeClass("has-error");
		($("#imptEmailCli").val().indexOf("@") <= 0 )?$("#fgEmailCli").addClass("has-error"):$("#fgEmailCli").removeClass("has-error");
		$('#modalFooterMsg').removeClass("label label-success glyphicon glyphicon-ok hidden")
	 	$('#modalFooterMsg').addClass("label label-warning glyphicon glyphicon-remove hidden")
		$('#modalFooterMsgtxt').html('<big>Error: Falta completar datos </big>');
		$('#modalFooterMsg').removeClass('hidden')
		console.log('returning sin send')
		return false;
	}
	// clean last red item on modal when validated ok 
	var items = new Array($('#dpk_modal_reserva'),$("#fgCantPax"),$("#fgCliente"),$("#fgEmailCli"),$('#fgHoraSalida'),$('#fgTipoPaseo'));
	for (var i = items.length - 1; i >= 0; i--) {
		items[i].removeClass("has-error");
	}
	$('#modalFooterMsgtxt').html('');
	$('#modalFooterMsg').addClass('hidden');
	
	// ***** define new or edit before save *********** 
	if(window.tcx.crtindex === -1){
		data = {
		'fecha_reserva':$('#dpk_modal_reserva').find("input").val(),
		'horarios_id': $("#selectHoraSalida").val(),
		'servicios_id': $("#selectTipoPaseo").val(),
		'cant_pasajeros_reserva': $("#inpCantPax").val(),
		'monto_pagado_reserva': $("#inpMontoPagado").val(),
		'monto_total_reserva': $("#inpMontoTotal").val(),
		'nombre_contacto_cliente': $("#reservasAutocmpl").val(),
		'email_cliente':$("#imptEmailCli").val(),
		'telefono_contacto_cliente':$("#imptTelCli").val(),
		// 'servicio_bar_reserva':$('#servicio_bar').val(),
		'observaciones_reserva':$("#imptDetalle").val(),
		'usuarios_id':window.tcx.user.userId
		};
		var method = "create";
	}else{
		data = {
		'id':window.tcx.data[window.tcx.crtindex].id_reserva,
		'eliminar_reserva':$("#checkDeleteReserva").prop('checked'),
		'fecha_reserva':$('#dpk_modal_reserva').find("input").val(),
		'horarios_id': $("#selectHoraSalida").val(),
		'servicios_id': $("#selectTipoPaseo").val(),
		'cant_pasajeros_reserva': $("#inpCantPax").val(),
		'monto_pagado_reserva': $("#inpMontoPagado").val(),
		'monto_total_reserva': $("#inpMontoTotal").val(),
		'nombre_contacto_cliente': $("#reservasAutocmpl").val(),
		'email_cliente':$("#imptEmailCli").val(),
		'telefono_contacto_cliente':$("#imptTelCli").val(),
		// 'servicio_bar_reserva':$('#servicio_bar').val(),
		'observaciones_reserva':$("#imptDetalle").val(),
		'usuarios_id':window.tcx.user.userId
		};
		var method = "update";
	}
	console.log('tcx',window.tcx.user);
	console.log('envio to reserva.php',data);
		// show loading ....
	$.blockUI({ message: null, baseZ: 10000  }); 
	return $.ajax({
	 	type : "POST",
	 	url : "reservas/"+method,
	 	data : data,
	 	dataType : "json",
	 	success : function(r) {
	 		$.unblockUI(); 
	 		 console.log('recieved from reservas',r)
	 		
	 		if(r.result === 'false'){
	 			$('#totImporte').html('');
	 			$('#modalFooterMsg').removeClass("label label-success glyphicon glyphicon-ok hidden")
	 			$('#modalFooterMsg').addClass("label label-warning glyphicon glyphicon-remove hidden")
	 			$('#modalFooterMsgtxt').html('<big>Error de comunicación...</big>');					
	 			$('#modalFooterMsg').removeClass('hidden')
	 			setTimeout(function() {
	 				getReservas(r.fecha);
	 				$('#modalFooterMsg').addClass('hidden');
	 			},1500);
	 		}else{
					// respuesta ok de ajax
					$('#modalFooterMsg').removeClass("label label-warning glyphicon glyphicon-remove hidden")
					$('#modalFooterMsg').addClass("label label-success glyphicon glyphicon-ok hidden")
					$('#modalFooterMsgtxt').html('<big>Guardando...</big>');					
					$('#modalFooterMsg').removeClass('hidden')
					setTimeout(function() {
						$('#modalFooterMsg').addClass('hidden');
						$('#myModalReservas').modal('hide')},2000);
						getReservas(r.fecha);
				}
		},
		error : function(xhr, ajaxOptions, thrownError) {
				$.unblockUI();
				console.log('err:',thrownError)
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


// GENERALES ************

function myAlert(container,type,tit='',msg=''){
	var scrn = "<div class=\"alert alert-dismissible alert-"+type+"\">\
  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\
  <h4>"+tit+"</h4><p>"+msg+"</p></div>";
  $(container).html(scrn);
  setTimeout(function(){$(container).html('')},3000);

}

function updateHorariosDropdown(target_elem,caller){
	$(target_elem).val(0);
		// ajax que llama a update_dropdown en reservas.php
		$.blockUI({ message: null, baseZ: 10000  }); 
		var data = {'serv_id':$(caller).val()}
		
		return $.ajax({
	 	type : "POST",
	 	url : "reservas/update_drop_down",
	 	data : data,
	 	dataType : "json",
	 	success : function(r) {
	 		$.unblockUI(); 
	 		if(r.length > 0){
	 			// respuesta ok de ajax
				var elm = $(target_elem) 
				for (var i = 0; i < elm[0].length; i++) {
					elm[0][i].disabled = true;
					for (var x = 0; x < r.length; x++) {
						
						if(elm[0][i].value == r[x]){console.log('cc',elm[0][i].value);elm[0][i].disabled = false}
					}
				}
			}
		},
		error : function(xhr, ajaxOptions, thrownError) {
				$.unblockUI();
		}
	});	

}

// obj con  nombre:value de los elementos a validar, compara contra los defaults 
// seteados en window.tcx.validateDefVals 
function validateInputs(arr){
	var err = false; 
	for (let [key, value] of Object.entries(arr)) {  
  		$("#fg"+key).removeClass("has-error");
	  	if(value == window.tcx.validateDefVals[key]){
	  		$($("#fg"+key).addClass("has-error"));
	  		err = true
	  	}
	}
	if(err){
		$('#modalFooterMsg').removeClass("label label-success glyphicon glyphicon-ok hidden")
	 	$('#modalFooterMsg').addClass("label label-warning glyphicon glyphicon-remove hidden")
		$('#modalFooterMsgtxt').html('<big>Error: Falta completar datos </big>');
		$('#modalFooterMsg').removeClass('hidden')
		return true;
	}else{
		$('#modalFooterMsgtxt').html('');
		$('#modalFooterMsg').addClass('hidden');
		return false;	
	}	
}


//llama a controler/function en url pasa data y OkMsg el controller debe pasar callback jsfunction y params 
function callToServer(d){
	// show loading ....
	// console.log('call', d)
	$.blockUI({ message: null, baseZ: 10000  }); 
	return $.ajax({
	 	type : "POST",
	 	url : d.url,
	 	data : d,
	 	dataType : "json",
	 	success : function(r) {
	 		if(r.result == 'error'){
	 			$.unblockUI(); 
	 			//$('#totImporte').html('');
	 			$('#modalFooterMsg').removeClass("label label-success glyphicon glyphicon-ok hidden");
	 			$('#modalFooterMsg').addClass("label label-warning glyphicon glyphicon-remove hidden");
	 			$('#modalFooterMsgtxt').html('<big>Error de comunicación...</big>');					
	 			$('#modalFooterMsg').removeClass('hidden');
	 			setTimeout(function() {
	 				$('#modalFooterMsg').addClass('hidden');
	 			},2500);
	 		}else{
					// respuesta ok de ajax
					
					$('#modalFooterMsg').removeClass("label label-warning glyphicon glyphicon-remove hidden");
					$('#modalFooterMsg').addClass("label label-success glyphicon glyphicon-ok hidden");
					$('#modalFooterMsgtxt').html(d.OkMsg);					
					$('#modalFooterMsg').removeClass('hidden');
					setTimeout(function() {
						$('#modalFooterMsg').addClass('hidden');
						$.unblockUI(); 
						$('#myModal').modal('hide');
					},1000);
					window[r.callback](r.clbkparam);
				}
		},
		error : function(xhr, ajaxOptions, thrownError) {
				$.unblockUI();
				console.log('err:',xhr)
				$('#modalFooterMsg').removeClass("label label-success glyphicon glyphicon-ok hidden");
				$('#modalFooterMsg').addClass("label label-warning glyphicon glyphicon-remove hidden");
				$('#modalFooterMsgtxt').html('Error de comunicación...');					
				$('#modalFooterMsg').removeClass('hidden');
				setTimeout(function() {
					$('#modalFooterMsg').addClass('hidden');
				},2500);
		}
	});

}


function mk_list(data){
	switch(data.tpl){
		case 'compras-ventas':
			window.tcx.data = data.list_data;
			console.log('en list',window.tcx.data);
			var screen = '<table class=\"table table-responsive table-striped table-hover\"><thead><tr>';
						for (var i = 0; i < data.header.length; i++) {
							screen += "<th>"+data.header[i]+"</th>";
						}
						screen += "<th></th></tr></thead><tbody>";
						var totImporte = 0.00;
						for (var i = 0; i < data.list_data.length; i++) {
							var flt = data.filter.slice(data.filter.indexOf("_"));
							screen += "<tr><td>"+moment(data.list_data[i].fecha).format("DD/MM/YYYY")+"</td><td>"+data.list_data[i].nombre+"</td><td>"+data.list_data[i].nombre_usuario+" "+data.list_data[i].apellido_usuario+"</td><td>"+data.list_data[i].importe_neto+"</td><td>&nbsp;&nbsp;&nbsp;<a href=\"#\" title=\'Editar Registro\'><span class=\"glyphicon glyphicon-edit text-center\" aria-hidden=\"true\" onClick='updAdmCmpts("+i+",\""+flt+"\")'></a></span></td></tr>";
							totImporte += parseFloat(data.list_data[i].importe_neto);
						}
						screen += "<tr class='active bordered'><th class='text-right' colspan='3'>TOTALES</th><th>"+totImporte.toFixed(2)+"</th><th></th></tr>";
						screen +="</tbody></table>";
						$('#main_container_'+data.filter).html(screen);
			break;
		case 'tkts':
			var screen = '<table class=\"table table-responsive table-striped table-hover\"><thead><tr>';
			for (var i = 0; i < data.header.length; i++) {
				screen += "<th>"+data.header[i]+"</th>";
			}
			screen += "</tr></thead><tbody>";
			var totImporte = 0.00;
			var totTikets = 0;
			for (var i = 0; i < data.list_data.length; i++) {
				screen += "<tr><td>"+moment(data.list_data[i].fecha).format("DD/MM/YYYY")+"</td><td>"+data.list_data[i].tipo+" - "+data.list_data[i].subtipo+"</td><td>"+data.list_data[i].hora_salida+"</td><td>"+data.list_data[i].cantkts+"</td><td>"+data.list_data[i].total+"</td></tr>";
				totImporte += parseFloat(data.list_data[i].total);
				totTikets +=parseInt(data.list_data[i].cantkts);
			}
			screen += "<tr class='active bordered'><th class='text-center' colspan='3'>TOTALES</th><th>"+totTikets+"</th><th>"+totImporte.toFixed(2)+"</th></tr>";
			screen +="</tbody></table>";
			$('#main_container_'+data.filter).html(screen);
			break;
	}

	
}
