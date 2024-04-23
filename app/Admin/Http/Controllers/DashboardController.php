<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\Dashboard\DashboardRequest;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    public function index(DashboardRequest $request) {
        $data = [
            'visitors' => [
                'total'  => $request->getTotalVisitorsByCountry(),
                'recent' => $request->getRecentVisitorsByCountry(),
            ],
        ];

        return view('admin.dashboard', $data);
    }

}
