<?php

class CommentController
{
    public function addvote()
    {
        global $params;

        $commentId = $params[0];
        $userId = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;

        if ($commentId && $userId) {
            if (!VoteModel::hasVoted($userId, $commentId)) {
                VoteModel::addVote($userId, $commentId);
                $rating = CommentsModel::addVote($commentId);
            }
        }

        header('Content-Type: application/json');

        if (isset($rating)) {
            $result = json_encode(array('rating' => $rating, 'status' => 'update'));
        } else {
            $result = json_encode(array('status' => 'noupdate'));
        }
        echo $result;
    }

    public function reducevote()
    {
        global $params;

        $commentId = $params[0];
        $userId = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;

        if ($commentId && $userId) {
            if (!VoteModel::hasVoted($userId, $commentId)) {
                VoteModel::addVote($userId, $commentId);
                $rating = CommentsModel::reduceVote($commentId);
            }
        }

        header('Content-Type: application/json');

        if (isset($rating)) {
            $result = json_encode(array('rating' => $rating, 'status' => 'update'));
        } else {
            $result = json_encode(array('status' => 'noupdate'));
        }
        echo $result;
    }
}
