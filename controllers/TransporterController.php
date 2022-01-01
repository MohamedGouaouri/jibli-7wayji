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
            if ((new TransporterController())->sendCertificationDemand(Auth::user()->getTransporterId())){
                $file = "documents/cert.pdf";
                StatusController::send_documents($file);
            }else{
                echo json_encode(["message" => "Vous pouvez pas faire une demande de certifaction"]);
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