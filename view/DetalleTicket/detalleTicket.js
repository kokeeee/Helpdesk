function init(){
   
}

$(document).ready(function(){
    var tick_id = getUrlParameter('ID');

    listardetalle(tick_id);

    $.post("../../controller/ticket.php?op=listardetalle", { tick_id : tick_id }, function (data) {
        $('#lbldetalle').html(data);
    });

    $.post("../../controller/ticket.php?op=mostrar", { tick_id : tick_id }, function (data) {
        $('lblestado').html(data.tick_estado);
        $('lblnomusuario').html(data.usu_nom +' '+data.usu_ape);
        $('lblfechcrea').html(data.fech_crea);
    });

    $('#tickd_descrip').summernote({
        height: 400,
        lang: "es-ES",
        callbacks: {
            onImageUpload: function(image) {
                console.log("Image detect...");
                myimagetreat(image[0]);
            },
            onPaste: function (e) {
                console.log("Text detect...");
            }
        },
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });

    $('#tickd_descripusu').summernote({
        height: 150,
        lang: "es-ES",
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });  

    $('#tickd_descripusu').summernote('disable');

    tabla=$('#documentos_data').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        "searching": true,
        lengthChange: false,
        colReorder: true,
        buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
                ],
        "ajax":{
            url: '../../controller/documento.php?op=listar',
            type : "post",
            data : {tick_id:tick_id},
            dataType : "json",
            error: function(e){
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo":true,
        "iDisplayLength": 10,
        "autoWidth": false,
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    }).DataTable();

});

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};


$(document).on("click","#btnenviar", function(){
    var tick_id = getUrlParameter('ID');
    var usu_id = $('#user_idx').val();
    var tickd_descrip = $('#tickd_descrip').summernote('code');

    if ($('#tickd_descrip').summernote('isEmpty')){
        swal("Advertencia!", "Falta Descripción", "warning");
    }else{
        $.post("../../controller/ticket.php?op=insertdetalle", {tick_id : tick_id, usu_id : usu_id, tickd_descrip : tickd_descrip}, function (data) {
            listardetalle(tick_id);
            $('#tickd_descrip').summernote('reset');
            swal("Respuesta enviada", "Tu consulta será respondida a la brevedad", "success");
        });
    }
});

$(document).on("click","#btncerrarticket", function(){
    swal({
        title: "Confirmación",
        text: "Estas seguro de Cerrar el Ticket?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Cerrar",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false
    },
    function(isConfirm) {
        if (isConfirm) {
            var tick_id = getUrlParameter('ID');
            var usu_id = $('#user_idx').val();
            $.post("../../controller/ticket.php?op=update", { tick_id:tick_id , usu_id:usu_id}, function (data) {
                console.log("Respuesta del servidor:", data);
            });

            $.post("../../controller/email.php?op=ticket_cerrado", {tick_id : tick_id}, function (data) {

            });

            listardetalle(tick_id);

            swal({
                title: "HelpDesk!",
                text: "Ticket Cerrado correctamente.",
                type: "success",
                confirmButtonClass: "btn-success"
            });
        }
    });
});

$(document).on("click","#btnreabrirticket", function(){
    swal({
        title: "Confirmación",
        text: "Estás seguro de Re-abrir el Ticket?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Sí",
        cancelButtonText: "No",
        closeOnConfirm: false
    },
    function(isConfirm) {
        if (isConfirm) {
            var tick_id = getUrlParameter('ID');
            var usu_id = $('#user_idx').val();
            $.post("../../controller/ticket.php?op=reabrir", { tick_id:tick_id , usu_id:usu_id}, function (data) {
                var response = JSON.parse(data);
                console.log("Respuesta del servidor:", response);
                
                if (response.success) {
                    listardetalle(tick_id);
                    
                    swal({
                        title: "¡Listo!",
                        text: response.message,
                        type: "success",
                        confirmButtonClass: "btn-success"
                    });
                } else {
                    swal({
                        title: "Error",
                        text: response.message,
                        type: "error",
                        confirmButtonClass: "btn-danger"
                    });
                }
            });
        }
    });
});

