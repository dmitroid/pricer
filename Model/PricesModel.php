<?php

use \Service\DBService as DBService;

class PricesModel
{

    public static function create($pricelists, $template_id, $user_id, $header)
    {
        foreach($pricelists as $pricelist) {
            if ($pricelist) {
                $query = "INSERT INTO `prices` SET `name` = '{$pricelist}', `template_id` = {$template_id}, `user_id` = $user_id, `header` = $header";
                $db = DBService::getInstance();
                $db->query($query);
            }
        }
    }

    public static function update($name, $user_id, $price_id, $header)
    {
        $query = "UPDATE `prices` SET `name` = '{$name}', `header` = $header WHERE `id` = {$price_id} and `user_id` = {$user_id}";
        $db = DBService::getInstance();
        $db->query($query);
    }

    public static function getPricesByTemplateId($template_id)
    {
        $db = \Service\DBService::getInstance();
        $result = $db->query("SELECT * FROM `prices` WHERE `template_id` = $template_id ORDER BY id ASC")->fetch_all(MYSQLI_ASSOC);
        $prices = [];
        foreach($result as $price) {
            $price['fields'] = FieldsModel::getFieldsByPriceId($price['id']);
            $prices[] = $price;
        }

        return $prices;
    }

    public static function getPricesById($id, $user_id)
    {
        $db = \Service\DBService::getInstance();
        $price = $db->query("SELECT * FROM `prices` WHERE `id` = $id and `user_id` = $user_id ORDER BY id ASC")->fetch_assoc();
        $price['fields'] = FieldsModel::getFieldsByPriceId($id);

        return $price;
    }

    public static function remove($price_id, $user_id)
    {
        $db = DBService::getInstance();
        $price = $db->query("SELECT * FROM `prices` WHERE `id` = $price_id and `user_id` = $user_id")->fetch_assoc();

        if ($price) {
            $db->query("DELETE FROM `prices` WHERE `id` = $price_id");
        }

        return $price['template_id'];
    }
}
