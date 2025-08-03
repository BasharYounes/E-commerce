<?php

namespace App\Http\Controllers;

use App\Http\Requests\Evaluation\StoreEvaluationRequest;
use App\Models\Evaluation;
use App\Traits\ApiResponse;


use App\Services\Evaluation\EvaluationCommandService;

class EvaluationController extends Controller
{
    use ApiResponse;
    public function __construct(
        public EvaluationCommandService $commandService
    ) {}

    public function index()
    {
        return Evaluation::with('user:id,name', 'adv:id,description')->get();
    }

    public function store(StoreEvaluationRequest $request)
    {
        $report = $this->commandService->createEvaluation($request->validated());
        return $this->success('success',$report);
    }


}
