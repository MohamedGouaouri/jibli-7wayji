<?php


class TransactionController
{
    public function makeTransaction($transporter_id, $announcement_id, $validated){
        if (Auth::isAuthorizedTransporter() || Auth::isAuthorizedClient()){
            // TODO: Handle the two cases, if the transporter is certified or not
            $transporter = Transporter::get_by_id($transporter_id);
            if ($transporter->isCertified()){
                // Certified transporters have a different path

            }
            else{
                // A non certified transporter
                $transaction = Transaction::add($transporter_id, $announcement_id, $validated);
                if ($transaction != null){
                    // update announcement status
                    Announcement::confirm($announcement_id);
                    TransporterApplication::delete($transporter_id, $announcement_id);
                    header("Content-Type: application/json");
                    echo json_encode(["success" => true, "transaction" => $transaction]);
                    return;
                }
                header("Content-Type: application/json");
                echo json_encode(["success" => false, "transaction" => null]);
            }

        }
    }
}