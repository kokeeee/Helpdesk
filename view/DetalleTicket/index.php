<?php
require_once("../../config/conexion.php");
if (!isset($_SESSION["usu_id"])) {
  header("Location: ../error404.php?reason=not_logged_in");
  exit();
}
?>
  <!DOCTYPE html>
  <html>
  <?php require_once("../MainHead/head.php"); ?>
  <title>Detalle Ticket</title>
  </head>

  <body class="with-side-menu">

    <?php require_once("../MainHeader/header.php"); ?>

    <div class="mobile-menu-left-overlay"></div>

    <?php require_once("../MainNav/navbar.php"); ?>

    <!-- Contenido -->
    <div class="page-content">
      <div class="container-fluid">

        <header class="section-header">
          <div class="tbl">
            <div class="tbl-row">
              <div class="tbl-cell">
                <div style="display: flex; align-items: center; gap: 15px;">
                  <a href="../ConsultarTicket/" class="btn" title="Volver a Consultar Tickets" style="display: flex; align-items: center; gap: 8px; background-color: white; color: #333; border: 2px solid #ddd; border-radius: 25px; padding: 8px 16px; font-weight: 600; transition: all 0.3s ease;">
                    <span style="font-size: 18px;">←</span>
                  </a>
                  <img src="../../public/img/usuario.png" alt="Logo" style="width: 50px; height: 50px; object-fit: contain; background-color: white; padding: 5px; border-radius: 8px; border: 1px solid #ddd;">
                  <h3 id="lblnomidticket" style="margin: 0;">Detalle Ticket - 1</h3>
                </div>
                <span class="label label-pill label-success" id="lblnomusuario"></span>
                <span class="label label-pill label-primary" id="lblfechcrea"></span>
                <span id="lblestado"></span>
                <ol class="breadcrumb breadcrumb-simple">
                  <li><a href="#">Home</a></li>
                  <li class="active">Detalle Ticket</li>
                </ol>
              </div>
            </div>
          </div>
        </header>

        <div class="box-typical box-typical-padding">
          <div class="row">

              <div class="col-lg-6">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="cat_nom">Categoria</label>
                  <input type="text" class="form-control" id="cat_nom" name="cat_nom" readonly>
                </fieldset>
              </div>

              <div class="col-lg-6">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="tick_asunto">Titulo</label>
                  <input type="text" class="form-control" id="tick_asunto" name="tick_asunto" readonly>
                </fieldset>
              </div>

              <div class="col-lg-6">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="tick_prioridad">Prioridad</label>
                  <input type="text" class="form-control" id="tick_prioridad" name="tick_prioridad" readonly>
                </fieldset>
              </div>

              <div class="col-lg-6">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="fech_cierre">Fecha de Cierre</label>
                  <input type="text" class="form-control" id="fech_cierre" name="fech_cierre" readonly>
                </fieldset>
              </div>

              <div class="col-lg-12">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="tickd_descripusu">Descripción</label>
                  <div class="summernote-theme-1">
                    <textarea id="tickd_descripusu" name="tickd_descripusu" class="summernote" name="name"></textarea>
                  </div>

                </fieldset>
              </div>

              <!-- Sección de Archivos Adjuntos -->
              <div class="col-lg-12">
                <fieldset class="form-group">
                  <label class="form-label semibold">Archivos Adjuntos</label>
                  <div id="archivos_adjuntos" class="list-group">
                    <p class="text-muted">No hay archivos adjuntos</p>
                  </div>
                </fieldset>
              </div>

          </div>
        </div>

        <section class="activity-line" id="lbldetalle">

        </section>

        <div class="box-typical box-typical-padding" id="pnldetalle">
          <p>
            Ingrese su duda o consulta
          </p>
          <div class="row">
              <div class="col-lg-12">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="tickd_descrip">Descripción</label>
                  <div class="summernote-theme-1">
                    <textarea id="tickd_descrip" name="tickd_descrip" class="summernote" name="name"></textarea>
                  </div>
                </fieldset>
              </div>
              <div class="col-lg-12">
                <button type="button" id="btnenviar" class="btn btn-rounded btn-inline btn-primary">Enviar</button>
                <button type="button" id="btncerrarticket" class="btn btn-rounded btn-inline btn-warning">Cerrar Ticket</button>
                <button type="button" id="btnencuesta" class="btn btn-rounded btn-inline btn-success" style="display:none;">Realizar Encuesta</button>
              </div>
          </div>
        </div>

        <div class="box-typical box-typical-padding" id="pnlreabrir" style="display:none;">
          <div class="row">
            <div class="col-lg-12">
              <p class="text-muted"><strong>El ticket está cerrado</strong></p>
              <button type="button" id="btnreabrirticket" class="btn btn-rounded btn-inline btn-warning">Re-abrir Ticket</button>
            </div>
          </div>
        </div>

      </div>
    </div>
    <!-- Contenido -->

    <?php require_once("../MainJs/js.php"); ?>

    <script type="text/javascript" src="detalleTicket.js"></script>

  </body>

  </html>