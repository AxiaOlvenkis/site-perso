//Bouton de la modal empty key
$(function(){
    var btn = $("button#valider");
    function search() {
        var $this   = $(this);
        var type = $($this).data('type');
        var search = $("#"+type+" > #search_add").val();
        single_update(langue, search, type);
    }
    btn.click(search);  // attach event handler
});

function single_update(locale, search, type)
{
    var DATA = "search=" + search + "&type=" + type;
    $.ajax({
        type: "POST",
        url: Routing.generate('auto_update_add_search', {'_locale': locale }),
        data: DATA,
        cache: false,
        success: function (data) {
            $("#"+type+" > .search").html(data);
        }
    });
    return false;
}