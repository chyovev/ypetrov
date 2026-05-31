<?php

namespace App\View\ViewModels;

use Carbon\Carbon;
use App\Models\Poem;
use App\Models\Stats;
use App\Models\Visitor;
use Illuminate\Support\Collection;

class DashboardViewModel
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch total visitors grouped by country.
     */
    public function getTotalVisitorsByCountry(): Collection {
        return Visitor::query()
            ->whereNotNull('country_code')
            ->selectRaw('country_code, COUNT(id) AS visitors')
            ->groupByRaw('country_code')
            ->orderByRaw('visitors DESC')
            ->limit(5)
            ->get();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get recent visitors grouped by month and year.
     */
    public function getMonthlyVisitors(): Collection {
        return Visitor::query()
            ->selectRaw('DATE_FORMAT(last_visit_date, "%Y-%m") AS month, COUNT(id) AS visitors')
            ->groupByRaw('month')
            ->orderByRaw('month ASC')
            ->whereBetween('last_visit_date', [
                Carbon::now()->subMonths(12)->startOfDay(),
                Carbon::now(),
            ])
            ->limit(12)
            ->get();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get top 10 most liked poems.
     * 
     * @return Collection<Stats>
     */
    public function getTopLikedPoems(): Collection {
        return $this->getTopPoemsStats('total_likes');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get top 10 most read poems.
     * 
     * @return Collection<Stats>
     */
    public function getTopReadPoems(): Collection {
        return $this->getTopPoemsStats('total_impressions');
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get top 10 most liked/read poems.
     * 
     * @param  string $field – total_likes / total_impressions
     * @return Collection<Stats>
     */
    private function getTopPoemsStats(string $field): Collection {
        return Stats::query()
            ->with('statsable')
            ->where('statsable_type', Poem::class)
            ->where($field, '>', 0)
            ->orderByDesc($field)
            ->limit(10)
            ->get();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * @return Collection<Poem>
     */
    public function getTopCommentedPoems(): Collection {
        return Poem::query()
            ->has('comments')
            ->withCount('comments')
            ->orderByDesc('comments_count')
            ->limit(10)
            ->get();
    }

}
