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
	<title>Consorcios</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
						<li class="active"><a href="pagos.php">Pagos</a></li>
						<li ><a href="cobranzas.php">Cobranzas</a></li>
						<li><a href="proveedores.php">Proveedores</a></li>
						<li><a href="estadisticas.php">Estadisticas</a></li>
					<?php else : ?>						
						<li><a href="consorcios.php">Consorcios</a></li>
						<li><a href="propietarios.php">Propietarios</a></li>					
						<li class="active"><a href="pagos.php">Pagos</a></li>
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
						<h2>Pagos</h2>
			<div class="table-responsive">
			<hr />
			<?php
			if(isset($_GET['delp']) == 'delete'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));

					$delete = mysqli_query($con, "DELETE FROM gasto WHERE idgasto='$nik'");
					if($delete){
						echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos eliminado correctamente.</div>';
					}else{
						echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudo eliminar los datos.</div>';
					}
			}

			if(isset($_GET['aksi']) == 'delete'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));	
				$delete = mysqli_query($con, "DELETE FROM reclamo WHERE idreclamo='$nik'");
				if($delete){
					echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos eliminado correctamente.</div>';
				}else{
					echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudo eliminar los datos.</div>';
				}				
			}

			if(isset($_POST['add'])){
                $fecha         = mysqli_real_escape_string($con,(strip_tags($_POST["fecha"],ENT_QUOTES)));//Escanpando caracteres 
                $importe  = mysqli_real_escape_string($con,(strip_tags($_POST["importe"],ENT_QUOTES)));//Escanpando caracteres 
                $concepto  = mysqli_real_escape_string($con,(strip_tags($_POST["concepto"],ENT_QUOTES)));
                $estado  = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));	
                $consorcio  = mysqli_real_escape_string($con,(strip_tags($_POST["consorcio"],ENT_QUOTES)));	

                $insert = mysqli_query($con, "INSERT INTO gasto (fecha, importe, concepto, idprov, idconsorcio)
                                        VALUES('$fecha','$importe', '$concepto', '$estado', '$consorcio')") or die(mysqli_error());
                if($insert){
                    echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
                        //sleep(2);
                        //header("Location: index.php");
                }else{
                     echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
                }
			}
			?>

			<h4>Detalles de gastos</h4>
			<table class="table table-striped table-hover">
				<tr>
					<th>Fecha</th>
                    <th>Importe</th>
                    <th>Concepto</th>
                    <th>Proveedor</th>
                    <th>Consorcio</th>
                    <th>Acciones</th>
				</tr>
				<?php

				$sql = mysqli_query($con, "SELECT a.idgasto,a.fecha, a.importe, a.concepto, b.nombre , c.nombre as consorcio
											 FROM gasto a join 
											 	  proveedor b on a.idprov = b.idprov join
											 	  consorcio c on c.idconsorcio = a.idconsorcio
										ORDER BY a.fecha ASC");
				
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
							<td>'.$row['fecha'].'</td>
                            <td>'.$row['importe'].'</td>
                            <td>'.$row['concepto'].'</td>
							<td>'.$row['nombre'].'</td>
							<td>'.$row['consorcio'].'</td>
							<td>

								<a href="edit.php?nik='.$row['idgasto'].'" title="Editar datos" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<a href="pagos.php?delp=delete&nik='.$row['idgasto'].'" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['concepto'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
							</td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>

			<h4>Reclamos</h4>
			<table class="table table-striped table-hover">
				<tr>
					<th>Fecha</th>
                    <th>Titulo</th>
                    <th>Descripcion</th>
                    <th>Propietario</th>
                    <th>Consorcio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
				</tr>
				<?php

				$sql = mysqli_query($con, "SELECT a.idreclamo,a.fecha, a.titulo, a.descripcion, a.estado, b.nombre , c.nombre as consorcio
											 FROM reclamo a join 
											 	  propietarios b on a.idpropietario = b.idpropietario join
											 	  consorcio c on c.idconsorcio = a.idconsorcio
										ORDER BY a.fecha ASC");
				
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
							<td>'.$row['fecha'].'</td>
                            <td>'.$row['titulo'].'</td>
                            <td>'.$row['descripcion'].'</td>
							<td>'.$row['nombre'].'</td>
							<td>'.$row['consorcio'].'</td>
							<td>';
							if($row['estado'] == '1'){
								echo '<span class="label label-success">Pendiente</span>';
							}
                            else if ($row['estado'] == '0' ){
								echo '<span class="label label-info">Pagado</span>';
							}
                           
								echo '
							</td>
							<td>

								<a href="efectuarpago.php?id='.$row['idreclamo'].'&fecha='.$row['fecha'].'&titulo='.$row['titulo'].'&nombre='.$row['nombre'].'&consorcio='.$row['consorcio'].'" title="Pagar" class="btn btn-primary btn-sm"><span class="fa fa-credit-card" aria-hidden="true"></span></a>
								<a href="pagos.php?aksi=delete&nik='.$row['idreclamo'].'" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['titulo'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
							</td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>

			
				<h2>Agregar gasto</h2>
				<hr />
				<div class="container">
					<div class="col-sm-10">
						<form class="form-horizontal" action="" method="post">
							<div class="form-group">
								<label class="col-sm-6 control-label">Fecha</label>
								<div class="col-sm-4">
									<input type="text" name="fecha" class="input-group date form-control" date="" data-date-format="dd-mm-yyyy"  required>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-6 control-label">Importe</label>
								<div class="col-sm-4">
									<input type="text" name="importe" class="form-control" placeholder="Importe" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label">Concepto</label>
								<div class="col-sm-4">
									<input type="text" name="concepto" class="form-control" placeholder="Concepto" required>
								</div>	
							</div>

							<div class="form-group">
								<label class="col-sm-6 control-label">Proveedores</label>
								<div class="col-sm-4">
									<select name="estado" class="form-control">
										<option value="">Seleccione proveedor </option>
										<?php
										// Check query error 
										$sql = mysqli_query($con, "SELECT * FROM proveedor ORDER BY nombre ASC");
										// Check query error
										if(mysqli_num_rows($sql) == 0){
											echo '<tr><td colspan="8">No hay datos.</td></tr>';
										}else{
											$no = 1;
											while($row = mysqli_fetch_assoc($sql)){
										      $selected = $row['nombre'] == $city_id ? "selected = 'selected'" : '';           
										      echo "<option value = '{$row['idprov']}' $selected >". $row['nombre'] ."</option>";
										    }
										    echo "</select>";
										 }
										    ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-6 control-label">Consorcios</label>
								<div class="col-sm-4">
									<select name="consorcio" class="form-control">
										<option value="">Seleccione consorcio </option>
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
								<div class="col-sm-8 col-sm-offset-6">
									<input type="submit" name="add" class="btn btn-sm btn-primary" value="Guardar">
									<label class="col-sm-2 control-label">&nbsp;</label>
								</div>
							</div>
						</form>
					</div>
				</div>	
		</div>
    </div>

	<center>
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
