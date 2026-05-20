<?php

namespace App\Providers;

use View;
use Blade;
use Carbon\Carbon;
use App\View\Composers\NavigationComposer;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Http\Middleware\TrimStrings;

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
        $this->registerMetaBladeDirectives();
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
     * The meta directives expect an object implementing
     * the SEO interface to have been passed to the view
     * under the $seo name. If no such object is passed,
     * a default value is be used.
     */
    private function registerMetaBladeDirectives(): void {
        Blade::directive('metaTitle', function () {
            return '<?php
                if (isset($seo)) {
                    $title = cleanString($seo->getMetaTitle());
            
                    echo "{$title} | Йосиф Петров (1909 – 2004)";
                }
                else {
                    echo "Официален сайт в памет на поета Йосиф Петров (1909 – 2004)";
                }
            ?>';
        });
        
        Blade::directive('metaDescription', function () {
            return '<?php
                if (isset($seo)) {
                    $description = cleanString($seo->getMetaDescription());
                }

                if ( ! isset($description)) {
                    $description = "Йосиф Петров е български поет, общественик и политик";
                }

                echo str($description)->words(50);
            ?>';
        });
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
