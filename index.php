<?php
require_once "smarty/Smarty.class.php";

spl_autoload_register(function($name) {
    $tmp = explode("\\", $name);
    $name = end($tmp);
    $folder = "";

    if (strpos($name, "Admin") === 0) {
        $folder = "Controller/admin";
    }
    else if (strpos($name, "Controller") > 0) {
        $folder = "Controller";
    } else if (strpos($name, "Model") > 0) {
        $folder = "Model";
    } else if (strpos($name, "Service") > 0) {
        $folder = "Service";
    }


    if(!file_exists($fullPath = "$folder/$name.php")) {
        throw new Exception( $folder . "/" . $name . " 404 NOT FOUND!");
    }

    require_once $fullPath;
});

$session = Service\SessionService::getInstance();
$smarty = new Smarty();
$smarty->setTemplateDir("View");

$url = ltrim($_SERVER["REQUEST_URI"], "/");
$url = explode("?", $url)[0];
$parts = explode("/", $url);


$controller = "";

//Check Admin
if(isset($parts[0]) && $parts[0] === 'user') {
    
    $user = $session->get('user');
    
    $controller = ucfirst(array_shift($parts));
    $controller .= ucfirst(array_shift($parts)) ?: "Panel";
}
else if(isset($parts[0]) && $parts[0] === 'admin') {
    
    $user = $session->get('user');
    
    $controller = ucfirst(array_shift($parts));
    $controller .= ucfirst(array_shift($parts)) ?: "Panel";
    
} else {
    $controller .= ucfirst(array_shift($parts)) ?: "Main";
}
$action = (array_shift($parts)) ?: "index";
$params = $parts;


    $cartItems = $session->get('cart', []);
    $count = 0;
    array_walk($cartItems, function($value) use (&$count) {
        $count += $value;
    });
    $smarty->assign('cart', $count);



$controller = ucfirst($controller)."Controller";

$controllerObj = new $controller();
if (!method_exists($controllerObj, $action)) {
    //throw new Exception("Method $action not found in CLASS $controller");
    header("Location: /");
}
$controllerObj->$action();