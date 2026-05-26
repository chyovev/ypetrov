<?php

namespace App\Providers;

use Carbon\Carbon;
use App\View\Composers\NavigationComposer;
use App\View\Composers\SeoComposer;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Support\Facades\View;

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
        $this->setCarbonLocale();
        $this->setAdminPaginationTheme();
        $this->registerNavigationViewComposer();
        $this->registerSeoViewComposer();
        $this->defineTrimStringsExemptions();
        $this->overrideUnauthenticatedRedirect();
        $this->overrideAuthenticatedRedirect();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Carbon uses the default system locale when displaying dates.
     * It's best to strictly instruct it to use the application's
     * locale which is Bulgarian.
     * 
     * @return void
     */
    private function setCarbonLocale(): void {
        Carbon::setLocale(config('app.locale'));
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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Handle SEO meta tags in the public layout.
     */
    private function registerSeoViewComposer(): void {
        $views = [
            'public.layout.default',
        ];

        View::composer($views, SeoComposer::class);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * As the name suggests, the TrimStrings middleware takes care
     * of trimming all strings of an incoming request. In most
     * cases that's okay, but some poems make use of leading
     * white-spaces for visual text alignment.
     * Since the exemption should apply only to the text field,
     * the skipWhen is used to detect the current request, but
     * its result is always false (i.e. no skipping) – instead,
     * the text field gets added to the except list.
     */
    private function defineTrimStringsExemptions(): void {
        TrimStrings::skipWhen(function(Request $request): bool {
            if ($request->routeIs('admin.poems.*')) {
                TrimStrings::except(['text']);
            }

            return false;
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * When an unauthenticated user tries to access a guarded route,
     * they get automatically redirected to the login page by the
     * Authenticate middleware.
     * By default it uses a route called 'login' which does not
     * exist here, so it should be overridden to 'admin.login'.
     */
    private function overrideUnauthenticatedRedirect(): void {
        Authenticate::redirectUsing(function(Request $request): string {
            return route('admin.login');
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Similarly to the Authenticate middleware, the RedirectIfAuthenticated
     * one redirects authenticated users to the home page in case they try
     * to access a guest-only route.
     * Since authentication matters only in the context of the admin panel,
     * the home page should be the admin home page.
     */
    private function overrideAuthenticatedRedirect(): void {
        RedirectIfAuthenticated::redirectUsing(function(Request $request): string {
            return route('admin.home');
        });
    }

}
