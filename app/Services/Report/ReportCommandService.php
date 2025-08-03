<?php

namespace App\Services\Report;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportCommandService
{
    public function createReport(array $data): Report
    {
        $data['user_id'] = Auth::id();
        $report = DB::transaction(function () use ($data) {
            return Report::create($data);
        });

        return $report;
    }
}
