<?php


class TransporterController
{
    public function getAllTransporters(): array {
        return Transporter::all();
    }

    public function sendCertificationDemand($transporter_id): bool {
        return CertificationDemand::save_certification_demand($transporter_id);
    }
}