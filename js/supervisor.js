/*************************
* SUPERVISOR  SETUP
**************************/


function setup_supervisor(o){
//$d = ['action'=>'setup_'.$userType,'layout'=>$layout,'user'=>$user,'allCamps'=>$campaigns,'agents'=> $agents,'agActs'=>$agnts_acts,'agCmpn'=>$agnts_cmpns];
		TOP.user = o.user;
		TOP.clientsList = new knldg(o.clients)
		TOP.allCamps = new knldg(o.allCamps);
		TOP.agents = new knldg(o.agents);
		TOP.agActs = new knldg(o.agActs);
		TOP.agCmpn = new knldg(o.agCmpn);
		TOP.pedidos = new knldg(o.pedidos)
		TOP.prods = new knldg(o.prodsList)
		TOP.agIdActs = new knldg(o.agIdActs)
		TOP.currentLayout = new knldg(o.layout);
		TOP.selectedCampId=0;
		console.log('raw',TOP);
		// SELECTOR DE CAMPAÑAS
		var o = {
		'id':'campaignSelector',
		'settings':{'selectionContainer':'mselect','action':'show_cmpn_report'},
		'values':TOP.allCamps.stk.map(function(itm){return itm.camp_id}),
		'labels':TOP.allCamps.stk.map(function(itm){return itm.name+" "}),
		'placeholder':'Selecciona una Campaña ',
		'parent': 'campaign_selector'
		}
		mk_select(o);
		var ag = {
		'id':'agentSelector',
		'settings':{'selectionContainer':'mselect','action':'show_agent_report'},
		'values':TOP.agents.stk.map(function(itm){return itm.usuarios_id}),
		'labels':TOP.agents.stk.map(function(itm){return itm.nombre+" "+itm.apellido+" "}),
		'placeholder':'Selecciona un Agente ',
		'parent': 'agent_selector'
		}
		mk_select(ag);
}

