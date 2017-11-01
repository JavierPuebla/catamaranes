

<?php

echo (new DateTime())->format('Y');
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
  Definir Ciclo : <b>Dia inicio:</b> <input id="ex2" type="text" class="span2" value="" data-slider-min="300" data-slider-max="1000" data-slider-step="5" data-slider-value="[400,450]"/> <b>Dia fin</b>

  </div>

<script type="text/javascript">

$("#ex2").slider({}).on('slide', function(ev){
    var f = $('#ex2').slider('getValue');

    console.log(f[0].value);
  });


  function rgbToHex(r, g, b) {
      return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
  }

console.log((255>>2 ));

</script>
