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

Route::post("login", function (){
    $email = $_POST["email"];
    $password = $_POST["password"];
    $is_client = true;
    $controller = new LoginController();
    $authenticated = $controller->authenticate($email, $password, $is_client);
    if ($authenticated){
        Route::router("vtc", "client");
    }else{
        Route::router("vtc", "login");
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
        Route::router("vtc", "login");

    }else{
        View::make("auth/register.html.twig", ["title" => "VTC application"]);
    }
});

// ================================= BEGIN LOGOUT ============================
Route::get("logout", function (){
    (new LoginController())->logout();
    Route::router("vtc", "login");
});

// ================================= END LOGOUT ==============================


// client portal routing
Route::get("client", function (){
    if (Auth::isAuthorized()){
        $client = Auth::user();
        View::make("client/index.html.twig", [
            "title" => "VTC client portal",
            "loggedIn" => true,
            "client" => $client,
            "wilayas" => (new WilayaController())->get_all()
        ]);
        return;
    }
    Route::router("vtc", "login");
});

// Add announcement post request
Route::post("new_announcement", function (){
    // get request parameters

    $start_point = $_POST["start_point"];
    $end_point = $_POST["end_point"];
    $type = $_POST["type"];
    $weight = $_POST["weight"];
    $volume = $_POST["volume"];
    $way = $_POST["way"];
    $message = trim($_POST["message"]);
    $announcement_controller = new AnnouncementController();
    $added = $announcement_controller->addNewAnnouncement($start_point, $end_point, $type, $weight, $volume, $message);
    header("Content-Type: application/json");
    echo json_encode(["added" => true]);

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
    if (Auth::isAuthorized()){
        $client = Auth::user();
        View::make("client/profile.html.twig", [
            "loggedIn" => true,
            "client" => $client,
            "title" => "profile"]);
        return;
    }
    Route::router("vtc", "login");
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
    echo (Auth::user()->getName());
});

Route::post("test", function (){
});