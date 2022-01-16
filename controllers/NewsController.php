<?php


class NewsController
{


    public function index()
    {
        View::make("news/index.html.twig", [
            "news" => News::all()
        ]);
    }
    public function show($id){
        View::make("news/details.html.twig", [
            "news" => News::byId($id)
        ]);
    }
}