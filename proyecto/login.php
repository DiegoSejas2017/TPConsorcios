<?php
include("conexion.php");
session_start();
?>
<!DOCTYPE html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
     <link href='http://fonts.googleapis.com/css?family=Raleway:500' rel='stylesheet' type='text/css'>
<link href="../Css/Estilos.css" rel='stylesheet' type='text/css'>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->


<link href='http://fonts.googleapis.com/css?family=Raleway:500' rel='stylesheet' type='text/css'>
    <title>Consorcios</title>
</head>
<body>
	
	<div class="container">
            
                <div class="middlePage">
                    <div class="page-header">
                      <h1 class="logo">Consorcios <small>Bienvenido!</small></h1>
                    </div>

                      <div class="panel panel-info">
                  <div class="panel-heading">
                    <h3 class="panel-title">Por favor iniciar sesión</h3>
                  </div>
                  <div class="panel-body">
                  
                  <div class="row">
                  
                    <div class="col-md-6 col-md-offset-3" style="solid #ccc;">
                        <?php
                            if(isset($_POST['ingresar'])){
                                $user          = mysqli_real_escape_string($con,(strip_tags($_POST["user"],ENT_QUOTES)));//Escanpando caracteres 
                                $pass             = mysqli_real_escape_string($con,(strip_tags($_POST["pass"],ENT_QUOTES)));
                                $password = md5($pass);

                                $sql = mysqli_query($con, "SELECT * FROM usuario WHERE emailusuario='$user' and contrasena ='$password'");
                                if(mysqli_num_rows($sql) == 0){                                
                                    $cek = mysqli_query($con, "SELECT * FROM propietarios WHERE email='$user' and password ='$password'");
                                
                                    if(mysqli_num_rows($cek) == 0){
                                        echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Usuario inexsistente.</div>';                                              
                                    }
                                    else{                                                  
                                        header("Location: gestionpropietarios.php?id=" .$user);
                                    }                                      
                                     
                                }else{       
                                    $row = mysqli_fetch_assoc($sql);
                                    if($row["estado"] == "1"){                                        
                                        if ($row["Idrol"] == "1") {
                                          $_SESSION["Usuario"] = "Administrador"; 
                                          header("Location: index.php"); 
                                        }
                                        else{
                                          $_SESSION["Usuario"] = "Operador"; 
                                          header("Location: consorcios.php"); 
                                        }                                                                                                                  
                                    }                               
                                    else{
                                        echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>El usuario aún no fue activado por un administrador.</div>';
                                    }
                                }
                            }
                        ?>
                        <form class="form-horizontal" method="post">
                            <fieldset>
                              <input id="user" name="user" type="text" placeholder="Ingrese Email" class="form-control input-md" required>
                              <div class="spacing"><a href="#"></a></div>
                              <input id="pass" name="pass" type="password" placeholder="Ingrese contraseña" class="form-control input-md" required>
                              <br>                            
                              <a href="registrar.php" title="Registrarse" class="btn btn-info center btn-sm pull-left">Registrarse</a>
                              <button id="ingresar" name="ingresar" class="btn btn-info center btn-sm pull-right">Ingresar</button>
                            </fieldset>
                        </form>
                     </div>                    
                </div>                
            </div>
            </div>
            <p><a href="#">Unlam</a> · Grupo 1</p>
            </div>
	    
	</div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    
</body>
</html>