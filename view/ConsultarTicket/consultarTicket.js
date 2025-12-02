var tabla;
var usu_asig = '';
var tipo_actual = 'pendientes'; // tipo por defecto

function init (){

}

var usu_id = '';
try{
    usu_id = document.getElementById('user_idx') ? document.getElementById('user_idx').value : (
        document.getElementById('user_id') ? document.getElementById('user_id').value : (
            document.getElementById('usu_id') ? document.getElementById('usu_id').value : ''
        )
    );
}catch(e){
    console.warn('No se encontró user id en DOM:', e);
}
// obtener rol_id desde inputs ocultos
var rol_id = '';
    try{
        rol_id = document.getElementById('rol_idx') ? document.getElementById('rol_idx').value : (
            document.getElementById('rol_id') ? document.getElementById('rol_id').value : ''
        );
    }catch(e){
        console.warn('No se encontró rol id en DOM:', e);
}

// Obtener parámetro de URL
function obtener_parametro_url(parametro) {
    var url = new URL(window.location);
    return url.searchParams.get(parametro);
}

function ver_ticket(tick_id){
    console.log(tick_id);
    // Marcar respuestas como leídas
    $.post("../../controller/ticket.php?op=marcar_respuestas_leidas", {tick_id: tick_id}, function(data){
        console.log("Respuestas marcadas como leídas");
    });
    window.open('http://localhost/HelpDesk/view/DetalleTicket/?ID=' + tick_id, '');
}

// Cargar tabla con AJAX dinámico
function cargar_tabla_dinamica(operacion, parametros){
    if(tabla){
        tabla.destroy();
    }

    tabla = $('#ticket_data').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        "searching": true,
        lengthChange: false,
        colReorder: true,
        "bSort": false,
        buttons: [		          
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
                ],
        "ajax":{
            url: '../../controller/ticket.php?op=' + operacion,
            type: "post",
            dataType: "json",
            data: parametros, 
            error: function(e){
                console.log("Error AJAX:", e.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo":true,
        "iDisplayLength": 50,
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
}

// Actualizar botones activos
function actualizar_botones_activos(tipo){
    $('#btn_pendientes, #btn_todos').removeClass('active btn-primary btn-info btn-default').addClass('btn-default');
    
    switch(tipo) {
        case 'pendientes':
            $('#btn_pendientes').removeClass('btn-default').addClass('active btn-primary');
            $('#titulo-pagina').text('Tickets Pendientes');
            $('#breadcrumb-actual').text('Tickets Pendientes');
            break;
        case 'todos':
            $('#btn_todos').removeClass('btn-default').addClass('active btn-default');
            $('#titulo-pagina').text('Todos los Tickets');
            $('#breadcrumb-actual').text('Todos los Tickets');
            break;
    }
}

$(document).ready(function(){

    $('#tickd_descrip').summernote({
        height: 150,
        lang: "es-ES",
        callbacks: {
            onImageUpload: function (image) {
                console.log("Image detect...");
                //myimagetreat(image[0]);  // comenta esto si aún no existe
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
    
    if (rol_id == 1){
        // Usuario regular - muestra solo sus tickets
        tabla=$('#ticket_data').dataTable({
                    "aProcessing": true,
                    "aServerSide": true,
                    dom: 'Bfrtip',
                    "searching": true,
                    lengthChange: false,
                    colReorder: true,
                    "bSort": false,
                    buttons: [		          
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                            ],
                    "ajax":{
                        url: '../../controller/ticket.php?op=listar_por_usuario',
                        type: "post",
                        dataType: "json",
                        data: { usu_id: usu_id }, 
                        error: function(e){
                            console.log("Error AJAX:", e.responseText);
                        }
                    },
                    "bDestroy": true,
                    "responsive": true,
                    "bInfo":true,
                    "iDisplayLength": 50,
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
        // Ocultar botones de opciones para usuario regular
        $('#btn_pendientes, #btn_todos').hide();

    } else {
        // Soporte - Obtener tipo de URL o usar pendientes por defecto
        var tipoUrl = obtener_parametro_url('tipo');
        if(tipoUrl && ['pendientes', 'todos'].includes(tipoUrl)) {
            tipo_actual = tipoUrl;
        }

        usu_asig = usu_id;
        
        // Cargar la vista correcta según el tipo
        if(tipo_actual === 'pendientes') {
            cargar_tabla_dinamica('listar_pendientes', { usu_asig: usu_asig });
        } else {
            tabla=$('#ticket_data').dataTable({
                "aProcessing": true,
                "aServerSide": true,
                dom: '<"row"<"col-sm-12"B>><"row"<"col-sm-12"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-12"ip>>',
                "searching": true,
                lengthChange: false,
                colReorder: true,
                "bSort": false,
                buttons: [		          
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                        ],
                "ajax":{
                    url: '../../controller/ticket.php?op=listar',
                    type: "post",
                    dataType: "json",
                    error: function(e){
                        console.log("Error AJAX:", e.responseText);
                    }
                },
                "bDestroy": true,
                "responsive": true,
                "bInfo":true,
                "iDisplayLength": 50,
                "autoWidth": false,
                "initComplete": function(settings, json) {
                    // Mover los botones al contenedor personalizado
                    $('#btn-container').html($('.dt-buttons'));
                },
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
        }

        actualizar_botones_activos(tipo_actual);

        // Evento para Tickets Pendientes
        $('#btn_pendientes').on('click', function(){
            tipo_actual = 'pendientes';
            actualizar_botones_activos('pendientes');
            cargar_tabla_dinamica('listar_pendientes', { usu_asig: usu_asig });
        });

        // Evento para Ver Todos
        $('#btn_todos').on('click', function(){
            tipo_actual = 'todos';
            actualizar_botones_activos('todos');
            tabla.destroy();
            tabla=$('#ticket_data').dataTable({
                "aProcessing": true,
                "aServerSide": true,
                dom: 'Bfrtip',
                "searching": true,
                lengthChange: false,
                colReorder: true,
                "bSort": false,
                buttons: [		          
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                        ],
                "ajax":{
                    url: '../../controller/ticket.php?op=listar',
                    type: "post",
                    dataType: "json",
                    error: function(e){
                        console.log("Error AJAX:", e.responseText);
                    }
                },
                "bDestroy": true,
                "responsive": true,
                "bInfo":true,
                "iDisplayLength": 50,
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
    }
    

});



init ();