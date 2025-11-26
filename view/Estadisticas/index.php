<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usu_id"]) && $_SESSION["rol_id"] == 2){ 
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
	<title>Estadísticas</title>
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
							<h3>Estadísticas del Equipo de Soporte</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="#">Home</a></li>
								<li class="active">Estadísticas</li>
							</ol>
						</div>
					</div>
				</div>
			</header>

			<div class="row">
				<div class="col-xl-12">
					<div class="row">
						<div class="col-sm-4">
	                        <article class="statistic-box green">
	                            <div>
	                                <div class="number" id="total_cerrados"></div>
	                                <div class="caption"><div>Tickets Cerrados</div></div>
	                            </div>
	                        </article>
	                    </div>
						<div class="col-sm-4">
	                        <article class="statistic-box yellow">
	                            <div>
	                                <div class="number" id="total_abiertos"></div>
	                                <div class="caption"><div>Tickets Abiertos</div></div>
	                            </div>
	                        </article>
	                    </div>
						<div class="col-sm-4">
	                        <article class="statistic-box purple">
	                            <div>
	                                <div class="number" id="porcentaje_cerrados"></div>
	                                <div class="caption"><div>% Tickets Cerrados</div></div>
	                            </div>
	                        </article>
	                    </div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">
					<section class="card">
						<header class="card-header">
							<h2>Tickets Cerrados por Personal (Total)</h2>
						</header>
						<div class="card-block">
							<table id="soporte_cerrados_table" class="table table-bordered table-striped table-vcenter">
								<thead>
									<tr>
										<th>Personal</th>
										<th>Tickets Cerrados</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</section>
				</div>

				<div class="col-lg-6">
					<section class="card">
						<header class="card-header">
							<h2>Tickets Cerrados por Personal (Este Mes)</h2>
						</header>
						<div class="card-block">
							<table id="soporte_cerrados_mes_table" class="table table-bordered table-striped table-vcenter">
								<thead>
									<tr>
										<th>Personal</th>
										<th>Tickets Cerrados</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</section>
				</div>
			</div>

		<div class="row">
			<div class="col-lg-6">
				<section class="card">
					<header class="card-header">
						<h2>Distribución de Tickets por Categoría</h2>
					</header>
					<div class="card-block">
						<div id="grafico_categorias" style="height: 300px;"></div>
					</div>
				</section>
			</div>

			<div class="col-lg-6">
				<section class="card">
					<header class="card-header">
						<h2>Distribución de Tickets por Estado</h2>
					</header>
					<div class="card-block">
						<div id="grafico_estado" style="height: 300px;"></div>
					</div>
				</section>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<section class="card">
					<header class="card-header">
						<h2>Tiempo Promedio de Resolución</h2>
					</header>
					<div class="card-block">
						<div style="text-align: center; padding: 20px;">
							<div style="font-size: 36px; font-weight: bold; color: #1AB244;">
								<span id="promedio_horas">0</span> hrs
							</div>
							<div style="font-size: 14px; color: #666; margin-top: 10px;">
								(<span id="promedio_dias">0</span> días aproximadamente)
							</div>
						</div>
					</div>
				</section>
			</div>

			<div class="col-lg-8">
				<section class="card">
					<header class="card-header">
						<h2>Carga de Tickets (Últimos 7 días)</h2>
					</header>
					<div class="card-block">
						<div id="grafico_carga" style="height: 300px;"></div>
					</div>
				</section>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<section class="card">
					<header class="card-header">
						<h2>Últimos Tickets Cerrados</h2>
					</header>
					<div class="card-block">
						<table id="ultimos_cerrados_table" class="table table-bordered table-striped table-vcenter">
							<thead>
								<tr>
									<th>ID</th>
									<th>Asunto</th>
									<th>Usuario</th>
									<th>Categoría</th>
									<th>Cerrado por</th>
									<th>Fecha de Cierre</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</section>
			</div>
		</div>		</div>
	</div>
	<!-- Contenido -->

	<?php require_once("../MainJs/js.php");?>

	<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	<script type="text/javascript" src="estadisticas.js"></script>

</body>
</html>
<?php
  } else {
    header("Location:".Conectar::ruta()."index.php");
  }
?>
