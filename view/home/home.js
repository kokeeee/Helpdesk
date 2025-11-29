function init(){
    var usu_id = $('#user_idx').val();
    var rol_id = parseInt($('#rol_idx').val());

    if ( rol_id == 1){
        // Usuario regular: sus propios tickets
        $.post("../../controller/usuario.php?op=total", {usu_id:usu_id}, function (data) {
            data = JSON.parse(data);
            $('#lbltotal').html(data.TOTAL);
        }); 
    
        $.post("../../controller/usuario.php?op=totalabierto", {usu_id:usu_id}, function (data) {
            data = JSON.parse(data);
            $('#lbltotalabierto').html(data.TOTAL);
        });
    
        $.post("../../controller/usuario.php?op=totalcerrado", {usu_id:usu_id}, function (data) {
            data = JSON.parse(data);
            $('#lbltotalcerrado').html(data.TOTAL);
        });

        // Gráfico de tickets por categoría
        $.post("../../controller/usuario.php?op=grafico", {usu_id:usu_id},function (data) {
            data = JSON.parse(data);
    
            // Crear gráfico
            if (data.length > 0) {
                new Morris.Bar({
                    element: 'divgrafico_categorias',
                    data: data,
                    xkey: 'nom',
                    ykeys: ['total'],
                    labels: ['Tickets'],
                    barColors: ["#1AB244"],
                    ymax: Math.max.apply(null, data.map(function(x) { return x.total; })) + 1,
                    ymin: 0,
                    numLines: 5,
                    gridTextSize: 10,
                    hideHover: 'auto',
                    parseTime: false,
                    preUnits: '',
                    postUnits: ''
                });
            }
            
            // Crear tabla
            var html = '<table class="table table-striped table-bordered">';
            html += '<thead><tr><th>Categoría</th><th>Cantidad de Tickets</th></tr></thead>';
            html += '<tbody>';
            
            for (var i = 0; i < data.length; i++) {
                html += '<tr><td>' + data[i].nom + '</td><td>' + data[i].total + '</td></tr>';
            }
            
            html += '</tbody></table>';
            $('#tabla_categorias').html(html);
        }); 

    } else if (rol_id == 2 || rol_id == 3) {
        // Soporte (rol 2) y Super Admin (rol 3): solo sus propios tickets
        $.post("../../controller/ticket.php?op=total_por_usuario", {usu_id:usu_id}, function (data) {
            data = JSON.parse(data);
            $('#lbltotal').html(data.TOTAL);
        }); 
    
        $.post("../../controller/ticket.php?op=totalabierto_por_usuario", {usu_id:usu_id}, function (data) {
            data = JSON.parse(data);
            $('#lbltotalabierto').html(data.TOTAL);
        });
    
        $.post("../../controller/ticket.php?op=totalcerrado_por_usuario", {usu_id:usu_id}, function (data) {
            data = JSON.parse(data);
            $('#lbltotalcerrado').html(data.TOTAL);
        });  

    } 
}

$(document).ready(function(){
    init();
});