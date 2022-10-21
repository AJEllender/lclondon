<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Yadda\Enso\Utilities\Traits\SharesBodyClasses;

class DashboardController extends Controller
{
    use SharesBodyClasses;

    public function __construct()
    {
        $this->bodyClasses('dashboard');
    }

    public function index()
    {
        return view('admin.dashboard');
    }
}
