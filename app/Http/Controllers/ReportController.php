<?php

namespace App\Http\Controllers;

use App\Events\GenericNotificationEvent;
use App\Models\Adv;
use App\Repositories\UserRepository;
use App\Traits\ApiResponse;


use App\Http\Requests\Report\StoreReportRequest;

use App\Models\Report;

use App\Services\Report\ReportCommandService;

class ReportController extends Controller
{
    use ApiResponse;
    public function __construct(
        public ReportCommandService $commandService,
        public UserRepository $userRepository
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

    public function unActiveAdv($id)
    {
        $report = Report::findOrFail($id);

        $Adv = Adv::findOrFail($report->adv_id);

        $Adv->is_active = false;
        $Adv->save();

        GenericNotificationEvent::dispatch($this->userRepository->findById($report->user_id),'Un_Active',['title' => $Adv->tiltle]);

        return $this->success('تم إلغاء تفعيل الإعلان بنجاح');
    }

     public function activeAdv($id)
    {

        $Adv = Adv::findOrFail($id);

        $Adv->is_active = true;
        $Adv->save();

        GenericNotificationEvent::dispatch($this->userRepository->findById($Adv->user_id),'Active',['title' => $Adv->tiltle]);

        return $this->success('تم إعادة تفعيل الإعلان بنجاح');
    }


}
