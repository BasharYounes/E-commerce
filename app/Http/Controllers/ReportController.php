<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;


use App\Http\Requests\Report\StoreReportRequest;

use App\Models\Report;

use App\Services\Report\ReportCommandService;

class ReportController extends Controller
{
    use ApiResponse;
    public function __construct(
        public ReportCommandService $commandService
    ) {}

    public function index()
    {
        return Report::with('user:id,name', 'adv:id,description')->get();
    }

    public function store(StoreReportRequest $request)
    {
        $report = $this->commandService->createReport($request->validated());
        return $this->success('success',$report);
    }


}
