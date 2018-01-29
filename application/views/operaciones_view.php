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
						
						<!-- create_dia_servicios_regulares -->
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
					<h4 class="modal-title" id="myModalOperTitle"></h4>
					<h4 class="modal-title" id="myModalTrpTitle"></h4>

				</div>
				<div class="modal-body" id="myModalOperBody">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group col-md-3" id="fgHoraSalida" >
			                  <label class="control-label" for="selectServicioHoraSalida" id="lblHoraSalida">Hora Salida:</label>
			                 	<?php 
			                    	$attr = "class='form-control' id='selectServicioHoraSalida' ";
			                    	echo form_dropdown('serv_hora',$dpdown_hora,'',$attr) ;
			                    	?>
			                </div>
			                <div class="form-group col-md-5">
			                    <label class="control-label" for="selectTipoPaseo" id="lblTipoPaseo">Tipo de Paseo:</label>
			                  		
			                    <?php 
			                    	$attr = "class='form-control' id='selectTipoPaseo' ";
			                    	echo form_dropdown('tipo_serv',$tiposerv_dpdown_data,'',$attr) ;
			                    ?>
			                </div>
			                <div class="form-group col-md-4">
			                	<label class="control-label" for="selectServicioEstado" id="lblTipoPaseo">Estado:</label>
			                	<select class="form-control" id="selectServicioEstado">
			                        <option value="D">Disponible</option>
			                        <option value="S">Suspendido</option>
			                    </select>
			                </div>	
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group col-md-4" >
								<label class="control-label" for="selectBarco" >Barco:</label>
				                  	<?php 
			                    	$attr = "class='form-control' id='selectBarco' ";
			                    	echo form_dropdown('dpdown_barco',$dpdown_barco,'',$attr) ;
			                    	?>
				            </div>
				            <div class="form-group col-md-8" >
								<label class="control-label" for="selectTrpl" >Tripulación:</label>
				                  	<?php 
			                    	$attr = "class='form-control' id='selectTrpl' ";
			                    	echo form_dropdown('mltSelect_trpl',$trpl,$trpl_keys,$attr) ;
			                    	?>
				            </div>
						</div>
					</div>
				</div>
				<div class="modal-body" id="myModalTrpBody"></div>
				<div class="modal-footer">
					<div class="col-md-6" >
						<h4 class="modal-title" id="modalFooterTitle"></h4>
						<big><strong><span id="modalFooterMsg"><span id="modalFooterMsgtxt" class="centered"></span> </span></strong></big>	
					</div>
					<div class="col-md-6">
						<button type="button " class="btn btn-default " data-dismiss="modal">Cancelar</button>
						<button type="button" id="btn-ok" onClick="guardaServ()" type="submit" class="btn btn-primary">Guardar</button>	
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
		window.tcx = {};
		// console.log('loaded',<?php //echo json_encode($data); ?>)

		$("#dpk_servicios").data("DateTimePicker").date(new Date());
		getServicios();

		
		
	});
</script>
</html>
