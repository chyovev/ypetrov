<?php

namespace App\Admin\Http\Controllers;

use App\Utils\Breadcrumbs\BreadcrumbManager;
use App\View\ViewModels\DashboardViewModel;
use Illuminate\View\View;

class DashboardController
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private BreadcrumbManager $breadcrumbManager) {
        //
    }
    
    ///////////////////////////////////////////////////////////////////////////
    public function index(): View {
        $data = [
            'stats'       => new DashboardViewModel,
            'breadcrumbs' => $this->breadcrumbManager->getCrumbs('dashboard'),
        ];

        return view('admin.dashboard', $data);
    }

}
