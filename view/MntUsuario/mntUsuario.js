var tabla;


function init (){

}


$(document).ready(function(){
    tabla=$('#usuario_data').dataTable({
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
            url: '../../controller/usuario.php?op=listar',
            type : "post",
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

    // Manejador para el formulario de usuario
    $('#usuario_form').on('submit', function(e) {
        e.preventDefault();
        guardaryeditar();
    });

    // Limpiar el formulario cuando se abre el modal
    $('#btnnuevo').on('click', function() {
        $('#usuario_form')[0].reset();
        $('#usu_id').val("");
        $('#mdltitulo').text('Nuevo Usuario');
    });
});

function editar (usu_id) {
    $.post("../../controller/usuario.php?op=mostrar", { usu_id: usu_id }, function (data) {
        data = JSON.parse(data);
        $('#usu_id').val(data.usu_id);
        $('#nombre').val(data.nombre);
        $('#apellido').val(data.apellido);
        $('#correo').val(data.correo);
        $('#contrasenia').val(data.contrasenia);
        $('#rol_id').val(data.rol_id);
        $('#mdltitulo').text('Editar Usuario');
        $('#usuarioModal').modal('show');
    });
}

function eliminar (usu_id) {
    swal({
        title: "Confirmación",
        text: "¿Estás seguro de eliminar este usuario?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Sí",
        cancelButtonText: "No",
        closeOnConfirm: false
    },
    function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: "../../controller/usuario.php?op=eliminar",
                type: "POST",
                data: { usu_id: usu_id },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        tabla.ajax.reload();
                        swal({
                            title: "¡Éxito!",
                            text: response.mensaje,
                            type: "success",
                            confirmButtonClass: "btn-success"
                        });
                    } else {
                        swal("Error", response.mensaje, "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error:", error);
                    swal("Error", "Error al eliminar el usuario", "error");
                }
            });
        }
    });
};


function guardaryeditar() {
    var nombre = $('#nombre').val().trim();
    var apellido = $('#apellido').val().trim();
    var correo = $('#correo').val().trim();
    var contrasenia = $('#contrasenia').val().trim();
    var rol_id = $('#rol_id').val();
    var usu_id = $('#usu_id').val();

    // Validar campos vacíos
    if (nombre == "") {
        swal("Advertencia!", "El nombre es obligatorio", "warning");
        return false;
    }

    if (apellido == "") {
        swal("Advertencia!", "El apellido es obligatorio", "warning");
        return false;
    }

    if (correo == "") {
        swal("Advertencia!", "El correo es obligatorio", "warning");
        return false;
    }

    // Validar que nombre solo contenga letras y espacios
    var nombreRegex = /^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/;
    if (!nombreRegex.test(nombre)) {
        swal("Advertencia!", "El nombre solo debe contener letras", "warning");
        return false;
    }

    // Validar que apellido solo contenga letras y espacios
    var apellidoRegex = /^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/;
    if (!apellidoRegex.test(apellido)) {
        swal("Advertencia!", "El apellido solo debe contener letras", "warning");
        return false;
    }

    // Validar longitud del nombre y apellido
    if (nombre.length < 2) {
        swal("Advertencia!", "El nombre debe tener al menos 2 caracteres", "warning");
        return false;
    }

    if (apellido.length < 2) {
        swal("Advertencia!", "El apellido debe tener al menos 2 caracteres", "warning");
        return false;
    }

    // Validar formato de correo
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(correo)) {
        swal("Advertencia!", "El formato del correo no es válido", "warning");
        return false;
    }

    if (contrasenia == "") {
        swal("Advertencia!", "La contraseña es obligatoria", "warning");
        return false;
    }

    // Validar longitud de contraseña
    if (contrasenia.length < 4) {
        swal("Advertencia!", "La contraseña debe tener al menos 4 caracteres", "warning");
        return false;
    }

    if (rol_id == "" || rol_id == null) {
        swal("Advertencia!", "Debe seleccionar un rol", "warning");
        return false;
    }

    $.post("../../controller/usuario.php?op=guardaryeditar", {
        usu_id: usu_id,
        nombre: nombre,
        apellido: apellido,
        correo: correo,
        contrasenia: contrasenia,
        rol_id: rol_id
    }, function (data) {
        console.log("Respuesta:", data);
        tabla.ajax.reload();
        $('#usuario_form')[0].reset();
        $('#usu_id').val("");
        $('#usuarioModal').modal('hide');
        swal("¡Éxito!", "Usuario guardado correctamente", "success");
    });
}

init ();