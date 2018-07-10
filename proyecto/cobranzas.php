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
						<li class="active"><a href="cobranzas.php">Cobranzas</a></li>
						<li><a href="proveedores.php">Proveedores</a></li>
						<li><a href="estadisticas.php">Estadisticas</a></li>
					<?php else : ?>						
						<li><a href="consorcios.php">Consorcios</a></li>
						<li><a href="propietarios.php">Propietarios</a></li>					
						<li><a href="pagos.php">Pagos</a></li>
						<li class="active"><a href="cobranzas.php">Cobranzas</a></li>
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
			<h2>Cobranzas</h2>
			<hr />

			<?php

            if(isset($_POST['generar'])){

				$sql = mysqli_query($con, "SELECT round((sum((importe * 20) / 100)),2) + sum(importe) as importe ,idconsorcio, (select CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE)) as fecha from gasto");
                                
                $row = mysqli_fetch_assoc($sql);

                $importe = $row["importe"];
                $idconsorcio = $row["idconsorcio"];
				$fecha = $row["fecha"];
				//var_dump($fecha);
                $insert = mysqli_query($con, "INSERT INTO liquidacion (fecha, idconsorcio)
                                        VALUES('$fecha' , '$idconsorcio')") or die(mysqli_error());
                if($insert){
                	$sql = mysqli_query($con, "SELECT idliquidacion from liquidacion order by idliquidacion desc LIMIT 1");
                                
	                $row = mysqli_fetch_assoc($sql);

	                $idliquidacion = $row["idliquidacion"];

                	$sql = mysqli_query($con, "SELECT d.idPropietario as propietario,
							round((((round((sum((a.importe * 20) / 100)),2) + sum(a.importe)) * d.porparticip) / 100), 2) as expensa 
							from gasto a join proveedor b on a.idprov = b.idprov JOIN 							     
								 propietarios d on a.idconsorcio = d.idconsorcio group by d.nombre
							");

						while($row = mysqli_fetch_assoc($sql)){
						
							$expensa = $row["expensa"];			                
							$idPropietario = $row["propietario"];											
							
			                mysqli_query($con, "INSERT INTO expensa (idliquidacion, idPropietario, importe,fecha,fechavenc)
			                                        VALUES('$idliquidacion','$idPropietario' ,'$expensa' ,now(), (SELECT DATE_ADD(now(), INTERVAL 10 DAY)))") or die(mysqli_error());	
			                
						}

                    	echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Liquidación generada exitosamente.</div>';
                }else{
                     echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
                }


            }
            if(isset($_GET['accion']) == 'generar'){

                $importe         = mysqli_real_escape_string($con,(strip_tags($_GET["importe"],ENT_QUOTES))); 
                $idconsorcio  = mysqli_real_escape_string($con,(strip_tags($_GET["idconsorcio"],ENT_QUOTES)));
                $fecha  = mysqli_real_escape_string($con,(strip_tags($_GET["fecha"],ENT_QUOTES)));

                $año = substr($fecha,0,4);
        
                $mes = substr($fecha,5,2);

                $insert = mysqli_query($con, "INSERT INTO liquidacion (fecha, idconsorcio)
                                        VALUES('$fecha' , '$idconsorcio')") or die(mysqli_error());
                if($insert){
                	$sql = mysqli_query($con, "SELECT idliquidacion from liquidacion order by idliquidacion desc LIMIT 1");
                                
	                $row = mysqli_fetch_assoc($sql);

	                $idliquidacion = $row["idliquidacion"];

                	$sql = mysqli_query($con, "SELECT d.idPropietario as propietario,
							round((((round((sum((a.importe * 20) / 100)),2) + sum(a.importe)) * d.porparticip) / 100), 2) as expensa 
							from gasto a join proveedor b on a.idprov = b.idprov JOIN 							     
								 propietarios d on a.idconsorcio = d.idconsorcio 
								 where YEAR(a.fecha) = '$año' AND MONTH(a.fecha) = '$mes'
								 group by d.nombre
							");

						while($row = mysqli_fetch_assoc($sql)){
						
							$expensa = $row["expensa"];			                
							$idPropietario = $row["propietario"];											
				
			                mysqli_query($con, "INSERT INTO expensa (idliquidacion, idPropietario, importe,fecha,fechavenc)
			                                        VALUES('$idliquidacion','$idPropietario' ,'$expensa' ,(SELECT (DATE_SUB(LAST_DAY(DATE_ADD('$fecha', INTERVAL 1 MONTH)), 
    INTERVAL DAY(LAST_DAY(DATE_ADD('$fecha', INTERVAL 1 MONTH)))-1 DAY))), (SELECT (SELECT DATE_ADD((DATE_SUB(LAST_DAY(DATE_ADD('$fecha', INTERVAL 1 MONTH)), 
    INTERVAL DAY(LAST_DAY(DATE_ADD('$fecha', INTERVAL 1 MONTH)))-1 DAY)),INTERVAL 10 DAY))))") or die(mysqli_error());	
			                
						}

                    	echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Liquidación generada exitosamente.</div>';
                }else{
                     echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
                }


            }

			?>
<!-- 			<form class="form-inline" method="POST">
				<div class="form-group">
					<button id="generar" name="generar" class="btn btn-info center btn-sm pull-right">Generar Liquidación</button>
				</div>
			</form>
			<br /> -->
	
			<h3>Liquidación por consorcio</h3>			
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>					
					<th>Consorcio</th>
                    <th>Periodo</th>
                    <th>Saldo</th>
                    <th>Pagado</th>
                    <th>Deuda</th>
                    <th>Detalle</th>
				</tr>
				<?php
				
					$sql = mysqli_query($con, 
							"SELECT sum(a.importe) as total,
									ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) as pagado, 
									(sum(a.importe) - ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) ) as deuda,
									(select '2018-01-01') as fecha, 
									(select nombre from consorcio where idconsorcio = (SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion)) as consorcio,
									(SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion) as idconsorcio
									FROM expensa a 
							  where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 2 
						      group by a.idliquidacion
						      union all
						      SELECT sum(a.importe) as total,
									ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) as pagado, 
									(sum(a.importe) - ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) ) as deuda,
									(select '2018-02-01') as fecha, 
									(select nombre from consorcio where idconsorcio = (SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion)) as consorcio,
									(SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion) as idconsorcio
									FROM expensa a 
							  where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 3 
						      group by a.idliquidacion
						      union all
						      SELECT sum(a.importe) as total,
									ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) as pagado, 
									(sum(a.importe) - ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) ) as deuda,
									(select '2018-03-01') as fecha, 
									(select nombre from consorcio where idconsorcio = (SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion)) as consorcio,
									(SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion) as idconsorcio
									FROM expensa a 
							  where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 4
						      group by a.idliquidacion
						      union all
						      SELECT sum(a.importe) as total,
									ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) as pagado, 
									(sum(a.importe) - ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) ) as deuda,
									(select '2018-04-01') as fecha, 
									(select nombre from consorcio where idconsorcio = (SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion)) as consorcio,
									(SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion) as idconsorcio
									FROM expensa a 
							  where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 5 
						      group by a.idliquidacion
						      union all
						      SELECT sum(a.importe) as total,
									ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) as pagado, 
									(sum(a.importe) - ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) ) as deuda,
									(select '2018-05-01') as fecha, 
									(select nombre from consorcio where idconsorcio = (SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion)) as consorcio,
									(SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion) as idconsorcio
									FROM expensa a 
							  where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 6 
						      group by a.idliquidacion
						      union all
						      SELECT sum(a.importe) as total,
									ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) as pagado, 
									(sum(a.importe) - ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) ) as deuda,
									(select '2018-06-01') as fecha, 
									(select nombre from consorcio where idconsorcio = (SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion)) as consorcio,
									(SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion) as idconsorcio
									FROM expensa a 
							  where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 7 
						      group by a.idliquidacion
						      union all
						      SELECT sum(a.importe) as total,
									ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) as pagado, 
									(sum(a.importe) - ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) ) as deuda,
									(select '2018-07-01') as fecha, 
									(select nombre from consorcio where idconsorcio = (SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion)) as consorcio,
									(SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion) as idconsorcio
									FROM expensa a 
							  where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 8 
						      group by a.idliquidacion
						      union all
						      SELECT sum(a.importe) as total,
									ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) as pagado, 
									(sum(a.importe) - ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) ) as deuda,
									(select '2018-08-01') as fecha, 
									(select nombre from consorcio where idconsorcio = (SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion)) as consorcio,
									(SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion) as idconsorcio
									FROM expensa a 
							  where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 9 
						      group by a.idliquidacion
						      union all
						      SELECT sum(a.importe) as total,
									ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) as pagado, 
									(sum(a.importe) - ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) ) as deuda,
									(select '2018-09-01') as fecha, 
									(select nombre from consorcio where idconsorcio = (SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion)) as consorcio,
									(SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion) as idconsorcio
									FROM expensa a 
							  where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 10 
						      group by a.idliquidacion
						      union all
						      SELECT sum(a.importe) as total,
									ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) as pagado, 
									(sum(a.importe) - ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) ) as deuda,
									(select '2018-10-01') as fecha, 
									(select nombre from consorcio where idconsorcio = (SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion)) as consorcio,
									(SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion) as idconsorcio
									FROM expensa a 
							  where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 11
						      group by a.idliquidacion
						      union all
						      SELECT sum(a.importe) as total,
									ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) as pagado, 
									(sum(a.importe) - ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) ) as deuda,
									(select '2018-11-01') as fecha, 
									(select nombre from consorcio where idconsorcio = (SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion)) as consorcio,
									(SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion) as idconsorcio
									FROM expensa a 
							  where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 12 
						      group by a.idliquidacion
						      union all
						      SELECT sum(a.importe) as total,
									ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) as pagado, 
									(sum(a.importe) - ifnull((SELECT sum(importe) from pago where idliquidacion = a.idliquidacion),0) ) as deuda,
									(select '2018-12-01') as fecha, 
									(select nombre from consorcio where idconsorcio = (SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion)) as consorcio,
									(SELECT idconsorcio from liquidacion where idliquidacion = a.idliquidacion) as idconsorcio
									FROM expensa a 
							  where YEAR(a.fecha) = 2019 AND MONTH(a.fecha) = 1 
						      group by a.idliquidacion						
								");
				
											
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
                            <td>'.$row['consorcio'].'</td>
                            <td>'.$row['fecha'].'</td>
                            <td>$'.$row['total'].'</td>
                            <td>$'.$row['pagado'].'</td>
                            <td>$'.$row['deuda'].'</td>
							<td>
								<a href="detalleCobranzas.php?fecha='.$row['fecha'].'&idconsorcio='.$row['idconsorcio'].'" title="Ver Detalle" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span></a>								
							</td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>
			<h3>Pendientes de generar liquidación</h3>
			<div class="table-responsive">				
			<table class="table table-striped table-hover">
				<tr>					
					<th>Consorcio</th>
                    <th>Periodo</th>
                    <th>Saldo</th>
                    <th>Detalle</th>
                    <th>Generar Liquidación</th>
				</tr>
				<?php
				$sql = mysqli_query($con, "SET lc_time_names = 'es_AR'");
				$sql = mysqli_query($con, 
							"
							SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio ,(SELECT DATE_FORMAT(a.fecha,'%M %Y')) as fecha,(select CAST(DATE_FORMAT(a.fecha ,'%Y-%m-01') as DATE)) as datofecha,c.idconsorcio
							from gasto a join consorcio c on a.idconsorcio = c.idconsorcio and a.Idconsorcio not in (select Idconsorcio from liquidacion where YEAR(fecha) = 2018 AND MONTH(fecha) = 1)
							where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 1 
	
							group by c.nombre 
							union all 
							SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio ,(SELECT DATE_FORMAT(a.fecha,'%M %Y')) as fecha,(select CAST(DATE_FORMAT(a.fecha ,'%Y-%m-01') as DATE)) as datofecha,c.idconsorcio
							from gasto a join consorcio c on a.idconsorcio = c.idconsorcio  not in (select Idconsorcio from liquidacion where YEAR(fecha) = 2018 AND MONTH(fecha) = 2)
							where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 2 
	
							group by c.nombre 
							union all 
							SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio ,(SELECT DATE_FORMAT(a.fecha,'%M %Y')) as fecha,(select CAST(DATE_FORMAT(a.fecha ,'%Y-%m-01') as DATE)) as datofecha,c.idconsorcio
							from gasto a join consorcio c on a.idconsorcio = c.idconsorcio and a.Idconsorcio not in (select Idconsorcio from liquidacion where YEAR(fecha) = 2018 AND MONTH(fecha) = 3)
							where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 3 
							group by c.nombre 
							union all 
							SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio ,(SELECT DATE_FORMAT(a.fecha,'%M %Y')) as fecha,(select CAST(DATE_FORMAT(a.fecha ,'%Y-%m-01') as DATE)) as datofecha,c.idconsorcio
							from gasto a join consorcio c on a.idconsorcio = c.idconsorcio and a.Idconsorcio not in (select Idconsorcio from liquidacion where YEAR(fecha) = 2018 AND MONTH(fecha) = 4)
							where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 4 
					
							group by c.nombre 
							union all
							SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio ,(SELECT DATE_FORMAT(a.fecha,'%M %Y')) as fecha,(select CAST(DATE_FORMAT(a.fecha ,'%Y-%m-01') as DATE)) as datofecha,c.idconsorcio
							from gasto a join consorcio c on a.idconsorcio = c.idconsorcio and a.Idconsorcio not in (select Idconsorcio from liquidacion where YEAR(fecha) = 2018 AND MONTH(fecha) = 5)
							where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 5 
	
							group by c.nombre 
							union all 
							SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio,(SELECT DATE_FORMAT(a.fecha,'%M %Y')) as fecha ,(select CAST(DATE_FORMAT(a.fecha ,'%Y-%m-01') as DATE)) as datofecha,c.idconsorcio
							from gasto a join consorcio c on a.idconsorcio = c.idconsorcio and a.Idconsorcio not in (select Idconsorcio from liquidacion where YEAR(fecha) = 2018 AND MONTH(fecha) = 6)
							where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 6 
	
							group by c.nombre 
							union all 
							SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio ,(SELECT DATE_FORMAT(a.fecha,'%M %Y')) as fecha,(select CAST(DATE_FORMAT(a.fecha ,'%Y-%m-01') as DATE)) as datofecha,c.idconsorcio
							from gasto a join consorcio c on a.idconsorcio = c.idconsorcio and a.Idconsorcio not in (select Idconsorcio from liquidacion where YEAR(fecha) = 2018 AND MONTH(fecha) = 7)
							where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 7 
						
							group by c.nombre 
							union all 
							SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio ,(SELECT DATE_FORMAT(a.fecha,'%M %Y')) as fecha,(select CAST(DATE_FORMAT(a.fecha ,'%Y-%m-01') as DATE)) as datofecha,c.idconsorcio
							from gasto a join consorcio c on a.idconsorcio = c.idconsorcio and a.Idconsorcio not in (select Idconsorcio from liquidacion where YEAR(fecha) = 2018 AND MONTH(fecha) = 8)
							where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 8 
	
							group by c.nombre 
							union all 
							SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio ,(SELECT DATE_FORMAT(a.fecha,'%M %Y')) as fecha,(select CAST(DATE_FORMAT(a.fecha ,'%Y-%m-01') as DATE)) as datofecha,c.idconsorcio
							from gasto a join consorcio c on a.idconsorcio = c.idconsorcio and a.Idconsorcio not in (select Idconsorcio from liquidacion where YEAR(fecha) = 2018 AND MONTH(fecha) = 9)
							where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 9 
			
							group by c.nombre 
							union all 
							SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio ,(SELECT DATE_FORMAT(a.fecha,'%M %Y')) as fecha,(select CAST(DATE_FORMAT(a.fecha ,'%Y-%m-01') as DATE)) as datofecha,c.idconsorcio
							from gasto a join consorcio c on a.idconsorcio = c.idconsorcio and a.Idconsorcio not in (select Idconsorcio from liquidacion where YEAR(fecha) = 2018 AND MONTH(fecha) = 10)
							where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 10 
					
							group by c.nombre
							union all 
							SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio ,(SELECT DATE_FORMAT(a.fecha,'%M %Y')) as fecha,(select CAST(DATE_FORMAT(a.fecha ,'%Y-%m-01') as DATE)) as datofecha,c.idconsorcio
							from gasto a join consorcio c on a.idconsorcio = c.idconsorcio and a.Idconsorcio not in (select Idconsorcio from liquidacion where YEAR(fecha) = 2018 AND MONTH(fecha) = 11)
							where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 11 
					
							group by c.nombre 
							union all 
							SELECT round((sum((a.importe * 20) / 100)),2) + sum(a.importe) as importe ,c.nombre as consorcio ,(SELECT DATE_FORMAT(a.fecha,'%M %Y')) as fecha,(select CAST(DATE_FORMAT(a.fecha ,'%Y-%m-01') as DATE)) as datofecha,c.idconsorcio
							from gasto a join consorcio c on a.idconsorcio = c.idconsorcio and a.Idconsorcio not in (select Idconsorcio from liquidacion where YEAR(fecha) = 2018 AND MONTH(fecha) = 12)
							where YEAR(a.fecha) = 2018 AND MONTH(a.fecha) = 12
					
							group by c.nombre  ");					
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
                            <td>'.$row['consorcio'].'</td>
                            <td>'.$row['fecha'].'</td>
                            <td>$'.$row['importe'].'</td>
							<td>
								<a href="pagar.php?nik='.$row['consorcio'].'" title="Editar datos" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span></a>
													
							</td>
							<td>
								<a href="cobranzas.php?accion=generar&fecha='.$row['datofecha'].'&idconsorcio='.$row['idconsorcio'].'&importe='.$row['importe'].'" title="Generar Liquidación" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></a>	
							</td>
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