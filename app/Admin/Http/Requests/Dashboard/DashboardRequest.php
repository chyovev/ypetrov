<?php

namespace App\Admin\Http\Requests\Dashboard;

use App\Models\Visitor;
use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;

class DashboardRequest extends HttpFormRequest
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Always allow the dashboard request to go through.
     * 
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch total visitors grouped by country.
     * 
     * @return array
     */
    public function getTotalVisitorsByCountry(): array {
        $recent = false;

        return $this->getVisitorsByCountry($recent);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch recent visitors grouped by country.
     * 
     * @return array
     */
    public function getRecentVisitorsByCountry(): array {
        $recent = true;

        return $this->getVisitorsByCountry($recent);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch visitors grouped by country, either total or recent
     * which is determined by the boolean parameter.
     * 
     * @param  bool $recent
     * @return array
     */
    private function getVisitorsByCountry(bool $recent): array {
        $query = Visitor::query()
            ->hasCountryCode()
            ->selectRaw('country_code, COUNT(id) AS visitors')
            ->groupByRaw('country_code', [])
            ->orderByRaw('visitors DESC');

        if ($recent) {
            $query->recentlyVisited(6);
        }

        return $query
            ->limit(5)
            ->get()
            ->toArray();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get recent visitors grouped by month and year.
     * 
     * @return array
     */
    public function getMonthlyVisitors(): array {
        return Visitor::query()
            ->selectRaw('DATE_FORMAT(last_visit_date, "%Y-%m") AS month, COUNT(id) AS visitors')
            ->groupByRaw('month', [])
            ->orderByRaw('month ASC')
            ->recentlyVisited(12)
            ->limit(12)
            ->get()
            ->toArray();
    }

}
