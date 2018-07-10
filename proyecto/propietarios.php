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
						<li ><a href="consorcios.php">Consorcios</a></li>
						<li class="active"><a href="propietarios.php">Propietarios</a></li>					
						<li><a href="pagos.php">Pagos</a></li>
						<li ><a href="cobranzas.php">Cobranzas</a></li>
						<li><a href="proveedores.php">Proveedores</a></li>
						<li><a href="estadisticas.php">Estadisticas</a></li>
					<?php else : ?>						
						<li><a href="consorcios.php">Consorcios</a></li>
						<li class="active"><a href="propietarios.php">Propietarios</a></li>					
						<li ><a href="pagos.php">Pagos</a></li>
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
			<h2>Propietarios</h2>
			<hr />

			<?php
			if(isset($_GET['aksi']) == 'delete'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM propietarios WHERE email='$nik'");
				if(mysqli_num_rows($cek) == 0){
					echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
				}else{
					$delete = mysqli_query($con, "DELETE FROM propietarios WHERE email='$nik'");
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
                  $nombre         = mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
                  $cuit  = mysqli_real_escape_string($con,(strip_tags($_POST["cuit"],ENT_QUOTES)));//Escanpando caracteres 
                  $consejo  = mysqli_real_escape_string($con,(strip_tags($_POST["consejo"],ENT_QUOTES)));//Escanpando caracteres 
                  $email       = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));//Escanpando caracteres  
                  $telefono      = mysqli_real_escape_string($con,(strip_tags($_POST["telefono"],ENT_QUOTES)));//Escanpando caracteres 
				  $dni      = mysqli_real_escape_string($con,(strip_tags($_POST["dni"],ENT_QUOTES)));	
				  $piso      = mysqli_real_escape_string($con,(strip_tags($_POST["piso"],ENT_QUOTES)));	
				  $departamento      = mysqli_real_escape_string($con,(strip_tags($_POST["departamento"],ENT_QUOTES)));
				  $participacion      = mysqli_real_escape_string($con,(strip_tags($_POST["participacion"],ENT_QUOTES)));	
				  $consorcio      = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 

                  $cek = mysqli_query($con, "SELECT * FROM propietarios WHERE nombre='$nombre'");
                  if(mysqli_num_rows($cek) == 0){
                      $insert = mysqli_query($con, "INSERT INTO propietarios(nombre, cuit, consejo, email, telefono, dni, piso, departamento, porparticip, idconsorcio)
                                        VALUES('$nombre','$cuit', '$consejo', '$email', '$telefono','$dni','$piso', '$departamento', '$participacion', '$consorcio')") or die(mysqli_error());
                      if($insert){
                        echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
                        //sleep(2);
                        //header("Location: index.php");
                      }else{
                        echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
                      }
                     
                  }else{
                    echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. código exite!</div>';
                  }
			}
			?>

			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>
					
					<th>Cuit</th>
                    <th>DNI</th>
                    <th>Piso</th>
                    <th>Departamento</th>
                    <th>Correo electronico</th>
					<th>Telefono</th>
					<th>Consejo</th>
					<th>Consorcio</th>
					<th>Participación</th>
                    <th>Acciones</th>
				</tr>
				<?php

					$sql = mysqli_query($con, "SELECT a.*,b.nombre as nombreconsorcio 
												 FROM propietarios a join 
													  consorcio b on a.idconsorcio = b.idconsorcio
											 ORDER BY a.nombre");
				
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
                            <td>'.$row['cuit'].'</td>
                            <td>'.$row['dni'].'</td>
                            <td>'.$row['piso'].'</td>
                            <td>'.$row['departamento'].'</td>
                            <td>'.$row['email'].'</td>
                            <td>'.$row['telefono'].'</td>
                            <td>'.$row['consejo'].'</td>
                            <td>'.$row['nombreconsorcio'].'</td>
                            <td>'.$row['porparticip'].'</td>
							<td>

								<a href="consorciosedit.php?nik='.$row['idPropietario'].'" title="Editar datos" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<a href="consorcios.php?aksi=delete&nik='.$row['idPropietario'].'" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['nombre'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
							</td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>
			<h2>Agregar Propietario</h2>
			<hr />
			<div class="container">
				<div class="col-sm-10">
					<form class="form-horizontal" action="" method="post">
							<div class="form-group">
								<label class="col-sm-4 control-label">Nombre de Propietario</label>
								<div class="col-sm-6">
									<input type="text" name="nombre" class="form-control" placeholder="Nombre de consorcio" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Consejo</label>
								<div class="col-sm-6">
									<input type="text" name="consejo" class="form-control" placeholder="Consejo" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Cuit</label>
								<div class="col-sm-6">
									<input type="text" name="cuit" class="form-control" placeholder="Cuit" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">DNI</label>
								<div class="col-sm-6">
									<input type="text" name="dni" class="form-control" placeholder="DNI" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Piso</label>
								<div class="col-sm-6">
									<input type="text" name="piso" class="form-control" placeholder="Piso" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Departamento</label>
								<div class="col-sm-6">
									<input type="text" name="departamento" class="form-control" placeholder="departamento" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Correo Electronico</label>
								<div class="col-sm-6">
									<input type="text" name="email" id="email" class="form-control" placeholder="Correo Electronico" required>
									<span id="error" style="display:none;color:red;">Email invalido</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Telefono</label>
								<div class="col-sm-6">
									<input type="text" name="telefono" class="form-control input-sm" placeholder="Telefono" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Participación</label>
								<div class="col-sm-6">
									<input type="text" name="participacion" class="form-control input-sm" placeholder="Participación" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Consorcio</label>
								<div class="col-sm-6">
									<select name="estado" class="form-control">
										<option value=""> ----- </option>
										<?php
										// Check query error 
										$sql = mysqli_query($con, "SELECT * FROM consorcio ORDER BY nombre ASC");
										// Check query error
										if(mysqli_num_rows($sql) == 0){
											echo '<tr><td colspan="8">No hay datos.</td></tr>';
										}else{
											$no = 1;
											while($row = mysqli_fetch_assoc($sql)){
										      $selected = $row['nombre'] == $city_id ? "selected = 'selected'" : '';           
										      echo "<option value = '{$row['idconsorcio']}' $selected >". $row['nombre'] ."</option>";
										    }
										    echo "</select>";
										 }
										    ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">&nbsp;</label>
								<div class="col-sm-6">
									<input type="submit" name="add" class="btn btn-sm btn-primary" value="Guardar datos">
									<a href="index.php" class="btn btn-sm btn-danger">Cancelar</a>
								</div>
							</div>
						</form>	
				</div>
			</div>
	</div><center>
	<p>&copy; Sistemas Web <?php echo date("Y");?></p
		</center>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
