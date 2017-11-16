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
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
	                <script type="text/javascript">
	            	$(function () { $('#dpk_servicios').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY', showClear: true, showClose: true }); });
	           		</script>
            </div>
            <button type="button" class="btn btn-primary" onclick="getServicios()" >Buscar</button>
            
        </div>
        <div class="form-inline"></div>
    </h3>
  </div>
  <div class="panel-body">
    <table class="table table-striped table-hover">
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
			<tbody id="tbl_servicios">
				
			</tbody>
		</table>
  </div>
</div>
<button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="ssssss" data-original-title="Tooltip on top">Top</button>

<button type="button" class="btn btn-secondary" title="ddddddd" data-container="body" data-toggle="popover" data-placement="top" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." data-original-title="Popover Title">Top</button>
	    
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
