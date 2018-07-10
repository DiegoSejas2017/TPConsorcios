<?php
include("conexion.php");
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<!--
Project      : Datos de empleados con PHP, MySQLi y Bootstrap CRUD  (Create, read, Update, Delete) 
Author		 : Obed Alvarado
Website		 : http://www.obedalvarado.pw
Blog         : http://obedalvarado.pw/blog/
Email	 	 : info@obedalvarado.pw
-->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Datos de empleados</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-datepicker.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">
	<style>
		.content {
			margin-top: 80px;
		}
	</style>
	
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand visible-xs-block visible-sm-block" href="">Inicio</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<?php					
					if ($_SESSION["Usuario"] == null) {
						header("Location: login.php"); 
					}
					if ($_SESSION["Usuario"] == "Administrador") : ?>					
						<li><a href="index.php">Inicio</a></li>
						<li class="active"><a href="consorcios.php">Consorcios</a></li>
						<li><a href="propietarios.php">Propietarios</a></li>					
						<li><a href="pagos.php">Pagos</a></li>
						<li ><a href="cobranzas.php">Cobranzas</a></li>
						<li><a href="proveedores.php">Proveedores</a></li>
						<li><a href="estadisticas.php">Estadisticas</a></li>
					<?php else : ?>						
						<li><a href="consorcios.php">Consorcios</a></li>
						<li><a href="propietarios.php">Propietarios</a></li>					
						<li><a href="pagos.php">Pagos</a></li>
						<li><a href="cobranzas.php">Cobranzas</a></li>
						<li><a href="proveedores.php">Proveedores</a></li>											
					<?php endif; ?>	
				</ul>
				<ul class="nav navbar-nav navbar-right">
			      <li><a href="#"><span class="glyphicon glyphicon-user"></span></a></li>
			      <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Cerrar Sesión</a></li>
			    </ul>				
			</div><!--/.nav-collapse -->
	</div>
	</nav>
	<div class="container">
		<div class="content">
			<h2>Datos del Consorcio &raquo; Editar datos</h2>
			<hr />
			
			<?php
			// escaping, additionally removing everything that could be (html/javascript-) code
			$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
			$sql = mysqli_query($con, "SELECT * FROM consorcio WHERE idconsorcio='$nik'");
			if(mysqli_num_rows($sql) == 0){
				header("Location: index.php");
			}else{
				$row = mysqli_fetch_assoc($sql);
			}
			if(isset($_POST['save'])){
                  $nombre         = mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
                  $cuit  = mysqli_real_escape_string($con,(strip_tags($_POST["cuit"],ENT_QUOTES)));//Escanpando caracteres 
                  $codpostal  = mysqli_real_escape_string($con,(strip_tags($_POST["cod_postal"],ENT_QUOTES)));//Escanpando caracteres 
                  $email       = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));//Escanpando caracteres  
                  $telefono      = mysqli_real_escape_string($con,(strip_tags($_POST["telefono"],ENT_QUOTES)));//Escanpando caracteres 
				  $direccion      = mysqli_real_escape_string($con,(strip_tags($_POST["direccion"],ENT_QUOTES)));	

				$update = mysqli_query($con, "UPDATE consorcio SET nombre='$nombre', cuit='$cuit', cod_postal='$codpostal', email_consorcio='$email', telefono_consorcio='$telefono', direccion='$direccion' WHERE idconsorcio='$nik'") or die(mysqli_error());
				if($update){
					header("Location: consorciosedit.php?nik=".$nik."&pesan=sukses");
				}else{
					echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error, no se pudo guardar los datos.</div>';
				}
			}
			
			if(isset($_GET['pesan']) == 'sukses'){
				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Los datos han sido guardados con éxito.</div>';
			}
			?>
			<form class="form-horizontal" action="" method="post">
				<div class="form-group">
					<label class="col-sm-3 control-label">Nombre de Consorcio</label>
					<div class="col-sm-2">
						<input type="text" name="nombre" class="form-control" value="<?php echo $row ['nombre']; ?>" placeholder="Nombre de consorcio" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Cuit</label>
					<div class="col-sm-4">
						<input type="text" name="cuit" class="form-control" value="<?php echo $row ['cuit']; ?>" placeholder="Cuit" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Codigo Postal</label>
					<div class="col-sm-4">
						<input type="text" name="cod_postal" class="form-control" value="<?php echo $row ['cod_postal']; ?>" placeholder="Codigo Postal" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Correo Electronico</label>
					<div class="col-sm-4">
						<input type="text" name="email" class="form-control input-sm" value="<?php echo $row ['email_consorcio']; ?>" placeholder="Correo Electronico" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Telefono</label>
					<div class="col-sm-4">
						<input type="text" name="telefono" class="form-control input-sm" value="<?php echo $row ['telefono_consorcio']; ?>" placeholder="Telefono" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Direccion</label>
					<div class="col-sm-4">
						<input type="text" name="direccion" class="form-control input-sm" value="<?php echo $row ['direccion']; ?>" placeholder="Direccion" required>
					</div>
				</div>
			
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="save" class="btn btn-sm btn-primary" value="Guardar datos">
						<a href="consorcios.php" class="btn btn-sm btn-danger">Cancelar</a>
					</div>
				</div>
			</form>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script>
	$('.date').datepicker({
		format: 'dd-mm-yyyy',
	})
	</script>
</body>
</html>