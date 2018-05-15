<?php

class AdminPanelController
{

    public function index()
    {
        $pagination = $this->getPageData();
        $user_id = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
        $user = UserModel::getUserById($user_id);
        
        global $smarty;
        $smarty->assign('page', 'panel');
    
        if (!$user) {
            $smarty->display("error.tpl");
        } elseif ($user->getRole() == 1) {
            $smarty->assign('users',  UserModel::getListAll($this->getLim($pagination)));
            $smarty->assign('pagination', $pagination);
            $smarty->display("admin_panel.tpl");
        } else {
            $smarty->display("error_role_admin.tpl");
        }
        

    }

    public function remove()
    {
        header("Content-Type: application/json");
        if($_SERVER['REQUEST_METHOD'] !== "DELETE") {
            http_response_code(405);
            die(json_encode([
                'status' => false,
                'message' => "Bad method call"
            ]));
        }
        global $params;
        $category = UserModel::getUserById($params[0]);
        $category->remove();
        die(json_encode([
            'status' => true
        ]));
    }
    
    private function getPageData()
    {
        global $params;
        
        $pagination = [];
        $pagination['count'] = UserModel::getUsersCount();
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
