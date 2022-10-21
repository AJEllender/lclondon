<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class HoldingPageController extends Controller
{
    /**
     * Show the holding page
     *
     * @return View|Factory
     */
    public function index()
    {
        header('Cache-Control', 'no-cache, must-revalidate');

        return (new PageController)->show('holding-page');
    }
}
