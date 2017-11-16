<script type="text/javascript"> 
$.blockUI({ message: null,
			baseZ: 10000  }); 
</script>
<div class="bs-component">
	<div class="container jp-centered col-md-12 col-sm-12 " id='mainContainer'>
		<!-- <div class="row  "> -->
			<table class="table table-bordered table-responsive table-striped table-hover">
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
						$totTikets += $itm['servicio']['cantkts'];
						$totImporte += $itm['servicio']['total'];
						$it .="<tr><td>{$itm['servicio']['fecha']}</td><td>{$itm['servicio']['tipo']}&nbsp;{$itm['servicio']['subtipo']}</td><td>{$itm['hora']}</td><td class='text-right'>{$itm['servicio']['cantkts']}</td><td class='text-right'>".number_format($itm['servicio']['total'],2)."</td></tr>";		
					}
					$it .="<tr class='active bordered'><th colspan='3'>TOTALES</th><th class='text-right'>{$totTikets}</th><th class='text-right'>".number_format($totImporte,2)."</th></tr>";
					echo $it;
				 ?>
			</tbody>
		</table> 		
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
						<button type="button" id="btnEmitirTks" onclick="emitirTks()" type="submit" class="btn btn-primary">OK</button>	
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	$( window ).load(function() {
		// Top context
		//window.tcx = {'selectedService':'','userId':<?php echo json_encode($user); ?>};

		console.log('loaded',<?php echo json_encode($data); ?>)
		 $.unblockUI(); 
	});
</script>
</html>
