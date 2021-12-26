<?php

class Route{
    private static $valid_get_routes = array();
    private static $valid_post_routes = array();
    private static $valid_put_routes = array();
    private static $valid_patch_routes = array();
    private static $valid_delete_routes = array();
    public static function get($route, $function){
        // handle get requests
        self::$valid_get_routes[] =  $route;
        // run the callback function
        if ($_SERVER["REQUEST_METHOD"] == "GET"  && $_GET["url"] == $route){
            $function->__invoke();
        }
    }
    public static function post($route, $function){
        // Handle post requests
        self::$valid_post_routes[] = $route;
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["url"] == $route){
            $function->__invoke();
        }
    }

    public static function put($route, $function){
        // Handle post requests
        self::$valid_put_routes[] = $route;
        if ($_SERVER["REQUEST_METHOD"] == "PUT"){
            $function->__invoke();
        }
    }

    public static function patch($route, $function){
        // Handle post requests
        self::$valid_patch_routes[] = $route;
        if ($_SERVER["REQUEST_METHOD"] == "PATCH"){
            $function->__invoke();
        }
    }

    public static function delete($route, $function){
        // Handle post requests
        self::$valid_delete_routes[] = $route;
        if ($_SERVER["REQUEST_METHOD"] == "DELETE"){
            $function->__invoke();
        }
    }

    public static function router($prefix, $route){
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" ."/$prefix/". "$route";
        header('Location: ' . $url, true);
        exit();
    }
}