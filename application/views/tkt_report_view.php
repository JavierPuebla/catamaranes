
<div class="bs-component">
	<div class="container-fluid" id='mainContainer'>
		<table class="table table-striped table-hover ">
			<thead>
				<tr>

				<?php foreach ($tikets['header'] as $tkth) {
					echo "<th>{$tkth}</th>"
				} ?>
				</tr>
			</thead>
			<tbody>
				<tr>
				<?php 
					$res = "";
					$tot = 0;
					foreach ($tkts as $tkt) {
						$res .= "<td>{$tkt['col']}</td>";
						$tot += $tkt['monto']
					}
					echo $res; 
				?>
				</tr>	
			</tbody>
		</table>
		<div class="panel panel-default">
		  <div class="panel-body">
		    Total: <?php $tot ?>
		  </div>
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
