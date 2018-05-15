<?php

class AdminCommentsController
{

    public function index()
    {
        $user_id = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
        $user = UserModel::getUserById($user_id);
        global $smarty;
        $pagination = $this->getPageData();
        $smarty->assign('page', 'comments');
    
        if (!$user) {
            $smarty->display("error.tpl");
        } elseif ($user->getRole() == 1) {
            $smarty->assign('comments', CommentsModel::getList('DESC', $this->getLim($pagination)));
            $smarty->assign('pagination', $pagination);
            $smarty->display("admin_comments.tpl");
        } else {
            $smarty->display("error_role_admin.tpl");
        }

    }

    private function getPageData()
    {
        global $params;

        $pagination = [];
        $pagination['count'] = CommentsModel::getListCount();
        $pagination['currentPage'] = isset($params[0]) ? $params[0] : 1;
        $pagination['pagesCount'] = ceil($pagination['count']/3);
        $pagination['next'] = ($pagination['currentPage'] < $pagination['pagesCount']) ? $pagination['currentPage'] + 1 : 0;
        $pagination['prev'] = ($pagination['currentPage'] > 1) ? $pagination['currentPage'] - 1 : 0;

        return $pagination;
    }

    private function getLim($pagination)
    {
        $itemsPerPage = 3;
        $offset = ($pagination['currentPage'] - 1) * $itemsPerPage;

        return $offset . ',' . $itemsPerPage;
    }

}
