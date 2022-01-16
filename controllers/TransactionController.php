<?php


class TransactionController
{
    public function makeTransaction($transporter_id, $announcement_id, $validated){
        if (Auth::isAuthorizedTransporter() || Auth::isAuthorizedClient()){
            // TODO: Handle the two cases, if the transporter is certified or not
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
    public function finishTransport($transporter_id, $announcement_id){
        if (Auth::isAuthorizedTransporter()){
            $transporter = Transporter::get_by_id($transporter_id);
            // update transport status
            Transaction::finishTransport($transporter_id, $announcement_id);
            $transport = Transaction::get($transporter_id, $announcement_id);
            if ($transporter->isCertified()){
                // get the pricing
                $pricing_controller = new PricingController();
                $price = $pricing_controller->calcPrice($announcement_id);
                // get inventory
                $current_inventory = $transporter->getInventory();
                // update the inventory
                Transporter::updateInventory($transporter_id, $current_inventory + $price);
                // archive announcement
                Announcement::archive($announcement_id);
                header("Content-Type: application/json");
                echo json_encode(["success" => true, "transport" => $transport, "certified" => true]);
                return;
            }
        }
    }


    // give feedback to transporter

}