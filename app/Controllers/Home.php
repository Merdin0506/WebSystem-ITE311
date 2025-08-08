<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // CI4 way of loading a view
        return view('template');
    }
}