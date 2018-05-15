<?php

namespace Service;

final class SessionService
{

    private static $instance;

    const KEY = 'SESSION_ID';
    private function __construct()
    {
        if (isset($_COOKIE[self::KEY])) {
            session_id($_COOKIE[self::KEY]);
            session_start();
        } else {
            session_start();
            setcookie(self::KEY, session_id());
        }
    }

    public static function getInstance()
    {
        return self::$instance ?? new self();
    }

    public function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function destroy()
    {
        session_destroy();
    }
}