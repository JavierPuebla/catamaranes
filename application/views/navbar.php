<script type="text/javascript"> console.log(<?php echo json_encode($acts); ?>)</script>
	<div class="navbar navbar-inverse" id="up_bar" >
		<div class="container-fluid">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
				<ul class="nav navbar-nav">
					<li><a href="#"><span class="sr-only">(current)</span></a></li>
					<?php 
					foreach ($acts as $v) {
						$acc = $this->app_model->get_acciones($v);
						echo "<li><a href='{$acc['clase']}'>{$acc['nombre']}</a></li>";
					};
 					?>
					<!-- 
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li class="divider"></li>
							<li><a href="#">Separated link</a></li>
							<li class="divider"></li>
							<li><a href="#">One more separated link</a></li>
						</ul>
					</li> -->
				</ul>
				<!-- <form class="navbar-form navbar-left" role="search">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Search">
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				</form>
				 -->
				 <ul class="nav navbar-nav navbar-right">
					<li><a href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <span class="glyphicon-class"><?php echo $username ?></span></a> </li>
				</ul>
			</div>
		</div>
	</div><!-- ***** END NAV BAR **** -->	