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
    __DIR__ . DIRECTORY_SEPARATOR . "resources/views/news",
    ]);
View::$twig = new Environment(View::$loader);

Route::get(/**
 *
 */ "index.php", function (){
    (new HomeController())->index();
});

Route::post(/**
 *
 */ "index.php", function (){
    $from = $_POST["start_point"];
    $to = $_POST["end_point"];
    (new HomeController())->search($from, $to);
});

Route::get("presentation", function (){
    (new HomeController())->presentation();
});



// =============================== LOGIN ================================
Route::get(/**
 *
 */ "login", function (){
    if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
        Route::router("vtc", "index.php");
        return;
    }
    View::make("auth/login.html.twig", ["title" => "VTC application"]);
});

Route::post(/**
 *
 */ "login", function (){

    $email = $_POST["email"];
    $password = $_POST["password"];
    $is_client = strcmp($_POST["client_or_transporter"], "client") == 0;
    $controller = new LoginController();
    $authenticated = $controller->authenticate($email, $password, $is_client);
    if (!$authenticated){
        Route::router("vtc", "login");
    }
});
// =========================== End login management ====================


// ============================ Registration ==========================
Route::get(/**
 *
 */ "register", function (){
    (new RegistrationController())->index();
});

Route::post(/**
 *
 */ "register", function (){

    $name = $_POST["name"];
    $family_name = $_POST["family_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $address = $_POST["address"];
    $is_client = strcmp($_POST["client_or_transporter"], "client") == 0; // TODO: Change this to get the value dynamically
    $phone_number = $_POST["phone_number"];
    $wilayas = array();
    if (!$is_client){

        foreach ($_POST["wilayas"] as $w){
            array_push($wilayas, (int)$w);
        }
    }
    $controller = new RegistrationController();
    $registered = $controller->register($name, $family_name, $email, $phone_number, $password, $address, $is_client, $wilayas);

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
Route::get(/**
 *
 */ "logout", function (){
    (new LoginController())->logout();
    Route::router("vtc", "login");
});

// ================================= END LOGOUT ==============================


// =================================== BEGIN USER ACTIONS ====================
Route::get(/**
 *
 */ "client", function (){
    (new ClientController())->index();
});




// Add announcement post request
Route::post(/**
 *
 */ "new_announcement", function (){
//    header("Content-Type: application/json");
    // get request parameters
    $start_point = $_POST["start_point"];
    $end_point = $_POST["end_point"];
    $type = $_POST["type"];
    $weight = $_POST["weight"];
    $volume = $_POST["volume"];
    $way = $_POST["way"];
    $message = trim($_POST["message"]);
//    echo json_encode("hello");
    $image_path = Uploader::upload("announcement_image");

    $announcement_controller = new AnnouncementController();

    $added = $announcement_controller->addNewAnnouncement($start_point, $end_point, $type, $weight, $volume, $way, $message, $image_path);
//    $transporters = Transporter::getByTrajectory((int)$start_point, (int)$end_point);

//    echo json_encode(["added" => $added]);
});


// Show user announcements
Route::get(/**
 *
 */ "announcements", function (){
    (new AnnouncementController())->index();
});

// Delete announcement
Route::post(/**
 *
 */ "delete_announcement", function (){
    $announcement_id = $_POST["announcement_id"];
    (new AnnouncementController())->delete($announcement_id);
});


// TODO: Implement this route which shows all transporter applications for a specific
Route::get(/**
 *
 */ "applications", function (){
    (new ApplicationController())->index();
});

Route::get(/**
 *
 */ "current", function (){
    $user = Auth::user();
    View::make("transporter/running_transports.html.twig", ["title" => "VTC client portal",
        "isAuthenticated" => true,
        "is_transporter"=>Auth::isAuthorizedTransporter(),
        "user" => $user,
        "current" => Transaction::getRunningTransports($user->getUserId())
    ]);
});

// update current trajectory routes
Route::get("trajectory", function (){
    if (Auth::isAuthorizedTransporter()){
        $transporter = Auth::user();
        View::make("transporter/trajectory.html.twig", [
            "is_transporter" => true,
            "isAuthenticated" => true,
            "user" => $transporter,
            "not_covered" => Transporter::getNonCoveredWilayas($transporter->getUserId()),
            "covered" => Transporter::getCoveredWilayas($transporter->getUserId())
        ]);
    }
});

Route::post("update_add", function (){
    $wilaya_id = (int)$_POST["wilaya_id"];
    $transporter = Auth::user();
    Transporter::add_wilaya($transporter->getUserId(), $wilaya_id);
    header("Content-Type: application/json");
    echo json_encode(["success" => true, "message" => "added"]);

});
Route::post("update_delete", function (){
    $wilaya_id = (int)$_POST["wilaya_id"];
    $transporter = Auth::user();
    Transporter::delete_wilaya($transporter->getUserId(), $wilaya_id);
    header("Content-Type: application/json");
    echo json_encode(["success" => true, "message" => "deleted"]);
});



Route::post(/**
 *  Transporter calls this route to inform the system that transport is done
 *
 */ "finish", function (){
    $transporter_id = $_POST["transporter_id"];
    $announcement_id = $_POST["announcement_id"];
    (new TransactionController())->finishTransport($transporter_id, $announcement_id);
});


/// ============================ BEGIN Profile management ========================

Route::get(/**
 *
 */ "client_profile", function (){
    (new ClientController())->profile();
});

Route::get(/**
 *
 */ "transporter_profile", function (){

    (new TransporterController())->profile();
});


// update profile
Route::post("update_profile", function (){
    if (Auth::isAuthorizedTransporter() || Auth::isAuthorizedClient()){
        $user = Auth::user();
        $email = $_POST["email"];
        $phone_number = $_POST["phone_number"];
        $address = $_POST["address"];
        header("Content-Type: application/json");
        $added = User::update($user->user_id, $email, $phone_number, $address);
        if ($added){
            echo json_encode(["success" => true, "message" => "Your profile has been updated"]);

        }else{
            echo json_encode(["success" => false, "message" => "You can't update your profile"]);
        }
    }
});

Route::get("client_demands", function (){
    (new TransactionController())->client_demands();
});


// Certification demands
Route::get(/**
 *
 */ "certification", function (){
    (new TransporterController())->certify();
});
/// ====================================== END Profile management =======================
///




// ============================ ANNOUNCEMENT ==================
Route::get(/**
 *
 */ "details", function (){
    $announcement_id = $_GET["id"];
    (new AnnouncementController())->show($announcement_id);
});


// Transporter application
Route::post(/**
 *
 */ "apply", function (){
    (new ApplicationController())->apply();
});


// accept transporter application route
Route::post(/**
 *
 */ "accept_application", function (){
    $transporter_id = $_POST["transporter_id"];
    $announcement_id = $_POST["announcement_id"];
    (new TransactionController())->makeTransaction($transporter_id, $announcement_id, true);
});

// refuse transporter application route
Route::post(/**
 *
 */ "refuse_application", function (){
    $transporter_id = $_POST["transporter_id"];
    $announcement_id = $_POST["announcement_id"];
    (new ApplicationController())->refuse($transporter_id, $announcement_id);
});


// client uses this route to make a demand to a transporter
// for a transport
Route::post("demand", function (){
    $announcement_id = $_POST["announcement_id"];
    $transporter_id = $_POST["transporter_id"];
    (new ApplicationController())->client_demand($announcement_id, $transporter_id);
});


// Transaction history
Route::get(/**
 *
 */ "history", function (){
    if (Auth::isAuthorizedTransporter()){
        $transporter = Auth::user();
        View::make("transporter/history.html.twig", [
            "is_transporter" => true,
            "isAuthenticated" => true,
            "user" => Auth::user(),
            "transactions" => Transaction::getOfTransporter($transporter->getUserId())
        ]);
    }else if (Auth::isAuthorizedClient()){
        // history for clients
        $client = Auth::user();
        View::make("client/history.html.twig", [
            "is_transporter" => false,
            "isAuthenticated" => true,
            "user" => $client,
            "transactions" => Transaction::getOfClient($client->getUserId())
        ]);
    }
});



// Give feedback for a transporter
Route::get(/**
 *
 */ "feedback", function (){
    if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
        $user = Auth::user();
        View::make("user/feedback/index.html.twig", [
            "isAuthenticated" => true,
            "user" => $user
        ]);
    }
});

Route::post("feedback", function (){
    if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
        $user = Auth::user();
        $noted_transporter_id = (int)$_POST["transporter_id"];
        $note = (int)$_POST["note"];
        $message = $_POST["message"];
        $added = (new FeedbackController())->add($user->getUserId(), $noted_transporter_id, $note, $message);
        header("Content-Type: application/json");
        if ($added){
            echo json_encode(["success" => true, "message" => "Merci pour votre feedback"]);
        }
        else{
            echo json_encode(["success" => false, "message" => "Vous pouvez pas effectuer un feedback feedback"]);
        }
    }
});

// User signal
Route::get("signal", function (){
    if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
        $user = Auth::user();
        View::make("user/signal.php.twig", [
            "user" => $user,
            "isAuthenticated" => true
        ]);
    }
});

