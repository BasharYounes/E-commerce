<?php

namespace App\Services\Evaluation;

use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationCommandService
{
    public function createEvaluation(array $data): Evaluation
    {
        $data['user_id'] = Auth::id();
        $evaluation = DB::transaction(function () use ($data) {
            return Evaluation::create($data);
        });

        return $evaluation;
    }
}
