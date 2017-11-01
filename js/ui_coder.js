

/* DROPDOWN TEMPLATE
<div class="btn-group">
    <button type="button" class="form-control btn btn-default dropdown-toggle" data-toggle="dropdown">
        Select Business type <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li><a href="#">small</a></li>
        <li><a href="#">medium</a></li>
        <li><a href="#">large</a></li>
    </ul>
</div>
*/
function mk_piechart(o){
//$('#content_res1_panel').append("<div id='rprtInfo' class='col-xs-6 col-md-6'></div><div id='chart' class='col-xs-6 col-md-3'></div>")
	var infopnl = document.createElement('div')
	//infopnl.className='col-xs-12 col-md-7'
	var chart = document.createElement('div')
	//chart.className='col-xs-3'
	chart.id = 'chart_'+o.parent
	var cnt = document.createElement('div')
	var lg = document.createElement('ul')
	cnt.id = 'cnt'
	cnt.className = 'col-xs-12 col-sm-12 col-md-6'
	lg.className = 'list-group'
	o.items.map(function(i){
		var itm = document.createElement('li')
		itm.className = 'list-group-item'
		if(i.hasOwnProperty('color')){
			itm.setAttribute('style','background-color:'+i.color)
		}
		var lbl = document.createTextNode(i.label)
		var boldval = document.createElement('strong')
		var myval=document.createTextNode(i.value)
		boldval.appendChild(myval)
		itm.appendChild(lbl)
		itm.appendChild(boldval)
		lg.appendChild(itm)
	})
	cnt.appendChild(lg)
	infopnl.appendChild(cnt)
	infopnl.appendChild(chart)
	document.getElementById(o.parent).appendChild(infopnl);
	var data = []
	o.items.map(function(i){
		if(i.hasOwnProperty('color')){
			data.push({value:i.value,color:i.color})
		}
	})
//console.log(data);
//data.map(function(d,i){console.log('data'+i,d.value)});
	var svg = d3.select("#chart_"+o.parent).append("svg").attr("width",300).attr("height",300);
	svg.append("g").attr("id","salesDonut"+o.parent);
	Donut3D.draw("salesDonut"+o.parent, data, 150, 150, 130, 100, 30, 0.35);



}




//o = {parent:'',items:[{label:'',value:''}]}
function mk_listgroup(o){
	var cnt = document.createElement('div')
	var lg = document.createElement('ul')
	cnt.id = 'cnt'
	cnt.className = 'col-xs-6 col-md-4'
	lg.className = 'list-group'
	o.items.map(function(i){
		var itm = document.createElement('li')
		itm.className = 'list-group-item'
		var lbl = document.createTextNode(i.label)
		var boldval = document.createElement('strong')
		var myval=document.createTextNode(i.value)
		boldval.appendChild(myval)
		itm.appendChild(lbl)
		itm.appendChild(boldval)
		lg.appendChild(itm)
	})
	document.getElementById(o.parent).appendChild(lg);

}


// params  o = {'id':'ig_1','setings':{'setting_label':0,'setting2':0},'values':['v1','v2','v3'],'labels':['lbl1','lbl2','lbl3'],'placeholder':'Select your country','parent':'the container'}
function mk_select(o){

	var btng = document.createElement('div')
	var btn = document.createElement('button')
	var ulist = document.createElement('ul');
	var caret = document.createElement('span');


	btng.className='btn-group'
	btn.className='btn btn-primary dropdown-toggle btn-block'
	ulist.className='dropdown-menu'
	caret.className = 'caret';


	btng.setAttribute('style','width: 100%;')
	ulist.setAttribute('style','width: 100%;')
	ulist.setAttribute('role','menu')
	ulist.setAttribute('style','height:auto;max-height:250px;overflow-x:hidden;')
	btn.setAttribute('type','button')
	btn.setAttribute('data-toggle','dropdown')
	btn.setAttribute('style','text-align:center;')
	btn.setAttribute('id',o.id)
	o.values.map(function(i,idx){
		 	var litem  = document.createElement('li');
		 	var liselect = document.createElement('a');
			liselect.setAttribute('onCLick',"$('#"+o.id+"').html('"+o.labels[idx]+"').append('<span class=\"caret\"></span>');$('#"+o.settings.selectionContainer+"').val('"+i+"');"+o.settings.action+"('"+i+"')");
		 	var contnt = document.createTextNode(o.labels[idx]);
		 	liselect.appendChild(contnt);
			litem.appendChild(liselect);
			ulist.appendChild(litem);
		 });

	var placeholder = document.createTextNode(o.placeholder)
	btn.appendChild(placeholder)
	btn.appendChild(caret)
	btng.appendChild(ulist)
	btng.appendChild(btn)
	document.getElementById(o.parent).appendChild(btng);
}
//params o = {'id':'ig_1','setings':{'setting1':0,'setting2':0},'values':['v1','v2','v3'],'labels':['lbl1','lbl2','lbl3'],'placeholder':'Select your country','parent':'cont'}
// HAY QUE REVISAR ESTA FUNC

