<?php

class Route{
    private static $valid_get_routes = array();
    private static $valid_post_routes = array();
    private static $valid_put_routes = array();
    private static $valid_patch_routes = array();
    private static $valid_delete_routes = array();
    public static function get($route, $function){
        // handle get requests

        // run the callback function
        if ($_SERVER["REQUEST_METHOD"] == "GET"  && $_GET["url"] == $route){
            self::$valid_get_routes[] =  $route;
            $function->__invoke();
        }
    }
    public static function post($route, $function){
        // Handle post requests

        if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["url"] == $route){
            self::$valid_post_routes[] = $route;
            $function->__invoke();
        }
    }

    public static function put($route, $function){
        // Handle post requests

        if ($_SERVER["REQUEST_METHOD"] == "PUT"){
            self::$valid_put_routes[] = $route;
            $function->__invoke();
        }
    }

    public static function patch($route, $function){
        // Handle post requests

        if ($_SERVER["REQUEST_METHOD"] == "PATCH"){
            self::$valid_patch_routes[] = $route;
            $function->__invoke();
        }
    }

    public static function delete($route, $function){
        // Handle post requests

        if ($_SERVER["REQUEST_METHOD"] == "DELETE" && $_GET["url"] == $route){
            self::$valid_delete_routes[] = $route;
            $function->__invoke();
        }
    }

    public static function router($prefix, $route){
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" ."/$prefix/". "$route";
        header('Location: ' . $url, true);
        exit();
    }
}