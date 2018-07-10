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
				 <?php
					$iduser = mysqli_real_escape_string($con,(strip_tags($_GET["id"],ENT_QUOTES)));
					echo '<li><a href="gestionpropietarios.php?id='.$iduser.'">Volver</a></li>' ;?>
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
    		$idliquidacion = mysqli_real_escape_string($con,(strip_tags($_GET["idliquidacion"],ENT_QUOTES)));
    		$idPropietario = mysqli_real_escape_string($con,(strip_tags($_GET["idPropietario"],ENT_QUOTES)));
    		$importe = mysqli_real_escape_string($con,(strip_tags($_GET["importe"],ENT_QUOTES)));
    		
            $sql = mysqli_query($con, "SELECT a.nombre, b.nombre as consorcio 
            							 FROM propietarios a join 
										      consorcio b on a.idconsorcio = b.idconsorcio
										where idPropietario = '$idPropietario'");
                 
            $row = mysqli_fetch_assoc($sql);
            $propietario = $row["nombre"];
			$consorcio = $row["consorcio"];

			if(isset($_POST['add'])){
                $propietario = mysqli_real_escape_string($con,(strip_tags($_POST["propietario"],ENT_QUOTES)));//
                $importe  = mysqli_real_escape_string($con,(strip_tags($_POST["importe"],ENT_QUOTES)));   
                $importe = substr($importe, 1);           
                $medio  = mysqli_real_escape_string($con,(strip_tags($_POST["medio"],ENT_QUOTES)));	

                $insert = mysqli_query($con, "INSERT INTO pago (idliquidacion, idPropietario, importe, fecha, metodopago)
                                        VALUES('$idliquidacion','$idPropietario', '$importe', now(), '$medio')") or die(mysqli_error());
                if($insert){
                    echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
                }else{
                     echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
                }
			}
			?>

					<div class="col-sm-10">
						<form class="form-horizontal" action="" method="post">
							<div class="form-group">
								<label class="col-sm-6 control-label">Propietario</label>
								<div class="col-sm-4">
									<input type="text" id="propietario" name="propietario"  value="<?php echo (isset($propietario))?$propietario:'';?>" class="input-group date form-control" date="" data-date-format="dd-mm-yyyy"  required>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-6 control-label">Importe</label>
								<div class="col-sm-4">
									<input type="text" id="importe" name="importe" value="$<?php echo (isset($importe))?$importe:'';?>" name="importe" class="form-control" placeholder="Importe" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label">Consorcio</label>
								<div class="col-sm-4">
									<input type="text" id="consorcio" name="consorcio" value="<?php echo (isset($consorcio))?$consorcio:'';?>" class="form-control" placeholder="Concepto" required>
								</div>	
							</div>

							<div class="form-group">
								<label class="col-sm-6 control-label">Medio De pago</label>
								<div class="col-sm-4">
									<select name="medio" class="form-control">
										<option value=""> ----- </option>
			                           <option value="Efectivo">Efectivo</option>
										<option value="Transferencia">Transferencia</option>
										<option value="MercadoPago">MercadoPago</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-8 col-sm-offset-6">
									<input type="submit" name="add" class="btn btn-sm btn-primary" value="Pagar">
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
