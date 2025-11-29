<script src="../../public/js/lib/jquery/jquery.min.js"></script>
<script src="../../public/js/lib/tether/tether.min.js"></script>
<script src="../../public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="../../public/js/plugins.js"></script>
<script src="../../public/js/app.js"></script>

<script src="../../public/js/lib/datatables-net/datatables.min.js"></script>

<script src="../../public/js/lib/bootstrap-sweetalert/sweetalert.min.js"></script>

<script src="../../public/js/lib/summernote/summernote.min.js"></script>

<script src="../../public/js/lib/fancybox/jquery.fancybox.pack.js"></script>

<script src="../../public/js/summernote-ES.js"></script>

<script src="../../public/js/lib/select2/select2.full.min.js"></script>

<script src="../MainJs/notificaciones.js"></script>

<!-- Script para el submenú desplegable -->
<script>
// Esperar a que jQuery esté completamente cargado
jQuery(function($) {
    var menuExpandible = $('.menu-expandible');
    var toggleSubmenu = $('.toggle-submenu');
    var submenuLink = $('.submenu-link');
    
    console.log('Inicializando submenú - Elementos encontrados:', menuExpandible.length);
    
    // Evento click en toggle del submenú
    toggleSubmenu.on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var currentMenu = $(this).closest('.menu-expandible');
        console.log('Click en toggle-submenu');
        
        // Cerrar otros menús abiertos
        menuExpandible.not(currentMenu).removeClass('active');
        
        // Toggle del menú actual
        currentMenu.toggleClass('active');
        
        console.log('Menú activo:', currentMenu.hasClass('active'));
        return false;
    });
    
    // Evento click en los enlaces del submenú
    submenuLink.on('click', function(e) {
        e.stopPropagation();
        var parentMenu = $(this).closest('.menu-expandible');
        parentMenu.removeClass('active');
    });
    
    // Cerrar menú al hacer click fuera
    $(document).on('click', function(e) {
        var clickedElement = $(e.target);
        
        // Si el click no está en el menú ni en el toggle
        if (!clickedElement.closest('.menu-expandible').length && !clickedElement.closest('.toggle-submenu').length) {
            menuExpandible.removeClass('active');
        }
    });
});
</script>

