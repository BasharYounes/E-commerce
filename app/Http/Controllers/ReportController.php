<?php

namespace App\Http\Controllers;

use App\Events\GenericNotificationEvent;
use App\Models\Adv;
use App\Repositories\AdvRepository;
use App\Repositories\UserRepository;
use App\Services\Adv\AdCommandService;
use App\Traits\ApiResponse;


use App\Http\Requests\Report\StoreReportRequest;

use App\Models\Report;

use App\Services\Report\ReportCommandService;

class ReportController extends Controller
{
    use ApiResponse;
    public function __construct(
        public ReportCommandService $commandService,
        public UserRepository $userRepository,
        public AdvRepository $advRepository,
        public AdCommandService $adCommandService,
    ) {}

    public function index()
    {
        return $this->commandService->getAllReports();
    }

    public function store(StoreReportRequest $request)
    {
        $report = $this->commandService->createReport($request->validated());
        return $this->success('success',$report);
    }

    public function unActiveAdv($id)
    {

        $Adv = $this->advRepository->findAdv($id);

        $this->adCommandService->updateAd($Adv,['is_active' => false]);

        GenericNotificationEvent::dispatch($this->userRepository->findById($Adv->user_id),'Un_Active',['title' => $Adv->tiltle]);

        return $this->success('تم إلغاء تفعيل الإعلان بنجاح');
    }

     public function activeAdv($id)
    {

        $Adv = $this->advRepository->findAdv($id);

        $this->adCommandService->updateAd($Adv,['is_active' => true]);

        GenericNotificationEvent::dispatch($this->userRepository->findById($Adv->user_id),'Active',['title' => $Adv->tiltle]);

        return $this->success('تم إعادة تفعيل الإعلان بنجاح');
    }

    public function showReport($id)
    {
        $report = $this->commandService->findReport($id);

        $this->commandService->updateViewReport($report,['is_view' => true]);

        return $this->success('الإعلان هو :',$report);
    }


}
