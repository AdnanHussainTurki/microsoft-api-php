<?php


namespace myPHPnotes\Microsoft\Handlers;
/**
 * 
 */
class Session
{
    protected $prefix = 'adnanhussainturki/microsoft';
    public static function set($key, $value)
    {
        $_SESSION[$this->prefix][$key] = $value;
    }
    public static function unset($key)
    {
        if ($this->get($key)) {
            unset($_SESSION[$this->prefix][$key]);
        }
    }
    public static function get($key)
    {
        return (isset($_SESSION[$this->prefix][$key]) ? $_SESSION[$this->prefix][$key] : null) ;
    }
}