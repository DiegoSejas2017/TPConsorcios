<?php
include("conexion.php");
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Consorcio</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">

	<style>
		.content {
			margin-top: 80px;
		}
	</style>
		<script type="text/javascript">
			$('ul li a').click(function(){ $('li a').removeClass("active"); $(this).addClass("active"); });
	</script> 
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
						?>			
						<li class="active"><a href="index.php">Inicio</a></li>
						<li><a href="consorcios.php">Consorcios</a></li>
						<li><a href="propietarios.php">Propietarios</a></li>					
						<li><a href="pagos.php">Pagos</a></li>
						<li><a href="cobranzas.php">Cobranzas</a></li>
						<li><a href="proveedores.php">Proveedores</a></li>
						<li><a href="estadisticas.php">Estadisticas</a></li>
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
			<h2>Lista de Usuario</h2>
			<hr />

			<?php			
			if(isset($_GET['aksi']) == 'delete'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM usuario WHERE emailusuario='$nik'");
				if(mysqli_num_rows($cek) == 0){
					echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
				}else{
					$delete = mysqli_query($con, "DELETE FROM usuario WHERE emailusuario='$nik'");
					if($delete){
						echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos eliminado correctamente.</div>';
					}else{
						echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudo eliminar los datos.</div>';
					}
				}
			}
			?>
			<?php
			if(isset($_POST['add'])){
                  $nombres = mysqli_real_escape_string($con,(strip_tags($_POST["nombreusuario"],ENT_QUOTES))); 
                  $dni = mysqli_real_escape_string($con,(strip_tags($_POST["dni"],ENT_QUOTES)));
                  $email  = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));
                  $password = mysqli_real_escape_string($con,(strip_tags($_POST["password"],ENT_QUOTES)));
                  $rol = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));
                  $pass = md5($password);
                  $cek = mysqli_query($con, "SELECT * FROM usuario WHERE emailusuario='$email'");
                  if(mysqli_num_rows($cek) == 0){
                      $insert = mysqli_query($con, "INSERT INTO usuario(nombre, contrasena, dni, emailusuario, estado, idrol)
                                        VALUES('$nombres','$pass', '$dni', '$email', '1', '$rol')") or die(mysqli_error());
                      if($insert){
                        echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';                                               
                      }else{
                        echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
                      }
                     
                  }else{
                    echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. código exite!</div>';
                  }
			}
			?>
			<form class="form-inline" method="get">
				<div class="form-group">
					<select name="filter" class="form-control" onchange="form.submit()">
						<option value="0">Filtrar por Rol</option>
						<?php $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);  ?>
						<option value="1" <?php if($filter == 'Administrador'){ echo 'selected'; } ?>>Administrador</option>
						<option value="2" <?php if($filter == 'Propietario'){ echo 'selected'; } ?>>Propietario</option>
					
					</select>
				</div>
			</form>
			<br />
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>                    
					<th>Nombre</th>
                    <th>DNI</th>
                    <th>Correo electronico</th>
					<th>Estado</th>
					<th>Rol</th>
                    <th>Acciones</th>
				</tr>
				<?php
				if($filter){
					$sql = mysqli_query($con, "SELECT * FROM usuario WHERE idrol = '$filter' ORDER BY nombre ASC");
				}else{
					$sql = mysqli_query($con, "SELECT * FROM usuario ORDER BY nombre ASC");
				}
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>							
							<td>'.$row['nombre'].'</td>
                            <td>'.$row['dni'].'</td>
                            <td>'.$row['emailusuario'].'</td>
							<td>';
							if($row['estado'] == '1'){
								echo '<span class="label label-success">Activo</span>';
							}
                            else if ($row['estado'] == '0' ){
								echo '<span class="label label-info">Inactivo</span>';
							}
                           
								echo '
							</td>

							<td>';
							if($row['Idrol'] == '1'){
								echo '<span class="label label-danger">Administrador</span>';
							}
                            else if ($row['Idrol'] == '2' ){
								echo '<span class="label label-info">Operador</span>';
							}
                           
								echo '
							</td>
							<td>

								<a href="edit.php?nik='.$row['emailusuario'].'" title="Editar datos" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<a href="index.php?aksi=delete&nik='.$row['emailusuario'].'" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['emailusuario'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
							</td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>

			<h2>Agregar Usuario</h2>
			<hr />
<form class="form-horizontal" action="" method="post">
				<div class="form-group">
					<label class="col-sm-3 control-label">Nombre de usuario</label>
					<div class="col-sm-2">
						<input type="text" name="nombreusuario" class="form-control" placeholder="Nomre de usuario" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Contraseña</label>
					<div class="col-sm-4">
						<input type="text" name="password" class="form-control" placeholder="Contraseña" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">DNI</label>
					<div class="col-sm-4">
						<input type="text" name="dni" class="form-control" placeholder="DNI" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Correo Electronico</label>
					<div class="col-sm-4">
						<input type="text" name="email" class="form-control input-sm" placeholder="Correo Electronico" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Rol</label>
					<div class="col-sm-3">
						<select name="estado" class="form-control">
							<option value=""> ----- </option>
                           <option value="1">Administrador</option>
							<option value="2">Operador</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="add" class="btn btn-sm btn-primary" value="Guardar datos">
						<a href="index.php" class="btn btn-sm btn-danger">Cancelar</a>
					</div>
				</div>
			</form>			
		</div>
	</div><center>
	<p>&copy; Sistemas Web <?php echo date("Y");?></p
		</center>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
