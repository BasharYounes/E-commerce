<?php

namespace App\Http\Controllers;

use App\Events\GenericNotificationEvent;
use App\Repositories\AuthunticateRequests\RequestRepository;
use App\Repositories\UserRepository;
use Auth;
use Illuminate\Http\Request;
use App\Models\AuthunticateRequest;
use App\Traits\ApiResponse;

class AuthunticateRequestController extends Controller
{
    use ApiResponse;

    public function __construct(
        public RequestRepository $requestRepository,
        public UserRepository $userRepository
    ){}

    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,zip,rar|max:5120' 
        ]);

        $existingRequest = $this->requestRepository->findRequestByUser_id();
                                            
        if ($existingRequest) {
            return $this->error('لقد حدث خطأ',[
                'error' => 'لديك طلب توثيق قيد المراجعة بالفعل'
            ], 400);
        }

        $documentPath = $this->requestRepository->storeDocument($request);

        $authRequest = $this->requestRepository->storeRequest($documentPath);

        return $this->success('تم تقديم طلب التوثيق بنجاح وسيتم مراجعته قريباً',['data' => $authRequest], 201);
    }

    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $requests = $this->requestRepository->allPendingRequests($status);

        return $this->success('The Resualts :',['data' => $requests]);
    }

    public function approve($requestId)
    {
        $authRequest = $this->requestRepository->findRequest($requestId);
        
        $this->requestRepository->updateRequest($authRequest,[
            'status' => 'approved',
            'processed_at' => now()
        ]);

        $this->userRepository->update($authRequest->user,[
            'is_verified' => true,
            'verified_at' => now()
        ]);

        GenericNotificationEvent::dispatch($authRequest->user,'Accept_Request',[]);

        return $this->success('تم الموافقة على طلب التوثيق',['data' => $authRequest]);
    }

    public function reject(Request $request, $requestId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $authRequest = $this->requestRepository->findRequest($requestId);

        $this->requestRepository->updateRequest($authRequest,[
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'processed_at' => now()
        ]);

        GenericNotificationEvent::dispatch(
            $authRequest->user,
            'reject_Request',
            ['rejection_reason' => $request->rejection_reason]
        );
        
        return $this->success('تم رفض طلب التوثيق',['data' => $authRequest]);
    }

    // الحصول على حالة طلبي (للمستخدم العادي)
    public function myRequest()
    {
        $authRequest = $this->requestRepository->getAllUserRequests();

        return $this->success('The Requests :',['data' => $authRequest]);
    }
}
