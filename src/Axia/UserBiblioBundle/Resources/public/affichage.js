$(function(){
    var panel = $("button");
    function aff_edition() {
        var $this   = $(this);
        type = $this.attr('id');
        edition(type);
    }
    panel.click(aff_edition);  // attach event handler
});

function edition(type)
{
    var DATA = "type=" + type;
    $.ajax({
        type: "POST",
        url: Routing.generate('biblio_edition_parts'),
        data: DATA,
        cache: false,
        success: function (data) {
            $(".parts").html(data);
        }
    });
    return false;
}