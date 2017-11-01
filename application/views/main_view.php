
<div class="bs-component">

	
	<div class="container-fluid" id='mainContainer'>
		
	</div>
</div>


<!--MODAL win-->
<div class='modal fade ' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog modal-md'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
				<h4 class='modal-title' id='myModalLabel'></h4>
			</div>
			<div class='modal-body'>
				<div class='container-fluid' class='col-md-12'>
					<div id='modal_content' ></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--END  modal window-->
<script type="text/javascript">
	$( window ).load(function() {
		console.log(<?php echo json_encode(array('controller'=>$controller,'user'=>$user)); ?>)
		//Do(	<?php echo json_encode(array('controller'=>$controller,'user'=>$user)); ?>)
	});
</script>
</body>

</html>
