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
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
			                  <select class="form-control" id="selectHoraSalida">
			                        <option value="10:00">10:00</option>
			                        <option value="10:30">10:30</option>
			                        <option value="11:00">11:00</option>
			                    	<option value="11:30">11:30</option>
			                    	<option value="12:00">12:00</option>
			                    	<option value="12:30">12:30</option>
			                    	<option value="13:00">13:00</option>
			                    	<option value="13:30">13:30</option>
			                    	<option value="14:00">14:00</option>
			                    	<option value="14:30">14:30</option>
			                    	<option value="15:00">15:00</option>
			                    	<option value="15:30">15:30</option>
			                    	<option value="16:00">16:00</option>
			                    	<option value="16:30">16:30</option>
			                    </select>
			                </div>
			                <div class="form-group col-md-4">
			                    <label class="control-label" for="selectTipoPaseo" id="lblTipoPaseo">Tipo de Paseo:</label>
			                  	<select class="form-control" id="selectTipoPaseo">
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
			                  <input class="form-control" id="inpCantPax" onchange="checkCantPaxReservas(this)" type="number" value=0 min="0">
			                </div>
			                <div class="form-group col-md-4">
			                  <label class="control-label" for="inpMontoPagado" id="lblMontoPagado">Seña recibida</label>
			                  <input class="form-control" id="inpMontoPagado" type="number" value=0 min="0">
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
						<button type="button " class="btn btn-primary " data-dismiss="modal">Ok</button>
						<!-- <button type="button" id="btn-ok" type="submit" class="btn btn-primary">OK</button>	 -->
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
		$( window ).load(function() {
		getTarifas();	
		$('#reservasAutocmpl').autocomplete({
		    source:  "reservas/autocomplete_clientes",
			minLength: 2,
			response: function( event, ui ) {console.log(ui)},
			select: function(event, ui)
    		{
	      		
	     	 //$("#id_nomenclador").val(ui.item.id);
	      	//$("#nomenclador_descripcion").html(ui.item.descripcion);
	    	}
		});
		// Top context
		window.tcx = {};
		console.log('loaded',<?php echo json_encode($fecha); ?>)
		var fecha = <?php echo json_encode($fecha); ?>;
		getReservas(fecha,'true');
	});
</script>
</html>
