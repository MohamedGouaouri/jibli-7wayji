<?php


class TransactionController
{
    public function makeTransaction($transporter_id, $announcement_id, $validated){
        if (Auth::isAuthorizedTransporter() || Auth::isAuthorizedClient()){
            // TODO: Handle the two cases, if the transporter is certified or not
            $transporter = Transporter::get_by_id($transporter_id);
            if ($transporter->isCertified()){
                // Certified transporters have a different path
                // Steps
                //  1. Update announcement status
                //  2. calculate the amount of money to give to the transporter
                //  3. Update transporter inventory
                Announcement::confirm($announcement_id);
                $pricing_controller = new PricingController();
                $price = $pricing_controller->calcPrice($announcement_id);
                // get transporter
                $transporter = Transporter::get_by_id($transporter_id);
                // get inventory
                $current_inventory = $transporter->getInventory();
                Transporter::updateInventory($transporter_id, $current_inventory + $price);
                $transaction = Transaction::add($transporter_id, $announcement_id, $validated);
                if ($transaction != null){
                    // update announcement status
                    Announcement::confirm($announcement_id);
                    TransporterApplication::delete($transporter_id, $announcement_id);
                    header("Content-Type: application/json");
                    echo json_encode(["success" => true, "transaction" => $transaction, "certified" => true]);
                    return;
                }
                header("Content-Type: application/json");
                echo json_encode(["success" => false, "transaction" => null]);
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