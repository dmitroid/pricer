var showBlock = 0;

function showPagination()
{
    if (showBlock) {
        $('#hidden-pagination').fadeOut('slow');
        showBlock = 0;
    } else {
        $('#hidden-pagination').fadeIn('slow');
        showBlock = 1;
    }
}
