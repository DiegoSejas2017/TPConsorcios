<?php
include("conexion.php");
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
				<a class="navbar-brand visible-xs-block visible-sm-block" href="">Volver</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li><a href="pagos.php">Volver</a></li>
				</ul>
			</div><!--/.nav-collapse -->
	</div>
	</nav>
	<div class="container">
		<div class="content">
			<h2>Efectuar Pago</h2>
			<div class="table-responsive">
			<hr />
			<?php

    		$id = mysqli_real_escape_string($con,(strip_tags($_GET["id"],ENT_QUOTES)));
    		$getfecha = mysqli_real_escape_string($con,(strip_tags($_GET["fecha"],ENT_QUOTES)));
    		$titulo = mysqli_real_escape_string($con,(strip_tags($_GET["titulo"],ENT_QUOTES)));
    		$nombre = mysqli_real_escape_string($con,(strip_tags($_GET["nombre"],ENT_QUOTES)));
    		$nombreconsorcio = mysqli_real_escape_string($con,(strip_tags($_GET["consorcio"],ENT_QUOTES)));
                 
			if(isset($_POST['add'])){
                $fecha         = mysqli_real_escape_string($con,(strip_tags($_POST["fecha"],ENT_QUOTES)));//Escanpando caracteres 
                $importe  = mysqli_real_escape_string($con,(strip_tags($_POST["importe"],ENT_QUOTES)));//Escanpando caracteres 
                $concepto  = mysqli_real_escape_string($con,(strip_tags($_POST["concepto"],ENT_QUOTES)));
                $idprovedor  = mysqli_real_escape_string($con,(strip_tags($_POST["proveedor"],ENT_QUOTES)));	
                $consorcio  = mysqli_real_escape_string($con,(strip_tags($_POST["consorcio"],ENT_QUOTES)));	
				

                $sqlconsorcio = mysqli_query($con, "SELECT idconsorcio FROM consorcio WHERE nombre = '$consorcio'");
                 
                $row = mysqli_fetch_assoc($sqlconsorcio);
                $idconsorcio = $row["idconsorcio"];

                $insert = mysqli_query($con, "INSERT INTO gasto (fecha, importe, concepto, idprov, idconsorcio)
                                        VALUES('$fecha','$importe', '$concepto', '$idprovedor', '$idconsorcio')") or die(mysqli_error());
                if($insert){
                	//var_dump($id);
                	$update = mysqli_query($con, "UPDATE reclamo SET estado= 0 WHERE idreclamo='$id'") or die(mysqli_error());

					if($update){
						header("Location: pagos.php");
					}else{
						echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error, no se pudo guardar los datos.</div>';
					}
/*                    echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';*/
                }else{
                     echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
                }
			}
			?>

					<div class="col-sm-10">
						<form class="form-horizontal" action="" method="post">
							<div class="form-group">
								<label class="col-sm-6 control-label">Fecha</label>
								<div class="col-sm-4">
									<input type="text" id="fecha" name="fecha"  value="<?php echo (isset($getfecha))?$getfecha:'';?>" class="input-group date form-control" date="" data-date-format="dd-mm-yyyy"  required>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-6 control-label">Importe</label>
								<div class="col-sm-4">
									<input type="text" id="importe" name="importe" class="form-control" placeholder="Importe" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label">Concepto</label>
								<div class="col-sm-4">
									<input type="text" id="concepto" name="concepto" value="<?php echo (isset($titulo))?$titulo:'';?>" class="form-control" placeholder="Concepto" required>
								</div>	
							</div>

							<div class="form-group">
								<label class="col-sm-6 control-label">Proveedores</label>
								<div class="col-sm-4">
									<select name="proveedor" class="form-control">
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
									<input type="text" id="consorcio" name="consorcio" value="<?php echo (isset($nombreconsorcio))?$nombreconsorcio:'';?>" class="form-control" placeholder="Concepto" required>
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
