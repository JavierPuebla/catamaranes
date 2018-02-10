<script type="text/javascript"> 
$.blockUI({ message: null,
			baseZ: 10000  }); 
</script>
<div class="bs-component">
	<div class="container" id='mainContainer'>
		<ul class="nav nav-tabs">
		  <!-- <li ><a href="#hoy" data-toggle="tab">Tickets Hoy</a></li> -->
		  <li id="init"><a href="#venta_online" data-toggle="tab">Venta Online</a></li>
		  <li ><a href="#historico" data-toggle="tab">General</a></li>
  
		</ul>
		<!-- <div id="myTabContent" class="tab-content">
			<div class="tab-pane fade active in" id="hoy">
		 -->   	
			   	<!-- <table class="table table-bordered table-responsive table-striped table-hover">
						<thead>
							<tr>
								<?php 
									$r = '';
									foreach ($header as $h) {
										$r .= "<th class='text-center'>{$h}</th>";
									}
									echo $r;
								 ?>
							</tr>
						</thead>
						<tbody>
							
							<?php 
								$totTikets = 0;
								$totImporte = 0;
								$it='';
								foreach ($data as $itm) {
									$totTikets += $itm['cantkts'];
									$totImporte += $itm['total'];
									$f = new DateTime($itm['fecha']);
									$fecha = $f ->format("d/m/Y");
									$it .="<tr><td>{$fecha}</td><td>{$itm['tipo']}</td><td>{$itm['hora_salida']}</td><td class='text-right'>{$itm['cantkts']}</td><td class='text-right'>".number_format($itm['total'],2)."</td></tr>";		
								}
								$it .="<tr class='active bordered'><th colspan='3'>TOTALES</th><th class='text-right'>{$totTikets}</th><th class='text-right'>".number_format($totImporte,2)."</th></tr>";
								echo $it;
							 ?>
						</tbody>
				</table> --> 


		  	<!-- </div> -->
			<div class="tab-pane fade active in" id="venta_online">
		    	<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<div class='form-inline '>
								<div class="form-group">
									<label for="dpk_tkts_desde_vta_oln"><h4>Listado Venta Online desde:</h4></label>
									<div class='input-group date' id='dpk_tkts_desde_vta_oln'>
										<input type='text' class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<script type="text/javascript">$(function () { $('#dpk_tkts_desde_vta_oln').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
								</div>
								<div class="form-group">
									<label for="dpk_tkts_hasta_vta_oln"><h4>&nbsp;Hasta:</h4></label>
									<div class='input-group date' id='dpk_tkts_hasta_vta_oln'>
										<input type='text' class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<script type="text/javascript">$(function () { $('#dpk_tkts_hasta_vta_oln').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
								</div>
								<button type="button" class="btn btn-primary" onclick="getTkts('vta_oln')" >Buscar</button>
							</div>
						</h3>
					</div>
					<div class="panel-body fixed-scrolable" id="main_container_vta_oln" ></div>
				</div>
			</div>
		  	<div class="tab-pane fade" id="historico">
		    	<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<div class='form-inline '>
								<div class="form-group">
									<label for="dpk_tkts_desde_all"><h4>Listado Tickets desde:</h4></label>
									<div class='input-group date' id='dpk_tkts_desde_all'>
										<input type='text' class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<script type="text/javascript">$(function () { $('#dpk_tkts_desde_all').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
								</div>
								<div class="form-group">
									<label for="dpk_tkts_hasta_all"><h4>&nbsp;Hasta:</h4></label>
									<div class='input-group date' id='dpk_tkts_hasta_all'>
										<input type='text' class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<script type="text/javascript">$(function () { $('#dpk_tkts_hasta_all').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
								</div>
								<button type="button" class="btn btn-primary" onclick="getTkts('all')" >Buscar</button>
							</div>
						</h3>
					</div>
					<div class="panel-body fixed-scrolable" id="main_container_all"></div>
				</div>
		  	</div>
		</div>

		<!-- <div class="row  "> -->
					
		<!-- </div> -->
	</div>
</div>


<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<form>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title" id="tit">tit</h3>
				</div>
				<div class="modal-body">
					<div class="panel panel-default">
						<div class="panel-body">
							<h5 id="descripServicio"></h5>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-body">
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
						<button type="button" id="btnEmitirTks" onclick="" type="submit" class="btn btn-primary">OK</button>	
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	$( window ).load(function() {
		// padding para fixed-top navbar ********
		$("body").attr({style: 'padding-top: 70px;'});  
		// Top context
		window.tcx = {'userId':<?php echo json_encode($user); ?>};

		//console.log('loaded',<?php //echo json_encode($data); ?>)
		 $('#init').addClass('active');
		 $.unblockUI();

		

		var date = new Date(), y = date.getFullYear(), m = date.getMonth(), d = date.getDate();
		$("#dpk_tkts_desde_vta_oln").data("DateTimePicker").date(new Date(y, m-1, d));
		$("#dpk_tkts_hasta_vta_oln").data("DateTimePicker").date(new Date());
		getTkts('vta_oln')
	});
</script>
</html>
