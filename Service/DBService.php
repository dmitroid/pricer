<?php

namespace Service;

final class DBService
{
    private static $instance;

    private function __construct(){}

    /**
     * @return \mysqli
     */
    public static function getInstance()
    {
        //return self::$instance ?? new \mysqli("practi06.mysql.tools", 'practi06_db', 'xV5JXVt8', 'practi06_db');
        return self::$instance ?? new \mysqli("127.0.0.1", 'root', '1', 'pricer_db');
    }
}