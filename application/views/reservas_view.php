<script type="text/javascript"> 
	// $.blockUI({ message: null,
	// 	baseZ: 10000  }); 


</script>
<div class="bs-component">
	<div class="container autoscroll">
		<!-- UP BAR -->
		<div class="row">
			<div class="col-md-6">
				<button type="button" class="btn btn-success" onclick="setNewReserva()" >Nueva Reserva</button>
			</div>
		
			<div class="col-md-6 text-right">
				<div class='form-inline '>
					<div class="form-group">
						<label for="dpk_reservas"></label>
						<div class='input-group date' id='dpk_reservas'>
							<input type='text' class="form-control" placeholder="Selecciona una fecha" />
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
						<script type="text/javascript">$(function () { $('#dpk_reservas').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
					</div>
					<button type="button" class="btn btn-primary" onclick="getReservas({'fecha':$('#dpk_reservas').find('input').val(),'scope':'day'})" >Buscar</button>

					<button type="button" class="btn btn-primary" onclick="printDiv('printable')" ><span class="glyphicon glyphicon-print"></span></button>

				</div>
			</div>
		</div>
		<!-- TABLE CONTAINER -->
		<div class="row">
			<div class="panel panel-default" >
				<div class="panel-heading">
					<h3 class="panel-title" id="screenTitle"></h3>
				</div>
				<div class="panel-body" id="main_container"></div>
			</div>
		</div>
	</div>
	<!-- MODAL WINDOW -->
	<div class="modal fade" id="myModal">
	<div class="modal-dialog modal-lg">
		<form class="eventInsForm">
			<div class="modal-content">
				<!-- TITULOS  -->
				<div class="modal-header">
					<a  class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
					<h3 class="modal-title" id="myModalReservasTitle"></h3>
				</div>
				<!-- MAIN  -->
				<div class="modal-body" id="myModalReservasBody">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group col-md-4" id="fgdpk_modal_reserva">
								<label for="dpk_modal_reserva">Fecha:</label>
								<div class='input-group date' id='dpk_modal_reserva' >
									<input type='text' class="form-control" placeholder="Selecciona una fecha" />
									<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<script type="text/javascript">$(function () { 
									$('#dpk_modal_reserva').datetimepicker({ minDate:new Date(),locale: 'es',allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
							</div>	
							<div class="form-group col-md-4" id="fgselectTipoPaseo">
			                    <label class="control-label" for="selectTipoPaseo" id="lblTipoPaseo">Tipo de Paseo:</label>
			                  	<?php 
			                    	$attr = "class='form-control' id='selectTipoPaseo' onchange=checkCantPaxReservas(true)";
			                    	echo form_dropdown('tipo_serv',$tpserv,'',$attr) ;
			                    	?>
			                </div>
			                <div class="form-group col-md-4" id="fgselectHoraSalida" >
			                  <label class="control-label" for="selectHoraSalida" id="lblHoraSalida">Hora Salida:</label>
			                  <?php 
			                    	$attr = "class='form-control' id='selectHoraSalida' ";
			                    	echo form_dropdown('serv_hora',$dpdown_hora,'',$attr) ;
			                    	?>
			                </div>	
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group col-md-4" id="fginpCantPax" >
			                  <label class="control-label" for="inpCantPax" id="lblCantPax">Cantidad pasajeros</label>
			                  <input class="form-control" id="inpCantPax" onchange="checkCantPaxReservas(false)" type="number" placeholder="ingresar cantidad pax" min="0">
			                </div>
			                <div class="form-group col-md-4">
			                  <label class="control-label" for="inpMontoPagado" id="lblMontoPagado">Pagado </label>
			                  <input class="form-control" id="inpMontoPagado" type="number" placeholder="ingresar monto en pesos" min="0" onchange="checkCantPaxReservas(false)">
			                </div>
			                <div class="form-group col-md-4" >
			                  <label class="control-label" for="inpMontoTotal" id="lblMontoTotal">Saldo</label>
			                  <input class="form-control" id="inpMontoTotal" type="number" disabled value=0 min="0">
			                </div>
							<div class="form-group col-md-4" id="fgreservasAutocmpl" >
								<label class="control-label" for="reservasAutocmpl">Nombre del Contratante</label>
								<input class="form-control" type="text" name="reservasAutocmpl" id="reservasAutocmpl"/>
								<input type="hidden" id="id" name="id">
							</div>
							<div class="form-group col-md-4" id="fgimptEmailCli">
								<label class="control-label" for="imptEmailCli">E mail:</label>
								<input class="form-control" type="text" id=imptEmailCli />
							</div>
							<div class="form-group col-md-4">
								<label class="control-label" for="imptTelCli">Teléfono:</label>
								<input class="form-control" type="text" id=imptTelCli />	
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group col-md-8">
								<label class="control-label" for="imptDetalle">Detalle del servicio</label>
								<textarea class="form-control" type="textarea" maxlength="150" rows='4' id='imptDetalle' ></textarea>	
							</div>
							 <div class="form-group col-md-4" id="fg_servicios_abordo_reserva" >
			                  <label class="control-label" for="slc_servicios_abordo_reserva" id="lbl_servicios_abordo_reserva">Servicios:</label>
			                  <?php 
			                    	$attr = "class='form-control' id='slc_servicios_abordo_reserva' onChange=handler_servicios_abordo_reserva()";
			                    	echo form_dropdown('slc_servicios_abordo_reserva',$servicios_abordo_reserva,'',$attr) ;
			                    	?>
			                </div>
						</div>
						<div class="form-group col-md-4"><br/>
							<div class="custom-control custom-checkbox">
	      						<input type="checkbox" class="custom-control-input" id="checkDeleteReserva" onchange="handlerDeleteReserva()">
						      	<label class="custom-control-label" id='lblDeleteReserva' for="checkDeleteReserva">Eliminar esta reserva</label>
						    </div>
						</div>
					</div>
				</div>
				<!-- FOOTER  -->
				<div class="modal-footer">
					<div class="col-md-6" >
						<h4 class="modal-title" id="modalFooterTitle"></h4>
						<big><strong><span id="modalFooterMsg"><span id="modalFooterMsgtxt" class="centered"></span> </span></strong></big>	
					</div>
					<div class="col-md-6">
						<button type="button " class="btn btn-default " data-dismiss="modal">Cancelar</button>
						<button type="button" id="reservas_btn_ok" onclick="saveReserva()" class="btn btn-primary">Guardar</button>	
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="panel panel-default" style="visibility:hidden" id="printable" >
	<div class="panel-heading">
		<h3 class="panel-title" id="printTitle"></h3>
	</div>
	<div class="panel-body" id="printable_content"></div>
</div>


<script type="text/javascript">

// RESERVAS FUNCTIONS  ************

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

function getReservas(o){
	// console.log('f',o.fecha);
	window.tcx.fecha = o.fecha
	var d ={
		method:'meet',
		msg:{'fecha':o.fecha,'scope':o.scope},
		// url:'reservas/list_reservas'
	}
	call(d);
}


function setNewReserva(){
	window.tcx.action = 'create';
	window.tcx.validateDefVals = {
		'dpk_modal_reserva':'',
		'selectHoraSalida':0,
		'selectTipoPaseo':0,
		'inpCantPax':0,
		'reservasAutocmpl':"",
		'imptEmailCli':""
		};
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
	$("#slc_servicios_abordo_reserva").prop('selectedIndex',0);
	$("#myModalReservasTitle").html("Crear Nueva Reserva")
	$('#myModal').modal('show'); 
	$( "#reservasAutocmpl" ).autocomplete( "option", "appendTo", ".eventInsForm" );
	$("#checkDeleteReserva").addClass('hidden');
	$("#lblDeleteReserva").addClass('hidden');
	$('#reservas_btn_ok').removeClass('btn btn-danger');
	$('#reservas_btn_ok').addClass('btn btn-primary');
	$('#reservas_btn_ok').html('Guardar');
}

function reservasUpd(index){
	window.tcx.action = 'update'
	window.tcx.crtindex = index;
	var d = window.tcx.data[index];
	window.tcx.validateDefVals = {
		'dpk_modal_reserva':'',
		'selectHoraSalida':0,
		'selectTipoPaseo':0,
		'inpCantPax':0,
		'reservasAutocmpl':"",
		'imptEmailCli':""
		};
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
	// $("#slc_servicios_abordo_reserva").val(d.servicios_abordo_reserva);
	$("#myModalReservasTitle").html("Modificar Reserva")
	$('#myModal').modal('show');
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

function handler_servicios_abordo_reserva(){
	var t = $('#slc_servicios_abordo_reserva').find('option:selected').html();
	if(t.indexOf('Selecciona el servicio') == -1){
		$("#imptDetalle").val($("#imptDetalle").val()+t.trim()+", ");	
	}
}

function saveReserva(){
	data = {
		'id':(window.tcx.action == 'update')?window.tcx.data[window.tcx.crtindex].id:null,
		'fecha_reserva':$('#dpk_modal_reserva').find("input").val(),
		'horarios_id': $("#selectHoraSalida").val(),
		'servicios_id': $("#selectTipoPaseo").val(),
		'cant_pasajeros_reserva': $("#inpCantPax").val(),
		'monto_pagado_reserva': $("#inpMontoPagado").val(),
		'monto_total_reserva': $("#inpMontoTotal").val(),
		'nombre_contacto_cliente': $("#reservasAutocmpl").val(),
		'email_cliente':$("#imptEmailCli").val(),
		'telefono_contacto_cliente':$("#imptTelCli").val(),
		// 'servicios_abordo_reserva':$('#slc_servicios_abordo_reserva').val(),
		'observaciones_reserva':$("#imptDetalle").val(),
		'usuarios_id':window.tcx.user.userId
		};
	var d={
			data:data,
			url:"reservas/"+window.tcx.action,
			eliminar_reserva:$("#checkDeleteReserva").prop('checked'),
			OkMsg: "Guardando..."
		}
	let errs = validateInputs(getCurrentInputsValues());
	if(!errs){callToServer(d)};
}
		


$( window ).load(function() {
	// padding para fixed-top navbar ********
	$("body").attr({style: 'padding-top: 70px;'});
	// my namespace
	window.tcx = {};
	//**************************
		
	// **************  autocomplete de clientes 
	$('#reservasAutocmpl').autocomplete({
	    source:  "reservas/autocomplete_clientes",
		minLength: 2,
		response: function( event, ui) {
		},
		select: function(event, ui)
   		{
      		$("#imptEmailCli").val(ui.item.email);
      		$("#imptTelCli").val(ui.item.tel);
      		$("#id").val(ui.item.id);
    	}
	});
	// **********************************
	// ************ init data from server
	getTarifas();	
	window.tcx.fecha = <?php echo json_encode($fecha); ?>;
	window.tcx.user = <?php echo json_encode($user); ?>;
	window.tcx.route = '<?php echo $route ?>';	
	var p = {fecha:window.tcx.fecha,scope:'all'}
	getReservas(p);
});
</script>
</html>
