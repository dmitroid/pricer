<?php

use \Service\DBService as DBService;

class FieldsModel
{

    public static function create($pricelists, $template_id)
    {
        foreach($pricelists as $pricelist) {
            $query = "INSERT INTO `prices` SET `name` = '{$pricelist}', `template_id` = '{$template_id}'";
            $db = DBService::getInstance();
            $db->query($query);
        }
    }

    public static function getFieldsByPriceId($price_id)
    {
        $db = \Service\DBService::getInstance();
        $fields = $db->query("SELECT * FROM `fields` WHERE `price_id` = $price_id ORDER BY id ASC")->fetch_all(MYSQLI_ASSOC);

        return $fields;
    }

    public function save($fields, $price_id, $template_id, $user_id)
    {
        $db = DBService::getInstance();
        foreach($fields as $field) {
            if ($field['id'] == 0 || $field['id'] == 'unique' || $field['id'] == 'price') {
                $query = "INSERT INTO `fields` SET `name` = '{$field['name']}', 
                          `is_unique` = {$field['is_unique']},
                          `is_price` = {$field['is_price']},
                          `order` = {$field['order']},
                          `order_in_main` = {$field['order_in_main']},
                          `template_id` = {$template_id},
                          `price_id` = {$price_id},
                          `user_id` = {$user_id}";
                $db->query($query);
            } else {
                $query = "UPDATE `fields` SET `name` = '{$field['name']}', 
                          `is_unique` = {$field['is_unique']},
                          `is_price` = {$field['is_price']},
                          `order` = {$field['order']},
                          `order_in_main` = {$field['order_in_main']}
                          WHERE
                          `id` = {$field['id']} and `user_id` = {$user_id}";
                $db->query($query);
            }
        }
    }

    public static function remove($field_id, $user_id)
    {
        $db = DBService::getInstance();
        $field = $db->query("SELECT * FROM `fields` WHERE `id` = $field_id and `user_id` = $user_id")->fetch_assoc();

        if ($field) {
            $db->query("DELETE FROM `fields` WHERE `id` = $field_id");
        }

        return $field['price_id'];
    }
}
