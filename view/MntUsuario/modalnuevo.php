<div id="usuarioModal" class="modal fade bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="font-icon-close-2"></i>
                </button>
                <h4 class="modal-title" id="mdltitulo">Nuevo Usuario</h4>
            </div>
            <form method="post" id="usuario_form">
                <div class="modal-body">
                    <input type="hidden" id="usu_id" name="usu_id">

                    <div class="form-group">
                        <label class="form-label" for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese Nombre" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="apellido">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese Apellido" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="correo">Correo Electronico</label>
                        <input type="email" class="form-control" id="correo" name="correo" placeholder="test@test.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contrasenia">Contraseña</label>
                        <input type="text" class="form-control" id="contrasenia" name="contrasenia" placeholder="************" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="rol_id">Rol</label>
                        <select class="select2" id="rol_id" name="rol_id">
                            <option value="2" selected>Soporte</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" id="btnGuardar" value="add" class="btn btn-rounded btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>