<?php


/**
 * Class PricingController
 * Handles all pricing related operation
 * for example:
 *  - Calculates the pricing of an announcement
 *  - withdraw a percentage of an announcement price from a transporter
 */
class PricingController
{
    private float $percentage = 0.2;

    /** Calculates the price of a transport
     * @param $announcement_id
     * @return float|int
     */
    public function calcPrice($announcement_id){
        $announcement = Announcement::byId($announcement_id);
        $raw_price = $announcement->getPrice();
        return $this->percentage * $raw_price;
    }
}