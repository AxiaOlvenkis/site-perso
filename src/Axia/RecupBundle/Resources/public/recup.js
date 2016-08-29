//Bouton de filtres par Panel
$(function(){
    var panel = $("button");
    function panel_aff() {
        var $this   = $(this);
        type = $this.attr('id');
        affListe(type);
    }
    panel.click(panel_aff);  // attach event handler
});

function affListe(type)
{
    var DATA = "type=" + type;
    $.ajax({
        type: "POST",
        url: Routing.generate('recup_parts'),
        data: DATA,
        cache: false,
        success: function (data) {
            $(".parts").html(data);
        }
    });
    return false;
}