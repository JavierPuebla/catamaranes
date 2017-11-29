<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="basic_app">
    <meta name="author" content="bigbot.io">

    <link rel="icon" href="favicon.png">

    <title>Catamaranes App V 0.1</title>

    <!-- Bootstrap core CSS -->
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- Custom styles for this template -->
    <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/yeti/bootstrap.min.css" rel="stylesheet">

    <!-- Custom core CSS -->
    <link rel="stylesheet" type="text/css" href="<?php  base_url()?>css/JP.css" />
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
<div class="bs-component">
    <div class="container">
    	<?php $t = validation_errors();
        if ($t != ''){
          echo"<div class='alert alert-danger' role='alert'><strong>Warning!</strong>$t</div>";
        }
      ?>

      <form class="jp-form-signin" method="post" accept-charset="utf-8" action="verifylogin">
        <h2 class="jp-form-signin-heading text-center"></h2>
        <p><label for="username" class="sr-only">Usuario</label>
        <input type="text" id="usr_usuario" name="usr_usuario" class="form-control" value="admin" required="" autofocus=""></p>
        <p><label for="inputPassword" class="sr-only">Clave</label>
        <input type="password" id="clave_usuario" name="clave_usuario" class="form-control" value="123" required=""></p>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
      </form>

    </div> <!-- /container -->
</div>



</body></html>
