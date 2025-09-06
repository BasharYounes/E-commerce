<?php

namespace App\Services\Report;

use App\Models\Report;

use App\Traits\ApiResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportCommandService
{
    use ApiResponse;
    public function createReport(array $data): Report
    {
        $data['user_id'] = Auth::id();
        $report = DB::transaction(function () use ($data) {
            return Report::create($data);
        });

        return $report;
    }

    public function findReport($id)
    {
    //     return Report::with([
    //     'user:id,is_banned',
    //     'adv' => function($query) {
    //         $query->select('*'); 
    //         $query->with(['category' => function($q) {
    //             $q->select('id', 'name');
    //         }]);
    //     }
    // ])->findOrFail($id);
     return Report::with([
        'user:id,is_banned',
        'adv.category:id,name'
    ])->findOrFail($id);
    }

    public function updateViewReport(Report $report,$data)
    {
        $report->update($data);
    }

    public function getAllReports()
    {
        $reports = Report::with('user:id,name', 'adv:id,description,is_active')->get();
        $nonViewedReports = Report::where('is_view',false)->count();
        return $this->success('The Reports are With count of reports which are not viewed ',[$reports,$nonViewedReports]);
    }
}
