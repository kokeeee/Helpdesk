<head lang="es">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<link href="img/favicon.144x144.png" rel="apple-touch-icon" type="image/png" sizes="144x144">
<link href="img/favicon.114x114.png" rel="apple-touch-icon" type="image/png" sizes="114x114">
<link href="img/favicon.72x72.png" rel="apple-touch-icon" type="image/png" sizes="72x72">
<link href="img/favicon.57x57.png" rel="apple-touch-icon" type="image/png">
<link href="img/favicon.png" rel="icon" type="image/png">
<link href="img/favicon.ico" rel="shortcut icon">

<link rel="stylesheet" href="../../public/css/separate/vendor/fancybox.min.css">
<link rel="stylesheet" href="../../public/css/separate/pages/activity.min.css">

<link rel="stylesheet" href="../../public/css/separate/vendor/fancybox.min.css">
<link rel="stylesheet" href="../../public/css/separate/pages/activity.min.css">

<link rel="stylesheet" href="../../public/css/lib/summernote/summernote.css"/>
<link rel="stylesheet" href="../../public/css/separate/pages/editor.min.css">

<link rel="stylesheet" href="../../public/css/lib/font-awesome/font-awesome.min.css">
<link rel="stylesheet" href="../../public/css/lib/bootstrap/bootstrap.min.css">

<link rel="stylesheet" href="../../public/css/lib/bootstrap-sweetalert/sweetalert.css">
<link rel="stylesheet" href="../../public/css/separate/vendor/sweet-alert-animations.min.css">

<link rel="stylesheet" href="../../public/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="../../public/css/separate/vendor/datatables-net.min.css">

<link rel="stylesheet" href="../../public/css/separate/vendor/select2.min.css">

<link rel="stylesheet" href="../../public/css/main.css">

<!-- CSS para submenú desplegable -->
<link rel="stylesheet" href="../../public/css/submenu.css">

<!-- Librerias Css -->
<link rel="stylesheet" href="../../public/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="../../public/css/separate/vendor/datatables-net.min.css">

<!-- Corrección para permitir scroll -->
<style>
html {
	height: auto !important;
	overflow: auto !important;
	min-height: auto !important;
}
body {
	height: auto !important;
	overflow: auto !important;
	min-height: auto !important;
}
.with-side-menu,
.with-side-menu-compact,
.with-side-menu-addl {
	height: auto !important;
	min-height: auto !important;
	overflow: auto !important;
}
.page-content {
	min-height: auto !important;
	height: auto !important;
	overflow: visible !important;
}
.jspContainer {
	overflow: visible !important;
	height: auto !important;
}

/* Estilos para el submenú desplegable */
.menu-expandible {
	position: relative !important;
}

.menu-expandible > .toggle-submenu {
	display: flex !important;
	align-items: center !important;
	justify-content: space-between !important;
	cursor: pointer !important;
	width: 100% !important;
}

.menu-expandible .submenu-icon {
	font-size: 12px !important;
	transition: transform 0.3s ease !important;
	margin-left: auto !important;
	padding-left: 10px !important;
	display: inline-block !important;
}

.menu-expandible.active .submenu-icon {
	transform: rotate(180deg) !important;
}

.menu-expandible > .submenu {
	list-style: none !important;
	padding: 0 !important;
	margin: 0 !important;
	max-height: 0 !important;
	overflow: hidden !important;
	background-color: rgba(0, 0, 0, 0.1) !important;
	transition: max-height 0.4s ease, opacity 0.4s ease, padding 0.4s ease !important;
	opacity: 0 !important;
	display: block !important;
}

.menu-expandible.active > .submenu {
	max-height: 500px !important;
	opacity: 1 !important;
	padding: 10px 0 !important;
}

.menu-expandible .submenu li {
	margin: 0 !important;
	padding: 0 !important;
	list-style: none !important;
}

.menu-expandible .submenu li a {
	display: block !important;
	padding: 12px 20px 12px 50px !important;
	color: #fff !important;
	text-decoration: none !important;
	font-size: 14px !important;
	transition: all 0.3s ease !important;
	border-left: 3px solid transparent !important;
}

.menu-expandible .submenu li a:hover {
	background-color: rgba(0, 0, 0, 0.2) !important;
	border-left-color: #1abc9c !important;
	padding-left: 55px !important;
}
</style>