

function addVote(commentId)
{
    $.post("/comment/addvote/" + commentId,
        {
        },
        function(data, status){
            voteSuccess(data, commentId);
        });
}

function reduceVote(commentId)
{
    $.post("/comment/reducevote/" + commentId,
        {
        },
        function(data, status){
            voteSuccess(data, commentId);
        });
}

function voteSuccess(data, commentId) {
    if (data.status == 'update') {
        $('#rating-' + commentId).html(data.rating);
    }
}

function addAnswer(id)
{
    $('#answer-' + id).show();
}
