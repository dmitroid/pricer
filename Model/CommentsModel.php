<?php

class CommentsModel
{
    private $id;
    private $user_name;
    private $user_id;
    private $rating;
    private $comment;
    private $parent_id;
    private $moderation;
    private $date_time;
    
    public function __construct($user_name, $user_id, $rating, $comment, $parent_id, $moderation = 1)
    {
        $this->user_name = $user_name;
        $this->user_id = $user_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->parent_id = $parent_id;
        $this->moderation = $moderation;
    }
    
    public static function findById($id)
    {
        $db = \Service\DBService::getInstance();
        $res = $db->query("SELECT * FROM `comments` WHERE `id` = $id LIMIT 1")->fetch_assoc();
        return $res ? new self($res['user_name'], $res['user_id'], $res['rating'], $res['comment'], $res['parent_id'], $res['moderation']) : null;
    }

    public function save()
    {
        return ($this->id) ? $this->update() : $this->create();
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function setModeration($moderation)
    {
        $this->moderation = $moderation;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }

    private function create()
    {
        $query = "INSERT INTO `comments`
        SET
        `user_name` = '{$this->user_name}',
        `user_id` = {$this->user_id},
        `rating` = {$this->rating},
        `comment` = '{$this->comment}',
        `parent_id` = {$this->parent_id},
        `date_time` = CURRENT_TIMESTAMP,
        `moderation` = {$this->moderation} ";
        $db = \Service\DBService::getInstance();
        $db->query($query);
		$this->id = $db->insert_id;
        return (bool) $db->insert_id;
    }

    private function update()
    {
        $query = "UPDATE `comments`
            SET
            `user_name` = '{$this->user_name}',
            `user_id` = {$this->user_id},
            `rating` = {$this->rating},
            `comment` = '{$this->comment}',
            `parent_id` = {$this->parent_id},
            `moderation` = {$this->moderation}
            WHERE `id` = {$this->id}";
        $db = \Service\DBService::getInstance();
        $db->query($query);

        return (bool) $db->affected_rows;
    }
    
    public static function getListByItemId($item_id = null, $order = "DESC", $lim = null)
    {
        $query = "SELECT * FROM `comments` ";
        if ($item_id) {
            $query .= " WHERE `item_id` = $item_id ";
        }
        $query .= " ORDER BY `rating` $order, `id` $order";
        if ($lim) {
            $query .= " LIMIT $lim";
        }
        
        return \Service\DBService::getInstance()->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public static function getParentListByItemId($order = "DESC", $lim = null)
    {
        $query = "SELECT * FROM `comments` WHERE `parent_id` = 0 AND `moderation` = 1";

        $query .= " ORDER BY `rating` $order, `id` $order";
        if ($lim) {
            $query .= " LIMIT $lim";
        }

        $result = \Service\DBService::getInstance()->query($query)->fetch_all(MYSQLI_ASSOC);;

        $commentIds = [];
        $comments = [];
        foreach ($result as $comment) {
            $commentIds[] = $comment['id'];
            $comments[$comment['id']] = $comment;
        }

        if ($commentIds) {
            $ids = implode(',',$commentIds);
            $result =  \Service\DBService::getInstance()->query("SELECT * FROM `comments` WHERE `parent_id` IN ({$ids}) AND `moderation` = 1 ORDER BY `rating` $order")->fetch_all(MYSQLI_ASSOC);

            foreach($result as $comment) {
                $comments[$comment['parent_id']]['children'][] = $comment;
            }
        }

        return $comments;
    }
    
    public static function getListByUserId($user_id = null, $order = "DESC", $lim = null)
    {
        $query = "SELECT * FROM `comments` ";
        if ($user_id) {
            $query .= " WHERE `user_id` = $user_id AND `moderation` = 1 ";
        }
        $query .= " ORDER BY `id` $order";
        if ($lim) {
            $query .= " LIMIT $lim";
        }
        
        return \Service\DBService::getInstance()->query($query)->fetch_all(MYSQLI_ASSOC);
    }
    
    public static function getListTopUsers($order = "DESC", $lim = 5)
    {
        $query = "SELECT user_id, user_name, COUNT(id) as res FROM `comments` GROUP BY user_name";
        $query .= " ORDER BY `res` $order";
        if ($lim) {
            $query .= " LIMIT $lim";
        }
        
        return \Service\DBService::getInstance()->query($query)->fetch_all(MYSQLI_ASSOC);
    }
    
    public static function getList($order = "DESC", $lim)
    {
        $query = "SELECT * FROM `comments` ORDER BY '$order'";

        if ($lim) {
            $query .= " LIMIT $lim";
        }

        return \Service\DBService::getInstance()->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public static function getListCount($userId = null)
    {
        $query = "SELECT COUNT(id) as count FROM `comments` ";
        if ($userId) {
            $query .= " WHERE `user_id` = $userId AND `moderation` = 1 ";
        }

        $result = \Service\DBService::getInstance()->query($query)->fetch_assoc();
        return !$result ? null : (int)$result['count'];
    }
    
    public function remove()
    {
    	if(!$this->id) {
    		return false;
	    }
	    return \Service\DBService::getInstance()->query("DELETE FROM `comments` WHERE `id` = {$this->id} LIMIT 1");
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_name' =>$this->user_name,
            'user_id' => $this->user_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'parent_id' => $this->parent_id,
            'moderation' => $this->moderation
        ];
    }

    public static function addVote($commentId)
    {
        $query = "UPDATE `comments` SET `rating` = `rating` + 1 WHERE `id` = $commentId";
        $db = \Service\DBService::getInstance();
        $db->query($query);

        $query = "SELECT `rating` FROM `comments` WHERE `id` = $commentId";
        $db = \Service\DBService::getInstance();
        $result = $db->query($query)->fetch_assoc();
        return $result['rating'];
    }

    public static function reduceVote($commentId)
    {
        $query = "UPDATE `comments` SET `rating` = `rating` - 1 WHERE `id` = $commentId";
        $db = \Service\DBService::getInstance();
        $db->query($query);

        $query = "SELECT `rating` FROM `comments` WHERE `id` = $commentId";
        $db = \Service\DBService::getInstance();
        $result = $db->query($query)->fetch_assoc();
        return $result['rating'];
    }
}
