<?php


namespace myPHPnotes\Microsoft\Handlers;
/**
 * 
 */
class Session
{
    public static function set($key, $value)
    {
        $_SESSION['adnanhussainturki/microsoft'][$key] = $value;
    }
    public static function unset($key)
    {
        if ($this->get($key)) {
            unset($_SESSION['adnanhussainturki/microsoft'][$key]);
        }
    }
    public static function get($key)
    {
        return (isset($_SESSION['adnanhussainturki/microsoft'][$key]) ? $_SESSION['adnanhussainturki/microsoft'][$key] : null) ;
    }
}