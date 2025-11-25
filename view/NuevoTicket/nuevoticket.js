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

    init();
});


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
                alert("Ticket creado exitosamente con ID: " + datos);
                window.location = "../../view/home/index.php";
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