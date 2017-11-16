<script type="text/javascript"> 
$.blockUI({ message: null,
			baseZ: 10000  }); 
</script>
<div class="bs-component">
	<div class="container-fluid" id='mainContainer'>
		<div class="list-group">
			<h5 href="#" class="list-group-item active">
				Servicios Disponibles  
			</h5>
			<?php 
				
				$p ='';
				foreach ($data as $s) {
					$p .="<a href='#' class='list-group-item'><div class='col-md-6'><h4>Salida:&nbsp;
					{$s[0]['hora_salida']}Hs &nbsp;Paseo:&nbsp; {$s[0]['tipo']} </h4></div>";
					foreach ($s as $st) {
						$p .="<button class='btn btn-primary' style='margin-right:20px;' onclick=select_servicio('{$st['hora_salida']}','".str_replace(" ", "-", $st['tipo'])."','".str_replace(" ", "-", $st['subtipo'])."','".str_replace(" ", "-", $st['nombre'])."','".$st['servicios_id']."','".$st['tarifa']."','".$st['id']."','".$st['fecha_servicio']."')>{$st['subtipo']}</button>";
					}
					$p .="</a>";	
				};
				echo $p;
 			?>

		</div>				
	</div>
</div>


<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<form>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title" id="tktNumero">Ticket De Servicio</h3>
				</div>
				<div class="modal-body">
					<div class="panel panel-default">
						<div class="panel-body">
							<h5 id="descripServicio"></h5>
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
			                  	<input class="form-control" id="nroTransacTarjeta" onchange="checkIdTransacTarjeta()" onblur="checkIdTransacTarjeta()"  type="text" placeholder=" ingresar Nro de operación de trajeta" >
			                </div>  

						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-md-6" >
						<h4 class="modal-title" id="totImporte"></h4>
						<big><strong><span id="modalFooterMsg"><span id="modalFooterMsgtxt" class="centered"></span> </span></strong></big>	
					</div>
					<div class="col-md-6">
						<button type="button " class="btn btn-default " data-dismiss="modal">Cancelar</button>
						<button type="button" id="btnEmitirTks" onclick="emitirTks()" type="submit" class="btn btn-primary">Emitir</button>	
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	$( window ).load(function() {
		// Top context
		window.tcx = {'selectedService':'','userId':<?php echo json_encode($user); ?>};

		console.log('loaded',<?php echo json_encode($horarios); ?>)
		console.log('loaded',<?php echo json_encode($tipos); ?>)
		 $.unblockUI(); 
	});
</script>
</html>
