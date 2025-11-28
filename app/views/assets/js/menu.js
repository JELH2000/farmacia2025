function loaded() {
    var paginaActual = $("#paginaActual").val();

    if(paginaActual == "Autores"){
        //PADRE
        $('#item-mant').removeClass('collapsed');

        //PAGINA ACTUAL
        $('#item-autores').css('color', '#4154f1');

        //OTRAS PAGINAS
        $('#item-libros').removeClass('resaltar');
        $('#item-inicio').addClass('collapsed');
    }else if(paginaActual == "Libros"){
        //PADRE
        $('#item-mant').removeClass('collapsed');

        //PAGINA ACTUAL
        $('#item-libros').css('color', '#4154f1');

        //OTRAS PAGINAS
        $('#item-autores').removeClass('resaltar');
        $('#item-inicio').addClass('collapsed');
    }else if(paginaActual == "Inicio"){
        $('#item-inicio').removeClass('collapsed');
        $('#item-mant').addClass('collapsed');
    }
};