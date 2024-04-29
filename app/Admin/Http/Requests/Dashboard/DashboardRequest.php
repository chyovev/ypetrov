<?php

namespace App\Admin\Http\Requests\Dashboard;

use App\Models\Stats;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Collection;
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
        return Visitor::query()
            ->hasCountryCode()
            ->selectRaw('country_code, COUNT(id) AS visitors')
            ->groupByRaw('country_code', [])
            ->orderByRaw('visitors DESC')
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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get top 10 most liked poems.
     * 
     * @return Collection<Stats>
     */
    public function getTopLikedPoems(): Collection {
        return $this->getTopPoems('total_likes');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get top 10 most read poems.
     * 
     * @return Collection<Stats>
     */
    public function getTopReadPoems(): Collection {
        return $this->getTopPoems('total_impressions');
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get top 10 most liked/read poems.
     * 
     * @param  string $field – total_likes / total_impressions
     * @return Collection<Stats>
     */
    private function getTopPoems(string $field): Collection {
        return Stats::query()
            ->with('statsable')
            ->forPoems()
            ->where($field, '>', 0)
            ->orderByDesc($field)
            ->limit(10)
            ->get();
    }

}