function show_agent_report(agId){
/*
	TOP.agCmpn.stk.map(function(astk,i){
		if(astk[0].agent_id == agId){
			TOP.selectedAgCmp=astk.map(function(agCp){return agCp})
		}
	})

//console.log(TOP.selectedAgCmp);
	TOP.selectedAgCmp.map(function(i){
			var contactos_por_agente =TOP.agActs.stk.map(function(sItem,agindx){return {agent:TOP.agents.stk[agindx].usuarios_id,contactos:sItem.filter(function(e){return e.campaign_id == i.camp_id})}});
			console.log(contactos_por_agente);
			contactos_por_agente.map(function(ctc){
				var tcc = TOP.agActs.stk.map(function(sItem){return sItem.filter(function(e){return e.campaign_id == campId})});

			})

	})
	*/
	if(agId != TOP.selectedAgId){
		//refresh campaing selector
		var o = {
		'id':'campaignSelector',
		'settings':{'selectionContainer':'mselect','action':'show_cmpn_report'},
		'values':TOP.allCamps.stk.map(function(itm){return itm.camp_id}),
		'labels':TOP.allCamps.stk.map(function(itm){return itm.name+" "}),
		'placeholder':'Selecciona una Campaña ',
		'parent': 'campaign_selector'
		}
		$('#campaign_selector').empty()
		mk_select(o);

		//////
		TOP.selectedCampId='';
		TOP.selectedAgId=agId;
		clean('report');

		show(TOP.currentLayout.stk[0])
		show(TOP.currentLayout.stk[1])
		show(TOP.currentLayout.stk[2])

		var testResults=['Venta Registrada','Visitado sin venta','Visitado con Promocion','No encontrado'];
		var agVr=TOP.agIdActs.stk[agId].filter(function(e){return e.result == testResults[0]}).length
		var agTotact= TOP.agIdActs.stk[agId].length
		var agPedidos = TOP.pedidos.stk.filter(function(e){return e.owner_id == agId})
		//console.log(agPedidos.reduce(function(ant,act){return ant+parseFloat(act.tot)},0));
		var agr= {
		parent:'pnlBody_res1_panel',
		items:[
			{
				label:'Total de Contactos: ',
				value:TOP.agIdActs.stk[agId].length
			},
			{
				label:'Total Contactos Con Venta: ',
				value:TOP.agIdActs.stk[agId].filter(function(e){return e.result == testResults[0]}).length,
				color:'#69CC76'
			},
			{
				label:'Total Contactos Sin Venta: ',
				value:TOP.agIdActs.stk[agId].filter(function(e){return e.result == testResults[1]}).length,
				color:'#EF6655'
			},
			{
				label:'Total Contactos Con Promocion: ',
				value:TOP.agIdActs.stk[agId].filter(function(e){return e.result == testResults[2]}).length,
				color:'#DEB947'
			},
			{
				label:'Total Contactos No Encontrado: ',
				value:TOP.agIdActs.stk[agId].filter(function(e){return e.result == testResults[3]}).length,
				color:'#5759BF'
			},
		]
		};
		agr.items.push(
		{
			label:'Contactos Efectivos: ',
			value:agr.items[1].value+agr.items[2].value+agr.items[3].value
		},
		{
			label:'Efectividad: ',
			value: (agr.items[1].value*100/agr.items[0].value).toFixed(2)+' %'
		}
		)
		mk_piechart(agr)
		crag=TOP.agents.stk[TOP.agents.search('usuarios_id',agId)];
		var agPD = {
			parent:'pnlBody_res2_panel',
			items:[
				{
					label:'Agente Ventas: ',
					value:crag.nombre+' '+crag.apellido
				},
				{
					label:'Fecha de Alta: ',
					value:crag.fechaalta.slice(0,crag.fechaalta.indexOf(' '))
				},
				{
					label:'Ventas $: ',
					value:numberWithDots(agPedidos.reduce(function(ant,act){return ant+parseFloat(act.tot)},0).toFixed(2))
				},
				{
					label:'Promedio Venta $: ',
					value: numberWithDots((agPedidos.reduce(function(ant,act){return ant+parseFloat(act.tot)},0)/agPedidos.length).toFixed(2))
				},
				{
					label:'Promedio Ventas / Contactos: ',
					value: numberWithDots((agVr/agTotact).toFixed(2))
				},


			]
		}
		mk_listgroup(agPD)
function gtotventas(){

}
//var ttt = TOP.agents.stk.map(function(ags){return (TOP.agIdActs.stk[ags.usuarios_id].filter(function(e){return e.result == testResults[0]}).length)})
//console.log(ttt);
		// TEAM COMPARISON
		//var comp=TOP.agents.stk.map(function(ag){TOP.agIdActs.stk[ag.usuarios_id].filter(function(e){return e.result == testResults[0]}).length})
		var teamCompare= {
			parent:'pnlBody_res3_panel',
			items:TOP.agents.stk.map(function(ag){return {label:ag.nombre+" "+ag.apellido,value:TOP.agIdActs.stk[ag.usuarios_id].filter(function(e){return e.result == testResults[0]}).length,color:getRandomColor()}})
		}
		$('#tit_res3_panel').html('Team Comparison - Contactos con Venta')
		mk_piechart(teamCompare);

				//muestra la tabla contactos del agente seleccionado
		show(TOP.currentLayout.stk[3])
				//console.log(TOP.currentLayout.stk[3]);

		show(TOP.currentLayout.stk[4])
	}




}

