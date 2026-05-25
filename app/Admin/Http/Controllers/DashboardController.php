<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\Dashboard\DashboardRequest;
use App\Utils\Breadcrumbs\BreadcrumbManager;

class DashboardController
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private BreadcrumbManager $breadcrumbManager) {
        //
    }
    
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
            'breadcrumbs' => $this->breadcrumbManager->getCrumbs('dashboard'),
        ];

        return view('admin.dashboard', $data);
    }

}
