<?php


class FeedbackController
{
    public function add($user_id, $transporter_id, $note, $message){
        return Feedback::addFeedBack($user_id, $transporter_id, $note, $message);
    }
}