function show_cmpn_report(campId){
	if(TOP.selectedCampId !=campId){
		clean('report');
		// refresh agent selector
		var ag = {
		'id':'agentSelector',
		'settings':{'selectionContainer':'mselect','action':'show_agent_report'},
		'values':TOP.agents.stk.map(function(itm){return itm.usuarios_id}),
		'labels':TOP.agents.stk.map(function(itm){return itm.nombre+" "+itm.apellido+" "}),
		'placeholder':'Selecciona un Agente ',
		'parent': 'agent_selector'
		}
		$('#agent_selector').empty()
		mk_select(ag);
		//*****************

		show(TOP.currentLayout.stk[0])
		show(TOP.currentLayout.stk[1])
		show(TOP.currentLayout.stk[2])
		show(TOP.currentLayout.stk[3])

		//TOP.currentLayout.stk.map(function(i){show(i)});
		// RESUMEN DE VISITAS
		var contactos_por_agente =TOP.agActs.stk.map(function(sItem,agindx){return {agent:TOP.agents.stk[agindx].usuario,contactos:sItem.filter(function(e){return e.campaign_id == campId})}});
		var totales_contactos_camp = TOP.agActs.stk.map(function(sItem){return sItem.filter(function(e){return e.campaign_id == campId})});
		var testResults=['Venta Registrada','Visitado sin venta','Visitado con Promocion','No encontrado'];
		var lc= {
			parent:'pnlBody_res1_panel',
			items:[
				{
					label:'Total de Contactos: ',
					value:totales_contactos_camp.reduce(function(ant,act,i){return ant+act.length},0)
				},
				{
					label:'Total Contactos Con Venta: ',
					value:totales_contactos_camp.map(function(ctcv){return ctcv.filter(function(e){return e.result == testResults[0]})}).reduce(function(ant,act,i){return ant+act.length},0),
					color:'#69CC76'
				},
				{
					label:'Total Contactos Sin Venta: ',
					value:totales_contactos_camp.map(function(ctcv){return ctcv.filter(function(e){return e.result == testResults[1]})}).reduce(function(ant,act,i){return ant+act.length},0),
					color:'#EF6655'
				},
				{
					label:'Total Contactos Con Promocion: ',
					value:totales_contactos_camp.map(function(ctcv){return ctcv.filter(function(e){return e.result == testResults[2]})}).reduce(function(ant,act,i){return ant+act.length},0),
					color:'#DEB947'
				},
				{
					label:'Total Contactos No Encontrado: ',
					value:totales_contactos_camp.map(function(ctcv){return ctcv.filter(function(e){return e.result == testResults[3]})}).reduce(function(ant,act,i){return ant+act.length},0),
					color:'#5759BF'
				},
			]
		};
		lc.items.push(
			{
				label:'Contactos Efectivos: ',
				value:lc.items[1].value+lc.items[2].value+lc.items[3].value
			},
			{
				label:'Efectividad: ',
				value: (lc.items[1].value*100/lc.items[0].value).toFixed(2)+' %'
				//value:((lc.items[0].value-lc.items[2].value-lc.items[3].value-lc.items[4].value)/lc.items[0].value*100).toFixed(2)+ '%'
			}
		)
		mk_piechart(lc)
		// RESUMEN DE VENTAS DE PRODUCTOS
		//.reduce(function(ant,act,i){return ant+act.length},0),

		var units=[]
		var montos=[]
		var prods_ids=[]
		TOP.clients=new knldg([])
		TOP.prods_venta=new knldg([])
		var ventasDeCamp =totales_contactos_camp.map(function(ctcv){return ctcv.filter(function(e){return e.result == testResults[0]})})
		var t = ventasDeCamp.map(function(vc){
		var x = vc.map(function(v){
					r = TOP.pedidos.stk.filter(function(e){return e.order_number==v.order_id})
					r.map(function(f){
						units.push(f.qtty)
						montos.push(f.tot)
						TOP.prods_venta.stk.push({_id:f.prodId,monto:f.tot,qtty:f.qtty})
						TOP.clients.stk.push({_id:f.client_id,monto:f.tot,qtty:f.qtty})
					})
			})
		})
		var resumenVentas= {
			parent:'pnlBody_res2_panel',
			items:[
				{
					label:'Unidades Vendidas: ',
					value:numberWithDots(units.reduce(function(ant,act,i){return ant+parseInt(act)},0))
				},
				{
					label:'Ventas $: ',
					value:numberWithDots((montos.reduce(function(ant,act,i){return ant+parseInt(act)},0)).toFixed(2))
					//color:'#69CC76'
				},
				{
					label:'Promedio Venta Unidades por Contacto: ',
					value:numberWithDots((units.reduce(function(ant,act,i){return ant+parseInt(act)},0)/totales_contactos_camp.length).toFixed(2))
					//color:'#EF6655'
				},
				{
					label:'Promedio Venta $ por Contacto: ',
					value:numberWithDots((montos.reduce(function(ant,act,i){return ant+parseInt(act)},0)/totales_contactos_camp.length).toFixed(2))
					//color:'#DEB947'
				},

			]
		};
		mk_listgroup(resumenVentas)


//		console.log('vxc',(group(TOP.clients).totMonto.reduce(function(ant,act,i){return ant+parseInt(act)},0)).toFixed(2));
//		console.log('vxp',vtaXProd);

//console.log(((-/ group(TOP.clients).totMonto[i].reduce(function(ant,act,i){return parseFloat(act.monto)+ant},0)));
var xtot = group(TOP.clients).totMonto.reduce(function(ant,act,i){return parseFloat(act)+ant},0);
var xrem = parseInt(group(TOP.clients).totMonto[0]);
//console.log(xtot);
//console.log(xrem);

		vtaXClient= {
			parent:'pnlBody_res3_panel',
//(group(TOP.clients).totMonto.reduce(function(ant,act,i){return parseFloat(act)+ant},0)-group(TOP.clients).totMonto[i]/group(TOP.clients).totMonto.reduce(function(ant,act,i){return parseFloat(act)+ant},0)
			items:group(TOP.clients).did.map(function(c,i){
				return {label:TOP.clientsList.stk[TOP.clientsList.search('client_id',c)].RAZON_SOCIAL +" :  ",
					value:group(TOP.clients).totMonto[i],
					color:colortemp((group(TOP.clients).totMonto[i]*100)/group(TOP.clients).totMonto.reduce(function(ant,act,i){return parseFloat(act)+ant},0))
			}})
		};
	//console.log('vtxc',vtaXClient);
		mk_piechart(vtaXClient)

		vtaXProd={
			parent:'pnlBody_res4_panel',
			items:group(TOP.prods_venta).did.map(function(c,i){return {label:TOP.prods.stk[TOP.prods.search('product_id',c)].descript +" : $  ",value:group(TOP.prods_venta).totMonto[i],color:getRandomColor()}})
		}
		mk_listgroup(vtaXProd)



	}
	TOP.selectedCampId=campId;

}

