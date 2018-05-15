<?php


class AdminCommentController
{

    public function remove()
    {
        global $params;
        $comment = CommentsModel::findById($params[0]);
        $comment->setId($params[0]);
        $comment->remove();
        header("Location: /admin/comments");
    }

}
