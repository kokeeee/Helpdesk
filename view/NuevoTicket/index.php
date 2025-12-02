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
	<title>Nuevo Ticket</title>
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
							<h3>Nuevo Ticket</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="#">Home</a></li>
								<li class="active">Nuevo Ticket</li>
							</ol>
						</div>
					</div>
				</div>
			</header>

			<div class="box-typical box-typical-padding">
				<div class="row">
					<form method = "POST" id = "ticket_form">

						<input type="hidden" id="usu_id" name="usu_id" value="<?php echo $_SESSION["usu_id"] ?>">
						<!-- Token CSRF para protección contra ataques -->
						<?php echo campo_csrf(); ?>

						<div class="col-lg-12">
							<fieldset class="form-group">
								<label class="form-label semibold" for="tick_asunto">Asunto</label>
								<input type="text" class="form-control" id="tick_asunto" name="tick_asunto">
							</fieldset>
						</div>

					<div class="col-lg-6">
						<fieldset class="form-group">
							<label class="form-label semibold" for="exampleInput">Categoria</label>
							<select id="cat_id" name="cat_id" class="form-control">
							</select>
						</fieldset>
					</div>						<div class="col-lg-12">
							<fieldset class="form-group">
								<label class="form-label semibold" for="tick_descrip">Descripción</label>
								<label for="">Aqui debes especificar tus consultas, uno de nuestros trabajadores te responderá a la brevedad.</label>
								<div class="summernote-theme-1">
									<textarea id="tick_descrip" name="tick_descrip" class="summernote" name="name"></textarea>
								</div>
							</fieldset>
						</div>

						<div class="col-lg-12">
							<fieldset class="form-group">
								<label class="form-label semibold" for="fileElem">Documentos Adicionales</label>
								<input type="file" name="fileElem[]" id="fileElem" class="form-control" multiple>
							</fieldset>
						</div>

						<div class="col-lg-12">
							<button type="submit" name="action" value="add" class="btn btn-rounded btn-inline btn-primary">Enviar</button>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>
	<!-- Contenido -->

	<?php require_once("../MainJs/js.php");?>

	<script type="text/javascript" src="nuevoticket.js"></script>

</body>
</html>