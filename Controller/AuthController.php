<?php

use \Service\SessionService as SessionService;

class AuthController
{
    public function index()
    {
        $this->login();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            global $smarty;
            $smarty->assign('category_id', 0);
            $smarty->assign('page', 'login');
            $smarty->display('login.tpl');
            return;
        }
        header("Content-Type: application/json");
        global $session;
        $user = UserModel::getUserByLogin($_POST['login']);

        if (!$user || md5($_POST['pass']) !== $user->getPass()) {
            http_response_code(404);
            die(
            json_encode([
                'status' => false,
                'message' => "User not found or password is incorrect"
            ])
            );
        }

        $session->set('user', $user->toArray());

        die(
        json_encode(
            [
                'status' => true,
                'messages' => "logged in"
            ]
        )
        );
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : false;
            if ($messages) {
                unset($_SESSION['messages']);
            }
            
            global $smarty;
            $smarty->assign('messages', $messages);
            $smarty->assign('category_id', 0);
            $smarty->assign('page', 'register');
            $smarty->display('register.tpl');
            return;
        }
    
    
        $users = UserModel::getListAll();
        foreach ($users as $usr) {
            if ($usr['login'] == $_POST['login']) {
                $_SESSION['messages'][] = 'Login <strong>"' . $_POST['login'] . '"</strong> is already in used. Please try another login';
                header("Location: /auth/register");
            }
        }
        
        $user = new UserModel($_POST['login'], $_POST['pass']);
        $res = $user->save();
        if(!$res) {
        	die("CREATING ERROR");
        }

        header("Location: /");

    }

    public function logout()
    {
    	global $session;
	    $session->remove('user');
	    header("Location: /");
    }
}
