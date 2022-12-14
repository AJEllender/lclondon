<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Yadda\Enso\Crud\Traits\UsesPage;

class PageController extends Controller
{
    use UsesPage;

    /**
     * Show the page it's slug
     *
     * @param string $slug
     *
     * @return View
     */
    public function show($slug): View
    {
        if (in_array($slug, Config::get('enso.crud.page.blacklist', []))) {
            abort(404);
        }

        $page = $this->usePage($slug);

        return ViewFacade::make($page->getViewName(), compact('page'));
    }
}
