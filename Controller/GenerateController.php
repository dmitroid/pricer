<?php
/**
 * Created by PhpStorm.
 * User: dmitroid
 * Date: 5/9/18
 * Time: 3:12 PM
 */

class GenerateController
{
    public function index()
    {
        global $params;
        $genblock = false;
        $template_id = $params[0];
        $prices = PricesModel::getPricesByTemplateId($template_id);
        
        foreach ($prices as $prc) {
            if (empty($prc['fields'])) {
                $_SESSION['messages'][] = 'Your pricelist settings for "' . $prc['name'] . '" do not have all required fields(Unique or Price).<br>Please check it and try correct edit this pricelis in template.<br><br>';
                $genblock = true;
            }
        }
        if (empty($prices)) {
            $_SESSION['messages'][] = 'Your template do not have any prices setings.<br> Please try correct edit your template';
            $genblock = true;
        }
        
        $user_id = isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
        global $smarty;
        $messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : false;
        if ($messages) {
            unset($_SESSION['messages']);
        }

        
        $smarty->assign('page', 'panel');
        $smarty->assign('messages', $messages);
        $smarty->assign('genblock', $genblock);
        $smarty->assign('template', TemplatesModel::getTemplate($template_id));
        $smarty->display("user_panel_generate.tpl");
    }

    public function show()
    {
        $template_id = $_POST['template_id'];
        $template = TemplatesModel::getTemplate($template_id);

        foreach ($_FILES['price']['error'] as $num_price => $value) {
            if ($value == 4) {
                $_SESSION['messages'][] = 'Field ' . ($num_price + 1) . ' must not be empty <br>';
                header("Location: /generate/index/" . $template_id);
            }
        }
        foreach ($_FILES['price']['type'] as $num_price => $value) {
            if ($value != 'text/csv') {
                $_SESSION['messages'][] = 'Field ' . ($num_price + 1) . ' must be text/csv type <br>';
                header("Location: /generate/index/" . $template_id);
            }
        }

        $files = $this->_uploadFiles();

        $generateModel = new GenerateModel();
        $preparedArray = $generateModel->getPreparedArray($files, $template);
        $header = $generateModel->getHeader($files, $template);

        $file = fopen('php://memory', 'w');
        fputcsv($file, $header);

        foreach ($preparedArray as $row)
        {
            fputcsv($file, $row);
        }
        fseek($file, 0);

        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $template['name'] . '.csv' . '";');
        fpassthru($file);
    }

    protected function _uploadFiles()
    {
        $files = [];
        foreach ($_FILES['price']['name'] as $key => $name) {
            $files[$key]['name'] = $name;
        }

        foreach ($_FILES['price']['tmp_name'] as $key => $name) {
            $files[$key]['tmp_name'] = $name;
        }

        foreach ($files as $file) {
            $target = 'storage/items/' . $file['name'];
            move_uploaded_file($file['tmp_name'], $target);
        }

        return $files;
    }
}
