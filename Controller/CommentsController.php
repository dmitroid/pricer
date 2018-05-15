<?php

class CommentsController
{
    
    public function create()
    {
        $item = new CommentsModel(
            $_POST['user_name'],
            $_POST['user_id'],
            $_POST['rating'],
            strip_tags($_POST['comment']),
            $_POST['parent_id']
        );
        
        $item->save();
        
        header("Location: /");
    }
}