//o=(btnlbl:'bot label',values:['v1','v2','v3'],labels:['lbl1','lbl2','lbl3'],placehlder:'select an item',parent:'containerdiv')
function mk_inputgroup(o){
	// CREATE ELEMENTS ********
		var group = document.createElement('div');
		var groupButton = document.createElement('div');
		var dbutton = document.createElement('button');
		var myul = document.createElement('ul');
		var caret = document.createElement('span');
		var btnlbl = document.createTextNode(o.btnlbl);
		var grpInputField = document.createElement('input');

	// CLASS NAMES ********
		group.className = 'input-group';
		groupButton.className = 'input-group-btn';
		dbutton.className = 'btn btn-default dropdown-toggle';
		caret.className = 'caret';
		grpInputField.className = 'form-control';
		myul.className = 'dropdown-menu dropdown-menu-right';

	// ATTRIBUTES ***********
		grpInputField.setAttribute('aria-label','...');
		grpInputField.setAttribute('id','input_'+o.id);
		grpInputField.setAttribute('placeholder',o.placeholder);

		dbutton.setAttribute('id','btn_'+o.id);
		dbutton.setAttribute('type','button');
		dbutton.setAttribute('data-toggle','dropdown');
		dbutton.setAttribute('aria-haspopup','true' )
		dbutton.setAttribute('aria-expanded','false');

		myul.setAttribute('role','menu');


	// ITEMS TO SELECT **********
		o.values.map(function(i,idx){
			var myli  = document.createElement('li');
			var liValue = document.createElement('a');
			liValue.setAttribute('href',"javascript:$('#input_"+o.id+"').val('"+o.labels[idx]+"')");
			var Contnt = document.createTextNode(o.labels[idx]);
			liValue.appendChild(Contnt);
			myli.appendChild(liValue);
			myul.appendChild(myli);
		});

	// APPENDING CHILDS ***********
		dbutton.appendChild(btnlbl);
		dbutton.appendChild(caret);

		groupButton.appendChild(myul);
		groupButton.appendChild(dbutton);

		group.appendChild(grpInputField);
		group.appendChild(groupButton);
		document.getElementById(o.parent).appendChild(group);
}

	function mk_well(id,type,content,parent){
	var well = document.createElement('div');
	well.className = 'well well-'+type;
	var range = document.createRange();
	var documentFragment = range.createContextualFragment(content);
	well.appendChild(documentFragment);
	var currentDiv = document.getElementById(parent);
	currentDiv.appendChild(well);
}
/*
container_div_id: "content_cliPanel"
elem_attribs: "{\'id\':\'l_\'+index}||{\'onMouseOver\':\"hlIn(\'l_\"+ index+\"\')\"}||{\'onMouseOut\':\"hlOut(\'l_\"+index+\"\')\"}||					{'onClick':"rowClicked(\'l_"+ index+"\')"}"
elem_cont_data_src: "{\'content\':ds.RAZON_SOCIAL}||{\'content\':ds.LOCALIDAD}||{\'content\':ds.CALLE}||{\'content\':ds.ALTURA}"
elem_cont_labels: "'Nombre'||'Localidad'||'Calle'||'Altura'"
elem_id: "cliPanel_table"
elem_tittle: ""
element: "table"
layout_id: "1"
order_of_apearance: "1"
pk_id: "2"
usertype: "agent"
*/
//id,setings,ptit,pcontnt,parent