$(document).on("click","#btnencuesta", function(){
    var tick_id = getUrlParameter('ID');
    
    swal({
        title: "Encuesta de Satisfacción",
        text: "¿Cuán satisfecho estás con la solución?",
        type: "info",
        html: true,
        content: {
            element: "div",
            attributes: {
                innerHTML: `
                    <div class="form-group">
                        <label>Calificación (1-5)</label>
                        <select id="encuesta_calificacion" class="form-control">
                            <option value="">-- Selecciona --</option>
                            <option value="1">1 - Muy Insatisfecho</option>
                            <option value="2">2 - Insatisfecho</option>
                            <option value="3">3 - Neutral</option>
                            <option value="4">4 - Satisfecho</option>
                            <option value="5">5 - Muy Satisfecho</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Comentarios (opcional)</label>
                        <textarea id="encuesta_comentario" class="form-control" rows="3" placeholder="Comparte tu opinión..."></textarea>
                    </div>
                `
            }
        },
        showCancelButton: true,
        confirmButtonText: "Enviar Encuesta",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false
    }, function(isConfirm) {
        if (isConfirm) {
            var calificacion = $('#encuesta_calificacion').val();
            var comentario = $('#encuesta_comentario').val();
            
            if (!calificacion) {
                swal("Advertencia", "Debes seleccionar una calificación", "warning");
                return;
            }
            
            $.post("../../controller/ticket.php?op=encuesta", {
                tick_id: tick_id,
                tick_estre: calificacion,
                tick_coment: comentario
            }, function(data) {
                swal("¡Listo!", "Tu encuesta ha sido registrada", "success");
                listardetalle(tick_id);
            });
        }
    });
});

$(document).on("click","#btnreabrirticket", function(){
    swal({
        title: "Confirmación",
        text: "Estás seguro de Re-abrir el Ticket?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Si",
        cancelButtonText: "No",
        closeOnConfirm: false
    },
    function(isConfirm) {
        if (isConfirm) {
            var tick_id = getUrlParameter('ID');
            var usu_id = $('#user_idx').val();
            $.post("../../controller/ticket.php?op=reabrir", { tick_id:tick_id , usu_id:usu_id}, function (data) {
                var response = JSON.parse(data);
                console.log("Respuesta del servidor:", response);
                
                if (response.success) {
                    listardetalle(tick_id);
                    
                    swal({
                        title: "¡Listo!",
                        text: response.message,
                        type: "success",
                        confirmButtonClass: "btn-success"
                    });
                } else {
                    swal({
                        title: "Error",
                        text: response.message,
                        type: "error",
                        confirmButtonClass: "btn-danger"
                    });
                }
            });
        }
    });
});

$(document).on("click","#btnencuesta", function(){
    var tick_id = getUrlParameter('ID');
    
    swal({
        title: "Encuesta de Satisfacción",
        text: "¿Cuán satisfecho estás con la solución?",
        type: "info",
        html: true,
        content: {
            element: "div",
            attributes: {
                innerHTML: `
                    <div class="form-group">
                        <label>Calificación (1-5)</label>
                        <select id="encuesta_calificacion" class="form-control">
                            <option value="">-- Selecciona --</option>
                            <option value="1">1 - Muy Insatisfecho</option>
                            <option value="2">2 - Insatisfecho</option>
                            <option value="3">3 - Neutral</option>
                            <option value="4">4 - Satisfecho</option>
                            <option value="5">5 - Muy Satisfecho</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Comentarios (opcional)</label>
                        <textarea id="encuesta_comentario" class="form-control" rows="3" placeholder="Comparte tu opinión..."></textarea>
                    </div>
                `
            }
        },
        showCancelButton: true,
        confirmButtonText: "Enviar Encuesta",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false
    }, function(isConfirm) {
        if (isConfirm) {
            var calificacion = $('#encuesta_calificacion').val();
            var comentario = $('#encuesta_comentario').val();
            
            if (!calificacion) {
                swal("Advertencia", "Debes seleccionar una calificación", "warning");
                return;
            }
            
            $.post("../../controller/ticket.php?op=encuesta", {
                tick_id: tick_id,
                tick_estre: calificacion,
                tick_coment: comentario
            }, function(data) {
                swal("¡Listo!", "Tu encuesta ha sido registrada", "success");
                listardetalle(tick_id);
            });
        }
    });
});

