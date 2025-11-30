<?php

namespace App\Http\Controllers\GeneralManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralManagementController extends Controller
{
    /**
     * Display the home page of General Management section.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        return view('general-management.home');
    }
}
