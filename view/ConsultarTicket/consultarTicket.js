var tabla;


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

function ver_ticket(tick_id){
    console.log(tick_id);

    window.open('http://localhost/HelpDesk/view/DetalleTicket/?ID=' + tick_id, '');

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
        tabla=$('#ticket_data').dataTable({
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

    } else {
                tabla=$('#ticket_data').dataTable({
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

    }
    

});



init ();