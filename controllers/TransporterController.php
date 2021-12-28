<?php


class TransporterController
{
    public function getAllTransporters(): array {
        return Transporter::all();
    }
}