function percent(tot,part){

}

function group(r){
	rid = [],ttl=[],res={did:[],totMonto:0,totUnits:0}
	r.stk.reduce(function(ant,act){if(ant != act._id){if(rid.indexOf(act._id)=== -1){rid.push(act._id)}}return ant = act._id},0)
	rid.map(function(r_id){res.did.push(r_id);ttl.push(r.stk.filter(function(e){return e._id==r_id}))});
	res.totMonto = ttl.map(function(x){tt=x.reduce(function(ant,act,i){return parseFloat(act.monto)+ant},0);
	return tt})//.reduce(function(ant,act,i){return ant+parseFloat(act)},0)

	res.totUnits = ttl.map(function(x){tt=x.reduce(function(ant,act,i){return parseFloat(act.qtty)+ant},0);
	return tt})//.reduce(function(ant,act,i){return ant+parseFloat(act)},0)
//console.log('tot',res.totMonto.reduce(function(ant,act,i){return ant+parseFloat(act)},0));
//console.log('monto',res.totMonto);
//console.log('percent',(res.totMonto.reduce(function(ant,act){return ant+parseFloat(act)},0)-res.totMonto[0])/res.totMonto.reduce(function(ant,act){return ant+parseFloat(act)},0)*10);
	return res

	//console.log('ttl',ttl);
	//console.log('monto',totMontoByCli);
	//console.log('units',totUnitsByCli);
	//console.log(TOP.clients);
}
