<?php

namespace App\Http\Controllers;

use App\Http\Requests\Evaluation\StoreEvaluationRequest;
use App\Models\Evaluation;
use App\Services\Adv\AdQueryService;
use App\Traits\ApiResponse;


use App\Services\Evaluation\EvaluationCommandService;

class EvaluationController extends Controller
{
    use ApiResponse;
    public function __construct(
        public EvaluationCommandService $commandService,
        public AdQueryService $adQueryService,
    ) {}

    public function index()
    {
        return Evaluation::with('user:id,name', 'adv:id,description')->get();
    }

    public function store(StoreEvaluationRequest $request)
    {
        $report = $this->commandService->createEvaluation($request->validated());

        $this->adQueryService->updateAdvRate($report->adv_id);
        
        return $this->success('success',$report);
    }


}
