<?php

// Register routes


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Load the view
View::$loader = new FilesystemLoader([__DIR__ . DIRECTORY_SEPARATOR . "resources/views", __DIR__ . DIRECTORY_SEPARATOR . "resources/views/client"]);
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

// client portal routing
Route::get("client", function (){
    View::make("client/index.html.twig", ["title" => "VTC client portal", "loggedIn" => true, "username" => "mohamed"]);
});

Route::get("client/profile", function (){
    View::make("client/profile.html.twig", ["title" => "VTC client portal", "loggedIn" => true, "username" => "mohamed"]);
});

// Show user announcements
Route::get("announcements", function (){

    View::make("client/announcements.html.twig", ["title" => "VTC client portal", "loggedIn" => true, "username" => "mohamed"]);
});

// show transporter applications
Route::get("applications", function (){

    View::make("client/notifications.html.twig", ["title" => "VTC client portal", "loggedIn" => true, "username" => "mohamed"]);
});


//Route::get("profile", function (){
//    View::make("client/profile.html.twig", ["loggedIn" => true, "client" => true, "title" => "profile"]);
//});

// Admin dashboard route
Route::get("admin", function (){
    View::make("admin/admin.html.twig");
});

// Register post requests
Route::post("index.php", function (){
    echo $_POST["id"];
});

Route::get("404", function (){
    View::make("404.html.twig");
});

Route::get("details", function (){
    View::make("announcements/details.html.twig", ["_title" => "This is announcement title", "client" => false]);
});

Route::get("transporter", function (){
    View::make("transporter/index.html.twig");
});


Route::get("demands", function (){
    View::make("transporter/demands.html.twig");
});