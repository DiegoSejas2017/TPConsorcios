<?php
include("conexion.php");
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
					<li class="active"><a href="#">Gestion Propietarios</a></li>					
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
			<h2>Gestion Propietarios</h2>
			<hr />
			<h4>Deudas pendientes</h4>
			<?php
			$id = mysqli_real_escape_string($con,(strip_tags($_GET["id"],ENT_QUOTES)));

			if(isset($_GET['aksi']) == 'delete'){				
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

			if(isset($_POST['addreclamo'])){
                  $titulo  = mysqli_real_escape_string($con,(strip_tags($_POST["titulo"],ENT_QUOTES)));
                  $descripcion  = mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));
				
            	  $sql = mysqli_query($con, "SELECT * FROM propietarios WHERE email = '$id'");
            	  $row = mysqli_fetch_assoc($sql);

                  $insert = mysqli_query($con, "INSERT INTO reclamo(fecha, titulo, descripcion, estado, idPropietario, idconsorcio)
                                        VALUES(CURDATE(),'$titulo', '$descripcion', '1', '$row[idPropietario]', '$row[idconsorcio]')") or die(mysqli_error());
                  if($insert){
                        echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
                  }else{
                        echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
                  }

			}
			?>
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>
					<th>Consorcio</th>
					<th>Propietario</th>
                    <th>Importe</th>
                    <th>Fecha de emisión</th>
					<th>Fecha de Vencimiento</th>					
                    <th>Pagar</th>
				</tr>
				<?php
			
					$sql = mysqli_query($con, "
						SELECT a.idexpensa,a.idliquidacion, a.importe, a.fecha, a.fechavenc, b.nombre as propietario,
						  (select nombre from consorcio where Idconsorcio = c.idconsorcio) as consorcio,  a.idPropietario
					  FROM expensa a join 
					  propietarios b on a.idPropietario = b.idPropietario join 
					   liquidacion c on a.idliquidacion = c.idliquidacion 
					  where b.email = '$id'
					   and a.idliquidacion not in( select idliquidacion from pago where idpropietario = a.idPropietario)
	   				 order by a.fecha");
				
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>							
							<td>'.$row['consorcio'].'</td>
                            <td>'.$row['propietario'].'</td>
                            <td>$'.$row['importe'].'</td>
                            <td>'.$row['fecha'].'</td>
                            <td>'.$row['fechavenc'].'</td>
							<td>
								<a href="pagar.php?idliquidacion='.$row['idliquidacion'].'&idPropietario='.$row['idPropietario'].'&importe='.$row['importe'].'&id='.$id.'" title="Editar datos" style="font-size:20px;color:red"><span class="fa fa-credit-card" aria-hidden="true"></span></a>
							</td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>
		
			<h4>Reclamos efectuados</h4>
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>
                    <th>Nro</th>
					<th>Titulo</th>
					<th>Descripcion</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
				</tr>
				<?php
				
				$sql = mysqli_query($con, "SELECT * FROM reclamo ORDER BY fecha ASC");
				
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
							<td>'.$no.'</td>
							<td>'.$row['titulo'].'</td>
                            <td>'.$row['descripcion'].'</td>
                            <td>'.$row['fecha'].'</td>
							<td>';
							if($row['estado'] == '1'){
								echo '<span class="label label-success">Pendiente</span>';
							}
                            else if ($row['estado'] == '0' ){
								echo '<span class="label label-info">Cerrado</span>';
							}                           
								echo '
							</td>                            
							<td>
								<a href="edit.php?nik='.$row['idreclamo'].'" title="Editar datos" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<a href="index.php?aksi=delete&nik='.$row['idreclamo'].'" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['idreclamo'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
							</td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>

			<h2>Agregar Reclamo</h2>
			<hr />
			<form class="form-horizontal" action="" method="post">
				<div class="form-group">
					<label class="col-sm-3 control-label">Titulo de reclamo</label>
					<div class="col-sm-2">
						<input type="text" name="titulo" class="form-control" placeholder="Titulo de reclamo" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Descripcion</label>
					<div class="col-sm-4">
						<textarea name="descripcion" class="form-control" rows="5" placeholder="Escriba la descripcion de su reclamo" required> </textarea>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="addreclamo" class="btn btn-sm btn-primary" value="Guardar Reclamo">
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
