

<?php
$dt = str_replace('/','-','05/03/2016');

$day=substr($dt,0,strpos($dt,'-'));
$mon=substr($dt,strpos($dt,'-')+1,strlen($day));
$yr=substr($dt,strrpos($dt,'-')+1);


echo $yr.'-'.$mon.'-'.$day;

 ?>

<!-- collapse demo-->
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"  href="#collapseOne"aria-expanded="false" aria-controls="collapseOne">
            <span id='collapseOne_icon' class='glyphicon glyphicon-menu-right' aria-hidden='false'> Titulo de heading 1</span>
          </a>
        </h4>
      </div>
      <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body" id='collapseOne_body'>
          Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
        </div>
      </div>


      <div class="panel-heading" role="tab" id="heading-dos">
        <h4 class="panel-title">
          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-dos" aria-expanded="false" aria-controls="collapse-dos">
            <span id='collapse-dos_icon' class='glyphicon glyphicon-menu-right' aria-hidden='true'> Titulo de Heading dos</span>
          </a>
        </h4>
      </div>
      <div id="collapse-dos" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-dos" >
        <div class="panel-body">
          contenido de heading dos asdlfkaslfk ldfklsfk safalsdfk alsfdk
        </div>
      </div>
    </div>
  </div>


<div class="container" id='bs'>

  <input type="text" class="span2" value="" data-slider-min="-20" data-slider-max="20" data-slider-step="1" data-slider-value="-14" data-slider-orientation="vertical" data-slider-selection="after"data-slider-tooltip="hide">
</div>
<label for='dp-revisita'>Agendar Revisita</label><div class='input-group date'><input type='text'id='dp-revisita' class='form-control'><span class='input-group-addon'><i class='glyphicon glyphicon-th'></i></span></div>
<script type="text/javascript">


$('.input-group.date').datepicker({
  language:'es',
  format: "dd/mm/yyyy",
  weekStart: 1,
  daysOfWeekHighlighted: "0,6",
  calendarWeeks: true,
  todayHighlight: true
});

var monthNames = [
  "Enero", "Febrero", "Marzo",
  "Abril", "Mayo", "Junio", "Julio",
  "Agosto", "Septimbre", "Octubre",
  "Novimbre", "Deciembre"
];
var date = new Date();
var day = date.getDate();
var monthIndex = date.getMonth();
var year = date.getFullYear();
console.log(date.toISOString());





function mk_collapsible(o){
  var pnlGrp = document.createElement('div');
  var pnl=document.createElement('div');
  pnlGrp.id = 'acordion';
  pnlGrp.className='panel-group';
  pnlGrp.setAttribute('role','tablist');
  pnlGrp.setAttribute('aria-multiselectable','false');
  pnl.className='panel panel-default';
  o.items.map(function(itm,ndx){
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
    var range = document.createRange();
    var documentFragment = range.createContextualFragment(itm.contnt);
    clpsbdy.appendChild(documentFragment);
    clps.appendChild(clpsbdy);
    but.appendChild(clpsTit);

    itmTit.appendChild(but);
    itmHdg.appendChild(itmTit);
    pnl.appendChild(itmHdg);
    pnl.appendChild(clps)
  });
  pnlGrp.appendChild(pnl);
  var currElem = document.getElementById(o.container);
	currElem.appendChild(pnlGrp);
  $('.panel-collapse').on('hide.bs.collapse', function () {$('#'+this.id+'_icon').removeClass('glyphicon glyphicon-menu-down').addClass('glyphicon glyphicon-menu-right')})
  $('.panel-collapse').on('show.bs.collapse', function () {$('#'+this.id+'_icon').removeClass('glyphicon glyphicon-menu-right').addClass('glyphicon glyphicon-menu-down')})
}

// *****************----------------------------******************


  var ob = {'container':'bs','items':[{'id':'colapse1','heading':' Titulo collapse-1','contnt':'<h3>contenido del colapse 0001</h3>'},{'id':'colapse2','heading':' Titulo collapse-2','contnt':'contenido del colapse 0002'},{'id':'colapse3','heading':' Titulo collapse-3','contnt':'contenido del colapse 0003'},{'id':'colapse4','heading':' Titulo collapse-4','contnt':'contenido del colapse 0004'}],

  }
mk_collapsible(ob);


if (typeof(localStorage) == 'undefined' ) {
  alert('Your browser does not support HTML5 localStorage. Try upgrading.');
} else {
  try {
      localStorage.setItem('name', 'Hello World!'); //saves to the database, “key”, “value”
  } catch (e) {
    if (e == QUOTA_EXCEEDED_ERR) {
      alert('Quota exceeded!'); //data wasn’t successfully saved due to quota exceed so throw an error
    }
  }
document.write(localStorage.getItem('name')); //Hello World!
//localStorage.removeItem('name'); //deletes the matching item from the database
//localStorage.clear();
}
var topcontext = 100;

// factories = una funcion que retorna un objeto
 const dog = (i) => {
  const sound='woof'
  const change='res: '+ i.name +" tot: "+(i.price*i.cant)
  const tc = topcontext
  return {
    talk : () => change + 'algo mas'+ (4*3+topcontext),
    tc:()=>topcontext,
    met :() => i.price * topcontext + 10
  }
}
/*
var sniffles = dog({price:10,name:'name1',cant:2});
var sn2 = dog({price:1})
//sniffles.change='ffff'
//sniffles.talk();

//console.log(sniffles.talk(),sniffles.tc());
//console.log(sn2.talk());


const cart = (itm) =>{
  return{
    set:()=>
  }
}
*/
var knldg= function(d){
  this.stk = d
}
knldg.prototype.search = function (lbl,val) {
  var res = function lp(s,lbl,val,i){
    if(i === s.length){return}
    else{
      if(s[i][lbl] === val){return i}
      else{return lp(s,lbl,val,i+1)}
    }
  }
  return res(this.stk,lbl,val,0);
  };


var cart = new knldg([])

const cartitm = (itm) =>{
  const price= itm.price
  const cant=0
  return{
    say:()=> price*cant
    //set:()=>cant = itm.cant
  }
}


var itm1={id:100,itm: cartitm({price:500})}
var itm2={id:20,itm: cartitm({price:1000})}
var itm3={id:30,itm:cartitm({price:3000})}
console.log(itm3);
itm3.itm.set({cant:20})
cart.stk.push(itm1)
cart.stk.push(itm2)


function updateCart(itm){

}
console.log(itm3.itm.say());
console.log((typeof(cart.search('id',100))!='undefined')?cart.search('id',100):cart.stk.length+1);
//console.log(cart.search('id',1000000));
</script>
