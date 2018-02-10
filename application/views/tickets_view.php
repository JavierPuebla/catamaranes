<script type="text/javascript"> 
$.blockUI({ message: null,
			baseZ: 10000  }); 
</script>
<div class="bs-component">
	<div class="row"><div class="col-md-12" id="mesages"></div></div>
	<div class="container" id='mainContainer'>
		<div class="list-group">
			<h5 href="#" class="list-group-item active">
				Servicios Disponibles  
			</h5>

			<?php 
				$p ='';
				// var_dump($data);
				//$id = $data[0]['id'];
				for ($i=0; $i < count($data); $i++) { 
					$p .="<a href='#' class='list-group-item'><div class='col-md-6'><h4>{$data[$i]['hora_salida']}Hs &nbsp;Paseo:&nbsp; {$data[$i]['tipo']}</h4></div>";
					$p .="<button class='btn btn-primary'style='margin-right:20px;' onclick=select_servicio('{$data[$i]['hora_salida']}','".str_replace(" ", "-", $data[$i]['tipo'])."','".str_replace(" ", "-", $data[$i]['subtipo'])."','".str_replace(" ", "-", $data[$i]['nombre_barco'])."','".$data[$i]['tarifa']."','".$data[$i]['id']."','".$data[$i]['fecha_servicio']."','".$data[$i]['servicios_id']."')>{$data[$i]['subtipo']}</button>";	
					while ($i+1 < count($data) && $data[$i+1]['id'] == $data[$i]['id']) {
						$i++;
						$p .="<button class='btn btn-primary'style='margin-right:20px;' onclick= select_servicio('{$data[$i]['hora_salida']}','".str_replace(" ", "-", $data[$i]['tipo'])."','".str_replace(" ", "-", $data[$i]['subtipo'])."','".str_replace(" ", "-", $data[$i]['nombre_barco'])."','".$data[$i]['tarifa']."','".$data[$i]['id']."','".$data[$i]['fecha_servicio']."','".$data[$i]['servicios_id']."')>{$data[$i]['subtipo']}</button>";	
						
					}
					$p .="</a>";
				}
				echo $p;
				// foreach ($data as $servicio) {
				// 	if()
					//echo $servicio['id'];
					// $p .="<a href='#' class='list-group-item'><div class='col-md-6'><h4>Salida:&nbsp;
					// {$servicio->hora_salida}Hs &nbsp;Paseo:&nbsp; {$servicio->tipo} </h4></div>";

					// foreach ($servicio as $hora){
					// 	
					// 	foreach ($hora as $h) {
							
					// 		$p .="<button class='btn btn-primary' style='margin-right:20px;' >{$h->subtipo}</button>";
					// 	}
						
						
					// }		
					 // 
				 // };
				 //var_dump($p) ;
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
							<div class="col-md-11">
								<h5 id="descripServicio"></h5>	
							</div>
							<div class="col-md-1">
								<div class="checkbox"><label><input type="checkbox" id="chk_selected"></label></div>
							</div>	
							
						</div>

					</div>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group col-md-6" id="fgCantTks" >
			                  <label class="control-label" for="cantidadTks" id="lblCantTks">Cantidad selecionada</label>
			                  <input class="form-control" id="cantidadTks" onchange="checkCantidad(this)" type="number" value=1 min="1">
			                </div>							
							<div class="form-group col-md-6" >
								<!-- <label class="control-label" for="cantdiponible" >Cantidad Disponible</label>
			                  	<input class="form-control " id="cantdiponible" type="text" value=10 disabled > -->
			                  	<div class="form-group">
				                	<label class="control-label" for="formaDePago">Forma de Pago</label>
				                	<select class="form-control" id="formaDePago" onchange="checkFormaDePago(this)">
				                        <option value="EFECTIVO">EFECTIVO</option>
				                        <option value="TARJETA">TARJETA</option>
				                    </select>
			                	</div>
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

<div class="modal fade" id="myModalAnularTicket">
	<div class="modal-dialog">
		<form>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title" id="tktNumero">Anular el último Ticket emitido? </h3>

				</div>
				<div class="modal-body">Esta operación no puede ser revertida.</div>
				<div class="modal-footer">
					<div class="col-md-6" >
						<h4 class="modal-title" id="totImporte"></h4>
						<big><strong><span id="modalFooterMsg"><span id="modalFooterMsgtxt" class="centered"></span> </span></strong></big>	
					</div>
					<div class="col-md-6">
						<button type="button " class="btn btn-default " data-dismiss="modal">Cancelar</button>
						<button type="button" id="btnEmitirTks" onclick="anularTicket(true)" type="submit" class="btn btn-primary">Confirmar</button>	
					</div>
				</div>
			</div>
		</form>
	</div>
</div>




<script type="text/javascript">
	$( window ).load(function() {
		// Top context
		window.tcx = {'selectedService':'','user':<?php echo json_encode($user); ?>,'lastInsertedTkts':null};
		console.log('init',window.tcx.user);
		if(window.tcx.user.tipo == 'boleteria'){
			$("#navbar").append("<li><a href='#' onclick='anularTicket(false)'>Anular Ticket</a></li>"); 
		}
		console.log('loaded',<?php echo json_encode($data); ?>)
		
		 $.unblockUI();
		// padding para fixed-top navbar ********
		$("body").attr({style: 'padding-top: 70px;'});  
	});
</script>
</html>
