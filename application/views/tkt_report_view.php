
<div class="bs-component">
	<div class="container" id='mainContainer'>
		<div class="row">
			<table class="table table-striped table-hover ">
			<thead>
				<tr>

				<?php foreach ($tikets['header'] as $tkth) {
					echo "<th>{$tkth}</th>"
				} ?>
				</tr>
			</thead>
			<tbody>
				<tr>
				<?php 
					$res = "";
					$tot = 0;
					foreach ($tkts as $tkt) {
						$res .= "<td>{$tkt['col']}</td>";
						$tot += $tkt['monto']
					}
					echo $res; 
				?>
				</tr>	
			</tbody>
			</table>
			<div class="panel panel-default">
			  <div class="panel-body">
			    Total: <?php $tot ?>
			  </div>
			</div>		
		</div>		
	</div>
</div>


<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<form>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title"></h3>
				</div>
				<div class="modal-body">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4></h4>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-body">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-md-6" >
						<big><strong><span id="modalFooterMsg" class="label label-success glyphicon glyphicon-ok hidden"><span id="modalFooterMsgtxt" ></span> </span></strong></big>	
					</div>
					<div class="col-md-6">
						<button type="button " class="btn btn-default " data-dismiss="modal">Cancelar</button>
						<button type="button" id="btnEmitirTks" onclick="emitirTks()" class="btn btn-primary">Emitir</button>	
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

</html>
