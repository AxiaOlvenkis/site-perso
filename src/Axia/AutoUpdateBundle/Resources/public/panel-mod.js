//Bouton de la modal empty key
$(function(){
    var btn_modal = $("td .btn-modal");
    function modal_empty_aff() {
        var $this   = $(this);
        var id = $this.data('id');
        var type = $('.tab-content > div.active').attr('id');
        empty_aff(langue, id, type);
    }
    btn_modal.click(modal_empty_aff);  // attach event handler
});

function empty_aff(locale, id, type)
{
    var DATA = "id=" + id + "&type=" + type;
    console.log(DATA);
    $.ajax({
        type: "POST",
        url: Routing.generate('empty_key_single', {'_locale': locale }),
        data: DATA,
        cache: false,
        success: function (data) {
            console.log(data);
            $("#emptyModal").html(data);
        }
    });
    return false;
}