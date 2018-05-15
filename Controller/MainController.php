<?php

class MainController
{
    
    public function index()
    {
        $userId = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
        
        global $smarty;
        $smarty->assign('comments', CommentsModel::getParentListByItemId());
        $smarty->assign('user_id', $userId);
        $smarty->display("index.tpl");
    }
}