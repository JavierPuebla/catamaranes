<div class="container-fluid" role="main">

	<div class="well" id="up_bar" >
		<div class="row">
			<input type="hidden" id="mselect" name="mselect" class="form-control">
			<div class="col-xs-5 col-md-2" id='campaign_selector'></div>
			<div class="col-xs-5 col-md-2" id='agent_selector'></div>
		</div>
	</div><!-- end container gris "well  -->
	<div class="row" id='mainContainer'>
		<div class="col-xs-12 col-sm-6">
		<!--	<div class="row"> -->
			<!-- HORIZONTAL LOADER
				<img id='hloader' class="center-block" style='margin-top:20px;display:none' src="images/horiz_load2.gif">
			-->
			<!-- activities -->
						<div id="content_pnl_1" ></div>
						<div id="content_pnl_3" ></div>
		</div>
		<div class="col-xs-12 col-sm-6">
		<!--	<div class="row"> -->

				<div id="content_pnl_2" ></div>
				<div id="content_pnl_4" ></div>


		</div>
				<div id='footer' class="col-xs-12 col-sm-6"></div>
	</div>
</div>
<!--
<div><button id='test2' onClick='back_from_cart()' type='button' class='btn btn btn-primary navbar-btn' role="button"  data-container="body" <span id='test' class='glyphicon glyphicon-ok' aria-hidden='true'> test</span></button></div>
-->

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

<!--END  modal window-->

<script type="text/javascript" src="<?php base_url() ?>js/supervisor.js"></script>
<script type="text/javascript">
			  $( window ).load(function() {
							Do(	<?php echo json_encode(array('controller'=>$controller,'user'=>$user)); ?>)
				});
</script>
</body>

</html>
