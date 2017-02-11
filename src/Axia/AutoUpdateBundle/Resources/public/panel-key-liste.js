//Bouton de filtres par Panel
$(function(){
    var panel = $(".pan-admin > a");
    function panel_aff() {
        var $this   = $(this);
        var cont = $this.attr('id');
        if(cont === 'add'){
            affAjout(langue, cont);
        }
        else{
            affListe(langue, cont);
        }
    }
    panel.click(panel_aff);  // attach event handler
});

function affListe(locale, type)
{
    var DATA = "choix=" + type;
    $.ajax({
        type: "POST",
        url: Routing.generate('empty_key_liste', {'_locale': locale }),
        data: DATA,
        cache: false,
        success: function (data) {
            $(".content-admin").html(data);
        }
    });
    return false;
}

function affAjout(locale, type)
{
    var DATA = "choix=" + type;
    $.ajax({
        type: "POST",
        url: Routing.generate('auto_update_add_affiche', {'_locale': locale }),
        data: DATA,
        cache: false,
        success: function (data) {
            $(".content-admin").html(data);
        }
    });
    return false;
}