function mk_panelWithHeading(o){
	var panel = document.createElement('div');
	var panel_heading = document.createElement('div');
	var panel_body = document.createElement('div');
	var panel_bodyContainer = document.createElement('div');

	panel.id = o.elem_id;
	panel_heading.id = 'pnlHeading_'+o.elem_id;
	panel_body.id = 'pnlBody_'+o.elem_id;
	panel_bodyContainer.id = 'content_'+o.elem_id;

	var panel_tit = document.createElement('h3');
	panel_tit.id = 'tit_'+o.elem_id;
	var headtitle = document.createTextNode(o.elem_tittle);
	//var cnt = document.createTextNode(pcontnt);

	panel.className = "panel panel-default";
	panel_heading.className = "panel-heading";
	panel_body.className = "panel-body";
	o.elem_attribs.map(function(item){
		var item_settings = Object.getOwnPropertyNames(item)
 		item_settings.map(function(i){
 			panel_bodyContainer.setAttribute(i ,item[i]);
 		})
	})
	panel_body.appendChild(panel_bodyContainer)
	panel.appendChild(panel_heading);
	panel.appendChild(panel_body);

	panel_tit.className = "panel-title";
	panel_tit.appendChild(headtitle);
	panel_heading.appendChild(panel_tit);
	//panel_body.appendChild(cnt);
	var parentDiv = document.getElementById(o.container_div_id);
	parentDiv.appendChild(panel);
}
//tblId,tclass,heading,content,parent
function mk_table(o){
	var tbl = document.createElement('table');
	tbl.id = o.elem_id;
	tbl.className = 'table sticky-header';
	var tbody = document.createElement('tbody')
	var hrow = document.createElement('tr');
	var tableRows = document.createElement('div');

	if(o.elem_cont_labels.length > 0 ){
		var thead = document.createElement('thead');
		o.elem_cont_labels.map(function(hitm,i){
			var col = document.createElement('td');
			col.setAttribute('id',tbl.id+'_headCol_'+i);
			o.elem_attribs.map(function(atrb){col.setAttribute(Object.keys(atrb),atrb[Object.keys(atrb)])})
			var colContnt = document.createTextNode(hitm);
			col.appendChild(colContnt);
			hrow.appendChild(col);
			thead.appendChild(hrow);
		});
	}
	o.elem_contentData.map(function(ci,index){
		var crow = document.createElement('tr');
		crow.setAttribute('id',tbl.id+'_row_'+index);
		if(ci.hasOwnProperty('attrib') && typeof ci.attrib === 'object'){
			if(Object.prototype.toString.call(ci.attrib) === '[object Array]'){
				ci.attrib.map(function(att){crow.setAttribute(Object.keys(att),att[Object.keys(att)])});
			}else{
				//console.log(crow);
				crow.setAttribute(Object.keys(ci.attrib),ci.attrib[Object.keys(ci.attrib)]);
			}
		}
		if(ci.hasOwnProperty('items')){
			if(Object.prototype.toString.call(ci.items) === '[object Array]'){
				ci.items.map(function(citem){
					var icol = document.createElement('td');
					if(citem.hasOwnProperty('attrib')){
						citem.attrib.map(function(itatt){
							icol.setAttribute(Object.keys(itatt),itatt[Object.keys(itatt)]);
						})
					}
					var range = document.createRange();
					var documentFragment = range.createContextualFragment(citem.content);
					icol.appendChild(documentFragment);
					crow.appendChild(icol);
				})
			}
		}
		tbody.appendChild(crow);
		});
	if(typeof thead != 'undefined'){tbl.appendChild(thead)};
	tbl.appendChild(tbody);
	var currElem = document.getElementById(o.container_div_id);
	currElem.appendChild(tbl);
}


