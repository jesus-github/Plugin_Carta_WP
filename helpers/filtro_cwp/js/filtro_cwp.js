/* Validamos que el objeto global jQuery no sea indefinido o no exista */
if (typeof  jQuery == 'undefined') {
    throw new Error('El plugin carta_wp necesita la librería jQuery para funcionar.');
}

(function ($) {

    'use strict';
    /********** Funcionalidad de filtrado *********/

    var TJ_CartaWp = function (element, options, callback) {
        this.element    = null;
        this.options    = null;
        this.zoomfull       = '<!--  INICIO - Maquetación al pinchar sobre un item -->\n' +
            '<div class="modal fade" id="cwpModal" tabindex="-1" aria-hidden="true">\n' +
            '   <div class="modal-dialog modal-dialog-centered p-3">\n' +
            '       <div class="modal-content cwp-zoom shadow-lg border-0">\n' +
            '           <div class="modal-header bg-dark text-white border-0">\n' +
            '               <h3 class="modal-title card-title cwp-title" ></h3>\n' +
            '               <button type="button" class="btn-lg btn-close btn-close-white bg-transparent align-self-start ms-4" aria-label="Close" data-bs-dismiss="modal" aria-label="Close"></button>\n' +
            '           </div>\n' +
            '           <div class="modal-body p-0 overflow-hidden">\n' +
            '               <img src="" class="cwp-main-image w-100 h-auto" alt="...">\n' +
            '           </div>\n' +
            '           <div class="modal-footer justify-content-start">\n' +
            '               <p class="card-text text-secondary mb-2 cwp-description"></p>\n' +
            '               <div class="w-100 d-flex justify-content-between">\n' +
            '                   <h5 class="card-text cwp-price m-1 align-self-center"></h5>\n' +
            '                   <ul class="list-inline cwp-alergenos m-1">\n' +
            '                   </ul>\n' +
            '               </div>\n' +
            '           </div>\n' +
            '        </div>\n' +
        '       </div>\n' +
            '</div>\n' +
            '<!--  FIN - Maquetación al pinchar sobre un item -->';

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

        // Añadimos el html del zoom al final de body (las hemos declarado como propiedades al crear el objeto TJ_CartaWp)
        $('body').prepend(this.zoomfull);

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
            var $alergenos_sin_icono = $contenedor_pequeno.find('.cwp-alerg-sin-icon');

            // Pasamos los valores del contenedor chico al grande
            // Controlamos que si no hay imagen no se muestre el contenedor de la imagen
            if (typeof $src != 'undefined'){
                $('.modal-body').removeClass('d-none');
                $contenedor_zoom.find('.cwp-main-image').attr('src', $src);
            } else if (typeof $src === 'undefined'){
                $contenedor_zoom.find('.cwp-main-image').attr('src', '');
                $('.modal-body').addClass('d-none');
            }
            $contenedor_zoom.find('.card-title').text($titulo);
            $contenedor_zoom.find('.cwp-description').text($descripcion);
            $contenedor_zoom.find('.cwp-price').text($precio);
            for (const $alergeno of $alergenos) {
                $contenedor_zoom.find('ul.cwp-alergenos').append("<li class='list-inline-item'><img src="+$alergeno.currentSrc+ " alt='"+$alergeno.alt+"'></li>")
            }
            //console.log($alergenos_sin_icono);
            for (const $alergeno_sin_icono of $alergenos_sin_icono) {
                console.log($alergeno_sin_icono.innerText)
                $contenedor_zoom.find('ul.cwp-alergenos').append("<li class='list-inline-item my-1 badge bg-secondary'>"+$alergeno_sin_icono.innerText+"</li>");
            }

        });

        // Añadimos un eventlistener para que al cerrarse el modal se borren los alergenos del DOM para que no aparezcan en el siguiente click
        var cwpModalEl = document.getElementById('cwpModal')
        cwpModalEl.addEventListener('hidden.bs.modal', function (event) {
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



