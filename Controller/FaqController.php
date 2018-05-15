<?php
/**
 * Created by PhpStorm.
 * User: dmitroid
 * Date: 5/15/18
 * Time: 2:24 PM
 */

class FaqController
{
    public function index()
    {
        $userId = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
        
        global $smarty;
        $smarty->assign('comments', CommentsModel::getParentListByItemId());
        $smarty->assign('user_id', $userId);
        $smarty->display("faq.tpl");
    }
}