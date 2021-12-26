<?php

// Register routes


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Load the view
View::$loader = new FilesystemLoader([__DIR__ . DIRECTORY_SEPARATOR . "resources/views", __DIR__ . DIRECTORY_SEPARATOR . "resources/views/client"]);
View::$twig = new Environment(View::$loader);

Route::get("index.php", function (){
    $controller = new AnnouncementController();
    $result = $controller->getAnnouncements(false, 8);
    View::make("index.html.twig",
        [
            "title" => "VTC application",
            "announcements" => $result,
            "isAuthenticated" => false,
            "wilayas" => DB::query("SELECT * FROM wilayas")
        ]);
});



// =============================== LOGIN ================================
Route::get("login", function (){
    View::make("auth/login.html.twig", ["title" => "VTC application"]);
});

Route::post("register", function (){
    $email = $_POST["email"];
    $password = $_POST["password"];
    $is_client = true;
    $controller = new LoginController();
    $authenticated = $controller->authenticate($email, $password, $is_client);
    if ($authenticated){
        Route::route("vtc", "client");
    }else{
        Route::route("vtc", "login");
    }
});



// ============================ Registration ==========================
Route::get("register", function (){
    View::make("auth/register.html.twig", ["title" => "VTC application"]);
});

Route::post("register", function (){
    // Steps
    // 1. Get POST parameters
    // 2. Invoke Registration controller
    //  2.1 Sanitize user input
    //  2.2 Update the DB
    //  2.3 Redirect the user to the login page
    $name = $_POST["name"];
    $family_name = $_POST["family_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $address = "address";
    $is_client = true; // TODO: Change this to get the value dynamically
    $controller = new RegistrationController();
    $registered = $controller->register($name, $family_name, $email, $password, $address, $is_client);

    if ($registered){
        // Redirect to login page
        Route::route("vtc", "login");

    }else{
        View::make("auth/register.html.twig", ["title" => "VTC application"]);
    }
});



// client portal routing
Route::get("client", function (){
    View::make("client/index.html.twig", ["title" => "VTC client portal", "loggedIn" => true, "username" => "mohamed"]);
});


// Show user announcements
Route::get("announcements", function (){

    View::make("client/announcements.html.twig", ["title" => "VTC client portal", "loggedIn" => true, "username" => "mohamed"]);
});

// show transporter applications
Route::get("applications", function (){

    View::make("client/notifications.html.twig", ["title" => "VTC client portal", "loggedIn" => true, "username" => "mohamed"]);
});


Route::get("profile", function (){
    View::make("transporter/profile.html.twig", ["loggedIn" => true, "client" => true, "title" => "profile"]);
});

// Admin dashboard route
Route::get("admin", function (){
    View::make("admin/admin.html.twig");
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


// ============================== POST Requests =================================
// Register post requests
Route::post("index.php", function (){
    $controller = new AnnouncementController();
    $from = $_POST["start_point"];
    $to = $_POST["end_point"];
    $result = $controller->getAnnouncementByCriteria($from, $to, false);
    header("Content-Type: application/json");
    echo json_encode(["success" => true, "announcements" => $result]);
});

Route::get("test", function (){
    echo password_hash("test", PASSWORD_BCRYPT);
});