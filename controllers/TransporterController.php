<?php


class TransporterController
{
    public function index(){
    }

    public function profile(){
        if (Auth::isAuthorizedTransporter()){
            $transporter = Auth::user();
            View::make("transporter/profile.html.twig", [
                "isAuthenticated" => true,
                "user" => $transporter,
                "is_transporter" => true,
            ]);
            return;
        }
        Route::router("vtc", "login");
    }


    public function certify(){
        if (Auth::isAuthorizedTransporter()){
            // TODO: Update DB
            $transporter = Transporter::get_by_id(Auth::user()->getUserId());
            if (!$transporter->isCertified()){
                if ($this->sendCertificationDemand(Auth::user()->getUserId())){
                    $file = "documents/cert.pdf";
                    StatusController::send_documents($file);
                }else{
                    header("Content-Type: application/json");
                    echo json_encode(["error"=>true, "message" => "Vous pouvez pas faire une demande de certification"]);
                }
            }else{
                header("Content-Type: application/json");
                echo json_encode(["error" => true, "message" => "Vous avez deja fait une demande de certification"]);
            }
        }
    }


    public function getAllTransporters(): array {
        return Transporter::all();
    }

    public function sendCertificationDemand($transporter_id): bool {
        return CertificationDemand::save_certification_demand($transporter_id);
    }
}