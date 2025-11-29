function init(){
    $("#ticket_form").on("submit", function(e){
        guardar(e);
    });
}

$(document).ready(function() {
    // Cargar categorías en el select
    $.post("../../controller/categoria.php?op=combo", function(data) {
        console.log("Categorías cargadas:", data);
        $('#cat_id').html(data);
    }).fail(function(error) {
        console.error("Error cargando categorías:", error);
    });

    // Inicializar Summernote
    $('#tick_descrip').summernote({
        height: 150,
        lang: "es-ES"
    });

    // Validar archivo al cambiar
    $('#fileElem').on('change', function() {
        validar_archivos();
    });

    init();
});

function validar_archivos() {
    var files = document.getElementById('fileElem').files;
    var extensiones_permitidas = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif'];
    var tamaño_maximo = 5 * 1024 * 1024; // 5MB

    if (files.length > 0) {
        for (var i = 0; i < files.length; i++) {
            var archivo = files[i];
            var extension = archivo.name.split('.').pop().toLowerCase();
            
            // Validar extensión
            if (extensiones_permitidas.indexOf(extension) === -1) {
                swal("Cuidado!", "El archivo " + archivo.name + " tiene una extensión no permitida. Solo se permiten: " + extensiones_permitidas.join(", "), "warning");
                document.getElementById('fileElem').value = '';
                return false;
            }

            // Validar tamaño
            if (archivo.size > tamaño_maximo) {
                swal("Cuidado!", "El archivo " + archivo.name + " excede el tamaño máximo de 5MB", "warning");
                document.getElementById('fileElem').value = '';
                return false;
            }
        }
    }
    return true;
}

function guardar(e){
    e.preventDefault();
    
    // Validar campos
    var asunto = $("#tick_asunto").val().trim();
    var categoria = $("#cat_id").val();
    var descrip = $("#tick_descrip").summernote('code');

    if (!asunto) {
        swal("Cuidado!", "El asunto es obligatorio", "warning");
        return;
    }
    if (!categoria) {
        swal("Cuidado!", "La categoría es obligatoria", "warning");
        return;
    }
    if (!descrip || descrip === '<p><br></p>') {
        swal("Cuidado!", "La descripcion es obligatoria", "warning");
        return;
    }

    // Validar archivos
    if (!validar_archivos()) {
        return;
    }
    
    var formData = new FormData($("#ticket_form")[0]);

    $.ajax({
        url: "../../controller/ticket.php?op=insertar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos){
            console.log("Respuesta del servidor:", datos);
            if (datos && !isNaN(datos)) {
                swal({
                    title: "¡Éxito!",
                    text: "Ticket creado exitosamente con ID: #" + datos,
                    type: "success",
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Ver Ticket"
                }, function() {
                    // Redirigir a Consultar Ticket
                    window.location = "../../view/ConsultarTicket/index.php";
                });
            } else {
                alert("Error: " + datos);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error AJAX:", error, xhr.responseText);
            alert("Error al enviar el formulario: " + error);
        }
    });
}