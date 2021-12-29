<?php

// Register routes


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Load the view
View::$loader = new FilesystemLoader([
    __DIR__ . DIRECTORY_SEPARATOR . "resources/views",
    __DIR__ . DIRECTORY_SEPARATOR . "resources/views/client",
    __DIR__ . DIRECTORY_SEPARATOR . "resources/views/transporter",
    __DIR__ . DIRECTORY_SEPARATOR . "resources/views/announcements",
    ]);
View::$twig = new Environment(View::$loader);

Route::get("index.php", function (){
//    echo "hello";
    if (Auth::isAuthorizedClient() && Auth::isAuthorizedTransporter()){
        $controller = new AnnouncementController();
        $result = $controller->getLimitedAnnouncements( 8);
        View::make("index.html.twig",
            [
                "title" => "VTC application",
                "announcements" => $result,
                "isAuthenticated" => true,
                "wilayas" => (new WilayaController())->get_all()
            ]);
    }else{
        $controller = new AnnouncementController();
        $result = $controller->getLimitedAnnouncements(8);
        View::make("index.html.twig",
            [
                "title" => "VTC application",
                "announcements" => $result,
                "isAuthenticated" => false,
                "wilayas" => (new WilayaController())->get_all()
            ]);
    }

});



// =============================== LOGIN ================================
Route::get("login", function (){
    if (Auth::isAuthorizedClient()){
        Route::router("vtc", "client");
        return;
    }
    if (Auth::isAuthorizedTransporter()){
        Route::router("vtc", "transporter");
        return;
    }
    View::make("auth/login.html.twig", ["title" => "VTC application"]);
});

Route::post("login", function (){

    $email = $_POST["email"];
    $password = $_POST["password"];
    $is_client = strcmp($_POST["client_or_transporter"], "client") == 0 ? true : false;
    $controller = new LoginController();
    $authenticated = $controller->authenticate($email, $password, $is_client);

    if ($authenticated){
        if (Session::get("is_client")){
            Route::router("vtc", "client");
        }else{
            // transporter
            Route::router("vtc", "transporter");
        }
    }else{
        Route::router("vtc", "login");
    }
});



// ============================ Registration ==========================
Route::get("register", function (){
    $wilayas = (new WilayaController())->get_all();
    View::make("auth/register.html.twig", [
        "title" => "VTC application",
        "wilayas" => $wilayas
    ]);
});

Route::post("register", function (){

    $name = $_POST["name"];
    $family_name = $_POST["family_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $address = "address";
    $is_client = strcmp($_POST["client_or_transporter"], "client") == 0; // TODO: Change this to get the value dynamically
    $wilayas = array();
    foreach ($_POST["wilayas"] as $w){
        array_push($wilayas, $w);
    }
    $controller = new RegistrationController();
    $registered = $controller->register($name, $family_name, $email, $password, $address, $is_client, $wilayas);

    if ($registered){
        // Redirect to login page
        Route::router("vtc", "login");

    }else{
        // Registration failed
        View::make("auth/register.html.twig", ["title" => "VTC application"]);
    }
});

// ================================= BEGIN LOGOUT ============================
Route::get("logout", function (){
    (new LoginController())->logout();
    Route::router("vtc", "login");
});

// ================================= END LOGOUT ==============================


// =================================== BEGIN CLIENT ACTIONS ====================
Route::get("client", function (){
    if (Auth::isAuthorizedClient()){
        $client = Auth::user();
        $controller = new AnnouncementController();
        $result = $controller->getLimitedAnnouncements(8);
        View::make("client/index.html.twig", [
            "title" => "VTC client portal",
            "loggedIn" => true,
            "client" => $client,
            "announcements" => $result,
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
    if (Auth::isAuthorizedClient()){
        $client = Auth::user();
        $controller = new AnnouncementController();
        $result = $controller->getAllOfClient($client->getClientId());
        View::make("client/announcements.html.twig", [
            "title" => "VTC client portal",
            "loggedIn" => true,
            "client" => $client,
            "announcements" => $result,
        ]);
        return;
    }
    Route::router("vtc", "login");

});

// show transporter applications
Route::get("applications", function (){

    View::make("client/notifications.html.twig", ["title" => "VTC client portal", "loggedIn" => true, "username" => "mohamed"]);
});


// Search for announcements
Route::post("search", function (){
    if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
        $controller = new AnnouncementController();
        $from = $_POST["start_point"];
        $to = $_POST["end_point"];
        $result = $controller->getAnnouncementByCriteria($from, $to, true);
        header("Content-Type: application/json");
        echo json_encode(["success" => true, "announcements" => $result]);
    }
});


Route::get("client_profile", function (){
    if (Auth::isAuthorizedClient()){
        $client = Auth::user();
        View::make("client/profile.html.twig", [
            "loggedIn" => true,
            "client" => $client,
            "title" => "profile"]);
        return;
    }
    Route::router("vtc", "login");
});

Route::get("transporter_profile", function (){
    if (Auth::isAuthorizedTransporter()){
        $transporter = Auth::user();
        View::make("transporter/profile.html.twig", [
            "loggedIn" => true,
            "transporter" => $transporter,
            "title" => "profile"]);
        return;
    }
    Route::router("vtc", "login");
});


// Certification demands
Route::get("certification", function (){
    if (Auth::isAuthorizedTransporter()){
        $file = "documents/cert.pdf";
        StatusController::send_documents($file);
        // TODO: Update DB
    }
});

// Admin dashboard route
Route::get("admin", function (){
    View::make("admin/admin.html.twig");
});

Route::get("404", function (){
    View::make("404.html.twig");
});

// ============================ ANNOUNCEMENT ==================
Route::get("details", function (){
    if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
        $user = Auth::user();
        $announcement_id = $_GET["id"];
        View::make("announcements/details.html.twig", [
            "isClient" => Session::get("is_client"),
            "user" => $user,
            "announcement" => (new AnnouncementController())->getById($announcement_id)[0],
            ]);
        return;
    }
});

Route::get("transporter", function (){
    if (Auth::isAuthorizedTransporter()){

        $transporter = Auth::user();

        $controller = new AnnouncementController();
        $result = $controller->getLimitedAnnouncements(8);

        View::make("transporter/index.html.twig", [
            "title" => "VTC client portal",
            "loggedIn" => true,
            "transporter" => $transporter,
            "announcements" => $result,
            "wilayas" => (new WilayaController())->get_all()
        ]);
        return;
    }
    Route::router("vtc", "login");
});


Route::get("demands", function (){
    if (Auth::isAuthorizedTransporter()){

        $transporter = Auth::user();

        // fetch demands

        View::make("transporter/demands.html.twig", [
            "title" => "VTC client portal",
            "loggedIn" => true,
            "transporter" => $transporter,
        ]);
        return;
    }
    Route::router("vtc", "login");
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

    Transporter::add_wilaya(1, 1);
    echo "hello";

});

Route::post("test", function (){

});

