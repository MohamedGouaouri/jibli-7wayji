<?php


class SignalsController
{
    public function addClientSignal($client_id, $transporter_id, $message){
        return ClientSignal::add($client_id, $transporter_id, $message);
    }
    public function addTransporterSignal($transporter_id, $client_id, $message){
        return TransporterSignal::add($client_id, $transporter_id, $message);
    }

}