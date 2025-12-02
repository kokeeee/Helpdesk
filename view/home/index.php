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
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
	<title>Home</title>
</head>
<body class="with-side-menu">

    <?php require_once("../MainHeader/header.php");?>

    <div class="mobile-menu-left-overlay"></div>
    
    <?php require_once("../MainNav/navbar.php");?>

	<!-- Contenido -->
	<div class="page-content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xl-12">
					<div class="row">
						<div class="col-sm-4">
	                        <article class="statistic-box green">
	                            <div>
	                                <div class="number" id="lbltotal"></div>
	                                <div class="caption"><div>Tickets totales</div></div>
	                            </div>
	                        </article>
	                    </div>
						<div class="col-sm-4">
	                        <article class="statistic-box yellow">
	                            <div>
	                                <div class="number" id="lbltotalabierto"></div>
	                                <div class="caption"><div>Tickets abiertos</div></div>
	                            </div>
	                        </article>
	                    </div>
						<div class="col-sm-4">
	                        <article class="statistic-box red">
	                            <div>
	                                <div class="number" id="lbltotalcerrado"></div>
	                                <div class="caption"><div>Tickets cerrados</div></div>
	                            </div>
	                        </article>
	                    </div>
				</div>
			</div>
		</div>
	</div>
	<!-- Contenido -->	<?php require_once("../MainJs/js.php");?>

	<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			// Cargar home.js después de que Raphael y Morris estén listos
			var checkLibraries = setInterval(function() {
				if (typeof Morris !== 'undefined' && typeof Raphael !== 'undefined') {
					clearInterval(checkLibraries);
					$.getScript("home.js");
				}
			}, 50);
		});
	</script>

</body>
</html>