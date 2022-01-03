<?php


class TransactionController
{
    public function makeTransaction($transporter_id, $announcement_id, $validated){
        if (Auth::isAuthorizedTransporter() || Auth::isAuthorizedClient()){
            $transaction = Transaction::add($transporter_id, $announcement_id, $validated);
            if ($transaction != null){
                header("Content-Type: application/json");
                echo json_encode(["success" => true, "transaction" => $transaction]);
                return;
            }
            header("Content-Type: application/json");
            echo json_encode(["success" => false, "transaction" => null]);

        }
    }
}