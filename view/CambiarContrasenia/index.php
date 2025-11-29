<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usu_id"])){ 
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
	<title>Cambiar Contraseña</title>
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
							<h3>Cambiar Contraseña</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="#">Home</a></li>
								<li class="active">Cambiar Contraseña</li>
							</ol>
						</div>
					</div>
				</div>
			</header>

			<div class="box-typical box-typical-padding">
				<div class="row">
					<form method = "POST" id = "cambiar_pass_form">

						<input type="hidden" id="usu_id" name="usu_id" value="<?php echo $_SESSION["usu_id"] ?>">

						<div class="col-lg-6">
							<fieldset class="form-group">
								<label class="form-label semibold" for="pass_actual">Contraseña Actual</label>
								<input type="password" class="form-control" id="pass_actual" name="pass_actual" required>
							</fieldset>
						</div>

						<div class="col-lg-6">
							<fieldset class="form-group">
								<label class="form-label semibold" for="pass_nueva">Contraseña Nueva</label>
								<input type="password" class="form-control" id="pass_nueva" name="pass_nueva" required>
							</fieldset>
						</div>

						<div class="col-lg-6">
							<fieldset class="form-group">
								<label class="form-label semibold" for="pass_confirmar">Confirmar Contraseña</label>
								<input type="password" class="form-control" id="pass_confirmar" name="pass_confirmar" required>
							</fieldset>
						</div>

						<div class="col-lg-12">
							<button type="submit" name="action" value="cambiar" class="btn btn-rounded btn-inline btn-primary">Cambiar Contraseña</button>
							<a href="../../view/home/index.php" class="btn btn-rounded btn-inline btn-secondary">Cancelar</a>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>
	<!-- Contenido -->

	<?php require_once("../MainJs/js.php");?>

	<script type="text/javascript" src="cambiarcontrasenia.js"></script>

</body>
</html>
<?php
  } else {
    header("Location:".Conectar::ruta()."index.php");
  }
?>
