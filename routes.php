<?php

// Register routes


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Load the view
View::$loader = new FilesystemLoader(__DIR__ . DIRECTORY_SEPARATOR . "resources/views");
View::$twig = new Environment(View::$loader);

Route::get("index.php", function (){
    View::make("index.html.twig", ["title" => "VTC application"]);
});

Route::get("login", function (){
    View::make("auth/login.html.twig", ["title" => "VTC application"]);
});

Route::get("register", function (){
    View::make("auth/register.html.twig", ["title" => "VTC application"]);
});

// Register post requests
Route::post("index.php", function (){
    echo $_POST["id"];
});