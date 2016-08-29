// Modification du dernier épisode vu
$(function(){
    var button = $("button.moinsPlus");
    function modif_nb() {
        var $this   = $(this);
        var id = $this.attr('id');
        var action = $this.children('span').attr('id');
        modifier_nb(id, action);
    }
    button.click(modif_nb);  // attach event handler
});

function modifier_nb(id, action)
{
    var DATA = "id=" + id + "&action=" + action;
    $.ajax({
        type: "POST",
        url: Routing.generate('biblio_edition_nombre'),
        data: DATA,
        cache: false,
        success: function (data) {
            $("#number").html(data);
        }
    });
    return false;
}

// Modification des true/false sur le vu/possede
$(function(){
    var button = $(".LuPossede > button");
    function modif_bool() {
        var $this   = $(this);
        bool_id = $this.parent(".LuPossede").attr('id');
        bool_action = $this.attr('class');
        modifier_bool(bool_id, bool_action);
    }
    button.click(modif_bool);  // attach event handler
});

function modifier_bool(id, action)
{
    var DATA = "id=" + id + "&action=" + action;
    $.ajax({
        type: "POST",
        url: Routing.generate('biblio_edition_bool'),
        data: DATA,
        cache: false,
        success: function (data) {
            var btn = $(".LuPossede#" + bool_id + " > ." + bool_action);
            btn.html(data);
        }
    });
    return false;
}