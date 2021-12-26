<?php


class WilayaController
{
    public function get_all(): array {
        return Wilaya::all();
    }
}