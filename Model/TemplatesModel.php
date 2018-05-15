<?php

use \Service\DBService as DBService;

class TemplatesModel
{
    private $id;
    private $name;
    private $create_date;
    private $update_date;
    private $user_id;
    
    public function __construct($id, $name, $create_date, $update_date, $user_id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->create_date = $create_date;
        $this->update_date = $update_date;
        $this->user_id = $user_id;
    }
    
    public static function getList($user_id, $order = "DESC")
    {
        return \Service\DBService::getInstance()->query("SELECT * FROM `templates` WHERE `user_id` = {$user_id} ORDER BY `id` $order")->fetch_all(MYSQLI_ASSOC);
    }
    
    public static function create($name, $user_id)
    {
        $query = "INSERT INTO `templates` SET `name` = '{$name}', `user_id` = '{$user_id}', `create_date` = CURRENT_TIMESTAMP, `update_date` = CURRENT_TIMESTAMP";
        $db = DBService::getInstance();
        $db->query($query);
        return $db->insert_id;
    }

    public static function update($name, $user_id, $template_id)
    {
        $query = "UPDATE `templates` SET `name` = '{$name}' WHERE `id` = {$template_id} and `user_id` = {$user_id}";
        $db = DBService::getInstance();
        $db->query($query);
    }

    public static function getTemplate($template_id)
    {
        $db = DBService::getInstance();
        $template = $db->query("SELECT * FROM `templates` WHERE `id` = $template_id")->fetch_assoc();
        $template['prices'] = PricesModel::getPricesByTemplateId($template_id);

        return $template;
    }
    
    public static function findById($id)
    {
        $db = \Service\DBService::getInstance();
        $user_id = $_SESSION['user']['id'];
        $res = $db->query("SELECT * FROM `templates` WHERE `id` = {$id} and `user_id` = {$user_id} LIMIT 1")->fetch_assoc();
        return $res ? new self($res['id'], $res['name'], $res['create_date'], $res['update_date'], $res['user_id']) : null;
    }
    
    public function remove()
    {
        if(!$this->id) {
            return false;
        }
    
        \Service\DBService::getInstance()->query("DELETE FROM `fields` WHERE `template_id` = {$this->id}");
        \Service\DBService::getInstance()->query("DELETE FROM `prices` WHERE `template_id` = {$this->id}");
        return \Service\DBService::getInstance()->query("DELETE FROM `templates` WHERE `id` = {$this->id} LIMIT 1");
    }

}
