<?php

// Register routes


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Load the view
View::$loader = new FilesystemLoader([
    __DIR__ . DIRECTORY_SEPARATOR . "resources/views",
    __DIR__ . DIRECTORY_SEPARATOR . "resources/views/user",
    __DIR__ . DIRECTORY_SEPARATOR . "resources/views/transporter",
    __DIR__ . DIRECTORY_SEPARATOR . "resources/views/announcements",
    __DIR__ . DIRECTORY_SEPARATOR . "resources/views/admin",
    ]);
View::$twig = new Environment(View::$loader);

Route::get("index.php", function (){
    (new HomeController())->index();
});

Route::post("index.php", function (){
    $from = $_POST["start_point"];
    $to = $_POST["end_point"];
    (new HomeController())->search($from, $to);
});



// =============================== LOGIN ================================
Route::get("login", function (){
    if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
        Route::router("vtc", "index.php");
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
    if (!$authenticated){
        Route::router("vtc", "login");
    }
});
// =========================== End login management ====================


// ============================ Registration ==========================
Route::get("register", function (){
    (new RegistrationController())->index();
});

Route::post("register", function (){

    $name = $_POST["name"];
    $family_name = $_POST["family_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $address = $_POST["address"];
    $is_client = strcmp($_POST["client_or_transporter"], "client") == 0; // TODO: Change this to get the value dynamically
    $wilayas = array();
    foreach ($_POST["start_points"] as $w){
        array_push($wilayas, (int)$w);
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


// ================================ END logout management ===================

// ================================= BEGIN LOGOUT ============================
Route::get("logout", function (){
    (new LoginController())->logout();
    Route::router("vtc", "login");
});

// ================================= END LOGOUT ==============================


// =================================== BEGIN USER ACTIONS ====================
Route::get("client", function (){
    (new ClientController())->index();
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
    $transporters = Transporter::getByTrajectory((int)$start_point, (int)$end_point);
    header("Content-Type: application/json");
    echo json_encode(["added" => $added, "transporters" => $transporters]);
});


// Show user announcements
Route::get("announcements", function (){
    (new AnnouncementController())->index();
});

// Delete announcement
Route::post("delete_announcement", function (){
    $announcement_id = $_POST["announcement_id"];
    (new AnnouncementController())->delete($announcement_id);
});


// TODO: Implement this route which shows all transporter applications for a specific
Route::get("applications", function (){
    (new ApplicationController())->index();
});

Route::get("current", function (){
    $user = Auth::user();
    View::make("transporter/running_transports.html.twig", ["title" => "VTC client portal",
        "isAuthenticated" => true,
        "is_transporter"=>Auth::isAuthorizedTransporter(),
        "user" => $user,
        "current" => Transaction::getRunningTransports($user->getUserId())
    ]);
});

Route::post("finish", function (){
    $transporter_id = $_POST["transporter_id"];
    $announcement_id = $_POST["announcement_id"];
    (new TransactionController())->finishTransport($transporter_id, $announcement_id);
});


/// ============================ BEGIN Profile management ========================

Route::get("client_profile", function (){
    (new ClientController())->profile();
});

Route::get("transporter_profile", function (){

    (new TransporterController())->profile();
});


// Certification demands
Route::get("certification", function (){
    (new TransporterController())->certify();
});
/// ====================================== END Profile management =======================
///




// ============================ ANNOUNCEMENT ==================
Route::get("details", function (){
    $announcement_id = $_GET["id"];
    (new AnnouncementController())->show($announcement_id);
});



Route::post("apply", function (){
    (new ApplicationController())->apply();
});


// accept transporter application route
Route::post("accept_application", function (){
    $transporter_id = $_POST["transporter_id"];
    $announcement_id = $_POST["announcement_id"];
    (new TransactionController())->makeTransaction($transporter_id, $announcement_id, true);
});

// refuse transporter application route
Route::post("refuse_application", function (){
    $transporter_id = $_POST["transporter_id"];
    $announcement_id = $_POST["announcement_id"];
    (new ApplicationController())->refuse($transporter_id, $announcement_id);
});


// Transaction history
Route::get("history", function (){
    if (Auth::isAuthorizedTransporter()){
        $transporter = Auth::user();
        View::make("transporter/history.html.twig", [
            "is_transporter" => true,
            "isAuthenticated" => true,
            "transactions" => Transaction::getOfTransporter($transporter->getUserId())
        ]);
    }
});



// Give feedback for a transporter
Route::get("feedback", function (){
    View::make("user/feedback/index.html.twig");
});



// ============================== ADMIN routes ====================================
Route::get("admin", function (){
    View::make("admin/admin.html.twig");
});

Route::get("admin_clients", function (){
    View::make("admin/clients.html.twig");
});

Route::get("admin_transporters", function (){
    View::make("admin/transporters.html.twig");
});



// ============================ Unit testing routes =============================
Route::get("test", function (){
//    header("Content-Type: application/json");
//    json_encode(Transaction::getRunningTransports(1));
//    echo "hello";
    var_dump(Transaction::getRunningTransports(6));
});

Route::post("test", function (){
    header("Content-Type: application/json");
//    var_dump();
//    $filename = $_FILES['image']['name'];
//    echo json_encode(["test" => $filename]);
    echo json_encode($_FILES);
});