<?php


class ApplicationController
{
    public function add($transporter_id, $announcement_id){
        return TransporterApplication::add($transporter_id, $announcement_id);
    }

    public function exists($transporter_id, $announcement_id){
        return TransporterApplication::exists($transporter_id, $announcement_id);
    }
    
}