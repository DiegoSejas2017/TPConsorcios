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

	<style>
		.content {
			margin-top: 80px;
		}
	</style>
		<script type="text/javascript">
			$('ul li a').click(function(){ $('li a').removeClass("active"); $(this).addClass("active"); });

	</script> 
    <script src="http://maps.googleapis.com/maps/api/js"></script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyfFGMvKH5dxN8zxhHCjzzq3yhaf-NgbA&callback=initMap">
    </script>
</head>
<body onload="loadMap()">
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
						<li class="active"><a href="consorcios.php">Consorcios</a></li>
						<li><a href="propietarios.php">Propietarios</a></li>					
						<li><a href="pagos.php">Pagos</a></li>
						<li><a href="cobranzas.php">Cobranzas</a></li>
						<li><a href="proveedores.php">Proveedores</a></li>
						<li><a href="estadisticas.php">Estadisticas</a></li>
					<?php else : ?>						
						<li class="active"><a href="consorcios.php">Consorcios</a></li>
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
			</div><!--/.nav-collapse -->
	</div>
	</nav>
	<div class="container">
		<div class="content">
			<h2>Lista de Consorcios</h2>
			<hr />

			<?php
			if(isset($_GET['aksi']) == 'delete'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM consorcio WHERE idconsorcio='$nik'");
				if(mysqli_num_rows($cek) == 0){
					echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
				}else{
					$delete = mysqli_query($con, "DELETE FROM consorcio WHERE idconsorcio='$nik'");
					if($delete){
						echo '<div class="alert alert-success alert-dismissable"><button type="button" onclick="window.location.href="/consorcios.php" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos eliminado correctamente.</div>';
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
                  $codpostal  = mysqli_real_escape_string($con,(strip_tags($_POST["codpostal"],ENT_QUOTES)));//Escanpando caracteres 
                  $email       = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));//Escanpando caracteres  
                  $telefono      = mysqli_real_escape_string($con,(strip_tags($_POST["telefono"],ENT_QUOTES)));//Escanpando caracteres 
				  $lat      = mysqli_real_escape_string($con,(strip_tags($_POST["lat"],ENT_QUOTES)));	
				  $lng      = mysqli_real_escape_string($con,(strip_tags($_POST["lng"],ENT_QUOTES)));	
				  $direccion      = mysqli_real_escape_string($con,(strip_tags($_POST["direccion"],ENT_QUOTES)));	

                  $cek = mysqli_query($con, "SELECT * FROM consorcio WHERE nombre='$nombre'");
                  if(mysqli_num_rows($cek) == 0){
                      $insert = mysqli_query($con, "INSERT INTO consorcio(nombre, cuit, cod_postal, email_consorcio, telefono_consorcio, lat, lng, direccion)
                                        VALUES('$nombre','$cuit', '$codpostal', '$email', '$telefono','$lat','$lng', '$direccion')") or die(mysqli_error());
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
			<br />
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>
					<th>Perfil</th>
					<th>Cuit</th>
                    <th>Codigo Postal</th>
                    <th>Correo electronico</th>
					<th>Telefono</th>
					<th>Direccion</th>
                    <th>Acciones</th>
				</tr>
				<?php

					$sql = mysqli_query($con, "SELECT * FROM consorcio ORDER BY nombre ASC");
				
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
							<td><a href="profile.php?nik='.$row['idconsorcio'].'"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> '.$row['nombre'].'</a></td>
                            <td>'.$row['cuit'].'</td>
                            <td>'.$row['cod_postal'].'</td>
                            <td>'.$row['email_consorcio'].'</td>
                            <td>'.$row['telefono_consorcio'].'</td>
                            <td>'.$row['direccion'].'</td>
							<td>

								<a href="consorciosedit.php?nik='.$row['idconsorcio'].'" title="Editar datos" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<a href="consorcios.php?aksi=delete&nik='.$row['idconsorcio'].'" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['nombre'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
							</td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>

			<h2>Agregar Consorcio</h2>
			<hr />
			<div class="container">
				<div class="col-sm-6">
					<form class="form-horizontal" action="" method="post">
							<div class="form-group">
								<label class="col-sm-4 control-label">Nombre de Consorcio</label>
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
								<label class="col-sm-4 control-label">Codigo Postal</label>
								<div class="col-sm-6">
									<input type="text" id="codpostal" name="codpostal" class="form-control" placeholder="Codigo Postal" required>
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
								<label class="col-sm-4 control-label">Direccion</label>
								<div class="col-sm-6">
									<input type="text" id="direccion" name="direccion" class="form-control input-sm" placeholder="Direccion" required>
								</div>
							</div>
							<div class="form-group" style="display:none">
								<label class="col-sm-4 control-label">Direccion</label>
								<div class="col-sm-6">
									<input type="text" id="lat" name="lat" class="form-control input-sm" placeholder="Direccion" required>
									<input type="text" id="lng" name="lng" class="form-control input-sm" placeholder="Direccion" required>
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
				<div class="col-sm-6">
								<p style="font-weight:bold">Seleccioné dirección</p>
						          <div id="mapa" style="width:500px;height:400px;"></div><br>
						          <?php
						            $query = "select * from consorcio";
						            $resultado = mysqli_query($con,$query);
						          ?>

						          <script type="text/javascript">
							            var mapOptions = {
							                center:new google.maps.LatLng(-34.6686986,-58.5614947),
							                zoom:13,  zoom:13,
							                panControl: false,
							                zoomControl: true,
							                scaleControl: false,
							                mapTypeControl:false,
							                streetViewControl:true,
							                overviewMapControl:true,
							                rotateControl:true,
							                mapTypeId:google.maps.MapTypeId.ROADMAP
							            };

							            var map = new google.maps.Map(document.getElementById("mapa"),mapOptions);
							                        var geocoder = new google.maps.Geocoder();
							            var direccion;
							            var codigopostal;
							            google.maps.event.addListener(map, 'click', function(event) {
							                geocoder.geocode({
							                    'latLng': event.latLng
							                }, function(results, status) {
							                    if (status == google.maps.GeocoderStatus.OK) {
							                        if (results[0]) {	
							                            	
													    direccion = results[0].address_components[0].long_name;					                        
							                            direccion = direccion + ', ' +  results[0].address_components[1].long_name;	
							                            direccion = direccion + ', ' +  results[0].address_components[2].long_name;		
									          
							                            codigopostal =results[0].address_components[6].long_name + results[0].address_components[7].long_name;
							                            
							                            var latitude = event.latLng.lat();
							                			var longitude = event.latLng.lng();	
							                            
							                            document.getElementById("direccion").value = direccion;
							                            document.getElementById("codpostal").value = codigopostal;
							                            document.getElementById("lat").value = latitude;
							                            document.getElementById("lng").value = longitude;					      
							                        }
							                    }
							                });
							            });
							            
						            </script>
						            <?php
						              $i=1;
						              while ($data = mysqli_fetch_assoc($resultado)) {
						            ?>
						            <script type="text/javascript">

						              var marker<?php echo $i;?> = new google.maps.Marker({
						                position: new google.maps.LatLng(<?php echo $data['lat']; ?>, <?php echo $data['lng']; ?>),
						                map: map,
						                title: <?php echo "'".$data['nombre']."'"; ?>,
						                draggable:true,
						                icon: 'imagenes/logo_unlam.png',
						              });


																 
						              var contentString = "<span class='glyphicon glyphicon-asterisk' aria-hidden='true'></span>&#160;<?php echo "".$data['nombre'].""; ?><p><span class='glyphicon glyphicon-screenshot' aria-hidden='true'></span>&#160;<b>Dirección</b><br> <?php echo "".$data['direccion'].""; ?></p>"
						 
						              var infowindow<?php echo $i;?> = new google.maps.InfoWindow({
						                content: contentString
						              });

									
										google.maps.event.addListener(marker<?php echo $i;?>, 'click', function() {
											infowindow<?php echo $i;?>.open(map, this);
										});
										google.maps.event.addListener(marker<?php echo $i;?>, 'mouseout', function() {
											infowindow<?php echo $i;?>.close();	
										});
						             
						            </script>
							        <?php
						                $i++;
						              }
						              mysqli_close($con);
						            ?>
				</div>
				
			</div>
		
		</div>
	</div><center>
	<p>&copy; Sistemas Web <?php echo date("Y");?></p
		</center>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
			<script type="text/javascript">
						$("#email").on("keypress", function() {
			    var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{2,4}/.test(this.value);
			    if(!re) {
			        $("#error").show();
			    } else {
			        $("#error").hide();
			    }
			})
		</script>
</body>
</html>