function listardetalle(tick_id){
    $.post("../../controller/ticket.php?op=listardetalle", { tick_id : tick_id }, function (data) {
        $('#lbldetalle').html(data);
    }); 

    $.post("../../controller/ticket.php?op=mostrar", { tick_id : tick_id }, function (data) {
        data = JSON.parse(data);
        
        var estadoLabel = '';
        if (data.tick_estado_texto == "Abierto") {
            estadoLabel = '<span class="label label-pill label-success">Abierto</span>';
        } else if (data.tick_estado_texto == "En Revision") {
            estadoLabel = '<span class="label label-pill label-warning">En Revision</span>';
        } else {
            estadoLabel = '<span class="label label-pill label-danger">Cerrado</span>';
        }
        $('#lblestado').html(estadoLabel);
        
        $('#lblnomusuario').html(data.nombre +' '+data.apellido);
        $('#lblfechcrea').html(data.fech_crea);
        
        $('#lblnomidticket').html("Detalle Ticket - "+data.tick_id);

        $('#cat_nom').val(data.cat_nom);
        $('#tick_asunto').val(data.tick_asunto);
        $('#tick_prioridad').val(data.prio_nom || 'N/A');
        $('#fech_cierre').val(data.fech_cierre || 'Sin cerrar');
        $('#tickd_descripusu').summernote ('code',data.tick_descrip);

        // Cargar archivos adjuntos
        $.post("../../controller/ticket.php?op=listar_archivos", { tick_id : tick_id }, function (data) {
            try {
                var archivos = JSON.parse(data);
                var html = '';
                if (archivos.length > 0) {
                    html = '<div class="list-group">';
                    $.each(archivos, function(index, archivo) {
                        html += '<a href="../../' + archivo.arch_ruta + '" target="_blank" class="list-group-item">' +
                                '<i class="fa fa-file"></i> ' + archivo.arch_nombre + '</a>';
                    });
                    html += '</div>';
                } else {
                    html = '<p class="text-muted">No hay archivos adjuntos</p>';
                }
                $('#archivos_adjuntos').html(html);
            } catch(e) {
                $('#archivos_adjuntos').html('<p class="text-muted">No hay archivos adjuntos</p>');
            }
        });

        console.log(data.tick_estado_texto);
        
        // Obtener el rol del usuario
        var rol_id = $('#rol_idx').val();
        var puede_cerrar = (rol_id == 2 || rol_id == 3); // Solo Soporte (2) y Super Admin (3)
        
        // Ocultar panel de respuesta si el ticket está cerrado
        if (data.tick_estado_texto == "Cerrado"){
            $('#pnldetalle').hide();
            $('#btncerrarticket').hide();
            // Mostrar encuesta si está cerrado y es usuario regular
            if (rol_id == 1) {
                $('#btnencuesta').show();
            }
            // Mostrar panel re-abrir solo si el usuario tiene permisos de soporte
            if (puede_cerrar) {
                $('#pnlreabrir').show();
            } else {
                $('#pnlreabrir').hide();
            }
        } else {
            $('#pnldetalle').show();
            $('#btnencuesta').hide();
            $('#pnlreabrir').hide();
            // Mostrar botón cerrar solo si el usuario tiene permisos y el ticket está abierto
            if (puede_cerrar) {
                $('#btncerrarticket').show();
            } else {
                $('#btncerrarticket').hide();
            }
        }
    }); 
}

init();
