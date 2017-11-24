<script type="text/javascript"> 
	$.blockUI({ message: null,
		baseZ: 10000  }); 

</script>
<div class="bs-component">
	<div class="container" id='mainContainer'>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<div class='form-inline '>
						<div class="form-group">
							<label for="dpk_servicios"><h4>Listado De Servicios Del Día:</h4></label>
							<div class='input-group date' id='dpk_servicios'>
								<input type='text' class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
							<script type="text/javascript">$(function () { $('#dpk_servicios').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true }); });</script>
						</div>
						<button type="button" class="btn btn-primary" onclick="getServicios()" >Buscar</button>
						<button type="button" class="btn btn-success text-right" onclick="setNewServicios()" >Crear Nuevo</button>
					</div>
				</h3>
			</div>
			<div class="panel-body" id="main_container"></div>
		</div>
	</div>

	<div class="modal fade" id="myModalOper">
	<div class="modal-dialog">
		<form>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title" id="myModalOperTitle"></h3>
				</div>
				<div class="modal-body" id="myModalOperBody">
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



		// Top context
		window.tcx = {};
		// console.log('loaded',<?php echo json_encode($data); ?>)
		$.unblockUI(); 
	});
</script>
</html>
