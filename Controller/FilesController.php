<?php

class FilesController
{
    public function show()
    {
        global $params;
        $path = UserModel::STORAGE_DIR.$params[0].".csv";
        if (file_exists($path)) {
            header("Content-Type: text/csv");
            die(file_get_contents($path));
        }
        header("Content-Type: image/jpeg");
        die(file_get_contents(UserModel::STORAGE_DIR.$params[0].".jpg"));
    }
}
