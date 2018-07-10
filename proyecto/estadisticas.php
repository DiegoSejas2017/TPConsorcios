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

<!-- 	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet"> -->

	<style>
		.content {
			margin-top: 80px;
		}
	</style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="jquery.table2excel.js"></script>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>

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
						<li><a href="proveedores.php">Proveedores</a></li>
						<li class="active"><a href="estadisticas.php">Estadisticas</a></li>
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
			</div>
	</div>
	</nav>
	<div class="container">
		<div class="content">
			<h2>Estadisticas</h2>
			<hr />

			<?php
			if(isset($_GET['aksi']) == 'delete'){				
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM empleados WHERE codigo='$nik'");
				if(mysqli_num_rows($cek) == 0){
					echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
				}else{
					$delete = mysqli_query($con, "DELETE FROM empleados WHERE codigo='$nik'");
					if($delete){
						echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos eliminado correctamente.</div>';
					}else{
						echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudo eliminar los datos.</div>';
					}
				}
			}
			?>

			<form class="form-inline" method="get">
				<div class="form-group">
					<select name="filter" class="form-control" onchange="form.submit()">
						<option value="0">Filtros de datos de empleados</option>
						<?php $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);  ?>
						<option value="1" <?php if($filter == 'Tetap'){ echo 'selected'; } ?>>Fijo</option>
						<option value="2" <?php if($filter == 'Kontrak'){ echo 'selected'; } ?>>Contratado</option>
                        <option value="3" <?php if($filter == 'Outsourcing'){ echo 'selected'; } ?>>Outsourcing</option>
					</select>
				</div>
			</form>
			<h4>Detalle</h4>			
			<div class="table-responsive">
				<p><button id="btn-export" class="btn btn-default">Exportar</button></p>
			<table 	id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>					
						<th>Consorcio</th>
	                    <th>Unidades</th>
	                    <th>Cobranzas Realizadas</th>
	                    <th>Cobranzas Pendientes</th>
	                    <th>Reclamos Abiertos</th>
	                    <th>Reclamos Cerrados</th>
					</tr>
				</thead>
			      <tbody>
				<?php
				if($filter){
					$sql = mysqli_query($con, "SELECT sum(a.importe) as pagado,(SELECT sum(importe) from expensa 	where idliquidacion = a.idliquidacion) as total,
						   ((SELECT sum(importe) from expensa where idliquidacion = a.idliquidacion) - sum(a.importe) ) as deuda,  c.nombre as consorcio, b.fecha
					  		   FROM pago a JOIN
									liquidacion b on a.idliquidacion = a.idliquidacion join 
					        		consorcio c on b.idconsorcio = c.idconsorcio
					        		WHERE b.idconsorcio = '$filter'
					        		group by c.nombre, b.fecha							 
							");
				}else{
					$sql = mysqli_query($con, 
							"SELECT COUNT(b.idPropietario) as Unidades, a.nombre, 
								 	  (select COUNT(*) from liquidacion where idconsorcio = a.idconsorcio ) as cobranzas, 
								  	  (select COUNT(*) from gasto where idgasto not in ( SELECT idgasto FROM gasto t join 
								         liquidacion u on YEAR(t.fecha) = YEAR(u.fecha) and MONTH(t.fecha) = MONTH(u.fecha) 
								         group by idgasto)) as pendientes,
								      (SELECT COUNT(*) from reclamo where estado = 1 and Idconsorcio = a.idconsorcio) as abiertos,
								      (SELECT COUNT(*) from reclamo where estado = 0 and Idconsorcio = a.idconsorcio) as cerrados
								 from consorcio a JOIN 
								 	  propietarios b on a.idconsorcio = b.idconsorcio 
								 group by a.idconsorcio
								");
				}
											
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
                            <td>'.$row['nombre'].'</td>
                            <td>'.$row['Unidades'].'</td>
                            <td>'.$row['cobranzas'].'</td>
                            <td>'.$row['pendientes'].'</td>
                            <td>'.$row['abiertos'].'</td>
                            <td>'.$row['cerrados'].'</td>
						</tr>
						';
						$no++;
					}
				}
				?>
				</tbody>
			</table>
			</div>	
	</div><center>
	<p>&copy; Sistemas Web <?php echo date("Y");?></p
		</center>
<!-- 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script> -->
</body>
</html>