Route::post("client_signals", function (){
    if (Auth::isAuthorizedTransporter() || Auth::isAuthorizedClient()){
        $user = Auth::user();
        $transporter_id = $_POST["transporter_id"];
        $message = $_POST["message"];
        $added = (new SignalsController())->addClientSignal($user->user_id, $transporter_id, $message);
        header("Content-Type: application/json");
        if ($added){

            echo json_encode(["success" => true, "message" => "Votre signale a ete envoye"]);

        }
        else{
            echo json_encode(["success" => true, "message" => "Votre signalement n'a pas pu etre enregistre"]);

        }
    }
});
Route::post("transporter_signals", function (){
    if (Auth::isAuthorizedTransporter() || Auth::isAuthorizedClient()){
        $user = Auth::user();
        $client_id = $_POST["client_id"];
        $message = $_POST["message"];
        $added = (new SignalsController())->addTransporterSignal($user->user_id, $client_id, $message);
        header("Content-Type: application/json");
        if ($added){
            echo json_encode(["success" => true, "message" => "Votre signale a ete envoye"]);
        }
        else{
            echo json_encode(["success" => true, "message" => "Votre signalement n'a pas pu etre enregistre"]);

        }
    }
});


// news
Route::get("news", function (){

    (new NewsController())->index();
});
Route::get("news_details", function (){
    $id = $_GET["id"];
    (new NewsController())->show($id);
});


