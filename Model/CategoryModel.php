<?php


class CategoryModel
{
    private $id;
    private $name;
    private $weight;
    private $parent_id;

    public function __construct($name, $weight = 0, $parent_id = 0, $id = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->weight = $weight;
        $this->parent_id = $parent_id;
    }

    public static function findById($id)
    {
        $db = \Service\DBService::getInstance();
        $res = $db->query("SELECT * FROM `categories` WHERE `id` = $id LIMIT 1")->fetch_assoc();
        return $res ? new self($res["name"], $res['weight'], $res['parent_id'], $res['id']) : null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getWeight()
    {
        return $this->weight;
    }
    public function getParentId() {
        return $this->parent_id;
    }

    public function save()
    {
        return ($this->id) ? $this->update() : $this->create();
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }
    
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    private function create()
    {
        $query = "INSERT INTO `categories` SET `name` = '{$this->name}', `weight` = {$this->weight}, `parent_id` = {$this->parent_id}";
        $db = \Service\DBService::getInstance();
        $db->query($query);
		$this->id = $db->insert_id;
        return (bool) $db->insert_id;
    }

    private function update()
    {
        $query = "UPDATE `categories`
            SET
            `name` = '{$this->name}',
            `weight` = {$this->weight},
            `parent_id` = {$this->parent_id}
            WHERE `id` = {$this->id}";
        $db = \Service\DBService::getInstance();
        $db->query($query);

        return (bool) $db->affected_rows;
    }

    public static function getList($order = "DESC")
    {
        $result =  \Service\DBService::getInstance()->query("SELECT * FROM `categories` WHERE `parent_id` = 0 ORDER BY `weight` $order")->fetch_all(MYSQLI_ASSOC);

        $categoryIds = [];
        $categories = [];
        foreach ($result as $category) {
            $categoryIds[] = $category['id'];
            $categories[$category['id']] = $category;
        }

        if ($categoryIds) {
            $ids = implode(',',$categoryIds);
            $result =  \Service\DBService::getInstance()->query("SELECT * FROM `categories` WHERE `parent_id` IN ({$ids}) ORDER BY `weight` $order")->fetch_all(MYSQLI_ASSOC);

            foreach($result as $category) {
                $categories[$category['parent_id']]['children'][] = $category;
            }
        }

        return $categories;
    }
    
    public static function getCategoryById($id)
    {
        $query = "SELECT name FROM `categories` WHERE `id` = $id";
        $db = \Service\DBService::getInstance();
        $result = $db->query($query)->fetch_row();
        return $result;
    }

    public static function getListAll($order = "DESC")
    {
        return \Service\DBService::getInstance()->query("SELECT * FROM `categories` ORDER BY `weight` $order")->fetch_all(MYSQLI_ASSOC);
    }
    
    public function remove()
    {
    	if(!$this->id) {
    		return false;
	    }
	    return \Service\DBService::getInstance()->query("DELETE FROM `categories` WHERE `id` = {$this->id} LIMIT 1");
    }
}
