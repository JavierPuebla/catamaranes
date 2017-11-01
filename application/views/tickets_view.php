
<div class="bs-component">
	<div class="container-fluid" id='mainContainer'>
		<div class="list-group">
			<h5 href="#" class="list-group-item active">
				Servicios Disponibles
			</h5>
			<a href="#" class="list-group-item"><div class="col-md-6"><h4>10:00 - Paseo Regular ---- Tarifa $: 150 </h4></div><button class="btn btn-primary" style="margin-right:20px;" onclick=select_servicio(this)>1 Hora</button><button class="btn btn-primary" style="margin-right:20px;">2 Horas
			</button><button class="btn btn-primary" style="margin-right:20px;">Estudiantil
			</button></a>
			<a href="#" class="list-group-item"><div class="col-md-6"><h4>11:00 - Paseo XXXXXX ---- Tarifa $: 150 </h4></div><button class="btn btn-primary" style="margin-right:20px;" onclick=select_servicio(this)>1 Hora</button><button class="btn btn-primary"  style="margin-right:20px;">2 Horas
			</button><button class="btn btn-primary ">Estudiantil
			</button></a>
			<a href="#" class="list-group-item"><div class="col-md-6"><h4>12:00 - Paseo Regular ---- Tarifa $: 150 </h4></div><button class="btn btn-primary" style="margin-right:20px;" onclick=select_servicio(this)>1 Hora</button><button class="btn btn-primary" style="margin-right:20px;">2 Horas
			</button><button class="btn btn-primary">Estudiantil</button></a>
		</div>				
	</div>
</div>


<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<form>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Emitiendo Ticket Nro: 999999</h3>
				</div>
				<div class="modal-body">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4>Salida: 10:00 -- Paseo: Regular  1 Hora -- Tarifa $: 150 </h4>

						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group col-md-6" id="fgCantTks" >
			                  <label class="control-label" for="cantidadTks" id="lblCantTks">Cantidad selecionada</label>
			                  <input class="form-control" id="cantidadTks" onchange="checkCantidad(this)" type="number" value=1 min="1">
			                </div>							
							<div class="form-group col-md-6" >
								<label class="control-label" for="cantdiponible" >Cantidad Disponible</label>
			                  	<input class="form-control " id="cantdiponible" type="text" value=10 disabled >
							</div>
			                <div class="form-group">
			                	<label class="control-label" for="formaDePago">Forma de Pago</label>
			                	<select class="form-control" id="formaDePago" onchange="checkFormaDePago(this)">
			                        <option value="EFECTIVO">EFECTIVO</option>
			                        <option value="TARJETA">TARJETA</option>
			                    </select>
			                </div>
			                <div class="form-group hidden"  id="fgNroTransacTarjeta">
			                	<label class="control-label" for="nroTransacTarjeta">Numero Transacción</label>
			                  	<input class="form-control" id="nroTransacTarjeta" onchange="checkIdTransacTarjeta()"  type="text" placeholder=" ingresar Nro de operación de trajeta" >
			                </div>  

						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-md-6" >
						<big><strong><span id="modalFooterMsg" class="label label-success glyphicon glyphicon-ok hidden"><span id="modalFooterMsgtxt" > Imprimiendo Ticket...</span> </span></strong></big>	
					</div>
					<div class="col-md-6">
						<button type="button " class="btn btn-default " data-dismiss="modal">Cancelar</button>
						<button type="button" id="btnEmitirTks" onclick="emitirTks()" class="btn btn-primary">Emitir</button>	
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

</html>