// Contact page
Route::get("contact", function (){
    View::make("contact.html.twig", [
        "email" => "admin@vtc.dz",
        "address" => "Alger",
        "mobile" => "0561586786"
    ]);
});



// ============================== ADMIN routes ====================================

// admin login
Route::get("admin_login", function (){
    if (!Auth::isAdmin()){
        (new AdminController())->loginPage();
        return;
    }
    Route::router("vtc","admin");
});

Route::post("admin_login", function (){
    $email = $_POST["email"];
    $password = $_POST["password"];
    if ($email == "admin@esi.dz" and $password == "admin"){
        (new LoginController())->adminAuthenticate();
    }
});


Route::get(/**
 *
 */ "admin", function (){
     //check if admin is authenticated
    (new AdminController())->index();
});

Route::get(/**
 *
 */ "admin_clients", function (){
    // 1. check if admin is connected
    // 2. Get all clients

    (new AdminController())->clients_index();
//    if (Auth::isAdmin()){
//        (new AdminController())->clients_index();
//    }
});

Route::get(/**
 *
 */ "banned_users", function (){
    (new AdminController())->banned_users();
});

// Ban a user
Route::post("ban_user", function (){
    // check if admin authenticated
    $user_id = $_POST["user_id"];
    (new AdminController())->ban_user($user_id);
});

Route::post("unban_user", function (){
    // check if admin authenticated
    $user_id = $_POST["user_id"];
    (new AdminController())->unban_user($user_id);
});





Route::get(/**
 *
 */ "admin_transporters", function (){
    (new AdminController())->transporters_index();
});

// validate transporter
Route::post("validate_transporter", function (){
    $transporter_id = $_POST["transporter_id"];
    (new AdminController())->validate_transporter($transporter_id);
});


// Validate certification demand

// Announcement management by the admin
Route::get("admin_announcements", function (){
    (new AdminController())->announcements_index();
});

// Validate announcement
Route::post("validate_announcement", function (){
    $announcement_id = $_POST["announcement_id"];
    Announcement::admin_validate($announcement_id);
    header("Content-Type: application/json");
    echo json_encode(["success" => true, "message" => "Vous avez valider cette annonce"]);

});

// delete announcement (archive it)
Route::post("delete_announcement", function (){
    $announcement_id = $_POST["announcement_id"];
    Announcement::archive($announcement_id);
    header("Content-Type: application/json");
    echo json_encode(["success" => true, "message" => "Vous avez archiver cette annonce"]);
});


// pricing view
Route::get("admin_pricing", function (){
    (new AdminController())->pricing_index();
});

// analytics view
Route::get("admin_analytics", function (){
    (new AdminController())->analytics_index();
});

Route::get("admin_analytics_api", function (){
    (new AdminController())->api();
});


// news edit
Route::get("admin_news", function (){
    View::make("admin/news.html.twig", [
        "all_news" => News::all()
    ]);
});

Route::post("admin_add_news", function (){
    $title = $_POST["title"];
    $synopsis = $_POST["synopsis"];
    $content = $_POST["content"];

    (new AdminController())->post_news($title, $synopsis, $content);
});

Route::post("admin_delete_news", function (){
    $id = $_POST["id"];
    (new AdminController())->delete_news($id);
});

// Update pricing
Route::post("update_pricing", function (){
    $from = $_POST["start_point"];
    $to = $_POST["end_point"];
    $price = $_POST["price"];
    (new AdminController())->update_pricing($from, $to, $price);
});


// show signals dashboard
Route::get("signals", function (){
    View::make("admin/signals.html.twig", [
        "client_signals" => ClientSignal::all(),
        "transporter_signals" => TransporterSignal::all()
    ]);
});

// Admin logout
Route::get("admin_logout", function (){
    (new LoginController())->adminLogout();
});


// not found page
Route::get("404", function (){
    View::make("404.html.twig");
});


// ============================ Unit testing routes =============================
Route::get(/**
 *
 */ "test", function (){
    (new LoginController())->adminAuthenticate();
    echo Auth::isAdmin();
 });

Route::post(/**
 *
 */ "test", function (){
    var_dump($_FILES["announcement_image"]);
});