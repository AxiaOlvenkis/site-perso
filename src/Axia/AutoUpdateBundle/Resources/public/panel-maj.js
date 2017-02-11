//Bouton de la modal empty key
$(function(){
    var btn_maj = $("td .maj-btn");
    function single_maj() {
        var $this   = $(this);
        var id = $this.data('id');
        var type = $('.tab-content > div.active').attr('id');
        single_update(langue, id, type);
    }
    btn_maj.click(single_maj);  // attach event handler
});

function single_update(locale, id, type)
{
    var DATA = "id=" + id + "&type=" + type;
    $.ajax({
        type: "POST",
        url: Routing.generate('auto_update_maj_single', {'_locale': locale }),
        data: DATA,
        cache: false,
        success: function (data) {
            if(data != ''){
                data = $.parseJSON(data);
                $('.tab-content > div.active#' + type + ' #message-'+id).html('élement mis à jour');
                $('.tab-content > div.active#' + type + ' #title-'+id).html(data);
            }
            else{
                $('.tab-content > div.active#' + type + ' #message-'+id).html("l'élément n'a pu être trouvé dans la base de données de référence");
            }
        }
    });
    return false;
}