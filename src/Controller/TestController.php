<?php

namespace App\Controller;

class TestController extends Controller
{
    public function index()
    {
        echo 'Hello World';
        self::render('first.html.twig', ['name' => 'John Doe', 
        'occupation' => 'gardener']);
    }
}