$(function(){
    var panel = $("nav.nav-interne a");
    function aff_liste() {
        var $this   = $(this);
        filtre = $this.attr('id');
        affichage(type, filtre);
        $("nav.nav-interne li").removeClass('active');
        $this.parent().addClass('active');
    }
    panel.click(aff_liste);
});

function affichage(type, filtre)
{
    var DATA = "type=" + type + "&filtre=" + filtre;
    $.ajax({
        type: "POST",
        url: Routing.generate('biblio_parts_liste'),
        data: DATA,
        cache: false,
        success: function (data) {
            $(".lst_biblio").html(data);
        }
    });
    return false;
}

// recherche par titre
$(function(){
    var panel = $("nav.nav-interne .form-search button");
    function aff_liste() {
        var search = $("#search").val();
        var filtre = 'recherche';
        func_search(type, filtre, search);
    }
    panel.click(aff_liste);
});

function func_search(type, filtre, search)
{
    var DATA = "type=" + type + "&filtre=" + filtre + "&search=" + search;
    $.ajax({
        type: "POST",
        url: Routing.generate('biblio_parts_liste'),
        data: DATA,
        cache: false,
        success: function (data) {
            $(".lst_biblio").html(data);
        }
    });
    return false;
}