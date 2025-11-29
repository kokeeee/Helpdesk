function init(){
    $("#cambiar_pass_form").on("submit", function(e){
        cambiar_contrasenia(e);
    });
}

$(document).ready(function() {
    init();
});

function cambiar_contrasenia(e) {
    e.preventDefault();

    var usu_id = $("#usu_id").val();
    var pass_actual = $("#pass_actual").val().trim();
    var pass_nueva = $("#pass_nueva").val().trim();
    var pass_confirmar = $("#pass_confirmar").val().trim();

    if (!pass_actual) {
        swal("Cuidado!", "Debes ingresar tu contraseña actual", "warning");
        return;
    }

    if (!pass_nueva) {
        swal("Cuidado!", "Debes ingresar una contraseña nueva", "warning");
        return;
    }

    if (pass_nueva.length < 6) {
        swal("Cuidado!", "La contraseña debe tener al menos 6 caracteres", "warning");
        return;
    }

    if (pass_nueva !== pass_confirmar) {
        swal("Cuidado!", "Las contraseñas nuevas no coinciden", "warning");
        return;
    }

    $.post("../../controller/usuario.php?op=cambiar_contrasenia", {
        usu_id: usu_id,
        pass_actual: pass_actual,
        pass_nueva: pass_nueva,
        pass_confirmar: pass_confirmar
    }, function(data) {
        try {
            var resultado = JSON.parse(data);
            if (resultado.success) {
                swal({
                    title: "¡Éxito!",
                    text: resultado.message,
                    type: "success",
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Aceptar"
                }, function() {
                    window.location = "../../view/home/index.php";
                });
            } else {
                swal("Error", resultado.message, "error");
            }
        } catch(e) {
            swal("Error", "Error al procesar la solicitud", "error");
        }
    }).fail(function(error) {
        swal("Error", "Error en la conexión", "error");
    });
}
