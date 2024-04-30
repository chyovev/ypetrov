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
                'by_country' => $request->getTotalVisitorsByCountry(),
                'monthly'    => $request->getMonthlyVisitors(),
            ],
            'poems' => [
                'likes'    => $request->getTopLikedPoems(),
                'reads'    => $request->getTopReadPoems(),
                'comments' => $request->getTopCommentedPoems(),
            ],
        ];

        return view('admin.dashboard', $data);
    }

}
