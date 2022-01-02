<?php


class Session
{
    public static function start(){
        // starts a session if it's not already started
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }

    }
    public static function get(string $key){
        Session::start();
        if (isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        return null;
    }

    public static function set(string $key, $value){
        $_SESSION[$key] = $value;
    }



    public static function delete(string $key){
        unset($_SESSION[$key]);
    }

    public static function exists($key){
        return isset($_SESSION[$key]);
    }

    public static function forget(){
        session_unset();
    }
    public static function destroy(){
        session_destroy();
    }

    public static function id(){
        return session_id();
    }

    public static function regenerate(){
        session_regenerate_id();
    }
}