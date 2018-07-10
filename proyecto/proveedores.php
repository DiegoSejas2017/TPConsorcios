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
						<li><a href="propietarios.php">Propietarios</a></li>					
						<li><a href="pagos.php">Pagos</a></li>
						<li ><a href="cobranzas.php">Cobranzas</a></li>
						<li class="active"><a href="proveedores.php">Proveedores</a></li>
						<li><a href="estadisticas.php">Estadisticas</a></li>
					<?php else : ?>						
						<li><a href="consorcios.php">Consorcios</a></li>
						<li ><a href="propietarios.php">Propietarios</a></li>					
						<li ><a href="pagos.php">Pagos</a></li>
						<li><a href="cobranzas.php">Cobranzas</a></li>
						<li class="active"><a href="proveedores.php">Proveedores</a></li>						
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
			<h2>Proveedores</h2>
			<hr />

			<?php
			if(isset($_GET['aksi']) == 'delete'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM proveedor WHERE idprov='$nik'");
				if(mysqli_num_rows($cek) == 0){
					echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
				}else{
					$delete = mysqli_query($con, "DELETE FROM proveedor WHERE idprov='$nik'");
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

                  $cek = mysqli_query($con, "SELECT * FROM proveedor WHERE cuit='$cuit'");
                  if(mysqli_num_rows($cek) == 0){
                      $insert = mysqli_query($con, "INSERT INTO proveedor(nombre, cuit)
                                        VALUES('$nombre','$cuit')") or die(mysqli_error());
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

			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>
					<th>Nombre</th>
					<th>Cuit</th>
                    <th>Acciones</th>
				</tr>
				<?php

					$sql = mysqli_query($con, "SELECT *
												 FROM proveedor a
											 ORDER BY a.nombre");
				
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
							<td><a href="profile.php?nik='.$row['idprov'].'"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> '.$row['nombre'].'</a></td>
                            <td>'.$row['cuit'].'</td>
                            
							<td>

								<a href="consorciosedit.php?nik='.$row['idprov'].'" title="Editar datos" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<a href="consorcios.php?aksi=delete&nik='.$row['idprov'].'" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['nombre'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
							</td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>
			<h2>Agregar Proveedor</h2>
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
								<label class="col-sm-4 control-label">Cuit</label>
								<div class="col-sm-6">
									<input type="text" name="cuit" class="form-control" placeholder="Cuit" required>
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/es.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script>
	$('.date').datepicker({
		format: 'yyyy-mm-dd',
		locale: 'es'
	})
	</script>
	
</body>
</html>