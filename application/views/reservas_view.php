<script type="text/javascript"> 
	$.blockUI({ message: null,
		baseZ: 10000  }); 

</script>
<div class="bs-component">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<button type="button" class="btn btn-success" onclick="setNewReserva()" >Nueva Reserva</button>
			</div>
		
			<div class="col-md-6 text-right">
					<div class='form-inline '>
						<div class="form-group">
							<label for="dpk_reservas"></label>
							<div class='input-group date' id='dpk_reservas'>
								<input type='text' class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
							<script type="text/javascript">$(function () { $('#dpk_reservas').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
						</div>
						<button type="button" class="btn btn-primary" onclick="getReservas($('#dpk_reservas').find('input').val())" >Buscar</button>
						
					</div>
				</div>
		<hr>
		</div>

		<!-- <div class="row"> -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Listado De Reservas </h3>
				</div>
				<div class="panel-body" id="main_container"></div>
			</div>
		<!-- </div> -->
		
	</div>

	<div class="modal fade" id="myModalReservas">
	<div class="modal-dialog modal-lg">
		<form>
			<div class="modal-content">
				<div class="modal-header">
					<a  class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
					<h3 class="modal-title" id="myModalReservasTitle">Nueva Reserva</h3>
				</div>
				<div class="modal-body" id="myModalReservasBody">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group col-md-4">
								<label for="dpk_new_reserva">Fecha:</label>
								<div class='input-group date' id='dpk_new_reserva'>
									<input type='text' class="form-control" />
									<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<script type="text/javascript">$(function () { 
									$('#dpk_new_reserva').datetimepicker({ minDate:new Date(),locale: 'es',allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
							</div>	
							<div class="form-group col-md-4" id="fgHoraSalida" >
			                  <label class="control-label" for="selectHoraSalida" id="lblHoraSalida">Hora Salida:</label>
			                  <?php 
			                    	$attr = "class='form-control' id='selectHoraSalida' ";
			                    	echo form_dropdown('serv_hora',$dpdown_hora,'',$attr) ;
			                    	?>
			                </div>
			                <div class="form-group col-md-4">
			                    <label class="control-label" for="selectTipoPaseo" id="lblTipoPaseo">Tipo de Paseo:</label>
			                  	<select class="form-control" id="selectTipoPaseo" onchange="checkCantPaxReservas()">
			                        <option value="1">1 Hora  - Regular</option>
			                        <option value="2">2 Horas - Regular</option>
			                        <option value="3">Estudiantil</option>
			                    	<option value="7">1 Hora  - Privado</option>
			                    	<option value="8">2 Horas - Privado</option>
			                    	<option value="9">1 Hora  - A Medida</option>
			                    	<option value="10">2 Horas - A Medida</option>
			                    </select>
			                </div>	
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group col-md-4" id="fgCantPax" >
			                  <label class="control-label" for="inpCantPax" id="lblCantPax">Cantidad pasajeros</label>
			                  <input class="form-control" id="inpCantPax" onchange="checkCantPaxReservas()" type="number" value=0 min="0">
			                </div>
			                <div class="form-group col-md-4">
			                  <label class="control-label" for="inpMontoPagado" id="lblMontoPagado">Seña recibida</label>
			                  <input class="form-control" id="inpMontoPagado" type="number" value=0 min="0" onchange="checkCantPaxReservas()">
			                </div>
			                <div class="form-group col-md-4" >
			                  <label class="control-label" for="inpMontoTotal" id="lblMontoTotal">Saldo</label>
			                  <input class="form-control" id="inpMontoTotal" type="number" disabled value=0 min="0">
			                </div>
							<div class="form-group col-md-4" id="fgCliente" >
								<label class="control-label" for="imptNomCli">Nombre del Contratante</label>
								<input class="form-control" type="text" name="reservasAutocmpl" id="reservasAutocmpl"/>
							</div>
							<div class="form-group col-md-4" >
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
							<div class="form-group col-md-12">
								<label class="control-label" for="imptDetalle">Detalle del servicio</label>
								<textarea class="form-control" type="textarea" maxlength="255" rows='4' id=imptDetalle ></textarea>	
							</div>
						</div>
					</div>



				</div>
				<div class="modal-footer">
					<div class="col-md-6" >
						<h4 class="modal-title" id="modalFooterTitle"></h4>
						<big><strong><span id="modalFooterMsg"><span id="modalFooterMsgtxt" class="centered"></span> </span></strong></big>	
					</div>
					<div class="col-md-6">
						<!-- <button type="button " class="btn btn-primary " data-dismiss="modal">Ok</button> -->
						<button type="button" id="btn-ok" onclick="saveReserva" class="btn btn-primary">Guardar</button>	
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
		$( window ).load(function() {
		// my namespace
		window.tcx = {};
		//**************************
		
		// **************  autocomplete de clientes 
		$('#reservasAutocmpl').autocomplete({
		    source:  "reservas/autocomplete_clientes",
			minLength: 2,
			response: function( event, ui ) {console.log('xx',ui)},
			select: function(event, ui)
    		{
	      		console.log('atcp ',ui);	
	     		$("#imptEmailCli").val(ui.item.email_cliente);
	      		$("#imptTelCli").val(ui.item.telefono_contacto_cliente);
	    	}
		});
	


		// ***************************
		console.log('loaded',<?php echo json_encode($fecha); ?>)
		
		// ************ init data from server
		getTarifas();	
		var fecha = <?php echo json_encode($fecha); ?>;
		getReservas(fecha,'true');
	});
</script>
</html>
