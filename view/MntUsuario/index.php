<?php
  require_once("../../config/conexion.php"); 
  if(!isset($_SESSION["usu_id"])) {
    header("Location: ../error404.php?reason=not_logged_in");
    exit();
  }
  if($_SESSION["rol_id"] != 3) {
    header("Location: ../error404.php?reason=no_permission");
    exit();
  }
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
	<title>Control de Usuarios</title>
</head>
<body class="with-side-menu">

    <?php require_once("../MainHeader/header.php");?>

    <div class="mobile-menu-left-overlay"></div>
    
    <?php require_once("../MainNav/navbar.php");?>

	<!-- Contenido -->
	<div class="page-content">
		<div class="container-fluid">

			<header class="section-header">
				<div class="tbl">
					<div class="tbl-row">
						<div class="tbl-cell">
							<h3>Control de Usuarios</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="#">Home</a></li>
								<li class="active">Control de Usuarios</li>
							</ol>
						</div>
					</div>
				</div>
			</header>

		<div class="box-typical box-typical-padding">
			<div style="margin-bottom: 20px;">
				<button type="button" id="btnnuevo" class="btn btn-primary" data-toggle="modal" data-target="#usuarioModal">
					<i class="fa fa-plus"></i> Crear nuevo usuario
				</button>
			</div>
			<table id="usuario_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
				<thead>
					<tr>
						<th style="width: 15%;">Nombre</th>
						<th style="width: 15%;">Apellido</th>
						<th class="d-none d-sm-table-cell" style="width: 15%;">Correo</th>
						<th class="d-none d-sm-table-cell" style="width: 10%;">Rol</th>
						<th class="d-none d-sm-table-cell" style="width: 10%;">Contraseña</th>
						<th class="d-none d-sm-table-cell" style="width: 10%;">Estado</th>
						<th class="d-none d-sm-table-cell" style="width: 15%;">Fecha de Creación</th>
						<th class="text-center" style="width: 5%;"></th>
						<th class="text-center" style="width: 5%;"></th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>		</div>
	</div>
	<!-- Contenido -->

	<?php require_once("modalnuevo.php");?>

	<?php require_once("../MainJs/js.php");?>

	<script type="text/javascript" src="mntUsuario.js"></script>

</body>
</html>