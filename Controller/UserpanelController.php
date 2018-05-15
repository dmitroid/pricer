<?php

class UserpanelController
{
    public function index()
    {
        $user_id = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
        $user = UserModel::getUserById($user_id);
        global $smarty;
        $smarty->assign('page', 'panel');
        if (!$user) {
            $smarty->display("error.tpl");
        } elseif ($user->getRole() == 0) {
            $smarty->assign('templates', TemplatesModel::getList($user_id));
            $smarty->display("user_panel.tpl");
        } else {
            $smarty->display("error_role_user.tpl");
        }

    }


    public function create()
    {
        $user_id = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
        $user = UserModel::getUserById($user_id);
        global $smarty;
        $smarty->assign('page', 'panel');
        if (!$user) {
            $smarty->display("error.tpl");
        } elseif ($user->getRole() == 0) {
            $messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : false;
            if ($messages) {
                unset($_SESSION['messages']);
            }
            $smarty->assign('messages', $messages);
            $smarty->display("user_panel_create.tpl");
        } else {
            $smarty->display("error_role_user.tpl");
        }

    }

    public function edittemplate()
    {
        $user_id = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
        $user = UserModel::getUserById($user_id);
        global $smarty, $params;
        $smarty->assign('page', 'panel');
        if (!$user) {
            $smarty->display("error.tpl");
        } elseif ($user->getRole() == 0) {
            $template_id = $params[0];
            $template = TemplatesModel::getTemplate($template_id);
    
            $messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : false;
            if ($messages) {
                unset($_SESSION['messages']);
            }
            $smarty->assign('messages', $messages);
            $smarty->assign('template', $template);
            $smarty->display("user_panel_edittemplate.tpl");
        } else {
            $smarty->display("error_role_user.tpl");
        }
    }

    public function editprice()
    {
        global $smarty, $params;

        $user_id = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
        $user = UserModel::getUserById($user_id);
        $smarty->assign('page', 'panel');
        
        if (!$user) {
            $smarty->display("error.tpl");
        } elseif ($user->getRole() == 0) {
            $price_id = $params[0];
            $price = PricesModel::getPricesById($price_id, $user_id);
            $messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : false;
            if ($messages) {
                unset($_SESSION['messages']);
            }
            $smarty->assign('messages', $messages);
            $smarty->assign('price', $price);
            $smarty->display("user_panel_editprice.tpl");
        } else {
            $smarty->display("error_role_user.tpl");
        }
    }

    public function saveprice()
    {
        $user_id = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : false;
        $name = isset($_POST['name']) ? $_POST['name'] : false;
        $header = isset($_POST['header']) ? 1 : 0;
        $price_id = isset($_POST['price_id']) ? $_POST['price_id'] : false;
        $template_id = isset($_POST['template_id']) ? $_POST['template_id'] : false;
        $field_ids = isset($_POST['field-id']) ? $_POST['field-id'] : false;
        $field_names = isset($_POST['field-name']) ? $_POST['field-name'] : false;
        $field_unique = isset($_POST['field-unique']) ? $_POST['field-unique'] : false;
        $field_price = isset($_POST['field-price']) ? $_POST['field-price'] : false;
        $field_order = isset($_POST['field-order']) ? $_POST['field-order'] : false;
        $field_order_in_main = isset($_POST['field-order-in-main']) ? $_POST['field-order-in-main'] : false;

        $fields = [];
        foreach($field_ids as $field_id) {
            $fields[$field_id]['id'] = $field_id;
            $fields[$field_id]['name'] = $field_names[$field_id];
            $fields[$field_id]['is_unique'] = $field_unique[$field_id];
            $fields[$field_id]['is_price'] = $field_price[$field_id];
            $fields[$field_id]['order'] = $field_order[$field_id];
            $fields[$field_id]['order_in_main'] = $field_order_in_main[$field_id];
        }

        if ($field_names[0]) {
            $fields[0]['id'] = 0;
            $fields[0]['name'] = $field_names[0];
            $fields[0]['is_unique'] = $field_unique[0];
            $fields[0]['is_price'] = $field_price[0];
            $fields[0]['order'] = $field_order[0];
            $fields[0]['order_in_main'] = $field_order_in_main[0];
        }

        if ($user_id && $name && $price_id && $template_id || $header) {
            PricesModel::update($name, $user_id, $price_id, $header);
            FieldsModel::save($fields, $price_id, $template_id, $user_id);
        } else {
            $_SESSION['messages'][] = 'Fields must not be empty';
        }

        header("Location: /userpanel/editprice/" . $price_id);
    }

    public function savenewtemplate()
    {
        $user_id = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : false;
        $name = isset($_POST['name']) ? $_POST['name'] : false;
        $pricelists = isset($_POST['pricelist']) ? $_POST['pricelist'] : false;

        if ($user_id && $name && $pricelists) {
            $template_id = TemplatesModel::create($name, $user_id);
            PricesModel::create($pricelists, $template_id, $user_id, 0);

            header("Location: /userpanel/edittemplate/" . $template_id);
        } else {
            $_SESSION['messages'][] = 'Fields must not be empty';
            header("Location: /userpanel/create");
        }
    }

    public function savetemplate()
    {
        $user_id = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : false;
        $name = isset($_POST['name']) ? $_POST['name'] : false;
        $template_id = isset($_POST['template_id']) ? $_POST['template_id'] : false;
        $pricelists = isset($_POST['pricelist']) ? $_POST['pricelist'] : false;

        if ($user_id && $name && $template_id) {
            TemplatesModel::update($name, $user_id, $template_id);

            if ($pricelists) {
                PricesModel::create($pricelists, $template_id, $user_id, 0);
            }
        } else {
            $_SESSION['messages'][] = 'Fields must not be empty';
        }
        header("Location: /userpanel/edittemplate/" . $template_id);
    }

    public function removefield()
    {
        global $params;
        $user_id = $_SESSION['user']['id'];
        $price_id = FieldsModel::remove($params[0], $user_id);
        header("Location: /userpanel/editprice/" . $price_id);
    }

    public function removeprice()
    {
        global $params;
        $user_id = $_SESSION['user']['id'];
        $template_id = PricesModel::remove($params[0], $user_id);
        header("Location: /userpanel/edittemplate/" . $template_id);
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
        $template = TemplatesModel::findById($params[0]);
        $template->remove();
        die(json_encode([
            'status' => true
        ]));
    }
}
