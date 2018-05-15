<?php

class VoteModel
{
    public static function addVote($userId, $commentId)
    {
        $query = "INSERT INTO `vote_log` SET `user_id` = {$userId}, `comment_id` = {$commentId}";
        $db = \Service\DBService::getInstance();
        $db->query($query);
    }

    public static function hasVoted($userId, $commentId)
    {
        $query = "SELECT COUNT(id) as countRows FROM `vote_log` ";
        $query .= " WHERE `user_id` = {$userId} AND `comment_id` = {$commentId}";

        $result = \Service\DBService::getInstance()->query($query)->fetch_assoc();
        return $result ? (int)$result['countRows'] : false;
    }
}
