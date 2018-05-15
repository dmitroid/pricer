<?php


use \Service\DBService as DBService;
use \Service\SessionService as SessionService;

class UserModel
{
    const STORAGE_DIR = 'storage/items/';

    private $login;
    private $pass;
    private $id;
    private $role;

    public function __construct($login, $pass, $id = null, $role = 0)
    {
        $this->login = $login;
        $this->pass = ($id) ? $pass : md5($pass);
        $this->role = $role;
        $this->id = $id;
    }

    public static function getUserByLogin($login)
    {
        $query = "SELECT * FROM `users` WHERE `login` = '$login' LIMIT 1";
        $user = DBService::getInstance()->query($query)->fetch_assoc();
        return !$user ? null : new self($login, $user['pass'], $user['id'], $user['role']);
    }
    
    public static function getListAll($lim = null)
    {
        $query = "SELECT * from `users` WHERE `role` = 0 ";
        if ($lim) {
            $query .= " LIMIT $lim";
        }
        return \Service\DBService::getInstance()->query($query)->fetch_all(MYSQLI_ASSOC);
    }
    
    public static function getUserById($id)
    {
        $db = \Service\DBService::getInstance();
        $res = $db->query("SELECT * FROM `users` WHERE `id` = $id LIMIT 1")->fetch_assoc();
        return $res ? new self($res["login"], $res['pass'], $id, $res['role']) : null;
    }
    
    public static function getUsersCount()
    {
        $query = "SELECT COUNT(id) as count FROM `users` ";
        
        $result = \Service\DBService::getInstance()->query($query)->fetch_assoc();
        return !$result ? null : (int)$result['count'];
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function save()
    {
    	return $this->id ? $this->update() : $this->create();
    }

    private function update()
    {
	    $query = "UPDATE `users` SET `login` = '{$this->login}', `pass` = '{$this->pass}', role = '0' WHERE ";
	    $db = DBService::getInstace();
	    $db->query($query);
	    return $db->insert_id;
    }

    private function create()
    {
	    global $session;
    	$query = "INSERT INTO `users` SET `login` = '{$this->login}', `pass` = '{$this->pass}', role = '0'";
    	$db = DBService::getInstance();
    	$db->query($query);
	    $res = (bool) $db->insert_id;
	    if (!$res) {
	    	return null;
	    }


	    $session->set('user', $this->getUserByLogin($this->login)->toArray());
	    return true;
    }
    public function remove()
    {
        if(!$this->id) {
            return false;
        }
        return \Service\DBService::getInstance()->query("DELETE FROM `users` WHERE `id` = {$this->id} LIMIT 1");
    }

    public function toArray()
    {
    	return [
    		    'login' => $this->login,
		        'pass' =>$this->pass,
                'id' => $this->id,
                'role' => $this->role
		    ];
    }

}