// Script para actualizar notificaciones de tickets en el navbar
$(document).ready(function(){
    // Actualizar notificaciones cada 10 segundos
    function actualizarNotificaciones() {
        var usu_id = $('#user_idx').val();
        
        if (!usu_id) {
            return;
        }
        
        // Obtener tickets pendientes con respuestas sin leer
        $.ajax({
            url: "../../controller/ticket.php?op=contar_no_leidos_pendientes",
            type: "post",
            dataType: "json",
            data: { usu_asig: usu_id },
            success: function(response) {
                var total = response.total || 0;
                
                if (total > 0) {
                    $('#badge-pendientes').text(total).show();
                    $('#badge-pendientes-admin').text(total).show();
                } else {
                    $('#badge-pendientes').hide();
                    $('#badge-pendientes-admin').hide();
                }
            },
            error: function(xhr, status, error) {
                console.log("Error al obtener pendientes:", error);
            }
        });
        
        // Obtener tickets en revisión con respuestas sin leer
        $.ajax({
            url: "../../controller/ticket.php?op=contar_no_leidos_revision",
            type: "post",
            dataType: "json",
            data: { usu_asig: usu_id },
            success: function(response) {
                var total = response.total || 0;
                
                if (total > 0) {
                    $('#badge-revision').text(total).show();
                    $('#badge-revision-admin').text(total).show();
                } else {
                    $('#badge-revision').hide();
                    $('#badge-revision-admin').hide();
                }
            },
            error: function(xhr, status, error) {
                console.log("Error al obtener revisión:", error);
            }
        });
    }
    
    // Ejecutar al cargar la página
    actualizarNotificaciones();
    
    // Actualizar cada 10 segundos
    setInterval(actualizarNotificaciones, 10000);
});
