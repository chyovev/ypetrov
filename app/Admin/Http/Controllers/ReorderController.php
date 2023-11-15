<?php

namespace App\Admin\Http\Controllers;

use DB;
use App\Http\Controllers\Controller;
use App\Admin\Http\Requests\ReorderRequest;

class ReorderController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get all records from a table ordered by their order column values.
     * 
     * @param  string $table – reorderable table (wihte-listed in admin routes)
     * @return array  $data  – all records from said table
     */
    public function get_all_records(string $table) {
        $data = DB::table($table)->orderBy('order')->get();

        return $data;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The IDs of all records should be passed in their new order.
     * Cycle through each of them, assign them a new order value
     * and save them individually.
     * 
     * @param  ReorderRequest $request – data validation
     * @param  string $table – which table the records are being reordered of
     * @return array
     */
    public function save_reordered_records(ReorderRequest $request, string $table) {
        $ids   = $request->validated('id');
        $order = 1;

        foreach ($ids as $id) {
            DB::table($table)->where('id', $id)->update(['order' => $order]);

            $order++;
        }

        // set a flash message which will be shown
        // as soon as the page gets reloaded
        session()->flash('success', __('global.reorder_successful'));

        // return empty array as a OK response (HTTP code 200)
        return [];
    }

}