function mk_collapse(o){
	var pnlGrp = document.createElement('div');
  var pnl=document.createElement('div');
  pnlGrp.id = 'acordion';
  pnlGrp.className='panel-group';
  pnlGrp.setAttribute('role','tablist');
  pnlGrp.setAttribute('aria-multiselectable','false');
  pnl.className='panel panel-default';
  o.collapseLines.map(function(itm,ndx){
    var itmHdg=document.createElement('div');
    var itmTit=document.createElement('h4');
    var but=document.createElement('a');
    var clpsTit=document.createElement('span');
    var clps=document.createElement('div');
    var clpsbdy=document.createElement('div')
    itmHdg.className='panel-heading';
    itmHdg.id='heading_'+itm.id;
    itmHdg.setAttribute('role','tab');
    itmTit.className='panel-title';
    but.className='collapsed';
    but.setAttribute('role','button');
    but.setAttribute('data-toggle','collapse');
    but.setAttribute('data-parent','#'+pnlGrp.id);
    but.setAttribute('href','#'+itm.id);
    but.setAttribute('aria-expanded','false');
    but.setAttribute('aria-controls',itm.id);
    clpsTit.id=itm.id+'_icon';
    clpsTit.className='glyphicon glyphicon-menu-right';
    clpsTit.setAttribute('aria-hidden','false');
    var hdng=document.createTextNode(itm.heading);
    clpsTit.appendChild(hdng);
    clps.id=itm.id;
    clps.className='panel-collapse collapse';
    clps.setAttribute('role','tabpanel');
    clps.setAttribute('aria-labelledby',itmHdg.id);
    clpsbdy.className='panel-body';
    clpsbdy.id=itm.id+'_body';
		// CONTENIDO de cada collapse
	//	var range = document.createRange();
  //  var documentFragment = range.createContextualFragment(itm.contnt);
	//	clpsbdy.appendChild(documentFragment);
		clps.appendChild(clpsbdy);
    but.appendChild(clpsTit);
		itmTit.appendChild(but);
    itmHdg.appendChild(itmTit);
    pnl.appendChild(itmHdg);
    pnl.appendChild(clps)
  });
	pnlGrp.appendChild(pnl);
	var currElem = document.getElementById(o.container_div_id);
	currElem.appendChild(pnlGrp);
	var temp = JSON.parse(JSON.stringify(o))
	o.collapseLines.map(function(clns){
			 temp.elem_id = 'tbl_'+clns.id;
			 temp.container_div_id = clns.id+'_body'
			 temp.elem_contentData = clns.contnt
			 mk_table(temp)
		})
	$('.panel-collapse').on('hide.bs.collapse', function () {$('#'+this.id+'_icon').removeClass('glyphicon glyphicon-menu-down').addClass('glyphicon glyphicon-menu-right')})
  $('.panel-collapse').on('show.bs.collapse', function () {$('#'+this.id+'_icon').removeClass('glyphicon glyphicon-menu-right').addClass('glyphicon glyphicon-menu-down')})
}




	//**********************************/

// HIGLIGHT Y CLICKED GENERICO
function hlIn(item){
	if($('#'+item).css('background-color') == 'rgba(0, 0, 0, 0)'){
		$('#'+item).css({'background-color':'#B0C0CF','cursor':'pointer'})
	}
}

function hlOut(item){
	if($('#'+item).css('background-color') == 'rgb(176, 192, 207)'){
			$('#'+item).css({'background-color':'rgba(0, 0, 0, 0)','cursor':'auto'});
	}
}

function rowClicked(id){
		clkdOut(TOP.selectedRow);
		clkdIn(id);
		TOP.selectedRow = id;
		clickedHook(id);
}

function clkdIn(i){
	// put row to red
	$('#'+i).css({'background-color':'#DF9090'});
}
function clkdOut(i){
	$('#'+i).css({'background-color':'rgba(0, 0, 0, 0)','cursor':'auto'})
}
function clkdDone(i){
	$('#'+i).css({'background-color':'#C0CFB0','cursor':'auto'})
}
