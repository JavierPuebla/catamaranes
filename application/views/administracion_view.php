<script type="text/javascript"> 
$.blockUI({ message: null,
			baseZ: 10000  }); 
</script>
<div class="bs-component">
	<div class="container" id='mainContainer'>
		<ul class="nav nav-tabs">
		  <li id="init"><a href="#ventas" data-toggle="tab">Ventas</a></li>
		  <li ><a href="#compras" data-toggle="tab">Compras</a></li>
		  <!-- <li ><a href="#historico" data-toggle="tab">General</a></li> -->
  
		</ul>
		<div id="myTabContent" class="tab-content">
			<div class="tab-pane fade active in" id="ventas">
		   		<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<div class='form-inline '>
								<div class="form-group">
									<label for="dpk_tkts_desde_all_ventas"><h4>Listar desde:</h4></label>
									<div class='input-group date' id='dpk_desde_all_vta'>
										<input type='text' class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<script type="text/javascript">$(function () { $('#dpk_desde_all_vta').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
								</div>
								<div class="form-group">
									<label for="dpk_hasta_all_vta"><h4>&nbsp;Hasta:</h4></label>
									<div class='input-group date' id='dpk_hasta_all_vta'>
										<input type='text' class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<script type="text/javascript">$(function () { $('#dpk_hasta_all_vta').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
								</div>
								<button type="button" class="btn btn-primary" onclick="getAdmCmpts('all_vta')" >Buscar</button>
								
									<button type="button" class="btn btn-success" onclick="NewAdmCmpts('_vta')" >Nuevo Ingreso</button>
							</div>
						</h3>
					</div>
					<div class="panel-body fixed-scrolable" id="main_container_all_vta"></div>
				</div>
		  	</div>
			

			<div class="tab-pane fade" id="compras">
		    	<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<div class='form-inline '>
								<div class="form-group">
									<label for="dpk_desde_all_cpra"><h4>Listar desde:</h4></label>
									<div class='input-group date' id='dpk_desde_all_cpra'>
										<input type='text' class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<script type="text/javascript">$(function () { $('#dpk_desde_all_cpra').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
								</div>
								<div class="form-group">
									<label for="dpk_hasta_all_cpra"><h4>&nbsp;Hasta:</h4></label>
									<div class='input-group date' id='dpk_hasta_all_cpra'>
										<input type='text' class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<script type="text/javascript">$(function () { $('#dpk_hasta_all_cpra').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
								</div>
								<button type="button" class="btn btn-primary" onclick="getAdmCmpts('all_cpra')" >Buscar</button>
								
									<button type="button" class="btn btn-success" onclick="NewAdmCmpts('_cpra')" >Nuevo Egreso</button>
							</div>
						</h3>
					</div>
					<div class="panel-body fixed-scrolable" id="main_container_all_cpra"></div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="myModal">
	<div class="modal-dialog modal-lg">
		<form>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title" id="modalTitle"></h3>
				</div>
				<div class="modal-body">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group col-md-4" id="fgdpkModalAdm">
								<label for="dpkModalAdm">Fecha:</label>
								<div class='input-group date' id='dpkModalAdm' >
									<input type='text' class="form-control" placeholder="Selecciona una fecha" />
									<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<script type="text/javascript">$(function () { 
									$('#dpkModalAdm').datetimepicker({locale: 'es',allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
							</div>	
							<div class="form-group col-md-4" id="fgselectUsuario">
			                    <label class="control-label" for="selectUsuario" id="lblUsuario">Usuario:</label>
			                  	<?php 
			                    	$attr = "class='form-control' id='selectUsuario'";
			                    	echo form_dropdown('dpdownUsuario',$dpdownUsuario,'',$attr) ;
			                    	?>
			                </div>
			                <div class="form-group col-md-4" id="fgselectTipoComprob" >
			                  <label class="control-label" for="selectTipoComprob" id="lblTipoComprob">Tipo de Comprobante:</label>
			                  <?php 
			                    	$attr = "class='form-control' id='selectTipoComprob' sele";
			                    	echo form_dropdown('dpdownTipoComprob',$dpdownTipoComprob,'',$attr) ;
			                    	?>
			                </div>	
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group col-md-4" id="fgselectFormaPago" >
								<label class="control-label" for="selectFormaPago" id="lblFormaPago">Forma de pago:</label>
			                  	<?php 
			                    	$attr = "class='form-control' id='selectFormaPago'";
			                    	echo form_dropdown('dpdownFPago',$dpdownFPago,'Efectivo',$attr) ;
			                    	?>
							</div>	
							<div class="form-group col-md-4" id="fginpMonto">
			                  <label class="control-label" for="inpMonto" id="lblMonto">Monto </label>
			                  <input class="form-control" id="inpMonto" type="number" placeholder="ingresa monto en pesos" min="0">
			                </div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-md-6" >
						<h4 class="modal-title" id="titFooterAdm"></h4>
						<big><strong><span id="modalFooterMsg"><span id="modalFooterMsgtxt" class="centered"></span> </span></strong></big>	
					</div>
					<div class="col-md-6">
						<button type="button " class="btn btn-default " data-dismiss="modal">Cancelar</button>
						<button type="button" id="btnAdmSave" onclick="saveAdm()" type="submit" class="btn btn-primary">Guardar</button>	
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	$( window ).load(function() {
		// Top context
		 window.tcx = {'userId':<?php echo json_encode($user); ?>};

		//console.log('loaded',<?php //echo json_encode($data); ?>)
		$('#init').addClass('active');
		$
		$.unblockUI();
		var date = new Date(), y = date.getFullYear(), m = date.getMonth();


		$("#dpk_desde_all_cpra").data("DateTimePicker").date(new Date(y, m, 1));
		$("#dpk_hasta_all_cpra").data("DateTimePicker").date(new Date(y, m + 1, 0));
		

		$("#dpk_desde_all_vta").data("DateTimePicker").date(new Date(y, m, 1));
		$("#dpk_hasta_all_vta").data("DateTimePicker").date(new Date(y, m + 1, 0));
		getAdmCmpts('all_vta');

		
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        	var target = $(e.target).attr("href") // activated tab
            switch (target) {
                case "#ventas":
                	getAdmCmpts('all_vta');
                break;
                case "#compras":
                	getAdmCmpts('all_cpra');
                break;  
            }
        });

		// padding para fixed-top navbar ********
		$("body").attr({style: 'padding-top: 70px;'});  
	});
</script>
</html>
