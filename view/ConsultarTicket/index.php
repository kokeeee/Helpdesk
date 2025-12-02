<?php
  require_once("../../config/conexion.php"); 
  if(!isset($_SESSION["usu_id"])) {
    header("Location: ../error404.php?reason=not_logged_in");
    exit();
  }
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
	<title>Consultar Ticket</title>
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
						<h3 id="titulo-pagina">Consultar Ticket</h3>
						<ol class="breadcrumb breadcrumb-simple">
							<li><a href="#">Home</a></li>
							<li class="active" id="breadcrumb-actual">Consultar Ticket</li>
						</ol>
					</div>
				</div>
			</div>
		</header>

		<div class="box-typical box-typical-padding">
			<table id="ticket_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
				<thead>
					<tr>
						<th style="width: 1%;">Nro.Ticket</th>
						<th style="width: 15%;">Categoria</th>
						<th class="d-none d-sm-table-cell" style="width: 20%;">Asunto</th>
						<th class="d-none d-sm-table-cell" style="width: 5%;">Respuestas</th>
						<th class="d-none d-sm-table-cell" style="width: 5%;">Estado</th>
						<th class="d-none d-sm-table-cell" style="width: 5%;">Ticket administrado por:</th>
						<th class="d-none d-sm-table-cell" style="width: 5%;">Fecha de Creación</th>
						<th class="d-none d-sm-table-cell" style="width: 1%;">Ver</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>		</div>
	</div>
	<!-- Contenido -->

	<?php require_once("../MainJs/js.php");?>

	<script type="text/javascript" src="consultarticket.js"></script>

</body>
</html>