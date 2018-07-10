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
					<li><a href="cobranzas.php">Volver</a></li>
				</ul>
			</div><!--/.nav-collapse -->
	</div>
	</nav>
	<div class="container">
		<div class="content">
			<h2>Detalle de cobranza</h2>
			<hr />
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>
					<th>Importe</th>
					<th>Consorcio</th>
                    <th>Propietarios</th>
                    <th>Departamento</th>
                    <th>Expensas</th>
				</tr>
				<?php
                $idconsorcio  = mysqli_real_escape_string($con,(strip_tags($_GET["idconsorcio"],ENT_QUOTES)));
                $fecha  = mysqli_real_escape_string($con,(strip_tags($_GET["fecha"],ENT_QUOTES)));
                $año = substr($fecha,0,4);
        
                $mes = substr($fecha,5,2);

				$sql = mysqli_query($con, "SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio, 
							d.nombre as propietario,concat(d.piso, d.departamento) as depto, 
							round((((round((sum((a.importe * 20) / 100)),2) + sum(a.importe)) * d.porparticip) / 100), 2) as porcentaje 
							from gasto a join proveedor b on a.idprov = b.idprov JOIN 
							     consorcio c on a.idconsorcio = c.idconsorcio join 
								 propietarios d on a.idconsorcio = d.idconsorcio 
								 where YEAR(a.fecha) = '$año' AND MONTH(a.fecha) = '$mes'
								 and c.idconsorcio = '$idconsorcio'
								 group by c.nombre, d.nombre
					");
				

											
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
                            <td>$'.$row['importe'].'</td>
                            <td>'.$row['consorcio'].'</td>
                            <td>'.$row['propietario'].'</td>
                            <td>'.$row['depto'].'</td>
                            <td>$'.$row['porcentaje'].'</td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>
	</div>
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