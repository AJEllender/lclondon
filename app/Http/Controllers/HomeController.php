<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Show the home page
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return (new PageController)->show('home');
    }

    /**
     * Redirect to the holding page
     *
     * @return RedirectResponse
     */
    public function redirect(): RedirectResponse
    {
        return Redirect::to('/');
    }
}
