
setInterval( function () {
    var itemId = $('#itemId').val();
    $.post("/item/currentview/" + itemId,
        {
        },
        function(data, status){
            viewSuccess(data);
        });
}, 5000);

function viewSuccess(data) {
    var allViews = $('#all-views').html() - 0;
    var currentViews = data.views - 0;
    $('#all-views').html(allViews + currentViews)
    $('#current-views').html(currentViews);
}
