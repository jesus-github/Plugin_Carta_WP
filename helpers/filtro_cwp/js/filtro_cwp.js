/* Validdamos que el objeto global jQuery no sea indefinido o no exista */
if (typeof  jQuery == 'undefined') {
    throw new Error('El plugin carta_wp necesita la librería jQuery para funcionar.');
}

(function ($) {

    'use strict';
    /********** Funcionalidad de filtrado *********/

    // Creamos un objeto (nombramos la variable empezando con mayúsculas)
    var TJ_CartaWp = function (element, options, callback) {
            this.element    = null;
            this.options    = null;
            this.zoomfull       = '<!--  INICIO - Maquetación al pinchar sobre un item -->\n' +
                '<div class="card shadow-lg cwp-zoom" id="">\n' +
                '   <button type="button" class="btn-close btn-close-white btn-lg bg-light p-2" aria-label="Close"></button>\n' +
                '    <img src="" class="card-img-top cwp-main-image" alt="...">\n' +
                '    <div class="card-body">\n' +
                '        <h3 class="card-title cwp-title"></h3>\n' +
                '        <p class="card-text text-secondary m-1 cwp-description"></p>\n' +
                '        <h6 class="card-text cwp-price mt-3"></h6>\n' +
                '        <ul class="list-inline m-0 cwp-alergenos">\n' +
                // '            <li class="list-inline-item"><img src="" alt=""></li>\n' +
                '        </ul>\n' +
                '    </div>\n' +
                '</div>\n' +
                '<!--  FIN - Maquetación al pinchar sobre un item -->';
            this.overdark   =   '<div class="cwp-fondo-zoom"></div>'; // Máscara negra al hace zoom

            this.init(element, options, callback);
        };

    // Valores por defecto
    TJ_CartaWp.Defaults = {
        filter : '.cwp-categorias li', // selector de los filtros
        item : '.seccion', // selector  los item a filtrar (clase que tendrá el contenedor de cada plato)
        animation : 'none',
        callback : null
    }

    // Método para ejecutar las acciones iniciales
    TJ_CartaWp.prototype.init = function (element, options, callback) {
        this.$element = $(element);
        this.options = this.getOptions(options);

        // Llamamos al método filtro pasándole las options
        this.filtro(this.options);

        // Añadimos el html de la máscara y el zoom al final de body (las hemos declarado como propiedades al crear el objeto TJ_CartaWp)
        $('body').prepend(this.zoomfull).prepend(this.overdark);

        // Inicializamos el método para hacer zoom
        this.zoom();

        // Aplicamos la clase cwp-item a cada contenedor de plato
        //this.$element.children().addClass(this.options.item.replace('.',''));
        this.$element.find('.seccion').addClass(this.options.item.replace('.',''));

        // Validamos los métodos callback
        if (typeof callback == 'function'){
            callback.call(this);
        }
        if (typeof this.options.callback == 'function'){
            this.options.callback.call(this);
        }
    }

    // Método que nos va a devolver los valores de las propiedades por defecto
    TJ_CartaWp.prototype.getDefaults = function () {
        return TJ_CartaWp.Defaults;
    }

    // Método que nos va a devolver los valores de las opciones introducidas por el usuario
    TJ_CartaWp.prototype.getOptions = function (options){
        // devolvemos la fusión de las opciones por Default con las opciones pasada por el usuario
        return $.extend({}, this.getDefaults(), options);
    }

    // Método para el filtrado. Al pulsar alguno de los botones nos muestre los platos de esa categoría
    TJ_CartaWp.prototype.filtro = function (options) {
        // Al hacer click sobre algún botón contenido en options.filter (hemos definido ya su valor por defecto del contenedor de los botones)
        $(document).on('click', options.filter, function (){

            setTimeout(function() {
                // Una vez seleccionada la categoría cerramos el menú hamburguesa
                $('.navbar-collapse').removeClass('show');
            }, 10);

            // Variable para almacenar el elemento sobre el que hacemos click
            var $this = $(this);
            // Variable para almacenar el valos del atributo data-filter del botón
            var filtro = $this.attr('data-filter');
            // Variable para almacenar la selección de todas los platos.
            var $item   = $(options.item);

            // Evaluamos la variable filtro para ver el valor del atributo data-filter del botón
            if (filtro === 'todo') {
                // Le añadimos la clase cwp-categoria-activa y a todos los hermanos le quitamos la clase cwp-categoria-activa
                $this.addClass('cwp-categoria-activa').siblings().removeClass('cwp-categoria-activa');
                // Muestra todas la imágenes
                $item.show();
            } else {
                // Si el elemento que pulsamos no tiene la clase cwp-categoria-activa vamos a agrgársela y quitársela a los hermanos
                if (!$this.hasClass('cwp-categoria-activa')) {
                    $this.addClass('cwp-categoria-activa').siblings().removeClass('cwp-categoria-activa');
                    // Ocultamos todos los items y una vez ocultados ejecutamos la función
                    $item.hide(0, function (){
                        // Mostramos  solo los items que tengan como valor del atributo data-f el valor de filtro (data-filter del botón en este caso)
                        // Utilizamos *= para indicar que data-f tiene que contener el valor de filtro, por si hay más de un filtro aplicado
                        $('[data-f *= "'+filtro+'"]').show();
                    });
                }
            }
        });
    }

    // Método para hacer Zoom al pinchar sobre un plato
    TJ_CartaWp.prototype.zoom = function () {
        // variable con la máscara negra
        var $overdark = $('.cwp-fondo-zoom');
        // variable con el contenedor que va a contener todo cuando hagamos zoom
        var $contenedor_zoom = $('.cwp-zoom');
        // Variables con los datos para pasarlos del contenedor pequeño al contenedor grande (cuando hacemos zoom
        var $imagen_pricipal = $('.cwp-main-image');
        // Evento para cuando hagamos click sobre el contenedor cwp-single-container
        $(document).on('click','.cwp-single-container', function (){
            // Almacenamos en una variable el elemento sobre el cual estamos haciendo click
            var $contenedor_pequeno = $(this);
            // Variables para almacenar los datos del contenedor pequeño:
            // Variable con la url de la imagen principal.
            var $src = $contenedor_pequeno.find('.cwp-main-image').attr('src');
            // Título
            var $titulo = $contenedor_pequeno.find('.card-title').text();
            // Descripcion. Vamos a distinguir si el contenedor tiene read-more o no para no mostrar lo que haya
            // en el contenedor .read-more al hacer zoom
            if ($contenedor_pequeno.find('.read-more').length != 0){
                var $descripcion = $contenedor_pequeno.find('.show-string').text() + $contenedor_pequeno.find('.hide-string').text()
            } else {
                var $descripcion = $contenedor_pequeno.find('.cwp-description').text();
            }


            // Precio
            var $precio = $contenedor_pequeno.find('.cwp-price').text();
            // $alergenos
            var $alergenos = $contenedor_pequeno.find('.cwp-alerg-icon');

            // Parámetro para mostrar el zoom y el overdark
            $contenedor_zoom.fadeIn();
            $overdark.fadeIn();

            // Pasamos los valores del contenedor chico al grande
            // Controlamos que si no hay imagen no se muestre el contenedor de la imagen
            if (typeof $src != 'undefined'){
                $('.card-img-top').removeClass('d-none');
                $contenedor_zoom.children('.cwp-main-image').attr('src', $src);
            } else if (typeof $src === 'undefined'){
                $contenedor_zoom.children('.cwp-main-image').attr('src', '');
                $('.card-img-top').addClass('d-none');
            }
            $contenedor_zoom.find('.card-title').text($titulo);
            $contenedor_zoom.find('.cwp-description').text($descripcion);
            $contenedor_zoom.find('.cwp-price').text($precio);
            for (const $alergeno of $alergenos) {
                $contenedor_zoom.find('ul.cwp-alergenos').append("<li class='list-inline-item'><img src="+$alergeno.currentSrc+ " alt='"+$alergeno.alt+"'></li>")
            }

        });

        // Tras aparecer el overdark y el contenedor zoom vamos a ocultarlos cuando hagamos click sobre cualquiera de ellos
        $.merge($overdark,$contenedor_zoom).on('click', function (){
            $overdark.fadeOut();
            $contenedor_zoom.fadeOut();
            // Borramos los alergenos del DOM para que no aparezcan en el siguiente click
            $contenedor_zoom.find('ul.cwp-alergenos').html('');

        })
    }

    // Añadimos leer más para no mostrar toda la descripción
    $(document).ready(function(){
        // Longitud visible
        var maxLength = 30;
        // Se lo aplicamos a los textos con la clase .show-read-more
        $(".show-read-more").each(function(){
            // Cogemos el texto del elemento
            var myStr = $(this).text();
            if($.trim(myStr).length > maxLength){
                var newStr = myStr.substring(0, maxLength);
                var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                // Vacia el contenedor y muestra la cadena visible
                $(this).empty().html("<span class='show-string'>"+newStr+"</span>");
                // Añadimos ...más
                $(this).append('<span class="read-more text-danger"> ...más</span>');
                // Añadimos la parte restante y la ocultamos mediante la clase d-none (no la eliminamos para
                // poder cogerla para mostrarla en el contenedor zoom)
                $(this).append('<span class="d-none hide-string">' + removedStr + '</span>');
            }
        });
    });

    var Plugin = function (options, callback) {
        return this.each(function () {
            options = typeof options == 'object' && options;
            var data = new TJ_CartaWp( this, options, callback);
        });
    }

    // para evitar conflictos con otros plugins
    var old = $.fn.filtro_cwp;
    //Método que vamos a usar en los elementos
    $.fn.filtro_cwp = Plugin;
    $.fn.filtro_cwp.Constructor = TJ_CartaWp;

    // Para evitar conflictos de nuestro plugin con otros plugins o frameworks con el símbolo $
    $.fn.filtro_cwp.noConflict = function () {
        $.fn.filtro_cwp = old;
    }

})(jQuery);



