<div class="bs-component" style="max-width: 350px">
	<div class="row"><div class="col-md-12" id="mesages"></div></div>
	<div class="container" id='mainContainer'>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h5>Compra online de tickets</h5>
			</div>
			<div class="panel-body">
				<div class="well well-sm">
					<div class="form-inline">
						<div class="form-group id="fgCantTks" >
			        		<label class="control-label" for="cantTkts" id="lblCantTks"><big>Cantidad de pasajes: &nbsp;&nbsp;&nbsp;&nbsp;</big> </label>
			        		<input class="form-control" id="cantTkts" type="number" value=1 min="1" max="6" style="min-width:90px;">
			        	</div>	
					</div>
				</div>
				<div class="well well-sm">
  					<div class="form-inline">
						<big><a href="paseos.php#1hora" title="mas información sobre el paseo de una hora"><span class="glyphicon glyphicon-zoom-in"></span> - </a>Paseo 1 Hora  </big>&nbsp;&nbsp;&nbsp;
						<button type="button " class="btn btn-primary" onclick="tktCantConfirm('_1h')">Comprar</button>
			        	
					</div>
				</div>
				<div class="well well-sm">
  					<div class="form-inline">
						<big><a href="paseos.php#2horas" title="mas información sobre el paseo de dos horas"><span class="glyphicon glyphicon-zoom-in"></span> - </a>Paseo 2 Horas  </big>
							&nbsp;
			        		<button type="button " class="btn btn-primary" onclick="tktCantConfirm('_2h')">Comprar</button>
			       </div>
				
				</div>
			</div>
		</div>				
	</div>
</div>
<script type="text/javascript">
function tktCantConfirm(c){
	var curr_selection  = $("#cantTkts").val() +c;
console.log('curr_selection',curr_selection)
	// switch (curr_selection){
	// 	case "1_1h":
	// 		window.location.replace("https://www.mercadopago.com/mla/checkout/start?pref_id=272234392-de1c76d2-4d41-4189-97b5-1a1f687e4948");
	// 	break;
	// 	case "2_1h":
	// 	window.location.replace("https://www.mercadopago.com/mla/checkout/start?pref_id=272234392-9aaa147a-afb1-4872-bbb3-4d831240fcf9");
	// 	break;
	// 	case "3_1h":
	// 	window.location.replace("https://www.mercadopago.com/mla/checkout/start?pref_id=272234392-42e94e61-00c2-4373-bdd3-17eefabc9a37");
	// 	break;

	// }






}
	
</script>

</html>
