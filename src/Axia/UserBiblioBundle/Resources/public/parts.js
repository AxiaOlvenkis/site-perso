//Bouton de filtres par Panel
$(function(){
    var panel = $("nav.bibliotheque a");
    function panel_aff() {
        var $this   = $(this);
        type = $this.attr('id');
        if(type == 'Accueil')
        {
            affAccueil();
        }
        else if(type == 'Course')
        {
            affCourse();
        }
        else
        {
            affListe(type);
        }

        $("nav.bibliotheque li").removeClass('active');
        $this.parent().addClass('active');
    }
    panel.click(panel_aff);  // attach event handler
});

function affListe(type)
{
    var DATA = "type=" + type;
    $.ajax({
        type: "POST",
        url: Routing.generate('biblio_parts'),
        data: DATA,
        cache: false,
        success: function (data) {
            $(".biblio").html(data);
        }
    });
    return false;
}

function affAccueil()
{
    $.ajax({
        type: "POST",
        url: Routing.generate('biblio_parts_accueil'),
        cache: false,
        success: function (data) {
            $(".biblio").html(data);
        }
    });
    return false;
}

function affCourse()
{
    $.ajax({
        type: "POST",
        url: Routing.generate('biblio_parts_course'),
        cache: false,
        success: function (data) {
            $(".biblio").html(data);
        }
    });
    return false;
}