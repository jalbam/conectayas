<?php
    //Se configura el BBClone:
    define("_BBC_PAGE_NAME", "spanish online");
    define("_BBCLONE_DIR", "../bbclone/");
    define("COUNTER", _BBCLONE_DIR."mark_page.php");
    if (is_readable(COUNTER)) include_once(COUNTER);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Conectayas &copy; (por Joan Alba Maldonado)</title>
        <!-- (c) Conectayas - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original. -->
        <script language="JavaScript1.2" type="text/javascript">
            <!--
                //(c) Conectayas - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original.

                //Numero de jugadores:
                var jugadores = 1;
                                
                //Determina el primer jugador:
                var primer_jugador = "usuario";
                //Determina al usuario que le toca tirar:
                var jugador_actual = primer_jugador;
                
                //Determina el color de cada usuario (0 o 1):
                var color_usuario = 1;
                var color_ordenador = (color_usuario == 0) ? 1 : 0; //El ordenador utiliza el color contrario.
                //Colores posibles:
                var colores = new Array("#bb0000", "#0000bb");

                //Numero de cada jugador:
                var numero_usuario = 1;
                var numero_ordenador = (numero_usuario == 1) ? 2 : 1;
                
                //Ancho de las piezas (pixels):
                var piezas_width = 50;
                //Alto de las piezas (pixels):
                var piezas_height = 50;
                
                //Color de los huecos:
                var color_huecos = "#002200";                

                //Espaciado entre un hueco y otro (pixels):
                var tablero_padding = parseInt((piezas_width + piezas_height) / 10);

                //Ancho del tablero (huecos para piezas):
                var tablero_width = 7;
                var tablero_width_maximo = 25; //Maximo de huecos que puede tener el tablero en horizontal.
                //Alto del tablero (huecos para piezas):
                var tablero_height = 6;
                var tablero_height_maximo = 25; //Maximo de huecos que puede tener el tablero en vertical.
                
                //Matriz que guarda el tablero:
                var tablero = new Array(tablero_width * tablero_height);
                
                //Fichas necesarias para ganar (al ponerse seguidas):
                var fichas_necesarias = 4;
                
                //Determina si las fichas caen al tirarlas (Conecta-4) o no (Tres en raya):
                var gravedad = true;

                //Variables que calcularan la diferencia entre las coordenadas del mouse y las del div de opciones:
                var diferencia_posicion_horizontal = false;
                var diferencia_posicion_vertical = false;
                //Variable para saber si se esta arrastrando en un campo seleccionable, y asi no dejar arrastrar:
                var campo_seleccionable = false;

                //Variable que guarda la columna donde la ficha se tira:
                var columna_caida_inicial = 0;
                //Variable que guarda la columna donde la ficha esta cayendo en la animacion:
                var columna_caida = 0;
                //Variable que guarda la ultima celda donde caera la ficha de la animacion:
                var celda_ultima = 0;
                
                //Variables que guarda el intervalo para la animacion de caida:
                var ficha_cayendo = false;

                //Variable para saber si alguien ha tirado ya:
                var se_ha_tirado = false;
                
                
                //Funcion que muestra la pieza diminuta moviendose con el raton, cuando es el turno del usuario:
                function arrastrar_pieza(e, arrastrar)
                {
                    //Si no le toca tirar al usuario o su pieza (o su sombra) esta oculta, se sale de la funcion:
//                    if (jugador_actual != "usuario" || document.getElementById("pieza").style.visibility == "hidden" || document.getElementById("pieza_sombra").style.visibility == "hidden") { return; }
                    if (jugador_actual != "usuario" || document.getElementById("opciones").style.visibility == "visible") { return; }
                    
                    //Si se ha parado de arrastrar, sale de la funcion:
                    if (!arrastrar) { return; }
                    //...pero si se ha enviado arrastrar, se arrastra:
                    else
                    {
                        //Variable para saber si estamos en Internet Explorer o no:
                        var ie = document.all ? true : false;
                        //Si estamos en internet explorer, se recogen las coordenadas del raton de una forma:
                        if (ie)
                        {
                            posicion_x_raton = event.clientX + document.body.scrollLeft;
                            posicion_y_raton = event.clientY + document.body.scrollTop;
                        }
                        //...pero en otro navegador, se recogen de otra forma:
                        else
                        {
                            //document.captureEvents(Event.MOUSEMOVE);
                            posicion_x_raton = e.pageX;
                            posicion_y_raton = e.pageY;
                        } 
                        //Si las coordenadas X o Y del raton son menores que cero, se ponen a cero:
                        if (posicion_x_raton < 0) { posicion_x_raton = 0; }
                        if (posicion_y_raton < 0) { posicion_y_raton = 0; }
                        //Se calculan las nuevas coordenadas del div de las opciones:
                        var posicion_left_pieza = eval(posicion_x_raton - parseInt(piezas_width / 4));
                        var posicion_top_pieza = eval(posicion_y_raton - parseInt(piezas_height / 4));
                        //Si alguna d las coordenadas fuera menos que cero, se ponen a cero:
                        if (posicion_left_pieza < 0) { posicion_left_pieza = 0; }
                        if (posicion_top_pieza < 0) { posicion_top_pieza = 0; }
                        //Se aplican las coordenadas al div de las opciones:                        
                        document.getElementById("pieza").style.left = posicion_left_pieza + "px";
                        document.getElementById("pieza").style.top = posicion_top_pieza + "px";
                        document.getElementById("pieza_sombra").style.left = parseInt(document.getElementById("pieza").style.left) + 4 + "px";
                        document.getElementById("pieza_sombra").style.top = parseInt(document.getElementById("pieza").style.top) + 4 + "px";

                        //Si la pieza esta arriba de la zona de juego, se oculta la pieza:
                        if (parseInt(document.getElementById("pieza").style.left) + parseInt(document.getElementById("pieza").style.width) / 2 < parseInt(document.getElementById("zona_juego").style.left) || parseInt(document.getElementById("pieza").style.top) + parseInt(document.getElementById("pieza").style.height) / 2 < parseInt(document.getElementById("zona_juego").style.top))
                        {
                            //Se oculta la pieza y su sombra:
                            document.getElementById("pieza").style.visibility = "hidden";
                            document.getElementById("pieza_sombra").style.visibility = "hidden";
                        }
                        //...si no, se muestra (la pieza y su sombra):
                        else { document.getElementById("pieza").style.visibility = "visible"; document.getElementById("pieza_sombra").style.visibility = "visible"; }
                    }
                }


                //Funcion que arrastra el menu de opciones:
                function arrastrar_opciones(e, arrastrar)
                {
                    //Si se ha parado de arrastrar, sale de la funcion:
                    if (!arrastrar) { diferencia_posicion_horizontal = false; diferencia_posicion_vertical = false; return; }
                    //...pero si se ha enviado arrastrar, se arrastra:
                    else
                    {
                        //Variable para saber si estamos en Internet Explorer o no:
                        var ie = document.all ? true : false;
                        //Si estamos en internet explorer, se recogen las coordenadas del raton de una forma:
                        if (ie)
                        {
                            posicion_x_raton = event.clientX + document.body.scrollLeft;
                            posicion_y_raton = event.clientY + document.body.scrollTop;
                        }
                        //...pero en otro navegador, se recogen de otra forma:
                        else
                        {
                            //document.captureEvents(Event.MOUSEMOVE);
                            posicion_x_raton = e.pageX;
                            posicion_y_raton = e.pageY;
                        } 
                        //Si las coordenadas X o Y del raton son menores que cero, se ponen a cero:
                        if (posicion_x_raton < 0) { posicion_x_raton = 0; }
                        if (posicion_y_raton < 0) { posicion_y_raton = 0; }

                        //Si se ha enviado arrastrar y no es un campo seleccionable, se arrastra:
                        if (arrastrar_opciones && !campo_seleccionable)
                        {
                            //Si es la primera vez que se arrastra despues del click, se calcula la diferencia inicial:
                            if (!diferencia_posicion_horizontal || !diferencia_posicion_vertical)
                            {
                                //Se calcula la diferencia que hay horizontalmente entre el raton y el div de las opciones:
                                diferencia_posicion_horizontal = eval(posicion_x_raton - parseInt(document.getElementById("opciones").style.left));
                                //Se calcula la diferencia que hay verticalmente entre el raton y el div de las opciones:
                                diferencia_posicion_vertical = eval(posicion_y_raton - parseInt(document.getElementById("opciones").style.top));
                            }
                            //Se calculan las nuevas coordenadas del div de las opciones:
                            var posicion_left_menu = posicion_x_raton - diferencia_posicion_horizontal;
                            var posicion_top_menu = posicion_y_raton - diferencia_posicion_vertical;
                            //Si alguna d las coordenadas fuera menos que cero, se ponen a cero:
                            if (posicion_left_menu < 0) { posicion_left_menu = 0; }
                            if (posicion_top_menu < 0) { posicion_top_menu = 0; }
                            //Se aplican las coordenadas al div de las opciones:                        
                            document.getElementById("opciones").style.left = posicion_left_menu + "px";
                            document.getElementById("opciones").style.top = posicion_top_menu + "px";
                            document.getElementById("opciones_sombra").style.left = posicion_left_menu  + 4 + "px";
                            document.getElementById("opciones_sombra").style.top = posicion_top_menu + 4 + "px";
                        }
                    }
                }
                
                
                //Funcion que muestra el mensaje de espera:
                function mostrar_mensaje(mensaje)
                {
                    //Se pone el mensaje enviado:
                    document.getElementById("mensaje").innerHTML = mensaje;
                    //Si el mensaje no esta vacio, se muestra:
                    if (mensaje != "") { document.getElementById("mensaje").style.visibility = "visible"; }
                    //...pero si no, se oculta:
                    else { document.getElementById("mensaje").style.visibility = "hidden"; }
                }
                

                //Funcion que muestra u oculta las opciones:
                function mostrar_ocultar_opciones()
                {
                    //Si el menu esta visible, se oculta:
                    if (document.getElementById("opciones").style.visibility == "visible") { document.getElementById("opciones").style.visibility = "hidden"; document.getElementById("opciones_sombra").style.visibility = "hidden"; document.getElementById("menu").title = "Abrir opciones"; if (jugador_actual == "usuario") { document.getElementById("zona_juego_invisible").style.visibility = "visible"; } }
                    //...pero si esta oculto, se muestra:
                    else { document.getElementById("opciones").style.visibility = "visible"; document.getElementById("opciones_sombra").style.visibility = "visible"; document.getElementById("menu").title = "Cerrar opciones"; document.getElementById("zona_juego_invisible").style.visibility = "hidden"; }
                }
                

                //Funcion que muestra la pieza cayendose de arriba hasta que coque con una pieza abajo, solo cuando hay gravedad:
                function animacion_caida(celda)
                {
                    var fila_caida = Math.ceil( (celda + 1 ) / tablero_width); //Numero de fila donde se encuentra la celda elegida.
                    columna_caida_inicial = (celda - ((fila_caida - 1) * tablero_width)); //Numero de columna donde se tira la pieza.
                    columna_caida = (celda - ((fila_caida - 1) * tablero_width)); //Numero de columna donde se encuentra la pieza mientras va cayendo.
                    celda_ultima = calcular_caida(celda); //Ultima celda donde caera la pieza que va cayendo.
                    
                    ficha_cayendo = setInterval('document.getElementById(columna_caida).style.background = colores[eval("color_"+jugador_actual)]; if (columna_caida > columna_caida_inicial) { document.getElementById(eval(columna_caida-tablero_width)).style.background = color_huecos; } columna_caida += tablero_width; if (columna_caida >= celda_ultima) { clearInterval(ficha_cayendo); ficha_cayendo = false; document.getElementById(eval(columna_caida-tablero_width)).style.background = color_huecos; poner_pieza_2('+celda+'); }', 25);
                }

                
                //Funcion que aplica las opciones:
                function aplicar_opciones()
                {
                    //Se guardan las opciones enviadas en variables, por comodidad:
                    var tablero_ancho_enviado = parseInt(document.getElementById("tablero_ancho").value);
                    var tablero_alto_enviado = parseInt(document.getElementById("tablero_alto").value);
                    var fichas_necesarias_enviado = parseInt(document.getElementById("fichas_necesarias").value);
                    var color_usuario_enviado = document.getElementById("color_usuario").value;
                    var color_ordenador_enviado = document.getElementById("color_ordenador").value;
                    var gravedad_enviado = (document.getElementById("gravedad").checked) ? true : false;
                    var primer_jugador_enviado = document.getElementById("primer_jugador").value;
                    var jugadores_enviado = parseInt(document.getElementById("numero_jugadores").value);
                    if (jugadores_enviado == 2) { primer_jugador_enviado = "usuario"; }
                    
                    //Si las opciones enviadas son las mismas que ya hay en curso, se sale de la funcion:
                    if (jugadores_enviado == jugadores && tablero_ancho_enviado == tablero_width && tablero_alto_enviado == tablero_height && fichas_necesarias_enviado == fichas_necesarias && color_usuario_enviado == colores[color_usuario] && color_ordenador_enviado == colores[color_ordenador] && gravedad_enviado == gravedad && primer_jugador_enviado == primer_jugador) { return false; }
                    
                    //Variable que guardara los errores al enviar las opciones seteadas, si los hay:
                    var errores = "";
                    
                    //Comprueba y registra los errores en la variable correspondiente:
                    if (fichas_necesarias_enviado == "" || fichas_necesarias_enviado < 2 || fichas_necesarias_enviado > tablero_ancho_enviado || fichas_necesarias_enviado > tablero_alto_enviado || isNaN(fichas_necesarias_enviado)) { errores += "* El numero de fichas necesarias para ganar debe estar entre 2 y un numero igual o menor que el alto del tablero y tambien que el ancho del tablero.\n"; fichas_necesarias_enviado = false; }
                    if (tablero_ancho_enviado == "" || tablero_ancho_enviado < 2 || tablero_ancho_enviado > tablero_width_maximo || isNaN(tablero_ancho_enviado)) { errores += "* El ancho del tablero debe ser entre 2 y " + tablero_width_maximo + ".\n"; tablero_ancho_enviado = false; }
                    if (tablero_alto_enviado == "" || tablero_alto_enviado < 2 || tablero_alto_enviado > tablero_height_maximo || isNaN(tablero_alto_enviado)) { errores += "* El alto del tablero debe ser entre 2 y " + tablero_height_maximo + ".\n"; tablero_alto_enviado = false; }
                    if (color_usuario_enviado == color_ordenador_enviado) { errores += "* El color del usuario no puede ser el mismo que el del ordenador\n"; color_usuario_enviado = false; color_ordenador_enviado = false; }
                    
                    //Si han habido errores, se alerta, restaura los valores y sale de la funcion:
                    if (errores != "")
                    {
                        //Se alerta de los errores:
                        alert("No se pueden aplicar las opciones porque:\n" + errores);
                        
                        //Restaura los valores del formulario de opciones:
                        if (!tablero_ancho_enviado) { document.getElementById("tablero_ancho").value = tablero_width; }
                        if (!tablero_alto_enviado) { document.getElementById("tablero_alto").value = tablero_height; }
                        if (!fichas_necesarias_enviado) { document.getElementById("fichas_necesarias").value = fichas_necesarias; }
                        if (!color_usuario_enviado) { document.getElementById("color_usuario").value = colores[color_usuario]; }
                        if (!color_ordenador_enviado) { document.getElementById("color_ordenador").value = colores[color_ordenador]; }
                        //document.getElementById("gravedad").checked = (gravedad) ? true : false;
                       
                        //Sale de la funcion:
                        return false;
                    }
                    //...pero si no han habido errores, se aplican las opciones:
                    else
                    {
                        //Se pide confirmacion y si se acepta, se procede:
                        if (confirm("Pulsa aceptar para aplicar las opciones. Se va a perder la partida actual."))
                        {

                            if (jugadores == 2 && jugadores_enviado == 1) { numero_usuario = 1; }

                            //Se aplican las opciones:
                            tablero_width = tablero_ancho_enviado;
                            tablero_height = tablero_alto_enviado;
                            fichas_necesarias = fichas_necesarias_enviado;
                            colores[color_usuario] = color_usuario_enviado;
                            colores[color_ordenador] = color_ordenador_enviado;
                            gravedad = (gravedad_enviado) ? true : false;
                            primer_jugador = primer_jugador_enviado;
                            jugadores = jugadores_enviado;
                            //Si esta activado el modo de dos jugadores, se desactiva el formulario de primer jugador:
                            if (jugadores == 2) { document.getElementById('primer_jugador').disabled = true; }
                            else { document.getElementById('primer_jugador').disabled = false; }
                           
                            //Se vuelve a iniciar el juego, con las nuevas opciones:
                            iniciar_juego();
                        
                            //Sale de la funcion:
                            return true;
                        }
                        //...pero si se cancela, se sale de la funcion:
                        else { return false; }
                    }
                }
                

                //Funcion que crea el tablero (vacio):
                function crear_tablero()
                {
                    //Resizear el tablero si es muy grande:
                    if (tablero_width < 12 && tablero_height < 12) { piezas_width = 50; piezas_height = 50; }
                    else if (tablero_width < 18 && tablero_height < 18) { piezas_width = 30; piezas_height = 30; }
                    else if (tablero_width < 24 && tablero_height < 24) { piezas_width = 20; piezas_height = 20; }
                    else { piezas_width = 15; piezas_height = 15; }
                    tablero_padding = parseInt((piezas_width + piezas_height) / 10);

                    //Vacia la matriz:
                    tablero = new Array(tablero_width * tablero_height);
                    //Variables necesarias:
                    var numero_columna = 0; //Contador de columna.
                    var numero_fila = 0; //Contador de fila.
                    var hueco_left = 0; //Posicionador horizontal.
                    var hueco_top = 0; //Posicionador vertical.
                    var codigo_html = ""; //Codigo del HTML que contendra los div.
                    var codigo_html_invisible = ""; //Codigo del HTML que contendra los div invisibles.

                    //Se ajusta la zona del juego al tama�o que va a tener el tablero:
                    document.getElementById("zona_juego").style.width = tablero_width * (piezas_width + tablero_padding) - tablero_padding + "px";
                    document.getElementById("zona_juego").style.height = tablero_height * (piezas_width + tablero_padding) - tablero_padding + "px";
                    document.getElementById("zona_juego_invisible").style.width = document.getElementById("zona_juego").style.width;
                    document.getElementById("zona_juego_invisible").style.height = document.getElementById("zona_juego").style.height;
                    document.getElementById("fondo").style.width = document.getElementById("zona_juego").style.width;
                    document.getElementById("fondo").style.height = document.getElementById("zona_juego").style.height;

                    //Se hace un bucle:
                    for (x=0; x<tablero.length; x++)
                    {
                        //Se borra la matriz:
                        tablero[x] = 0;
                        //Se calcula la posicion horizontal:
                        hueco_left = numero_columna * (piezas_width + tablero_padding);
                        //Se calcula la posicion vertical:
                        hueco_top = numero_fila * (piezas_height + tablero_padding);
                        //Se almacena el codigo:
                        codigo_html += '<div id="'+x+'" style="left:'+hueco_left+'px; top:'+hueco_top+'px; width:'+piezas_width+'px; height:'+piezas_height+'px; position:absolute; background:'+color_huecos+'; color:#ffffff; font-size:10px; font-family:arial; filter:alpha(opacity=80); opacity:0.8; -moz-opacity:0.8; -khtml-opacity:0.8; z-index:3;"></div>';
                        codigo_html_invisible += '<div id="'+x+'_invisible" style="left:'+hueco_left+'px; top:'+hueco_top+'px; width:'+piezas_width+'px; height:'+piezas_height+'px; position:absolute; background:transparent; cursor:crosshair; z-index:11;" onClick="if (jugador_actual == \'usuario\' && !ficha_cayendo) { poner_pieza('+x+'); }"></div>';
                        //Se incrementa una unidad el contador de columnas:
                        numero_columna++;
                        //Si el contador de columnas ha llegado al final, se restaura y se incrementa una fila:
                        if (numero_columna >= tablero_width) { numero_columna = 0; numero_fila++; }
                    }
                    
                    //Se introduce el fondo en el div:
                    document.getElementById("fondo").innerHTML = '<img src="img/fondo.gif" width="'+parseInt(document.getElementById("fondo").style.width)+'" height="'+parseInt(document.getElementById("fondo").style.height)+'" hspace="0" vspace="0">';
                    //Se vuelva el codigo HTML en la pantalla (en el div de la zona de juego):
                    document.getElementById("zona_juego").innerHTML = codigo_html;
                    document.getElementById("zona_juego_invisible").innerHTML = codigo_html_invisible;

                    //Se setean las opciones de la pieza:
                    document.getElementById("pieza").style.background = colores[color_usuario]; //Se aplica el color de la pieza del usuario.
                    document.getElementById("pieza").style.width = parseInt(piezas_width / 2) + "px"; //Se aplica el ancho de la pieza del usuario.
                    document.getElementById("pieza").style.height = parseInt(piezas_height / 2) + "px"; //Se aplica el alto de la pieza del usuario.
                    document.getElementById("pieza_sombra").style.width = document.getElementById("pieza").style.width; //Se aplica el ancho de la sombra de la pieza del usuario.
                    document.getElementById("pieza_sombra").style.height = document.getElementById("pieza").style.height; //Se aplica el alto de la sombra de la pieza del usuario.

                    //Se ajusta la posicion vertical del div con la informacion del autor:
                    document.getElementById("informacion").style.top = parseInt(document.getElementById("zona_juego").style.top) + parseInt(document.getElementById("zona_juego").style.height) + (tablero_padding * 2) + "px";
                }

                
                //Funcion que inicia el juego por primera vez:
                function iniciar_juego_primera_vez()
                {
                    //Muestra el mensaje de cargando:
                    mostrar_mensaje("Cargando...");
                    
                    //Se aplican los valores iniciales a los campos del formulario:
                    document.getElementById("tablero_ancho").value = tablero_width;
                    document.getElementById("tablero_alto").value = tablero_height;
                    document.getElementById("fichas_necesarias").value = fichas_necesarias;
                    document.getElementById("color_usuario").value = colores[color_usuario];
                    document.getElementById("color_ordenador").value = colores[color_ordenador];
                    document.getElementById("gravedad").checked = (gravedad) ? true : false;
                    //Se selecciona la opcion de numero de jugadores en el formulario (y se desactiva el input de primer jugador si los jugadores son dos):
                    if (jugadores == 1) { document.getElementById("numero_jugadores").options[0].selected = true; document.getElementById('primer_jugador').disabled = false; }
                    else { document.getElementById("numero_jugadores").options[1].selected = true; document.getElementById('primer_jugador').disabled = true; }
                    //Selecciona la opcion indicada como primer jugador en el formulario:
                    var primer_jugador_seleccionado = (primer_jugador == "usuario") ? 0 : 1;
                    document.getElementById("primer_jugador").options[primer_jugador_seleccionado].selected = true;

                    //Se hace visible el area de juego:
                    document.getElementById("zona_juego").style.visibility = "visible";

                    //Despues de unos milisegundos, inicia el juego y luego deja de mostrar el mensaje:
                    setTimeout('iniciar_juego(); mostrar_mensaje("");', 10);
                }


                //Funcion que inicia el juego:
                function iniciar_juego()
                {
                    //Muestra el mensaje de cargando:
                    mostrar_mensaje("Cargando...");
                    
                    //Se setea como jugador actual el primer jugador:
                    jugador_actual = primer_jugador;
//                    if (jugadores == 1 && jugador_actual != "ordenador") { jugador_actual = "usuario"; }
                    
                    //Despues de unso milisegundos, crea el tablero, deja de mostrar el mensaje y pasa el turno al jugador actual:
                    setTimeout('crear_tablero(); mostrar_mensaje(""); tirar();', 10);
                }
                

                //Funcion que elige el mejor hueco donde se podria tirar la pieza:
                function elegir_mejor_hueco()
                {
                    //Si el jugador actual es el usuario, retorna -1:
                    if (jugador_actual == "usuario") { return -1; }
                    
                    //Variable que contendra el mejor hueco que pueda usarse:
                    var mejor_hueco = -1;

                    //El ordenador busca si hay algun hueco donde pueda ganar con una sola ficha mas:
                    mejor_hueco = buscar_mejor_hueco("ordenador", fichas_necesarias, false);
                    //...pero si no es posible ganar con una sola ficha, impide que el usuario gane:
                    if (mejor_hueco < 0) { mejor_hueco = buscar_mejor_hueco("usuario", fichas_necesarias, true); }
                    //...pero si el usuario no va a ganar de momento, impide que haga fichas_necesarias-1:
                    if (mejor_hueco < 0 && fichas_necesarias-1 > 1) { if (tablero_width == 3 && tablero_height == 3 && !gravedad && fichas_necesarias == 3 && tablero[4] == 0 && parseInt(Math.random() * 2) == 1) { return 4; } mejor_hueco = buscar_mejor_hueco("usuario", fichas_necesarias-1, true); }
                    //...pero si el usuario no va a hacer fichas_necesarias-1 de momento, impide que haga fichas_necesarias-2:
                    if (!gravedad && mejor_hueco < 0 && fichas_necesarias-2 > 1) { mejor_hueco = buscar_mejor_hueco("usuario", fichas_necesarias-2, true); }
                    //...pero si el usuario no va a hacer fichas_necesarias-2 de momento, busca poder juntar una ficha con otra:
                    if (mejor_hueco < 0 && fichas_necesarias-1 > 1) { for (fichas_bucle=fichas_necesarias-1; fichas_bucle>1; fichas_bucle--) { mejor_hueco = buscar_mejor_hueco("ordenador", fichas_bucle, true); if (mejor_hueco >= 0) { break; } } }
                    //...pero si no se puede encajar ninguna, se intenta tapar una al usuario:
                    if (mejor_hueco < 0 && fichas_necesarias-2 > 1) { for (fichas_bucle=fichas_necesarias-2; fichas_bucle>1; fichas_bucle--) { mejor_hueco = buscar_mejor_hueco("usuario", fichas_bucle, true); if (mejor_hueco >= 0) { break; } } }
                    //...si no hay ninguna ficha con la que poder juntar otra, se calcula un numero aleatorio:
                    if (mejor_hueco < 0)
                    {
                        //Si esta en el tres en raya, y aun nadie ha tirado en medio lo hace:
                        if (tablero_width == 3 && tablero_height == 3 && !gravedad && fichas_necesarias == 3 && tablero[4] == 0) { return 4; }
                        //...pero si no, intenta poner una ficha:
//                        else { mejor_hueco = buscar_mejor_hueco("ordenador", 1, true); }
                        //...pero si no es posible (cosa algo imposible xD), intenta poner donde pondria una ficha el usuario (menudo sinsentido, pero podria ser prevenido):
//                        if (mejor_hueco < 0) { mejor_hueco = buscar_mejor_hueco("usuario", 1, true); }
                        //...pero si no, se escoge un hueco aleatorio, siempre que este vacio:
//                        if (mejor_hueco < 0) { while (tablero[mejor_hueco] != 0) { mejor_hueco = parseInt(Math.random() * (tablero.length)); } }
                        else { while (tablero[mejor_hueco] != 0) { mejor_hueco = parseInt(Math.random() * (tablero.length)); } }
                    }

                    //Se retorna el mejor hueco posible:
                    return mejor_hueco;
                }
                
                
                //Funcion que busca el mejor hueco, dependiendo de quien sea el jugador:
                function buscar_mejor_hueco(jugador, fichas_buscadas, recursivear)
                {
                    //Variable que contendra el hueco buscado:
                    var hueco_buscado = hueco_buscado_horizontal = hueco_buscado_vertical = hueco_buscado_diagonal_izquierda = hueco_buscado_diagonal_derecha = -1;
                    
                    //Recoge el numero del jugador enviado:
                    var numero_jugador = (jugador == "usuario") ? numero_usuario : numero_ordenador;
                   
                    var bucle_tope = 0;
                    if (gravedad) { bucle_tope = tablero_width; }
                    else { bucle_tope = tablero.length; }
                    
                    if (bucle_tope)
                    {
                        var celda_al_caer = -1;
                        //Hacer un bucle para comprobar todas las columnas:
                        for (celda_contador=0; celda_contador < bucle_tope; celda_contador++)
                        {
                            //Si hay gravedad, calcular donde caeria la ficha al tirarla por la columna actual:
                            if (gravedad) { celda_al_caer = calcular_caida(celda_contador); var fila = Math.ceil( (celda_al_caer + 1 ) / tablero_width); var columna = celda_contador; }
                            //...si no, dejarla igual que el contador:
                            else { celda_al_caer = celda_contador; var fila = Math.ceil( (celda_al_caer + 1 ) / tablero_width); var columna = (celda_al_caer - ((fila - 1) * tablero_width)); }

                            //Si la columna esta llena, se sale esta columna y continua el bucle con la siguiente:
                            if (gravedad && celda_al_caer < 0) { continue; }
                            //Si el hueco ya esta ocupado, continue el bucle con el siguiente:
                            if (!gravedad && tablero[celda_al_caer] != 0) { continue; }

                            //Crea una copia del tablero actual pero inserta la ficha ficticia:
                            var tablero_ficticio = new Array(tablero_width*tablero_height);
                            tablero_ficticio = tablero.slice(0, tablero.length);
                            tablero_ficticio[celda_al_caer] = numero_jugador;

                            //Comprueba si al poner la ficha ficticia da la oportunidad de ganar al usuario, y si es asi la descarta:
                            if (recursivear)
                            {
                                //Guarda el contador del bucle actual:
                                var contador_backup = celda_contador;
                                //Si actualmente el usuario no va a hacer linea:
                                if (buscar_mejor_hueco("usuario", fichas_necesarias, false) < 0)
                                {
                                    //Restaura el contador del bucle atual:
                                    celda_contador = contador_backup;
                                    //Pone la ficha del usuario:
                                    tablero[celda_al_caer] = 2;
                                    //Si despues de poner la ficha ficticia puede hacer linea el usuario:
                                    if (buscar_mejor_hueco("usuario", fichas_necesarias, false) >= 0)
                                    {
                                        //Quita la ficha ficticia:
                                        tablero[celda_al_caer] = 0;
                                        //Restaura el contador del bucle actual:
                                        celda_contador = contador_backup;
                                        //Si la celda es la ultima (de la columna si hay gravedad o del tablero si no la hay), retorna -1:
                                        if (gravedad && celda_al_caer+1 >= tablero_width || !gravedad && celda_al_caer+1 >= tablero.length) { return -1; }
                                        //...y si no, continua el bucle:
                                        else { continue; }
                                    }
                                    //...y si no, restaura el contador del bucle actual y borra la ficha ficticia:
                                    else { celda_contador = contador_backup; tablero[celda_al_caer] = 0; } //Quita la ficha ficticia.
                                }
                            }

//                            var columna = (celda_al_caer - ((fila - 1) * tablero_width)); //Numero de columna donde se encuentra la celda elegida.
                            var primera_celda_fila = (fila - 1) * tablero_width; //Numero de la primera celda que esta en la misma columna que la celda elegida.
                            var primera_celda_columna = columna - 1; //Numero de la primera celda que esta en la misma fila que la celda elegida.
                            var primera_celda_diagonal_izquierda_superior = celda_al_caer - (columna - 1) - ((columna - 1) * tablero_width) - tablero_width - 1;
                            if (primera_celda_diagonal_izquierda_superior < 0) { primera_celda_diagonal_izquierda_superior = Math.abs(primera_celda_diagonal_izquierda_superior / tablero_width); }
                            var primera_celda_diagonal_derecha_superior = celda_al_caer + (tablero_width - columna) - ((tablero_width - columna) * tablero_width) + tablero_width - 1;
                            if (primera_celda_diagonal_derecha_superior < 0) { primera_celda_diagonal_derecha_superior = Math.ceil(tablero_width - 1 - Math.abs(primera_celda_diagonal_derecha_superior / tablero_width)) - 1; }
                            var fichas_seguidas = 0; //Contador de fichas seguidas, para saber si ha hecho linea.

//                            if (celda_al_caer == 4 && jugador == "usuario" && !recursivear) { alert(x); }

                            //Comprueba si se ha hecho linea en diagonal izquierda:
                            var x_fila = Math.ceil( (primera_celda_diagonal_izquierda_superior + 1) / tablero_width); //Numero de fila donde se encuentra la celda elegida.
                            var x_columna = (primera_celda_diagonal_izquierda_superior - ((x_fila - 1) * tablero_width)) + 1; //Numero de columna donde se encuentra la celda elegida.
                            for (x = primera_celda_diagonal_izquierda_superior; x <= tablero_width*tablero_height; x+=tablero_width+1)
                            {
//                                if (celda_al_caer == 0 && jugador == "usuario" && !recursivear) { alert(x); }
                                
                                if (tablero_ficticio[x] == numero_jugador) { fichas_seguidas++; } //Si el hueco actual tiene una ficha del jugador actual, se cuenta como seguida.
                                else { fichas_seguidas = 0; } //...pero si no, se pone a cero.
                                //Si ya se ha hecho linea:
                                if (fichas_seguidas >= fichas_buscadas)
                                {
//                                    alert("kuidadin en "+celda_al_caer);
                                    hueco_buscado_diagonal_izquierda = celda_al_caer;
                                    //Si es imposible hacer linea aunque se tape en celda_al_caer, se salta este loop:
                                    if (fila-fichas_necesarias+fichas_seguidas-1 < 0 || columna+fichas_necesarias-fichas_seguidas+1 > tablero_width || columna-fichas_necesarias+fichas_seguidas < 0) { hueco_buscado_diagonal_izquierda = -1; fichas_seguidas = 0; }
                                }
                                //Si se ha alcanzado el final de la columna o el final de las filas, sale del bucle:
                                if (x_columna >= tablero_width || x_fila >= tablero_height) { break; }
                                //Se incrementan los contadores de fila y de columna:
                                x_fila++;
                                x_columna++;
                            }
                            //Si se ha encontrado un buen hueco, sale del bucle:
                            if (hueco_buscado_diagonal_izquierda > 0) { break; }

                            //Comprueba si se ha hecho linea en diagonal derecha:
                            fichas_seguidas = 0;
                            var x_fila = Math.ceil( (primera_celda_diagonal_derecha_superior + 1 ) / tablero_width); //Numero de fila donde se encuentra la celda elegida.
                            var x_columna = (primera_celda_diagonal_derecha_superior - ((x_fila - 1) * tablero_width)) + 1; //Numero de columna donde se encuentra la celda elegida.
//                            if (fichas_buscadas < 3) alert("se buskaran "+fichas_buscadas+" de "+jugador+" en "+celda_al_caer+"...");
                            for (x = primera_celda_diagonal_derecha_superior; x <= tablero_width*tablero_height; x+=tablero_width-1)
                            {
                                if (tablero_ficticio[x] == numero_jugador) { fichas_seguidas++; } //Si el hueco actual tiene una ficha del jugador actual, se cuenta como seguida.
                                else { fichas_seguidas = 0; } //...pero si no, se pone a cero.
                                //Si ya se ha hecho linea:
                                if (fichas_seguidas >= fichas_buscadas)
                                {
//                                    alert("kuidadin en "+celda_al_caer);
//                                alert("kuidado en "+celda_al_caer);
                                    hueco_buscado_diagonal_derecha = celda_al_caer;
                                    //Si es imposible hacer linea aunque se tape en celda_al_caer, se salta este loop:
                                    if (fila-fichas_necesarias+fichas_seguidas-1 < 0 || columna+fichas_necesarias-fichas_seguidas+1 > tablero_width || columna-fichas_necesarias+fichas_seguidas < 0) { hueco_buscado_diagonal_derecha = -1; fichas_seguidas = 0; }
                                }
                                //Si se ha alcanzado el principio de la columna o el final de las filas, sale del bucle:
                                if (x_columna <= 1 || x_fila >= tablero_height) { break; }
                                //Se incrementan los contadores de fila y de columna:
                                x_fila++;
                                x_columna--;
                            }
                            //Si se ha encontrado un buen hueco, sale del bucle:
                            if (hueco_buscado_diagonal_derecha > 0) { break; }
                            
                            //Comprueba si se ha hecho linea en horizontal:
                            fichas_seguidas = 0;
                            for (x = primera_celda_fila; x <= primera_celda_fila+tablero_width-1; x++)
                            {
                                if (tablero_ficticio[x] == numero_jugador) { fichas_seguidas++; } //Si el hueco actual tiene una ficha del jugador actual, se cuenta como seguida.
                                else { fichas_seguidas = 0; } //...pero si no, se pone a cero.
                                //Si ya se ha hecho linea:
                                if (fichas_seguidas >= fichas_buscadas)
                                {
                                    hueco_buscado_horizontal = celda_al_caer;
                                    //Si es imposible hacer linea aunque se tape en celda_al_caer, se salta este loop:
                                    if (columna+fichas_necesarias-fichas_seguidas+1 > tablero_width || columna-fichas_necesarias+fichas_seguidas < 0) { hueco_buscado_horizontal = -1; fichas_seguidas = 0; }
                                }
                            }
                            //Si se ha encontrado un buen hueco, sale del bucle:
                            if (hueco_buscado_horizontal > 0 && gravedad) { break; }

                            //Comprueba si se ha hecho linea en vertical:
                            fichas_seguidas = 0;
                            if (gravedad) { var principio_bucle = celda_al_caer; var final_bucle = celda_al_caer+(tablero_width*fichas_buscadas); }
                            else { var principio_bucle = columna; var final_bucle = tablero.length - tablero_width + columna; }
//                            for (x = celda_al_caer; x <= celda_al_caer+(tablero_width*fichas_buscadas) && fila+fichas_buscadas-1 <= tablero_height; x+=tablero_width)
//                            if (fichas_buscadas == 3) alert("se buskaran "+fichas_buscadas+" de "+jugador+" en "+celda_al_caer+"...");
//                                if (fichas_buscadas == 3 && jugador == "usuario" && recursivear) { alert("MASTER: en "+celda_al_caer + "..fin en: tablero.length("+tablero.length+") - tablero_width("+tablero_width+") + columna("+columna+") = "+final_bucle + " ....ke da fila ("+fila+")+fichas_buscadas("+fichas_buscadas+")-1 = "+eval(fila+fichas_buscadas-1)); }
//                            for (x = principio_bucle; x <= final_bucle && fila+fichas_buscadas-1 <= tablero_height; x+=tablero_width)
                            for (x = principio_bucle; x <= final_bucle; x += tablero_width)
                            {
                                if (tablero_ficticio[x] == numero_jugador) { fichas_seguidas++; } //Si el hueco actual tiene una ficha del jugador actual, se cuenta como seguida.
//                                else { break; } //...pero si sale del bucle.
                                else { fichas_seguidas = 0; } //...pero si no, se pone a cero.
//if (fichas_buscadas == 3 && jugador == "usuario" && recursivear && celda_al_caer == 4) { alert("en "+x+" hay "+tablero_ficticio[x]); }
//                                if (fichas_buscadas == 3 && jugador == "usuario" && recursivear) { alert("en "+x+" hay "+tablero_ficticio[x]); }
//                                if (fichas_buscadas == 3 && celda_al_caer == 5 && jugador == "usuario") { alert("en "+x+" hay "+tablero_ficticio[x]); }
                                //Si ya se ha hecho linea:
//                                if (fichas_seguidas >= fichas_buscadas-1) { alert("kasi kuidadin en "+celda_al_caer); }
                                if (fichas_seguidas >= fichas_buscadas)
                                {
//                                    alert("kuidadin en "+celda_al_caer);
                                    hueco_buscado_vertical = celda_al_caer;
                                    //Si es imposible hacer linea aunque se tape en celda_al_caer, se salta este loop:
                                    if (fila-fichas_necesarias+fichas_seguidas-1 < 0) { hueco_buscado_vertical = -1; fichas_seguidas = 0; }
//                                    else { break; }
                                }
                            }
                            //Si se ha encontrado un buen hueco, sale del bucle:
//                            if (hueco_buscado_vertical > 0 && gravedad) { break; }
                            if (hueco_buscado_vertical > 0) { break; }
                            //Si se ha encontrado algun hueco, sale del bucle:
//                            if (gravedad) { if (hueco_buscado_horizontal > 0 || hueco_buscado_vertical > 0 || hueco_buscado_diagonal_izquierda > 0 || hueco_buscado_diagonal_derecha > 0) { break; } }
                        }
                    }

                    //Si no se ha encontrado el criterio buscado, sale de la funcion retornando -1:
                    if (hueco_buscado_horizontal < 0 && hueco_buscado_vertical < 0 && hueco_buscado_diagonal_izquierda < 0 && hueco_buscado_diagonal_derecha < 0) { return -1; }
                    
                    //Calcula aleatoriamente que hueco usar de los buscados Y ENCONTRADOS (si usar el horizontal, vertical o diagonal izquierda o diagonal derecha):
                    var huecos = new Array(hueco_buscado_horizontal, hueco_buscado_vertical, hueco_buscado_diagonal_izquierda, hueco_buscado_diagonal_derecha);
                    while (hueco_buscado < 0) { hueco_buscado = huecos[parseInt(Math.random() * (huecos.length))]; }
                    
                    //Retorna el mejor hueco:
                    return hueco_buscado;
                }


                //Funcion que calcula donde caeria la pieza al tirarla por una columna, habiendo gravedad:
                function calcular_caida(celda)
                {
                    //Calcula en que columna esta la celda enviada:
                    var fila = Math.ceil( (celda + 1 ) / tablero_width); //Numero de fila donde se encuentra la celda elegida.
                    var columna = (celda - ((fila - 1) * tablero_width)) + 1; //Numero de columna donde se encuentra la celda elegida.
                    //Busca la ultima ficha puesta en la columna:
                    for (x=columna-1; x<=tablero_width*tablero_height-1; x+=tablero_width)
                    {
                        //Si ha encontrado una pieza en la columna, sale del bucle:
                        if (tablero[x] != 0) { break; }
                    }
                    //Si la posicion anterior a la ultima ficha puesta no existe, es que la columna esta llena y returna -1:
                    if (x-tablero_width < 0) { return -1; }
                    //...pero si no, retorna la posicion anterior:
                    else { return x-tablero_width; }
                }


                //Funcion que hace tirar la pieza al ordenador:
                function tirar()
                {
                    //Si el jugador actual es el ordenador y no hay mas de un jugador:
                    if (jugador_actual == "ordenador" && jugadores == 1)
                    {
                        //Muestra el mensaje de procesando:
                        mostrar_mensaje("Procesando...");
                        
                        //Oculta la pieza del jugador:
                        document.getElementById("pieza").style.visibility = "hidden";
                        document.getElementById("pieza_sombra").style.visibility = "hidden";
                        
                        //Oculta las casillas invisibles:
                        document.getElementById("zona_juego_invisible").style.visibility = "hidden";
                    }
                    //...pero si es el usuario:
                    else if (document.getElementById("opciones").style.visibility != "visible")
                    {
                        //Deja de mostrar el mensaje de procesando:
                        mostrar_mensaje("");

                        //Muestra la pieza del jugador:
                        document.getElementById("pieza").style.visibility = "visible";
                        document.getElementById("pieza_sombra").style.visibility = "visible";

                        //Muestra las casillas invisibles:
                        document.getElementById("zona_juego_invisible").style.visibility = "visible";
                    } else { mostrar_mensaje(""); } //Si no ocurre ninguna de las dos condiciones (debe tocarle al usuario pero las opciones estan abiertas, se quita el mensaje de procesando.

                    //Despues de unos milisegundos, calcula el mejor hueco (si es el turno del usuario, retornara -1) y dispone para que ponga la pieza el jugador actual:
                    setTimeout("var mejor_hueco = elegir_mejor_hueco(); poner_pieza(mejor_hueco);", 10);
                }                


                //Funcion que pone la pieza en el tablero (paso 1, antes de la animacion):
                function poner_pieza(celda)
                {
                    //Si el jugador actual es el usuario y no se ha enviado donde poner la pieza, sale de la funcion:
                    if (jugador_actual == "usuario" && celda < 0) { return; }
                    //Si el hueco elegido no esta libre, sale de la funcion:
                    if (tablero[celda] != 0) { return; }
                    
                    //Se setea conforme ya se ha tirado:
                    se_ha_tirado = true;
                    
                    //Calcula donde se ha elegido poner la pieza (tiene en cuenta la gravedad, si la hay):
                    if (gravedad)
                    {
                        //Se calcula donde va a caer la pieza:
                        celda = calcular_caida(celda);
                        
                        //Se hace caer la pieza hasta el hueco elegido:
                        animacion_caida(celda);
                    } else { poner_pieza_2(celda); } //Si no hay gravedad, continua con el juego.
                }


                //Funcion que pone la pieza en el tablero (paso 2, despues de la animacion):
                function poner_pieza_2(celda)
                {
                    //Pone la pieza en la matriz:
                    tablero[celda] = (jugador_actual == "usuario") ? numero_usuario : numero_ordenador;
                    
                    //Pone la pieza visualmente:
                    document.getElementById(celda).style.background = colores[eval("color_"+jugador_actual)];
                    
                    //Quita el evento onclick en la celda invisible:
                    document.getElementById(celda+"_invisible").onclick = function() { };

                    //Quita el cursor en forma de cruz a la celda invisible:
                    document.getElementById(celda+"_invisible").style.cursor = "default";
                   
                    //Comprueba si alguien ha ganado:
                    partida_acabada = comprobar_ganador(celda);
                    
                    //Si la partida se ha acabado, sale de la funcion:
                    if (partida_acabada) { return; }

                    //Pasa el turno al adversario:
                    if (jugadores == 1) { jugador_actual = (jugador_actual == "usuario") ? "ordenador" : "usuario"; }
                    else { jugador_actual = "usuario"; var color_usuario_backup = colores[color_usuario]; colores[color_usuario] = colores[color_ordenador]; colores[color_ordenador] = color_usuario_backup; document.getElementById("pieza").style.background = colores[color_usuario]; numero_usuario = (numero_usuario == 1) ? 2 : 1; }

                    //Continua el juego, tirando el otro:
                    tirar();
                }
                

                //Funcion que comprueba si alguien ha ganado:
                function comprobar_ganador(celda)
                {
                    //Variable que guarda si hay un ganador:
                    var ganador = "";
                    
                    //Matriz que calcula las fichas que hacen linea:
                    var tablero_lineas = new Array(tablero_width*tablero_height);
                    for (x_tablero_lineas = 0; x_tablero_lineas < tablero_lineas.length; x_tablero_lineas++) { tablero_lineas[x_tablero_lineas] = 0; }
                    
                    //Comprueba si la nueva ficha puesta en el hueco ha formado linea en vertical, horizontal o diagonal:
                    var numero_jugador = (jugador_actual == "usuario") ? numero_usuario : numero_ordenador; //Recoge el numero del jugador actual.
                    var fila = Math.ceil( (celda + 1 ) / tablero_width); //Numero de fila donde se encuentra la celda elegida.
                    var columna = (celda - ((fila - 1) * tablero_width)) + 1; //Numero de columna donde se encuentra la celda elegida.
                    var primera_celda_fila = (fila - 1) * tablero_width; //Numero de la primera celda que esta en la misma columna que la celda elegida.
                    var primera_celda_columna = columna - 1; //Numero de la primera celda que esta en la misma fila que la celda elegida.
                    var primera_celda_diagonal_izquierda_superior = celda - (columna - 1) - ((columna - 1) * tablero_width);
                    if (primera_celda_diagonal_izquierda_superior < 0) { primera_celda_diagonal_izquierda_superior = Math.abs(primera_celda_diagonal_izquierda_superior / tablero_width); }
                    var primera_celda_diagonal_derecha_superior = celda + (tablero_width - columna) - ((tablero_width - columna) * tablero_width);
//                    alert("!!de "+celda+" es "+primera_celda_diagonal_derecha_superior);
                    if (primera_celda_diagonal_derecha_superior < 0) { primera_celda_diagonal_derecha_superior = Math.ceil(tablero_width - 1 - Math.abs(primera_celda_diagonal_derecha_superior / tablero_width)) - 1; }
                    var fichas_seguidas = 0; //Contador de fichas seguidas, para saber si ha hecho linea.
//                    alert("Superior izquierda: "+primera_celda_diagonal_izquierda_superior+"\nInferior izquierda: "+primera_celda_diagonal_izquierda_inferior);
                    
                    //Comprueba si se ha hecho linea en horizontal:
                    var comienzo_linea = primera_celda_fila;
                    for (x = primera_celda_fila; x <= primera_celda_fila+tablero_width-1; x++)
                    {
                        if (tablero[x] == numero_jugador) { fichas_seguidas++; } //Si el hueco actual tiene una ficha del jugador actual, se cuenta como seguida.
                        else { fichas_seguidas = 0; comienzo_linea = x; } //...pero si no, se pone a cero.
                        if (fichas_seguidas >= fichas_necesarias)
                        {
//                            var fila_comienzo_linea = Math.ceil( (comienzo_linea + 1 ) / tablero_width);
//                            var columna_comienzo_linea = (comienzo_linea - ((fila_comienzo_linea - 1) * tablero_width)) + 1;
                            var comienzo_bucle = (comienzo_linea == primera_celda_fila) ? comienzo_linea : comienzo_linea+1;
                            var salir_del_bucle = false;
                            for (contador_comienzo_linea = comienzo_bucle; contador_comienzo_linea <= primera_celda_fila+tablero_width-1; contador_comienzo_linea++) { if (tablero[contador_comienzo_linea] == numero_jugador) { tablero_lineas[contador_comienzo_linea] = 1; salir_del_bucle = true; } else if (salir_del_bucle) { break; } }
                            ganador = jugador_actual;
                            break;
                        } //Si ya se ha hecho linea, sale del bucle y se define al jugador actual como ganador.
                    }

                    //Comprueba si se ha hecho linea en vertical:
                    comienzo_linea = primera_celda_columna;
                    fichas_seguidas = 0;
                    for (x = primera_celda_columna; x <= tablero_width*tablero_height-1; x+=tablero_width)
                    {
                        if (tablero[x] == numero_jugador) { fichas_seguidas++; } //Si el hueco actual tiene una ficha del jugador actual, se cuenta como seguida.
                        else { fichas_seguidas = 0; comienzo_linea = x; } //...pero si no, se pone a cero.
                        if (fichas_seguidas >= fichas_necesarias)
                        {
//                            var fila_comienzo_linea = Math.ceil( (comienzo_linea + 1 ) / tablero_width);
//                            var comienzo_bucle = (fila_comienzo_linea <= 1) ? comienzo_linea : comienzo_linea+tablero_width;
                            var comienzo_bucle = (comienzo_linea == primera_celda_columna) ? comienzo_linea : comienzo_linea+tablero_width;
                            salir_del_bucle = false;
                            for (contador_comienzo_linea = comienzo_bucle; contador_comienzo_linea <= tablero_width*tablero_height-tablero_width+columna-1; contador_comienzo_linea+=tablero_width) { if (tablero[contador_comienzo_linea] == numero_jugador) { tablero_lineas[contador_comienzo_linea] = 1; salir_del_bucle = true; } else if (salir_del_bucle) { break; } }
//                            for (contador_comienzo_linea = tablero_width*tablero_height-tablero_width+columna-1; contador_comienzo_linea >= comienzo_linea; contador_comienzo_linea-=tablero_width) { if (tablero[contador_comienzo_linea] == numero_jugador) { tablero_lineas[contador_comienzo_linea] = 1; } else { break; } }
                            ganador = jugador_actual;
                            break;
                        } //Si ya se ha hecho linea, sale del bucle y se define al jugador actual como ganador.
                    }

                    //Comprueba si se ha hecho linea en diagonal izquierda:
                    comienzo_linea = primera_celda_diagonal_izquierda_superior;
                    fichas_seguidas = 0;
                    var x_fila = Math.ceil( (primera_celda_diagonal_izquierda_superior + 1) / tablero_width); //Numero de fila donde se encuentra la celda elegida.
                    var x_columna = (primera_celda_diagonal_izquierda_superior - ((x_fila - 1) * tablero_width)) + 1; //Numero de columna donde se encuentra la celda elegida.
                    for (x = primera_celda_diagonal_izquierda_superior; x <= tablero_width*tablero_height; x+=tablero_width+1)
                    {
//                        alert("en "+x+" hay "+tablero[x] + "\ncolumna: "+x_columna+"\nfila: "+x_fila);
                        if (tablero[x] == numero_jugador) { fichas_seguidas++; } //Si el hueco actual tiene una ficha del jugador actual, se cuenta como seguida.
                        else { fichas_seguidas = 0; comienzo_linea = x; } //...pero si no, se pone a cero.
                        if (fichas_seguidas >= fichas_necesarias)
                        {
                            for (j=x; j<=tablero_width*tablero_height; j+=tablero_width+1) { if (x_columna >= tablero_width || x_fila >= tablero_height) { break; } } //Se calcula la ultima celda de la diagonal.
//                            alert(j+" en "+primera_celda_diagonal_izquierda_superior);
//                            var fila_comienzo_linea = Math.ceil( (comienzo_linea + 1 ) / tablero_width);
//                            var comienzo_bucle = (fila_comienzo_linea <= 1) ? comienzo_linea : comienzo_linea+tablero_width+1;
                            var comienzo_bucle = (comienzo_linea == primera_celda_diagonal_izquierda_superior) ? comienzo_linea : comienzo_linea+tablero_width+1;
                            salir_del_bucle = false;
                            for (contador_comienzo_linea = comienzo_bucle; contador_comienzo_linea <= j; contador_comienzo_linea+=tablero_width+1) { if (tablero[contador_comienzo_linea] == numero_jugador) { tablero_lineas[contador_comienzo_linea] = 1; salir_del_bucle = true; } else if (salir_del_bucle) { break; } }
                            ganador = jugador_actual;
                            break;
                        } //Si ya se ha hecho linea, sale del bucle y se define al jugador actual como ganador.
                        //Si se ha alcanzado el final de la columna o el final de las filas, sale del bucle:
                        if (x_columna >= tablero_width || x_fila >= tablero_height) { break; }
                        //Se incrementan los contadores de fila y de columna:
                        x_fila++;
                        x_columna++;
                    }

                    //Comprueba si se ha hecho linea en diagonal derecha:
                    comienzo_linea = primera_celda_diagonal_derecha_superior;
                    fichas_seguidas = 0;
                    var x_fila = Math.ceil( (primera_celda_diagonal_derecha_superior + 1) / tablero_width); //Numero de fila donde se encuentra la celda elegida.
                    var x_columna = (primera_celda_diagonal_derecha_superior - ((x_fila - 1) * tablero_width)) + 1; //Numero de columna donde se encuentra la celda elegida.
                    for (x = primera_celda_diagonal_derecha_superior; x <= tablero_width*tablero_height; x+=tablero_width-1)
                    {
                        if (tablero[x] == numero_jugador) { fichas_seguidas++; } //Si el hueco actual tiene una ficha del jugador actual, se cuenta como seguida.
                        else { fichas_seguidas = 0; comienzo_linea = x; } //...pero si no, se pone a cero.
                        if (fichas_seguidas >= fichas_necesarias)
                        {
                            for (j=x; j<=tablero_width*tablero_height; j+=tablero_width-1) { if (x_columna <= 1 || x_fila >= tablero_height) { break; } } //Se calcula la ultima celda de la diagonal.
//                            var fila_comienzo_linea = Math.ceil( (comienzo_linea + 1 ) / tablero_width);
//                            var comienzo_bucle = (fila_comienzo_linea <= 1) ? comienzo_linea : comienzo_linea+tablero_width-1;
                            var comienzo_bucle = (comienzo_linea == primera_celda_diagonal_derecha_superior) ? comienzo_linea : comienzo_linea+tablero_width-1;
                            salir_del_bucle = false;
                            for (contador_comienzo_linea = comienzo_bucle; contador_comienzo_linea <= j; contador_comienzo_linea+=tablero_width-1) { if (tablero[contador_comienzo_linea] == numero_jugador) { tablero_lineas[contador_comienzo_linea] = 1; salir_del_bucle = true; } else if (salir_del_bucle) { break; } }
                            ganador = jugador_actual;
                            break;
                        } //Si ya se ha hecho linea, sale del bucle y se define al jugador actual como ganador.
                        //Si se ha alcanzado el principio de la columna o el final de las filas, sale del bucle:
                        if (x_columna <= 1 || x_fila >= tablero_height) { break; }
                        //Se incrementan los contadores de fila y de columna:
                        x_fila++;
                        x_columna--;
                    }
                    
                    
                    //Si alguien ha ganado, lo notifica, vuevle a comenzar el juego y sale retornando true:
                    if (ganador != "")
                    {
                        //Se resaltan las lineas:
                        for (x_resaltar = 0; x_resaltar < tablero_lineas.length; x_resaltar++)
                        {
                            if (tablero_lineas[x_resaltar] == 1) { document.getElementById(x_resaltar).style.width = parseInt((parseInt(document.getElementById(x_resaltar).style.width) / 1.1)) + "px"; document.getElementById(x_resaltar).style.height = parseInt((parseInt(document.getElementById(x_resaltar).style.height) / 1.1)) + "px"; }
                        }
                        
                        //Si gana el usuario, le da la enhorabuena:
                        if (ganador == "usuario")
                        {
                            //Esconde la pieza y su sombra:
                            document.getElementById("pieza").style.visibility = "hidden";
                            document.getElementById("pieza_sombra").style.visibility = "hidden";
                            //Da la enhorabuena:
                            if (jugadores == 2) { mostrar_mensaje("Felicidades jugador "+numero_jugador); alert("Ha ganado el jugador "+numero_jugador); }
                            else { mostrar_mensaje("Has ganado"); alert("Enhorabuena! has ganado."); }
                            //Deja de mostrar el mensaje:
                            mostrar_mensaje("");
                        }
                        else { mostrar_mensaje("Has perdido"); alert("Has perdido."); mostrar_mensaje(""); } //Si gana el ordenador, se notifica.
                        if (jugadores == 1) { primer_jugador = (primer_jugador == "usuario") ? "ordenador" : "usuario"; } //Alterna el primer jugador. 
                        else { jugador_actual = "usuario"; var color_usuario_backup = colores[color_usuario]; colores[color_usuario] = colores[color_ordenador]; colores[color_ordenador] = color_usuario_backup; document.getElementById("pieza").style.background = colores[color_usuario]; numero_usuario = (numero_usuario == 1) ? 2 : 1; }
                        //Selecciona la opcion indicada como primer jugador en el formulario:
                        var primer_jugador_seleccionado = (primer_jugador == "usuario") ? 0 : 1;
                        document.getElementById("primer_jugador").options[primer_jugador_seleccionado].selected = true;
                        iniciar_juego(); //Inicia el juego otra vez.
                        return true; //Sale de la funcion retornando true.
                    }
                    //...pero si nadie ha ganado, retorna false:
                    else
                    {
                        //Si el talbero esta todo usado, lo notifica y vuelve a comenzar el juego:
                        var huecos_libres = false;
                        for (x=0; x<tablero.length; x++) { if (tablero[x] == 0) { huecos_libres = true; } }
                        if (!huecos_libres)
                        {
                            mostrar_mensaje("No hay ganador");
                            if (jugadores == 1) { primer_jugador = (primer_jugador == "usuario") ? "ordenador" : "usuario"; }
                            else { jugador_actual = "usuario"; var color_usuario_backup = colores[color_usuario]; colores[color_usuario] = colores[color_ordenador]; colores[color_ordenador] = color_usuario_backup; document.getElementById("pieza").style.background = colores[color_usuario]; numero_usuario = (numero_usuario == 1) ? 2 : 1; }
                            var primer_jugador_seleccionado = (primer_jugador == "usuario") ? 0 : 1;
                            document.getElementById("primer_jugador").options[primer_jugador_seleccionado].selected = true;
                            alert("El juego ha terminado, ya no hay mas huecos libres");
                            iniciar_juego();
                            return true;
                        }
                        //...pero si no, retorna false (aun no se ha acabado la partida):
                        else { return false; }
                    }
                }
               
            // -->
        </script>
    </head>
    <body onLoad="iniciar_juego_primera_vez();" onMouseMove="arrastrar_pieza(event, true);" onMouseUp="campo_seleccionable = false; document.onmousemove=function(event) { arrastrar_opciones(event, false); }" bgcolor="#ddddef" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <!-- Menu: -->
        <div id="menu" style="left:110px; top:10px; width:65px; height:20px; position:absolute; border:0px; padding:0px; background:transparent; color:#000000; text-align:center; line-height:20px; text-decoration:none; font-family:arial; font-size:12px; cursor:pointer; cursor:hand; -moz-user-select:none; z-index:2;" title="Abrir opciones" onMouseOver="this.style.fontWeight = 'bold';" onMouseOut="this.style.fontWeight = 'normal';" onClick="mostrar_ocultar_opciones();" onSelectStart="return false;">
            Opciones
        </div>
        <div id="juego_nuevo" style="left:8px; top:10px; width:110px; height:20px; position:absolute; border:0px; padding:0px; background:transparent; color:#000000; text-align:center; line-height:20px; text-decoration:none; font-family:arial; font-size:12px; cursor:pointer; cursor:hand; -moz-user-select:none; z-index:2;" title="Juego nuevo" onMouseOver="this.style.fontWeight = 'bold';" onMouseOut="this.style.fontWeight = 'normal';" onClick="if (se_ha_tirado && confirm('Pulsa aceptar para iniciar un juego nuevo. Se perdera el actual.')) { clearInterval(ficha_cayendo); ficha_cayendo = false; iniciar_juego(); }" onSelectStart="return false;">
            Nuevo juego
        </div>
        <!-- Fin de Menu. -->
        <!-- Zona de juego: -->
        <div id="fondo" style="left:20px; top:30px; width:450px; height:450px; position:absolute; border:0px; padding:0px; background:#efdddd; color:#333333; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; z-index:1;"><img src="img/fondo.gif" width="450" height="450" hspace="0" vspace="0"></div>
        <div id="zona_juego" style="left:20px; top:30px; width:450px; height:450px; visibility:visible; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; z-index:2;"></div>
        <div id="zona_juego_invisible" style="left:20px; top:30px; width:450px; height:450px; visibility:visible; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; z-index:10;"></div>
        <!-- Fin de Zona de juego. -->
        <!-- Menu de Opciones: -->
        <div id="opciones" style="left:40px; top:50px; width:340px; height:480px; visibility:hidden; position:absolute; border:0px; padding:0px; background:#efefdd; color:#333333; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; filter:alpha(opacity=70); opacity:0.7; -moz-opacity:0.7; -khtml-opacity:0.7; cursor:crosshair; z-index:5;" onMouseUp="campo_seleccionable = false; document.onmousemove=function(event) { arrastrar_opciones(event, false); }" onMouseDown="if (campo_seleccionable) { campo_seleccionable = false; document.onmousemove=function(event) { arrastrar_opciones(event, false); } } else { document.onmousemove=function(event) { arrastrar_opciones(event, true); } }">
            <div style="left:312px; top:2px; position:absolute; cursor:pointer; cursor:hand; -moz-user-select:none;" title="Cerrar opciones" onMouseOver="this.style.fontWeight = 'bold';" onMouseOut="this.style.fontWeight = 'normal';" onClick="mostrar_ocultar_opciones();" onSelectStart="return false;">[x]&nbsp;</div>
            <div style="left:20px; top:20px; position:absolute; cursor:crosshair;">
                <form style="display:inline;" onSubmit="aplicar_opciones(); return false;" align="center">
                   <center>
                       <fieldset style="width:280px; cursor:crosshair;">
                           <legend style="width:280px; color:#aa0000; font-size:20px; font-weight:bold; text-align:center; -moz-user-select:none;" onSelectStart="return false;" title="Men&uacute;n de opciones">Opciones</legend>
                           <br>
                           <center>
                               <select id="numero_jugadores" name="numero_jugadores" style="cursor:pointer; cursor:hand;" onChange="if (parseInt(this.value) == 2) { document.getElementById('primer_jugador').disabled = true; } else { document.getElementById('primer_jugador').disabled = false; }">
                                   <option value="1">1 Jugador (usuario contra ordenador)</option>
                                   <option value="2">2 Jugadores (usuario contra usuario)</option>
                               </select>
                           </center>
                           <br>
                           <label for="tablero_ancho" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="t" title="Ancho del tablero (n&uacute;mero de huecos)"><b style="-moz-user-select:none;" onSelectStart="return false;">&nbsp; Ancho del <u>t</u>ablero:</b> <input type="text" name="tablero_ancho" id="tablero_ancho" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" size="4" maxlength="3" style="height:22px; color:#0000aa; background:#ffffdd; font-family:courier; line-height:12px; font-size:12px; font-weight:bold;" accesskey="t"></label>
                           <br>
                           <br>
                           <label for="tablero_alto" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="e" title="Alto del tablero (n&uacute;mero de huecos)"><b style="-moz-user-select:none;" onSelectStart="return false;">&nbsp; Alto d<u>e</u>l tablero:</b> <input type="text" name="tablero_alto" id="tablero_alto" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" size="4" maxlength="3" style="height:22px; color:#0000aa; background:#ffffdd; font-family:courier; line-height:12px; font-size:12px; font-weight:bold;" accesskey="e"></label>
                           <br>
                           <br>
                           <label for="fichas_necesarias" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="n" title="Fichas que se necesitan poner en l&iacute;nea para ganar"><b style="-moz-user-select:none;" onSelectStart="return false;">&nbsp; Fichas <u>n</u>ecesarias para ganar:</b> <input type="text" name="fichas_necesarias" id="fichas_necesarias" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" size="4" maxlength="3" style="height:22px; color:#0000aa; background:#ffffdd; font-family:courier; line-height:12px; font-size:12px; font-weight:bold;" accesskey="n"></label>
                           <br>
                           <br>
                           <label for="color_usuario" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="u" title="Color de la ficha del usuario (RGB hexadecimal en HTML)"><b style="-moz-user-select:none;" onSelectStart="return false;">&nbsp; Color <u>u</u>suario:</b> <input type="text" name="color_usuario" id="color_usuario" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" size="8" maxlength="7" style="height:22px; color:#0000aa; background:#ffffdd; font-family:courier; line-height:12px; font-size:12px; font-weight:bold;" accesskey="u"></label>
                           <br>
                           <br>
                           <label for="color_ordenador" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="c" title="Color de la ficha del oponente (RGB hexadecimal en HTML)"><b style="-moz-user-select:none;" onSelectStart="return false;">&nbsp; <u>C</u>olor oponente:</b> <input type="text" name="color_ordenador" id="color_ordenador" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" size="8" maxlength="7" style="height:22px; color:#0000aa; background:#ffffdd; font-family:courier; line-height:12px; font-size:12px; font-weight:bold;" accesskey="c"></label>
                           <br>
                           <br>
                           <label for="gravedad" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="g" title="Si se activa la gravedad, las piezas caer&aacute;n"><b style="-moz-user-select:none;" onSelectStart="return false;">&nbsp; <input type="checkbox" name="gravedad" id="gravedad" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" accesskey="g" style="cursor:pointer; cursor:hand;"> Activar <u>g</u>ravedad</b></label>
                           <br>
                           <br>
                           <label for="primer_jugador" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="i" title="Qu&eacute; usuario tirar&aacute; primero">
                           <b style="-moz-user-select:none;" onSelectStart="return false;">&nbsp; Pr<u>i</u>mer jugador:</b>
                           <select id="primer_jugador" name="primer_jugador" accesskey="i" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" style="cursor:pointer; cursor:hand;">
                               <option value="usuario">usuario</option>
                               <option value="ordenador">ordenador</option>
                           </select>
                           </label>
                           <br>
                           <br>
                           <center><input type="submit" value="Aplicar" name="boton_aplicar" style="height:24px; color:#aa0000; font-weight:bold; text-align:center; line-height:12px; font-size:12px; font-family:arial; cursor:pointer; cursor:hand; -moz-user-select:none;" accesskey="a" onSelectStart="return false;" title="Aplicar opciones"></center>
                           <br>
                       </fieldset>
                    </center>
                </form>
            </div>
        </div>
        <div id="opciones_sombra" style="left:44px; top:54px; width:340px; height:480px; visibility:hidden; position:absolute; border:0px; padding:0px; background:#aaaaaa; color:#333333; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; filter:alpha(opacity=70); opacity:0.7; -moz-opacity:0.7; -khtml-opacity:0.7; cursor:crosshair; z-index:4;" onMouseUp="campo_seleccionable = false; document.onmousemove=function(event) { arrastrar_opciones(event, false); }" onMouseDown="if (campo_seleccionable) { campo_seleccionable = false; document.onmousemove=function(event) { arrastrar_opciones(event, false); } } else { document.onmousemove=function(event) { arrastrar_opciones(event, true); } }"></div>
        <!-- Fin de Menu de Opciones. -->
        <!-- Pieza: -->
        <div id="pieza" style="left:20px; top:30px; width:50px; height:50px; visibility:hidden; position:absolute; border:0px; padding:0px; background:#0000bb; color:#000000; text-align:left; line-height:40px; text-decoration:none; font-weight:bold; font-family:verdana; font-size:20px; filter:alpha(opacity=70); opacity:0.7; -moz-opacity:0.7; -khtml-opacity:0.7; z-index:5;"></div>
        <div id="pieza_sombra" style="left:24px; top:34px; width:50px; height:50px; visibility:hidden; position:absolute; border:0px; padding:0px; background:#000000; color:#000000; text-align:left; line-height:40px; text-decoration:none; font-weight:bold; font-family:verdana; font-size:20px; filter:alpha(opacity=70); opacity:0.7; -moz-opacity:0.7; -khtml-opacity:0.7; z-index:4;"></div>
        <!-- Fin de Pieza. -->
        <!-- Mensaje: -->
        <div id="mensaje" style="left:20px; top:30px; width:300px; height:40px; visibility:visible; position:absolute; border:0px; padding:0px; background:#ddefdd; color:#000000; text-align:center; line-height:40px; text-decoration:none; font-weight:bold; font-family:verdana; font-size:20px; filter:alpha(opacity=90); opacity:0.9; -moz-opacity:0.9; -khtml-opacity:0.9; z-index:10;">
            Cargando...
        </div>
        <!-- Fin de Mensaje. -->
        <!-- Informacion: -->
        <div id="informacion" style="left:10px; top:490px; height:0px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; z-index:3;">
            &copy; <b>Conectayas</b> 0.17a por <i>Joan Alba Maldonado</i> (<a href="mailto:granvino@granvino.com">granvino@granvino.com</a>) &nbsp;<sup>(100% DHTML)</sup>
            <br>&nbsp;&nbsp;- Prohibido publicar, reproducir o modificar sin citar expresamente al autor original.
            <br>
            &nbsp;&nbsp;<i>Dedicado a Yasmina Llaveria del Castillo</i>
        <!-- Fin de Informacion. -->
    </body>
</html>
