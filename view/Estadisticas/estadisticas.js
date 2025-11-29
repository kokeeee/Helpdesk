function init(){
   
}

$(document).ready(function(){
    
    // Cargar estadísticas generales
    $.post("../../controller/estadistica.php?op=general", function(data){
        data = JSON.parse(data);
        console.log("Estadísticas generales:", data);
        $('#total_cerrados').html(data.tickets_cerrados);
        $('#total_abiertos').html(data.tickets_abiertos);
        $('#porcentaje_cerrados').html(data.porcentaje_cerrados + '%');
    });

    // Cargar tabla de tickets cerrados por soporte (Total)
    var tabla_soporte = $('#soporte_cerrados_table').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "searching": false,
        "bPaginate": false,
        "bInfo": false,
        "ajax":{
            url: '../../controller/estadistica.php?op=soporte_cerrados',
            type: "post",
            dataType: "json",
            error: function(e){
                console.log("Error:", e.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "autoWidth": false,
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    }).DataTable();

    // Cargar tabla de tickets cerrados por soporte (Este Mes)
    var tabla_soporte_mes = $('#soporte_cerrados_mes_table').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "searching": false,
        "bPaginate": false,
        "bInfo": false,
        "ajax":{
            url: '../../controller/estadistica.php?op=soporte_cerrados_mes',
            type: "post",
            dataType: "json",
            error: function(e){
                console.log("Error:", e.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "autoWidth": false,
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    }).DataTable();

    // Cargar gráfico de personal
    $.post("../../controller/estadistica.php?op=grafico_soporte", function(data){
        data = JSON.parse(data);
        console.log("Datos gráfico personal:", data);
        
        if (data.length > 0) {
            new Morris.Bar({
                element: 'divgrafico',
                data: data,
                xkey: 'name',
                ykeys: ['value'],
                labels: ['Tickets Cerrados'],
                barColors: ["#1AB244"], 
            });
        } else {
            $('#divgrafico').html('<p style="text-align: center; padding: 20px;">No hay datos disponibles</p>');
        }
    });

    // Cargar gráfico de distribución por categoría (Barras)
    $.post("../../controller/estadistica.php?op=tickets_por_categoria", function(data){
        data = JSON.parse(data);
        console.log("Tickets por categoría:", data);
        
        if (data.length > 0) {
            new Morris.Bar({
                element: 'grafico_categorias',
                data: data,
                xkey: 'label',
                ykeys: ['value'],
                labels: ['Tickets'],
                barColors: ['#1AB244']
            });
        } else {
            $('#grafico_categorias').html('<p style="text-align: center; padding: 20px;">No hay datos disponibles</p>');
        }
    });

    // Cargar gráfico de distribución por estado (Barras)
    $.post("../../controller/estadistica.php?op=distribucion_estado", function(data){
        data = JSON.parse(data);
        console.log("Distribución estado:", data);
        
        if (data.length > 0) {
            new Morris.Bar({
                element: 'grafico_estado',
                data: data,
                xkey: 'label',
                ykeys: ['value'],
                labels: ['Tickets'],
                barColors: ['#1AB244']
            });
        } else {
            $('#grafico_estado').html('<p style="text-align: center; padding: 20px;">No hay datos disponibles</p>');
        }
    });

    // Cargar tiempo promedio de resolución
    $.post("../../controller/estadistica.php?op=tiempo_promedio", function(data){
        data = JSON.parse(data);
        console.log("Tiempo promedio:", data);
        
        $('#promedio_horas').html(data.horas_promedio || 0);
        $('#promedio_dias').html(data.dias_promedio || 0);
    });

    // Cargar gráfico de carga por día (Últimos 7 días)
    $.post("../../controller/estadistica.php?op=carga_por_dia", function(data){
        data = JSON.parse(data);
        console.log("Carga por día:", data);
        
        if (data.length > 0) {
            new Morris.Line({
                element: 'grafico_carga',
                data: data,
                xkey: 'label',
                ykeys: ['value'],
                labels: ['Tickets Creados'],
                lineColors: ['#1AB244'],
                pointSize: 5,
                smooth: true
            });
        } else {
            $('#grafico_carga').html('<p style="text-align: center; padding: 20px;">No hay datos disponibles</p>');
        }
    });

    // Cargar tabla de últimos tickets cerrados
    var tabla_ultimos = $('#ultimos_cerrados_table').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "searching": false,
        "bPaginate": false,
        "bInfo": false,
        "ajax":{
            url: '../../controller/estadistica.php?op=ultimos_cerrados',
            type: "post",
            dataType: "json",
            error: function(e){
                console.log("Error:", e.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "autoWidth": false,
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    }).DataTable();
});

init();
