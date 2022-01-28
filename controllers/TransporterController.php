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
                "certification" => CertificationDemand::of($transporter->getUserId())
            ]);
            return;
        }
        Route::router("vtc", "login");
    }


    public function certify(){
        if (Auth::isAuthorizedTransporter()){
            // TODO: Update DB
            header("Content-Type: application/json");
            $transporter = Transporter::get_by_id(Auth::user()->getUserId());
            if (!$transporter->isCertified()){
                $demand = CertificationDemand::of($transporter->getUserId());
                if ($demand){
                    echo json_encode(["error"=>true, "message" => "Votre demande est entrain d'etre etudie"]);
                }else {
                    if ($this->sendCertificationDemand(Auth::user()->getUserId())) {
                        json_encode(["error" => false, "message" => "Votre demande de certification a ete enrigistre"]);
                    } else {
                        echo json_encode(["error"=>true, "message" => "Vous pouvez pas faire une demande de certification"]);
                    }
                }
            }else{
                echo json_encode(["error" => true, "message" => "Vous ete deja un transporteur certifie"]);
            }
        }
    }


    public function sendCertificationDemand($transporter_id): bool {
        return CertificationDemand::save_certification_demand($transporter_id);
    }
}