<?php

namespace App\Providers;

use View;
use App\View\Composers\NavigationComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        $this->setAdminPaginationTheme();
        $this->registerNavigationViewComposer();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The pagination links in the Administrative panel
     * are generated automatically by the links() method.
     * Laravel supports multiple Bootstrap versions for
     * that generation, and since the current theme is
     * running Bootstrap 4, so should the pagination links.
     */
    private function setAdminPaginationTheme(): void {
        Paginator::useBootstrapFour();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The public navigation gets generated dynamically
     * using data from the database which should be fetched
     * by a view composer right before the respective
     * navigation view is loaded.
     */
    private function registerNavigationViewComposer(): void {
        $views = [
            'public.layout.navigation',
        ];

        View::composer($views, NavigationComposer::class);
    }
}
