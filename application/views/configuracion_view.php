<?php //var_dump($_ci_vars) ?>
<div class="bs-component">
	<div class="container" id='mainContainer'>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<div class='form-inline '>
						<div class="form-group">
							<label for="dpdn_tables"><h4><?php echo $title ?>&nbsp;</h4></label>
							<?php echo form_dropdown('',$items,'',$attr)?>
						</div>
						<button type="button" class="btn btn-success pull-right" onclick="call({'method':'add'})" >Agregar Item</button>
					</div>
				</h3>
			</div>
			<div class="panel-body" id="main_container"></div>
		</div>
		
	</div>
</div>


<!--MODAL WINDOW-->
<div class='modal fade ' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog modal-lg'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
				<h4 class='modal-title' id='myModalLabel'></h4>
			</div>
			<div class='modal-body'>
				<div class='container-fluid' class='col-md-12' id='modal_content' >
					
				</div>

			</div>
			<div class="modal-footer">
					
					<div class="col-md-6" >
						<h4 class="modal-title" id="modalFooterTitle"></h4>
						<big><strong><span id="modalFooterMsg"><span id="modalFooterMsgtxt" class="centered"></span> </span></strong></big>	
					</div>
					<div class="col-md-6">
						<button type="button " class="btn btn-default " data-dismiss="modal">Cancelar</button>
						<button type="button" id="btn_ok" onClick="wideSave()" type="submit" class="btn btn-primary">Guardar</button>	
					</div>
				</div>
		</div>
	</div>
</div>
<!--END  modal window-->

<!-- FRONTEND INIT  -->
<script type="text/javascript">
	$( window ).load(function() {
		// padding para fixed-top navbar ********
		$("body").attr({style: 'padding-top: 70px;'});

		// Top context
		window.tcx = {};
		window.tcx.route = '<?php echo $route ?>'		
		// console.log(<?php //echo json_encode(array('controller'=>$controller,'user'=>$user)); ?>)
		//Do(	<?php //echo json_encode(array('controller'=>$controller,'user'=>$user)); ?>)
	});
// <!-- FRONTEND INIT  -->	
</script>
</body>

</html>
