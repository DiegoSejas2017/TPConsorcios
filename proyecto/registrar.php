<?php
include("conexion.php");
?>
<!DOCTYPE html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
     <link href='http://fonts.googleapis.com/css?family=Raleway:500' rel='stylesheet' type='text/css'>
<link href="../Css/Estilos.css" rel='stylesheet' type='text/css'>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->


<link href='http://fonts.googleapis.com/css?family=Raleway:500' rel='stylesheet' type='text/css'>
    <title>Consorcios</title>
</head>
<body>
	
	<div class="container">
            
<div class="middlePage" style="width: 565px">
  <div class="page-header">
    <h1 class="logo">Consorcios</h1>
  </div>

      <div class="container">
          <div class="row centered-form">
          <div class="col-xs-12 col-sm-8 col-md-6 ">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Registro de usuario <small>Consorcio</small></h3>
              </div>
              <div class="panel-body">
                <?php
                if(isset($_POST['guardar'])){
                  $nombres = mysqli_real_escape_string($con,(strip_tags($_POST["nombreusuario"],ENT_QUOTES))); 
                  $dni  = mysqli_real_escape_string($con,(strip_tags($_POST["dni"],ENT_QUOTES)));
                  $email  = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES))); 
                  $password       = mysqli_real_escape_string($con,(strip_tags($_POST["password"],ENT_QUOTES)));
                  $password2       = mysqli_real_escape_string($con,(strip_tags($_POST["password_confirmation"],ENT_QUOTES)));
                  if ($password == $password2) {
                    
                    $pass = md5($password);

                    $cek = mysqli_query($con, "SELECT * FROM usuario WHERE emailusuario='$email'");
                    if(mysqli_num_rows($cek) == 0){
                        $insert = mysqli_query($con, "INSERT INTO usuario(nombre, contrasena, dni, emailusuario, estado, idrol)
                                          VALUES('$nombres','$pass', '$dni', '$email', 0, '2')") or die(mysqli_error());
                        if($insert){
                          echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Registro exitoso!  aguarde la activación de su usuario por parte de un Administrador.</div>';                        
                        }else{
                          echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
                        }
                       
                    }else{
                      echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. código exite!</div>';
                    }  
                  }
                  else{
                    echo "<script type='text/javascript'>alert('Las contraseñas no coinciden');</script>";
                  }
                }
                ?>

                <form  method="post">
                  <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                      <div class="form-group">
                        <input type="text" name="nombreusuario" id="nombreusuario" class="form-control input-sm" placeholder="Nombre de usuario">
                      </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                      <div class="form-group">
                        <input type="text" name="dni" id="dni" class="form-control input-sm" placeholder="DNI">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <input type="email" name="email" id="email" class="form-control input-sm" placeholder="Email">
                  </div>

                  <!-- <div class="form-group">                       
                      <select name="estado" class="form-control">
                        <option value=""> Seleccione Rol </option>
                                     <option value="1">Administrador</option>
                        <option value="2">Operador</option>
                      </select>                    
                  </div> -->

                  <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                      <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control input-sm" placeholder="Contraseña">
                      </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                      <div class="form-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-sm" placeholder="Confirme contraseña">
                      </div>
                    </div>
                  </div>
                  <a href="login.php" title="volver" class="btn btn-info center btn-sm pull-left">Volver</a>
                      <button type="submit" name="guardar" class="btn btn-info btn-sm pull-right">Guardar Usuario</button>
                </form>          
              </div>
            </div>
          </div>
        </div>
      </div>
</div>
	    
	</div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    
</body>
</html>